<?php
/**
 * Server-side update steps (SSH deploys, or by hand):
 *   php private/cli/apply-update.php --backup --migrate --refresh-text --import-photos [--phone=".."] [--whatsapp=..] [--once]
 * --once: apply new text and contact numbers only the first time for this version.
 * Email/SMTP settings are never read or changed.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}
define('PRIVATE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', is_dir(dirname(__DIR__, 2) . '/public_html') ? dirname(__DIR__, 2) . '/public_html' : dirname(__DIR__, 2));
require PRIVATE_PATH . '/app/bootstrap.php';

if (!App\Core\Config::installed()) {
    fwrite(STDERR, "Website is not installed on this server (private/config.php missing).\n");
    exit(1);
}
$opt = getopt('', ['backup', 'migrate', 'refresh-text', 'import-photos', 'phone:', 'whatsapp:', 'once']);
$lines = (new App\Core\Updater())->run([
    'backup' => isset($opt['backup']), 'migrate' => isset($opt['migrate']),
    'refresh_text' => isset($opt['refresh-text']), 'import_photos' => isset($opt['import-photos']),
    'phone' => (string) ($opt['phone'] ?? ''), 'whatsapp' => (string) ($opt['whatsapp'] ?? ''), 'once' => isset($opt['once']),
]);
echo implode("\n", $lines), "\n";
