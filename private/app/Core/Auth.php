<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public const ROLES = [
        'super_admin' => 'Super Admin',
        'manager' => 'Expedition Manager',
        'sales' => 'Sales / Concierge',
        'editor' => 'Content Editor',
        'read_only' => 'Read Only',
    ];

    /** area => [roles that can view, roles that can edit] */
    private const AREAS = [
        'dashboard' => [['*'], ['*']],
        'enquiries' => [['super_admin', 'manager', 'sales', 'read_only'], ['super_admin', 'manager', 'sales']],
        'content' => [['super_admin', 'manager', 'editor', 'read_only'], ['super_admin', 'manager', 'editor']],
        'media' => [['super_admin', 'manager', 'editor', 'read_only'], ['super_admin', 'manager', 'editor']],
        'seo' => [['super_admin', 'manager', 'editor', 'read_only'], ['super_admin', 'manager', 'editor']],
        'settings' => [['super_admin', 'manager'], ['super_admin', 'manager']],
        'email' => [['super_admin'], ['super_admin']],
        'users' => [['super_admin'], ['super_admin']],
        'audit' => [['super_admin', 'manager'], ['super_admin']],
    ];

    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;
    private const IDLE_MINUTES = 60;

    private static ?array $user = null;

    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }
        $id = Session::get('uid');
        if (!$id) {
            return null;
        }
        if ((time() - (int) Session::get('last_seen', 0)) > self::IDLE_MINUTES * 60) {
            self::logout();
            return null;
        }
        Session::put('last_seen', time());
        $u = DB::one('SELECT id, name, email, role, is_active FROM users WHERE id = ?', [$id]);
        if (!$u || !(int) $u['is_active']) {
            self::logout();
            return null;
        }
        return self::$user = $u;
    }

    public static function id(): ?int
    {
        return isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function role(): string
    {
        return self::user()['role'] ?? '';
    }

    public static function can(string $area, string $mode = 'view'): bool
    {
        $role = self::role();
        if ($role === '') {
            return false;
        }
        [$view, $edit] = self::AREAS[$area] ?? [[], []];
        $allowed = $mode === 'edit' ? $edit : $view;
        return in_array('*', $allowed, true) || in_array($role, $allowed, true);
    }

    public static function require(string $area, string $mode = 'view'): void
    {
        if (!self::check()) {
            Session::put('intended', $_SERVER['REQUEST_URI'] ?? admin_url());
            redirect(admin_url('login'));
        }
        if (!self::can($area, $mode)) {
            http_response_code(403);
            echo View::render('admin/forbidden', ['title' => 'Access denied'], 'admin/layout');
            exit;
        }
    }

    public static function lockedOut(string $ip): bool
    {
        $since = date('Y-m-d H:i:s', time() - self::LOCK_MINUTES * 60);
        return (int) DB::val('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND created_at > ?', [$ip, $since]) >= self::MAX_ATTEMPTS;
    }

    public static function attempt(string $email, string $password): bool|string
    {
        $ip = client_ip();
        if (self::lockedOut($ip)) {
            return 'Too many failed attempts. Please wait ' . self::LOCK_MINUTES . ' minutes and try again.';
        }
        $u = DB::one('SELECT * FROM users WHERE email = ?', [strtolower(trim($email))]);
        if (!$u || !(int) $u['is_active'] || !password_verify($password, (string) $u['password_hash'])) {
            DB::insert('login_attempts', ['ip' => $ip, 'email' => mb_substr($email, 0, 200), 'created_at' => now()]);
            Audit::log('login_failed', 'users', $u ? (int) $u['id'] : null, ['email' => $email]);
            return false;
        }
        if (password_needs_rehash($u['password_hash'], self::algo())) {
            DB::update('users', ['password_hash' => self::hash($password)], 'id = ?', [$u['id']]);
        }
        Session::regenerate();
        Session::put('uid', (int) $u['id']);
        Session::put('last_seen', time());
        DB::delete('login_attempts', 'ip = ?', [$ip]);
        DB::update('users', ['last_login_at' => now()], 'id = ?', [$u['id']]);
        self::$user = null;
        Audit::log('login', 'users', (int) $u['id']);
        return true;
    }

    public static function logout(): void
    {
        self::$user = null;
        Session::forget('uid');
        Session::regenerate();
    }

    public static function algo(): string|int
    {
        return defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
    }

    public static function hash(string $password): string
    {
        return password_hash($password, self::algo());
    }
}
