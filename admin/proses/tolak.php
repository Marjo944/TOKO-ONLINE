<?php 
include '../../koneksi/koneksi.php';

// Membuat koneksi menggunakan new mysqli
$conn = new mysqli("localhost", "root", "", "dbbangunan"); // Pastikan untuk mengganti parameter dengan benar

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$inv = $_GET['inv'];

// Menyiapkan query untuk update status produksi
$stmt = $conn->prepare("UPDATE produksi SET tolak = '1', terima = '2' WHERE invoice = ?");
$stmt->bind_param("s", $inv);

// Eksekusi query dan periksa hasilnya
if ($stmt->execute()) {
    echo "
    <script>
    alert('PESANAN DITOLAK');
    window.location = '../produksi.php';
    </script>
    ";
} else {
    echo "
    <script>
    alert('TERJADI KESALAHAN SAAT MENOLAK PESANAN');
    window.location = '../produksi.php';
    </script>
    ";
}

$stmt->close();
$conn->close();
?>
