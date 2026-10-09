<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\DB;
use App\Core\Mailer;
use App\Core\Settings;

/** Public form endpoints (AJAX, JSON responses; graceful non-JS fallback via redirect). */
final class ApiController
{
    public function enquiry(): void
    {
        $this->guard();
        $errors = [];
        $name = str_input('full_name', 100);
        $email = strtolower(str_input('email', 150));
        $phone = str_input('phone', 40);
        if (mb_strlen($name) < 2) {
            $errors['full_name'] = 'Please tell us your name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if ($phone === '' || !preg_match('/^[+\d][\d\s().-]{5,}$/', $phone)) {
            $errors['phone'] = 'Please provide a valid phone / WhatsApp number with country code.';
        }
        $interests = array_values(array_intersect((array) input('interests', []), Settings::lines('form_interests')));
        $accommodation = str_input('accommodation', 150);
        $transfer = str_input('transfer', 150);
        $nights = str_input('nights', 50);
        $window = trim(str_input('travel_month', 60) . ' ' . str_input('travel_dates', 100));
        if ($window === '') {
            $window = 'Flexible';
        }
        if ($errors) {
            $this->fail($errors);
        }

        $data = [
            'type' => 'journey',
            'full_name' => $name, 'email' => $email, 'phone' => $phone,
            'country' => str_input('country', 80) ?: 'India',
            'interests' => implode(', ', $interests),
            'travel_window' => $window . (input('flexible') ? ' (flexible)' : ''),
            'nights' => $nights,
            'adults' => max(1, min(50, (int) input('adults', 2))),
            'children' => max(0, min(30, (int) input('children', 0))),
            'private_vehicle' => input('private_vehicle') ? 'Yes' : 'No preference',
            'accommodation' => $accommodation, 'transfer' => $transfer,
            'message' => str_input('message', 3000),
        ];
        $this->done($this->store($data), 'journey');
    }

    public function contact(): void
    {
        $this->guard();
        $errors = [];
        $name = str_input('full_name', 100);
        $email = strtolower(str_input('email', 150));
        $message = str_input('message', 3000);
        if (mb_strlen($name) < 2) {
            $errors['full_name'] = 'Please tell us your name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (mb_strlen($message) < 5) {
            $errors['message'] = 'Please write a short message.';
        }
        if ($errors) {
            $this->fail($errors);
        }
        $id = $this->store([
            'type' => 'contact', 'full_name' => $name, 'email' => $email,
            'phone' => str_input('phone', 40), 'country' => str_input('country', 80), 'message' => $message,
        ]);
        $this->done($id, 'contact');
    }

    /** CSRF, honeypot, minimum fill time and per-IP rate limit. */
    private function guard(): void
    {
        if (!Csrf::valid()) {
            $this->fail(['_form' => 'Your session expired. Please refresh the page and try again.'], 419);
        }
        // Honeypot field must stay empty; form must take at least 3 seconds to fill.
        $started = (int) input('_ts', 0);
        if (str_input('website') !== '' || ($started > 0 && time() - $started < 3)) {
            $this->done(0); // pretend success to bots
        }
        $limit = max(1, (int) setting('form_rate_limit', 5));
        $recent = (int) DB::val('SELECT COUNT(*) FROM enquiries WHERE ip = ? AND created_at > ?', [client_ip(), date('Y-m-d H:i:s', time() - 3600)]);
        if ($recent >= $limit) {
            $this->fail(['_form' => 'We have received several messages from you already. Please wait a little or contact us directly.'], 429);
        }
    }

    private function store(array $data): int
    {
        $ref = parse_url((string) ($_SERVER['HTTP_REFERER'] ?? ''), PHP_URL_PATH) ?: '';
        $now = now();
        $id = DB::insert('enquiries', $data + [
            'code' => 'TMP',
            'source_page' => mb_substr(str_input('source_page', 200) ?: $ref, 0, 250),
            'utm_source' => str_input('utm_source', 100), 'utm_medium' => str_input('utm_medium', 100), 'utm_campaign' => str_input('utm_campaign', 100),
            'ip' => client_ip(), 'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 250),
            'status' => 'new', 'created_at' => $now, 'updated_at' => $now,
        ]);
        $code = 'COJ-' . date('Y') . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
        DB::update('enquiries', ['code' => $code], 'id = ?', [$id]);
        return $id;
    }

