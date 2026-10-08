# Analytics Architecture & GA4 Event Tracking

## 1. Measurement Strategy
- Google Analytics 4 (GA4) integrated via minimal asynchronous snippet.
- Custom Event Taxonomy:
  - `enquiry_step_viewed`: Tracks drop-offs per multi-step form stage.
  - `enquiry_submitted`: Fires on successful inquiry creation.
  - `whatsapp_click`: Tracks mobile and desktop WhatsApp triggers.
  - `itinerary_download`: Tracks sample PDF guide downloads.
  - `safari_card_click`: Identifies which safari experience attracts the highest interest.
