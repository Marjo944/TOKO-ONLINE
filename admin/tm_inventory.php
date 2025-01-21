<?php 
include 'header.php';

// Generate the material code (kode_bk) dynamically
$kode = mysqli_query($conn, "SELECT kode_bk FROM inventory ORDER BY kode_bk DESC LIMIT 1");
$data = mysqli_fetch_assoc($kode);
if ($data) {
    $num = substr($data['kode_bk'], 1, 4);
    $add = (int) $num + 1;
    $format = "M" . str_pad($add, 4, "0", STR_PAD_LEFT);
} else {
    $format = "M0001";  // Default starting format if no records exist
}

?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Tambah Material</b></h2>

    <form action="proses/tambah_inv.php" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode_material">Kode Material</label>
                    <input type="text" class="form-control" id="kode_material" disabled value="<?= $format; ?>">
                    <input type="hidden" class="form-control" name="kd_material" value="<?= $format; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Material</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Material" name="nama" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" placeholder="contoh 2 atau 0.4" min="1" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" placeholder="Contoh : Kg atau gram" name="satuan" required>
                    <p class="help-block">Hanya Masukkan Satuan saja: Kg atau gram</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh : 1000" min="1" required>
                    <p class="help-block">Harga termasuk harga per kg atau per gram</p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah</button>
        <a href="inventory.php" class="btn btn-danger">Cancel</a>
    </form>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
