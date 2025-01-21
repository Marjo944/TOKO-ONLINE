<?php 
include '../../koneksi/koneksi.php';
$inv = $_GET['inv'];

$conn = new mysqli("localhost", "root", "", "dbbangunan"); // Pastikan variabel koneksi DB sudah benar.

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$result = $conn->query("SELECT * from produksi where invoice = '$inv'");

while($row = $result->fetch_assoc()){
    $kodep = $row['kode_produk'];
    $t_bom = $conn->query("SELECT * FROM bom_produk WHERE kode_produk = '$kodep'");

    while($row1 = $t_bom->fetch_assoc()){
        $kodebk = $row1['kode_bk'];

        $inventory = $conn->query("SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
        $r_inv = $inventory->fetch_assoc();

        $kebutuhan = $row1['kebutuhan'];    
        $qtyorder = $row['qty'];
        $inven = $r_inv['qty'];
        $bom = ($kebutuhan * $qtyorder);
        $hasil = $inven - $bom;

        $conn->query("UPDATE inventory SET qty = '$hasil' WHERE kode_bk = '$kodebk'");

        if($conn->affected_rows > 0){
            $conn->query("UPDATE produksi SET terima = '1', status = '0' WHERE invoice = '$inv'");

            echo "
            <script>
            alert('PESANAN BERHASIL DITERIMA, BAHAN BAKU TELAH DIKURANGKAN');
            window.location = '../produksi.php';
            </script>
            ";
        }

    }

}

$conn->close();
?>
