<?php require_once 'header.php'; ?>

<!-- Page Banner -->
<div class="page-banner" style="background-image: url('/assets/images/bird1.jpg'); background-size: cover; background-position: center; padding: 120px 0; text-align: center; color: white; position: relative;">
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6);"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 50px; text-shadow: 0 4px 10px rgba(0,0,0,0.3); color: #fff;">Contact Us</h1>
        <p style="font-size: 18px;">Get in touch to plan your exclusive Jawai Leopard Safari.</p>
    </div>
</div>

<div class="section-padding bg-light">
    <div class="container">
        <div class="details-row">
            <div class="details-left">
                <span class="section-subtitle">Reach Out</span>
                <h2>We're Here to Help</h2>
                <p>Have questions about the safaris, accommodations, or the best time to visit? Our local experts are available 24/7 to assist you in planning the perfect itinerary.</p>
                
                <div style="margin-top: 40px; background: #fff; padding: 30px; border-radius: 16px; box-shadow: var(--shadow-soft);">
                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
                        <i class="fa fa-phone" style="font-size: 30px; color: var(--primary-color);"></i>
                        <div>
                            <h4 style="margin: 0; color: var(--text-dark);">Phone / WhatsApp</h4>
                            <a href="tel:<?= htmlspecialchars(\App\Settings::get('contact_phone', '+91-9876543210')) ?>" style="color: var(--text-gray); font-size: 18px; font-weight: bold;"><?= htmlspecialchars(\App\Settings::get('contact_phone', '+91-9876543210')) ?></a>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
                        <i class="fa fa-envelope" style="font-size: 30px; color: var(--primary-color);"></i>
                        <div>
                            <h4 style="margin: 0; color: var(--text-dark);">Email Address</h4>
                            <a href="mailto:<?= htmlspecialchars(\App\Settings::get('contact_email', 'booking@captainsofjawai.com')) ?>" style="color: var(--text-gray); font-size: 18px;"><?= htmlspecialchars(\App\Settings::get('contact_email', 'booking@captainsofjawai.com')) ?></a>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <i class="fa fa-map-marker-alt" style="font-size: 30px; color: var(--primary-color);"></i>
                        <div>
                            <h4 style="margin: 0; color: var(--text-dark);">Office Location</h4>
                            <p style="margin: 0; color: var(--text-gray);">Jawai Bandh Road, Sena Village, Pali, Rajasthan 306126</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="details-right">
                <form action="/plan-your-journey/" method="GET" style="background: #fff; padding: 40px; border-radius: 16px; box-shadow: var(--shadow-soft);">
                    <h3 style="margin-top:0;">Send an Enquiry</h3>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight: bold; margin-bottom: 8px;">Full Name</label>
                        <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight: bold; margin-bottom: 8px;">Phone Number</label>
                        <input type="text" name="phone" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-weight: bold; margin-bottom: 8px;">Message</label>
                        <textarea name="message" rows="4" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;"></textarea>
                    </div>
                    <button type="submit" class="primary-btn1" style="width: 100%;">Submit Enquiry</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
