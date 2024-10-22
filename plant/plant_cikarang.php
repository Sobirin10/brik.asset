<?php
session_start();

// Koneksi ke database
$host = 'localhost';
$dbname = 'aset_cikarang'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Pastikan folder uploads ada
if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
}

// Proses untuk menyimpan data baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nama_barang = $_POST['nama_barang'];
        $jumlah_barang = $_POST['jumlah_barang'];
        $id_barang_prefix = $_POST['id_barang_prefix']; 

        $qr_code = '';
        if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['qr_code']['tmp_name'];
            $qr_code = 'uploads/' . basename($_FILES['qr_code']['name']);
            move_uploaded_file($tmp_name, $qr_code);
        }

        for ($i = 0; $i < $jumlah_barang; $i++) {
            $stmt = $pdo->prepare("INSERT INTO barang (id_barang, nama_barang, qr_code) VALUES (?, ?, ?)");
            $stmt->execute([$id_barang_prefix . '-' . ($i + 1), $nama_barang, $qr_code]);
        }

        $_SESSION['message'] = "Barang berhasil ditambahkan!";
        $_SESSION['msg_type'] = "update"; // Untuk menambahkan barang
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
} elseif (isset($_POST['update'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah_barang = $_POST['jumlah_barang']; 

    $qr_code = $_POST['existing_qr_code'];
    if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['qr_code']['tmp_name'];
        $qr_code = 'uploads/' . basename($_FILES['qr_code']['name']);
        move_uploaded_file($tmp_name, $qr_code); 
    }

    $stmt = $pdo->prepare("UPDATE barang SET nama_barang = ?, qr_code = ? WHERE id_barang = ?");
    $stmt->execute([$nama_barang, $qr_code, $id_barang]);

    $_SESSION['message'] = "Barang berhasil diperbarui!";
    $_SESSION['msg_type'] = "info"; // Untuk memperbarui barang
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses untuk menghapus data
if (isset($_GET['delete'])) {
    $id_barang = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang = ?");
    $stmt->execute([$id_barang]);
    $_SESSION['message'] = "Barang berhasil dihapus!";
    $_SESSION['msg_type'] = "delete"; // Untuk menghapus barang
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses untuk mendapatkan data untuk di-edit
$barang = [];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE id_barang = ?");
    $stmt->execute([$id]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Proses pencarian
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE nama_barang LIKE ?");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->prepare("SELECT * FROM barang");
    $stmt->execute();
}
$barangList = $stmt->fetchAll();

// Menghitung jumlah barang berdasarkan nama
$jumlahBarang = [];
foreach ($barangList as $b) {
    $nama = $b['nama_barang'];
    if (!isset($jumlahBarang[$nama])) {
        $jumlahBarang[$nama] = 0;
    }
    $jumlahBarang[$nama]++;
}

// Menghitung total semua barang
$totalCount = array_sum($jumlahBarang); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIK Mix Cikarang</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
            body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #fff;
        }
        .alert.info {
    background-color: #007bff; /* Biru */
    }
    .alert.update {
    background-color: #28a745; /* Hijau */
    }
    .alert.delete {
    background-color: #dc3545; /* Merah */
}

        {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap');

.animated-text {
    display: inline-block;
    animation: slide 10s infinite alternate;
    font-size: 28px; /* Ukuran font yang lebih kecil */
    color: #2c3e50; /* Warna lebih gelap untuk kesan elegan */
    font-family: 'Playfair Display', serif; /* Font yang lebih mewah */
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2); /* Efek bayangan */
}

@keyframes slide {
    0% { transform: translateY(0); }
    100% { transform: translateY(-10px); }
}


        @keyframes slide {
    0% { transform: translateX(-10%); } /* Bergerak sedikit ke kiri */
    50% { transform: translateX(0); }   /* Kembali ke posisi semula */
    100% { transform: translateX(10%); } /* Bergerak sedikit ke kanan */
}

    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.8); /* Black background with opacity */
}
.logout-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
.modal-content {
    margin: auto;
    display: block;
    width: 80%; /* Could be more or less, depends on screen size */
    max-width: 700px;
}

