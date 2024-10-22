<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika belum login
    exit;
}

// Cek nama pengguna untuk menentukan akses
$username = $_SESSION['username'];
$isAdmin = ($username === 'm_sobirin10');

// Array aset per cabang BRIK Mix
$assets = [
    'BRIK Mix HO' => [
        ['Laptop', 'ID-001', 10],
        ['Printer', 'ID-002', 5],
        ['Proyektor', 'ID-003', 3],
        ['AC', 'ID-004', 8],
        ['Telepon Kantor', 'ID-005', 6],
        ['Lemari Arsip', 'ID-006', 4],
        ['Kursi Kantor', 'ID-007', 20],
        ['Meja Kantor', 'ID-008', 15],
        ['Scanner', 'ID-009', 7],
        ['Whiteboard', 'ID-010', 2]
    ],
    'BRIK Mix Bekasi Barat' => [
        ['Forklift', 'ID-011', 2],
        ['Crane', 'ID-012', 1],
        ['Mixer', 'ID-013', 3],
        ['Komputer', 'ID-014', 10],
        ['Kamera CCTV', 'ID-015', 12],
        ['Stapel', 'ID-016', 6],
        ['Scanner', 'ID-017', 5],
        ['Kursi Roda', 'ID-018', 4],
        ['Meja Rapat', 'ID-019', 8],
        ['Lampu Proyektor', 'ID-020', 5]
    ],
    // Tambahkan cabang lain sesuai kebutuhan
];

// Menentukan cabang default
$default_location = 'BRIK Mix HO';

// Mengambil lokasi yang dipilih dari form
$selected_location = isset($_POST['location']) ? $_POST['location'] : $default_location;

// Aset yang akan ditampilkan tergantung pada lokasi yang dipilih
$selected_assets = $assets[$selected_location] ?? [];

// Pembatasan akses pengguna
if (!$isAdmin) {
    $selected_assets = array_slice($selected_assets, 0, 20); // Batasi 20 aset untuk user non-admin
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php'); // Arahkan ke halaman login setelah logout
    exit;
}

// Menyiapkan data untuk grafik
$locations = array_keys($assets);
$quantities = array_map(function($loc) use ($assets) {
    return array_sum(array_column($assets[$loc], 2)); // Jumlahkan total barang di setiap lokasi
}, $locations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Aset Kantor BRIK Mix</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-bar {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .asset-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .asset-table th, .asset-table td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .asset-table th {
            background-color: #007bff;
            color: white;
        }

        .no-result {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            color: #555;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .chart-container {
            margin-top: 50px;
            position: relative;
            height: 40vh;
            width: 80vw;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Aset Peralatan Kantor BRIK Mix - <?php echo $selected_location; ?></h1>
        
        <!-- Logout Button -->
        <form method="POST" action="">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>

        <!-- Dropdown untuk memilih lokasi -->
        <form method="POST" action="">
            <label for="location">Pilih Lokasi:</label>
            <select name="location" id="location" onchange="this.form.submit()">
                <option value="BRIK Mix HO" <?php echo $selected_location === 'BRIK Mix HO' ? 'selected' : ''; ?>>BRIK Mix HO</option>
                <option value="BRIK Mix Bekasi Barat" <?php echo $selected_location === 'BRIK Mix Bekasi Barat' ? 'selected' : ''; ?>>BRIK Mix Bekasi Barat</option>
                <option value="BRIK Mix Karawang" <?php echo $selected_location === 'BRIK Mix Karawang' ? 'selected' : ''; ?>>BRIK Mix Karawang</option>
                <option value="BRIK Mix Sentul" <?php echo $selected_location === 'BRIK Mix Sentul' ? 'selected' : ''; ?>>BRIK Mix Sentul</option>
                <option value="BRIK Mix Kelapa Gading" <?php echo $selected_location === 'BRIK Mix Kelapa Gading' ? 'selected' : ''; ?>>BRIK Mix Kelapa Gading</option>
                <option value="BRIK Mix Alam Sutera" <?php echo $selected_location === 'BRIK Mix Alam Sutera' ? 'selected' : ''; ?>>BRIK Mix Alam Sutera</option>
                <option value="BRIK Mix Cikarang" <?php echo $selected_location === 'BRIK Mix Cikarang' ? 'selected' : ''; ?>>BRIK Mix Cikarang</option>
                <option value="BRIK Mix Legok" <?php echo $selected_location === 'BRIK Mix Legok' ? 'selected' : ''; ?>>BRIK Mix Legok</option>
            </select>
        </form>

        <!-- Daftar aset -->
        <table class="asset-table" id="assetTable">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selected_assets as $asset): ?>
                <tr>
                    <td><?php echo $asset[1]; ?></td>
                    <td><?php echo $asset[0]; ?></td>
                    <td><?php echo $asset[2]; ?></td>
                    <td><canvas id="qrcode-<?php echo strtolower(str_replace(' ', '-', $asset[0])); ?>"></canvas></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="no-result" id="noResult" style="display: none;">Tidak ada hasil ditemukan.</p>

        <!-- Grafik jumlah barang per cabang -->
        <div class="chart-container">
            <canvas id="assetChart"></canvas>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        // Fungsi untuk membuat QR code
        function generateQRCode(text, elementId) {
            var qr = new QRious({
                element: document.getElementById(elementId),
                value: text,
                size: 100
            });
        }

        // Generate QR codes
        <?php foreach ($selected_assets as $asset): ?>
            generateQRCode('<?php echo $asset[0]; ?> - <?php echo $asset[1]; ?>', 'qrcode-<?php echo strtolower(str_replace(' ', '-', $asset[0])); ?>');
        <?php endforeach; ?>

        // Data untuk grafik
        const ctx = document.getElementById('assetChart').getContext('2d');
        const assetChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($locations); ?>,
                datasets: [{
                    label: 'Jumlah Barang',
                    data: <?php echo json_encode($quantities); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Barang'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Lokasi'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
