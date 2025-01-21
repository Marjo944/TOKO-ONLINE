<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Profit</title>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Set header untuk ekspor Excel
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Profit_" . date('Y-m-d') . ".xls");
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

        // Query untuk mengambil data produksi yang diterima (terima = 1) dalam rentang tanggal
        $sql = "SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN '$date1' AND '$date2'";
        $result = $conn->query($sql);

        // Variabel untuk nomor urut dan total pendapatan
        $no = 1;
        $total = 0;

        // Loop melalui hasil query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $row['invoice']; ?></td>
                <td><?= $row['nama_produk']; ?></td>
                <td><?= number_format($row['harga']); ?></td>
                <td><?= $row['qty']; ?></td>
                <td><?= number_format($row['harga'] * $row['qty']); ?></td>
                <td><?= $row['tanggal']; ?></td>
            </tr>
        <?php
            $total += $row['harga'] * $row['qty']; // Menambahkan total pendapatan
            $no++;
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><b>Total Pendapatan Kotor = <?= number_format($total); ?></b></td>
            </tr>
        </tfoot>
    </table>

    <h4><b>Pemotongan dengan Biaya Bahan Baku</b></h4>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan Baku</th>
                <th>Harga</th>
                <th>Kebutuhan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Query untuk mengambil data produksi yang diterima dalam rentang tanggal
        $result = $conn->query("SELECT * FROM produksi WHERE terima = 1 AND tanggal BETWEEN '$date1' AND '$date2'");
        $no1 = 1;
        $totalb = 0;

        // Loop melalui hasil query untuk bahan baku
        while ($row = $result->fetch_assoc()) {
            $kd = $row['kode_produk'];

            // Query untuk mendapatkan bahan baku terkait dengan produk
            $bahan = $conn->query("SELECT b.kebutuhan as kebutuhan, i.nama as nama, i.harga as harga 
                                   FROM bom_produk b 
                                   JOIN inventory i ON b.kode_bk = i.kode_bk 
                                   WHERE b.kode_produk = '$kd'");
            while ($row1 = $bahan->fetch_assoc()) {
        ?>
            <tr>
                <td><?= $no1; ?></td>
                <td><?= $row1['nama']; ?></td>
                <td><?= number_format($row1['harga']); ?></td>
                <td><?= $row1['kebutuhan']; ?></td>
                <td><?= number_format($row1['harga'] * $row1['kebutuhan']); ?></td>
            </tr>
        <?php
            $totalb += $row1['harga'] * $row1['kebutuhan']; // Menambahkan total biaya bahan baku
            $no1++;
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><b>Total Biaya Bahan Baku = <?= number_format($totalb); ?></b></td>
            </tr>
            <tr>
                <td colspan="7" class="text-right bg-success" style="color: green;">
                    <b>TOTAL PENDAPATAN BERSIH = <?= number_format($total - $totalb); ?></b>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php
    // Tutup koneksi setelah selesai
    $conn->close();
    ?>
</body>
</html>
