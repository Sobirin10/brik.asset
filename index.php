<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit;
}

// Cek apakah pengguna adalah User1 (admin)
$isAdmin = ($_SESSION['username'] === 'User1');

// Jika bukan User1, tampilkan pesan tidak ada akses dan hentikan proses
if (!$isAdmin) {
    echo "
    
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Akses Ditolak</title>
        <link rel='stylesheet' href='assets/css/style.css'>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #e9ecef;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #444;
            }

            .access-denied-container {
                text-align: center;
                background-color: white;
                border: 2px solid #007bff;
                border-radius: 10px;
                padding: 40px;
                max-width: 600px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            }

            .access-denied-container h1 {
                font-size: 36px;
                color: #007bff;
                margin-bottom: 20px;
                text-transform: uppercase;
            }

            .access-denied-container p {
                font-size: 18px;
                margin-bottom: 20px;
                color: #444;
            }

            .access-denied-container a {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border-radius: 25px;
                text-decoration: none;
                transition: background-color 0.3s;
            }

            .access-denied-container a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='access-denied-container'>
            <h1>Akses Ditolak</h1>
            <p>Anda tidak memiliki akses ke halaman ini.</p>
            <a href='login.php'>Kembali ke Login</a>
        </div>
    </body>
    </html>
    ";
    exit;
}

// Fungsi untuk menampilkan pesan akses ditolak
function showAccessDeniedMessage() {
    echo "
    <script>
        alert('Akses ditolak. Anda tidak memiliki hak untuk melakukan tindakan ini.');
        window.location.href = 'dashboard.php'; // Arahkan kembali ke dashboard
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard BRIK Mix</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            color: #444;
            line-height: 1.6;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .logout-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            text-align: center;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s;
        }

        .container:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #007bff;
            margin-bottom: 40px;
            font-size: 48px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-transform: uppercase;
            cursor: pointer;
        }

        h1 img {
            margin-right: 15px;
            height: 60px;
            transition: transform 0.5s;
        }

        .plants {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
            justify-items: center;
        }

        .plant-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .plant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .plant-card a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            position: relative;
        }

        .plant-card a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #007bff;
            transition: width 0.3s;
            position: absolute;
            left: 50%;
            bottom: -5px;
            margin-left: -50%;
        }

        .plant-card a:hover::after {
            width: 100%;
        }

        .ho-card {
            grid-column: 3;
            justify-self: end;
        }

        footer {
            margin-top: 50px;
            font-size: 14px;
            color: #666;
        }

        .greeting {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .logout-button {
                width: 100%;
            }
        }
        </style>
</head>
<body>
    <div class="container">
        <div class="greeting">
            Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
        <h1 onclick="flyLogo(this)">
            <img src="assets/images/logo.svg" alt="BRIK Logo">
            <span>Mix Dashboard</span>
        </h1>
        <div class="plants">
            <div class="plant-card">
                <a href="plant/plant_legok.php">BRIK Mix Legok</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_cikarang.php">BRIK Mix Cikarang</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_alam_sutera.php">BRIK Mix Alam Sutera</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_sentul.php">BRIK Mix Sentul</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_kelapa_gading.php">BRIK Mix Kelapa Gading</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_karawang.php">BRIK Mix Karawang</a>
            </div>
            <div class="plant-card">
                <a href="plant/plant_bekasi_barat.php">BRIK Mix Bekasi Barat</a>
            </div>
            <div class="plant-card ho-card">
                <a href="plant/plant_ho.php">BRIK Mix HO</a>
            </div>
        </div>
        
        <form method="POST" action="logout.php">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
        
        <footer>
            &copy; 2024 BRIK Mix. All rights reserved.
        </footer>
    </div>

    <script>
            function editItem() {
                <?php if (!$isAdmin) { showAccessDeniedMessage(); } else { ?>
                    // Logika untuk mengedit item
                    alert('Fungsi Edit sedang diproses...');
                <?php } ?>
            }

            function deleteItem() {
                <?php if (!$isAdmin) { showAccessDeniedMessage(); } else { ?>
                    // Logika untuk menghapus item
                    alert('Fungsi Hapus sedang diproses...');
                <?php } ?>
            }
        </script>
</body>
</html>