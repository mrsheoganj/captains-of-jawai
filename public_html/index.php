<?php require_once 'header.php'; ?>

    <!-- Swiper Hero Slider -->
    <div class="swiper mySwiper hero-slider">
        <div class="swiper-wrapper">
            <!-- Slide 1: Leopards -->
            <div class="swiper-slide slide-bg-1">
                <div class="slide-content" data-aos="fade-up" data-aos-duration="1000">
                    <h1>The Leopards of Jawai</h1>
                    <p>Track apex predators in their natural granite habitat. A breathtaking 100% ethical wildlife experience.</p>
                    <a href="#packages" class="primary-btn1">View Packages</a>
                </div>
            </div>
            <!-- Slide 2: Rabari Culture -->
            <div class="swiper-slide slide-bg-2">
                <div class="slide-content">
                    <h1>A Sacred Coexistence</h1>
                    <p>Experience the unique harmony between the ancient Rabari herdsmen and Jawai's wild leopards.</p>
                    <a href="#about" class="primary-btn1">Discover Jawai</a>
                </div>
            </div>
            <!-- Slide 3: Landscapes -->
            <div class="swiper-slide slide-bg-3">
                <div class="slide-content">
                    <h1>Untamed Landscapes</h1>
                    <p>From monolithic granite boulders to shimmering wetlands, Jawai is a photographer's paradise.</p>
                    <a href="#gallery" class="primary-btn1">View Gallery</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next" style="color: var(--primary-color);"></div>
        <div class="swiper-button-prev" style="color: var(--primary-color);"></div>
    </div>

    <!-- About Section -->
    <div id="about" class="section-padding bg-white">
        <div class="container">
            <div class="details-row">
                <div class="details-left" data-aos="fade-right" data-aos-duration="1200">
                    <span class="section-subtitle">Welcome to Jawai Safari</span>
                    <h2>The Most Unique Leopard Tracking Experience in India</h2>
                    <p style="font-size: 18px; margin-bottom: 20px;">Located in the Pali district of Rajasthan, Jawai is a hidden gem where nature, wildlife, and local culture blend seamlessly. Unlike heavily regulated national parks, Jawai offers an unrestricted, intimate safari experience across private and community lands.</p>
                    <p style="font-size: 16px; margin-bottom: 30px;">Our open-top 4x4 Gypsies, driven by indigenous trackers who have lived alongside these big cats for generations, get you closer to the action while maintaining absolute respect for the wildlife.</p>
                    <div class="includ-and-exclud-area">
                        <ul style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <li><i class="fa fa-paw"></i> <p>Highest Leopard Density</p></li>
                            <li><i class="fa fa-leaf"></i> <p>100% Ethical Tracking</p></li>
                            <li><i class="fa fa-car"></i> <p>Private 4x4 Expeditions</p></li>
                            <li><i class="fa fa-camera"></i> <p>Perfect for Photographers</p></li>
                        </ul>
                    </div>
                </div>
                <div class="details-right" data-aos="fade-left" data-aos-duration="1200">
                    <div style="position: relative;">
                        <!-- Jawai Leopard Image -->
                        <img src="https://images.unsplash.com/photo-1549479354-945763a824cb?q=80&w=2938&auto=format&fit=crop" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow-soft);" alt="Jawai Leopard">
                        <!-- Overlapping Image (Culture/Landscape) -->
                        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=2936&auto=format&fit=crop" style="position: absolute; bottom: -40px; left: -40px; width: 50%; border-radius: 20px; border: 10px solid #fff; box-shadow: var(--shadow-soft);" alt="Jawai Culture">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Safari Packages Section -->
    <div id="packages" class="section-padding bg-light">
        <div class="container">
            <div style="text-align: center;" data-aos="fade-up">
                <span class="section-subtitle">Our Expeditions</span>
                <h2>Popular Safari Packages</h2>
                <p style="max-width: 600px; margin: 0 auto 20px;">Choose from our meticulously crafted itineraries, designed to offer the best wildlife sightings in Jawai.</p>
            </div>
            
            <div class="package-grid">
                <!-- Package 1 -->
                <div class="package-card" data-aos="fade-up" data-aos-delay="100">
                    <div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1615598285513-43f1190bc1b9?q=80&w=2940&auto=format&fit=crop" alt="Morning Safari"></div>
                    <div class="package-content">
                        <h3 style="font-size: 22px; margin-bottom: 10px;">Sunrise Leopard Safari</h3>
                        <p style="color: #666; margin-bottom: 20px; min-height: 50px;">Track apex predators as they return from their night hunts in the soft dawn light.</p>
                        <div class="package-price-row">
                            <div>
                                <span style="font-size: 13px; color: #999; text-transform: uppercase; font-weight: 600;">Per Jeep (Up to 6)</span>
                                <div style="font-size: 24px; font-weight: 700; color: var(--text-dark);">₹6,500</div>
                            </div>
                            <a href="/plan-your-journey/" class="primary-btn1" style="padding: 10px 25px;">Book Now</a>
                        </div>
                    </div>
                </div>

                <!-- Package 2 -->
                <div class="package-card" data-aos="fade-up" data-aos-delay="200" style="border: 2px solid var(--primary-color);">
                    <div style="background: var(--primary-color); color: #fff; text-align: center; padding: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Recommended</div>
                    <div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1549479354-945763a824cb?q=80&w=2938&auto=format&fit=crop" alt="Evening Safari"></div>
                    <div class="package-content">
                        <h3 style="font-size: 22px; margin-bottom: 10px;">Sunset Leopard Safari</h3>
                        <p style="color: #666; margin-bottom: 20px; min-height: 50px;">Watch leopards emerge from the granite caves to bask in the fading golden sunlight.</p>
                        <div class="package-price-row">
                            <div>
                                <span style="font-size: 13px; color: #999; text-transform: uppercase; font-weight: 600;">Per Jeep (Up to 6)</span>
                                <div style="font-size: 24px; font-weight: 700; color: var(--primary-color);">₹6,500</div>
                            </div>
                            <a href="/plan-your-journey/" class="primary-btn1" style="padding: 10px 25px;">Book Now</a>
                        </div>
                    </div>
                </div>

                <!-- Package 3 -->
                <div class="package-card" data-aos="fade-up" data-aos-delay="300">
                    <div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1544482025-a83151817730?q=80&w=2940&auto=format&fit=crop" alt="Wetland Safari"></div>
                    <div class="package-content">
                        <h3 style="font-size: 22px; margin-bottom: 10px;">Wetland Birding Safari</h3>
                        <p style="color: #666; margin-bottom: 20px; min-height: 50px;">Explore the dam area. Perfect for bird watchers, with crocodiles and flamingos.</p>
                        <div class="package-price-row">
                            <div>
                                <span style="font-size: 13px; color: #999; text-transform: uppercase; font-weight: 600;">Per Jeep (Up to 6)</span>
                                <div style="font-size: 24px; font-weight: 700; color: var(--text-dark);">₹5,500</div>
                            </div>
                            <a href="/plan-your-journey/" class="primary-btn1" style="padding: 10px 25px;">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Gallery -->
    <div id="gallery" class="section-padding bg-white">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px;" data-aos="fade-up">
                <span class="section-subtitle">Visual Journey</span>
                <h2>The Magic of Jawai</h2>
                <p style="max-width: 600px; margin: 0 auto;">Glimpses of the wildlife, landscapes, and rich culture that await you.</p>
            </div>
            
            <div class="gallery-grid" data-aos="fade-up" data-aos-delay="200">
                <div class="gallery-item gi-1">
                    <img src="https://images.unsplash.com/photo-1549479354-945763a824cb?q=80&w=2938&auto=format&fit=crop" alt="Jawai Leopard Close Up">
                </div>
                <div class="gallery-item gi-2">
                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?q=80&w=2936&auto=format&fit=crop" alt="Rabari Herdsman">
                </div>
                <div class="gallery-item gi-3">
                    <img src="https://images.unsplash.com/photo-1534143026859-00f7239f60f6?q=80&w=2940&auto=format&fit=crop" alt="Jawai Granites">
                </div>
                <div class="gallery-item gi-2">
                    <img src="https://images.unsplash.com/photo-1615598285513-43f1190bc1b9?q=80&w=2940&auto=format&fit=crop" alt="Sunset in Jawai">
                </div>
                <div class="gallery-item gi-3">
                    <img src="https://images.unsplash.com/photo-1544482025-a83151817730?q=80&w=2940&auto=format&fit=crop" alt="Birding Jawai Dam">
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="section-padding bg-light">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px;" data-aos="fade-up">
                <span class="section-subtitle">Trust & Safety</span>
                <h2>Why Book With Captains of Jawai?</h2>
            </div>
            <div class="features-grid">
                <div class="feature-box" data-aos="zoom-in" data-aos-delay="100">
                    <i class="fa fa-shield-alt"></i>
                    <h3>Safe & Secure</h3>
                    <p>All our vehicles are highly maintained open 4x4s equipped for rugged terrains, ensuring absolute safety.</p>
                </div>
                <div class="feature-box" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fa fa-users"></i>
                    <h3>Private Experience</h3>
                    <p>No crowded buses. Your jeep is 100% exclusive to your group or family for a personalized adventure.</p>
                </div>
                <div class="feature-box" data-aos="zoom-in" data-aos-delay="300">
                    <i class="fa fa-camera"></i>
                    <h3>Photography Ready</h3>
                    <p>Our naturalists know the exact angles and lighting to get you the perfect shot of the wildlife.</p>
                </div>
                <div class="feature-box" data-aos="zoom-in" data-aos-delay="400">
                    <i class="fa fa-star"></i>
                    <h3>5-Star Rated</h3>
                    <p>Trusted by hundreds of guests worldwide for providing the ultimate Jawai safari experience.</p>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
