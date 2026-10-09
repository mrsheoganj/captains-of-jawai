<?php
declare(strict_types=1);

const APP_VERSION = '2.0.0';

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(__DIR__, 2) . '/public_html');
}
if (!defined('PRIVATE_PATH')) {
    define('PRIVATE_PATH', dirname(__DIR__));
}
define('STORAGE_PATH', PRIVATE_PATH . '/storage');
define('VIEW_PATH', PRIVATE_PATH . '/views');
define('CONFIG_FILE', PRIVATE_PATH . '/config.php');

spl_autoload_register(function (string $class): void {
    if (str_starts_with($class, 'App\\')) {
        $file = PRIVATE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    } elseif (str_starts_with($class, 'PHPMailer\\PHPMailer\\')) {
        $file = PRIVATE_PATH . '/lib/PHPMailer/' . substr($class, 20) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

require PRIVATE_PATH . '/app/helpers.php';

foreach ([STORAGE_PATH, STORAGE_PATH . '/logs', STORAGE_PATH . '/cache'] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

$config = is_file(CONFIG_FILE) ? require CONFIG_FILE : null;
App\Core\Config::load(is_array($config) ? $config : null);

date_default_timezone_set(App\Core\Config::get('timezone', 'Asia/Kolkata'));

$debug = (bool) App\Core\Config::get('debug', false);
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');

set_exception_handler(function (Throwable $e) use ($debug): void {
    error_log('[' . date('c') . '] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString());
    if (!headers_sent()) {
        http_response_code(500);
    }
    if ($debug) {
        echo '<pre style="padding:20px;white-space:pre-wrap">' . htmlspecialchars((string) $e) . '</pre>';
        return;
    }
    $view = VIEW_PATH . '/errors/500.php';
    if (is_file($view)) {
        include $view;
    } else {
        echo 'Something went wrong. Please try again shortly.';
    }
});
