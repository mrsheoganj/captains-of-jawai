<?php
declare(strict_types=1);

use App\Core\Config;
use App\Core\Csrf;
use App\Core\Media;
use App\Core\Session;
use App\Core\Settings;
use App\Core\View;

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function setting(string $key, mixed $default = null): mixed
{
    return Settings::get($key, $default);
}

function base_url(): string
{
    $configured = rtrim((string) Config::get('base_url', ''), '/');
    if ($configured !== '') {
        return $configured;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

/** Absolute URL for a site path. */
function abs_url(string $path = '/'): string
{
    if (preg_match('#^https?://#', $path)) {
        return $path;
    }
    return base_url() . '/' . ltrim($path, '/');
}

function admin_path(): string
{
    return '/' . trim((string) Config::get('admin_path', 'admin'), '/');
}

function admin_url(string $path = ''): string
{
    return admin_path() . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function asset(string $path): string
{
    $file = PUBLIC_PATH . '/assets/' . ltrim($path, '/');
    $v = is_file($file) ? substr((string) filemtime($file), -6) : APP_VERSION;
    return '/assets/' . ltrim($path, '/') . '?v=' . $v;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
}

function csrf_token(): string
{
    return Csrf::token();
}

function old(string $key, mixed $default = ''): mixed
{
    return Session::old($key, $default);
}

function flash(string $type, string $message): void
{
    Session::flash($type, $message);
}

function redirect(string $to, int $code = 302): never
{
    header('Location: ' . $to, true, $code);
    exit;
}

function back(string $fallback = '/'): never
{
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $host = parse_url($ref, PHP_URL_HOST);
    redirect($ref !== '' && $host === ($_SERVER['HTTP_HOST'] ?? null) ? $ref : $fallback);
}

function json_out(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function str_input(string $key, int $max = 2000): string
{
    $v = input($key, '');
    if (is_array($v)) {
        return '';
    }
    $v = trim(str_replace("\0", '', (string) $v));
    return mb_substr($v, 0, $max);
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('~[^a-z0-9/]+~', '-', $text);
    $text = preg_replace('~-+~', '-', $text);
    $text = preg_replace('~/+~', '/', $text);
    return trim($text, '-/');
}

function view(string $template, array $data = [], ?string $layout = null): string
{
    return View::render($template, $data, $layout);
}

/** Paragraphs from plain text (escaped). */
function paragraphs(?string $text, string $class = ''): string
{
    $out = '';
    foreach (preg_split('/\R{2,}/', trim((string) $text)) as $p) {
        if ($p !== '') {
            $out .= '<p' . ($class ? ' class="' . e($class) . '"' : '') . '>' . nl2br(e($p)) . '</p>';
        }
    }
    return $out;
}

function lines(?string $text): array
{
    return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text))));
}

function excerpt(?string $html, int $len = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $html)));
    return mb_strlen($text) > $len ? rtrim(mb_substr($text, 0, $len - 1)) . '…' : $text;
}

function fmt_date(?string $date, string $format = 'j M Y'): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    return $ts ? date($format, $ts) : '';
}

function time_ago(?string $date): string
{
    if (!$date) {
        return '';
    }
    $diff = time() - strtotime($date);
    return match (true) {
        $diff < 60 => 'just now',
        $diff < 3600 => floor($diff / 60) . ' min ago',
        $diff < 86400 => floor($diff / 3600) . ' h ago',
        $diff < 604800 => floor($diff / 86400) . ' d ago',
        default => fmt_date($date),
    };
}

/** URL for a media record variant ('lg' 1600px, 'md' 800px). Accepts id or row. */
function media_url(int|array|null $media, string $size = 'lg', string $fallback = ''): string
{
    return Media::url($media, $size, $fallback);
}

/** Responsive <picture> tag for a media id/row, with optional static fallback photo. */
function picture(int|array|null $media, string $alt = '', array $attrs = [], string $fallback = 'leopard1'): string
{
    return Media::picture($media, $alt, $attrs, $fallback);
}

function whatsapp_link(?string $message = null): string
{
    $num = preg_replace('/\D+/', '', (string) setting('whatsapp_number', ''));
    if ($num === '') {
        return '';
    }
    return 'https://wa.me/' . $num . '?text=' . rawurlencode($message ?? (string) setting('whatsapp_message', ''));
}

function tel_link(): string
{
    $p = preg_replace('/[^\d+]/', '', (string) setting('contact_phone', ''));
    return $p !== '' ? 'tel:' . $p : '';
}

function icon(string $name, int $size = 20, string $class = ''): string
{
    return '<svg class="icon ' . e($class) . '" width="' . $size . '" height="' . $size . '" aria-hidden="true"><use href="#i-' . e($name) . '"/></svg>';
}

function current_path(): string
{
    $p = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return '/' . trim(rawurldecode($p), '/') . (trim($p, '/') === '' ? '' : '/');
}

function is_active(string $url): bool
{
    $path = current_path();
    $url = '/' . trim(parse_url($url, PHP_URL_PATH) ?? '', '/') . '/';
    if ($url === '//') {
        return $path === '/';
    }
    return str_starts_with($path, $url);
}

function status_badge(string $status): string
{
    return '<span class="badge badge-' . e($status) . '">' . e(ucwords(str_replace('_', ' ', $status))) . '</span>';
}

function emails_list(?string $raw): array
{
    $out = [];
    foreach (preg_split('/[\s,;]+/', (string) $raw) as $addr) {
        if ($addr !== '' && filter_var($addr, FILTER_VALIDATE_EMAIL)) {
            $out[] = $addr;
        }
    }
    return array_values(array_unique($out));
}

function client_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45);
}
