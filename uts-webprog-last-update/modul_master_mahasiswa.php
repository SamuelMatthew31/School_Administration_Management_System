<?php
session_start();
include 'connection.php';

$pesan_error = "";
$edit_mode = false;
$data_edit = [];

// Tambah / Update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nim = $_POST['nim'];
  $nama = $_POST['nama'];
  $tahun_masuk = $_POST['tahun_masuk'];
  $alamat = $_POST['alamat'];
  $telp = $_POST['telp'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $tanggal_input = $_POST['tanggal_input'];
  $role = 'mahasiswa';

  // Jika sedang update
  if (isset($_POST['update_mode']) && $_POST['update_mode'] === '1') {
    $stmt = $conn->prepare("UPDATE mahasiswa SET nama=?, tahun_masuk=?, alamat=?, telp=?, email=?, password=?, tanggal_input=? WHERE nim=?");
    $stmt->bind_param("sissssss", $nama, $tahun_masuk, $alamat, $telp, $email, $password, $tanggal_input, $nim);
    if ($stmt->execute()) {
      $pesan_error = "Data berhasil diperbarui.";
    } else {
      $pesan_error = "Gagal update data.";
    }
    $stmt->close();
  } else {
    // Tambah Baru
    $cekEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cekEmail->bind_param("s", $email);
    $cekEmail->execute();
    $resEmail = $cekEmail->get_result();

    if ($resEmail->num_rows === 0) {
      $insertUser = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
      $insertUser->bind_param("sss", $email, $password, $role);
      if ($insertUser->execute()) {
        $user_id = $conn->insert_id;

        $cekNim = $conn->prepare("SELECT 1 FROM mahasiswa WHERE nim = ?");
        $cekNim->bind_param("s", $nim);
        $cekNim->execute();
        $resNim = $cekNim->get_result();

        if ($resNim->num_rows === 0) {
          $stmt = $conn->prepare("INSERT INTO mahasiswa (user_id, nim, nama, tahun_masuk, alamat, telp, email, password, user_input, tanggal_input) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("ississssss", $user_id, $nim, $nama, $tahun_masuk, $alamat, $telp, $email, $password, $email, $tanggal_input);
          $stmt->execute();
          $stmt->close();
        } else {
          $pesan_error = "NIM sudah terdaftar.";
        }
      } else {
        $pesan_error = "Gagal simpan ke tabel users.";
      }
    } else {
      $pesan_error = "Email sudah digunakan.";
    }
  }
}

// Hapus
if (isset($_GET['hapus'])) {
  $nim_hapus = $_GET['hapus'];
  $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE nim = ?");
  $stmt->bind_param("s", $nim_hapus);
  $stmt->execute();
  $stmt->close();
  header("Location: modul_master_mahasiswa.php");
  exit();
}

// Edit - ambil data
if (isset($_GET['edit'])) {
  $edit_mode = true;
  $nim_edit = $_GET['edit'];
  $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
  $stmt->bind_param("s", $nim_edit);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res->num_rows > 0) {
    $data_edit = $res->fetch_assoc();
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modul Master Mahasiswa</title>
  <link rel="stylesheet" href="modul_master_mahasiswa.css">
</head>
<body>

<div class="navbar">
  <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
  <button class="signout-button" onclick="window.location.href='logout.php'">Sign Out</button>
</div>

<div style="padding: 1rem;">
  <button onclick="window.location.href='menu_utama.php'" style="padding: 8px 16px; font-size: 14px;">← Kembali ke Menu Utama</button>
</div>

<main class="main-content">
  <section class="section-form">
    <h2><?= $edit_mode ? "Edit Mahasiswa" : "Tambah Mahasiswa" ?></h2>
    <?php if (!empty($pesan_error)) : ?>
      <p style="color: red; font-weight: bold;"><?= htmlspecialchars($pesan_error) ?></p>
    <?php endif; ?>

    <form method="post" class="form-mahasiswa">
      <input type="hidden" name="update_mode" value="<?= $edit_mode ? '1' : '0' ?>">
      <label for="nim">NIM:</label>
      <input type="text" id="nim" name="nim" required value="<?= $edit_mode ? htmlspecialchars($data_edit['nim']) : '' ?>" <?= $edit_mode ? 'readonly' : '' ?>>

      <label for="nama">Nama:</label>
      <input type="text" id="nama" name="nama" required value="<?= $edit_mode ? htmlspecialchars($data_edit['nama']) : '' ?>">

      <label for="tahun_masuk">Tahun Masuk:</label>
      <input type="number" id="tahun_masuk" name="tahun_masuk" required value="<?= $edit_mode ? htmlspecialchars($data_edit['tahun_masuk']) : '' ?>">

      <label for="alamat">Alamat:</label>
      <input type="text" id="alamat" name="alamat" required value="<?= $edit_mode ? htmlspecialchars($data_edit['alamat']) : '' ?>">

      <label for="telp">Telp:</label>
      <input type="text" id="telp" name="telp" required value="<?= $edit_mode ? htmlspecialchars($data_edit['telp']) : '' ?>">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required value="<?= $edit_mode ? htmlspecialchars($data_edit['email']) : '' ?>">

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" <?= $edit_mode ? '' : 'required' ?>>

      <label for="tanggal_input">Tanggal Input:</label>
      <input type="date" id="tanggal_input" name="tanggal_input" required value="<?= $edit_mode ? htmlspecialchars($data_edit['tanggal_input']) : '' ?>">

      <button type="submit"><?= $edit_mode ? "Update Mahasiswa" : "Tambah Mahasiswa" ?></button>
    </form>
  </section>

  <section class="section-tabel">
    <h3>Daftar Mahasiswa</h3>
    <table class="table-mahasiswa">
      <thead>
        <tr>
          <th>NIM</th>
          <th>Nama</th>
          <th>Tahun</th>
          <th>Alamat</th>
          <th>Telp</th>
          <th>Email</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM mahasiswa ORDER BY tanggal_input DESC");
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>{$row['nim']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['tahun_masuk']}</td>
            <td>{$row['alamat']}</td>
            <td>{$row['telp']}</td>
            <td>{$row['email']}</td>
            <td>{$row['tanggal_input']}</td>
            <td>
              <a href='modul_master_mahasiswa.php?edit={$row['nim']}' style='color:blue;'>Edit</a> | 
              <a href='modul_master_mahasiswa.php?hapus={$row['nim']}' onclick=\"return confirm('Hapus data ini?')\" style='color:red;'>Hapus</a>
            </td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
  </section>
</main>

</body>
</html>
