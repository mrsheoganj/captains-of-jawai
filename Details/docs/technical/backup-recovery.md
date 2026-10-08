# Automated Backup & Disaster Recovery Plan

- **Database Backup:** Daily cron job executing `mysqldump` with gzip compression, retaining rolling 30-day archives.
- **Media & Assets Backup:** Weekly rsync/archive of user uploads.
- **Emergency Recovery:** Documented 15-minute complete restore procedure from single SQL dump and webroot archive.
