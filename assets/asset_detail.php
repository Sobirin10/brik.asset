<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .detail-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .detail-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .detail-table th, .detail-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ccc;
        }

        .detail-table th {
            background-color: #007bff;
            color: white;
        }

        .detail-table td {
            background-color: #f9f9f9;
        }

        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <h1>Detail Barang</h1>

        <?php
        // Ambil data dari URL
        $nama_barang = isset($_GET['name']) ? $_GET['name'] : 'N/A';
        $jumlah_barang = isset($_GET['jumlah']) ? $_GET['jumlah'] : 'N/A';
        $plant = isset($_GET['plant']) ? $_GET['plant'] : 'N/A';
        ?>

        <table class="detail-table">
            <tr>
                <th>Nama Barang</th>
                <td><?php echo htmlspecialchars($nama_barang); ?></td>
            </tr>
            <tr>
                <th>Jumlah Barang</th>
                <td><?php echo htmlspecialchars($jumlah_barang); ?></td>
            </tr>
            <tr>
                <th>Plant</th>
                <td><?php echo htmlspecialchars($plant); ?></td>
            </tr>
        </table>

        <a href="javascript:history.back()" class="back-button">Kembali</a>
    </div>
</body>
</html>
