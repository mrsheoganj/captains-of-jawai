<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Mailer;
use App\Core\SettingDefs;
use App\Core\Settings;

final class SettingsController extends BaseController
{
    private function area(string $group): string
    {
        return in_array($group, ['email', 'templates'], true) ? 'email' : 'settings';
    }

    public function edit(string $group): string
    {
        $groups = SettingDefs::groups();
        if (!isset($groups[$group])) {
            redirect(admin_url('settings/general'));
        }
        $this->guard($this->area($group));
        $values = [];
        foreach ($groups[$group]['fields'] as $f) {
            $values[$f[0]] = SettingDefs::isSecret($f[0]) ? '' : Settings::get($f[0]);
        }
        $hasSecret = [];
        foreach ($groups[$group]['fields'] as $f) {
            if ($f[2] === 'secret') {
                $hasSecret[$f[0]] = (string) Settings::get($f[0]) !== '';
            }
        }
        return $this->render('settings', [
            'groups' => $groups, 'group' => $group, 'g' => $groups[$group], 'values' => $values, 'hasSecret' => $hasSecret,
            'title' => $groups[$group]['title'], 'canEdit' => Auth::can($this->area($group), 'edit'),
        ]);
    }

    public function save(string $group): void
    {
        $groups = SettingDefs::groups();
        if (!isset($groups[$group])) {
            redirect(admin_url('settings/general'));
        }
        $this->guard($this->area($group), 'edit');
        $values = [];
        $errors = [];
        foreach ($groups[$group]['fields'] as $f) {
            [$key, $label, $type] = $f;
            $raw = $_POST[$key] ?? '';
            $raw = is_array($raw) ? implode(',', $raw) : (string) $raw;
            $raw = str_replace("\r\n", "\n", $raw);
            switch ($type) {
                case 'bool':
                    $values[$key] = !empty($_POST[$key]) ? '1' : '0';
                    break;
                case 'secret':
                    if (!empty($_POST[$key . '_clear'])) {
                        $values[$key] = '';
                    } elseif ($raw !== '') {
                        $values[$key] = $raw;
                    }
                    break;
                case 'number':
                    $values[$key] = (string) (int) $raw;
                    break;
                case 'email':
                    $raw = trim($raw);
                    if ($raw !== '' && !filter_var($raw, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "$label: “{$raw}” is not a valid email address.";
                        break;
                    }
                    $values[$key] = $raw;
                    break;
                case 'emails':
                    $list = preg_split('/[\s,;]+/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
                    $bad = array_filter($list, fn ($a) => !filter_var($a, FILTER_VALIDATE_EMAIL));
                    if ($bad) {
                        $errors[] = "$label: invalid address " . implode(', ', $bad);
                        break;
                    }
                    $values[$key] = implode(', ', $list);
                    break;
                case 'url':
                    $raw = trim($raw);
                    if ($raw !== '' && !preg_match('~^https?://~i', $raw)) {
                        $errors[] = "$label must start with https://";
                        break;
                    }
                    $values[$key] = $raw;
                    break;
                case 'color':
                    $values[$key] = preg_match('/^#[0-9a-f]{6}$/i', trim($raw)) ? strtoupper(trim($raw)) : (string) SettingDefs::default($key);
                    break;
                case 'select':
                    $values[$key] = array_key_exists($raw, $f[5] ?? []) ? $raw : (string) SettingDefs::default($key);
                    break;
                case 'image':
                    $values[$key] = (int) $raw > 0 ? (string) (int) $raw : '';
                    break;
                case 'images':
                    $values[$key] = implode(',', array_filter(array_map('intval', explode(',', $raw))));
                    break;
                case 'code':
                    $values[$key] = Auth::role() === 'super_admin' ? trim($raw) : (string) Settings::get($key);
                    break;
                default:
                    $values[$key] = trim($raw);
            }
        }
        if ($errors) {
            foreach ($errors as $err) {
                flash('error', $err);
            }
        }
        Settings::setMany($values);
        Audit::log('settings_update', 'settings', null, ['group' => $group, 'keys' => array_keys(array_filter($values, fn ($k) => !SettingDefs::isSecret($k), ARRAY_FILTER_USE_KEY))]);
        if (!$errors) {
            flash('success', $groups[$group]['title'] . ' saved.');
        }
        redirect(admin_url('settings/' . $group));
    }

    public function testEmail(): void
    {
        $this->guard('email', 'edit');
        $to = str_input('test_to', 200) ?: (Auth::user()['email'] ?? '');
        Mailer::$lastDebug = [];
        $result = Mailer::send(
            [$to],
            'Test email from ' . setting('site_name'),
            "Hello!\n\nThis is a test email from your website admin panel.\n\nIf you are reading this, your email settings work correctly.\n\nTransport: " . setting('mail_transport') . "\nSMTP host: " . setting('smtp_host') . ':' . setting('smtp_port') . ' (' . setting('smtp_encryption') . ")\nSent at: " . date('Y-m-d H:i:s T'),
            ['context' => 'test', 'debug' => true]
        );
        if ($result === true) {
            flash('success', "Test email sent to $to. Check the inbox (and spam folder).");
        } else {
            flash('error', 'Sending failed: ' . $result);
            $_SESSION['smtp_debug'] = array_slice(Mailer::$lastDebug, -40);
        }
        redirect(admin_url('settings/email') . '#test');
    }

    public function emailLog(): string
    {
        $this->guard('email');
        $rows = DB::all('SELECT * FROM email_log ORDER BY id DESC LIMIT 200');
        return $this->render('email-log', ['rows' => $rows, 'title' => 'Email log']);
    }
}
