# SVG Iconography System & Implementation Specs

## 1. Technical SVG Guidelines
- All icons must be authored as clean, optimized SVGs with `viewBox="0 0 24 24"`.
- Use `stroke="currentColor"`, `stroke-width="1.5"`, `stroke-linecap="round"`, `stroke-linejoin="round"`, `fill="none"`.
- This ensures icons dynamically inherit typography and accent colors without loading separate files.

## 2. Master SVG Component Definitions
```html
<!-- 4x4 Safari Vehicle Icon -->
<svg class="icon icon-safari" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5">
  <path d="M5 17h14M3 11l2-4h14l2 4M3 11v6a1 1 0 001 1h1m15-7v6a1 1 0 01-1 1h-1M6 18a2 2 0 100-4 2 2 0 000 4zm12 0a2 2 0 100-4 2 2 0 000 4zM9 7l1-3h4l1 3" />
</svg>

<!-- Granite Kopje / Mountain Icon -->
<svg class="icon icon-kopje" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5">
  <path d="M3 20L11 6l4 7 2-3 4 10H3z" />
</svg>

<!-- Binoculars / Optics Icon -->
<svg class="icon icon-optics" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5">
  <circle cx="7" cy="14" r="4" />
  <circle cx="17" cy="14" r="4" />
  <path d="M10 14h4M7 10V6l3-2h4l3 2v4" />
</svg>
```
