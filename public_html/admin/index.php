<?php
session_start();
require_once __DIR__ . '/../../private/Settings.php';
use App\Settings;

// Hardcoded admin for simplicity, in a real app check the 'users' table
$admin_user = 'admin';
$admin_pass = 'jawai2026';

// Handle Login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Check auth
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle Form Submission for Settings
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $fields = [
        'hero_title_1', 'hero_desc_1',
        'hero_title_2', 'hero_desc_2',
        'hero_title_3', 'hero_desc_3',
        'about_title', 'about_text_1', 'about_text_2',
        'package_1_title', 'package_1_price',
        'package_2_title', 'package_2_price',
        'package_3_title', 'package_3_price',
        'contact_phone', 'contact_email'
    ];
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            Settings::set($field, $_POST[$field]);
        }
    }
    $success = "Settings saved successfully! Refresh your homepage to see the changes.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawai Safari Admin Panel</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f3f4f6; color: #111; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; border-bottom: 2px solid #D4AF37; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        input[type="text"], input[type="password"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; box-sizing: border-box; }
        textarea { height: 80px; resize: vertical; }
        button { background: #D4AF37; color: #fff; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        button:hover { background: #b5952f; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; }
        .logout { float: right; color: #991b1b; text-decoration: none; font-weight: bold; }
        h2 { margin-top: 40px; color: #D4AF37; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!$is_logged_in): ?>
        <h1>Admin Login</h1>
        <?php if (isset($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    <?php else: ?>
        <a href="?logout=1" class="logout">Logout</a>
        <h1>Homepage CMS Editor</h1>
        <p>Edit the text content of your website below. Changes reflect instantly on the live site.</p>
        
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        
        <form method="POST">
            <h2>Hero Slider</h2>
            <div class="form-group"><label>Slide 1 Title</label><input type="text" name="hero_title_1" value="<?= htmlspecialchars(Settings::get('hero_title_1', 'The Leopards of Jawai')) ?>"></div>
            <div class="form-group"><label>Slide 1 Subtext</label><textarea name="hero_desc_1"><?= htmlspecialchars(Settings::get('hero_desc_1', 'Track apex predators in their natural granite habitat. A breathtaking 100% ethical wildlife experience.')) ?></textarea></div>
            
            <div class="form-group"><label>Slide 2 Title</label><input type="text" name="hero_title_2" value="<?= htmlspecialchars(Settings::get('hero_title_2', 'A Sacred Coexistence')) ?>"></div>
            <div class="form-group"><label>Slide 2 Subtext</label><textarea name="hero_desc_2"><?= htmlspecialchars(Settings::get('hero_desc_2', 'Experience the unique harmony between the ancient Rabari herdsmen and Jawai\'s wild leopards.')) ?></textarea></div>
            
            <div class="form-group"><label>Slide 3 Title</label><input type="text" name="hero_title_3" value="<?= htmlspecialchars(Settings::get('hero_title_3', 'Untamed Landscapes')) ?>"></div>
            
            <h2>About Jawai Section</h2>
            <div class="form-group"><label>About Title</label><input type="text" name="about_title" value="<?= htmlspecialchars(Settings::get('about_title', 'The Most Unique Leopard Tracking Experience in India')) ?>"></div>
            <div class="form-group"><label>About Paragraph 1</label><textarea name="about_text_1"><?= htmlspecialchars(Settings::get('about_text_1', 'Located in the Pali district of Rajasthan, Jawai is a hidden gem where nature, wildlife, and local culture blend seamlessly.')) ?></textarea></div>
            <div class="form-group"><label>About Paragraph 2</label><textarea name="about_text_2"><?= htmlspecialchars(Settings::get('about_text_2', 'Our open-top 4x4 Gypsies, driven by indigenous trackers who have lived alongside these big cats for generations, get you closer to the action.')) ?></textarea></div>
            
            <h2>Packages / Pricing</h2>
            <div class="form-group"><label>Package 1 (Morning) Title</label><input type="text" name="package_1_title" value="<?= htmlspecialchars(Settings::get('package_1_title', 'Sunrise Leopard Safari')) ?>"></div>
            <div class="form-group"><label>Package 1 Price</label><input type="text" name="package_1_price" value="<?= htmlspecialchars(Settings::get('package_1_price', '₹6,500')) ?>"></div>
            
            <div class="form-group"><label>Package 2 (Evening) Title</label><input type="text" name="package_2_title" value="<?= htmlspecialchars(Settings::get('package_2_title', 'Sunset Leopard Safari')) ?>"></div>
            <div class="form-group"><label>Package 2 Price</label><input type="text" name="package_2_price" value="<?= htmlspecialchars(Settings::get('package_2_price', '₹6,500')) ?>"></div>
            
            <div class="form-group"><label>Package 3 (Wetland) Title</label><input type="text" name="package_3_title" value="<?= htmlspecialchars(Settings::get('package_3_title', 'Wetland Birding Safari')) ?>"></div>
            <div class="form-group"><label>Package 3 Price</label><input type="text" name="package_3_price" value="<?= htmlspecialchars(Settings::get('package_3_price', '₹5,500')) ?>"></div>
            
            <h2>Contact Information</h2>
            <div class="form-group"><label>Phone Number</label><input type="text" name="contact_phone" value="<?= htmlspecialchars(Settings::get('contact_phone', '+91-9876543210')) ?>"></div>
            <div class="form-group"><label>Email Address</label><input type="text" name="contact_email" value="<?= htmlspecialchars(Settings::get('contact_email', 'booking@captainsofjawai.com')) ?>"></div>
            
            <button type="submit" name="save_settings" style="width: 100%; margin-top: 20px; font-size: 18px;">Save All Changes to Live Website</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
