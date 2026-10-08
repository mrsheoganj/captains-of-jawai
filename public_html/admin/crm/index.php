<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin/login/');
    exit;
}
require_once '../../../private/config/Database.php';
use App\Config\Database;

$db = Database::getConnection();
$stmt = $db->query("SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 50");
$enquiries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inquiry CRM - Captains of Jawai</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: var(--color-surface-dark); color: var(--color-text-muted); padding: 2rem; }
        .sidebar a { color: var(--color-text-muted); text-decoration: none; display: block; margin-bottom: 1rem; }
        .sidebar a:hover { color: #fff; }
        .main-content { flex: 1; padding: 3rem; background: var(--color-bg-base); }
        .card { background: var(--color-bg-surface); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-card); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 1rem; border-bottom: 1px solid var(--color-border-subtle); }
        th { font-family: 'Space Mono', monospace; font-size: 0.8rem; text-transform: uppercase; color: var(--color-text-muted); }
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .status-new { background: var(--color-bg-subtle); color: var(--color-text-primary); }
        .status-contacted { background: #e3f2fd; color: #1565c0; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <img src="/assets/logo.PNG" alt="Logo" style="height: 32px; filter: brightness(0) invert(1); margin-bottom: 3rem;">
            <a href="/admin/dashboard/">Dashboard</a>
            <a href="/admin/crm/" style="color: #fff; font-weight: 600;">Inquiry CRM</a>
            <a href="/admin/cms/">Headless CMS</a>
            <a href="/admin/logout.php" style="margin-top: auto;">Sign Out</a>
        </div>
        <div class="main-content">
            <h2 class="text-display-3" style="margin-bottom: 2rem;">Inquiry CRM Pipeline</h2>
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($enquiries as $enq): ?>
                        <tr>
                            <td style="font-family: 'Space Mono', monospace;"><?= htmlspecialchars($enq['enquiry_code']) ?></td>
                            <td><?= htmlspecialchars($enq['full_name']) ?></td>
                            <td><?= htmlspecialchars($enq['travel_dates']) ?></td>
                            <td>
                                <span class="status-badge status-<?= htmlspecialchars($enq['status']) ?>"><?= htmlspecialchars($enq['status']) ?></span>
                            </td>
                            <td><?= date('M j, Y', strtotime($enq['created_at'])) ?></td>
                            <td><a href="#" style="color: var(--color-accent-amber); text-decoration: none; font-weight: 600;">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($enquiries)): ?>
                        <tr><td colspan="6" style="text-align: center; color: var(--color-text-muted);">No inquiries found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
