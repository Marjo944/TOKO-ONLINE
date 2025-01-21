<?php 

include 'header.php';

// Koneksi ke database menggunakan MySQLi OOP
$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pesanan baru 
$result1 = $conn->query("SELECT DISTINCT invoice FROM produksi WHERE terima = 0 AND tolak = 0");
$jml1 = $result1->num_rows;

// Pesanan dibatalkan/ditolak
$result2 = $conn->query("SELECT DISTINCT invoice FROM produksi WHERE tolak = 1");
$jml2 = $result2->num_rows;

// Pesanan diterima
$result3 = $conn->query("SELECT DISTINCT invoice FROM produksi WHERE terima = 1");
$jml3 = $result3->num_rows;

?>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px;padding-right: 20px; padding-top: 10px;">
				<h4>PESANAN BARU</h4>
				<h4 style="font-size: 56pt;"><b><?= $jml1; ?></b></h4>
			</div>
		</div>

		<div class="col-md-4">
			<div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px;padding-right: 20px; padding-top: 10px;">
				<h4>PESANAN DIBATALKAN</h4>
				<h4 style="font-size: 56pt;"><b><?= $jml2; ?></b></h4>
			</div>
		</div>

		<div class="col-md-4">
			<div style="background-color: #dfdfdf; padding-bottom: 60px; padding-left: 20px;padding-right: 20px; padding-top: 10px;">
				<h4>PESANAN DITERIMA</h4>
				<h4 style="font-size: 56pt;"><b><?= $jml3; ?></b></h4>
			</div>
		</div>

	</div>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<br>

<?php 
include 'footer.php';
?>
