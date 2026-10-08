# Inquiry CRM Pipeline Architecture

## 1. The 9-Stage Expedition Pipeline
```
[ NEW INQUIRY ]
       │  (Automated notification sent to admin & staff)
       ▼
[ CONTACTED ]
       │  (Staff reaches out via WhatsApp or phone within 6-12 hours)
       ▼
[ QUALIFIED ]
       │  (Dates, party size, lodging tier, and wildlife focus validated)
       ▼
[ PROPOSAL SENT ]
       │  (Customized itinerary PDF & quotation delivered to traveler)
       ▼
[ FOLLOW-UP ]
       │  (Scheduled touchpoint; addressing traveler questions)
       ▼
[ CONFIRMED OFFLINE ]
       │  (Advance deposit received; jeeps & naturalist secured)
       ▼
[ EXPEDITION ACTIVE ]
       │  (Guest currently on-ground in Jawai)
       ▼
[ COMPLETED ]
       │  (Post-trip thank you sent; review requested)
       ▼
[ LOST / SPAM ] (Closed with documented reason)
```

## 2. Lead Record Data Model
Each lead profile captures:
- Lead ID (`COJ-2026-XXXX`) & Submission Timestamp.
- Traveler Full Name, Email, WhatsApp / Phone with country dial code.
- Planned Travel Dates (or flexible window) & Total Stay Duration.
- Party Composition: Adults, Children, Senior Citizens.
- Safari Interests: Leopard tracking, Birding/Wetlands, Rabari culture, Photography.
- Lodging Preference: Luxury Tented Camp, Boutique Stone Villa, Heritage Haveli, Self-Booked.
- Transfer Requirements: Airport pickup from Udaipur / Jodhpur / Ahmedabad.
- Internal Notes, Activity Log, and Next Action Date.
