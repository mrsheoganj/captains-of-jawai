# Complete Design System Specification

## 1. Grid, Spacing & Container Hierarchy
- **Base Spacing Unit:** 8px (`0.5rem`). Scale: `4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px, 96px, 128px`.
- **Max Container Widths:**
  - Standard Content: `1240px` (padded with `24px` gutter on desktop).
  - Editorial Reading / Longform Post: `780px` (optimized for 65–75 characters per line).
  - Full-Bleed Showcase: `100%` viewport width (for hero and photographic parallax showcases).
- **Responsive Breakpoints:**
  - `sm`: 640px (Mobile landscape & large smartphones)
  - `md`: 768px (Tablets portrait)
  - `lg`: 1024px (Tablets landscape & small laptops)
  - `xl`: 1280px (Standard desktop displays)
  - `2xl`: 1536px (Wide desktop monitors)

## 2. Component Design Specifications
- **Button System:**
  - *Primary CTA:* Solid Burnished Amber background (`#C8963E`), Dark Granite text (`#0F1113`), SemiBold weight, subtle hover translation (`translateY(-2px)`), no harsh border radius (refined `4px` or `6px` radius).
  - *Secondary CTA:* Ghost outline button, 1px hairlined border in Warm Ivory or Burnished Amber, transparent fill with smooth fade-in fill on hover.
  - *Floating / Mobile Sticky Bar:* Positioned fixed at bottom viewport on mobile screens (< 768px), high z-index, containing two thumb-accessible triggers: "Plan Your Journey" and quick "WhatsApp Consultation".
- **Card System:**
  - *Safari & Experience Cards:* Aspect ratio 4:5 or 3:4 portrait orientation. Subtle dark gradient overlay at bottom 40% for text contrast. Hover effect: smooth image scale (`scale(1.04)`) with 500ms cubic-bezier ease.
- **Form Inputs:**
  - Generous height (52px minimum), subtle 1px border (`var(--color-border-dark)` / `var(--color-border-light)`), active focus state highlighted with burnished amber glow, floating labels, explicit error states in Rabari Crimson.
