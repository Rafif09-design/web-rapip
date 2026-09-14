<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// 1. Proteksi Halaman: Pengguna wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// 2. Ambil cerita dari DB & pastikan pemiliknya adalah user yang sedang login
$stmt = $pdo->prepare("SELECT * FROM stories WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id']]);
$story = $stmt->fetch();

if (!$story) {
    // Jika cerita tidak ditemukan atau bukan milik pengguna
    header("Location: index.php");
    exit;
}

$error = '';

// 3. Proses pembaruan data cerita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = "Judul dan isi cerita tidak boleh kosong!";
    } else {
        $update_stmt = $pdo->prepare("UPDATE stories SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
        $result = $update_stmt->execute([
            ':title'   => $title,
            ':content' => $content,
            ':id'      => $id,
            ':user_id' => $_SESSION['user_id']
        ]);

        if ($result) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal memperbarui cerita.";
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Edit Cerita</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="edit.php?id=<?= $id; ?>" method="POST">
    <div>
        <label for="title">Judul Cerita:</label><br>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($story['title']); ?>" style="width: 100%;" required>
    </div>
    <br>
    <div>
        <label for="content">Isi Cerita:</label><br>
        <textarea id="content" name="content" rows="8" style="width: 100%;" required><?= htmlspecialchars($story['content']); ?></textarea>
    </div>
    <br>
    <button type="submit">Simpan Perubahan</button>
    <a href="index.php">Batal</a>
</form>

<?php require_once 'includes/footer.php'; ?>
