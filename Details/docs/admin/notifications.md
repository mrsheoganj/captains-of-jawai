# Lead Alert & Notification Infrastructure

## 1. Notification Triggers
- **New Web Inquiry:** Instant email alert to `concierge@captainsofjawai.com` and SMS/WhatsApp webhook trigger.
- **Stale Lead Alert:** Notification dispatched if a lead remains in `NEW` status for > 12 hours.
- **Follow-up Reminder:** Daily 08:00 AM morning digest sent to concierge staff listing all scheduled follow-ups.

## 2. Delivery Channels
- **SMTP Email:** Secure transactional delivery via PHP PHPMailer connecting to GoDaddy cPanel authenticated SMTP.
- **WhatsApp Webhook Ready:** Architecture supports modular plug-in for official WhatsApp Cloud API (Phase 2).
