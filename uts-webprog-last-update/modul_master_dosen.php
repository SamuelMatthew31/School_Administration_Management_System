<?php
session_start();
include 'connection.php';

$pesan_error = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nik = $_POST['nik'];
  $nama = $_POST['nama'];
  $gelar = $_POST['gelar'];
  $lulusan = $_POST['lulusan'];
  $telp = $_POST['telp'];
  $email = $_POST['email'];
  $password_plain = $_POST['password'];
  $password = md5($password_plain); // simple hash
  $tanggal_input = $_POST['tanggal_input'];
  $role = 'dosen';
  $user_input = $_SESSION['username'] ?? 'admin@example.com';

  // Cek email ganda
  $cekEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $cekEmail->bind_param("s", $email);
  $cekEmail->execute();
  $resEmail = $cekEmail->get_result();

  if ($resEmail->num_rows === 0) {
    // Tambah ke users
    $insertUser = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $insertUser->bind_param("sss", $email, $password, $role);
    if ($insertUser->execute()) {
      $user_id = $conn->insert_id;

      // Tambah ke dosen
      $stmt = $conn->prepare("INSERT INTO dosen (user_id, nik, nama, gelar, lulusan, telp, email, password, user_input, tanggal_input)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssssssss", $user_id, $nik, $nama, $gelar, $lulusan, $telp, $email, $password, $user_input, $tanggal_input);
      $stmt->execute();
      $stmt->close();
    } else {
      $pesan_error = "Gagal membuat akun user.";
    }
  } else {
    $pesan_error = "Email sudah digunakan.";
  }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modul Master Dosen</title>
  <link rel="stylesheet" href="modul_master_dosen.css">
</head>
<body>
  <div class="navbar">
    <div class="logo"><img src="asset/logo.jpg" alt=""></div>
    <button class="signout-button" onclick="window.location.href='logout.php'">Sign Out</button>
  </div>

  <!-- Tombol Kembali ke Halaman Utama -->
  <div style="padding: 1rem;">
    <button onclick="window.location.href='menu_utama.php'" style="padding: 8px 16px; font-size: 14px;">← Kembali ke Menu Utama</button>
  </div>

  <main class="main-content">
    <section class="section-form">
      <h2>Manajemen Data Dosen</h2>

      <?php if (!empty($pesan_error)) : ?>
        <p style="color: red; font-weight: bold;">
          <?= htmlspecialchars($pesan_error) ?>
        </p>
      <?php endif; ?>

      <form class="form-dosen" method="post">
        <label for="nik">NIK:</label>
        <input type="text" id="nik" name="nik" required>

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="gelar">Gelar:</label>
        <input type="text" id="gelar" name="gelar" required>

        <label for="lulusan">Lulusan:</label>
        <input type="text" id="lulusan" name="lulusan" required>

        <label for="telp">Telp:</label>
        <input type="text" id="telp" name="telp" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="tanggal_input">Tanggal Input:</label>
        <input type="date" id="tanggal_input" name="tanggal_input" required>

        <button type="submit">Simpan</button>
      </form>
    </section>

    <section class="section-tabel">
      <h3>Daftar Dosen</h3>
      <table class="table-dosen">
        <thead>
          <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>Gelar</th>
            <th>Lulusan</th>
            <th>Telp</th>
            <th>Email</th>
            <th>Tanggal Input</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT d.nik, d.nama, d.gelar, d.lulusan, d.telp, u.email, d.tanggal_input FROM dosen d JOIN users u ON d.user_id = u.id ORDER BY d.tanggal_input DESC");
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>" . htmlspecialchars($row['nik']) . "</td>
              <td>" . htmlspecialchars($row['nama']) . "</td>
              <td>" . htmlspecialchars($row['gelar']) . "</td>
              <td>" . htmlspecialchars($row['lulusan']) . "</td>
              <td>" . htmlspecialchars($row['telp']) . "</td>
              <td>" . htmlspecialchars($row['email']) . "</td>
              <td>" . htmlspecialchars($row['tanggal_input']) . "</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
