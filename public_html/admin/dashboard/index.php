<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Captains of Jawai</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: var(--color-surface-dark); color: var(--color-text-muted); padding: 2rem; }
        .sidebar a { color: var(--color-text-muted); text-decoration: none; display: block; margin-bottom: 1rem; }
        .sidebar a:hover { color: #fff; }
        .main-content { flex: 1; padding: 3rem; background: var(--color-bg-base); }
        .card { background: var(--color-bg-surface); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-card); margin-bottom: 2rem; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <img src="/assets/logo.PNG" alt="Logo" style="height: 32px; filter: brightness(0) invert(1); margin-bottom: 3rem;">
            <a href="/admin/dashboard/" style="color: #fff; font-weight: 600;">Dashboard</a>
            <a href="/admin/crm/">Inquiry CRM</a>
            <a href="/admin/cms/">Headless CMS</a>
            <a href="/admin/logout.php" style="margin-top: auto;">Sign Out</a>
        </div>
        <div class="main-content">
            <h2 class="text-display-3" style="margin-bottom: 2rem;">Executive Dashboard</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
                <div class="card">
                    <h3 style="color: var(--color-text-muted); font-size: 0.9rem; text-transform: uppercase;">New Inquiries</h3>
                    <p style="font-size: 2.5rem; font-family: 'Space Mono', monospace; font-weight: 700; color: var(--color-text-primary);">12</p>
                </div>
                <div class="card">
                    <h3 style="color: var(--color-text-muted); font-size: 0.9rem; text-transform: uppercase;">Active Proposals</h3>
                    <p style="font-size: 2.5rem; font-family: 'Space Mono', monospace; font-weight: 700; color: var(--color-accent-amber);">5</p>
                </div>
                <div class="card">
                    <h3 style="color: var(--color-text-muted); font-size: 0.9rem; text-transform: uppercase;">Upcoming Expeditions</h3>
                    <p style="font-size: 2.5rem; font-family: 'Space Mono', monospace; font-weight: 700; color: var(--color-accent-olive);">3</p>
                </div>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 1rem;">Recent Activity</h3>
                <p style="color: var(--color-text-muted);">No new activity logs.</p>
            </div>
        </div>
    </div>
</body>
</html>
