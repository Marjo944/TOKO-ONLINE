<?php 
include '../../koneksi/koneksi.php';

// generate kode bom
$conn = new mysqli("localhost", "root", "", "dbbangunan"); // Pastikan untuk mengganti parameter dengan benar

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$kode = $conn->query("SELECT kode_bom from bom_produk order by kode_bom desc");
$data = $kode->fetch_assoc();
if ($data['kode_bom'] == null) {
    $format = "B0001";
} else {
    $num = substr($data['kode_bom'], 1, 4);
    $add = (int) $num + 1;
    if (strlen($add) == 1) {
        $format = "B000".$add;
    } else if (strlen($add) == 2) {
        $format = "B00".$add;
    } else if (strlen($add) == 3) {
        $format = "B0".$add;
    } else {
        $format = "B".$add;
    }
}

$kode = $_POST['kode'];
$nm_produk = $_POST['nama'];
$harga = $_POST['harga'];
$desk = $_POST['desk'];
$nama_gambar = $_FILES['files']['name'];
$size_gambar = $_FILES['files']['size'];
$tmp_file = $_FILES['files']['tmp_name'];
$eror = $_FILES['files']['error'];
$type = $_FILES['files']['type'];

// BOM
$kd_material = $_POST['material'];
$keb = $_POST['keb'];

if ($eror === 4) {
    echo "
    <script>
    alert('TIDAK ADA GAMBAR YANG DIPILIH');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

$ekstensiGambar = array('jpg', 'jpeg', 'png');
$ekstensiGambarValid = explode(".", $nama_gambar);
$ekstensiGambarValid = strtolower(end($ekstensiGambarValid));

if (!in_array($ekstensiGambarValid, $ekstensiGambar)) {
    echo "
    <script>
    alert('EKSTENSI GAMBAR HARUS JPG, JPEG, PNG');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

if ($size_gambar > 1000000) {
    echo "
    <script>
    alert('UKURAN GAMBAR TERLALU BESAR');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

$namaGambarBaru = uniqid();
$namaGambarBaru .= ".";
$namaGambarBaru .= $ekstensiGambarValid;

if (move_uploaded_file($tmp_file, "../../image/produk/" . $namaGambarBaru)) {

    $stmt = $conn->prepare("INSERT INTO produk (kode_produk, nama, image, deskripsi, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kode, $nm_produk, $namaGambarBaru, $desk, $harga);
    $stmt->execute();

    $filter = array_filter($kd_material);
    $jml = count($filter) - 1;
    $no = 0;
    while ($no <= $jml) {

        $stmt_bom = $conn->prepare("INSERT INTO bom_produk (kode_bom, kode_bk, kode_produk, nama_produk, kebutuhan) VALUES (?, ?, ?, ?, ?)");
        $stmt_bom->bind_param("sssss", $format, $kd_material[$no], $kode, $nm_produk, $keb[$no]);
        $stmt_bom->execute();

        $no++;
    }

    if ($stmt->affected_rows > 0) {
        echo "
        <script>
        alert('PRODUK BERHASIL DITAMBAHKAN');
        window.location = '../m_produk.php';
        </script>
        ";
    }

    $stmt->close();
    $stmt_bom->close();
}

$conn->close();
?>
