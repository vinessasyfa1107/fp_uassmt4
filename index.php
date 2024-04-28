<?php
require('koneksi.php');
$error = '';
$validate = '';
session_start();

//mengecek apakah cookie username tersedia atau tidak jika tersedia maka akan diredirect ke halaman index
if (!isset($_COOKIE['username_user'])) header('Location: login.php');
?>


<?php
$cari = "";
//untuk cari produk
if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $result = mysqli_query($con, "SELECT p.gambar_produk, p.nama_produk, k.nama_kategori, p.stok, p.harga, p.id
    FROM produk p
    JOIN kategori k ON p.cat_id = k.id_cat
    WHERE p.stok > 0 AND p.nama_produk LIKE '%$cari%'");
} else {
    $result = mysqli_query($con, "SELECT p.gambar_produk, p.nama_produk, k.nama_kategori, p.stok, p.harga, p.id
    FROM produk p
    JOIN kategori k ON p.cat_id = k.id_cat
    WHERE p.stok > 0");
} ?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard | Warung Santuy</title>
    <link rel="icon" href="img/relaxed.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/user.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

    <div class="sidebar">
        <div class="topSidebar">
            <img src="img/relaxed.png" alt="image" class="logoWeb">
            <p>Warung Santuy</p>
        </div>
        <ul class="side">
            <li class="dashboardList active">
                <a href="index.php">
                    <img src="img/dashboardLogo.png" alt="image" class="menuLogo dashboard">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="dashboardList pesan">
                <a href="order/index.php">
                    <img src="img/pesananLogo.png" alt="image" class="menuLogo dashboard">
                    <span>Pesanan Saya</span>
                </a>
            </li>
            <li class="dashboardList">
                <a href="akun.php">
                    <img src="img/pengaturanLogo.png" alt="image" class="menuLogo dashboard">
                    <span>Akun</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="custom-container">
        <header>
            <p>Halo, <?= $_COOKIE['username_user'] ?> | </p>
            <a href="logout.php" class="text-dark fw-bold">
                Keluar &nbsp;
                <i class="fa fa-sign-out"></i>
            </a>
        </header>
        <div class="content">
            <?php
            if (isset($_SESSION['status'])) {
            ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['status'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            unset($_SESSION['status']);

            ?>
            <div class="d-flex justify-content-between">
                <div class="col">
                    <h1>Daftar Produk</h1>
                </div>
                <div class="col">
                    <div class="row justify-content-end">
                        <div class="col-6">
                            <form action="index.php" method="get">
                                <div class="input-group">
                                    <input class="form-control border-end-0 border rounded-pill" placeholder="Cari Produk..." value="<?php echo $cari; ?>" type="text" name="cari" id="example-search-input">
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="submit" value="cari">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <br><br>
            <div id="produkContainer">
                <div id="produkList">
                    <ul class="listProduk">
                        <?php
                        // Iterate through each row of the result
                        while ($row = mysqli_fetch_assoc($result)) {
                            $gambar_produk = $row['gambar_produk'];
                            $nama = $row['nama_produk'];
                            $kategori = $row['nama_kategori'];
                            $stok = $row['stok'];
                            $harga = $row['harga'];
                            $produk_id = $row['id'];
                        ?>
                            <li>
                                <div class="singleImage">
                                    <div class="product-img">
                                        <?php if ($gambar_produk != null) { ?>
                                            <img src="img/products/<?= $gambar_produk; ?>" alt="image">
                                        <?php } else { ?>
                                            <img src="img/no-photo.png" alt="image">
                                        <?php } ?>
                                    </div>
                                    <div class="textContainer">
                                        <p class="subSingle1"><?php echo $nama; ?></p>
                                        <p class="subSingle2"><?php echo $kategori; ?></p>
                                        <div class="subSingle3">
                                            <p>Stok : <?php echo $stok; ?></p>
                                            <p class="price">Rp. <?= number_format($harga, 0, '', '.') ?></p>
                                        </div>
                                        <div class="buttonContainer">
                                            <button class="btn addButton" data-bs-toggle="modal" data-bs-target="#exampleModal" id="addButton" data-user-id="<?= $_COOKIE['user_id']; ?>" data-nama="<?= $nama; ?>" data-harga="<?= $harga; ?>" data-id="<?php echo $produk_id; ?>">Pesan Sekarang</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mau Berapa <span id="name" class="text-primary"></span> ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./CRUD/add_transaction.php" method="post">
                    <div class="modal-body">
                        <p>Qty : </p>
                        <div class="number mb-2">
                            <span class="minus">-</span>
                            <input disabled type="text" name="qtyDisplay" id="qtyDisplay" value="1" />
                            <span class="plus">+</span>
                        </div>
                        <p>Total Harga : <b id="total" class="total-harga"></b></p>
                        <input type="hidden" id="namaproduk" name="nama_produk">
                        <input type="hidden" id="userid" name="id_user">
                        <input type="hidden" id="idproduk" name="id_produk">
                        <input type="hidden" id="totalharga" name="total_harga">
                        <input type="hidden" id="qty" name="qty" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeButton" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" onclick="confirmPurchase()" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function(d, t) {
            var BASE_URL = "https://app.chatwoot.com";
            var g = d.createElement(t),
                s = d.getElementsByTagName(t)[0];
            g.src = BASE_URL + "/packs/js/sdk.js";
            g.defer = true;
            g.async = true;
            s.parentNode.insertBefore(g, s);
            g.onload = function() {
                window.chatwootSDK.run({
                    websiteToken: 'ADgJ6Q2L14TXQmRcJUgaUKdD',
                    baseUrl: BASE_URL
                })
            }
        })(document, "script");
    </script>


    <script>
        function confirmPurchase() {
            event.preventDefault(); // prevent form submit
            var form = event.target.form; // storing the form
            swal({
                    title: "Konfirmasi Pesanan",
                    text: "Cek kembali pesanan kamu ya sebelum dikonfirmasi",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "green",
                    cancelButtonText: "Cek Kembali",
                    confirmButtonText: "Sudah Cek",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        form.submit(); // submitting the form when user press yes
                    } else {
                        // tutup swa
                        swal.close();
                    }
                });
        }
    </script>

    <script>
        var harga = 0;
        $(document).on("click", ".addButton", function() {
            var name = $(this).data('nama');
            var idProduk = $(this).data('id');
            var user_id = $(this).data('user-id');
            harga = $(this).data('harga');
            var formattedharga = formatCurrency(harga);
            console.log(formattedharga);

            //add the header name
            $(".modal-header #name").html(name);
            $(".modal-body #total").html(formattedharga);

            //change the value of the input field
            $(".modal-body #idproduk").val(idProduk);
            $(".modal-body #userid").val(user_id);
            $(".modal-body #totalharga").val(harga);
            $(".modal-body #namaproduk").val(name);

            function formatCurrency(value) {
                return value.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
            }
        });
        $(document).ready(function() {
            $('.minus').click(function() {
                var $input = $(this).parent().find('input');
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                updateTotalHarga(); // Panggil fungsi updateTotalHarga()
                return false;
            });

            $('.plus').click(function() {
                var $input = $(this).parent().find('input');
                $input.val(parseInt($input.val()) + 1);
                $input.change();
                updateTotalHarga(); // Panggil fungsi updateTotalHarga()
                return false;
            });

            function updateTotalHarga() {
                var qty = parseInt($('input[name="qtyDisplay"]').val()); // Ambil nilai qty dari input field

                var total = qty * harga; // Hitung total harga
                var formattedTotal = formatCurrency(total); // Memformat nilai total menjadi format uang
                $('.total-harga').text(formattedTotal); // Update nilai total harga pada elemen dengan class total-harga
                //update nilai total harga pada elemen dengan class total-harga
                $(".modal-body #totalharga").val(total);
                $(".modal-body #qty").val(qty);
            }

            function formatCurrency(value) {
                return value.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
            }
        });

        $(document).ready(function() {
            $('#closeButton').click(function() {
                resetModal(); // Panggil fungsi resetModal()
            });

            function resetModal() {
                $('input[name="qtyDisplay"]').val(1); // Set nilai qty ke 1
                $('.total-harga').text(''); // Hapus nilai total harga

                // Reset nilai-nilai pada input field yang lain
                $(".modal-body #totalharga").val('');
                $(".modal-body #qty").val(1);
            }
        });
    </script>
</body>

</html>