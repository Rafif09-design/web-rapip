<?php
$host = 'localhost';
$dbname = 'cerita_db';
$user = 'cerita_user';
$pass = 'rahasia123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>
