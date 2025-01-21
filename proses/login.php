<?php 
session_start();
include '../koneksi/koneksi.php';

$username = $_POST['username'];
$password = $_POST['pass'];

try {
    // Persiapkan query untuk memeriksa apakah username ada di database
    $stmt = $conn->prepare("SELECT * FROM customer WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah username ditemukan
    if ($result->num_rows == 1) {
        // Ambil data pengguna
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session jika login berhasil
            $_SESSION['user'] = $row['nama'];
            $_SESSION['kd_cs'] = $row['kode_customer'];
            header('location:../index.php');
            exit;
        } else {
            echo "
            <script>
            alert('USERNAME/PASSWORD SALAH');
            window.location = '../user_login.php';
            </script>
            ";
            exit;
        }
    } else {
        echo "
        <script>
        alert('USERNAME/PASSWORD SALAH');
        window.location = '../user_login.php';
        </script>
        ";
        exit;
    }
} catch (Exception $e) {
    // Tangani error dengan menampilkan pesan kesalahan
    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>
