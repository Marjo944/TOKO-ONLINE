<?php 
include 'header.php';
require 'config.php'; // Assuming this file contains your DB connection details

// Database connection using new mysqli
try {
    $conn = new mysqli("localhost", "root", "", "dbbangunan");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Generate kode material
$kode_query = "SELECT kode_produk FROM produk ORDER BY kode_produk DESC LIMIT 1";
$kode_result = $conn->query($kode_query);
$data = $kode_result->fetch_assoc();
$num = substr($data['kode_produk'], 1, 4);
$add = (int) $num + 1;
if (strlen($add) == 1) {
    $format = "P000" . $add;
} elseif (strlen($add) == 2) {
    $format = "P00" . $add;
} elseif (strlen($add) == 3) {
    $format = "P0" . $add;
} else {
    $format = "P" . $add;
}
?>

<div class="container">
    <h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Tambah Produk</b></h2>

    <form action="proses/tm_produk.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="exampleInputFile">Pilih Gambar </label>
            <input type="file" id="exampleInputFile" name="files">
            <p class="help-block">Pilih Gambar untuk Produk</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Kode Produk</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" disabled value="<?= $format; ?>">
                    <input type="hidden" name="kode" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" value="<?= $format; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama Produk</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" name="nama">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Harga</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" placeholder="Contoh : 12000" name="harga">
                    <p class="help-block">Isi Harga tanpa menggunakan Titik(.) atau Koma (,)</p>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="exampleInputPassword1">Deskripsi</label>
            <textarea name="desk" class="form-control"></textarea>
        </div>
        
        <hr>
        <h3 style=" width: 100%; border-bottom: 4px solid gray">BOM Produk</h3>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h4>Daftar Material yang ada di Gudang/Inventory</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kode Material</th>
                            <th scope="col">Nama Material</th>
                        </tr>
                    </thead>
                    <?php 
                    $result2 = $conn->query("SELECT * FROM inventory ORDER BY kode_bk ASC");
                    $no2 = 1;
                    while ($row2 = $result2->fetch_assoc()) {
                        ?>
                        <tbody>
                            <tr>
                                <th scope="row"><?= $no2;  ?></th>
                                <td><?= $row2['kode_bk']; ?></td>
                                <td><?= $row2['nama']; ?></td>
                            </tr>
                        </tbody>
                        <?php 
                        $no2++;
                    }
                    ?>
                </table>
            </div>

            <div class="col-md-6">
                <h4>Pilih material yang hanya dibutuhkan untuk produk</h4>
                <div class="bg-danger" style="padding: 5px;">
                    <p style="color: red; font-weight: bold;">NB. Form dibawah tidak harus diisi semua</p>
                    <p style="color: red; font-weight: bold;">Kode Material tidak boleh sama</p>
                </div>
                <br>

                <?php 
                $result3 = $conn->query("SELECT * FROM inventory");
                $jml = $result3->num_rows;
                $no3 = 1;
                while ($no3 <= $jml) {
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Kode Material</label>
                                <input type="text" name="material[]" class="form-control" placeholder="Masukkan Kode Material">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Kebutuhan Material</label>
                                <input type="text" class="form-control" placeholder="Contoh : 250 atau 0.2" name="keb[]">
                            </div>
                        </div>
                    </div>
                    <?php 
                    $no3++;
                }    
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="submit"  class="btn btn-success btn-block"><i class="glyphicon glyphicon-plus-sign"></i> Tambah</button>
            </div>  
            <div class="col-md-6">
                <a href="m_produk.php" class="btn btn-danger btn-block">Cancel</a>
            </div>
        </div>

        <br>

    </div>
</form>

</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
