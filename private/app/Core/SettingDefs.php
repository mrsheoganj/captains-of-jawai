<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Every admin-editable setting, grouped into the tabs shown in Admin → Settings.
 * Field: [key, label, type, default, help, options]
 * Types: text, textarea, lines, email, emails, url, number, bool, select, secret, image, color, code
 */
final class SettingDefs
{
    private static ?array $index = null;

    public static function groups(): array
    {
        return [
            'general' => [
                'title' => 'General & Branding',
                'icon' => 'settings',
                'intro' => 'Site identity, brand colours, announcement bar and maintenance mode.',
                'fields' => [
                    ['site_name', 'Site name', 'text', 'Captains of Jawai'],
                    ['site_tagline', 'Tagline', 'text', "Private leopard safaris in Jawai, Rajasthan"],
                    ['site_description', 'Short brand description (footer)', 'textarea', "Private, small-group wildlife safaris in Jawai, Pali district, Rajasthan. We plan every trip by hand and reply to every enquiry personally."],
                    ['logo_image', 'Logo / emblem (header, mobile menu, loading spinner, admin, emails)', 'image', '', 'Upload a PNG (transparent background works) in the Media library or straight from the picker. Leave empty to use the built-in Captains of Jawai emblem.'],
                    ['logo_show_name', 'Show the site name next to the logo in the header', 'bool', '1'],
                    ['logo_dark_badge', 'Show the uploaded logo on a dark round badge (turn on for logos with white artwork)', 'bool', '0'],
                    ['footer_logo_image', 'Footer logo (optional — defaults to the header logo)', 'image', ''],
                    ['favicon_image', 'Browser tab icon / favicon (square image, optional)', 'image', '', 'Defaults to the built-in emblem.'],
                    ['color_accent', 'Accent colour (buttons, highlights)', 'color', '#B8822B'],
                    ['color_accent_hover', 'Accent hover colour', 'color', '#9E6B1D'],
                    ['color_dark', 'Dark surface colour (footer, dark sections)', 'color', '#121416'],
                    ['color_crimson', 'Secondary accent (Rabari crimson)', 'color', '#B93826'],
                    ['announcement_enabled', 'Show announcement bar', 'bool', '0'],
                    ['announcement_text', 'Announcement text', 'text', "Booking now for October 2026 to March 2027."],
                    ['announcement_link', 'Announcement link', 'text', '/plan-your-journey/'],
                    ['maintenance_mode', 'Maintenance mode (visitors see a holding page; logged-in admins see the site)', 'bool', '0'],
                    ['maintenance_message', 'Maintenance message', 'textarea', "We're updating the website. Please check back soon or email us directly."],
                    ['copyright_text', 'Copyright line', 'text', "© {year} Captains of Jawai. All rights reserved."],
                ],
            ],
            'contact' => [
                'title' => 'Contact & Social',
                'icon' => 'phone',
                'intro' => 'Shown in the header, footer, contact page, WhatsApp buttons and structured data.',
                'fields' => [
                    ['contact_email', 'Public contact email', 'email', 'contact@captainsofjawai.com'],
                    ['contact_phone', 'Phone number (display)', 'text', '', 'e.g. +91 98765 43210 — leave empty to hide'],
                    ['whatsapp_number', 'WhatsApp number (digits with country code)', 'text', '', 'e.g. 919876543210 — leave empty to hide WhatsApp buttons'],
                    ['whatsapp_message', 'WhatsApp pre-filled message', 'textarea', 'Hello Captains of Jawai, I would like to plan a private safari in Jawai.'],
                    ['address', 'Address', 'textarea', 'Jawai Bandh, Bera, Pali District, Rajasthan, India'],
                    ['map_embed_url', 'Google Maps embed URL (optional)', 'url', '', 'From Google Maps → Share → Embed a map → copy only the src URL'],
                    ['office_hours', 'Response / office hours', 'text', 'Every day, 07:00–21:00 IST'],
                    ['legal_name', 'Registered legal business name', 'text', ''],
                    ['gstin', 'GSTIN / Tax ID', 'text', ''],
                    ['social_instagram', 'Instagram URL', 'url', ''],
                    ['social_facebook', 'Facebook URL', 'url', ''],
                    ['social_youtube', 'YouTube URL', 'url', ''],
                    ['social_tripadvisor', 'TripAdvisor URL', 'url', ''],
                    ['social_google', 'Google Business profile URL', 'url', ''],
                    ['latitude', 'Latitude', 'text', '25.1055'],
                    ['longitude', 'Longitude', 'text', '73.1722'],
                ],
            ],
            'home' => [
                'title' => 'Homepage',
                'icon' => 'home',
                'intro' => 'All homepage copy and imagery. Sections can be hidden individually.',
                'fields' => [
                    ['hero_kicker', 'Hero kicker (small line above headline)', 'text', "Jawai · Pali district · Rajasthan"],
                    ['hero_title', 'Hero headline', 'text', "Private leopard safaris in Jawai"],
                    ['hero_subtitle', 'Hero sub-headline', 'textarea', "Small, unhurried drives with local trackers who know these hills and their leopards. Tell us your dates and we'll plan the rest."],
                    ['hero_images', 'Hero slideshow images (pick one or more)', 'images', ''],
                    ['hero_cta_primary', 'Primary button text', 'text', "Plan your trip"],
                    ['hero_cta_secondary', 'Secondary button text', 'text', "See the safaris"],
                    ['ticker_enabled', 'Show field ticker', 'bool', "0"],
                    ['ticker_items', 'Ticker items (one per line)', 'lines', "Leopard safaris\nJawai Dam birding\nRabari village walks\nDawn and dusk drives\nOctober to March season"],
                    ['intro_kicker', 'Intro kicker', 'text', "About Jawai"],
                    ['intro_heading', 'Intro heading', 'text', "Leopards, granite hills and a working countryside"],
                    ['intro_body', 'Intro text', 'textarea', "Jawai is a stretch of granite hills around the Jawai Dam in Pali district, roughly halfway between Udaipur and Jodhpur. Leopards den in the caves and rest on the rocks here, a short walk from villages, farms and hill temples. The Rabari herders who graze their animals on these hills have lived alongside them for generations.\n\nIt isn't a fenced national park. Our drives use village tracks and go out early in the morning and late in the afternoon, which is when the leopards are most often out on the rocks."],
                    ['intro_image', 'Intro image', 'image', ''],
                    ['stats', 'Key facts (one per line: value | label)', 'lines', "50–60 | Leopards estimated in the reserve\n1957 | Jawai Dam completed\n19.8 km² | Leopard conservation reserve\n2 | Drives a day, dawn and dusk"],
                    ['safaris_kicker', 'Safaris section kicker', 'text', "What we offer"],
                    ['safaris_heading', 'Safaris section heading', 'text', "Our safaris"],
                    ['coexist_enabled', 'Show coexistence feature', 'bool', '1'],
                    ['coexist_kicker', 'Coexistence kicker', 'text', "The Rabari"],
                    ['coexist_heading', 'Coexistence heading', 'text', "Herders and leopards on the same hills"],
                    ['coexist_body', 'Coexistence text', 'textarea', "The Rabari are pastoralists who have grazed sheep, goats, cattle and camels around Jawai for generations. Many hill shrines here are linked to local deities, and leopards are generally left alone, even when they take livestock. That tolerance is a big part of why the leopards are so relaxed around people. We arrange visits with families we know, and we ask guests to dress modestly and ask before taking photographs."],
                    ['coexist_image', 'Coexistence image', 'image', ''],
                    ['coexist_link', 'Coexistence link', 'text', '/experiences/rabari-culture/'],
                    ['team_enabled', 'Show “The Captains” section', 'bool', '1'],
                    ['team_kicker', 'Team kicker', 'text', "Our team"],
                    ['team_heading', 'Team heading', 'text', "Who you'll be out with"],
                    ['team_body', 'Team intro', 'textarea', "Our guides and trackers grew up in the villages around Jawai. They know where individual leopards tend to den, and they follow a simple rule: the animal comes first."],
                    ['values_items', 'Value cards (shown until team profiles are added) — one per line: Title | text', 'lines', "Local trackers | Our trackers are from the villages around the dam and know the hills well.\nRespect for the village | We drive on village land, so we keep noise down and stick to agreed tracks.\nPrivate vehicles | Your group has its own jeep, guide and timings.\nNo promises we can't keep | Sightings are never guaranteed. We switch the engine off and keep our distance."],
                    ['values_images', 'Value card images (same order as above)', 'images', ''],
                    ['journeys_enabled', 'Show curated journeys', 'bool', '1'],
                    ['journeys_heading', 'Journeys heading', 'text', "Sample itineraries"],
                    ['testimonials_enabled', 'Show guest words (only if testimonials exist)', 'bool', '1'],
                    ['journal_enabled', 'Show journal section', 'bool', '1'],
                    ['journal_heading', 'Journal heading', 'text', "From our journal"],
                    ['cta_heading', 'Closing call-to-action heading', 'text', "Ask us about dates"],
                    ['cta_body', 'Closing call-to-action text', 'textarea', "Send us your dates and who's travelling. We reply personally, usually within 12 hours, with options and prices. Nothing is booked or charged until you confirm."],
                    ['cta_image', 'Closing call-to-action background image', 'image', ''],
                ],
            ],
            'pages' => [
                'title' => 'Page Images',
                'icon' => 'image',
                'intro' => 'Header images for the listing and utility pages. (Safaris, journeys, articles and pages each choose their own image in Content.)',
                'fields' => [
                    ['img_safaris', 'Expeditions page (/safaris/)', 'image', ''],
                    ['img_journeys', 'Journeys page (/journeys/)', 'image', ''],
                    ['img_journal', 'Field Journal page (/journal/)', 'image', ''],
                    ['img_gallery', 'Gallery page (/gallery/)', 'image', ''],
                    ['img_faq', 'FAQ page (/faq/)', 'image', ''],
                    ['img_contact', 'Contact page (/contact/)', 'image', ''],
                    ['img_plan', 'Plan Your Journey (side image)', 'image', ''],
                    ['img_about', 'About page fallback (used if the About page has no image)', 'image', ''],
                    ['img_404', 'Page-not-found & photo credits', 'image', ''],
                    ['img_login', 'Admin login screen', 'image', ''],
                ],
            ],
            'email' => [
                'title' => 'Email & SMTP',
                'icon' => 'mail',
                'intro' => 'How the website sends email, who receives enquiry notifications, and the sender identity. Use “Send test email” after saving.',
                'fields' => [
                    ['mail_transport', 'Sending method', 'select', 'smtp', 'SMTP is strongly recommended (GoDaddy cPanel mailbox, Google Workspace, Zoho, SendGrid, Amazon SES…).', ['smtp' => 'SMTP server', 'mail' => 'PHP mail() (server default)', 'log' => 'Do not send — log only (testing)']],
                    ['smtp_host', 'SMTP host', 'text', 'localhost', 'GoDaddy cPanel: mail.yourdomain.com or localhost · Google: smtp.gmail.com · Zoho: smtp.zoho.in'],
                    ['smtp_port', 'SMTP port', 'number', '465', '465 = SSL, 587 = TLS/STARTTLS, 25 = none'],
                    ['smtp_encryption', 'Encryption', 'select', 'ssl', '', ['ssl' => 'SSL (port 465)', 'tls' => 'TLS / STARTTLS (port 587)', 'none' => 'None']],
                    ['smtp_auth', 'Server requires authentication', 'bool', '1'],
                    ['smtp_username', 'SMTP username', 'text', '', 'Usually the full mailbox address'],
                    ['smtp_password', 'SMTP password', 'secret', '', 'Stored encrypted. Leave blank to keep the current password.'],
                    ['smtp_timeout', 'Connection timeout (seconds)', 'number', '15'],
                    ['smtp_verify_peer', 'Verify SSL certificate', 'bool', '1', 'Turn off only if your host uses a self-signed mail certificate.'],
                    ['mail_from_email', 'From email address', 'email', 'no-reply@captainsofjawai.com', 'Should belong to your domain / SMTP account to avoid spam folders.'],
                    ['mail_from_name', 'From name', 'text', 'Captains of Jawai'],
                    ['mail_reply_to', 'Reply-to address for guest emails', 'email', 'contact@captainsofjawai.com'],
                    ['notify_recipients', 'Journey enquiry notifications → To', 'emails', 'contact@captainsofjawai.com', 'Comma-separated list of addresses that receive every new journey enquiry.'],
                    ['notify_cc', 'Journey enquiry notifications → CC', 'emails', ''],
                    ['notify_bcc', 'Journey enquiry notifications → BCC', 'emails', ''],
                    ['contact_recipients', 'Contact form messages → To', 'emails', '', 'Leave empty to use the journey enquiry recipients.'],
                    ['autoreply_enabled', 'Send confirmation email to the guest', 'bool', '1'],
                    ['email_footer', 'Footer text in all emails', 'textarea', 'Captains of Jawai · Private wildlife expeditions · Jawai, Rajasthan'],
                ],
            ],
            'templates' => [
                'title' => 'Email Templates',
                'icon' => 'template',
                'intro' => 'Subjects and messages. Placeholders: {name} {first_name} {code} {email} {phone} {country} {travel_window} {interests} {party} {accommodation} {message} {details} {admin_link} {site_name} {site_url}',
                'fields' => [
                    ['tpl_admin_subject', 'New enquiry → team subject', 'text', 'New journey enquiry {code} — {name}'],
                    ['tpl_admin_body', 'New enquiry → team message', 'textarea', "A new journey enquiry has arrived.\n\n{details}\n\nOpen it in the CRM: {admin_link}"],
                    ['tpl_guest_subject', 'Guest confirmation subject', 'text', 'We have received your enquiry ({code}) — Captains of Jawai'],
                    ['tpl_guest_body', 'Guest confirmation message', 'textarea', "Dear {first_name},\n\nThank you for reaching out to Captains of Jawai. Please note that this is a private expedition request, not an instant booking confirmation.\n\nA Captain will review your dates and preferences and reply personally within 12 hours with ideas, availability and a tailored proposal.\n\nYour reference: {code}\n\n{details}\n\nWarm regards,\nThe Captains of Jawai team"],
                    ['tpl_contact_admin_subject', 'Contact form → team subject', 'text', 'Website message from {name}'],
                    ['tpl_contact_admin_body', 'Contact form → team message', 'textarea', "{details}\n\nOpen in CRM: {admin_link}"],
                    ['tpl_contact_guest_subject', 'Contact form → guest subject', 'text', 'Thank you for your message — Captains of Jawai'],
                    ['tpl_contact_guest_body', 'Contact form → guest message', 'textarea', "Dear {first_name},\n\nThank you for writing to us. A member of our team will reply shortly.\n\nWarm regards,\nThe Captains of Jawai team"],
                ],
            ],
            'forms' => [
                'title' => 'Enquiry Form',
                'icon' => 'form',
                'intro' => 'Options and messages used by the multi-step “Plan Your Journey” form and the contact form.',
                'fields' => [
                    ['form_interests', 'Expedition interests (one per line)', 'lines', "Leopard Tracking\nWetland & Birding\nRabari Cultural Walk\nWildlife Photography\nFamily Journey\nBoulder Sundowner"],
                    ['form_accommodation', 'Accommodation options (one per line)', 'lines', "Luxury Wilderness Tented Camp\nBoutique Stone Villa\nHeritage Haveli / Fort\nAlready booked my own stay\nPlease advise me"],
                    ['form_transfers', 'Transfer options (one per line)', 'lines', "No transfer needed\nUdaipur airport / city\nJodhpur airport / city\nFalna railway station\nAhmedabad\nOther"],
                    ['form_nights', 'Stay length options (one per line)', 'lines', "1 night\n2 nights\n3 nights\n4+ nights\nNot sure yet"],
                    ['form_success_title', 'Success heading', 'text', "Thank you. We have your enquiry."],
                    ['form_success_message', 'Success message', 'textarea', "We'll reply personally, usually within 12 hours. Please check your spam folder if you don't see our email."],
                    ['form_trust_points', 'Trust points beside the form (one per line)', 'lines', "A personal reply, usually within 12 hours\nNo payment is taken on this website\nWe never share your details"],
                    ['form_rate_limit', 'Max submissions per IP per hour', 'number', '5'],
                    ['privacy_note', 'Consent text under forms', 'text', "By sending this form you agree to be contacted about your enquiry."],
                ],
            ],
            'seo' => [
                'title' => 'SEO & Tracking',
                'icon' => 'search',
                'intro' => 'Default meta tags, social preview image, analytics and custom code.',
                'fields' => [
                    ['seo_title_suffix', 'Title suffix', 'text', " | Captains of Jawai"],
                    ['seo_home_title', 'Homepage title', 'text', "Jawai Leopard Safari | Private Safaris in Jawai, Rajasthan"],
                    ['seo_home_description', 'Homepage meta description', 'textarea', "Private leopard safaris in Jawai (Jawai Bandh), Pali district, Rajasthan. Local trackers, small groups, dawn and dusk drives, birding at Jawai Dam and Rabari village visits."],
                    ['seo_og_image', 'Default social share image', 'image', ''],
                    ['seo_noindex', 'Hide entire site from search engines (staging)', 'bool', '0'],
                    ['google_verification', 'Google Search Console verification code', 'text', ''],
                    ['ga4_id', 'Google Analytics 4 measurement ID', 'text', '', 'e.g. G-XXXXXXX'],
                    ['head_code', 'Custom code inside <head>', 'code', ''],
                    ['body_code', 'Custom code before </body>', 'code', ''],
                ],
            ],
        ];
    }

    private static function index(): array
    {
        if (self::$index === null) {
            self::$index = [];
            foreach (self::groups() as $g) {
                foreach ($g['fields'] as $f) {
                    self::$index[$f[0]] = $f;
                }
            }
        }
        return self::$index;
    }

    public static function field(string $key): ?array
    {
        return self::index()[$key] ?? null;
    }

    public static function default(string $key): mixed
    {
        return self::index()[$key][3] ?? null;
    }

    public static function isSecret(string $key): bool
    {
        return (self::index()[$key][2] ?? '') === 'secret';
    }
}
