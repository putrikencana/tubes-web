<?php
session_start();
include 'db.php'; // Pastikan ini terhubung ke database Anda

// Cek apakah pengguna sudah login dan adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Tambah data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];

    // Menangani upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/"; // Folder untuk menyimpan file yang diupload
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($_FILES['gambar']['name'])); // Menangani nama file
        $target_file = $target_dir . $filename; // Menggabungkan direktori dan nama file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if ($check === false) {
            die("File yang diupload bukan gambar.");
        }

        // Cek ukuran file (misalnya, batas 2 MB)
        if ($_FILES['gambar']['size'] > 2000000) {
            die("Maaf, ukuran file terlalu besar.");
        }

        // Cek format file
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.");
        }

        // Upload file
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Simpan data ke database
            $conn->query("INSERT INTO data (nama, deskripsi, gambar) VALUES ('$nama', '$deskripsi', '$target_file')");
            header('Location: dashboard_admin.php'); // Redirect setelah sukses
            exit();
        } else {
            die("Maaf, terjadi kesalahan saat mengupload file."); // Pesan kesalahan
        }
    } else {
        die("Tidak ada file yang diupload atau terjadi kesalahan."); // Pesan kesalahan
    }
}
?>