.close {
    position: absolute;
    top: 15px;
    right: 25px;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
}


        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 30px;
        }

        .logo {
            display: block;
            margin: 0 auto;
            height: 80px; /* Ukuran logo */
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        form {
            margin-bottom: 30px;
        }

        input, button {
            padding: 10px;
            margin: 5px;
            width: 200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-button, .delete-button {
            padding: 5px 10px;
            cursor: pointer;
            color: white;
            border: none;
        }

        .edit-button {
            background-color: #28a745;
        }

        .delete-button {
            background-color: #dc3545;
        }
        .total-count {
    color: #007bff; /* Warna biru untuk teks total */
    font-size: 24px; /* Ukuran font lebih besar */
    font-weight: bold; /* Teks tebal */
}

.qr-code {
            cursor: pointer;
            width: 75px;
            height: 50px;
        }
        .qr-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .qr-modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
        #detail-barang {
    margin-top: 20px;
    padding: 20px;
    background-color: #ffffff; /* Putih untuk latar belakang */
    border-radius: 8px; /* Sudut melengkung */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Bayangan halus */
    transition: box-shadow 0.3s; /* Transisi halus untuk bayangan */
}
#detail-barang h3 {
    color: #333; /* Warna judul */
    margin-bottom: 15px; /* Ruang bawah */
}

#detail-barang ul {
    list-style-type: none; /* Hilangkan bullet */
    padding: 0; /* Hilangkan padding */
}
#detail-barang li {
    padding: 10px; /* Ruang dalam */
    border-bottom: 1px solid #ddd; /* Garis pemisah */
    display: flex; /* Flexbox untuk penataan */
    justify-content: space-between; /* Jarak antara elemen */
}

#detail-barang li:last-child {
    border-bottom: none; /* Hilangkan garis bawah di item terakhir */
}
#detail-barang li span {
    font-weight: bold; /* Teks tebal untuk label */
    color: #555; /* Warna teks */
}

    </style>
