<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Ambil semua cerita
$stmt = $pdo->query("
    SELECT stories.*, users.username 
    FROM stories 
    JOIN users ON stories.user_id = users.id 
    ORDER BY stories.created_at DESC
");
$stories = $stmt->fetchAll();
?>

<h2>Curhatan Terbaru</h2>

<?php if (empty($stories)): ?>
    <p>Belum ada curhatan. <?php if (isset($_SESSION['user_id'])): ?><a href="create.php">Tulis curhatan pertama!</a><?php endif; ?></p>
<?php else: ?>
    <?php foreach ($stories as $story): ?>
        <?php
        // Ambil komentar untuk cerita ini
        $stmt_comments = $pdo->prepare("
            SELECT comments.*, users.username 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.story_id = :story_id 
            ORDER BY comments.created_at ASC
        ");
        $stmt_comments->execute([':story_id' => $story['id']]);
        $comments = $stmt_comments->fetchAll();
        $total_comments = count($comments);
        ?>

        <article id="story-<?= $story['id']; ?>">
            <header>
                <h3><?= htmlspecialchars($story['title']); ?></h3>
                <small>Ditulis oleh: <strong><?= htmlspecialchars($story['username']); ?></strong> | Pada: <?= $story['created_at']; ?></small>
            </header>
            
            <div style="display: flex; gap: 20px; align-items: flex-start; justify-content: space-between;">
                <div style="flex: 1;">
                    <p><?= nl2br(htmlspecialchars($story['content'])); ?></p>
                </div>
                
                <?php if (!empty($story['image'])): ?>
                    <div style="text-align: center; flex-shrink: 0;">
                        <img src="uploads/<?= htmlspecialchars($story['image']); ?>" 
                             alt="Foto Curhatan" 
                             style="width: 140px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc; display: block; margin-bottom: 8px;">
                        
                        <a href="uploads/<?= htmlspecialchars($story['image']); ?>" 
                           download="CurhatTipis_<?= htmlspecialchars($story['image']); ?>" 
                           role="button" 
                           class="outline secondary" 
                           style="padding: 4px 8px; font-size: 12px; width: 100%;">
                            📥 Download
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $story['user_id']): ?>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <a href="edit.php?id=<?= $story['id']; ?>" role="button" class="outline" style="padding: 4px 10px; font-size: 13px;">Edit</a>
                    <form action="delete.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus curhatan ini?');" style="margin: 0;">
                        <input type="hidden" name="id" value="<?= $story['id']; ?>">
                        <button type="submit" class="outline contrast" style="padding: 4px 10px; font-size: 13px;">Hapus</button>
                    </form>
                </div>
            <?php endif; ?>

            <hr>

            <!-- Kolom Komentar Interaktif -->
            <details>
                <summary>💬 Komentar (<?= $total_comments; ?>)</summary>
                
                <div style="margin-top: 15px; padding-left: 10px;">
                    <?php if (empty($comments)): ?>
                        <p><small>Belum ada komentar. Jadi yang pertama memberikan tanggapan!</small></p>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px; margin-bottom: 8px;">
                                <strong><?= htmlspecialchars($c['username']); ?></strong> 
                                <small style="opacity: 0.7;">• <?= $c['created_at']; ?></small>
                                <p style="margin: 4px 0 0 0; font-size: 14px;"><?= nl2br(htmlspecialchars($c['comment'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Form Tambah Komentar -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="comment.php" method="POST" style="margin-top: 15px;">
                            <input type="hidden" name="story_id" value="<?= $story['id']; ?>">
                            <div style="display: flex; gap: 10px;">
                                <input type="text" name="comment" placeholder="Tulis komentar/feedback ramah..." required style="margin-bottom: 0;">
                                <button type="submit" style="width: auto; white-space: nowrap;">Kirim</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p><small><a href="login.php">Login</a> untuk ikut berdiskusi di kolom komentar.</small></p>
                    <?php endif; ?>
                </div>
            </details>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
