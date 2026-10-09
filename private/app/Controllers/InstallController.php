<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Installer;
use App\Core\View;

final class InstallController
{
    public function form(array $errors = [], array $old = []): string
    {
        $checks = [
            'PHP 8.1 or newer' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'PDO MySQL extension' => extension_loaded('pdo_mysql'),
            'PDO SQLite extension (optional)' => extension_loaded('pdo_sqlite'),
            'GD image extension' => extension_loaded('gd'),
            'WebP support in GD (optional)' => function_exists('imagewebp'),
            'mbstring extension' => extension_loaded('mbstring'),
            'OpenSSL extension (for SMTP SSL/TLS)' => extension_loaded('openssl'),
            'private/ folder writable' => is_writable(PRIVATE_PATH),
            'public_html/uploads writable' => is_writable(PUBLIC_PATH . '/uploads'),
        ];
        return View::render('install/form', ['errors' => $errors, 'old' => $old, 'checks' => $checks]);
    }

    public function install(): string
    {
        Csrf::verify();
        $errors = Installer::install($_POST);
        if ($errors) {
            return $this->form($errors, $_POST);
        }
        flash('success', 'Installation complete. Please sign in.');
        redirect(admin_url('login'));
    }
}
