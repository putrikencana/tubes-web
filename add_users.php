<?php
include 'db.php';

// Hash password
$admin_password = password_hash('admin', PASSWORD_DEFAULT);
$ken_password = password_hash('123', PASSWORD_DEFAULT);
$cana_password = password_hash('1234', PASSWORD_DEFAULT);

// Tambahkan pengguna awal
$conn->query("INSERT INTO users (username, password, role) VALUES 
    ('admin', '$admin_password', 'admin'), 
    ('ken', '$ken_password', 'user'), 
    ('cana', '$cana_password', 'user')");
?>