<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "akun"; // Sesuai dengan database kamu. db akun udah gaada udah gw hapus tad

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
