# Captains of Jawai: after-update admin checklist

Do these in the admin panel (your admin address, e.g. `https://captainsofjawai.com/pk-admin/`) after uploading this version.
Email/SMTP settings are not part of this update and do not need to be touched.

## 1. Upload the files
Either run **Actions → Deploy to GoDaddy** on GitHub (see README, "Deploying with GitHub Actions"), or upload the update package by cPanel File Manager:

* `public_html/` → into your `public_html/` (overwrite). **Do not delete** `public_html/uploads/`.
* `private/` → into your `private/` folder next to `public_html` (overwrite). **Keep** your `private/config.php` and `private/storage/`.

Open the website once. The database updates itself automatically (new menus, photo-credits link, legal-page clean-up).

## 2. Load the new text (one click)
**System → Update website text to the latest version** (asks for your admin password).
This loads everything from the October 2026 content report:

* Safari timings **Morning 6:00–9:00 AM · Evening 4:00–7:00 PM**, private **and** shared 4x4 gypsy options
* Temples of Jawai: **Dev Giri (Ashapura Mata cave temple)**, **Kambeshwar Mahadev**, **Khodiyar Mata & Tankeshwar Mahadev**
* Wildlife additions: **sloth bear, Indian wolf, chinkara, desert fox, porcupine, sarus crane, pelicans**, crocodile numbers
* New FAQs (temples; sloth bears and wolves), new page titles and meta descriptions

Your enquiries, settings, users and photos are kept. Text you typed yourself into Safaris, Pages or FAQs is replaced, so redo any personal edits afterwards.

## 3. Photos (done automatically by the deploy)
The deploy imports 62 hand-picked photos taken in Jawai (Wikimedia Commons) and gives every spot on the website its own photo:
slider, intro, safaris, "Jawai in pictures", seasons, the new **Village life** section, the Jawai guide pages and the page banners.
Similar shots (more frames of the same leopard, more flamingos) are in the **Gallery** only, never in a second spot on the site.
Exact duplicate files in the media library are merged into one (removed copies are kept in `private/storage/removed-media/`).
Photos taken outside Jawai stay in the media library but are not shown in the Gallery.
Photographer and licence are shown on the **Photo credits** page (linked in the footer), as the CC BY / BY-SA licences require.
This runs once; after that, any photo you pick in the admin stays. To change a spot: Settings → Homepage (or the page/safari) → pick another photo.

## 4. Settings to fill in (Settings → …)
| Where | What | Why |
|---|---|---|
| Contact & Social | **Phone** and **WhatsApp number** (digits with country code, e.g. `919876543210`) | Turns on the call buttons, the WhatsApp bar and **"Or book on WhatsApp"** on the booking form |
| Contact & Social | Address, Instagram, Google Business, TripAdvisor links | Shown in the footer and used in Google's business data |
| Enquiry Form → Booking types | Add prices if you want them shown, e.g. `Morning leopard safari \| 6:00 to 9:00 AM \| From ₹4,000 per jeep` | Prices appear on the booking cards |
| Homepage → Booking details | Check the Price and Payment lines | Shown next to the booking form |
| SEO & Tracking | Google Analytics ID, Search Console verification | Measure visits; submit `https://captainsofjawai.com/sitemap.xml` |

## 5. Testimonials
Add **real** guest reviews only, in **Content → Testimonials** (copy them from Google or TripAdvisor with the guest's permission). The section appears on the homepage automatically once one exists. Do not publish invented reviews.

## 6. Security
* Remove the SSH key that was shared in chat (cPanel → SSH Access → Manage SSH Keys) and create a new one.
* Use a strong admin password; the login already locks out after repeated failed attempts.
* In cPanel → Domains, make sure **Force HTTPS Redirect** is on. The site itself now redirects `www` and `http` to `https://captainsofjawai.com` in one step.
