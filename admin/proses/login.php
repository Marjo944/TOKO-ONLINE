<?php 
session_start();
include '../../koneksi/koneksi.php';

// Mengambil data dari form login
$username = $_POST['user'];
$pass = $_POST['pass'];

// Menyiapkan query untuk memeriksa username
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

// Mengambil hasil query
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Mengecek apakah username ditemukan
if ($row) {
    // Verifikasi password
    if (password_verify($pass, $row['password'])) {
        // Jika berhasil login, set session dan alihkan ke halaman utama
        $_SESSION["admin"] = true;
        header('Location: ../halaman_utama.php');
        exit();
    } else {
        // Jika password salah
        echo "
        <script>
        alert('USERNAME/PASSWORD SALAH');
        window.location = '../index.php';
        </script>
        ";
    }
} else {
    // Jika username tidak ditemukan
    echo "
    <script>
    alert('USERNAME/PASSWORD SALAH');
    window.location = '../index.php';
    </script>
    ";
}

// Menutup statement
$stmt->close();
?>
