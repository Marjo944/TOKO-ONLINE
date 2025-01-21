<?php 
include 'header.php';

// Pastikan 'produk' ada di URL sebelum melanjutkan
if (isset($_GET['produk'])) {
    $kode = $_GET['produk'];

    // Menggunakan prepared statements untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk = ?");
    $stmt->bind_param("s", $kode); // Mengikat parameter produk (string)
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    // Jika parameter 'produk' tidak ada, bisa menampilkan error atau redirect
    echo "Produk tidak ditemukan.";
    exit;
}
?>
<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid #ff8680"><b>Detail produk</b></h2>

    <div class="row">
        <div class="col-md-4">
            <div class="thumbnail">
                <!-- Menggunakan htmlspecialchars untuk mencegah XSS -->
                <img src="image/produk/<?= htmlspecialchars($row['image']); ?>" width="400">
            </div>
        </div>

        <div class="col-md-8">
            <form action="proses/add.php" method="GET">
                <!-- Menambahkan parameter hidden -->
                <input type="hidden" name="kd_cs" value="<?= htmlspecialchars($kode_cs); ?>">
                <input type="hidden" name="produk" value="<?= htmlspecialchars($kode); ?>">
                <input type="hidden" name="hal"  value="2">

                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><b>Nama</b></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                        </tr>
                        <tr>
                            <td><b>Harga</b></td>
                            <td>Rp.<?= number_format($row['harga']); ?></td>
                        </tr>
                        <tr>
                            <td><b>Deskripsi</b></td>
                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                        </tr>
                        <tr>
                            <td><b>Jumlah</b></td>
                            <td><input class="form-control" type="number" min="1" name="jml" value="1" style="width: 155px;"></td>
                        </tr>
                    </tbody>
                </table>
                
                <?php 
                // Cek apakah user sudah login atau belum
                if (isset($_SESSION['user'])) {
                    ?>
                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</button>
                    <?php 
                } else {
                    ?>
                    <a href="keranjang.php" class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Tambahkan ke Keranjang</a>
                    <?php 
                }
                ?>
                <a href="index.php" class="btn btn-warning"> Kembali Belanja</a>
            </form>
        </div>
    </div>    
</div>  
<br><br>

<?php 
include 'footer.php';
?>
