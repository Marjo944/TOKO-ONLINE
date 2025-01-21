<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembatalan</title>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Set header untuk ekspor Excel
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Pembatalan_" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Koneksi ke database menggunakan MySQLi OOP
        $conn = new mysqli("localhost", "root", "", "dbbangunan");

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Mendapatkan parameter tanggal dari form
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];

        // Query untuk mengambil data transaksi yang dibatalkan (tolak = 1) dalam rentang tanggal
        $sql = "SELECT * FROM produksi WHERE tolak = 1 AND tanggal BETWEEN '$date1' AND '$date2'";
        $result = $conn->query($sql);

        // Variabel untuk nomor urut dan total
        $no = 1;
        $total = 0;

        // Loop melalui hasil query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $row['nama_produk']; ?></td>
                <td><?= $row['tanggal']; ?></td>
                <td><?= $row['qty']; ?></td>
            </tr>
        <?php
            $total += $row['qty']; // Menambahkan total qty
            $no++;
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><b>Jumlah dibatalkan:</b></td>
                <td><b><?= $total; ?></b></td>
            </tr>
        </tfoot>
    </table>

    <?php
    // Tutup koneksi setelah selesai
    $conn->close();
    ?>
</body>
</html>
