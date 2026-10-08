<?php
// footer.php
?>
    <!-- Footer Section -->
    <footer class="footer-area">
        <div class="container">
            <div class="footer-row">
                <!-- Brand / Intro -->
                <div class="footer-col">
                    <img src="/assets/logo.PNG" alt="Logo" style="height: 60px; margin-bottom: 20px;">
                    <p>Discover the finest Jawai Tour Packages. Experience the thrill of leopard sightings in the wild granite hills of Rajasthan.</p>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-col">
                    <h4 style="color: var(--text-dark);">Quick Links</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/about.php">About Us</a></li>
                        <li><a href="/plan-your-journey/">Book Safari</a></li>
                        <li><a href="/contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h4 style="color: var(--text-dark);">Contact Info</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fa fa-map-marker-alt"></i>
                            <div>Jawai Bandh Road Near Jawai Dam Bisalpur (Pali), Rajasthan, India</div>
                        </li>
                        <li>
                            <i class="fa fa-envelope"></i>
                            <div><a href="mailto:<?= htmlspecialchars(Settings::get('contact_email', 'booking@captainsofjawai.com')) ?>"><?= htmlspecialchars(Settings::get('contact_email', 'booking@captainsofjawai.com')) ?></a></div>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i>
                            <div><a href="tel:<?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?>"><?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?></a></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <p>&copy; <?php echo date('Y'); ?> Captains of Jawai. All Rights Reserved.</p>
                <div class="footer-links">
                    <a href="/privacy/" style="margin-right: 15px;">Privacy Policy</a>
                    <a href="/terms/">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Floating Contact Actions -->
    <div class="mf-social-side-list">
        <ul>
            <li><a href="https://wa.me/<?= htmlspecialchars(Settings::get('contact_phone', '+919876543210')) ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
            <li><a href="tel:<?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?>"><i class="fa fa-phone"></i></a></li>
        </ul>   
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animations
        AOS.init({ once: true });
        
        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
        });
    </script>
</body>
</html>
