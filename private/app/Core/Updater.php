<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Post-deploy steps, shared by private/cli/apply-update.php (SSH) and the one-time deploy hook
 * (POST /_deploy, used by FTP deploys on hosting without shell access).
 * Email/SMTP settings are never read or changed here.
 */
final class Updater
{
    /** @var string[] */
    private array $log = [];

    private function say(string $m): void
    {
        $this->log[] = '[' . date('H:i:s') . "] $m";
    }

    /**
     * @param array{backup?:bool,migrate?:bool,refresh_text?:bool,import_photos?:bool,phone?:string,whatsapp?:string,once?:bool} $o
     * "once": apply refresh_text/phone/whatsapp only the first time for this version.
     * @return string[] log lines
     */
    public function run(array $o): array
    {
        $mark = STORAGE_PATH . '/.content-applied-' . APP_VERSION;
        $first = !is_file($mark);
        if (!empty($o['once']) && !$first) {
            unset($o['refresh_text'], $o['phone'], $o['whatsapp']);
            $this->say('New text and contact numbers were already applied for ' . APP_VERSION . '; skipping them.');
        }
        if (!empty($o['backup'])) {
            $this->backup();
        }
        if (!empty($o['migrate'])) {
            Schema::create(DB::pdo());
            App::upgradeNow();
            $this->say('Database updated to version ' . APP_VERSION . '.');
        }
        if (!empty($o['refresh_text'])) {
            $stats = SiteReset::refreshContent();
            Audit::log('content_refresh', 'system', null, $stats + ['via' => 'deploy']);
            $this->say('Website text updated (safaris, Jawai guide pages, FAQs, menus). Enquiries, settings and images kept.');
        }
        if (!empty($o['import_photos'])) {
            if (ImagePlan::done()) {
                $this->say('Photo layout already applied; your own photo choices are kept.');
            } else {
                [$lines, $finished] = ImagePlan::apply();
                foreach ($lines as $l) {
                    $this->say($l);
                }
            }
        }
        if (!empty($o['phone'])) {
            $phone = trim((string) preg_replace('/[^\d+ ]/', '', (string) $o['phone']));
            Settings::set('contact_phone', $phone);
            $this->say("Phone number set: $phone");
        }
        if (!empty($o['whatsapp'])) {
            $wa = (string) preg_replace('/\D+/', '', (string) $o['whatsapp']);
            Settings::set('whatsapp_number', $wa);
            $this->say("WhatsApp number set: $wa");
        }
        if (!empty($o['once']) && $first && (!empty($o['refresh_text']) || !empty($o['phone']))) {
            @file_put_contents($mark, date('c'));
        }
        // Tell search engines about every page, at most every 6 hours (deploys can run several rounds).
        $ping = STORAGE_PATH . '/.indexnow-sent';
        $version = STORAGE_PATH . '/.indexnow-' . APP_VERSION;
        if (!is_file($ping) || filemtime($ping) < time() - 6 * 3600 || !is_file($version)) {
            @touch($version);
            $this->say(IndexNow::submit(IndexNow::allPaths()));
            @touch($ping);
        }
        Settings::flush();
        $this->say('Done.');
        @file_put_contents(STORAGE_PATH . '/logs/deploy.log', implode("\n", $this->log) . "\n", FILE_APPEND);
        return $this->log;
    }

    /** Full SQL backup to private/storage/backups/ (not web-accessible). */
    public function backup(): string
    {
        $dir = STORAGE_PATH . '/backups';
        @mkdir($dir, 0700, true);
        @file_put_contents($dir . '/.htaccess', "Require all denied\n");
        $file = $dir . '/db-' . date('Ymd-His') . '.sql';
        $fh = fopen($file, 'w');
        $pdo = DB::pdo();
        $mysql = DB::driver() !== 'sqlite';
        fwrite($fh, '-- Captains of Jawai database backup ' . date('c') . "\n" . ($mysql ? "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n" : ''));
        $tables = $mysql
            ? $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)
            : $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
        $q = fn (string $n) => $mysql ? "`$n`" : "\"$n\"";
        $rows = 0;
        foreach ($tables as $t) {
            if ($mysql) {
                $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_NUM)[1];
                fwrite($fh, "DROP TABLE IF EXISTS `$t`;\n$create;\n");
            }
            foreach ($pdo->query('SELECT * FROM ' . $q($t), PDO::FETCH_ASSOC) as $r) {
                $vals = array_map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), array_values($r));
                fwrite($fh, 'INSERT INTO ' . $q($t) . ' (' . implode(',', array_map($q, array_keys($r))) . ') VALUES (' . implode(',', $vals) . ");\n");
                $rows++;
            }
        }
        if ($mysql) {
            fwrite($fh, "SET FOREIGN_KEY_CHECKS=1;\n");
        }
        fclose($fh);
        @chmod($file, 0600);
        $this->say('Backup saved: private/storage/backups/' . basename($file) . " ($rows rows, " . round(filesize($file) / 1024) . ' KB)');
        return $file;
    }
}
