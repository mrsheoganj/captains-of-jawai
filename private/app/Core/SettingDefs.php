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
                    ['color_accent', 'Accent colour (buttons, highlights)', 'color', '#B4532A'],
                    ['color_accent_hover', 'Accent hover colour', 'color', '#94421F'],
                    ['color_dark', 'Dark surface colour (footer, dark sections)', 'color', '#18221E'],
                    ['color_crimson', 'Secondary accent (Rabari crimson)', 'color', '#B93826'],
                    ['announcement_enabled', 'Show announcement bar', 'bool', '0'],
                    ['announcement_text', 'Announcement text', 'text', "Booking now for October 2026 to March 2027."],
                    ['announcement_link', 'Announcement link', 'text', '/book-a-safari/'],
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
                    ['hero_subtitle', 'Hero sub-headline', 'textarea', "Open-jeep leopard and wildlife safaris at dawn and dusk, with local trackers who know these hills. Safaris only: choose your drive, we take care of the rest."],
                    ['hero_images', 'Hero slideshow images (pick one or more)', 'images', ''],
                    ['hero_cta_primary', 'Primary button text', 'text', "Book a safari"],
                    ['hero_cta_secondary', 'Secondary button text', 'text', "See the safaris"],
                    ['ticker_enabled', 'Show moving ticker', 'bool', "1"],
                    ['ticker_items', 'Ticker items (one per line)', 'lines', "Leopard safaris\nJawai Dam wetland drives\nPhotography safaris\nDawn and dusk drives\nOctober to March season"],
                    ['intro_kicker', 'Intro kicker', 'text', "About Jawai"],
                    ['intro_heading', 'Intro heading', 'text', "Leopards, granite hills and a working countryside"],
                    ['intro_body', 'Intro text', 'textarea', "Jawai is a stretch of granite hills around the Jawai Dam in Pali district, roughly halfway between Udaipur and Jodhpur. Leopards den in the caves and rest on the rocks here, a short walk from villages, farms and hill temples. The Rabari herders who graze their animals on these hills have lived alongside them for generations.\n\nIt isn't a fenced national park. Our drives use village tracks and go out early in the morning and late in the afternoon, which is when the leopards are most often out on the rocks."],
                    ['intro_image', 'Intro image', 'image', ''],
                    ['stats', 'Key facts (one per line: value | label)', 'lines', "50–60 | Leopards estimated in the reserve\n1957 | Jawai Dam completed\n19.8 km² | Leopard conservation reserve\n2 | Drives a day, dawn and dusk"],
                    ['distances_enabled', 'Show distance map', 'bool', '1'],
                    ['distances_kicker', 'Distance map kicker', 'text', "Getting here"],
                    ['distances_heading', 'Distance map heading', 'text', "How far is Jawai?"],
                    ['distances_intro', 'Distance map text', 'textarea', "Approximate road distances from Bera, in the middle of the leopard hills. Times depend on traffic and road works."],
                    ['distances_items', 'Places (one per line: name | type | distance | time | latitude | longitude)', 'lines', "Udaipur Airport (UDR) | airport | 155 km | 3.5 hrs | 24.6177 | 73.8961\nUdaipur city | city | 141 km | 3 to 3.5 hrs | 24.5854 | 73.7125\nSumerpur Jawai Bandh station (JWB) | rail | 15 km | 25 min | 25.1160 | 73.1488\nFalna station (FA) | rail | 34 km | 45 min | 25.2353 | 73.2400\nJodhpur Airport (JDH) | airport | 175 km | 4 hrs | 26.2511 | 73.0489", 'Type is airport, rail or city. Leave latitude/longitude empty to list a place without putting it on the map.'],
                    ['jawai_images_enabled', 'Show Jawai photo strip', 'bool', '1'],
                    ['jawai_images_heading', 'Jawai photo strip heading', 'text', "Jawai in pictures"],
                    ['jawai_images', 'Jawai photo strip images (pick 5)', 'images', ''],
                    ['booking_enabled', 'Show booking section', 'bool', '1'],
                    ['booking_kicker', 'Booking section kicker', 'text', "Book a safari"],
                    ['booking_heading', 'Booking section heading', 'text', "Request your safari"],
                    ['booking_intro', 'Booking section intro', 'textarea', "Choose a safari, a date and a drive time. We check availability and reply personally, usually within 12 hours, with the price and where to meet. Nothing is charged until you confirm."],
                    ['booking_details', 'Booking details (one per line: label | text)', 'lines', "Drive times | Winter (Oct to Mar): 6:00 to 9:00 and 15:30 to 18:30. Summer: 5:00 to 8:00 and 16:30 to 19:30\nVehicle | Open 4x4 gypsy with driver and local tracker, up to 6 guests\nDuration | About 3 hours per drive\nSeason | October to March is best; we run all year\nPrice | Per vehicle, confirmed when you enquire\nPayment | Paid directly to us after confirmation; nothing is taken online\nNot included | Hotels, meals and transfers. We run safaris only"],
                    ['booking_image', 'Booking section image', 'image', ''],
                    ['home_gallery_enabled', 'Show gallery section', 'bool', '1'],
                    ['home_gallery_heading', 'Gallery section heading', 'text', "From the gallery"],
                    ['home_gallery_count', 'Number of gallery photos on the homepage', 'number', '8'],
                    ['safaris_kicker', 'Safaris section kicker', 'text', "What we offer"],
                    ['safaris_heading', 'Safaris section heading', 'text', "Jawai safaris"],
                    ['coexist_enabled', 'Show coexistence feature', 'bool', '1'],
                    ['coexist_kicker', 'Coexistence kicker', 'text', "Jawai culture"],
                    ['coexist_heading', 'Coexistence heading', 'text', "Herders and leopards on the same hills"],
                    ['coexist_body', 'Coexistence text', 'textarea', "The Rabari are pastoralists who have grazed sheep, goats, cattle and camels around Jawai for generations. Many hill shrines here are linked to local deities, and leopards are generally left alone, even when they take livestock. That tolerance is a big part of why the leopards are so relaxed around people. On our drives you pass their herds and hill shrines. We ask guests to keep a respectful distance and to ask before photographing anyone."],
                    ['coexist_image', 'Coexistence image', 'image', ''],
                    ['coexist_link', 'Coexistence link', 'text', '/jawai/culture/'],
                    ['team_enabled', 'Show “The Captains” section', 'bool', '1'],
                    ['team_kicker', 'Team kicker', 'text', "Our team"],
                    ['team_heading', 'Team heading', 'text', "Who you'll be out with"],
                    ['team_body', 'Team intro', 'textarea', "Our guides and trackers grew up in the villages around Jawai. They know where individual leopards tend to den, and they follow a simple rule: the animal comes first."],
                    ['values_items', 'Value cards (shown until team profiles are added) — one per line: Title | text', 'lines', "Local trackers | Our trackers are from the villages around the dam and know the hills well.\nRespect for the village | We drive on village land, so we keep noise down and stick to agreed tracks.\nPrivate vehicles | Your group has its own open jeep, tracker and timings.\nNo promises we can't keep | Sightings are never guaranteed. We switch the engine off and keep our distance."],
                    ['values_images', 'Value card images (same order as above)', 'images', ''],
                    ['testimonials_enabled', 'Show guest words (only if testimonials exist)', 'bool', '1'],
                    ['journal_enabled', 'Show journal section', 'bool', '1'],
                    ['journal_heading', 'Journal heading', 'text', "From our journal"],
                    ['cta_heading', 'Closing call-to-action heading', 'text', "Ask us about dates"],
                    ['cta_body', 'Closing call-to-action text', 'textarea', "Tell us which safari, the date and how many of you. We confirm availability and the price, usually within 12 hours. Nothing is charged until you confirm."],
                    ['cta_image', 'Closing call-to-action background image', 'image', ''],
                ],
            ],
            'pages' => [
                'title' => 'Page Images',
                'icon' => 'image',
                'intro' => 'Header images for the listing and utility pages. (Safaris, articles and pages each choose their own image in Content.)',
                'fields' => [
                    ['img_safaris', 'Expeditions page (/safaris/)', 'image', ''],
                    ['img_journal', 'Field Journal page (/journal/)', 'image', ''],
                    ['img_gallery', 'Gallery page (/gallery/)', 'image', ''],
                    ['img_faq', 'FAQ page (/faq/)', 'image', ''],
                    ['img_contact', 'Contact page (/contact/)', 'image', ''],
                    ['img_plan', 'Book a safari form (side image)', 'image', ''],
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
                    ['notify_recipients', 'Safari enquiry notifications → To', 'emails', 'contact@captainsofjawai.com', 'Comma-separated list of addresses that receive every new safari enquiry.'],
                    ['notify_cc', 'Safari enquiry notifications → CC', 'emails', ''],
                    ['notify_bcc', 'Safari enquiry notifications → BCC', 'emails', ''],
                    ['contact_recipients', 'Contact form messages → To', 'emails', '', 'Leave empty to use the safari enquiry recipients.'],
                    ['autoreply_enabled', 'Send confirmation email to the guest', 'bool', '1'],
                    ['email_footer', 'Footer text in all emails', 'textarea', 'Captains of Jawai · Private wildlife expeditions · Jawai, Rajasthan'],
                ],
            ],
            'templates' => [
                'title' => 'Email Templates',
                'icon' => 'template',
                'intro' => 'Subjects and messages. Placeholders: {name} {first_name} {code} {email} {phone} {country} {travel_window} {interests} {party} {drive_time} {drives} {message} {details} {admin_link} {site_name} {site_url}',
                'fields' => [
                    ['tpl_admin_subject', 'New enquiry → team subject', 'text', 'New safari enquiry {code}: {name}'],
                    ['tpl_admin_body', 'New enquiry → team message', 'textarea', "A new safari enquiry has arrived.\n\n{details}\n\nOpen it in the CRM: {admin_link}"],
                    ['tpl_guest_subject', 'Guest confirmation subject', 'text', 'Your safari request ({code}): Captains of Jawai'],
                    ['tpl_guest_body', 'Guest confirmation message', 'textarea', "Dear {first_name},\n\nThank you for reaching out to Captains of Jawai. Please note that this is a safari request, not a confirmed booking yet.\n\nWe will check availability for your date and drive time and reply personally, usually within 12 hours, with the price and the meeting point for your safari.\n\nYour reference: {code}\n\n{details}\n\nWarm regards,\nThe Captains of Jawai team"],
                    ['tpl_contact_admin_subject', 'Contact form → team subject', 'text', 'Website message from {name}'],
                    ['tpl_contact_admin_body', 'Contact form → team message', 'textarea', "{details}\n\nOpen in CRM: {admin_link}"],
                    ['tpl_contact_guest_subject', 'Contact form → guest subject', 'text', 'Thank you for your message: Captains of Jawai'],
                    ['tpl_contact_guest_body', 'Contact form → guest message', 'textarea', "Dear {first_name},\n\nThank you for writing to us. A member of our team will reply shortly.\n\nWarm regards,\nThe Captains of Jawai team"],
                ],
            ],
            'forms' => [
                'title' => 'Enquiry Form',
                'icon' => 'form',
                'intro' => 'Options and messages used by the “Book a safari” form and the contact form. The safari choices come from your published safaris (Content → Safaris).',
                'fields' => [
                    ['form_interests', 'Extra options shown after the safaris (one per line)', 'lines', "Sundowner on the rocks after an evening drive\nNot sure, please advise"],
                    ['form_drive_times', 'Drive time options (one per line)', 'lines', "Morning (sunrise)\nEvening (sunset)\nMorning and evening\nNo preference"],
                    ['form_drives', 'Number of drives options (one per line)', 'lines', "1 drive\n2 drives\n3 drives\n4 or more drives\nNot sure yet"],
                    ['form_scope_note', 'Note shown on the form', 'textarea', "We run safaris only. We do not book hotels or arrange airport or station transfers. Tell us where you are staying in Jawai and we will tell you where to meet."],
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
