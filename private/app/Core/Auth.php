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
        'reviews' => [['super_admin', 'manager', 'sales', 'editor', 'read_only'], ['super_admin', 'manager', 'sales', 'editor']],
    ];

    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;
    /** Signed-in admins stay signed in on that device until they sign out (renewed on every visit). */
    private const REMEMBER_DAYS = 365;
    private const REMEMBER_COOKIE = 'coj_remember';

    private static ?array $user = null;

    /** CRM-only mode (/pk-crm): the same logins, but only the dashboard and enquiries are available. */
    public const CRM_PATH = 'pk-crm';
    private const CRM_AREAS = ['dashboard', 'enquiries', 'reviews'];
    private static bool $crm = false;

    public static function enterCrm(): void
    {
        self::$crm = true;
    }

    public static function crm(): bool
    {
        return self::$crm;
    }

    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }
        $id = Session::get('uid') ?: self::fromRememberCookie();
        if (!$id) {
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
        if ($role === '' || (self::$crm && !in_array($area, self::CRM_AREAS, true))) {
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
        self::remember((int) $u['id']);
        DB::delete('login_attempts', 'ip = ?', [$ip]);
        DB::update('users', ['last_login_at' => now()], 'id = ?', [$u['id']]);
        self::$user = null;
        Audit::log('login', 'users', (int) $u['id']);
        return true;
    }

    public static function logout(): void
    {
        self::forgetThisDevice();
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

    /** Issue a long-lived sign-in cookie for this device (selector:validator; only a hash is stored). */
    public static function remember(int $uid): void
    {
        try {
            self::forgetThisDevice();
            $selector = bin2hex(random_bytes(9));
            $validator = bin2hex(random_bytes(32));
            DB::insert('remember_tokens', ['user_id' => $uid, 'selector' => $selector, 'token_hash' => hash('sha256', $validator),
                'expires_at' => date('Y-m-d H:i:s', time() + self::REMEMBER_DAYS * 86400), 'created_at' => now()]);
            self::setCookie($selector . ':' . $validator, time() + self::REMEMBER_DAYS * 86400);
            if (random_int(1, 20) === 1) {
                DB::delete('remember_tokens', 'expires_at < ?', [now()]);
            }
        } catch (\Throwable $e) {
            error_log('Remember me: ' . $e->getMessage());
        }
    }

    /** Sign out every device of a user (after a password change or when an admin resets it). */
    public static function forgetDevices(int $uid): void
    {
        DB::delete('remember_tokens', 'user_id = ?', [$uid]);
    }

    private static function fromRememberCookie(): ?int
    {
        $raw = (string) ($_COOKIE[self::REMEMBER_COOKIE] ?? '');
        if (!preg_match('/^([a-f0-9]{18}):([a-f0-9]{64})$/', $raw, $m)) {
            return null;
        }
        try {
            $row = DB::one('SELECT * FROM remember_tokens WHERE selector = ?', [$m[1]]);
        } catch (\Throwable) {
            return null;
        }
        if (!$row || (string) $row['expires_at'] < now() || !hash_equals((string) $row['token_hash'], hash('sha256', $m[2]))) {
            self::setCookie('', time() - 3600);
            return null;
        }
        // Valid: start a fresh session and extend the cookie for another year.
        Session::regenerate();
        Session::put('uid', (int) $row['user_id']);
        Session::put('last_seen', time());
        DB::update('remember_tokens', ['expires_at' => date('Y-m-d H:i:s', time() + self::REMEMBER_DAYS * 86400)], 'id = ?', [$row['id']]);
        self::setCookie($raw, time() + self::REMEMBER_DAYS * 86400);
        return (int) $row['user_id'];
    }

    private static function forgetThisDevice(): void
    {
        $raw = (string) ($_COOKIE[self::REMEMBER_COOKIE] ?? '');
        if (preg_match('/^([a-f0-9]{18}):/', $raw, $m)) {
            try {
                DB::delete('remember_tokens', 'selector = ?', [$m[1]]);
            } catch (\Throwable) {
            }
        }
        if ($raw !== '') {
            self::setCookie('', time() - 3600);
        }
    }

    private static function setCookie(string $value, int $expires): void
    {
        if (headers_sent()) {
            return;
        }
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        setcookie(self::REMEMBER_COOKIE, $value, ['expires' => $expires, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
        $_COOKIE[self::REMEMBER_COOKIE] = $value;
    }
}
