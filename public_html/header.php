<?php
require_once __DIR__ . '/../private/Settings.php';
use App\Settings;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawai Leopard Safari Booking | Captains of Jawai</title>
    <link rel="stylesheet" href="/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span><i class="fa fa-envelope"></i> <?= htmlspecialchars(Settings::get('contact_email', 'booking@captainsofjawai.com')) ?></span>
                <span><i class="fa fa-map-marker-alt"></i> Jawai Bandh Road, Pali, Rajasthan</span>
            </div>
            <div class="top-bar-right">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- Header / Navigation -->
    <header class="header-area">
        <div class="container">
            <div class="logo">
                <a href="/"><img src="/assets/logo.PNG" alt="Logo" style="height: 80px; width: auto; max-width: 250px; object-fit: contain;"></a>
            </div>
            <nav class="nav-menu">
                <a href="/">Home</a>
                <a href="/about.php">About</a>
                <a href="/plan-your-journey/">Book Safari</a>
                <a href="/contact.php">Contact</a>
            </nav>
            <div class="hotline" style="text-align:right;">
                <span style="font-size: 11px; color: #9ca3af; display:block; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">For More Inquiry</span>
                <a href="tel:<?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?>" style="font-weight: 700; color: var(--text-dark); font-size: 18px;"><?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?></a>
            </div>
        </div>
    </header>
