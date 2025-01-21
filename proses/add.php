<?php 
include '../koneksi/koneksi.php';

$hal = $_GET['hal'];
$kode_cs = $_GET['kd_cs'];
$kode_produk = $_GET['produk'];
$qty = isset($_GET['jml']) ? (int)$_GET['jml'] : 1; // Pastikan $qty selalu terdefinisi dan dalam bentuk integer.

try {
    // Ambil detail produk berdasarkan kode produk
    $stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk = ?");
    $stmt->bind_param('s', $kode_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $nama_produk = $row['nama'];
    $kd = $row['kode_produk'];
    $harga = $row['harga'];

    // Cek apakah ini untuk menambahkan produk pertama kali atau memperbarui keranjang
    if ($hal == 1) {
        // Cek apakah produk sudah ada di keranjang
        $stmt = $conn->prepare("SELECT * FROM keranjang WHERE kode_produk = ? AND kode_customer = ?");
        $stmt->bind_param('ss', $kode_produk, $kode_cs);
        $stmt->execute();
        $cek = $stmt->get_result();
        $jml = $cek->num_rows;
        
        if ($jml > 0) {
            // Jika produk sudah ada di keranjang, update jumlahnya
            $row1 = $cek->fetch_assoc();
            $new_qty = $row1['qty'] + 1;
            
            $update_stmt = $conn->prepare("UPDATE keranjang SET qty = ? WHERE kode_produk = ? AND kode_customer = ?");
            $update_stmt->bind_param('iss', $new_qty, $kode_produk, $kode_cs);
            if ($update_stmt->execute()) {
                echo "
                <script>
                alert('BERHASIL DITAMBAHKAN KE KERANJANG');
                window.location = '../keranjang.php';
                </script>
                ";
                exit;
            }
        } else {
            // Jika produk belum ada di keranjang, tambahkan produk baru
            $insert_stmt = $conn->prepare("INSERT INTO keranjang (kode_customer, kode_produk, nama_produk, qty, harga) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param('ssssi', $kode_cs, $kd, $nama_produk, $qty, $harga);
            if ($insert_stmt->execute()) {
                echo "
                <script>
                alert('BERHASIL DITAMBAHKAN KE KERANJANG');
                window.location = '../keranjang.php';
                </script>
                ";
                exit;
            }
        }

    } else {
        // Jika ini adalah aksi untuk memperbarui keranjang
        $stmt = $conn->prepare("SELECT * FROM keranjang WHERE kode_produk = ? AND kode_customer = ?");
        $stmt->bind_param('ss', $kode_produk, $kode_cs);
        $stmt->execute();
        $cek = $stmt->get_result();
        $jml = $cek->num_rows;
        
        if ($jml > 0) {
            // Produk sudah ada di keranjang, update jumlahnya
            $row1 = $cek->fetch_assoc();
            $new_qty = $row1['qty'] + $qty;
            
            $update_stmt = $conn->prepare("UPDATE keranjang SET qty = ? WHERE kode_produk = ? AND kode_customer = ?");
            $update_stmt->bind_param('iss', $new_qty, $kode_produk, $kode_cs);
            if ($update_stmt->execute()) {
                echo "
                <script>
                alert('BERHASIL DITAMBAHKAN KE KERANJANG');
                window.location = '../detail_produk.php?produk=".$kode_produk."';
                </script>
                ";
                exit;
            }
        } else {
            // Jika produk belum ada di keranjang, tambahkan produk baru
            $insert_stmt = $conn->prepare("INSERT INTO keranjang (kode_customer, kode_produk, nama_produk, qty, harga) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param('ssssi', $kode_cs, $kd, $nama_produk, $qty, $harga);
            if ($insert_stmt->execute()) {
                echo "
                <script>
                alert('BERHASIL DITAMBAHKAN KE KERANJANG');
                window.location = '../detail_produk.php?produk=".$kode_produk."';
                </script>
                ";
                exit;
            }
        }
    }
} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>
