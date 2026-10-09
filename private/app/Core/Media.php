<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Media library: image upload, validation, re-encoding and responsive variants.
 * Each media row has a base `path` (relative to public_html) and these files:
 *   {path}-1600.{ext} {path}-800.{ext} {path}-400.{ext} and .webp siblings when GD supports WebP.
 */
final class Media
{
    public const SIZES = ['lg' => 1600, 'md' => 800, 'sm' => 400];
    private const MAX_BYTES = 15 * 1024 * 1024;
    private const ALLOWED = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    /** Built-in photos shipped in /assets/photos (used as fallbacks before real photos are uploaded). */
    public const STOCK = ['leopard1', 'leopard2', 'landscape1', 'bird1', 'safari1'];

    private static array $cache = [];

    public static function find(int $id): ?array
    {
        if (!array_key_exists($id, self::$cache)) {
            self::$cache[$id] = $id > 0 ? DB::one('SELECT * FROM media WHERE id = ?', [$id]) : null;
        }
        return self::$cache[$id];
    }

    public static function ext(array $m): string
    {
        return ($m['mime'] ?? '') === 'image/png' ? 'png' : 'jpg';
    }

    private static function sizeKey(string $size): int
    {
        return self::SIZES[$size] ?? 1600;
    }

    private static function resolve(int|array|null $media): ?array
    {
        if (is_array($media)) {
            return $media;
        }
        if (is_int($media) || (is_string($media) && ctype_digit($media))) {
            return self::find((int) $media);
        }
        return null;
    }

    private static function stockUrl(string $name, string $size, string $fmt = 'jpg'): string
    {
        $w = self::sizeKey($size) >= 1600 ? 1600 : 800;
        return '/assets/photos/' . $name . '-' . $w . '.' . $fmt;
    }

    public static function url(int|array|null $media, string $size = 'lg', string $fallback = ''): string
    {
        $m = self::resolve($media);
        if ($m) {
            return '/' . $m['path'] . '-' . self::sizeKey($size) . '.' . self::ext($m);
        }
        if ($fallback !== '' && in_array($fallback, self::STOCK, true)) {
            return self::stockUrl($fallback, $size);
        }
        return $fallback;
    }

    public static function picture(int|array|null $media, string $alt = '', array $attrs = [], string $fallback = 'leopard1'): string
    {
        $m = self::resolve($media);
        $sizes = $attrs['sizes'] ?? '100vw';
        $class = $attrs['class'] ?? '';
        $loading = $attrs['loading'] ?? 'lazy';
        $priority = isset($attrs['fetchpriority']) ? ' fetchpriority="' . e($attrs['fetchpriority']) . '"' : '';

        if ($m) {
            $base = '/' . $m['path'];
            $ext = self::ext($m);
            $alt = $alt !== '' ? $alt : (string) $m['alt_text'];
            $w = (int) ($m['width'] ?: 1600);
            $h = (int) ($m['height'] ?: 1000);
            $set = fn (string $x) => implode(', ', array_map(fn ($px) => "$base-$px.$x {$px}w", array_values(self::SIZES)));
            $webp = is_file(PUBLIC_PATH . "/{$m['path']}-800.webp") ? '<source type="image/webp" srcset="' . e($set('webp')) . '" sizes="' . e($sizes) . '">' : '';
            return '<picture>' . $webp . '<img src="' . e("$base-1600.$ext") . '" srcset="' . e($set($ext)) . '" sizes="' . e($sizes) . '" width="' . $w . '" height="' . $h . '" alt="' . e($alt) . '" loading="' . e($loading) . '" decoding="async"' . $priority . ($class ? ' class="' . e($class) . '"' : '') . '></picture>';
        }
        if (!in_array($fallback, self::STOCK, true)) {
            return '';
        }
        $b = '/assets/photos/' . $fallback;
        return '<picture><source type="image/webp" srcset="' . e("$b-800.webp 800w, $b-1600.webp 1600w") . '" sizes="' . e($sizes) . '"><img src="' . e("$b-1600.jpg") . '" srcset="' . e("$b-800.jpg 800w, $b-1600.jpg 1600w") . '" sizes="' . e($sizes) . '" width="1600" height="893" alt="' . e($alt) . '" loading="' . e($loading) . '" decoding="async"' . $priority . ($class ? ' class="' . e($class) . '"' : '') . '></picture>';
    }

