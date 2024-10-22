<?php
session_start();
$host = 'localhost';
$dbname = 'aset'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

if (isset($_POST['export'])) {
    $format = $_POST['format'];

    // Get data from database
    $stmt = $pdo->prepare("SELECT * FROM barang");
    $stmt->execute();
    $barangList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($format === 'excel') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="barang.xls"');
        
        echo "ID Barang\tNama Barang\tJumlah Barang\tQR Code\tWaktu Ditambahkan\tWaktu Diperbarui\n";
        foreach ($barangList as $b) {
            echo "{$b['id_barang']}\t{$b['nama_barang']}\t{$b['jumlah_barang']}\t{$b['qr_code']}\t{$b['created_at']}\t{$b['updated_at']}\n";
        }
    } elseif ($format === 'pdf') {
        // Include a library like TCPDF or MPDF to generate PDF files
        // This is a placeholder, you can implement PDF generation as per your needs
        echo "PDF export functionality not implemented yet.";
    }
    exit();
}
