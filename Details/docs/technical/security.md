# Web Application Security Architecture

## 1. Defense-in-Depth Implementation
1. **SQL Injection Defense:** 100% of database queries execute through PDO prepared statements with strictly typed parameter binding. Zero raw string concatenation.
2. **Cross-Site Scripting (XSS):** Context-aware output escaping via `htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8')`.
3. **Cross-Site Request Forgery (CSRF):** Synchronizer token pattern with cryptographically secure per-session tokens validated on every POST/PUT/PATCH/DELETE request.
4. **File Upload Hardening:** Strict extension whitelist, MIME-type inspection via `finfo_file()`, storage outside public execution paths with renamed unique hashes.
5. **HTTP Security Headers (`.htaccess`):**
   ```apache
   Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
   Header always set X-Content-Type-Options "nosniff"
   Header always set X-Frame-Options "SAMEORIGIN"
   Header always set X-XSS-Protection "1; mode=block"
   Header always set Referrer-Policy "strict-origin-when-cross-origin"
   ```
