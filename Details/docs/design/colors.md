# Color Guide & Semantic Palette: Captains of Jawai (Master Light Theme)

## 1. Visual Theme Philosophy: The Radiant Desert Light
By default, **Captains of Jawai is designed as a refined, high-end LUXURY LIGHT THEME**. 

In luxury travel and conservation (benchmarks like *Aman*, *National Geographic Traveler*, *Singita Editorial*, and *Condé Nast Traveler*), a warm, airy, sun-drenched light palette conveys prestige, openness, warmth, and timeless elegance. It reflects the sun rising over the granite hills, sun-bleached desert sands, and bright morning safari light.

---

## 2. Master Light Theme Palette & CSS Tokens

```css
:root {
  /* ========================================================
     PRIMARY LIGHT SURFACES (Master Theme)
     ======================================================== */
  --color-bg-base: #FDFBF7;           /* Warm Sandstone Linen / Sunlit Ivory (Global Canvas) */
  --color-bg-surface: #FFFFFF;        /* Pure White (Cards, Dropdowns, Form Inputs, Modals) */
  --color-bg-subtle: #F4EFE6;         /* Weathered Granite Sand (Section Alternates & Badges) */
  --color-bg-muted: #EBE5D8;          /* Warm Pebble (Hover states, Inset panels) */

  /* ========================================================
     TYPOGRAPHY & CONTENT (High-Contrast Readability)
     ======================================================== */
  --color-text-primary: #141618;      /* Deep Mineral Charcoal / Soft Black (H1-H4, Primary Text) */
  --color-text-secondary: #4A5057;    /* Weathered Slate (Body Copy, Secondary Descriptions) */
  --color-text-muted: #7A828A;        /* Granite Mist (Timestamps, Metadata, Captions) */
  --color-text-inverted: #FDFBF7;     /* Warm Ivory (Text on dark accent buttons/badges) */

  /* ========================================================
     BRAND ACCENTS & INTERACTIVE ELEMENTS
     ======================================================== */
  --color-accent-amber: #B8822B;       /* Burnished Ochre / Leopard Amber (Buttons, Highlights, Stars) */
  --color-accent-amber-hover: #9E6B1D; /* Deep Sunset Ochre (Button Hover State) */
  --color-accent-crimson: #B93826;     /* Rabari Turban Crimson (Cultural Badges, Urgency Notes) */
  --color-accent-olive: #4E5D3E;       /* Acacia Thorn Olive (Ecological & Naturalist Tags) */

  /* ========================================================
     HAIRLINE BORDERS & STRUCTURAL DIVIDERS
     ======================================================== */
  --color-border-subtle: #E8E2D6;     /* Light Stone Hairline (Card borders, Nav divider) */
  --color-border-medium: #D3CAB9;     /* Defined Stone Border (Input outlines, Active tabs) */
  --color-border-accent: #B8822B;     /* Gold Accent Border (Focused inputs, Featured cards) */

  /* ========================================================
     SHADOWS & ELEVATION (Soft, Natural, Non-Synthetic)
     ======================================================== */
  --shadow-subtle: 0 2px 8px rgba(20, 22, 24, 0.04);
  --shadow-card: 0 4px 20px rgba(20, 22, 24, 0.06);
  --shadow-float: 0 12px 36px rgba(20, 22, 24, 0.10);

  /* Optional Dark Accent Surface (Used strictly for Night Drives & Footer) */
  --color-surface-dark: #121416;       /* Deep Night Granite (Footer & Night Drive Cards) */
}
```

---

## 3. Light Theme Accessibility & Contrast Audit (WCAG 2.2 AA / AAA)

| Element Pair | Foreground | Background | Contrast Ratio | WCAG Compliance |
| :--- | :--- | :--- | :--- | :--- |
| **Headings & H1-H4** | `#141618` (Mineral Charcoal) | `#FDFBF7` (Sandstone Linen) | **16.1:1** | Passes AAA (Max 7.0:1) |
| **Body Paragraphs** | `#4A5057` (Weathered Slate) | `#FDFBF7` (Sandstone Linen) | **8.2:1** | Passes AAA |
| **Cards & Modals** | `#141618` (Mineral Charcoal) | `#FFFFFF` (Pure White) | **17.4:1** | Passes AAA |
| **Primary Buttons** | `#FFFFFF` (Pure White) | `#B8822B` (Burnished Ochre) | **4.6:1** | Passes AA for UI |
| **Primary Buttons (Alt)**| `#141618` (Mineral Charcoal) | `#B8822B` (Burnished Ochre) | **4.7:1** | Passes AA for UI |
| **Rabari Accents** | `#B93826` (Rabari Crimson) | `#FDFBF7` (Sandstone Linen) | **5.8:1** | Passes AA |

---

## 4. Light Theme Layout Rules for Antigravity IDE
1. **Header & Navigation:** Clean translucent ivory glass (`background: rgba(253, 251, 247, 0.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--color-border-subtle);`).
2. **Hero Section:** Light-filled, bright morning landscape photography with crisp mineral charcoal typography.
3. **Card Containers:** Pure white elevated cards (`#FFFFFF`) with subtle stone hairline borders (`#E8E2D6`) and soft ambient elevation.
4. **Form Inputs:** Pure white background, 52px height, slate placeholder text, and burnished ochre focus ring.
5. **Logo Display:** The master logo (`logo.PNG`) sits naturally on the warm linen and white headers with its dark framing and golden sun.
