<?php
session_start();
include 'connection.php'; // koneksi ke database

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // cocokkan dengan hash MD5 dari DB

    // Cek keberadaan user berdasarkan email dan password (yang di-hash)
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Simpan session user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'admin':
                header("Location: menu_utama.php");
                break;
            case 'dosen':
                header("Location: laporan_jadwal_dosen.php");
                break;
            case 'mahasiswa':
                header("Location: laporan_jadwal_mahasiswa.php");
                break;
            default:
                $error = "Role tidak dikenali.";
                break;
        }
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="modul_login.css">
</head>
<body>
    <div class="navbar">
        <div class="logo"><img src="asset/logo.jpg" alt="Logo Kampus"></div>
        <span class="role-info">Silakan login terlebih dahulu</span>
    </div>

    <div class="container">
        <div class="left-panel">
            <div class="slideshow-wrapper">
                <div class="slideshow-track" id="slideshow-track">
                    <div class="slide" style="background-image: url('asset/kampus1.jpg');"></div>
                    <div class="slide" style="background-image: url('asset/kampus2.jpg');"></div>
                    <div class="slide" style="background-image: url('asset/kampus3.jpg');"></div>
                    <div class="slide" style="background-image: url('asset/kampus1.jpg');"></div>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-card">
                <h2>Login</h2>
                <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
                <form action="" method="post">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan Email" required>

                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password" required>

                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const track = document.getElementById('slideshow-track');
        const totalSlides = 4;
        let currentSlide = 0;

        setInterval(() => {
            currentSlide++;
            track.style.transition = 'transform 1s ease-in-out';
            track.style.transform = `translateX(-${currentSlide * 100}%)`;

            if (currentSlide === totalSlides - 1) {
                setTimeout(() => {
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0%)';
                    currentSlide = 0;
                }, 1000);
            }
        }, 4000);
    </script>
</body>
</html>
