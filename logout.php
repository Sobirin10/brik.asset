<?php
session_start();
session_destroy(); // Hapus sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Aplikasi Manajemen Aset</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .logout-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .logout-container h1 {
            color: #28a745; /* Warna hijau untuk konfirmasi */
            margin-bottom: 20px;
        }

        .logout-container p {
            margin-bottom: 20px;
            color: #555;
        }

        .logout-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body> 
    <div class="logout-container">
        <h1>Logout Berhasil!</h1>
        <p>Anda telah berhasil keluar dari aplikasi. Silakan klik tombol di bawah ini untuk kembali ke halaman login.</p>
        <a href="login.php">Kembali ke Login</a>
    </div>
</body>
</html>
