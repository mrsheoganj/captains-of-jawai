<?php
declare(strict_types=1);

namespace App\Core;

/** Key/value site settings stored in the `settings` table, with defaults from SettingDefs. */
final class Settings
{
    private static ?array $cache = null;

    private static function loadAll(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        self::$cache = [];
        if (!Config::installed()) {
            return self::$cache;
        }
        try {
            foreach (DB::all('SELECT skey, svalue FROM settings') as $row) {
                self::$cache[$row['skey']] = $row['svalue'];
            }
        } catch (\Throwable $e) {
            error_log('Settings load failed: ' . $e->getMessage());
        }
        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::loadAll();
        if (array_key_exists($key, $all)) {
            $value = $all[$key];
            if (SettingDefs::isSecret($key) && $value !== '' && $value !== null) {
                return Crypto::decrypt((string) $value);
            }
            return $value;
        }
        // Built-in defaults (SettingDefs) win over call-site fallbacks.
        return SettingDefs::default($key) ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $value = is_array($value) ? json_encode($value) : (string) $value;
        if (SettingDefs::isSecret($key) && $value !== '') {
            $value = Crypto::encrypt($value);
        }
        $now = now();
        if (DB::val('SELECT COUNT(*) FROM settings WHERE skey = ?', [$key])) {
            DB::update('settings', ['svalue' => $value, 'updated_at' => $now], 'skey = ?', [$key]);
        } else {
            DB::insert('settings', ['skey' => $key, 'svalue' => $value, 'updated_at' => $now]);
        }
        self::$cache = null;
    }

    public static function setMany(array $values): void
    {
        DB::pdo()->beginTransaction();
        try {
            foreach ($values as $k => $v) {
                self::set($k, $v);
            }
            DB::pdo()->commit();
        } catch (\Throwable $e) {
            DB::pdo()->rollBack();
            throw $e;
        }
    }

    /** Lines of a multi-line setting, trimmed, empty lines removed. */
    public static function lines(string $key): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) self::get($key, '')))));
    }

    public static function bool(string $key): bool
    {
        return in_array((string) self::get($key, '0'), ['1', 'true', 'on', 'yes'], true);
    }

    public static function flush(): void
    {
        self::$cache = null;
    }
}
