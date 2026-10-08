# Media Asset Library & Image Optimization Pipeline

## 1. Upload & Automated Optimization Engine
- **Supported Input Formats:** JPG, PNG, WEBP, AVIF, TIFF. Max file size: 15MB.
- **Automated Processing Pipeline (via PHP GD / Imagick):**
  1. Validation of MIME type and image header signatures to prevent malicious payload uploads.
  2. Automatic generation of modern `.webp` and `.avif` formats.
  3. Generation of responsive srcset derivatives:
     - Thumbnail: `300px` width.
     - Card / Mobile: `600px` width.
     - Tablet / Editorial: `1200px` width.
     - Fullscreen Retina Hero: `1920px` width.
  4. Stripping unnecessary EXIF metadata for privacy while preserving copyright and IPTC photographer credits.

## 2. Media Metadata & Copyright Tracking
Every image record in the media library requires:
- `alt_text` (Enforced before publishing).
- `caption` & `credit_photographer`.
- `license_type` (Client Owned, CC-BY 2.0, Unsplash Free, Licensed Stock).
- `focal_point` (CSS coordinate for smart responsive cropping).
