<?php 
include 'header.php';

// Create a new MySQLi connection (assuming the connection variables are set)
$servername = "localhost"; // Example host
$username = "username"; // Your database username
$password = "password"; // Your database password
$dbname = "database"; // Your database name

$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = date('yy-m-d');

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
    <h2 style=" width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan Pembatalan Pesanan</b></h2>
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
                        <td><button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-save-file"></i> Export to Excel</button></td>
                        <td> &nbsp;</td>
                        <td><a href="" onclick="window.print()" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Cetak</a></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br>
    <br>
    <table class="table table-striped">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>tanggal</th>
            <th>qty</th>
        </tr>
        <?php 
        if(isset($_POST['submit'])){
            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM produksi WHERE tolak = 1 AND tanggal BETWEEN ? AND ?");
            $stmt->bind_param("ss", $date1, $date2); // "ss" means two string parameters
            $stmt->execute();
            $result = $stmt->get_result();
            
            $no = 1;
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $row['nama_produk']; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                    <td><?= $row['qty']; ?></td>
                </tr>
                <?php 
                $total += $row['qty'];
                $no++;
            }

            ?>
            <tr>
                <td colspan="4" class="text-right"><b>Jumlah dibatalkan = <?= $total; ?></b></td>
            </tr>
        <?php 
        }
        ?>
    </table>
</div>

<br>
<br>
<br>
<br>
<br>

<?php 
include 'footer.php';
?>
