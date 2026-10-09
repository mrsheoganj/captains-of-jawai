<?php
/**
 * Command-line installer / upgrader.
 *   php private/cli/install.php --driver=sqlite --email=you@example.com --password=secret123 --name="Your Name"
 *   php private/cli/install.php --migrate      (create missing tables/columns after an update)
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}
define('PRIVATE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', dirname(__DIR__, 2) . '/public_html');
require PRIVATE_PATH . '/app/bootstrap.php';

$opt = getopt('', ['driver::', 'host::', 'port::', 'db::', 'user::', 'pass::', 'email::', 'password::', 'name::', 'url::', 'admin-path::', 'migrate', 'no-demo']);

if (isset($opt['migrate'])) {
    if (!App\Core\Config::installed()) {
        exit("Not installed yet.\n");
    }
    App\Core\Schema::create(App\Core\DB::pdo());
    App\Core\Demo::assignImages();
    echo "Schema up to date; empty image slots filled.\n";
    exit(0);
}

$errors = App\Core\Installer::install([
    'db_driver' => $opt['driver'] ?? 'sqlite',
    'db_host' => $opt['host'] ?? 'localhost',
    'db_port' => $opt['port'] ?? 3306,
    'db_name' => $opt['db'] ?? '',
    'db_user' => $opt['user'] ?? '',
    'db_pass' => $opt['pass'] ?? '',
    'admin_name' => $opt['name'] ?? 'Site Admin',
    'admin_email' => $opt['email'] ?? '',
    'admin_password' => $opt['password'] ?? '',
    'base_url' => $opt['url'] ?? '',
    'admin_path' => $opt['admin-path'] ?? 'admin',
    'demo_data' => !isset($opt['no-demo']),
]);
if ($errors) {
    foreach ($errors as $k => $v) {
        fwrite(STDERR, "$k: $v\n");
    }
    exit(1);
}
echo "Installed. Log in at /" . App\Core\Config::get('admin_path') . "/login\n";
