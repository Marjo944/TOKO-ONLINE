<?php 
include 'header.php';

// Database connection setup using new mysqli
$servername = "localhost"; // Example host
$username = "username"; // Your database username
$password = "password"; // Your database password
$dbname = "database"; // Your database name

// Create a new MySQLi connection
$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = date('yy-m-d');

if (isset($_POST['submit'])) {
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
    <h2 style=" width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan profit</b></h2>
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
            <form action="exp_profit.php" method="POST">
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
            <th>Invoice</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>qty</th>
            <th>Subtotal</th>
            <th>tanggal</th>
        </tr>
        <?php 
        if (isset($_POST['submit'])) {
            // Prepare SQL query with placeholders
            $stmt = $conn->prepare("SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN ? AND ?");
            $stmt->bind_param("ss", $date1, $date2); // Bind the parameters (ss: two string parameters)
            $stmt->execute();
            $result = $stmt->get_result();
            
            $no = 1;
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $row['invoice']; ?></td>
                    <td><?= $row['nama_produk']; ?></td>
                    <td><?= number_format($row['harga']); ?></td>
                    <td><?= $row['qty']; ?></td>
                    <td><?= number_format($row['harga'] * $row['qty']); ?></td>
                    <td><?= $row['tanggal']; ?></td>
                </tr>
                <?php 
                $total += $row['harga'] * $row['qty'];
                $no++;
            }

            ?>
            <tr>
                <td colspan="7" class="text-right"><b>Total Pendapatan Kotor = <?= number_format($total); ?></b></td>
            </tr>
        </table>
        <hr>
        <h4><b>Pemotongan dengan Biaya Bahan Baku</b></h4>
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Nama Bahan Baku</th>
                <th>Harga</th>
                <th>Kebutuhan</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            // Re-run the query to get the data again (can be optimized with a single query if needed)
            $stmt2 = $conn->prepare("SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN ? AND ?");
            $stmt2->bind_param("ss", $date1, $date2); // Bind the parameters
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            $no1 = 1;
            $totalb = 0;
            while ($row = $result2->fetch_assoc()) {
                $kd = $row['kode_produk'];
                // Get the materials for each product
                $stmt3 = $conn->prepare("SELECT b.kebutuhan as kebutuhan, i.nama as nama, i.harga as harga FROM bom_produk b JOIN inventory i ON b.kode_bk = i.kode_bk WHERE b.kode_produk = ?");
                $stmt3->bind_param("s", $kd); // Bind the parameter
                $stmt3->execute();
                $result3 = $stmt3->get_result();

                while ($row1 = $result3->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $no1; ?></td>
                        <td><?= $row1['nama']; ?></td>
                        <td><?= number_format($row1['harga']); ?></td>
                        <td><?= $row1['kebutuhan']; ?></td>
                        <td><?= number_format($row1['harga'] * $row1['kebutuhan']); ?></td>
                    </tr>
                    <?php 
                    $totalb += $row1['harga'] * $row1['kebutuhan'];
                    $no1++;
                }
            }
            ?>
            <tr>
                <td colspan="7" class="text-right"><b>Total Biaya Bahan Baku = <?= number_format($totalb); ?></b></td>
            </tr>
            <tr>
                <td colspan="7" class="text-right bg-success" style="color: green;"><b>TOTAL PENDAPATAN BERSIH = <?= number_format($total - $totalb); ?></b></td>
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
