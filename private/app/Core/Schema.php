<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Database schema, defined once and rendered for MySQL/MariaDB or SQLite.
 * Column types: pk, str, short, text, long, int, bool, date, datetime.
 * Running create() is idempotent (CREATE TABLE IF NOT EXISTS) and also adds missing columns,
 * so it doubles as a simple migration step after updates.
 */
final class Schema
{
    public static function tables(): array
    {
        $seo = ['meta_title' => 'str', 'meta_description' => 'text'];
        $ts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

        return [
            'users' => [
                'id' => 'pk', 'name' => 'str', 'email' => 'str', 'password_hash' => 'str',
                'role' => 'short', 'is_active' => 'bool', 'last_login_at' => 'datetime', ...$ts,
                '_unique' => ['email'],
            ],
            'settings' => [
                'skey' => 'pkstr', 'svalue' => 'long', 'updated_at' => 'datetime',
            ],
            'media' => [
                'id' => 'pk', 'path' => 'str', 'filename' => 'str', 'mime' => 'short', 'width' => 'int', 'height' => 'int',
                'filesize' => 'int', 'alt_text' => 'str', 'caption' => 'text', 'credit' => 'str', 'license' => 'str',
                'in_gallery' => 'bool', 'gallery_category' => 'short', 'sort_order' => 'int', 'created_at' => 'datetime',
            ],
            'safaris' => [
                'id' => 'pk', 'title' => 'str', 'slug' => 'str', 'category' => 'short', 'tagline' => 'str', 'excerpt' => 'text',
                'body' => 'long', 'highlights' => 'text', 'duration' => 'short', 'timing' => 'str', 'best_season' => 'str',
                'group_size' => 'short', 'image_id' => 'int', 'is_featured' => 'bool', 'sort_order' => 'int', 'status' => 'short',
                ...$seo, ...$ts, '_unique' => ['slug'],
            ],
            'journeys' => [
                'id' => 'pk', 'title' => 'str', 'slug' => 'str', 'duration_label' => 'short', 'tagline' => 'str', 'excerpt' => 'text',
                'body' => 'long', 'itinerary' => 'long', 'inclusions' => 'text', 'image_id' => 'int', 'is_featured' => 'bool',
                'sort_order' => 'int', 'status' => 'short', ...$seo, ...$ts, '_unique' => ['slug'],
            ],
            'posts' => [
                'id' => 'pk', 'title' => 'str', 'slug' => 'str', 'category' => 'short', 'excerpt' => 'text', 'body' => 'long',
                'image_id' => 'int', 'author_name' => 'str', 'reading_time' => 'int', 'status' => 'short',
                'published_at' => 'datetime', ...$seo, ...$ts, '_unique' => ['slug'],
            ],
            'pages' => [
                'id' => 'pk', 'title' => 'str', 'slug' => 'str', 'kicker' => 'str', 'intro' => 'text', 'body' => 'long',
                'image_id' => 'int', 'show_cta' => 'bool', 'status' => 'short', ...$seo, ...$ts, '_unique' => ['slug'],
            ],
            'faqs' => [
                'id' => 'pk', 'question' => 'str', 'answer' => 'text', 'category' => 'short', 'sort_order' => 'int', 'status' => 'short',
            ],
            'team' => [
                'id' => 'pk', 'name' => 'str', 'role' => 'str', 'bio' => 'text', 'image_id' => 'int', 'sort_order' => 'int', 'status' => 'short',
            ],
            'testimonials' => [
                'id' => 'pk', 'author' => 'str', 'origin' => 'str', 'quote' => 'text', 'source' => 'str', 'sort_order' => 'int', 'status' => 'short',
            ],
            'enquiries' => [
                'id' => 'pk', 'code' => 'short', 'type' => 'short', 'full_name' => 'str', 'email' => 'str', 'phone' => 'short',
                'country' => 'short', 'interests' => 'text', 'travel_window' => 'str', 'nights' => 'short', 'adults' => 'int', 'children' => 'int',
                'private_vehicle' => 'short', 'accommodation' => 'str', 'transfer' => 'str', 'message' => 'text', 'source_page' => 'str',
                'utm_source' => 'str', 'utm_medium' => 'str', 'utm_campaign' => 'str', 'ip' => 'short', 'user_agent' => 'str',
                'status' => 'short', 'assigned_user_id' => 'int', 'follow_up_date' => 'date', 'first_contacted_at' => 'datetime',
                ...$ts, '_index' => ['status', 'created_at', 'email'],
            ],
            'enquiry_notes' => [
                'id' => 'pk', 'enquiry_id' => 'int', 'user_id' => 'int', 'note' => 'text', 'created_at' => 'datetime',
                '_index' => ['enquiry_id'],
            ],
            'menu_items' => [
                'id' => 'pk', 'location' => 'short', 'label' => 'str', 'url' => 'str', 'parent_id' => 'int',
                'sort_order' => 'int', 'is_active' => 'bool', 'new_tab' => 'bool',
            ],
            'redirects' => [
                'id' => 'pk', 'from_path' => 'str', 'to_url' => 'str', 'code' => 'int', 'hits' => 'int', 'created_at' => 'datetime',
            ],
            'email_log' => [
                'id' => 'pk', 'to_email' => 'text', 'subject' => 'str', 'status' => 'short', 'error' => 'text',
                'context' => 'short', 'created_at' => 'datetime', '_index' => ['created_at'],
            ],
            'audit_log' => [
                'id' => 'pk', 'user_id' => 'int', 'action' => 'short', 'entity' => 'short', 'entity_id' => 'int',
                'details' => 'text', 'ip' => 'short', 'created_at' => 'datetime', '_index' => ['created_at'],
            ],
            'login_attempts' => [
                'id' => 'pk', 'ip' => 'short', 'email' => 'str', 'created_at' => 'datetime', '_index' => ['ip'],
            ],
        ];
    }

