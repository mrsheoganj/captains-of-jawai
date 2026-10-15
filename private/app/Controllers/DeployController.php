<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Updater;

/**
 * One-time deploy hook for hosting without SSH shell access (FTP deploys).
 * The deploy workflow uploads a random token to private/storage/deploy-token by FTP, then POSTs
 * the same token here. The token must be fresh (15 minutes) and is deleted on first use, so the
 * endpoint is closed at all other times (it answers 404).
 */
final class DeployController
{
    public function run(): void
    {
        $file = STORAGE_PATH . '/deploy-token';
        $sent = (string) ($_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '');
        $token = is_file($file) ? trim((string) file_get_contents($file)) : '';
        $fresh = $token !== '' && filemtime($file) > time() - 900;
        if (!$fresh || strlen($sent) < 32 || !hash_equals($token, $sent)) {
            http_response_code(404);
            exit('Not found');
        }
        @unlink($file); // single use
        ignore_user_abort(true);
        @set_time_limit(900);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        $lines = (new Updater())->run([
            'backup' => true, 'migrate' => true, 'once' => true,
            'refresh_text' => ($_POST['refresh_text'] ?? '1') === '1',
            'import_photos' => ($_POST['import_photos'] ?? '1') === '1',
            'phone' => (string) ($_POST['phone'] ?? ''), 'whatsapp' => (string) ($_POST['whatsapp'] ?? ''),
        ]);
        echo implode("\n", $lines), "\n";
        exit;
    }
}
