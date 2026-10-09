<?php
declare(strict_types=1);

namespace App\Core;

final class Menu
{
    private static array $cache = [];

    /** Active menu items for a location as a tree: [ [item..., 'children' => [...]], ... ] */
    public static function tree(string $location): array
    {
        if (isset(self::$cache[$location])) {
            return self::$cache[$location];
        }
        $rows = DB::all('SELECT * FROM menu_items WHERE location = ? AND is_active = 1 ORDER BY sort_order, id', [$location]);
        $byParent = [];
        foreach ($rows as $r) {
            $byParent[(int) ($r['parent_id'] ?? 0)][] = $r;
        }
        $build = function (int $parent) use (&$build, $byParent): array {
            $out = [];
            foreach ($byParent[$parent] ?? [] as $r) {
                $r['children'] = $build((int) $r['id']);
                $out[] = $r;
            }
            return $out;
        };
        return self::$cache[$location] = $build(0);
    }
}
