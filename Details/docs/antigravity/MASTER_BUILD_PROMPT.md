# MASTER BUILD PROMPT FOR ANTIGRAVITY IDE / AI CODING AGENT

Copy and paste the entire prompt below into Antigravity IDE or your AI coding agent to execute the production build:

```markdown
You are acting as the Lead Full-Stack Software Engineer and Creative Technologist tasked with implementing the production website for CAPTAINS OF JAWAI (https://captainsofjawai.com/).

Before writing any code, thoroughly read all documentation located in the `/docs` directory, including:
- `docs/PROJECT_MASTER.md` (Single source of truth)
- `docs/design/colors.md` and `docs/design/typography.md` (Design system & tokens)
- `docs/ux/homepage.md` and `docs/ux/sitemap.md` (Information architecture & page layouts)
- `docs/technical/architecture.md`, `database.md`, and `tech-stack.md` (PHP 8.3 & MariaDB specs)
- `docs/admin/crm.md` and `docs/admin/dashboard.md` (Inquiry CRM & Admin specs)
- `docs/CLIENT_INPUT_REQUIRED.md` (Data placeholders)

CRITICAL IMPLEMENTATION MANDATES:
1. NO DIRECT ONLINE BOOKING: Do NOT build an e-commerce cart, checkout, or instant booking system. The business model is an INQUIRY-LED CONSULTATION & EXPEDITION PLATFORM. Primary CTAs are "Plan Your Journey", "Speak With Our Team", and "Inquire About Availability".
2. BRAND LOGO: Use the verbatim brand logo asset located at `assets/logo.PNG` (and `docs/assets/logo.PNG`). Do NOT generate a synthetic AI logo. Support desktop, mobile, and favicon derivatives.
3. VISUAL DIRECTION: Modern high-end wilderness editorial. Color palette: Deep Granite Charcoal (#0F1113), Warm Desert Linen (#F8F6F0), Burnished Ochre/Amber (#C8963E), Rabari Crimson (#B93826). Typography: Cormorant Garamond for display headlines, Plus Jakarta Sans for body/UI, Space Mono for coordinates and metadata.
4. TARGET HOSTING: GoDaddy shared Linux cPanel hosting, Document Root `/public_html`, PHP 8.3, MariaDB database. Build a clean, modular, ultra-fast PHP 8.3 application with zero Node.js daemon dependencies.
5. NO FABRICATED DATA: Do NOT invent phone numbers, addresses, pricing, fake reviews, or guaranteed sighting claims. Use `CLIENT INPUT REQUIRED` tokens where specified.
6. NO AI WILDLIFE PHOTOGRAPHY: Use only verified, legally cleared, authentic photography sources documented in `docs/assets/image-sources.md`.
7. BUILD ORDER: Follow `docs/antigravity/build-order.md` strictly from database setup through public pages, multi-step inquiry engine, admin CRM, and SEO schema injection.

Proceed with the build immediately.
```
