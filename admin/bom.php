<?php 
include 'header.php';
$kode = $_GET['kode'];

// Menggunakan koneksi new mysqli
$conn = new mysqli("localhost", "root", "", "dbbangunan"); // Gantilah dengan data koneksi yang sesuai

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$pr = $conn->query("SELECT * FROM produk WHERE kode_produk = '$kode'");
$tam = $pr->fetch_assoc();
?>

<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Master Produk</b></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Produk</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Image</th>
                <th scope="col">Harga</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $result = $conn->query("SELECT * FROM produk");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $row['kode_produk']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><img src="../image/produk/<?= $row['image']; ?>" width="100"></td>
                <td>Rp.<?= number_format($row['harga']); ?></td>
                <td>
                    <a href="edit_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="proses/del_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></a>
                    <a href="bom.php?bom=" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Lihat BOM</a>
                </td>
            </tr>
            <?php
                $no++; 
            }
            ?>
        </tbody>
    </table>
    <a href="tm_produk.php" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Produk</a>
</div>

<!-- Button trigger modal -->
<button type="hidden" data-toggle="modal" data-target="#myModal" id="btn" style="background-color: #fff; border: #fff;"></button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="m_produk.php" class="btn btn-default close"></a>
                <h4 class="modal-title" id="myModalLabel">BOM PRODUK <?= strtoupper($tam['nama']); ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>Nama Material</th>
                        <th>Kebutuhan Material</th>
                    </tr>
                    <?php 
                    $result1 = $conn->query("SELECT i.nama as nama, b.kebutuhan as jml, i.satuan as satu FROM bom_produk b JOIN inventory i ON b.kode_bk = i.kode_bk WHERE b.kode_produk = '$kode'");
                    $no = 1;
                    while ($row1 = $result1->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row1['nama']; ?></td>
                        <td><?= $row1['jml'] . " " . $row1['satu']; ?></td>
                    </tr>
                    <?php 
                    $no++;
                    }
                    ?>
                </table>
            </div>
            <div class="modal-footer">
                <a href="m_produk.php" class="btn btn-default">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $( "#btn" ).click();
    });
</script>

<?php 
include 'footer.php';
?>
