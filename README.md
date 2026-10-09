# Captains of Jawai — Website, CMS & Enquiry CRM

A modern, fast, inquiry-led website for **Captains of Jawai** (private leopard safaris in Jawai, Rajasthan), with a full admin panel where every text, image, menu, email route, SMTP setting and SEO field can be changed without touching code.

Built from the project blueprint in [`Details/docs`](Details/docs/PROJECT_MASTER.md): PHP 8.1+ (tested on 8.3), MySQL/MariaDB, plain CSS and JavaScript. There's no Node.js and no build step, so it runs on GoDaddy shared cPanel hosting.

---

## What's included

### Public website
| Page | URL |
| --- | --- |
| Homepage: cinematic hero slideshow, field ticker, story, key facts, expeditions, Rabari coexistence, the Captains, curated journeys, guest words, journal, consultation CTA | `/` |
| Expeditions hub and detail pages (facts strip, highlights, sticky enquiry card) | `/safaris/`, `/safaris/{slug}/` |
| Curated journeys with a day-by-day timeline | `/journeys/`, `/journeys/{slug}/` |
| Field Journal (categories, pagination, related articles, share buttons) | `/journal/`, `/journal/{slug}/` |
| Gallery (category filter and lightbox) | `/gallery/` |
| FAQ (accordion and FAQPage schema) | `/faq/` |
| About (story, values, team) | `/about/` |
| Contact (AJAX form, map embed) | `/contact/` |
| **Plan Your Journey**, a 5-step enquiry wizard | `/plan-your-journey/` |
| CMS pages, with nesting allowed (e.g. `experiences/rabari-culture`) | `/{any/path}/` |
| Dynamic XML sitemap and robots.txt | `/sitemap.xml`, `/robots.txt` |

Other features:
* Mobile sticky dock (WhatsApp + Plan Journey), a floating WhatsApp button and a full-screen mobile menu.
* Responsive WebP images and scroll reveals, with reduced-motion support.
* **No online booking or payment.** Every call to action leads to a consultation, as the brief requires.

### Admin panel (`/admin` by default; the path can be changed at install)
* **Dashboard:** KPIs (new enquiries vs the previous week, active proposals, conversion rate, average first-response time), a 30-day chart, the pipeline, overdue leads and a launch checklist.
* **Enquiry CRM:** a 10-stage pipeline (New → Contacted → Qualified → Proposal sent → Follow-up → Confirmed → Active → Completed / Lost / Spam), plus:
  * owner assignment, follow-up dates, notes and an activity history;
  * one-click Email, WhatsApp and Call buttons;
  * search, filters, bulk actions and CSV export;
  * a "re-send notification" button.
* **Content (CMS):** Safaris, Journeys, Field Journal articles (drafts and scheduled posts), Pages, FAQs, Team/Captains and Testimonials.
  * Content uses a rich-text editor with an HTML view.
  * Items can be reordered by drag-and-drop, duplicated, saved as drafts and previewed.
* **Media library:** drag-and-drop upload with automatic resizing (1600/800/400 px) and WebP conversion. Each image has alt text, caption, credit/licence and gallery fields.
* **Navigation menus:** a drag-and-drop editor for the header (with dropdowns) and the footer columns.
* **SEO:**
  * Every content item gets a meta title and description, with a live Google preview and character counters.
  * An **SEO overview** audits every URL, and a site checklist covers the rest.
  * There's a 301/302 **redirect manager** and an editable **robots.txt**.
  * The XML sitemap updates automatically.
  * Google Analytics 4 and Search Console verification can be set, along with custom head/body code.
  * Schema.org markup is built in: TravelAgency, WebSite, TouristTrip, BlogPosting, FAQPage and BreadcrumbList.
  * Open Graph and Twitter cards are included.
* **Settings** (each tab is editable):
  * *General & Branding:* site name, tagline, logos, **brand colours**, announcement bar, maintenance mode.
  * *Contact & Social:* email, phone, WhatsApp number and message, address, map, social links, legal name, GSTIN.
  * *Homepage:* every headline, paragraph and image, including the hero slideshow, with show/hide for each section.
  * **Email & SMTP:**
    * sending method (SMTP / PHP mail / log-only) and SMTP host, port, SSL/TLS, username and password (stored encrypted);
    * from-address, reply-to, and **notification recipients (To / CC / BCC)**, with separate recipients for contact-form messages;
    * guest auto-reply on or off, and a **"Send test email"** button that shows the SMTP conversation for troubleshooting.
  * **Email templates:** subjects and bodies for team and guest emails, with placeholders such as `{name}`, `{code}` and `{details}`.
  * *Enquiry form:* interest, accommodation, transfer and stay options, success message, trust points and rate limit.
  * *SEO & Tracking:* default titles, social image, noindex (staging) switch, GA4, verification and custom code.
* **Email log:** shows every email sent, and why any failed.
* **Users & roles:** Super Admin, Expedition Manager, Sales/Concierge, Content Editor and Read Only.
* **Activity log** and a **System** page (environment info and recent PHP errors).

### Security
* All SQL uses prepared statements, and all output is escaped.
* Every form carries a CSRF token.
* Passwords are hashed with Argon2id, and repeated failed logins lock the IP out for 15 minutes.
* Sessions are hardened and time out after 60 idle minutes.
* Public forms are protected by a honeypot, a minimum fill time and a per-IP rate limit.
* Uploads are checked by their real content type and re-encoded with GD, and scripts cannot run in `/uploads`.
* Rich text goes through a whitelist HTML sanitiser.
* The SMTP password is encrypted at rest with libsodium.
* `private/` sits outside the web root, and admin pages send `noindex`.

