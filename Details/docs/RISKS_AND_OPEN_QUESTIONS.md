# Risk Register & Technical Risk Mitigations

| Risk Description | Severity | Probability | Impact | Mitigation Strategy | Owner |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **SSL / HTTPS Configuration Issue on GoDaddy** | High | Medium | Site warnings in browser; SEO penalty; broken form submissions. | Run AutoSSL force-renewal in cPanel; enforce canonical HTTPS redirection via `.htaccess` with HSTS headers. | DevOps / Lead Eng |
| **Copyright Infringement from Unauthorized Images** | Critical | High (if unmanaged) | Legal takedown notices, financial damages, brand damage. | Enforce strict ban on Pinterest downloads; mandate verified CC-BY / Unsplash / client-owned photos with rights ledger. | Content Lead |
| **Client Expectation of Instant Booking vs. Inquiry** | High | Low | Confusion over unconfirmed dates; operational overbooking. | Clear UX messaging across all forms: "Inquiry & Consultation Only — No Instant Charges"; automated expectation email. | Product Architect |
| **Spam & Bot Infiltration on Inquiry Form** | Medium | High | CRM clogged with fake leads; email notification flooding. | Implement zero-friction invisible honeypot fields, timestamp validation, and IP rate limiting on `/api/enquiries`. | Backend Engineer |
| **Mobile Performance Degradation on Slow 4G** | High | Medium | Luxury users bounce on smartphone networks. | Zero heavy client frameworks; modern WebP/AVIF compression; inline critical CSS; preloaded hero assets. | Frontend Engineer |
| **Overpromising Leopard Sightings** | High | Medium | Disappointed guests; negative online reviews. | Strict editorial policy: never guarantee sightings; emphasize high historical probability and ethical tracking. | Marketing Lead |
