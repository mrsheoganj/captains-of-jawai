# Technical Architecture & Infrastructure Analysis

## 1. Hosting Environment & Server Specifications
- **Primary Domain:** `https://captainsofjawai.com/` (and `www.captainsofjawai.com`)
- **Hosting Provider:** GoDaddy Web Hosting (Linux cPanel Environment)
- **Document Root:** `/public_html`
- **Current Server IP:** `118.139.178.249`
- **PHP Version:** PHP 8.3 (with OPcache, PDO, MySQLi, GD/Imagick, cURL, OpenSSL enabled)
- **Database Server:** MariaDB / MySQL 8.x
- **Web Server:** Apache 2.4 with `mod_rewrite`, `mod_headers`, `mod_deflate` / Brotli compression.

---

## 2. Architectural Option Analysis

### OPTION A: Lightweight High-Performance Hybrid (RECOMMENDED PRODUCTION ARCHITECTURE)
- **Frontend:** Semantic, ultra-clean HTML5, Modern CSS Variables, and Vanilla ES6 JavaScript (Zero heavy client framework overhead like React/Vue runtime bloat).
- **Backend:** Modular PHP 8.3 MVC micro-framework with clean object-oriented architecture, PDO prepared statements, and custom lightweight router.
- **Admin Suite:** Built natively in PHP 8.3 + Tailwind CSS (compiled ahead of time) / Alpine.js for interactive CRM tables.
- **Database:** Fully normalized MariaDB relational database on localhost.
- **Advantages:**
  - 100% native compatibility with GoDaddy shared cPanel hosting.
  - Zero Node.js runtime dependencies or daemon process requirements that break on shared servers.
  - Blazing Core Web Vitals performance (sub-second load times, 98+ Google Lighthouse scores).
  - Effortless maintenance, low hosting cost, and bulletproof security.

### OPTION B: Monolithic Traditional CMS (WordPress)
- **Drawbacks:** Vulnerable to security exploits, bloated SQL queries, requires 25+ third-party plugins for CRM and SEO, sluggish page speeds, frequent maintenance breaks on shared hosting. **REJECTED.**

### OPTION C: Decoupled Headless Jamstack (Next.js on Vercel + Headless CMS)
- **Drawbacks:** Requires dual hosting environments, monthly recurring third-party SaaS subscription costs, complex API authentication, and splits domain DNS management across platforms. **REJECTED FOR MVP.**

---

## 3. Selected Architecture: OPTION A
Option A delivers an uncompromising, ultra-fast, luxury editorial experience with a tailored safari CRM that runs natively, securely, and permanently on the client's existing GoDaddy cPanel infrastructure.
