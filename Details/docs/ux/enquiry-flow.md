# Multi-Step Journey Inquiry Workflow

## Step 1: Expedition Focus
- Checkboxes: Leopard Tracking, Wetland & Birding, Rabari Cultural Walk, Wildlife Photography, Family Journey.

## Step 2: Travel Timeline
- Selection: Flexible vs. Fixed Dates. Month selector or calendar date picker.

## Step 3: Travelling Party
- Number of Adults, Number of Children (<12 yrs), Private Vehicle exclusivity preference (Yes/No).

## Step 4: Accommodation Preference
- Tier: Luxury Wilderness Tented Camp / Boutique Stone Villa / Heritage Haveli / Already booked own stay.

## Step 5: Contact Credentials & Notes
- Full Name, Email Address, WhatsApp / Phone Number with Country Code selector, Special notes or wildlife interests.

## Backend Processing
- AJAX POST to `/api/enquiries.php`
- Immediate CSRF & reCAPTCHA / honeypot verification.
- Enters database with status `NEW`.
- Automated branded confirmation email dispatched to traveller.
- Instant alert email dispatched to expedition admin team.
