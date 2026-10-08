# Technology Stack Specification

| Layer | Component | Technology / Library | Purpose & Rationale |
| :--- | :--- | :--- | :--- |
| **Web Server** | HTTP Server | Apache 2.4 (`.htaccess` routing) | Native GoDaddy cPanel web server; handles URL rewrites and SSL enforcement. |
| **Backend Runtime** | Programming Language | PHP 8.3 | High execution speed, low memory footprint, native PDO database abstraction. |
| **Database** | RDBMS | MariaDB 10.6+ / MySQL 8.0 | ACID-compliant relational storage for inquiries, content, and media logs. |
| **Frontend Markup** | Document Structure | Semantic HTML5 | Accessible, SEO-friendly, clean semantic hierarchy. |
| **Styling & Design** | CSS Engine | Modern CSS3 (Variables, Grid, Flexbox) | Zero runtime overhead; custom design system tokens. |
| **Client Scripting** | Interactive Logic | Vanilla ES6+ JavaScript | Lightweight progressive enhancement (multi-step form, mobile drawer, modal). |
| **Email Transport** | Transactional Mail | PHPMailer via Authenticated cPanel SMTP | Reliable inbox delivery for inquiry notifications and traveler confirmations. |
| **Image Processing** | Media Optimization | PHP GD / Imagick | Automated WebP generation and responsive resizing on upload. |
| **Security** | Protection Layer | CSRF Tokens, PDO Bound Parameters, Security Headers | Defense-in-depth protection against XSS, SQLi, and brute-force attacks. |
