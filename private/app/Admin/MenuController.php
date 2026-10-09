<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\DB;

final class MenuController extends BaseController
{
    public const LOCATIONS = [
        'header' => 'Main navigation (header)',
        'footer' => 'Footer — Explore column',
        'footer2' => 'Footer — Company column',
        'legal' => 'Footer — bottom legal links',
    ];

    public function index(): string
    {
        $this->guard('content');
        $items = [];
        foreach (array_keys(self::LOCATIONS) as $loc) {
            $items[$loc] = DB::all('SELECT * FROM menu_items WHERE location = ? ORDER BY sort_order, id', [$loc]);
        }
        $links = $this->linkSuggestions();
        return $this->render('menu', ['items' => $items, 'links' => $links, 'title' => 'Navigation menus']);
    }

    /** Replace all menu items with the posted structure. */
    public function save(): void
    {
        $this->guard('content', 'edit');
        $menus = json_decode((string) input('menus', '{}'), true);
        if (!is_array($menus)) {
            flash('error', 'Invalid menu data.');
            redirect(admin_url('menu'));
        }
        DB::pdo()->beginTransaction();
        try {
            DB::q('DELETE FROM menu_items');
            foreach ($menus as $loc => $list) {
                if (!isset(self::LOCATIONS[$loc]) || !is_array($list)) {
                    continue;
                }
                $parentId = null;
                foreach (array_values($list) as $n => $it) {
                    $label = mb_substr(trim((string) ($it['label'] ?? '')), 0, 120);
                    $url = mb_substr(trim((string) ($it['url'] ?? '')), 0, 250);
                    if ($label === '' || $url === '') {
                        continue;
                    }
                    if (!preg_match('~^(https?://|/|#|mailto:|tel:)~i', $url)) {
                        $url = '/' . ltrim($url, '/');
                    }
                    $isChild = $loc === 'header' && !empty($it['child']) && $parentId !== null;
                    $id = DB::insert('menu_items', [
                        'location' => $loc, 'label' => $label, 'url' => $url,
                        'parent_id' => $isChild ? $parentId : null, 'sort_order' => $n + 1,
                        'is_active' => empty($it['hidden']) ? 1 : 0, 'new_tab' => !empty($it['new_tab']) ? 1 : 0,
                    ]);
                    if (!$isChild) {
                        $parentId = $id;
                    }
                }
            }
            DB::pdo()->commit();
        } catch (\Throwable $e) {
            DB::pdo()->rollBack();
            throw $e;
        }
        Audit::log('update', 'menu_items');
        flash('success', 'Menus saved.');
        redirect(admin_url('menu'));
    }

    private function linkSuggestions(): array
    {
        $links = ['/' => 'Home', '/safaris/' => 'Expeditions (all)', '/journeys/' => 'Journeys (all)', '/journal/' => 'Field Journal', '/gallery/' => 'Gallery', '/faq/' => 'FAQ', '/about/' => 'About', '/contact/' => 'Contact', '/plan-your-journey/' => 'Plan Your Journey'];
        foreach (DB::all('SELECT title, slug FROM safaris ORDER BY sort_order') as $r) {
            $links['/safaris/' . $r['slug'] . '/'] = 'Safari: ' . $r['title'];
        }
        foreach (DB::all('SELECT title, slug FROM journeys ORDER BY sort_order') as $r) {
            $links['/journeys/' . $r['slug'] . '/'] = 'Journey: ' . $r['title'];
        }
        foreach (DB::all('SELECT title, slug FROM pages ORDER BY slug') as $r) {
            $links['/' . $r['slug'] . '/'] = 'Page: ' . $r['title'];
        }
        return $links;
    }
}
