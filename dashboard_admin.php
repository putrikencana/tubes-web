<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login dan adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fungsi untuk menghapus data
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM data WHERE id='$delete_id'");
}

// Ambil data
$data = $conn->query("SELECT * FROM data");

// Cek apakah ada ID untuk edit
$id = isset($_GET['id']) ? $_GET['id'] : null;
$currentData = null;

// Ambil data jika mengedit
if ($id) {
    $sql = "SELECT * FROM data WHERE id='$id'";
    $result = $conn->query($sql);
    $currentData = $result->fetch_assoc();
}

// Tambah atau update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $target_dir = "uploads/"; // Folder untuk menyimpan file yang diupload
    $filename = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
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
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            die("Maaf, terjadi kesalahan saat mengupload file."); // Pesan kesalahan
        }
    }

    if ($id) {
        // Jika mengedit, update data
        if ($filename) {
            // Jika ada gambar baru, update gambar
            $conn->query("UPDATE data SET nama='$nama', deskripsi='$deskripsi', gambar='$target_file' WHERE id='$id'");
        } else {
            // Jika tidak ada gambar baru, update tanpa mengganti gambar
            $conn->query("UPDATE data SET nama='$nama', deskripsi='$deskripsi' WHERE id='$id'");
        }
    } else {
        // Jika menambah data baru
        $conn->query("INSERT INTO data (nama, deskripsi, gambar) VALUES ('$nama', '$deskripsi', '$target_file')");
    }

    header('Location: dashboard_admin.php'); // Redirect setelah sukses
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
</head>

<body>
    <h2>Dashboard Admin</h2>
    <a href="logout.php">Logout</a>

    <h3>Data</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['deskripsi']; ?></td>
                <td><img src="<?php echo $row['gambar']; ?>" alt="Gambar" style="max-width: 100px;" /></td>
                <td>
                    <a href="?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?delete_id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3><?php echo $id ? 'Edit Data' : 'Tambah Data'; ?></h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($currentData['nama'] ?? ''); ?>" required>
        <br>
        <label>Deskripsi:</label>
        <textarea name="deskripsi" required><?php echo htmlspecialchars($currentData['deskripsi'] ?? ''); ?></textarea>
        <br>
        <label>Gambar:</label><br>
        <?php if ($currentData && $currentData['gambar']): ?>
            <img src="<?php echo $currentData['gambar']; ?>" alt="Gambar" style="max-width: 200px;" /><br>
        <?php endif; ?>
        <input type="file" name="gambar" accept="image/*">
        <br>
        <button type="submit"><?php echo $id ? 'Update' : 'Tambah'; ?></button>
        <link rel="stylesheet" href="styles.css">
    </form>
</body>

</html>