<?php 
include 'header.php';
?>
<!-- IMAGE -->
<div class="container-fluid" style="margin: 0;padding: 0;">
    <div class="image" style="margin-top: -21px">
        <img src="image/home/1.jpg" style="width: 100%; height: 650px;">
    </div>
</div>
<br><br>

<!-- PRODUK TERBARU -->
<div class="container">

    <h4 class="text-center" style="font-family: arial; padding-top: 10px; padding-bottom: 10px; font-style: italic; line-height: 29px; border-top: 2px solid #ff8d87; border-bottom: 2px solid #ff8d87;">
    Toko Bangunan UD Marjo Jaya adalah salah satu toko bahan bangunan terpercaya   kami menyediakan berbagai produk bahan bangunan berkualitas tinggi dan harga yang terjangkau untuk semua kalangan. Kami berkomitmen untuk mendukung setiap proyek pembangunan, baik rumah tinggal maupun proyek komersial, dengan layanan terbaik dan produk yang selalu terjamin keandalannya.
    </h4>

    <h2 style="width: 100%; border-bottom: 4px solid #ff8680; margin-top: 80px;"><b>Produk Kami</b></h2>

    <div class="row">
        <?php 
        // Query produk menggunakan prepared statement untuk mencegah SQL injection
        $stmt = $conn->prepare("SELECT * FROM produk");
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img src="image/produk/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" />
                    <div class="caption">
                        <h3><?= htmlspecialchars($row['nama']); ?></h3>
                        <h4>Rp.<?= number_format($row['harga'], 0, ',', '.'); ?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="detail_produk.php?produk=<?= urlencode($row['kode_produk']); ?>" class="btn btn-warning btn-block">Detail</a> 
                            </div>
                            <?php if (isset($_SESSION['kd_cs'])) { ?>
                                <div class="col-md-6">
                                    <a href="proses/add.php?produk=<?= urlencode($row['kode_produk']); ?>&kd_cs=<?= urlencode($kode_cs); ?>&hal=1" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-6">
                                    <a href="keranjang.php" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
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
<br><br><br><br>

<?php 
include 'footer.php';
?>
