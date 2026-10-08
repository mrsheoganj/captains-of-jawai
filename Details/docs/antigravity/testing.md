# Quality Assurance, Security & Penetration Testing

## Automated & Manual Testing Checklist
1. **Form Validation & Anti-Spam:** Test inquiry submission with empty fields, malformed emails, malicious script tags (`<script>alert(1)</script>`), and rapid spam bursts. Verify honeypot field traps bots silently.
2. **SQL Injection Verification:** Test all dynamic URL query parameters (`/safaris/{slug}`, `/journal/{slug}`, `/api/admin/enquiries?status=`) with single-quote payloads (`' OR '1'='1`). Ensure PDO throws handled PDOExceptions and returns zero data leakage.
3. **Responsive Breakpoint Verification:** Test across:
   - iPhone 14/15/16 Pro (393px width)
   - Samsung Galaxy S23 (360px width)
   - iPad Air / Mini (768px - 820px width)
   - Desktop 14" MacBook (1440px width)
   - Desktop 27" 4K Monitor (1920px - 2560px width)
4. **Core Web Vitals & Lighthouse:** Audit on mobile emulation:
   - Performance: >= 95
   - Accessibility: >= 98
   - Best Practices: 100
   - SEO: 100
