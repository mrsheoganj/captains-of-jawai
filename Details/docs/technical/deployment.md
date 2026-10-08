# Production Deployment Guide: GoDaddy cPanel Environment

## 1. Server Environment Verification
- Confirm PHP version is set to **PHP 8.3** via cPanel *MultiPHP Manager*.
- Verify extensions enabled: `pdo_mysql`, `curl`, `gd`, `imagick`, `mbstring`, `openssl`, `zip`.
- Ensure SSL certificate is active for both `captainsofjawai.com` and `www.captainsofjawai.com`.

## 2. Directory Structure on cPanel
```
/home/username/
├── private/                   <-- Non-public code (controllers, models, database configs)
│   ├── config.php             <-- Database credentials (outside document root)
│   ├── app/
│   └── templates/
└── public_html/               <-- Public web root
    ├── index.php              <-- Single entry point
    ├── .htaccess              <-- Routing and security rules
    ├── assets/
    │   ├── css/
    │   ├── js/
    │   └── logo.PNG
    └── uploads/
```
