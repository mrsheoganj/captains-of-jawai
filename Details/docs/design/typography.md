# Typography System & Editorial Font Hierarchy

## 1. Selected Font Family Pairing
To achieve international luxury travel prestige without relying on expensive proprietary commercial font licenses, we specify premium, modern, open-source Google Fonts:

```
[ THE TYPOGRAPHIC HARMONY ]
┌──────────────────────────────────────────────────────────────┐
│ PRIMARY EDITORIAL SERIF: Cormorant Garamond / Playfair Display│
│ - Elegant, high-contrast, timeless, literary                 │
│ - Used for H1 hero headlines, editorial pull quotes, titles  │
└──────────────────────────────────────────────────────────────┘
                               +
┌──────────────────────────────────────────────────────────────┐
│ SECONDARY CLEAN SANS-SERIF: Plus Jakarta Sans / Outfit        │
│ - Ultra-legible, geometric yet warm, contemporary 2026 finish│
│ - Used for body copy, navigational menus, form inputs, specs │
└──────────────────────────────────────────────────────────────┘
                               +
┌──────────────────────────────────────────────────────────────┐
│ FUNCTIONAL MONOSPACE / TECHNICAL LABEL: JetBrains Mono / Space│
│ - Precision tracking, field coordinates, safari timings      │
│ - Used for GPS coords, elevation, timestamps, metadata badges│
└──────────────────────────────────────────────────────────────┘
```

## 2. Detailed Font Configuration
| Role | Font Family | Weights | Intended Use Case & Character |
| :--- | :--- | :--- | :--- |
| **Display & Editorial Serif** | `Cormorant Garamond` (Primary) or `Playfair Display` (Fallback) | Regular (400), Medium (500), SemiBold (600), Italic | H1 Hero Titles, Section Headlines, Poetic Epigraphs, Storyteller Leads. Conveys aristocratic wilderness legacy. |
| **Contemporary Sans** | `Plus Jakarta Sans` | Light (300), Regular (400), Medium (500), SemiBold (600) | Navigation links, Subheadings, Body paragraphs, Input fields, CTAs, UI components. Exceptional clarity at small mobile sizes. |
| **Expedition Monospace** | `Space Mono` or `JetBrains Mono` | Regular (400), Medium (500) | Geographic coordinates (`25°06'N 73°10'E`), Safari timings, Vehicle specs, Sighting altitude, Tag indicators. |

## 3. Typographic Scale & CSS System
```css
/* Responsive Modular Typography Scale */
h1, .text-display-1 {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(2.5rem, 5vw + 1rem, 4.5rem);
  font-weight: 500;
  line-height: 1.1;
  letter-spacing: -0.02em;
}

h2, .text-display-2 {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(2rem, 3.5vw + 0.5rem, 3.25rem);
  font-weight: 500;
  line-height: 1.2;
  letter-spacing: -0.01em;
}

h3, .text-display-3 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(1.25rem, 1.5vw + 0.5rem, 1.75rem);
  font-weight: 600;
  line-height: 1.35;
  letter-spacing: -0.01em;
}

body, p {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: clamp(1rem, 0.2vw + 0.95rem, 1.125rem);
  font-weight: 400;
  line-height: 1.7;
  color: var(--color-text-secondary-light);
}

.text-caption, .metadata-tag {
  font-family: 'Space Mono', monospace;
  font-size: 0.8125rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}
```
