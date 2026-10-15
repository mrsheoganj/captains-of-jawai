<?php
/**
 * Server-side update steps, run over SSH by the "Deploy to GoDaddy" workflow (or by hand):
 *   php private/cli/apply-update.php --backup --migrate --refresh-text --import-photos
 *
 *   --backup         save a full SQL backup of the database to private/storage/backups/
 *   --migrate        create new tables/columns and run the version upgrade steps
 *   --refresh-text   load the latest safaris, Jawai guide pages, FAQs and menus
 *                    (keeps enquiries, settings, email/SMTP, users and images)
 *   --import-photos  import the recommended Wikimedia Commons photos and place them
 *
 * Email/SMTP settings are never read or changed by this script.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}
define('PRIVATE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', is_dir(dirname(__DIR__, 2) . '/public_html') ? dirname(__DIR__, 2) . '/public_html' : dirname(__DIR__, 2));
require PRIVATE_PATH . '/app/bootstrap.php';

use App\Core\{App, Config, DB, PhotoImporter, Schema, Settings, SiteReset};

$opt = getopt('', ['backup', 'migrate', 'refresh-text', 'import-photos']);
if (!Config::installed()) {
    fwrite(STDERR, "Website is not installed on this server (private/config.php missing).\n");
    exit(1);
}
$say = fn (string $m) => print('[' . date('H:i:s') . "] $m\n");

if (isset($opt['backup'])) {
    $dir = STORAGE_PATH . '/backups';
    @mkdir($dir, 0700, true);
    @file_put_contents($dir . '/.htaccess', "Require all denied\n");
    $file = $dir . '/db-' . date('Ymd-His') . '.sql';
    $fh = fopen($file, 'w');
    $pdo = DB::pdo();
    $mysql = DB::driver() !== 'sqlite';
    fwrite($fh, "-- Captains of Jawai database backup " . date('c') . "\n" . ($mysql ? "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n" : ''));
    $tables = $mysql ? $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) : $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
    $rows = 0;
    foreach ($tables as $t) {
        if ($mysql) {
            $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_NUM)[1];
            fwrite($fh, "DROP TABLE IF EXISTS `$t`;\n$create;\n");
        }
        foreach ($pdo->query("SELECT * FROM " . ($mysql ? "`$t`" : "\"$t\""), PDO::FETCH_ASSOC) as $r) {
            $vals = array_map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), array_values($r));
            fwrite($fh, 'INSERT INTO ' . ($mysql ? "`$t`" : "\"$t\"") . ' (' . implode(',', array_map(fn ($c) => $mysql ? "`$c`" : "\"$c\"", array_keys($r))) . ') VALUES (' . implode(',', $vals) . ");\n");
            $rows++;
        }
    }
    if ($mysql) {
        fwrite($fh, "SET FOREIGN_KEY_CHECKS=1;\n");
    }
    fclose($fh);
    @chmod($file, 0600);
    $say("Backup saved: private/storage/backups/" . basename($file) . " ($rows rows, " . round(filesize($file) / 1024) . " KB)");
}

if (isset($opt['migrate'])) {
    Schema::create(DB::pdo());
    App::upgradeNow();
    $say('Database updated to version ' . APP_VERSION . '.');
}

if (isset($opt['refresh-text'])) {
    $stats = SiteReset::refreshContent();
    \App\Core\Audit::log('content_refresh', 'system', null, $stats + ['via' => 'deploy']);
    $say('Website text updated (safaris, Jawai guide pages, FAQs, menus). Enquiries, settings and images kept.');
}

if (isset($opt['import-photos'])) {
    @set_time_limit(900);
    [$n, $filled, $msgs] = PhotoImporter::importRecommended();
    $say("Recommended photos: $n ready, placed in $filled spot(s).");
    foreach ($msgs as $m) {
        $say('  note: ' . $m);
    }
}

Settings::flush();
$say('Done.');
