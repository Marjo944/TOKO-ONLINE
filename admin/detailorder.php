<?php 
include 'header.php';
$invoices = $_GET['inv'];

// Menggunakan koneksi new mysqli
$conn = new mysqli("localhost", "root", "", "dbbangunan"); // Gantilah dengan data koneksi yang sesuai

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data order berdasarkan invoice
$d_order = $conn->query("SELECT * FROM produksi WHERE invoice = '$invoices'");
$t_order = $d_order->fetch_assoc();

// Mengambil data untuk material shortage
$sortage = $conn->query("SELECT * FROM produksi WHERE cek = '1'");
$cek_sor = $sortage->num_rows;

// Mengambil data customer
$cs = $conn->query("SELECT * FROM customer WHERE kode_customer = '".$t_order['kode_customer']."'");
$t_cs = $cs->fetch_assoc();
?>

<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Daftar Pesanan</b></h2>
    <br>
    <h5 class="bg-success" style="padding: 7px; width: 710px; font-weight: bold;">
        <marquee>Lakukan Reload Setiap Masuk Halaman ini, untuk menghindari terjadinya kesalahan data dan informasi</marquee>
    </h5>
    <a href="produksi.php" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reload</a>
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
            $result = $conn->query("SELECT DISTINCT invoice, kode_customer, status, kode_produk, qty, terima, tolak, cek FROM produksi GROUP BY invoice");
            $no = 1;
            $nama_material = [];  // Initialize array to store material shortages
            while($row = $result->fetch_assoc()) {
                $kodep = $row['kode_produk'];
                $inv = $row['invoice'];
            ?>

            <tr>
                <td><?= $no; ?></td>
                <td><?= $row['invoice']; ?></td>
                <td><?= $row['kode_customer']; ?></td>
                <?php if($row['terima'] == 1) { ?>
                    <td style="color: green;font-weight: bold;">Pesanan Diterima (Siap Kirim)</td>
                <?php } else if($row['tolak'] == 1) { ?>
                    <td style="color: red;font-weight: bold;">Pesanan Ditolak</td>
                <?php } else if($row['terima'] == 0 && $row['tolak'] == 0) { ?>
                    <td style="color: orange;font-weight: bold;"><?= $row['status']; ?></td>
                <?php }

                // Cek BOM untuk kekurangan material
                $t_bom = $conn->query("SELECT * FROM bom_produk WHERE kode_produk = '$kodep'");
                while($row1 = $t_bom->fetch_assoc()) {
                    $kodebk = $row1['kode_bk'];
                    $inventory = $conn->query("SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
                    $r_inv = $inventory->fetch_assoc();

                    $kebutuhan = $row1['kebutuhan'];
                    $qtyorder = $row['qty'];
                    $inventory_qty = $r_inv['qty'];

                    $bom = $kebutuhan * $qtyorder;
                    $hasil = $inventory_qty - $bom;

                    if ($hasil < 0 && $row['tolak'] == 0) {
                        $conn->query("UPDATE produksi SET cek = '1' WHERE invoice = '$inv'");
                        $nama_material[] = $r_inv['nama'];
                    }
                }
                ?>
                <td>2020/26-01</td>
                <td>
                    <?php if($row['tolak'] == 0 && $row['cek'] == 1 && $row['terima'] == 0) { ?>
                        <a href="inventory.php?cek=0" id="rq" class="btn btn-warning"><i class="glyphicon glyphicon-warning-sign"></i> Request Material Shortage</a>
                        <a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a>
                    <?php } else if($row['terima'] == 0 && $row['cek'] == 0) { ?>
                        <a href="proses/terima.php?inv=<?= $row['invoice']; ?>&kdp=<?= $row['kode_produk']; ?>" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Terima</a>
                        <a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a>
                    <?php } ?>
                    <a href="detailorder.php?inv=<?= $row['invoice']; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan</a>
                </td>
            </tr>
            <?php
            $no++;
            }
            ?>
        </tbody>
    </table>

    <!-- Modal -->
    <button type="hidden" data-toggle="modal" data-target="#myModal" id="btn" style="background-color: #fff; border: #fff;"></button>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="m_produk.php" class="btn btn-default close"></a>
                    <h4 class="modal-title" id="myModalLabel">#<?= $t_order['invoice']; ?></h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Invoice</td>
                            <td><?= $t_order['invoice']; ?></td>
                        </tr>
                        <tr>
                            <td>Kode Customer</td>
                            <td><?= $t_order['kode_customer']; ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td><?= $t_cs['nama']; ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td><?= $t_order['alamat'] . ", " . $t_order['kota'] . " " . $t_order['provinsi'] . ", " . $t_order['kode_pos']; ?></td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td><?= $t_cs['telp']; ?></td>
                        </tr>
                    </table>

                    <hr>
                    <h4>List Order</h4>
                    <table class="table table-striped">
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                        <?php
                        $order = $conn->query("SELECT * FROM produksi WHERE invoice = '$invoices'");
                        $no = 1;
                        $grand = 0;
                        while ($list = $order->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $list['kode_produk']; ?></td>
                                <td><?= $list['nama_produk']; ?></td>
                                <td><?= number_format($list['harga'], 0, ".", "."); ?></td>
                                <td><?= $list['qty']; ?></td>
                                <td><?= number_format($list['harga'] * $list['qty'], 0, ".", "."); ?></td>
                            </tr>
                        <?php
                            $sub = $list['harga'] * $list['qty'];
                            $grand += $sub;
                            $no++;
                        }
                        ?>
                        <tr>
                            <td colspan="6" class="text-right"><b>Grand Total = <?= number_format($grand, 0, ".", "."); ?></b></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="produksi.php" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($cek_sor > 0) { ?>
        <br>
        <br>
        <div class="row">
            <div class="col-md-4 bg-danger" style="padding:10px;">
                <h4>Kekurangan Material </h4>
                <h5 style="color: red;font-weight: bold;">Silahkan Tambah Stok Material dibawah ini : </h5>
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>Material</th>
                    </tr>
                    <?php 
                    $arr = array_values(array_unique($nama_material));
                    for ($i = 0; $i < count($arr); $i++) {
                    ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $arr[$i]; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#btn").click();
    });
</script>

<?php 
include 'footer.php';
?>
