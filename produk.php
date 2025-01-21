<?php 
	include 'header.php';
?>

<!-- PRODUK TERBARU -->
<div class="container">
	<h2 style=" width: 100%; border-bottom: 4px solid #ff8680"><b>Produk Kami</b></h2>

	<div class="row">
		<?php 
		// Query untuk mengambil semua produk dari database
		$result = mysqli_query($conn, "SELECT * FROM produk");
		
		// Loop untuk menampilkan setiap produk dalam result set
		while ($row = mysqli_fetch_assoc($result)) {
			?>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<!-- Menampilkan gambar produk dengan menggunakan htmlspecialchars untuk mencegah XSS -->
					<img src="image/produk/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" class="img-responsive">
					<div class="caption">
						<h3><?= htmlspecialchars($row['nama']); ?></h3>
						<h4>Rp.<?= number_format($row['harga']); ?></h4>
						<div class="row">
							<div class="col-md-6">
								<!-- Tombol untuk menuju halaman detail produk -->
								<a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-warning btn-block">Detail</a>
							</div>
							
							<?php if (isset($_SESSION['kd_cs'])) { ?>
								<!-- Jika pengguna sudah login, tampilkan tombol 'Tambah ke Keranjang' -->
								<div class="col-md-6">
									<a href="proses/add.php?produk=<?= $row['kode_produk']; ?>&kd_cs=<?= $kode_cs; ?>&hal=1" class="btn btn-success btn-block" role="button">
										<i class="glyphicon glyphicon-shopping-cart"></i> Tambah
									</a>
								</div>
							<?php } else { ?>
								<!-- Jika pengguna belum login, tampilkan tombol yang mengarahkan ke halaman keranjang -->
								<div class="col-md-6">
									<a href="keranjang.php" class="btn btn-success btn-block" role="button">
										<i class="glyphicon glyphicon-shopping-cart"></i> Tambah
									</a>
								</div>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
	</div>

</div>

<?php 
	include 'footer.php';
?>
