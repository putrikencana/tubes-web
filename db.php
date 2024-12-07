<?php
// db.php - Menghubungkan ke database
$host = 'localhost'; // Ganti jika perlu
$user = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$dbname = 'website_db'; // Nama database yang sudah dibuat

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>