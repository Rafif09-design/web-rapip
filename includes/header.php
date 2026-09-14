<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curhat Tipis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong><a href="index.php" class="contrast">Curhat Tipis</a></strong></li>
            </ul>
            <ul>
                <li><a href="panduan.php" class="secondary">📋 Panduan</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></li>
                    <li><a href="create.php" role="button">Tulis Cerita</a></li>
                    <li><a href="logout.php" class="outline contrast">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" role="button">Login</a></li>
                    <li><a href="register.php" class="secondary">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <hr>
