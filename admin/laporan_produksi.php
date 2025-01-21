<?php 
include 'header.php';

// Membuat koneksi MySQLi baru (mengasumsikan variabel koneksi sudah diset)
$servername = "localhost"; // Contoh host
$username = "username"; // Nama pengguna database
$password = "password"; // Kata sandi database
$dbname = "database"; // Nama database

$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$date = date('yy-m-d');

// Menetapkan nilai default untuk date1 dan date2
$date1 = $date;
$date2 = $date;

if (isset($_POST['submit'])) {
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
}
?>
<style type="text/css">
    @media print {
        .print {
            display: none;
        }
    }
</style>
<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray; padding-bottom: 5px;"><b>Laporan Produksi</b></h2>
    <div class="row print">
        <div class="col-md-9">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <table>
                    <tr>
                        <td><input type="date" name="date1" class="form-control" value="<?= htmlspecialchars($date1); ?>"></td>
                        <td>&nbsp; - &nbsp;</td>
                        <td><input type="date" name="date2" class="form-control" value="<?= htmlspecialchars($date2); ?>"></td>
                        <td> &nbsp;</td>
                        <td><input type="submit" name="submit" class="btn btn-primary" value="Tampilkan"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-md-3">
            <form action="exp_produksi.php" method="POST">
                <table>
                    <tr>
                        <td><input type="hidden" name="date1" class="form-control" value="<?= htmlspecialchars($date1); ?>"></td>
                        <td><input type="hidden" name="date2" class="form-control" value="<?= htmlspecialchars($date2); ?>"></td>
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
            <th>Tanggal</th>
            <th>Total Produksi</th>
        </tr>
        <?php 
        if (isset($_POST['submit'])) {
            // Menggunakan prepared statement untuk mencegah SQL injection
            $stmt = $conn->prepare("SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN ? AND ?");
            $stmt->bind_param("ss", $date1, $date2); // "ss" artinya dua parameter string
            $stmt->execute();
            $result = $stmt->get_result();
            
            $no = 1;
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal']); ?></td>
                    <td><?= htmlspecialchars($row['qty']); ?></td>
                </tr>
                <?php 
                $total += $row['qty'];
                $no++;
            }

            ?>
            <tr>
                <td colspan="4" class="text-right"><b>Total Jumlah Produksi = <?= $total; ?></b></td>
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
