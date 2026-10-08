# Transactional Email System Architecture

## 1. Delivery Protocol
- Authenticated SMTP via cPanel server or dedicated transactional provider (SendGrid/Amazon SES).
- HTML email templates crafted with responsive inline CSS, featuring the official `logo.PNG` brand mark.

## 2. Customer Confirmation Email Content
- Clear, honest disclaimer: *"Thank you for your inquiry. Please note that this is a private expedition request, not an instant booking confirmation. A Captain will review your dates and craft a tailored itinerary within 12 hours."*
