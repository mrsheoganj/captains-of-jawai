# Production Deployment & GoDaddy cPanel Runbook

1. **cPanel Verification:**
   - Log into GoDaddy cPanel at hosting IP `118.139.178.249`.
   - Open *MultiPHP Manager* and set domain `captainsofjawai.com` to **PHP 8.3**.
   - Open *MySQL Databases* and create database `coj_production` and user `coj_dbuser`.
   - Import `database.sql` via phpMyAdmin.

2. **File Deployment:**
   - Upload application archive to `/home/{user}/`.
   - Move public web files into `/public_html/`.
   - Move non-public configuration files into `/home/{user}/private/`.
   - Set file permissions: Folders `755`, Files `644`, `/uploads` directory `775`.

3. **SSL & Domain Validation:**
   - Open *AutoSSL* in cPanel and force certificate renewal for `captainsofjawai.com` and `www.captainsofjawai.com`.
   - Verify HTTPS redirect operates seamlessly via `.htaccess`.
