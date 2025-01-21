<?php 
include '../../koneksi/koneksi.php';

$kode = $_POST['kd_material'];
$nama = $_POST['nama'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];
$harga = $_POST['harga'];
$tanggal = date("y-m-d");

// Menyiapkan query untuk insert data
$stmt = $conn->prepare("INSERT INTO inventory (kode_bk, nama, qty, satuan, harga, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissi", $kode, $nama, $stok, $satuan, $harga, $tanggal);

// Menjalankan query
$result = $stmt->execute();

// Mengecek apakah query berhasil dijalankan
if($result) {
    echo "
    <script>
        alert('DATA BERHASIL DITAMBAHKAN');
        window.location = '../inventory.php';
    </script>
    ";
} else {
    echo "
    <script>
        alert('Gagal menambahkan data');
        window.location = '../inventory.php';
    </script>
    ";
}

// Menutup prepared statement
$stmt->close();
?>
