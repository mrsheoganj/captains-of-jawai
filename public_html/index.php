<?php
/**
 * Captains of Jawai — single front controller.
 * Every request (except real files under /assets and /uploads) is routed here by .htaccess.
 */
declare(strict_types=1);

define('PUBLIC_PATH', __DIR__);

// The private application folder normally lives one level above public_html (outside the web root).
$candidates = [
    dirname(__DIR__) . '/private',
    __DIR__ . '/../private',
    __DIR__ . '/private',
];
foreach ($candidates as $dir) {
    if (is_file($dir . '/app/bootstrap.php')) {
        define('PRIVATE_PATH', realpath($dir));
        break;
    }
}
if (!defined('PRIVATE_PATH')) {
    http_response_code(500);
    exit('Application folder "private" not found. Upload it next to public_html.');
}

require PRIVATE_PATH . '/app/bootstrap.php';

App\Core\App::run();
