<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login dan adalah user
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit();
}

$data = $conn->query("SELECT * FROM data");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard User</title>
</head>

<body>
    <h2>Dashboard User</h2>
    <a href="logout.php">Logout</a>

    <h3>Data</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
        </tr>
        <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                <td>
                    <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar" style="max-width: 100px;" />
                </td>
            </tr>
            <link rel="stylesheet" href="styles.css">
        <?php endwhile; ?>
    </table>
</body>

</html>