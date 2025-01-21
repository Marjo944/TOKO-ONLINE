<?php 
include 'header.php';
include '../koneksi/koneksi.php';  // Menyertakan file koneksi database

// Membuat objek database
$db = new Database("localhost", "root", "", "dbbangunan");

$date = date('yy-m-d');  // Default date

if(isset($_POST['submit'])){
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
}
?>
<style type="text/css">
    @media print{
        .print{
            display: none;
        }
    }
</style>
<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan Inventory</b></h2>
    <div class="row print">
        <div class="col-md-9">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <table>
                    <tr>
                        <td><input type="date" name="date1" class="form-control" value="<?= $date; ?>"></td>
                        <td>&nbsp; - &nbsp;</td>
                        <td><input type="date" name="date2" class="form-control" value="<?= $date; ?>"></td>
                        <td> &nbsp;</td>
                        <td><input type="submit" name="submit" class="btn btn-primary" value="Tampilkan"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-3">
            <form action="exp_pembatalan.php" method="POST">
                <table>
                    <tr>
                        <td><input type="hidden" name="date1" class="form-control" value="<?= $date1; ?>"></td>
                        <td><input type="hidden" name="date2" class="form-control" value="<?= $date2; ?>"></td>
                        <td><a href="" onclick="window.print()" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Cetak</a></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br><br>
    <table class="table table-striped">
        <tr>
            <th>No</th>
            <th>Nama Bahan Baku</th>
            <th>qty</th>
            <th>Satuan</th>
            <th>tanggal</th>
        </tr>
        <?php 
        if(isset($_POST['submit'])){
            // Mengambil data berdasarkan rentang tanggal
            $result = $db->query("SELECT * FROM inventory WHERE tanggal BETWEEN '$date1' AND '$date2'");
            $rows = $db->fetchAll($result);  // Mengambil hasil query sebagai array
            $no = 1;
            $total = 0;
            foreach ($rows as $row) {
        ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['qty']; ?></td>
            <td><?= $row['satuan']; ?></td>
            <td><?= $row['tanggal']; ?></td>
        </tr>
        <?php 
            $total += $row['qty'];
            $no++;
            }
        ?>
        <tr>
            <td colspan="5" class="text-right"><b>Jumlah semua bahan baku = <?= $total; ?></b></td>
        </tr>
        <?php } ?>
    </table>
</div>

<br><br><br><br><br><br>

<?php 
$db->close();  // Menutup koneksi setelah selesai
include 'footer.php';
?>
