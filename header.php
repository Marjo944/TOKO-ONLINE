<?php 
session_start();
include 'koneksi/koneksi.php';

// Mengambil kode customer jika sudah login
if (isset($_SESSION['kd_cs'])) {
    $kode_cs = $_SESSION['kd_cs'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD Jaya Marjo</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row top">
            <center>
                <div class="col-md-4" style="padding: 3px;">
                    <span><i class="glyphicon glyphicon-earphone"></i> +6285277906416</span>
                </div>

                <div class="col-md-4" style="padding: 3px;">
                    <span><i class="glyphicon glyphicon-envelope"></i> simbolonmarjo@gmail.com</span>
                </div>

                <div class="col-md-4" style="padding: 3px;">
                    <span>UD Jaya Marjo</span>
                </div>
            </center>
        </div>
    </div>

    <nav class="navbar navbar-default" style="padding: 5px;">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="color: #ff8680"><b>UD JAYA MARJO</b></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produk.php">Produk</a></li>
                    <li><a href="about.php">Tentang Kami</a></li>
                    <li><a href="manual.php">Manual Aplikasi</a></li>
                    

                    <?php 
                    if (isset($kode_cs)) {
                        // Menggunakan prepared statement untuk mencegah SQL injection
                        $stmt = $conn->prepare("SELECT kode_produk FROM keranjang WHERE kode_customer = ?");
                        $stmt->bind_param("s", $kode_cs); // Mengikat parameter untuk mencegah SQL injection
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $value = $result->num_rows;
                        $stmt->close();
                    ?>
                        <li><a href="keranjang.php"><i class="glyphicon glyphicon-shopping-cart"></i> <b>[ <?= $value ?> ]</b></a></li>
                    <?php 
                    } else {
                        echo "<li><a href='keranjang.php'><i class='glyphicon glyphicon-shopping-cart'></i> [0]</a></li>";
                    }

                    // Menangani login dan logout
                    if (!isset($_SESSION['user'])) {
                    ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Akun <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="user_login.php">Login</a></li>
                                <li><a href="register.php">Register</a></li>
                            </ul>
                        </li>
                    <?php 
                    } else {
                    ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> <?= htmlspecialchars($_SESSION['user']); ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="proses/logout.php">Log Out</a></li>
                            </ul>
                        </li>
                    <?php 
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</body>
</html>
