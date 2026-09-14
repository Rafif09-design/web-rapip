<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email'] ?? '');
    $password       = $_POST['password'] ?? '';

    if (empty($username_email) || empty($password)) {
        $error = "Username/Email dan Password wajib diisi!";
    } else {
        // Menggunakan 2 parameter terpisah agar sesuai dengan PDO Native Prepare
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            ':username' => $username_email,
            ':email'    => $username_email
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username/Email atau Password salah!";
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Login Pengguna</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="login.php" method="POST">
    <div>
        <label for="username_email">Username atau Email:</label><br>
        <input type="text" id="username_email" name="username_email" value="<?= htmlspecialchars($_POST['username_email'] ?? ''); ?>" required>
    </div>
    <br>
    <div>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
    </div>
    <br>
    <button type="submit">Login</button>
</form>

<p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>

<?php require_once 'includes/footer.php'; ?>
