<?php 
session_start();

// Menghapus sesi 'user' dan 'kd_cs' dari sesi yang sedang aktif
unset($_SESSION['user']);
unset($_SESSION['kd_cs']);

// Redirect pengguna ke halaman login
header('Location: ../user_login.php');
exit; // Pastikan script tidak melanjutkan eksekusi setelah header.
?>
