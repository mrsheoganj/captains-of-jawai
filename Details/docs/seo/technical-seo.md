# Technical SEO Architecture & Core Web Vitals

## 1. Technical Standards for 2026 Search Crawlers
- **Canonical URLs:** Strict self-referential canonical tags on all pages to prevent duplicate content indexing.
- **Clean Semantic HTML5:** `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`.
- **Core Web Vitals Targets:**
  - Largest Contentful Paint (LCP): **< 1.8s** (Preloaded hero image, critical CSS inline).
  - First Input Delay (FID) / Interaction to Next Paint (INP): **< 100ms** (Zero heavy JS framework overhead).
  - Cumulative Layout Shift (CLS): **< 0.05** (Explicit `width` and `height` attributes on all images and containers).
- **Robots.txt & XML Sitemap:**
  - Auto-generated `sitemap.xml` listing all canonical pages, safaris, and published journal articles.
  - `robots.txt` granting full crawler access to public routes while disallowing `/admin/`, `/api/`, and staging paths.