---

## Folder structure

```
private/                 ← application code (upload OUTSIDE public_html)
  app/Core/              ← framework: router, DB, settings, auth, mailer, media, schema, seeder
  app/Controllers/       ← public site + form API + installer
  app/Admin/             ← admin controllers and content-type definitions (Resources.php)
  views/                 ← PHP templates (site/, admin/, emails/, install/)
  lib/PHPMailer/         ← PHPMailer 6.12 (bundled, no Composer needed)
  storage/               ← logs, sessions, SQLite DB (if used) — must be writable
  database/schema.sql    ← reference MySQL schema (the installer creates it for you)
  cli/install.php        ← command-line installer / migrations
  config.php             ← created by the installer (DB credentials, app key) — not in git
public_html/             ← web root
  index.php              ← single front controller
  .htaccess              ← HTTPS, routing, security headers, caching
  assets/                ← css, js, logo derivatives, starter photos
  uploads/               ← media library files (must be writable)
Details/                 ← research, brand, UX, SEO and technical documentation
```

---

## Deploying on GoDaddy cPanel

1. **PHP version:** in cPanel, go to **MultiPHP Manager** and set the domain to **PHP 8.3**. In **Select PHP Version → Extensions**, make sure `pdo_mysql`, `gd`, `mbstring`, `openssl`, `fileinfo` and `sodium` are enabled.
2. **Database:** in cPanel, open **MySQL® Databases**.
   1. Create a database, e.g. `cpuser_jawai`.
   2. Create a user with a strong password.
   3. Add the user to the database with **ALL PRIVILEGES**.
3. **Upload files** with File Manager or FTP:
   * Upload the `private/` folder to your **home directory** (`/home/cpuser/private`). It must sit **next to** `public_html`, not inside it.
   * Upload the **contents** of `public_html/` into your existing `public_html/`, replacing the old site files.
4. **Permissions:** `private/`, `private/storage/` and `public_html/uploads/` need to be writable (755 is usually fine on cPanel).
5. **SSL:** make sure AutoSSL is active for `captainsofjawai.com` and `www`. The `.htaccess` file forces HTTPS.
6. **Run the installer:** open `https://captainsofjawai.com/install` and enter the following.
   * The database details from step 2.
   * The site address and the email address that should receive enquiries.
   * An **admin path**. Changing it from `admin` (e.g. to `captains-desk`) hides the login page from bots.
   * Your admin name, email and password.

   The installer creates all tables, loads starter content and locks itself.
7. **Sign in** at `/admin` (or your custom path) and work through the **Launch checklist** on the dashboard.
   1. **Settings → Email & SMTP.**
      * Enter your mailbox details. For a cPanel mailbox use host `mail.captainsofjawai.com` (or `localhost`), port **465**, SSL, the full email address as username, and the mailbox password.
      * Save, then **Send test email**.
      * Set **notification recipients** (To/CC/BCC) for journey enquiries, plus separate recipients for contact messages if wanted.
   2. **Settings → Contact & Social:** phone, WhatsApp number (digits with country code, e.g. `919876543210`), address and social links.
   3. **Media library:** upload authentic Jawai photography and replace the placeholder photos. In **Settings → Homepage**, choose the hero slideshow and section images.
   4. **Team / Captains:** add naturalist bios and portraits.
   5. **Settings → SEO & Tracking:** add the GA4 ID and Search Console verification, then submit `https://captainsofjawai.com/sitemap.xml` in Google Search Console.

**No SSH?** Everything above works through the browser. If you do have SSH, you can install from the command line instead:

```bash
php private/cli/install.php --driver=mysql --db=cpuser_jawai --user=cpuser_web --pass='…' \
  --email=you@captainsofjawai.com --password='…' --name="Your Name" --url=https://captainsofjawai.com
```

### Updating later
Upload the new files over the old ones. Keep `private/config.php`, `private/storage/` and `public_html/uploads/`. Any new database tables or columns are created automatically on the next page load. If you have SSH you can also run `php private/cli/install.php --migrate`. Back up the database (cPanel → Backup) and `public_html/uploads/` regularly.

### Moving the old site out of the way
The previous prototype pages (`about.php`, `contact.php`, old `/admin/*` PHP files) have been removed. `/about.php` and `/contact.php` now 301-redirect to the new URLs. Add more redirects in **SEO → Redirects** if Google has indexed other old URLs.

---

## Local development

```bash
# SQLite (no database server needed)
php private/cli/install.php --driver=sqlite --email=admin@example.com --password=ChangeMe12345 --url=http://localhost:8080
php -S localhost:8080 -t public_html private/cli/dev-router.php
# → http://localhost:8080  and  http://localhost:8080/admin
```

To start over, delete `private/config.php` and `private/storage/database.sqlite`.

---

## Before launch: client information still needed

These are deliberately **not invented** (see `Details/docs/CLIENT_INPUT_REQUIRED.md`). Enter them in the admin:

| Item | Where |
| --- | --- |
| Phone, WhatsApp, address, legal name, GSTIN, social links | Settings → Contact & Social |
| Naturalist names, bios and portraits | Content → Team / Captains |
| Authentic photography (the starter photos are placeholders) | Media library → filter "Placeholders" |
| Genuine guest reviews only | Content → Testimonials |
| Privacy, terms and cancellation policies (templates provided) | Content → Pages |
| Pricing, fleet and partner lodges (if you want them published) | Content → Safaris / Journeys / Pages |

The starter photos in `public_html/assets/photos/` came with the previous build. The brand guidelines require authentic, rights-cleared Jawai photography, so please replace them before launch. The media library flags them as **Placeholder**.