</head>
<body>
    <div class="container">
        <img src="../assets/images/logo.svg" alt="BRIK Logo" class="logo">
        <p class="animated-text">Selamat datang di halaman detail plant Cikarang.</p>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['msg_type']; ?>">
                <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Form untuk menambahkan atau memperbarui barang -->
        <h2><?php echo $barang ? 'Edit Barang' : 'Tambah Barang'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($barang): ?>
                <input type="hidden" name="id_barang" value="<?php echo $barang['id_barang']; ?>"> 
                <input type="hidden" name="existing_qr_code" value="<?php echo $barang['qr_code']; ?>"> 
            <?php endif; ?>
            <input type="text" name="nama_barang" placeholder="Nama Barang" required value="<?php echo $barang ? $barang['nama_barang'] : ''; ?>">
            <input type="number" name="jumlah_barang" placeholder="Jumlah Barang" required value="<?php echo $barang ? $barang['jumlah_barang'] : ''; ?>">
            <input type="text" name="id_barang_prefix" placeholder="Prefix ID Barang" required>
            <input type="file" name="qr_code" accept="image/*" <?php echo $barang ? '' : 'required'; ?>>
            <button type="submit" name="<?php echo $barang ? 'update' : 'create'; ?>">
                <?php echo $barang ? 'Update Barang' : 'Tambah Barang'; ?>
            </button>
        </form>

        <!-- Form Pencarian -->
        <h2>Pencarian Barang</h2>
        <form method="GET">
            <input type="text" name="search" placeholder="Cari Barang" value="<?php echo $search; ?>">
            <button type="submit">Cari</button>
        </form>

        <h2>Total Jumlah Barang: <span class="total-count"><?php echo $totalCount; ?></span></h2>

        <!-- Tombol untuk Menampilkan/Sembunyikan Barang -->
        <h2>Daftar Barang</h2>
        <button id="toggle-button" onclick="toggleVisibility()">Tampilkan Semua</button>
        <button onclick="printTable()" style="margin-bottom: 20px;">Cetak Daftar Barang</button>

        <table id="barang-table" style="display: none;">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>QR Code</th>
                    <th>Waktu Ditambahkan</th>
                    <th>Waktu Diperbarui</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangList as $b): ?>
                    <tr>
                        <td><?php echo $b['id_barang']; ?></td>
                        <td><?php echo $b['nama_barang']; ?></td>
                        <td><?php echo $jumlahBarang[$b['nama_barang']]; ?></td>
                        <td>
                            <img src="<?php echo $b['qr_code']; ?>" alt="QR Code" class="qr-code" onclick="openModal('<?php echo $b['qr_code']; ?>')">
                        </td>
                        <td><?php echo date("Y-m-d H:i:s", strtotime($b['created_at'])); ?></td>
                        <td><?php echo date("Y-m-d H:i:s", strtotime($b['updated_at'])); ?></td>
                        <td>
                            <a href="?edit=<?php echo $b['id_barang']; ?>" class="edit-button">Edit</a>
                            <a href="?delete=<?php echo $b['id_barang']; ?>" class="delete-button" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="detail-barang" style="display: none;">
            <h3>Detail Jumlah Barang</h3>
            <ul>
                <?php foreach ($jumlahBarang as $nama => $jumlah): ?>
                    <li><?php echo $nama . ': ' . $jumlah; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="navigation">
            <a href="../index.php" class="back-button">Kembali ke Dashboard</a>
            <a href="../logout.php" class="logout-button" style="margin-left: 10px;">Logout</a>
        </div>

        <!-- QR Code Modal -->
        <div id="qrModal" class="qr-modal" onclick="closeModal()">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="qr-modal-content" id="modalImage" src="">
        </div>
    </div>

    <script>
        let showAll = false;

        function toggleVisibility() {
            const table = document.getElementById('barang-table');
            const button = document.getElementById('toggle-button');
            const details = document.getElementById('detail-barang');

            if (showAll) {
                table.style.display = 'none'; // Sembunyikan semua
                details.style.display = 'none'; // Sembunyikan detail
                button.textContent = 'Tampilkan Semua';
            } else {
                table.style.display = 'table'; // Tampilkan semua
                details.style.display = 'block'; // Tampilkan detail
                button.textContent = 'Sembunyikan';
            }

            showAll = !showAll;
        }

        function printTable() {
    var printContent = `
        <h2>Daftar Barang</h2>
        <h3>Total Jumlah Barang: ${<?php echo $totalCount; ?>}</h3>
        <h3>Detail Jumlah Barang</h3>
        <ul>
    `;

    <?php foreach ($jumlahBarang as $nama => $jumlah): ?>
        printContent += `
            <li>${<?php echo json_encode($nama); ?>}: ${<?php echo json_encode($jumlah); ?>}</li>
        `;
    <?php endforeach; ?>

    printContent += `</ul><table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>QR Code</th>
                <th>Waktu Ditambahkan</th>
                <th>Waktu Diperbarui</th>
            </tr>
        </thead>
        <tbody>
    `;

    <?php foreach ($barangList as $b): ?>
        printContent += `
            <tr>
                <td><?php echo $b['id_barang']; ?></td>
                <td><?php echo $b['nama_barang']; ?></td>
                <td><?php echo $jumlahBarang[$b['nama_barang']]; ?></td>
                <td><img src="<?php echo $b['qr_code']; ?>" alt="QR Code" style="width: 50px; height: 50px;"></td>
                <td><?php echo date("Y-m-d H:i:s", strtotime($b['created_at'])); ?></td>
                <td><?php echo date("Y-m-d H:i:s", strtotime($b['updated_at'])); ?></td>
            </tr>
        `;
    <?php endforeach; ?>

    printContent += `
            </tbody>
        </table>
    `;

    var newWindow = window.open('', '', 'width=800,height=600');
    newWindow.document.write('<html><head><title>Cetak Daftar Barang</title>');
    newWindow.document.write('</head><body>');
    newWindow.document.write(printContent);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.print();
}
        function openModal(src) {
            const modal = document.getElementById("qrModal");
            const modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = src;
        }

        function closeModal() {
            const modal = document.getElementById("qrModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>