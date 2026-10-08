# Mobile UX Architecture & Touch Strategy

## 1. Mobile-First Optimization Principles
- **Over 70% of luxury travel discovery happens on smartphones.**
- Zero horizontal overflow; all touch targets calibrated to a minimum of `48px x 48px`.
- High-contrast typography optimized for outdoor sunlight readability.
- Tap-to-call and instant tap-to-WhatsApp capabilities with pre-filled inquiry parameters.

## 2. Sticky Mobile Bottom Bar
- **Z-Index:** 9999.
- **Structure:** 2-button horizontal dock:
  - *Button 1 (Left - 40% width):* "WhatsApp Us" (Icon + direct chat).
  - *Button 2 (Right - 60% width):* "Plan Journey" (Primary Burnished Amber fill).
- **Auto-Hide on Form Focus:** When a user taps an input field inside the inquiry form, the sticky bottom bar temporarily hides to prevent keyboard occlusion.
