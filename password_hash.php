<?php
include 'db.php';

// Daftar pengguna dengan password yang akan di-hash
$users = [
    ['username' => 'admin', 'password' => 'admin'],
    ['username' => 'ken', 'password' => '123'],
    ['username' => 'cana', 'password' => '1234'],
];

foreach ($users as $user) {
    $username = $user['username'];
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$hashed_password' WHERE username='$username'");
}

echo "Password berhasil di-hash!";
?>