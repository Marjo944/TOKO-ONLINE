<?php 
include 'header.php';

if (isset($_POST['submit1'])) {
    $id_keranjang = $_POST['id'];
    $qty = $_POST['qty'];

    // Validasi input untuk qty (harus positif dan lebih besar dari 0)
    if ($qty > 0) {
        $stmt = $conn->prepare("UPDATE keranjang SET qty = ? WHERE id_keranjang = ?");
        $stmt->bind_param('ii', $qty, $id_keranjang);

        if ($stmt->execute()) {
            echo "
            <script>
            alert('KERANJANG BERHASIL DIPERBARUI');
            window.location = 'keranjang.php';
            </script>
            ";
        } else {
            echo "<script>alert('Gagal memperbarui keranjang!');</script>";
        }
    }
} elseif (isset($_GET['del'])) {
    $id_keranjang = $_GET['id'];

    // Validasi ID Keranjang
    if (is_numeric($id_keranjang)) {
        $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_keranjang = ?");
        $stmt->bind_param('i', $id_keranjang);

        if ($stmt->execute()) {
            echo "
            <script>
            alert('1 PRODUK DIHAPUS');
            window.location = 'keranjang.php';
            </script>
            ";
        }
    }
}
?>

<div class="container" style="padding-bottom: 300px;">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Keranjang</b></h2>
    <table class="table table-striped">
        <?php 
        if (isset($_SESSION['user'])) {
            $kode_cs = $_SESSION['kd_cs'];
            
            // CEK JUMLAH KERANJANG
            $stmt = $conn->prepare("SELECT * FROM keranjang WHERE kode_customer = ?");
            $stmt->bind_param('s', $kode_cs);
            $stmt->execute();
            $result = $stmt->get_result();
            $jml = mysqli_num_rows($result);

            if ($jml > 0) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Image</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Qty</th>
                        <th scope="col">SubTotal</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $stmt = $conn->prepare("SELECT k.id_keranjang, k.kode_produk, k.nama_produk, k.qty, p.image, p.harga FROM keranjang k JOIN produk p ON k.kode_produk = p.kode_produk WHERE k.kode_customer = ?");
                $stmt->bind_param('s', $kode_cs);
                $stmt->execute();
                $result = $stmt->get_result();
                $no = 1;
                $hasil = 0;

                while ($row = $result->fetch_assoc()) {
                ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id_keranjang']; ?>">
                        <tr>
                            <th scope="row"><?= $no; ?></th>
                            <td><img src="image/produk/<?= htmlspecialchars($row['image']); ?>" width="100"></td>
                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                            <td>Rp.<?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><input type="number" name="qty" class="form-control" style="text-align: center;" value="<?= $row['qty']; ?>" min="1"></td>
                            <td>Rp.<?= number_format($row['harga'] * $row['qty'], 0, ',', '.'); ?></td>
                            <td>
                                <button type="submit" name="submit1" class="btn btn-warning">Update</button> |
                                <a href="keranjang.php?del=1&id=<?= $row['id_keranjang']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin dihapus?')">Delete</a>
                            </td>
                        </tr>
                    </form>
                <?php 
                    $hasil += $row['harga'] * $row['qty'];
                    $no++;
                }
                ?>
                    <tr>
                        <td colspan="7" style="text-align: right; font-weight: bold;">Grand Total = Rp.<?= number_format($hasil, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: right; font-weight: bold;">
                            <a href="index.php" class="btn btn-success">Lanjutkan Belanja</a>
                            <a href="checkout.php?kode_cs=<?= urlencode($kode_cs); ?>" class="btn btn-primary">Checkout</a>
                        </td>
                    </tr>
                <?php 
            } else {
                echo "
                <tr>
                    <td colspan='7' class='text-center bg-warning'><h5><b>KERANJANG BELANJA ANDA KOSONG </b></h5></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center bg-danger'><h5><b>SILAHKAN LOGIN TERLEBIH DAHULU SEBELUM BERBELANJA</b></h5></td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php 
include 'footer.php';
?>
