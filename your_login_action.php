<?php
session_start(); // Mulai sesi

// Data autentikasi untuk 8 pengguna
$valid_users = [
    'User1' => ['password' => 'Brik123', 'role' => 'admin', 'location' => 'All'], // Admin dengan akses ke semua database
    'User2' => ['password' => 'Brik456', 'role' => 'branch', 'location' => 'BRIK Mix Legok'], // Hanya akses ke Legok
    'User3' => ['password' => 'Brik789', 'role' => 'branch', 'location' => 'BRIK Mix Cikarang'], // Hanya akses ke Cikarang
    'User4' => ['password' => 'Brik012', 'role' => 'branch', 'location' => 'BRIK Mix Alam Sutera'], // Hanya akses ke Alam Sutera
    'User5' => ['password' => 'Brik345', 'role' => 'branch', 'location' => 'BRIK Mix Sentul'], // Hanya akses ke Sentul
    'User6' => ['password' => 'Brik678', 'role' => 'branch', 'location' => 'BRIK Mix Kelapa Gading'], // Hanya akses ke Kelapa Gading
    'User7' => ['password' => 'Brik901', 'role' => 'branch', 'location' => 'BRIK Mix Karawang'], // Hanya akses ke Karawang
    'User8' => ['password' => 'Brik234', 'role' => 'branch', 'location' => 'BRIK Mix Bekasi Barat'], // Hanya akses ke Bekasi Barat
    'User9' => ['password' => 'Brik567', 'role' => 'branch', 'location' => 'BRIK Mix HO'] // Hanya akses ke HO
];

// Cek jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username ada dan password valid
    if (array_key_exists($username, $valid_users) && $valid_users[$username]['password'] === $password) {
        $_SESSION['username'] = $username; // Simpan username di session
        $_SESSION['role'] = $valid_users[$username]['role']; // Simpan role di session
        $_SESSION['location'] = $valid_users[$username]['location']; // Simpan lokasi database yang diakses

        // Jika User1, berikan akses ke semua plant
        if ($valid_users[$username]['role'] === 'admin') {
            header('Location: index.php'); // Arahkan ke halaman admin dengan akses penuh
        } else {
            // Arahkan ke halaman plant sesuai dengan lokasi pengguna
            switch ($_SESSION['location']) {
                case 'BRIK Mix Legok':
                    header('Location: plant/plant_legok.php');
                    break;
                case 'BRIK Mix Cikarang':
                    header('Location: plant/plant_cikarang.php');
                    break;
                case 'BRIK Mix Alam Sutera':
                    header('Location: plant/plant_alam_sutera.php');
                    break;
                case 'BRIK Mix Sentul':
                    header('Location: plant/plant_sentul.php');
                    break;
                case 'BRIK Mix Kelapa Gading':
                    header('Location: plant/plant_kelapa_gading.php');
                    break;
                case 'BRIK Mix Karawang':
                    header('Location: plant/plant_karawang.php');
                    break;
                case 'BRIK Mix Bekasi Barat':
                    header('Location: plant/plant_bekasi_barat.php');
                    break;
                case 'BRIK Mix HO':
                    header('Location: plant/plant_ho.php');
                    break;
                default:
                    echo "<script>alert('Lokasi tidak valid!');</script>";
                    echo "<script>window.location.href = 'login.php';</script>";
                    exit;
            }
        }
        exit;
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
} else {
    // Jika bukan POST, arahkan kembali ke login
    header('Location: login.php');
    exit;
}
?>