<?php 
include 'header.php';
include '../koneksi/koneksi.php';  // Menyertakan file koneksi database

// Membuat objek database
$db = new Database("localhost", "root", "", "dbbangunan");  // Membuat objek dari class Database

if(isset($_GET['cek'])){
    $cek = $_GET['cek'];
    $db->query("UPDATE produksi SET cek = '$cek'");  // Menggunakan metode query untuk memperbarui data
}

if(isset($_GET['page'])){
    $kode = $_GET['kode'];
    $result = $db->query("DELETE FROM inventory WHERE kode_bk = '$kode'");  // Menggunakan metode query untuk menghapus data

    if($result){
        echo "
        <script>
        alert('DATA BERHASIL DIHAPUS');
        window.location = 'inventory.php';
        </script>
        ";
    }
}

?>

<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Inventory Material</b></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Material</th>
                <th scope="col">Nama</th>
                <th scope="col">Stok</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Mengambil data dari database
            $result = $db->query("SELECT * FROM inventory ORDER BY kode_bk ASC");
            $rows = $db->fetchAll($result);  // Mengambil semua hasil query sebagai array
            $no = 1;
            foreach ($rows as $row) {
            ?>
                <tr>
                    <th scope="row"><?php echo $no; ?></th>
                    <td><?= $row['kode_bk']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['qty']; ?></td>
                    <td><?= $row['satuan']; ?></td>
                    <td><?php echo number_format($row['harga']) . "/" . $row['satuan']; ?></td>
                    <td>
                        <a href="edit_inventory.php?kode=<?php echo $row['kode_bk']; ?>" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="inventory.php?kode=<?php echo $row['kode_bk']; ?>&page=del" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            <?php 
                $no++;
            }
            ?>
        </tbody>
    </table>
    <a href="tm_inventory.php" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Material</a>
</div>

<?php 
$db->close();  // Menutup koneksi setelah selesai
include 'footer.php';
?>
