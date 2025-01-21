<?php 
include 'header.php';

// Mendapatkan jumlah pesanan yang ditandai untuk pengecekan kekurangan material
$sortage = $conn->query("SELECT * FROM produksi WHERE cek = '1'");
$cek_sor = $sortage->num_rows;
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Daftar Pesanan</b></h2>
    <br>
    <h5 class="bg-success" style="padding: 7px; width: 710px; font-weight: bold;">
        <marquee>Lakukan Reload Setiap Masuk Halaman ini, untuk menghindari terjadinya kesalahan data dan informasi</marquee>
    </h5>
    <a href="produksi.php" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reload</a>
    <br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Invoice</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            // Mengambil data pesanan unik dengan query yang aman
            $stmt = $conn->prepare("SELECT DISTINCT invoice, kode_customer, status, kode_produk, qty, terima, tolak, cek FROM produksi GROUP BY invoice");
            $stmt->execute();
            $result = $stmt->get_result();
            $no = 1;
            $nama_material = []; // Array untuk menampung material yang kekurangan

            while($row = $result->fetch_assoc()){
                $kodep = $row['kode_produk'];
                $inv = $row['invoice'];
                ?>

                <tr>
                    <td><?= $no; ?></td>
                    <td><?= htmlspecialchars($row['invoice']); ?></td>
                    <td><?= htmlspecialchars($row['kode_customer']); ?></td>
                    <?php if($row['terima'] == 1){ ?>
                        <td style="color: green; font-weight: bold;">Pesanan Diterima (Siap Kirim)</td>
                    <?php } else if($row['tolak'] == 1){ ?>
                        <td style="color: red; font-weight: bold;">Pesanan Ditolak</td>
                    <?php } else { ?>
                        <td style="color: orange; font-weight: bold;"><?= htmlspecialchars($row['status']); ?></td>
                    <?php } ?>

                    <?php
                    // Mengecek kekurangan material dan memperbarui jika diperlukan
                    $t_bom = $conn->prepare("SELECT * FROM bom_produk WHERE kode_produk = ?");
                    $t_bom->bind_param('s', $kodep);
                    $t_bom->execute();
                    $result_bom = $t_bom->get_result();

                    while($row1 = $result_bom->fetch_assoc()){
                        $kodebk = $row1['kode_bk'];

                        // Query untuk inventaris material yang dibutuhkan
                        $inventory = $conn->prepare("SELECT * FROM inventory WHERE kode_bk = ?");
                        $inventory->bind_param('s', $kodebk);
                        $inventory->execute();
                        $r_inv = $inventory->get_result()->fetch_assoc();

                        if ($r_inv) { // Pastikan $r_inv tidak null
                            $kebutuhan = $row1['kebutuhan'];    
                            $qtyorder = $row['qty'];
                            $inventory_qty = $r_inv['qty'];

                            $bom = ($kebutuhan * $qtyorder);
                            $hasil = $inventory_qty - $bom;

                            if($hasil < 0 && $row['tolak'] == 0){
                                $nama_material[] = $r_inv['nama'];
                                // Memperbarui produksi untuk menandai pengecekan kekurangan material
                                $update_stmt = $conn->prepare("UPDATE produksi SET cek = '1' WHERE invoice = ?");
                                $update_stmt->bind_param('s', $inv);
                                $update_stmt->execute();
                            }
                        } else {
                            // Menangani kasus jika material tidak ditemukan di inventaris
                            echo "Material tidak ditemukan di inventaris untuk kode BK: " . htmlspecialchars($kodebk);
                        }
                    }
                    ?>
                    </td>
                    <td>2020/26-01</td>
                    <td>
                        <?php if($row['tolak'] == 0 && $row['cek'] == 1 && $row['terima'] == 0){ ?>
                            <a href="inventory.php?cek=0" id="rq" class="btn btn-warning"><i class="glyphicon glyphicon-warning-sign"></i> Request Material Shortage</a> 
                            <a href="proses/tolak.php?inv=<?= urlencode($row['invoice']); ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
                        <?php } else if($row['terima'] == 0 && $row['cek'] == 0){ ?>
                            <a href="proses/terima.php?inv=<?= urlencode($row['invoice']); ?>&kdp=<?= urlencode($row['kode_produk']); ?>" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Terima</a> 
                            <a href="proses/tolak.php?inv=<?= urlencode($row['invoice']); ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
                        <?php } ?>

                        <a href="detailorder.php?inv=<?= urlencode($row['invoice']); ?>&cs=<?= urlencode($row['kode_customer']); ?>" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan</a>
                    </td>
                </tr>
                <?php
                $no++; 
            }
            ?>

        </tbody>
    </table>

    <?php 
    if($cek_sor > 0){
    ?>
    <br><br>
    <div class="row">
        <div class="col-md-4 bg-danger" style="padding:10px;">
            <h4>Kekurangan Material</h4>
            <h5 style="color: red; font-weight: bold;">Silahkan Tambah Stok Material dibawah ini:</h5>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Material</th>
                </tr>
    <?php 
    // Menghapus nama material yang duplikat
    $arr = array_values(array_unique($nama_material));
    foreach ($arr as $key => $material) { 
    ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= htmlspecialchars($material); ?></td>
                </tr>
    <?php } ?>
            </table>
        </div>
    </div>
    <?php 
    }
    ?>

</div>

<?php 
include 'footer.php';
?>
