<?php
declare(strict_types=1);

namespace App\Core;

final class Installer
{
    /** @return array<string,string> errors (empty on success) */
    public static function install(array $in): array
    {
        $errors = [];
        $driver = ($in['db_driver'] ?? 'mysql') === 'sqlite' ? 'sqlite' : 'mysql';
        $db = ['driver' => $driver];
        if ($driver === 'mysql') {
            $db += [
                'host' => trim($in['db_host'] ?? 'localhost') ?: 'localhost',
                'port' => (int) ($in['db_port'] ?? 3306) ?: 3306,
                'name' => trim($in['db_name'] ?? ''),
                'user' => trim($in['db_user'] ?? ''),
                'pass' => (string) ($in['db_pass'] ?? ''),
            ];
            if ($db['name'] === '' || $db['user'] === '') {
                $errors['db_name'] = 'Database name and user are required (create them in cPanel → MySQL® Databases).';
            }
        } else {
            $db['path'] = STORAGE_PATH . '/database.sqlite';
        }
        $name = trim($in['admin_name'] ?? '');
        $email = strtolower(trim($in['admin_email'] ?? ''));
        $pass = (string) ($in['admin_password'] ?? '');
        if ($name === '') {
            $errors['admin_name'] = 'Your name is required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['admin_email'] = 'A valid email is required.';
        }
        if (strlen($pass) < 10) {
            $errors['admin_password'] = 'Use at least 10 characters.';
        }
        $adminPath = slugify((string) ($in['admin_path'] ?? 'admin')) ?: 'admin';
        if (in_array($adminPath, ['api', 'assets', 'uploads', 'safaris', 'journal', 'journeys'], true) || str_contains($adminPath, '/')) {
            $errors['admin_path'] = 'Choose a different admin path.';
        }
        if (!is_writable(PRIVATE_PATH)) {
            $errors['general'] = 'The folder ' . PRIVATE_PATH . ' is not writable, so config.php cannot be saved.';
        }
        if ($errors) {
            return $errors;
        }

        try {
            $pdo = DB::connect($db);
        } catch (\PDOException $e) {
            return ['db_name' => 'Could not connect to the database: ' . $e->getMessage()];
        }

        $config = [
            'installed' => true,
            'installed_at' => date('c'),
            'app_key' => bin2hex(random_bytes(32)),
            'base_url' => rtrim(trim($in['base_url'] ?? ''), '/'),
            'admin_path' => $adminPath,
            'timezone' => 'Asia/Kolkata',
            'debug' => false,
            'db' => $db,
        ];

        DB::use($pdo);
        Schema::create($pdo);
        Config::load($config);

        $existing = DB::one('SELECT id FROM users WHERE email = ?', [$email]);
        if ($existing) {
            DB::update('users', ['name' => $name, 'password_hash' => Auth::hash($pass), 'role' => 'super_admin', 'is_active' => 1, 'updated_at' => now()], 'id = ?', [$existing['id']]);
        } else {
            DB::insert('users', ['name' => $name, 'email' => $email, 'password_hash' => Auth::hash($pass), 'role' => 'super_admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        Seeder::run(!empty($in['demo_data']));
        if (!empty($in['site_email']) && filter_var($in['site_email'], FILTER_VALIDATE_EMAIL)) {
            Settings::setMany(['contact_email' => $in['site_email'], 'notify_recipients' => $in['site_email'], 'mail_reply_to' => $in['site_email']]);
        }

        if (!Config::write($config)) {
            return ['general' => 'Could not write ' . CONFIG_FILE];
        }
        return [];
    }
}
