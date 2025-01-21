<?php 
include '../../koneksi/koneksi.php';

$kode = $_POST['kd_material'];
$nama = $_POST['nama'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];
$harga = $_POST['harga'];
$tanggal = date("y-m-d");

// Menggunakan prepared statement untuk mengupdate data inventory
$stmt = $conn->prepare("UPDATE inventory SET kode_bk = ?, nama = ?, qty = ?, satuan = ?, harga = ?, tanggal = ? WHERE kode_bk = ?");
$stmt->bind_param("ssiiiss", $kode, $nama, $stok, $satuan, $harga, $tanggal, $kode); // Menyusun parameter yang sesuai

if ($stmt->execute()) {
    echo "
    <script>
    alert('DATA BERHASIL DIUPDATE');
    window.location = '../inventory.php';
    </script>
    ";
} else {
    echo "
    <script>
    alert('Gagal Memperbarui Data');
    window.location = '../inventory.php';
    </script>
    ";
}

$stmt->close();
?>
