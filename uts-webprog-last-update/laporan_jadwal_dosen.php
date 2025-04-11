<?php
session_start();
include 'connection.php';

// Cek role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['dosen', 'admin'])) {
  header("Location: modul_login.php");
  exit();
}

$email = $_SESSION['username'];
$role = $_SESSION['role'];

// Ambil data dosen berdasarkan email (khusus role dosen)
if ($role === 'dosen') {
  $q = $conn->prepare("SELECT nik, nama FROM dosen WHERE email = ?");
  $q->bind_param("s", $email);
  $q->execute();
  $res = $q->get_result();
  $data_dosen = $res->fetch_assoc();

  if (!$data_dosen) {
    header("Location: modul_login.php");
    exit();
  }

  $nik = $data_dosen['nik'];
  $nama_dosen = $data_dosen['nama'];
} else {
  // Jika admin, gunakan dummy (atau nanti bisa pakai filter)
  $nik = null;
  $nama_dosen = "Administrator";
}

// Ambil jadwal dosen dari tabel krs
$query = "
SELECT 
  krs.hari_matkul,
  krs.ruangan,
  matakuliah.kode_matkul,
  matakuliah.nama_matkul,
  matakuliah.sks
FROM krs
JOIN matakuliah ON krs.kode_matkul = matakuliah.kode_matkul
WHERE krs.nik_dosen = ?
ORDER BY FIELD(krs.hari_matkul, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();

// Susun data berdasarkan hari
$jadwal_mingguan = ['Senin'=>[], 'Selasa'=>[], 'Rabu'=>[], 'Kamis'=>[], 'Jumat'=>[], 'Sabtu'=>[], 'Minggu'=>[]];
while ($row = $result->fetch_assoc()) {
  $jadwal_mingguan[$row['hari_matkul']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Laporan Jadwal Dosen</title>
  <link rel="stylesheet" href="laporan_jadwal_dosen.css" />
</head>
<body>
  <div class="navbar">
    <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
    <span class="role-info">Role: <?= ucfirst(htmlspecialchars($role)) ?> | Nama: <?= htmlspecialchars($nama_dosen) ?></span>
    <button class="signout-button" onclick="window.location.href='modul_login.php'">Sign Out</button>
  </div>

  <main class="main-content">
    <section class="section-jadwal">
      <h2>Jadwal Mengajar Mingguan - <?= htmlspecialchars($nama_dosen) ?></h2>
      <p class="desc">Berikut jadwal mengajar Anda yang diambil langsung dari sistem KRS.</p>

      <div class="calendar-grid">
        <?php foreach ($jadwal_mingguan as $hari => $list): ?>
          <div class="day" data-hari="<?= $hari ?>">
            <strong><?= $hari ?></strong>
            <?php if (empty($list)): ?>
              <p style="color: gray; font-size: 0.9rem;">Tidak ada jadwal</p>
            <?php else: ?>
              <?php foreach ($list as $item): ?>
                <div class="jadwal-box">
                  <div class="info-row"><span class="label">Kode Matkul</span><span class="value"><?= $item['kode_matkul'] ?></span></div>
                  <div class="info-row"><span class="label">Nama Matkul</span><span class="value"><?= $item['nama_matkul'] ?></span></div>
                  <div class="info-row"><span class="label">SKS</span><span class="value"><?= $item['sks'] ?></span></div>
                  <div class="info-row"><span class="label">Hari</span><span class="value"><?= $hari ?></span></div>
                  <div class="info-row"><span class="label">Ruangan</span><span class="value"><?= $item['ruangan'] ?></span></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>
