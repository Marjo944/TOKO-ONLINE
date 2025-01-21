<?php 
// Menggunakan password_hash dengan algoritma default (bcrypt)
$password = "admin";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Menampilkan hasil hash
echo $hashed_password;
?>
