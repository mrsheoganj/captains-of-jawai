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
                    ['site_tagline', 'Tagline', 'text', 'Mastering the Granite Wilderness'],
                    ['site_description', 'Short brand description (footer)', 'textarea', 'Private, naturalist-led wildlife expeditions across the ancient granite leopard hills of Jawai, Rajasthan. Inquiry-led, bespoke, and ethically guided.'],
                    ['logo_image', 'Header logo (optional — leave empty for the built-in emblem)', 'image', ''],
                    ['footer_logo_image', 'Footer logo (optional — leave empty for the full brand logo)', 'image', ''],
                    ['color_accent', 'Accent colour (buttons, highlights)', 'color', '#B8822B'],
                    ['color_accent_hover', 'Accent hover colour', 'color', '#9E6B1D'],
                    ['color_dark', 'Dark surface colour (footer, dark sections)', 'color', '#121416'],
                    ['color_crimson', 'Secondary accent (Rabari crimson)', 'color', '#B93826'],
                    ['announcement_enabled', 'Show announcement bar', 'bool', '0'],
                    ['announcement_text', 'Announcement text', 'text', 'Season 2026–27 is open — private expeditions from October to March.'],
                    ['announcement_link', 'Announcement link', 'text', '/plan-your-journey/'],
                    ['maintenance_mode', 'Maintenance mode (visitors see a holding page; logged-in admins see the site)', 'bool', '0'],
                    ['maintenance_message', 'Maintenance message', 'textarea', 'We are polishing a few details. Please check back shortly, or write to us directly.'],
                    ['copyright_text', 'Copyright line', 'text', '© {year} Captains of Jawai. All rights reserved.'],
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
                    ['hero_kicker', 'Hero kicker (small line above headline)', 'text', "25°06'N · 73°10'E — Pali District, Rajasthan"],
                    ['hero_title', 'Hero headline', 'text', 'The Kingdom of Granite and Gold.'],
                    ['hero_subtitle', 'Hero sub-headline', 'textarea', 'Private, naturalist-led wildlife expeditions across the ancient leopard hills of Jawai.'],
                    ['hero_images', 'Hero slideshow images (pick one or more)', 'images', ''],
                    ['hero_cta_primary', 'Primary button text', 'text', 'Plan Your Journey'],
                    ['hero_cta_secondary', 'Secondary button text', 'text', 'Explore Safaris'],
                    ['ticker_enabled', 'Show field ticker', 'bool', '1'],
                    ['ticker_items', 'Ticker items (one per line)', 'lines', "Bespoke 4x4 Expeditions\nResident Leopards of the Granite Hills\nOver 300 Bird Species\nEthical Tracking — Never Guaranteed, Always Respectful\nSacred Rabari Coexistence\nJawai Bandh Wetland"],
                    ['intro_kicker', 'Intro kicker', 'text', 'Why Jawai'],
                    ['intro_heading', 'Intro heading', 'text', 'Where apex predators walk among sacred temples and nomadic herdsmen.'],
                    ['intro_body', 'Intro text', 'textarea', "For millennia, the granite hills of Jawai have stood silent watch over the Thar Desert's eastern edge. Here, among sun-warmed monoliths and acacia scrub, wild Indian leopards live alongside red-turbaned Rabari pastoralists in an extraordinary covenant of coexistence.\n\nThere are no rigid park gates or convoy routes here — only open granite country, patient tracking, and the quiet thrill of a leopard surfacing on a rock at first light."],
                    ['intro_image', 'Intro image', 'image', ''],
                    ['stats', 'Key facts (one per line: value | label)', 'lines', "850M yrs | Age of the granite kopjes\n300+ | Bird species recorded\n4–6 | Guests per private vehicle\n12 hrs | Personal reply to every enquiry"],
                    ['safaris_kicker', 'Safaris section kicker', 'text', 'Signature Expeditions'],
                    ['safaris_heading', 'Safaris section heading', 'text', 'Three ways into the wild heart of Jawai'],
                    ['coexist_enabled', 'Show coexistence feature', 'bool', '1'],
                    ['coexist_kicker', 'Coexistence kicker', 'text', 'The Living Pact'],
                    ['coexist_heading', 'Coexistence heading', 'text', 'A sacred coexistence, centuries in the making.'],
                    ['coexist_body', 'Coexistence text', 'textarea', 'The Rabari pastoralists have grazed their herds beneath these hills for generations. Their reverence for the leopard — seen as the guardian of the hill shrines — has created one of the rare places on Earth where a large cat and people share the same ground in relative peace. We introduce guests to this world as respectful visitors, never as spectators.'],
                    ['coexist_image', 'Coexistence image', 'image', ''],
                    ['coexist_link', 'Coexistence link', 'text', '/experiences/rabari-culture/'],
                    ['team_enabled', 'Show “The Captains” section', 'bool', '1'],
                    ['team_kicker', 'Team kicker', 'text', 'Field Leadership'],
                    ['team_heading', 'Team heading', 'text', 'Meet the Captains'],
                    ['team_body', 'Team intro', 'textarea', 'Our expedition leaders are lifelong trackers who grew up among these granite kopjes. They read alarm calls, wind and light — and they always put the animal first.'],
                    ['journeys_enabled', 'Show curated journeys', 'bool', '1'],
                    ['journeys_heading', 'Journeys heading', 'text', 'Curated multi-day journeys'],
                    ['testimonials_enabled', 'Show guest words (only if testimonials exist)', 'bool', '1'],
                    ['journal_enabled', 'Show journal section', 'bool', '1'],
                    ['journal_heading', 'Journal heading', 'text', 'From the Field Journal'],
                    ['cta_heading', 'Closing call-to-action heading', 'text', 'Speak with an Expedition Captain'],
                    ['cta_body', 'Closing call-to-action text', 'textarea', 'Tell us when you would like to travel and what moves you. A Captain will personally reply within 12 hours with ideas, availability and a tailored proposal — no automated quotes, no obligation.'],
                    ['cta_image', 'Closing call-to-action background image', 'image', ''],
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
                    ['form_success_title', 'Success heading', 'text', 'Your journey has begun.'],
                    ['form_success_message', 'Success message', 'textarea', 'Thank you. A Captain will personally reply within 12 hours. Please check your inbox (and spam folder) for our confirmation.'],
                    ['form_trust_points', 'Trust points beside the form (one per line)', 'lines', "Personal reply from an Expedition Captain within 12 hours\nNo automated quotes, no payment taken online\nYour details stay private — never shared or sold"],
                    ['form_rate_limit', 'Max submissions per IP per hour', 'number', '5'],
                    ['privacy_note', 'Consent text under forms', 'text', 'By sending this form you agree to be contacted about your enquiry. See our privacy policy.'],
                ],
            ],
            'seo' => [
                'title' => 'SEO & Tracking',
                'icon' => 'search',
                'intro' => 'Default meta tags, social preview image, analytics and custom code.',
                'fields' => [
                    ['seo_title_suffix', 'Title suffix', 'text', ' | Captains of Jawai'],
                    ['seo_home_title', 'Homepage title', 'text', 'Jawai Leopard Safari & Private Expeditions | Captains of Jawai'],
                    ['seo_home_description', 'Homepage meta description', 'textarea', 'Private 4x4 leopard tracking across the ancient granite hills of Jawai, Rajasthan. Expert local naturalists, bespoke journeys, ethical wildlife viewing.'],
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
