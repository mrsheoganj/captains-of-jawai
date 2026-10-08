# Database Implementation Notes & Seed Data

## Initial Seed User (Super Admin)
Upon database creation, seed the initial administrator:
- `username`: `captain_admin`
- `email`: `admin@captainsofjawai.com`
- `role`: `super_admin`
- `password_hash`: Generated via `password_hash('TEMP_ADMIN_PASSWORD_TO_BE_CHANGED', PASSWORD_ARGON2ID)`

## Required Indexing & Collation
- Database collation must strictly be `utf8mb4_unicode_ci` to support full international character sets, accented names, and geographic symbols.
