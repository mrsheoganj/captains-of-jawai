<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Media;

final class MediaController extends BaseController
{
    public function index(): string
    {
        $this->guard('media');
        $q = str_input('q', 100);
        $filter = str_input('filter', 20);
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (filename LIKE ? OR alt_text LIKE ? OR caption LIKE ?)';
            array_push($params, "%$q%", "%$q%", "%$q%");
        }
        if ($filter === 'gallery') {
            $where .= ' AND in_gallery = 1';
        } elseif ($filter === 'placeholder') {
            $where .= " AND path LIKE 'assets/photos/%'";
        } elseif ($filter === 'noalt') {
            $where .= " AND (alt_text IS NULL OR alt_text = '')";
        }
        $items = DB::all("SELECT * FROM media WHERE $where ORDER BY id DESC", $params);
        $cats = array_column(DB::all("SELECT DISTINCT gallery_category FROM media WHERE gallery_category <> '' ORDER BY gallery_category"), 'gallery_category');
        return $this->render('media', ['items' => $items, 'q' => $q, 'filter' => $filter, 'cats' => $cats, 'title' => 'Media library', 'canEdit' => Auth::can('media', 'edit')]);
    }

    /** JSON list for the image picker modal. */
    public function picker(): void
    {
        $this->guard('media');
        $q = str_input('q', 100);
        $rows = $q !== ''
            ? DB::all('SELECT * FROM media WHERE filename LIKE ? OR alt_text LIKE ? ORDER BY id DESC LIMIT 200', ["%$q%", "%$q%"])
            : DB::all('SELECT * FROM media ORDER BY id DESC LIMIT 200');
        json_out(['items' => array_map(fn ($m) => ['id' => (int) $m['id'], 'url' => Media::url($m, 'sm'), 'alt' => $m['alt_text'], 'name' => $m['filename']], $rows)]);
    }

    public function upload(): void
    {
        $this->guard('media', 'edit');
        $files = $_FILES['files'] ?? $_FILES['file'] ?? null;
        $results = [];
        if ($files && is_array($files['name'])) {
            foreach ($files['name'] as $i => $name) {
                $results[] = Media::upload([
                    'name' => $name, 'type' => $files['type'][$i], 'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i], 'size' => $files['size'][$i],
                ], ['in_gallery' => (int) input('in_gallery', 0)]);
            }
        } elseif ($files) {
            $results[] = Media::upload($files, ['in_gallery' => (int) input('in_gallery', 0)]);
        } else {
            $results[] = 'No file received. The file may exceed the server limit (' . ini_get('post_max_size') . ').';
        }
        $ok = array_values(array_filter($results, 'is_int'));
        $errors = array_values(array_filter($results, 'is_string'));
        foreach ($ok as $id) {
            Audit::log('upload', 'media', $id);
        }
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            json_out([
                'success' => (bool) $ok,
                'items' => array_map(fn ($id) => ['id' => $id, 'url' => Media::url($id, 'sm'), 'alt' => Media::find($id)['alt_text'] ?? ''], $ok),
                'errors' => $errors,
            ], $ok ? 200 : 422);
        }
        if ($ok) {
            flash('success', count($ok) . ' image(s) uploaded. Remember to add descriptive alt text.');
        }
        foreach ($errors as $err) {
            flash('error', $err);
        }
        redirect(admin_url('media'));
    }

    public function update(string $id): void
    {
        $this->guard('media', 'edit');
        $m = Media::find((int) $id);
        if ($m) {
            DB::update('media', [
                'alt_text' => str_input('alt_text', 250),
                'caption' => str_input('caption', 1000),
                'credit' => str_input('credit', 250),
                'license' => str_input('license', 250),
                'in_gallery' => input('in_gallery') ? 1 : 0,
                'gallery_category' => str_input('gallery_category', 100),
                'sort_order' => (int) input('sort_order', 0),
            ], 'id = ?', [$m['id']]);
            Audit::log('update', 'media', (int) $m['id']);
            flash('success', 'Image details saved.');
        }
        redirect(admin_url('media') . '#m' . (int) $id);
    }

    public function delete(string $id): void
    {
        $this->guard('media', 'edit');
        $uses = Media::usages((int) $id);
        if ($uses && !input('force')) {
            flash('error', 'This image is still used: ' . implode('; ', $uses) . '. Replace it there first, or delete anyway from the image details.');
            redirect(admin_url('media') . '#m' . (int) $id);
        }
        Media::delete((int) $id);
        Audit::log('delete', 'media', (int) $id);
        flash('success', 'Image deleted.');
        redirect(admin_url('media'));
    }
}