    /** Send team notification + guest confirmation using admin-configured templates and recipients. */
    public static function notify(int $id, string $type): void
    {
        $e = DB::one('SELECT * FROM enquiries WHERE id = ?', [$id]);
        if (!$e) {
            return;
        }
        $vars = self::vars($e);
        $journey = $type === 'journey';
        $team = $journey ? emails_list(setting('notify_recipients')) : (emails_list(setting('contact_recipients')) ?: emails_list(setting('notify_recipients')));
        if (!$team) {
            $team = emails_list(setting('contact_email'));
        }
        $prefix = $journey ? 'tpl_admin' : 'tpl_contact_admin';
        Mailer::send($team, Mailer::fill((string) setting("{$prefix}_subject"), $vars), Mailer::fill((string) setting("{$prefix}_body"), $vars), [
            'cc' => $journey ? emails_list(setting('notify_cc')) : [],
            'bcc' => $journey ? emails_list(setting('notify_bcc')) : [],
            'reply_to' => $e['email'], 'reply_name' => $e['full_name'], 'context' => $type . '_admin',
        ]);
        if (Settings::bool('autoreply_enabled')) {
            $gp = $journey ? 'tpl_guest' : 'tpl_contact_guest';
            Mailer::send([$e['email']], Mailer::fill((string) setting("{$gp}_subject"), $vars), Mailer::fill((string) setting("{$gp}_body"), $vars), [
                'reply_to' => (string) setting('mail_reply_to'), 'context' => $type . '_guest',
            ]);
        }
    }

    public static function vars(array $e): array
    {
        $party = (int) $e['adults'] . ' adult(s)' . ((int) $e['children'] ? ', ' . (int) $e['children'] . ' child(ren)' : '');
        $rows = array_filter([
            'Reference' => $e['code'], 'Name' => $e['full_name'], 'Email' => $e['email'], 'Phone / WhatsApp' => $e['phone'],
            'Country' => $e['country'], 'Interests' => $e['interests'], 'Travel window' => $e['travel_window'], 'Stay' => $e['nights'],
            'Party' => $e['type'] === 'journey' ? $party : '', 'Private vehicle' => $e['type'] === 'journey' ? $e['private_vehicle'] : '',
            'Accommodation' => $e['accommodation'], 'Transfer' => $e['transfer'], 'Message' => $e['message'],
        ], fn ($v) => $v !== null && $v !== '');
        $details = implode("\n", array_map(fn ($k, $v) => "$k: $v", array_keys($rows), $rows));
        return [
            'name' => $e['full_name'], 'first_name' => explode(' ', trim((string) $e['full_name']))[0], 'code' => $e['code'],
            'email' => $e['email'], 'phone' => $e['phone'], 'country' => $e['country'], 'travel_window' => $e['travel_window'],
            'interests' => $e['interests'], 'party' => $party, 'accommodation' => $e['accommodation'], 'message' => $e['message'],
            'details' => $details, 'admin_link' => abs_url(admin_url('enquiries/' . $e['id'])),
        ];
    }

    private function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    private function fail(array $errors, int $code = 422): never
    {
        if ($this->wantsJson()) {
            json_out(['success' => false, 'errors' => $errors, 'message' => $errors['_form'] ?? 'Please check the highlighted fields.'], $code);
        }
        \App\Core\Session::keepInput($_POST);
        flash('error', implode(' ', $errors));
        back('/plan-your-journey/');
    }

    /** Respond to the visitor, then send notification emails (after the response when PHP-FPM allows). */
    private function done(int $id, string $type = ''): never
    {
        $code = $id ? DB::val('SELECT code FROM enquiries WHERE id = ?', [$id]) : 'COJ-' . date('Y') . '-0000';
        $canDetach = function_exists('fastcgi_finish_request');
        if ($id && $type !== '' && !$canDetach) {
            self::notify($id, $type);
        }
        if ($this->wantsJson()) {
            http_response_code(201);
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-store');
            echo json_encode(['success' => true, 'code' => $code, 'title' => setting('form_success_title'), 'message' => setting('form_success_message')], JSON_UNESCAPED_UNICODE);
        } else {
            flash('success', setting('form_success_title') . ' ' . setting('form_success_message') . ' Reference: ' . $code);
            $ref = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . (parse_url($ref, PHP_URL_HOST) === ($_SERVER['HTTP_HOST'] ?? null) ? $ref : '/'), true, 302);
        }
        if ($id && $type !== '' && $canDetach) {
            session_write_close();
            fastcgi_finish_request();
            ignore_user_abort(true);
            self::notify($id, $type);
        }
        exit;
    }
}
