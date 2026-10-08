# Performance Optimization & Core Web Vitals Strategy

## 1. Asset Delivery & Server Caching
- **Browser Caching via `.htaccess`:**
  - CSS / JS: Cached for 1 year with cache-busting query hashes (`app.css?v=2.1`).
  - WebP / AVIF Images: Cached for 1 year (`max-age=31536000, immutable`).
- **Gzip & Brotli Compression:** Enabled for text, HTML, CSS, JavaScript, and SVG assets.
- **Critical CSS Inlining:** Above-the-fold hero CSS inlined directly in `<head>` to eliminate render-blocking stylesheet latency.
