<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['mahasiswa', 'admin'])) {
  header("Location: modul_login.php");
  exit();
}

$email = $_SESSION['username'];
$role = $_SESSION['role'];

// Ambil data mahasiswa berdasarkan email
$q = $conn->prepare("SELECT nim, nama FROM mahasiswa WHERE email = ?");
$q->bind_param("s", $email);
$q->execute();
$res = $q->get_result();
$data_mhs = $res->fetch_assoc();

if ($data_mhs) {
  $nim = $data_mhs['nim'];
  $nama = $data_mhs['nama'];
} else {
  if ($role === 'admin') {
    $nim = '00000000';
    $nama = 'Administrator';
  } else {
    header("Location: modul_login.php");
    exit();
  }
}

$query = "
SELECT
  krs.hari_matkul,
  krs.ruangan,
  matakuliah.kode_matkul,
  matakuliah.nama_matkul,
  matakuliah.sks,
  dosen.nama AS nama_dosen
FROM krs
JOIN matakuliah ON krs.kode_matkul = matakuliah.kode_matkul
JOIN dosen ON krs.nik_dosen = dosen.nik
WHERE krs.nim_mahasiswa = ?
ORDER BY FIELD(krs.hari_matkul, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$jadwal = $stmt->get_result();

$minggu = ['Senin'=>[], 'Selasa'=>[], 'Rabu'=>[], 'Kamis'=>[], 'Jumat'=>[], 'Sabtu'=>[], 'Minggu'=>[]];
while ($row = $jadwal->fetch_assoc()) {
  $minggu[$row['hari_matkul']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Laporan Jadwal Mahasiswa</title>
  <link rel="stylesheet" href="laporan_jadwal_mahasiswa.css">
</head>
<body>
  <div class="navbar">
    <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
    <span class="role-info">Role: <?= htmlspecialchars(ucfirst($role)) ?> | Nama: <?= htmlspecialchars($nama) ?></span>
    <button class="signout-button" onclick="window.location.href='modul_login.php'">Sign Out</button>
  </div>

  <main class="main-content">
    <section class="section-jadwal">
      <h2>Jadwal Kuliah Mingguan - <?= htmlspecialchars($nama) ?></h2>
      <p class="desc">Berikut adalah jadwal kuliah Anda yang ditampilkan berdasarkan hari dalam bentuk kalender mingguan.</p>

      <div class="calendar-grid">
        <?php foreach ($minggu as $hari => $list): ?>
          <div class="day" data-hari="<?= $hari ?>">
            <strong><?= $hari ?></strong>
            <?php foreach ($list as $item): ?>
              <div class="jadwal-box">
                <div class="info-row"><span class="label">Nama Mahasiswa</span><span class="value"><?= htmlspecialchars($nama) ?></span></div>
                <div class="info-row"><span class="label">NIM</span><span class="value"><?= htmlspecialchars($nim) ?></span></div>
                <div class="info-row"><span class="label">Nama Dosen</span><span class="value"><?= htmlspecialchars($item['nama_dosen']) ?></span></div>
                <div class="info-row"><span class="label">Kode Matkul</span><span class="value"><?= htmlspecialchars($item['kode_matkul']) ?></span></div>
                <div class="info-row"><span class="label">Nama Matkul</span><span class="value"><?= htmlspecialchars($item['nama_matkul']) ?></span></div>
                <div class="info-row"><span class="label">SKS</span><span class="value"><?= $item['sks'] ?></span></div>
                <div class="info-row"><span class="label">Hari</span><span class="value"><?= htmlspecialchars($hari) ?></span></div>
                <div class="info-row"><span class="label">Ruangan</span><span class="value"><?= htmlspecialchars($item['ruangan']) ?></span></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>
