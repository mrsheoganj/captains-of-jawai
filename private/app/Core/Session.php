<?php
declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        session_name('coj_session');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $dir = STORAGE_PATH . '/sessions';
        if (!is_dir($dir)) {
            @mkdir($dir, 0700, true);
        }
        if (is_dir($dir) && is_writable($dir)) {
            session_save_path($dir);
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', '7200');
        session_start();
        // Age out flash data from the previous request.
        $_SESSION['_flash_now'] = $_SESSION['_flash_next'] ?? [];
        $_SESSION['_flash_next'] = [];
        $_SESSION['_old_now'] = $_SESSION['_old_next'] ?? [];
        $_SESSION['_old_next'] = [];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash_next'][] = ['type' => $type, 'message' => $message];
    }

    /** Flash messages to show on this request (set during the previous one or this one). */
    public static function messages(): array
    {
        $m = array_merge($_SESSION['_flash_now'] ?? [], $_SESSION['_flash_next'] ?? []);
        $_SESSION['_flash_now'] = [];
        $_SESSION['_flash_next'] = [];
        return $m;
    }

    public static function keepInput(array $data): void
    {
        unset($data['_token'], $data['password'], $data['smtp_password']);
        $_SESSION['_old_next'] = $data;
    }

    public static function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old_now'][$key] ?? $default;
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
