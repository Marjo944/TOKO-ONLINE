<?php
// Include database connection
include '../koneksi/koneksi.php';

// Create a new MySQLi object
$conn = new mysqli("localhost", "root", "", "dbbangunan");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest customer code from the 'customer' table
$kode = $conn->query("SELECT kode_customer FROM customer ORDER BY kode_customer DESC");
$data = $kode->fetch_assoc();
$num = substr($data['kode_customer'], 1, 4);
$add = (int) $num + 1;

// Format the customer code based on the incremented value
if (strlen($add) == 1) {
    $format = "C000" . $add;
} elseif (strlen($add) == 2) {
    $format = "C00" . $add;
} elseif (strlen($add) == 3) {
    $format = "C0" . $add;
} else {
    $format = "C" . $add;
}

// Get the form data
$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$tlp = $_POST['telp'];
$konfirmasi = $_POST['konfirmasi'];

// Hash the password for security
$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if the password and confirmation match
if ($password == $konfirmasi) {
    // Check if the username already exists
    $cek = $conn->query("SELECT username FROM customer WHERE username = '$username'");
    $jml = $cek->num_rows;

    // If the username already exists, show an error
    if ($jml == 1) {
        echo "
        <script>
        alert('USERNAME SUDAH DIGUNAKAN');
        window.location = '../register.php';
        </script>
        ";
        die;
    }

    // Insert the new customer record into the 'customer' table
    $result = $conn->query("INSERT INTO customer (kode_customer, nama, email, username, password, telp) 
                            VALUES ('$format', '$nama', '$email', '$username', '$hash', '$tlp')");
    // Check if the insert was successful
    if ($result) {
        echo "
        <script>
        alert('REGISTER BERHASIL');
        window.location = '../user_login.php';
        </script>
        ";
    }
} else {
    // If passwords don't match, show an error
    echo "
    <script>
    alert('KONFIRMASI PASSWORD TIDAK SAMA');
    window.location = '../register.php';
    </script>
    ";
}

// Close the connection
$conn->close();
?>
