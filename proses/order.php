<?php 
// Include database connection
include '../koneksi/koneksi.php'; // Make sure this file has the correct DB credentials

// Get the POST data
$kd_cs = $_POST['kode_cs'];
$nama = $_POST['nama'];
$prov = $_POST['prov'];
$kota = $_POST['kota'];
$alamat = $_POST['almt'];
$kopos = $_POST['kopos'];
$tanggal = date('yy-m-d');

// Create a new MySQLi object (the connection is already established in koneksi.php)
$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest invoice number from the 'produksi' table
$kode = $conn->query("SELECT invoice FROM produksi ORDER BY invoice DESC");
$data = $kode->fetch_assoc();
$num = substr($data['invoice'], 3, 4);
$add = (int) $num + 1;

// Format the invoice number
if(strlen($add) == 1){
    $format = "INV000".$add;
} else if(strlen($add) == 2){
    $format = "INV00".$add;
} else if(strlen($add) == 3){
    $format = "INV0".$add;
} else {
    $format = "INV".$add;
}

// Fetch the items in the cart (keranjang) for the given customer
$keranjang = $conn->query("SELECT * FROM keranjang WHERE kode_customer = '$kd_cs'");

// Loop through the cart items and insert them into the 'produksi' table
while($row = $keranjang->fetch_assoc()){
    $kd_produk = $row['kode_produk'];
    $nama_produk = $row['nama_produk'];
    $qty = $row['qty'];
    $harga = $row['harga'];
    $status = "Pesanan Baru";

    // Insert the order into the 'produksi' table
    $order = $conn->query("INSERT INTO produksi 
                            VALUES('', '$format', '$kd_cs', '$kd_produk', '$nama_produk', '$qty', '$harga', '$status', '$tanggal', '$prov', '$kota', '$alamat', '$kopos', '0', '0', '0')");
}

// Delete the items from the cart after processing the order
$del_keranjang = $conn->query("DELETE FROM keranjang WHERE kode_customer = '$kd_cs'");

// Check if the cart was deleted and redirect to the 'selesai' page
if ($del_keranjang) {
    header("Location: ../selesai.php");
    exit;
}

// Close the connection
$conn->close();
?>
