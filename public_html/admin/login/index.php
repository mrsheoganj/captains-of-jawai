<?php
session_start();
require_once '../../../private/config/Database.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT id, password_hash, role, is_active FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && $user['is_active'] && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        // Update last login
        $db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute([':id' => $user['id']]);

        header('Location: /admin/dashboard/');
        exit;
    } else {
        $error = "Invalid credentials or inactive account.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Captains of Jawai</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body { background-color: var(--color-bg-base); display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { background: var(--color-bg-surface); padding: 3rem; border-radius: 8px; box-shadow: var(--shadow-card); width: 100%; max-width: 400px; text-align: center; }
        .login-card input { width: 100%; height: 52px; margin-bottom: 1rem; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit; }
        .login-card button { width: 100%; background: var(--color-text-primary); color: #fff; border: none; height: 52px; border-radius: 4px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="/assets/logo.PNG" alt="Captains of Jawai" style="height: 48px; margin-bottom: 2rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; margin-bottom: 1.5rem;">Secure Admin Access</h2>
        <?php if (isset($error)): ?>
            <p style="color: var(--color-accent-crimson); margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Authenticate</button>
        </form>
    </div>
</body>
</html>