    private static function type(string $t, bool $sqlite): string
    {
        if ($sqlite) {
            return match ($t) {
                'pk' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
                'pkstr' => 'VARCHAR(120) NOT NULL PRIMARY KEY',
                'int', 'bool' => 'INTEGER NULL',
                default => 'TEXT NULL',
            };
        }
        return match ($t) {
            'pk' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'pkstr' => 'VARCHAR(120) NOT NULL PRIMARY KEY',
            'str' => 'VARCHAR(255) NULL',
            'short' => 'VARCHAR(100) NULL',
            'text' => 'TEXT NULL',
            'long' => 'LONGTEXT NULL',
            'int' => 'INT NULL',
            'bool' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'date' => 'DATE NULL',
            'datetime' => 'DATETIME NULL',
        };
    }

    /** Create all tables / add any missing columns. Returns list of statements executed. */
    public static function create(\PDO $pdo): array
    {
        $sqlite = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'sqlite';
        $q = fn (string $n) => $sqlite ? "\"$n\"" : "`$n`";
        $done = [];
        foreach (self::tables() as $table => $cols) {
            $unique = $cols['_unique'] ?? [];
            $index = $cols['_index'] ?? [];
            unset($cols['_unique'], $cols['_index']);

            $defs = [];
            foreach ($cols as $name => $type) {
                $defs[] = $q($name) . ' ' . self::type($type, $sqlite);
            }
            $sql = 'CREATE TABLE IF NOT EXISTS ' . $q($table) . " (\n  " . implode(",\n  ", $defs) . "\n)";
            if (!$sqlite) {
                $sql .= ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
            }
            $pdo->exec($sql);
            $done[] = $sql;

            // Add columns introduced by later versions.
            $existing = self::columns($pdo, $table, $sqlite);
            foreach ($cols as $name => $type) {
                if (!in_array($name, $existing, true)) {
                    $pdo->exec('ALTER TABLE ' . $q($table) . ' ADD COLUMN ' . $q($name) . ' ' . self::type($type, $sqlite));
                }
            }
            foreach ($unique as $col) {
                self::safeExec($pdo, $sqlite
                    ? "CREATE UNIQUE INDEX IF NOT EXISTS ux_{$table}_{$col} ON \"$table\" (\"$col\")"
                    : "CREATE UNIQUE INDEX ux_{$table}_{$col} ON `$table` (`$col`(191))");
            }
            foreach ($index as $col) {
                self::safeExec($pdo, $sqlite
                    ? "CREATE INDEX IF NOT EXISTS ix_{$table}_{$col} ON \"$table\" (\"$col\")"
                    : "CREATE INDEX ix_{$table}_{$col} ON `$table` (`$col`)");
            }
        }
        return $done;
    }

    private static function columns(\PDO $pdo, string $table, bool $sqlite): array
    {
        if ($sqlite) {
            return array_column($pdo->query("PRAGMA table_info(\"$table\")")->fetchAll(\PDO::FETCH_ASSOC), 'name');
        }
        return $pdo->query("SHOW COLUMNS FROM `$table`")->fetchAll(\PDO::FETCH_COLUMN);
    }

    private static function safeExec(\PDO $pdo, string $sql): void
    {
        try {
            $pdo->exec($sql);
        } catch (\PDOException) {
            // index already exists (MySQL has no IF NOT EXISTS for indexes)
        }
    }
}
