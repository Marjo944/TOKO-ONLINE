<?php 
include '../../koneksi/koneksi.php';

$kode = $_GET['kode'];

// Menggunakan prepared statement untuk mengambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk = ?");
$stmt->bind_param("s", $kode); // "s" berarti string
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Hapus file gambar produk
$imagePath = "../../image/produk/" . $row['image'];
if (file_exists($imagePath)) {
    unlink($imagePath);
}

// Menggunakan prepared statement untuk menghapus data dari bom_produk
$stmt = $conn->prepare("DELETE FROM bom_produk WHERE kode_produk = ?");
$stmt->bind_param("s", $kode); // Mengikat parameter kode_produk
$stmt->execute();
$stmt->close();

// Menggunakan prepared statement untuk menghapus produk
$stmt = $conn->prepare("DELETE FROM produk WHERE kode_produk = ?");
$stmt->bind_param("s", $kode); // Mengikat parameter kode_produk
$stmt->execute();
$stmt->close();

// Cek apakah query delete berhasil
if ($stmt->affected_rows > 0) {
    echo "
    <script>
    alert('DATA BERHASIL DIHAPUS');
    window.location = '../m_produk.php';
    </script>
    ";
} else {
    echo "
    <script>
    alert('Gagal Menghapus Data');
    window.location = '../m_produk.php';
    </script>
    ";
}
?>
