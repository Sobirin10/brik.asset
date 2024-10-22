<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Cek apakah pengguna adalah admin
if ($_SESSION['username'] !== 'User1') {
    echo "<h1>Anda tidak memiliki akses untuk menghapus barang ini.</h1>";
    echo "<a href='index.php'>Kembali ke Dashboard</a>";
    exit;
}

// Kode untuk menghapus barang jika pengguna adalah admin...
?>
