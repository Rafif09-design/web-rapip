<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Jika pengguna sudah login, alihkan ke halaman utama
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

// Memproses data jika form dikirimkan (Method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil dan membersihkan input dari spasi di awal/akhir
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi sederhana
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal harus 6 karakter!";
    } else {
        // Cek apakah username atau email sudah pernah digunakan
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmtCheck->execute([
            ':username' => $username,
            ':email'    => $email
        ]);

        if ($stmtCheck->rowCount() > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            // ENKRIPSI PASSWORD dengan password_hash()
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data pengguna baru ke database menggunakan Prepared Statement
            $stmtInsert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $result = $stmtInsert->execute([
                ':username' => $username,
                ':email'    => $email,
                ':password' => $hashedPassword
            ]);

            if ($result) {
                $success = "Pendaftaran berhasil! Silakan <a href='login.php'>Login di sini</a>.";
            } else {
                $error = "Terjadi kesalahan sistem saat mendaftar.";
            }
        }
    }
}
?>

<h2>Pendaftaran Akun Baru</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?= $success; ?></p>
<?php else: ?>
    <form action="register.php" method="POST">
        <div>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        <br>
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Daftar Sekarang</button>
    </form>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
