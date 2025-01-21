<?php 
include 'header.php';

// Membuat koneksi baru menggunakan MySQLi OOP
$host = 'localhost'; // Ganti dengan host database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'dbpw192_18410100054'; // Ganti dengan nama database Anda

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan kode produk dari parameter GET
$kode_produk = $_GET['kode'];

// Mengambil data produk berdasarkan kode_produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk = ?");
$stmt->bind_param("s", $kode_produk); // Mengikat parameter
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Produk</b></h2>

    <form action="proses/edit_produk.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputFile"><img src=".5f0fcade66ae6.jpg/<?= $data['image']; ?>" width="100"></label>
            <input type="file" id="exampleInputFile" name="files">
            <p class="help-block">Pilih Gambar untuk Produk</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Kode Produk</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" disabled value="<?= $data['kode_produk']; ?>">
                    <input type="hidden" name="kode" class="form-control" id="exampleInputEmail1" value="<?= $data['kode_produk']; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama Produk</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" name="nama" value="<?= $data['nama']; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Harga</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" placeholder="masukkan Harga" name="harga" value="<?= $data['harga']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="exampleInputPassword1">Deskripsi</label>
            <textarea name="desk" class="form-control"><?= $data['deskripsi']; ?></textarea>
        </div>

        <hr>
        <h3 style="width: 100%; border-bottom: 4px solid gray">BOM Produk</h3>

        <div class="row">
            <div class="col-md-6">
                <h4>Daftar Material</h4>
                <table class="table table-striped ">
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
                // Mengambil data BOM untuk produk
                $stmt3 = $conn->prepare("SELECT * FROM bom_produk WHERE kode_produk = ?");
                $stmt3->bind_param("s", $kode_produk); // Mengikat parameter
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                $jml = $result3->num_rows;
                $no3 = 1;
                while ($no3 <= $jml) {
                    $row3 = $result3->fetch_assoc();
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Kode Material</label>
                                <input type="text" name="material[]" class="form-control" placeholder="Masukkan Kode Material" value="<?= $row3['kode_bk']; ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Kebutuhan Material</label>
                                <input type="text" class="form-control" placeholder="Contoh : 250 atau 0.2" name="keb[]" value="<?= $row3['kebutuhan']; ?>" required>
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
                <button type="submit" class="btn btn-warning btn-block"><i class="glyphicon glyphicon-edit"></i> Edit</button>
            </div>    
            <div class="col-md-6">
                <a href="m_produk.php" class="btn btn-danger btn-block">Cancel</a>
            </div>
        </div>

        <br>

    </div>
</form>

</div>

<br>
<br>
<br>

<?php 
include 'footer.php';
?>
