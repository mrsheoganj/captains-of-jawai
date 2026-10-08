# Reusable UI Component Architecture & Design Specs

## 1. Public Frontend Components

### 1.1 Header & Main Navigation (`Header.tsx` / `header.php`)
- **Desktop Layout:** Left brand logo (`logo.PNG`), center navigation links (*Expeditions*, *The Leopards*, *Jawai Landscape*, *Rabari Heritage*, *Journeys*, *Journal*), right primary conversion CTA (*Plan Your Journey*).
- **Sticky Blur Transition:** Starts transparent over hero media; transitions to `rgba(15, 17, 19, 0.95)` with `backdrop-filter: blur(12px)` on scroll.
- **Mobile Menu (`MobileNav.tsx`):** Fullscreen animated overlay with large editorial typography, direct contact link, and quick WhatsApp trigger.

### 1.2 Cinematic Hero Section (`HeroSection.tsx`)
- **Structure:** Full-bleed background media container (picture tag with AVIF/WebP responsive sources), dark vignette gradient overlay, bold editorial serif H1, location indicator badge (`25°06'N · 73°10'E · JAWAI`), dual CTA buttons (*Plan Your Journey* / *Explore Safaris*), and subtle scroll indicator.

### 1.3 Safari & Experience Cards (`ExperienceCard.tsx`)
- **Aspect Ratio:** 4:5 vertical portrait.
- **Elements:** High-resolution wildlife photography, category pill (*Apex Predator*, *Avian Wetland*, *Pastoral Heritage*), H3 title, duration/timing tag (*Morning & Twilight*), 2-sentence evocative teaser, hover reveal arrow.

### 1.4 Interactive Multi-Step Journey Inquiry (`EnquiryForm.tsx`)
- **Design Philosophy:** Frictionless progressive disclosure.
- **Steps:**
  1. *Destination Interest:* Leopard Tracking, Birding & Wetland, Rabari Culture, Bespoke Photography.
  2. *Travel Window:* Targeted month/season or specific dates.
  3. *Party Composition:* Adults, Children, Private Vehicle Preference.
  4. *Lodging Preference:* Luxury Tented Camp, Boutique Stone Villa, Heritage Haveli, Self-Arranged Stay.
  5. *Contact & Details:* Name, Country, WhatsApp Number, Email, Custom Requests.
- **Validation:** Instant inline feedback, no page reload, AJAX submission to `/api/enquiries.php`.

### 1.5 Editorial Split & Storytelling Sections (`EditorialSplit.tsx`)
- **Layout:** Asymmetric 60/40 grid alternating between large landscape photography and refined editorial copy with pull quotes.

### 1.6 Sticky Mobile Conversion Bar (`MobileStickyCTA.tsx`)
- **Placement:** Fixed bottom anchor on screens < 768px.
- **Actions:** Left button: "WhatsApp Captain" (Direct chat); Right button: "Plan Journey" (Opens multi-step modal).
