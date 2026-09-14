<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image_name = null;

    if (empty($title) || empty($content)) {
        $error = "Judul dan isi curhatan tidak boleh kosong!";
    } else {
        // Proses Upload Foto jika ada file yang dipilih
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (in_array($ext, $allowed)) {
                $image_name = uniqid('img_', true) . '.' . $ext;
                $destination = 'uploads/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], $destination);
            } else {
                $error = "Format foto harus JPG, PNG, WEBP, atau GIF!";
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("INSERT INTO stories (user_id, title, content, image) VALUES (:user_id, :title, :content, :image)");
            $result = $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':title'   => $title,
                ':content' => $content,
                ':image'   => $image_name
            ]);

            if ($result) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal menyimpan curhatan.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Tulis Curhatan Baru</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="create.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="title">Judul Curhatan:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <br>
    <div>
        <label for="content">Isi Curhatan:</label>
        <textarea id="content" name="content" rows="6" required></textarea>
    </div>
    <br>
    <div>
        <label for="image">Tambah Foto (Opsional, misal foto karakter anime):</label>
        <input type="file" id="image" name="image" accept="image/*">
    </div>
    <br>
    <button type="submit">Publikasikan</button>
</form>

<?php require_once 'includes/footer.php'; ?>
