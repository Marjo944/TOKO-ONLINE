<?php 
include 'header.php';
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Master Produk</b></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Produk</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Image</th>
                <th scope="col">Harga</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Securely retrieve product data using a prepared statement
            $stmt = $conn->prepare("SELECT * FROM produk");
            $stmt->execute();
            $result = $stmt->get_result();
            $no = 1;

            while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlspecialchars($row['kode_produk']); ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><img src="../image/produk/<?= htmlspecialchars($row['image']); ?>" width="100"></td>
                    <td>Rp.<?= number_format($row['harga']); ?></td>
                    <td>
                        <a href="edit_produk.php?kode=<?= htmlspecialchars($row['kode_produk']); ?>" class="btn btn-warning">
                            <i class="glyphicon glyphicon-edit"></i> Edit
                        </a> 
                        <a href="proses/del_produk.php?kode=<?= htmlspecialchars($row['kode_produk']); ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data?')">
                            <i class="glyphicon glyphicon-trash"></i> Hapus
                        </a> 
                        <a href="bom.php?kode=<?= htmlspecialchars($row['kode_produk']); ?>" class="btn btn-primary">
                            <i class="glyphicon glyphicon-eye-open"></i> Lihat BOM
                        </a>
                    </td>
                </tr>
            <?php 
                $no++;
            }
            ?>
        </tbody>
    </table>

    <a href="tm_produk.php" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Produk</a>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
