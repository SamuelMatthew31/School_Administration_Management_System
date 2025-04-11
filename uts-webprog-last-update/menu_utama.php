<?php
session_start();

// Jika belum login, redirect ke login
if (!isset($_SESSION['user_id'])) {
  header("Location: modul_login.php");
  exit();
}

// Ambil data sesi
$role = $_SESSION['role'];
$email = $_SESSION['username']; // disimpan saat login
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu Utama</title>
  <link rel="stylesheet" href="menu_utama.css" />
</head>

<body>
  <div class="navbar">
    <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
    <span class="role-info">Role: <?= htmlspecialchars($role) ?> | Email: <?= htmlspecialchars($email) ?></span>
    <button class="signout-button" onclick="window.location.href='modul_login.php'">Sign Out</button>
  </div>

  <main class="menu-container">
    <h1>Menu Utama</h1>
    <div class="menu-grid">
      <a href="modul_master_mahasiswa.php" class="menu-card">Master Mahasiswa</a>
      <a href="modul_master_dosen.php" class="menu-card">Master Dosen</a>
      <a href="modul_master_mk.php" class="menu-card">Master Mata Kuliah</a>
      <a href="modul_transaksi_krs.php" class="menu-card">Transaksi KRS</a>
      <a href="laporan_jadwal_mahasiswa.php" class="menu-card">Jadwal Mahasiswa</a>
      <a href="laporan_jadwal_dosen.php" class="menu-card">Jadwal Dosen</a>
    </div>
  </main>
</body>

</html>
