<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Html;
use App\Core\Schema;

final class ContentController extends BaseController
{
    private function resource(string $res): array
    {
        $r = Resources::get($res);
        if (!$r) {
            http_response_code(404);
            exit('Unknown content type');
        }
        $r['key'] = $res;
        return $r;
    }

    public function index(string $res): string
    {
        $r = $this->resource($res);
        $this->guard($r['area']);
        $q = str_input('q', 100);
        $status = str_input('status', 30);
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (' . implode(' OR ', array_map(fn ($c) => "$c LIKE ?", $r['search'])) . ')';
            array_push($params, ...array_fill(0, count($r['search']), '%' . $q . '%'));
        }
        if ($status !== '' && isset($r['fields']['status'])) {
            $where .= ' AND status = ?';
            $params[] = $status;
        }
        $page = max(1, (int) input('page', 1));
        $per = 50;
        $total = (int) DB::val("SELECT COUNT(*) FROM {$r['table']} WHERE $where", $params);
        $rows = DB::all("SELECT * FROM {$r['table']} WHERE $where ORDER BY {$r['order']} LIMIT $per OFFSET " . (($page - 1) * $per), $params);
        return $this->render('content/index', ['r' => $r, 'rows' => $rows, 'q' => $q, 'status' => $status, 'total' => $total, 'page' => $page, 'per' => $per, 'title' => $r['label']]);
    }

    public function create(string $res): string
    {
        $r = $this->resource($res);
        $this->guard($r['area'], 'edit');
        $row = ['status' => 'draft', 'code' => 301, 'show_cta' => 1];
        if (array_key_exists('sort_order', Schema::tables()[$r['table']])) {
            $row['sort_order'] = (int) DB::val("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM {$r['table']}");
        }
        if ($res === 'posts') {
            $row['published_at'] = now();
            $row['author_name'] = Auth::user()['name'] ?? '';
        }
        return $this->form($r, $row, null);
    }

    public function edit(string $res, string $id): string
    {
        $r = $this->resource($res);
        $this->guard($r['area']);
        $row = DB::one("SELECT * FROM {$r['table']} WHERE id = ?", [(int) $id]);
        if (!$row) {
            flash('error', $r['singular'] . ' not found.');
            redirect(admin_url('content/' . $res));
        }
        return $this->form($r, $row, (int) $id);
    }

    private function form(array $r, array $row, ?int $id, array $errors = []): string
    {
        $lists = [];
        if (isset($r['fields']['category'])) {
            $lists['categories'] = array_column(DB::all("SELECT DISTINCT category FROM {$r['table']} WHERE category <> '' ORDER BY category"), 'category');
        }
        return $this->render('content/form', [
            'r' => $r, 'row' => $row, 'id' => $id, 'errors' => $errors, 'lists' => $lists,
            'title' => ($id ? 'Edit ' : 'New ') . strtolower($r['singular']),
            'canEdit' => Auth::can($r['area'], 'edit'),
        ]);
    }

    public function store(string $res): string
    {
        return $this->save($res, null);
    }

    public function update(string $res, string $id): string
    {
        return $this->save($res, (int) $id);
    }

    private function save(string $res, ?int $id): string
    {
        $r = $this->resource($res);
        $this->guard($r['area'], 'edit');
        [$data, $errors] = $this->collect($r, $id);
        if ($errors) {
            flash('error', 'Please fix the highlighted fields.');
            return $this->form($r, array_merge($_POST, ['id' => $id]), $id, $errors);
        }
        $cols = Schema::tables()[$r['table']];
        if (array_key_exists('updated_at', $cols)) {
            $data['updated_at'] = now();
        }
        if ($id) {
            DB::update($r['table'], $data, 'id = ?', [$id]);
            Audit::log('update', $r['table'], $id, ['title' => $data[$r['title']] ?? '']);
            flash('success', $r['singular'] . ' saved.');
        } else {
            if (array_key_exists('created_at', $cols)) {
                $data['created_at'] = now();
            }
            $id = DB::insert($r['table'], $data);
            Audit::log('create', $r['table'], $id, ['title' => $data[$r['title']] ?? '']);
            flash('success', $r['singular'] . ' created.');
        }
        redirect(admin_url("content/$res/$id"));
    }

    /** Validate and normalise posted fields. @return array{0: array, 1: array} */
    private function collect(array $r, ?int $id): array
    {
        $data = [];
        $errors = [];
        foreach ($r['fields'] as $name => [$label, $type]) {
            $opt = $r['fields'][$name][2] ?? [];
            $raw = $_POST[$name] ?? null;
            $value = match ($type) {
                'bool' => !empty($raw) ? 1 : 0,
                'number' => (int) $raw,
                'image' => (int) $raw > 0 ? (int) $raw : null,
                'rich' => Html::clean((string) $raw),
                'datetime' => $raw ? date('Y-m-d H:i:s', strtotime((string) $raw) ?: time()) : null,
                'select' => array_key_exists((string) $raw, $opt['choices'] ?? []) ? (string) $raw : (string) array_key_first($opt['choices'] ?? ['' => '']),
                'slug' => slugify((string) $raw),
                default => trim(str_replace("\r\n", "\n", (string) $raw)),
            };
            if (!empty($opt['required']) && ($value === '' || $value === null)) {
                $errors[$name] = $label . ' is required.';
            }
            $data[$name] = $value;
        }
        foreach ($r['fields'] as $name => $f) {
            if ($f[1] === 'slug') {
                $from = $f[2]['from'] ?? 'title';
                if ($data[$name] === '') {
                    $data[$name] = slugify((string) ($data[$from] ?? '')) ?: 'item-' . time();
                }
                $base = $data[$name];
                $n = 2;
                while (DB::val("SELECT COUNT(*) FROM {$r['table']} WHERE $name = ? AND id <> ?", [$data[$name], $id ?? 0])) {
                    $data[$name] = $base . '-' . $n++;
                }
            }
        }
        if (!empty($r['seo'])) {
            $data['meta_title'] = mb_substr(str_input('meta_title', 255), 0, 255);
            $data['meta_description'] = mb_substr(str_input('meta_description', 500), 0, 500);
        }
        if ($r['table'] === 'posts') {
            if (empty($data['reading_time'])) {
                $data['reading_time'] = max(1, (int) round(str_word_count(strip_tags((string) $data['body'])) / 200));
            }
            $data['published_at'] ??= now();
        }
        if ($r['table'] === 'redirects') {
            $data['from_path'] = '/' . ltrim((string) parse_url($data['from_path'], PHP_URL_PATH), '/');
            $data['code'] = (int) $data['code'] ?: 301;
            if ($data['from_path'] === '/' || str_starts_with($data['from_path'], admin_path())) {
                $errors['from_path'] = 'This path cannot be redirected.';
            }
            if (rtrim($data['from_path'], '/') === rtrim((string) parse_url($data['to_url'], PHP_URL_PATH), '/') && !parse_url($data['to_url'], PHP_URL_HOST)) {
                $errors['to_url'] = 'A redirect cannot point to itself.';
            }
        }
        return [$data, $errors];
    }

    public function delete(string $res, string $id): void
    {
        $r = $this->resource($res);
        $this->guard($r['area'], 'edit');
        $row = DB::one("SELECT * FROM {$r['table']} WHERE id = ?", [(int) $id]);
        if ($row) {
            DB::delete($r['table'], 'id = ?', [(int) $id]);
            Audit::log('delete', $r['table'], (int) $id, ['title' => $row[$r['title']] ?? '']);
            flash('success', $r['singular'] . ' deleted.');
        }
        redirect(admin_url('content/' . $res));
    }

    public function duplicate(string $res, string $id): void
    {
        $r = $this->resource($res);
        $this->guard($r['area'], 'edit');
        $row = DB::one("SELECT * FROM {$r['table']} WHERE id = ?", [(int) $id]);
        if (!$row) {
            redirect(admin_url('content/' . $res));
        }
        unset($row['id']);
        $row[$r['title']] = $row[$r['title']] . ' (copy)';
        if (isset($row['slug'])) {
            $base = $row['slug'] . '-copy';
            $row['slug'] = $base;
            $n = 2;
            while (DB::val("SELECT COUNT(*) FROM {$r['table']} WHERE slug = ?", [$row['slug']])) {
                $row['slug'] = $base . '-' . $n++;
            }
        }
        if (isset($row['status'])) {
            $row['status'] = 'draft';
        }
        foreach (['created_at', 'updated_at'] as $c) {
            if (array_key_exists($c, $row)) {
                $row[$c] = now();
            }
        }
        if (array_key_exists('hits', $row)) {
            $row['hits'] = 0;
        }
        $new = DB::insert($r['table'], $row);
        Audit::log('duplicate', $r['table'], $new);
        flash('success', 'Copy created as a draft.');
        redirect(admin_url("content/$res/$new"));
    }

    public function reorder(string $res): void
    {
        $r = $this->resource($res);
        $this->guard($r['area'], 'edit');
        $ids = array_map('intval', (array) input('ids', []));
        foreach ($ids as $n => $rowId) {
            DB::update($r['table'], ['sort_order' => $n + 1], 'id = ?', [$rowId]);
        }
        json_out(['success' => true]);
    }
}
