<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Hanya proses jika permintaan berupa POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM stories WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            ':id'      => $id,
            ':user_id' => $_SESSION['user_id']
        ]);
    }
}

header("Location: index.php");
exit;
?>
