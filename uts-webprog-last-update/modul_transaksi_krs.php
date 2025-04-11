<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: modul_login.php");
  exit();
}

$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_transaksi = $_POST['id_transaksi'];
  $kode_matkul = $_POST['kode_matkul'];
  $nik_dosen = $_POST['nik_dosen'];
  $nim_mahasiswa = $_POST['nim_mahasiswa'];
  $hari_matkul = $_POST['hari_matkul'];
  $ruangan = $_POST['ruangan'];
  $user_input = $_POST['user_input'];
  $tanggal_input = $_POST['tanggal_input'];

  // Validasi foreign key ada atau tidak
  $cek_matkul = $conn->prepare("SELECT 1 FROM matakuliah WHERE kode_matkul = ?");
  $cek_matkul->bind_param("s", $kode_matkul);
  $cek_matkul->execute();
  $hasil_matkul = $cek_matkul->get_result();

  $cek_dosen = $conn->prepare("SELECT 1 FROM dosen WHERE nik = ?");
  $cek_dosen->bind_param("s", $nik_dosen);
  $cek_dosen->execute();
  $hasil_dosen = $cek_dosen->get_result();

  $cek_mhs = $conn->prepare("SELECT 1 FROM mahasiswa WHERE nim = ?");
  $cek_mhs->bind_param("s", $nim_mahasiswa);
  $cek_mhs->execute();
  $hasil_mhs = $cek_mhs->get_result();

  if ($hasil_matkul->num_rows > 0 && $hasil_dosen->num_rows > 0 && $hasil_mhs->num_rows > 0) {
    $stmt = $conn->prepare("INSERT INTO krs (id, kode_matkul, nik_dosen, nim_mahasiswa, hari_matkul, ruangan, user_input, tanggal_input) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $id_transaksi, $kode_matkul, $nik_dosen, $nim_mahasiswa, $hari_matkul, $ruangan, $user_input, $tanggal_input);
    $stmt->execute();
    $stmt->close();
  } else {
    $pesan_error = "Invalid input, ada beberapa data yang belum ada di database.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modul Transaksi KRS</title>
  <link rel="stylesheet" href="modul_transaksi_krs.css">
</head>
<body>
  <div class="navbar">
    <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
    <button class="signout-button" onclick="window.location.href='modul_login.php'">Sign Out</button>
  </div>

  <!-- Tombol Kembali ke Halaman Utama -->
  <div style="padding: 1rem;">
    <button onclick="window.location.href='menu_utama.php'" style="padding: 8px 16px; font-size: 14px;">← Kembali ke Menu Utama</button>
  </div>

  <main class="main-content">
    <section class="section-form">
      <h2>Transaksi KRS (Kartu Rencana Studi)</h2>
      <p class="desc">Pengaturan relasi antara Mata Kuliah, Dosen pengampu, dan Mahasiswa yang mengambil.</p>

      <!-- ✅ Tampilkan pesan error jika ada -->
      <?php if (!empty($pesan_error)) : ?>
        <p style="color: red; font-weight: bold;"><?= $pesan_error ?></p>
      <?php endif; ?>

      <form class="form-krs" method="post">
        <label for="id_transaksi">ID Transaksi:</label>
        <input type="text" id="id_transaksi" name="id_transaksi" required>

        <label for="kode_matkul">Kode Matkul:</label>
        <input type="text" id="kode_matkul" name="kode_matkul" required>

        <label for="nik_dosen">NIK Dosen:</label>
        <input type="text" id="nik_dosen" name="nik_dosen" required>

        <label for="nim_mahasiswa">NIM Mahasiswa:</label>
        <input type="text" id="nim_mahasiswa" name="nim_mahasiswa" required>

        <label for="hari_matkul">Hari Matkul:</label>
        <input type="text" id="hari_matkul" name="hari_matkul" required>

        <label for="ruangan">Ruangan:</label>
        <input type="text" id="ruangan" name="ruangan" required>

        <label for="user_input">User Input:</label>
        <input type="text" id="user_input" name="user_input" required>

        <label for="tanggal_input">Tanggal Input:</label>
        <input type="date" id="tanggal_input" name="tanggal_input" required>

        <button type="submit">Simpan Transaksi</button>
      </form>
    </section>

    <section class="section-tabel">
      <h3>Data Transaksi KRS</h3>
      <table class="table-krs">
        <thead>
          <tr>
            <th>ID</th>
            <th>Kode Matkul</th>
            <th>NIK Dosen</th>
            <th>NIM Mahasiswa</th>
            <th>Hari</th>
            <th>Ruangan</th>
            <th>User Input</th>
            <th>Tanggal Input</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT * FROM krs");
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['kode_matkul']}</td>
              <td>{$row['nik_dosen']}</td>
              <td>{$row['nim_mahasiswa']}</td>
              <td>{$row['hari_matkul']}</td>
              <td>{$row['ruangan']}</td>
              <td>{$row['user_input']}</td>
              <td>{$row['tanggal_input']}</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
