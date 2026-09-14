<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $story_id = $_POST['story_id'] ?? null;
    $comment  = trim($_POST['comment'] ?? '');

    if ($story_id && !empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (story_id, user_id, comment) VALUES (:story_id, :user_id, :comment)");
        $stmt->execute([
            ':story_id' => $story_id,
            ':user_id'  => $_SESSION['user_id'],
            ':comment'   => $comment
        ]);
    }
    
    header("Location: index.php#story-" . $story_id);
    exit;
}

header("Location: index.php");
exit;
?>
