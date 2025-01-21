<?php 
include 'header.php';
$kode = $_GET['kode'];

// Menyiapkan dan mengeksekusi query untuk mengambil data barang berdasarkan kode_bk
$stmt = $conn->prepare("SELECT * FROM inventory WHERE kode_bk = ?");
$stmt->bind_param("s", $kode);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Inventory</b></h2>

    <!-- Menampilkan pesan sukses atau gagal -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success">Inventory berhasil diperbarui!</div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <div class="alert alert-danger">Terjadi kesalahan saat memperbarui inventory.</div>
    <?php endif; ?>

    <!-- Form Edit Inventory -->
    <form action="proses/edit_inv.php" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode_bk">Kode Material</label>
                    <input type="text" class="form-control" id="kode_bk" disabled value="<?= $row['kode_bk']; ?>">
                    <input type="hidden" name="kd_material" value="<?= $row['kode_bk']; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">Nama Material</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $row['nama']; ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $row['qty']; ?>" required min="0">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?= $row['satuan']; ?>" required>
                    <p class="help-block">Masukkan satuan material (contoh: Kg atau gram)</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?= $row['harga']; ?>" required min="0">
                    <p class="help-block">Harga per satuan </p>
                </div>
            </div>
        </div>

        <!-- Tombol Submit dan Cancel -->
        <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i> Edit</button>
        <a href="inventory.php" class="btn btn-danger">Cancel</a>
    </form>
</div>

<!-- Pemisah untuk jarak tambahan -->
<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
