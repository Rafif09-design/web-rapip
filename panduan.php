<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

require_once 'includes/header.php';
?>

<article>
  <header>
    <h2>📌 Panduan & Aturan Komunitas Curhat Tipis</h2>
  </header>

  <section>
    <h3>1. 🔒 Privasi & Keamanan Identitas (100% Anonim)</h3>
    <ul>
      <li>Pengunjung **WAJIB** melakukan registrasi terlebih dahulu di web Curhat Tipis untuk dapat mengakses halaman ini.</li>
      <li>Kamu **TIDAK WAJIB** menggunakan nama asli atau data pribadi.</li>
      <li>Boleh menggunakan **username anonim** atau nama samaran favoritmu.</li>
      <li>Email pendaftaran tidak harus email asli, yang penting berakhiran <code>@gmail.com</code> (contoh: <code>curhat_bebas123@gmail.com</code>).</li>
      <li>Identitasmu aman, jadi kamu bisa mencurahkan isi hati tanpa rasa khawatir!</li>
    </ul>
  </section>

  <hr>

  <section>
    <h3>2. 🤝 Aturan Sopan Santun</h3>
    <ul>
      <li><strong>Dilarang SARA:</strong> Tidak boleh membawa unsur Suku, Agama, Ras, dan Antargolongan.</li>
      <li><strong>Bebas Hinaan & Celaan:</strong> Dilarang keras saling mengejek, membully, atau merendahkan curhatan orang lain.</li>
      <li><strong>Saling Mendukung:</strong> Berikan tanggapan dan masukan (<em>feedback</em>) yang positif serta membangun di kolom komentar.</li>
    </ul>
  </section>

  <hr>

  <section>
    <h3>3. 💡 Tujuan Web Ini</h3>
    <p>
      Web ini diciptakan sebagai ruang aman untuk saling berbagi kisah hidup, beban pikiran, dan cerita harian. Mari kita jaga suasana tempat ini agar tetap nyaman!
    </p>
  </section>

  <footer>
    <a href="index.php" role="button">Saya Mengerti</a>
  </footer>
</article>

<?php require_once 'includes/footer.php'; ?>
