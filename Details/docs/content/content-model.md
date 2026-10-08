# Headless CMS Content Model & Data Schema

## 1. Content Types & Data Structures

### Content Type: `safaris`
- `id` (INT, PK)
- `title` (VARCHAR 255)
- `slug` (VARCHAR 255, Unique)
- `category` (ENUM: 'leopard', 'wetland', 'photography', 'private')
- `hero_image_id` (INT, FK to `media`)
- `tagline` (VARCHAR 255)
- `duration_hours` (DECIMAL 3,1)
- `timing_description` (VARCHAR 255)
- `ideal_season` (VARCHAR 100)
- `body_content` (LONGTEXT, Markdown/HTML)
- `included_highlights` (JSON Array)
- `is_featured` (BOOLEAN)
- `status` (ENUM: 'draft', 'review', 'published', 'archived')
- `seo_meta_title` (VARCHAR 255)
- `seo_meta_description` (TEXT)

### Content Type: `journal_posts`
- `id` (INT, PK)
- `title` (VARCHAR 255)
- `slug` (VARCHAR 255, Unique)
- `author_id` (INT, FK to `users`)
- `category_id` (INT, FK to `categories`)
- `featured_image_id` (INT, FK to `media`)
- `excerpt` (TEXT)
- `body_content` (LONGTEXT)
- `reading_time_minutes` (INT)
- `published_at` (DATETIME)
- `status` (ENUM: 'draft', 'review', 'published', 'archived')
- `seo_meta_title` (VARCHAR 255)
- `seo_meta_description` (TEXT)

### Content Type: `enquiries` (CRM Entity)
- Detailed in `/docs/admin/crm.md` and `/docs/technical/database.md`.
