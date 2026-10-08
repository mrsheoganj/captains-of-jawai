# Step-by-Step Implementation Sequence

An AI engineer or Antigravity IDE must implement the system in this strict chronological order:

1. **Phase 1: Environment & Database Foundations**
   - Execute MariaDB schema from `docs/technical/database.md`.
   - Setup project file hierarchy (separating `/private` from `/public_html`).
   - Configure PDO database singleton connection with environment variable overrides.

2. **Phase 2: Core Styling & Design Tokens**
   - Implement CSS custom properties from `docs/design/colors.md` and `docs/design/typography.md`.
   - Setup Google Fonts loading (`Cormorant Garamond`, `Plus Jakarta Sans`, `Space Mono`).
   - Implement base responsive grid, reset, and container classes.

3. **Phase 3: Public Experience Pages**
   - Implement `Header.tsx` / `header.php` with sticky scroll blur and `logo.PNG`.
   - Build Homepage (`/`) with 10 sections specified in `docs/ux/homepage.md`.
   - Build Safari Hub (`/safaris/`) and detail routes (`/safaris/leopard-safari/`, etc.).
   - Build Wilderness & Landscape Pages (`/jawai/`, `/jawai/dam-wetlands/`).
   - Build Rabari Heritage Page (`/experiences/rabari-culture/`).

4. **Phase 4: The Journey Inquiry Engine (Lead Gen)**
   - Build multi-step client inquiry form (`/plan-your-journey/`).
   - Build backend API endpoint `POST /api/enquiries` with CSRF protection, sanitization, and DB storage.
   - Configure transactional email alert dispatch via PHPMailer SMTP.
   - Build mobile sticky conversion dock.

5. **Phase 5: Secure Admin Suite & CRM**
   - Build admin authentication system with Argon2id password hashing and session hardening.
   - Build Executive Dashboard with KPI counters and upcoming expedition calendar.
   - Build Inquiry CRM with 9-stage pipeline, status dropdowns, and staff notes.
   - Build Headless CMS editors for Safaris, Journal Posts, FAQs, and Media Library.

6. **Phase 6: Technical SEO, Performance & Verification**
   - Inject Schema.org JSON-LD structured data into all templates.
   - Setup dynamic `sitemap.xml` and `robots.txt`.
   - Verify Core Web Vitals (LCP < 1.8s, CLS < 0.05).
   - Conduct complete security penetration test against SQLi, XSS, and CSRF.
