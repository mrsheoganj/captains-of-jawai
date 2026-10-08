# Motion Design, Transitions & Animation System

## 1. Motion Principles: "Weight, Restraint, and Flow"
- **Subtle & Cinematic:** No bouncy, cartoonish, or spinning animations. Transitions should mimic smooth camera dolly pans and organic reveals.
- **Performance First:** All animations must utilize GPU-accelerated CSS properties (`transform`, `opacity`). Never animate layout properties (`width`, `height`, `margin`, `top`).
- **Reduced Motion Respect:** Fully support `@media (prefers-reduced-motion: reduce)` by disabling parallax and viewport translations for sensitive users.

## 2. Key Interaction Specifications
- **Navigation Scroll State:** Header starts transparent over the hero imagery, transitioning smoothly to a blurred dark granite backdrop (`background: rgba(15, 17, 19, 0.92); backdrop-filter: blur(12px);`) after 80px of vertical scroll.
- **Card Image Hover:** `transform: scale(1.04); transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);`.
- **Editorial Text Reveal:** Subtle upward fade (`opacity: 0 -> 1; transform: translateY(20px) -> translateY(0)`) triggered on scroll via lightweight IntersectionObserver.
- **Marquee / Editorial Ticker:** Smooth, continuous horizontal ticker showcasing geographic coordinates, conservation pledges, and field status without stutter.