    /** Handle one entry of $_FILES. Returns the new media id, or an error string. */
    public static function upload(array $file, array $meta = []): int|string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return match ($file['error'] ?? 0) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File is larger than the server allows (' . ini_get('upload_max_filesize') . ').',
                UPLOAD_ERR_NO_FILE => 'No file selected.',
                default => 'Upload failed (code ' . ($file['error'] ?? '?') . ').',
            };
        }
        if ($file['size'] > self::MAX_BYTES) {
            return 'File exceeds 15 MB.';
        }
        if (!is_uploaded_file($file['tmp_name']) && !($meta['_local'] ?? false)) {
            return 'Invalid upload.';
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            return 'Only JPG, PNG, WebP and GIF images are allowed.';
        }
        $info = @getimagesize($file['tmp_name']);
        if (!$info) {
            return 'The file is not a valid image.';
        }
        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file['tmp_name']),
            'image/png' => @imagecreatefrompng($file['tmp_name']),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file['tmp_name']) : false,
            'image/gif' => @imagecreatefromgif($file['tmp_name']),
        };
        if (!$src) {
            return 'Could not read the image.';
        }
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $src = self::orient($src, $file['tmp_name']);
        }
        $hasAlpha = $mime === 'image/png';
        $dir = 'uploads/' . date('Y/m');
        if (!is_dir(PUBLIC_PATH . '/' . $dir) && !@mkdir(PUBLIC_PATH . '/' . $dir, 0755, true)) {
            return 'Upload folder is not writable: public_html/' . $dir;
        }
        $name = slugify(pathinfo((string) $file['name'], PATHINFO_FILENAME)) ?: 'image';
        $name = substr(str_replace('/', '-', $name), 0, 60) . '-' . bin2hex(random_bytes(3));
        $base = $dir . '/' . $name;

        $w = imagesx($src);
        $h = imagesy($src);
        foreach (self::SIZES as $target) {
            $tw = min($w, $target);
            $th = (int) round($h * $tw / $w);
            $dst = imagecreatetruecolor($tw, $th);
            if ($hasAlpha) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            $out = PUBLIC_PATH . "/$base-$target";
            $hasAlpha ? imagepng($dst, "$out.png", 8) : imagejpeg($dst, "$out.jpg", 82);
            if (function_exists('imagewebp')) {
                imagewebp($dst, "$out.webp", 80);
            }
            imagedestroy($dst);
        }
        imagedestroy($src);

        $lgW = min($w, 1600);
        $id = DB::insert('media', [
            'path' => $base,
            'filename' => mb_substr((string) $file['name'], 0, 250),
            'mime' => $hasAlpha ? 'image/png' : 'image/jpeg',
            'width' => $lgW,
            'height' => (int) round($h * $lgW / $w),
            'filesize' => (int) $file['size'],
            'alt_text' => mb_substr($meta['alt_text'] ?? ucwords(str_replace(['-', '_'], ' ', pathinfo((string) $file['name'], PATHINFO_FILENAME))), 0, 250),
            'caption' => $meta['caption'] ?? '',
            'credit' => $meta['credit'] ?? '',
            'license' => $meta['license'] ?? 'Client Owned',
            'in_gallery' => (int) ($meta['in_gallery'] ?? 0),
            'gallery_category' => $meta['gallery_category'] ?? '',
            'sort_order' => 0,
            'source_url' => mb_substr((string) ($meta['source_url'] ?? ''), 0, 250),
            'license_url' => mb_substr((string) ($meta['license_url'] ?? ''), 0, 250),
            'created_at' => now(),
        ]);
        return $id;
    }

    private static function orient(\GdImage $img, string $file): \GdImage
    {
        $exif = @exif_read_data($file);
        $o = (int) ($exif['Orientation'] ?? 1);
        return match ($o) {
            3 => imagerotate($img, 180, 0) ?: $img,
            6 => imagerotate($img, -90, 0) ?: $img,
            8 => imagerotate($img, 90, 0) ?: $img,
            default => $img,
        };
    }

    public static function delete(int $id): void
    {
        $m = self::find($id);
        if (!$m) {
            return;
        }
        if (str_starts_with($m['path'], 'uploads/')) {
            foreach (glob(PUBLIC_PATH . '/' . $m['path'] . '-*') ?: [] as $f) {
                @unlink($f);
            }
        }
        DB::delete('media', 'id = ?', [$id]);
        unset(self::$cache[$id]);
    }

    /** Is this media id used anywhere? Returns human-readable list of usages. */
    public static function usages(int $id): array
    {
        $uses = [];
        foreach (['safaris' => 'title', 'journeys' => 'title', 'posts' => 'title', 'pages' => 'title', 'team' => 'name'] as $t => $col) {
            foreach (DB::all("SELECT $col AS label FROM $t WHERE image_id = ?", [$id]) as $r) {
                $uses[] = ucfirst($t) . ': ' . $r['label'];
            }
        }
        foreach (SettingDefs::groups() as $g) {
            foreach ($g['fields'] as $f) {
                if (in_array($f[2], ['image', 'images'], true) && in_array((string) $id, explode(',', (string) Settings::get($f[0], '')), true)) {
                    $uses[] = 'Setting: ' . $f[1];
                }
            }
        }
        return $uses;
    }
}
