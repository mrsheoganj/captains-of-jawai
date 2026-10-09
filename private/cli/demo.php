<?php
/**
 * One-command demo: installs the site with SQLite (no database server needed), demo photos everywhere
 * and sample CRM enquiries, then prints how to open it.
 *   php private/cli/demo.php            (install if needed)
 *   php private/cli/demo.php --reset    (wipe the SQLite demo and reinstall fresh)
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}
define('PRIVATE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', dirname(__DIR__, 2) . '/public_html');
$email = 'demo@captainsofjawai.com';
$password = 'Demo@Jawai2026';
$port = 8080;

if (in_array('--reset', $argv, true)) {
    $cfg = is_file(PRIVATE_PATH . '/config.php') ? require PRIVATE_PATH . '/config.php' : [];
    if (($cfg['db']['driver'] ?? 'sqlite') !== 'sqlite') {
        exit("Refusing to reset: this install uses MySQL. Delete private/config.php manually if you are sure.\n");
    }
    @unlink(PRIVATE_PATH . '/config.php');
    foreach (glob(PRIVATE_PATH . '/storage/database.sqlite*') ?: [] as $f) {
        @unlink($f);
    }
    echo "Previous demo removed.\n";
}

require PRIVATE_PATH . '/app/bootstrap.php';

if (!App\Core\Config::installed()) {
    $errors = App\Core\Installer::install([
        'db_driver' => 'sqlite', 'admin_name' => 'Demo Admin', 'admin_email' => $email, 'admin_password' => $password,
        'base_url' => "http://localhost:$port", 'admin_path' => 'admin', 'demo_data' => true, 'site_email' => 'contact@captainsofjawai.com',
    ]);
    if ($errors) {
        foreach ($errors as $k => $v) {
            fwrite(STDERR, "$k: $v\n");
        }
        exit(1);
    }
    // Demo: log emails instead of trying to reach a mail server.
    App\Core\Settings::set('mail_transport', 'log');
    echo "Demo installed.\n";
} else {
    App\Core\Demo::assignImages();
    echo "Already installed — empty image slots filled.\n";
}

echo <<<TXT

  Start the demo server:
    php -S localhost:$port -t public_html private/cli/dev-router.php

  Website:  http://localhost:$port/
  Admin:    http://localhost:$port/admin
  Login:    $email  /  $password

TXT;
