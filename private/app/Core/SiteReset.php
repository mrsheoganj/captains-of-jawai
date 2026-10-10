<?php
declare(strict_types=1);

namespace App\Core;

/**
 * "Reset website": clear demo/test data and reload the real Jawai starter content, ready to go live.
 *
 * Deletes: enquiries and notes, email log, activity log, login attempts, safaris, pages, FAQs,
 *          testimonials and menus (then reloads the real starter content for all of them).
 * Keeps:   admin users, the media library (every image), the image chosen for each safari, page
 *          and homepage slot, branding (name, logo, favicon, colours), contact details, email/SMTP
 *          settings and recipients, analytics and verification codes, redirects.
 * Resets:  homepage text, form options, email templates, SEO titles and other copy to the defaults.
 * Go-live: maintenance mode off, search engines allowed, real email sending switched on.
 */
final class SiteReset
{
    /** Settings that survive a reset (exact keys or prefixes ending in "_"). */
    private const KEEP = [
        'site_name', 'site_tagline', 'site_description', 'logo_', 'footer_logo_image', 'favicon_image', 'color_', 'copyright_text',
        'contact_', 'whatsapp_', 'address', 'map_embed_url', 'office_hours', 'legal_name', 'gstin', 'social_', 'latitude', 'longitude',
        'mail_', 'smtp_', 'notify_', 'autoreply_enabled', 'email_footer',
        'google_verification', 'ga4_id', 'head_code', 'body_code', 'seo_og_image',
        'schema_version',
    ];

    private static function keep(string $key): bool
    {
        if (preg_match('/(_image|_images)$|^img_/', $key)) {
            return true; // every image choice
        }
        foreach (self::KEEP as $k) {
            if ($key === $k || (str_ends_with($k, '_') && str_starts_with($key, $k))) {
                return true;
            }
        }
        return false;
    }

    /** @return array<string,int> counts of what was removed / reloaded */
    public static function run(): array
    {
        $stats = [];
        // Remember which image each safari/page used, so the reload keeps your photos.
        $images = [];
        foreach (['safaris', 'pages'] as $t) {
            foreach (DB::all("SELECT slug, image_id FROM $t WHERE image_id IS NOT NULL") as $r) {
                $images[$t][$r['slug']] = (int) $r['image_id'];
            }
        }

        foreach (['enquiry_notes', 'enquiries', 'email_log', 'audit_log', 'login_attempts', 'safaris', 'pages', 'faqs', 'testimonials', 'menu_items'] as $t) {
            $stats[$t] = (int) DB::val("SELECT COUNT(*) FROM $t");
            DB::q("DELETE FROM $t");
        }

        $removed = 0;
        foreach (DB::all('SELECT skey FROM settings') as $r) {
            if (!self::keep($r['skey'])) {
                DB::delete('settings', 'skey = ?', [$r['skey']]);
                $removed++;
            }
        }
        $stats['settings reset'] = $removed;

        // Go-live switches
        Settings::set('maintenance_mode', '0');
        Settings::set('seo_noindex', '0');
        if (Settings::get('mail_transport') === 'log') {
            Settings::set('mail_transport', trim((string) Settings::get('smtp_host')) !== '' ? 'smtp' : 'mail');
        }
        Settings::flush();

        // Real starter content (safaris, Jawai guide, culture, FAQs, menus) — no demo enquiries.
        Seeder::run(false, false);

        // Put your images back on the reloaded safaris and pages.
        $exists = fn (int $id) => (bool) DB::val('SELECT COUNT(*) FROM media WHERE id = ?', [$id]);
        foreach ($images as $t => $bySlug) {
            foreach ($bySlug as $slug => $id) {
                if ($exists($id)) {
                    DB::update($t, ['image_id' => $id], 'slug = ?', [$slug]);
                }
            }
        }
        Demo::assignImages(false); // fill any slot still empty
        Settings::flush();
        return $stats;
    }
}
