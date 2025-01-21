<?php 
include 'header.php';

// Check if the page request is to delete a customer
if (isset($_GET['page']) && $_GET['page'] == 'del' && isset($_GET['kode'])) {
    // Validate and sanitize the 'kode' parameter to prevent potential security issues
    $kode = mysqli_real_escape_string($conn, $_GET['kode']);

    // Prepare and execute the DELETE query using a prepared statement
    $stmt = $conn->prepare("DELETE FROM customer WHERE kode_customer = ?");
    $stmt->bind_param("s", $kode); // "s" indicates that the parameter is a string
    $result = $stmt->execute();

    if ($result) {
        echo "
        <script>
        alert('DATA BERHASIL DIHAPUS');
        window.location = 'm_customer.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Gagal menghapus data');
        window.location = 'm_customer.php';
        </script>
        ";
    }
}

?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Data Customer</b></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Retrieve customer data using a prepared statement
            $stmt = $conn->prepare("SELECT * FROM customer ORDER BY kode_customer ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            $no = 1;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <th scope="row"><?php echo $no; ?></th>
                    <td><?= htmlspecialchars($row['kode_customer']); ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="m_customer.php?kode=<?php echo $row['kode_customer'];?>&page=del" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data?')">
                            <i class="glyphicon glyphicon-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php 
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Optional Modal (You can customize or remove if not needed) -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <!-- Modal content goes here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
