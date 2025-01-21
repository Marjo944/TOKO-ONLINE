<?php 
include 'header.php';
?>

<style type="text/css">
    .bs-acc {
        margin: 20px 0;
    }
    .panel-heading {
        background-color: #f7f7f7;
        border: 1px solid #ddd;
    }
    .panel-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }
    .panel-body {
        background-color: #fafafa;
    }
</style>

<div class="container">
    <h2 class="text-center" style="width: 100%; border-bottom: 4px solid #ff8680; padding-bottom: 20px;">
        <b>Manual Aplikasi</b>
    </h2>

    <div class="bs-acc">
        <div class="panel-group" id="accordion">
            <!-- Panel 1: Bagaimana Cara Berbelanja -->
            <div class="panel panel-default">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="color:#000;">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            Bagaimana Cara Berbelanja di UD Jaya Marjo ?
                        </h4>
                    </div>
                </a>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ol>
                            <li>Pastikan Anda sudah Daftar/Register dahulu.</li>
                            <li>Pilih Produk yang ingin dibeli.</li>
                            <li>Lakukan Checkout pesanan Anda.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Cara Melakukan Pembayaran -->
            <div class="panel panel-default">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="color:#000;">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            Bagaimana cara melakukan pembayaran?
                        </h4>
                    </div>
                </a>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                        <p>Setelah checkout, Anda dapat memilih metode pembayaran yang tersedia seperti transfer bank atau pembayaran melalui aplikasi e-wallet yang kami dukung.</p>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Pengiriman Produk -->
            <div class="panel panel-default">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="color:#000;">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            Bagaimana pengiriman produk dilakukan?
                        </h4>
                    </div>
                </a>
                <div id="collapseThree" class="panel-collapse collapse">
                    <div class="panel-body">
                        <p>Produk Anda akan dikirim melalui jasa pengiriman yang kami gunakan. Anda akan menerima nomor resi pengiriman untuk melacak status paket Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<br><br><br><br><br><br><br><br>

<?php 
include 'footer.php';
?>
