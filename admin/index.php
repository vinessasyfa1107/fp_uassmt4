<?php
require('../koneksi.php');

session_start();


//mengecek username pada session
if (!isset($_COOKIE['username_admin'])) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}

// query transaksi join produk & user
$resultOnlyProsesTransaksi = mysqli_query($con, "SELECT * FROM transaksi INNER JOIN users ON transaksi.id_user = users.id WHERE status = 'proses' ORDER BY created_at ASC");
$result     = mysqli_query($con, "SELECT * FROM produk INNER JOIN kategori ON produk.cat_id = kategori.id_cat");

$resultTP     = mysqli_query($con, "SELECT COUNT(id) AS hasil FROM produk");
$resultTT     = mysqli_query($con, "SELECT COUNT(id_transaksi) AS hasil FROM transaksi");
$row_count = mysqli_num_rows($result);
$totalProduk = mysqli_fetch_array($resultTP);
$totalTransaksi = mysqli_fetch_array($resultTT);
$totalPenghasilan = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(total_harga) AS hasil FROM transaksi WHERE status = 'selesai'"));


?>

<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');

$queryKat      = "SELECT * FROM kategori";
$resultKat     = mysqli_query($con, $queryKat);
$row_count = mysqli_num_rows($resultKat);


$error = '';
$validate = '';
//mengecek apakah data username yang diinpukan user kosong atau tidak
if (isset($_POST['submit'])) {

    $namaProduk     = stripslashes($_POST['namaProduk']);
    $namaProduk     = mysqli_real_escape_string($con, $namaProduk);
    $harga          = stripslashes($_POST['hargaProduk']);
    $harga          = mysqli_real_escape_string($con, $harga);
    $cat_id         = stripslashes($_POST['cat_id']);
    $cat_id         = mysqli_real_escape_string($con, $cat_id);
    $stok           = stripslashes($_POST['stok']);
    $stok           = mysqli_real_escape_string($con, $stok);
    $keterangan     = stripslashes($_POST['keterangan']);
    $keterangan     = mysqli_real_escape_string($con, $keterangan);

    // File upload
    $gambarProduk = $_FILES['gambarProduk'];
    $gambarName = $gambarProduk['name'];
    $gambarTmp = $gambarProduk['tmp_name'];
    $gambarSize = $gambarProduk['size'];
    $gambarError = $gambarProduk['error'];

    // Cek apakah gambar berhasil diupload
    if ($gambarError === UPLOAD_ERR_OK) {
        // Mendapatkan informasi file gambar
        $gambarExt = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        // Cek ekstensi file gambar
        $allowedExtensions = array('png', 'jpg', 'jpeg');
        if (in_array($gambarExt, $allowedExtensions)) {
            // Cek ukuran file gambar
            $maxSize = 1048576; // 1MB dalam byte
            if ($gambarSize <= $maxSize) {
                // Pindahkan file gambar ke direktori tujuan
                $gambarDestination = '../img/products/' . $gambarName;
                move_uploaded_file($gambarTmp, $gambarDestination);

                // Insert data ke database
                $query = "INSERT INTO produk (nama_produk, cat_id, harga, keterangan, stok, gambar_produk) VALUES ('$namaProduk','$cat_id','$harga','$keterangan', '$stok', '$gambarName')";
                $result   = mysqli_query($con, $query);

                // Jika insert data berhasil maka akan diredirect ke halaman index.php serta menyimpan data username ke session
                if ($result) {
                    $_SESSION['alert'] = "Data Berhasil Ditambah!";
                    header('Location: index.php');
                } else {
                    $error =  'Tambah Produk Gagal !!';
                }
            } else {
                $error = 'Ukuran gambar melebihi batas maksimum (1MB).';
            }
        } else {
            $error = 'Hanya gambar dengan format PNG, JPG, dan JPEG yang diperbolehkan.';
        }
    } elseif ($gambarError === UPLOAD_ERR_NO_FILE) {
        $error = 'Silakan pilih file gambar.';
    } else {
        $error = 'Terjadi kesalahan saat mengupload gambar.';
    }

    // ...
}


//fungsi untuk mengecek username apakah sudah terdaftar atau belum
function cek_produk($namaProduk, $con)
{
    $nama = mysqli_real_escape_string($con, $namaProduk);
    $query = "SELECT * FROM produk WHERE nama = '$nama'";
    if ($result = mysqli_query($con, $query)) return mysqli_num_rows($result);
}

?>

<?php
$type = "";
$cari = "";
$highlited = 0;
//untuk cari produk
if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $type = $_GET['search-type'];
    if ($type == "all") {
        $result = mysqli_query($con, "select * from produk INNER JOIN kategori ON produk.cat_id = kategori.id_cat where nama_produk like '%" . $cari . "%'
        or harga like '%" . $cari . "%'
        or nama_kategori like '%" . $cari . "%'
        or keterangan like '%" . $cari . "%'");
    } else {
        $result = mysqli_query($con, "select * from produk INNER JOIN kategori ON produk.cat_id = kategori.id_cat where $type like '%" . $cari . "%'");
    }
    if (mysqli_num_rows($result) != 0) {
        $highlited = 1;
    }
} else {
    $result = mysqli_query($con, "select * from produk INNER JOIN kategori ON produk.cat_id = kategori.id_cat");
} ?>

<?php

// Query to retrieve data from transaksi table and calculate sum of counts for each id_produk
$query2 = "SELECT p.nama_produk, sum(t.qty) AS total
FROM transaksi t
INNER JOIN produk p ON t.id_produk = p.id
GROUP BY t.id_produk";
$result2     = mysqli_query($con, $query2);

$labels = array();
$data = array();

while ($row = mysqli_fetch_assoc($result2)) {
    $labels[] = $row['nama_produk'];
    $data[] = $row['total'];
}

// Convert PHP arrays to JavaScript arrays
$labels_js = json_encode($labels);
$data_js = json_encode($data);


// Query to retrieve data from transaksi table and calculate sum of total_harga for each month
$query3 = "SELECT MONTHNAME(created_at) AS bulan, SUM(total_harga) AS total FROM transaksi
    WHERE status = 'selesai'
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at) ASC";
$result3     = mysqli_query($con, $query3);

$labels2 = array();
$data2 = array();

while ($row2 = mysqli_fetch_assoc($result3)) {
    $labels2[] = $row2['bulan'];
    $data2[] = $row2['total'];
}

// Convert PHP arrays to JavaScript arrays
$labels_js2 = json_encode($labels2);
$data_js2 = json_encode($data2);

// Query to retrieve data from transaksi table and count total of transaksi for each month

$query4 = "SELECT MONTHNAME(created_at) AS bulan, COUNT(id_transaksi) AS total FROM transaksi
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at) ASC";
$result4     = mysqli_query($con, $query4);

$labels3 = array();
$data3 = array();

while ($row3 = mysqli_fetch_assoc($result4)) {
    $labels3[] = $row3['bulan'];
    $data3[] = $row3['total'];
}

// Convert PHP arrays to JavaScript arrays
$labels_js3 = json_encode($labels3);
$data_js3 = json_encode($data3);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="icon" href="../img/relaxed.png">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark text-light'>
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../img/relaxed.png" width="40px" alt="logo">
                Warung Santuy
            </a>
            <button class="navbar-toggler" type="button" data-togle="collapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav ml-auto pt-2 pb-2">

                <li class="nav-item ml-4">
                    <a href="logout.php" class="nav-link text-light">
                        <i class="fa fa-sign-out"></i>

                        Log Out </a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="mt-4">
        <div class="container">
            <div class="col-md-auto align-self-center">
                <h1>Selamat Datang <?php echo $_COOKIE['username_admin'] ?>!</h1>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">

                                    <div class="media-body">
                                        <i class="fa fa-shopping-cart fa-2x float-end"></i>

                                        <h3><?php echo $totalProduk['hasil'] ?></h3>
                                        <span>Jumlah Produk</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body text-right">
                                        <i class="fa fa-tags fa-2x me-2 float-end"></i>
                                        <h3><?php echo $totalTransaksi['hasil'] ?></h3>
                                        <span>Total Transaksi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body text-right">
                                        <i class="fa fa-money fa-2x me-1 float-end"></i>
                                        <h3><?= "Rp. " . number_format($totalPenghasilan['hasil'], 0, '', '.') ?></h3>
                                        <span>Total Penghasilan Selama Ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 mt-4 containerAllChart">
                    <div class="d-flex justify-content-between">
                        <div class="canvas-container line1">
                            <h5>Total Penghasilan Setiap Bulan</h5>
                            <div class="chartContainer">
                                <canvas id="monthlyResult"></canvas>
                            </div>
                        </div>
                        <div class="canvas-container bar">
                            <h5>Transaksi Setiap Produk</h5>
                            <div class="chartContainer2">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col align-self-center d-flex justify-content-end">
                    <a href="category.php" class="btn btn-primary "><i class="fa fa-eye"></i> Lihat Kategori Produk</a>
                </div>
            </div>
            <br />
            <div class="row mb-3">
                <!-- daftar transaksi -->
                <div class="col">
                    <?php
                    if (isset($_SESSION['process-alert'])) {
                    ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['process-alert'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                    }
                    unset($_SESSION['process-alert']);
                    ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Daftar Proses Transaksi</h4>
                            <a href="transactions.php" class="btn btn-outline-primary  btn-sm"><i class="fa fa-eye"></i> Lihat Semua Transaksi</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Tanggal Dibuat</th>
                                        <th scope="col">Nama Pembeli</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;

                                    while ($row = mysqli_fetch_array($resultOnlyProsesTransaksi)) {
                                    ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['nama_produk']; ?></td>
                                            <td><?= $row['qty']; ?></td>
                                            <td>Rp. <?= number_format($row['total_harga'], 0, '', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $row['status'] == 'proses' ? 'text-dark bg-warning' : 'bg-success' ?>">
                                                    <?= $row['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-evenly">
                                                    <form method="post" action="../CRUD/transaction_done.php?id=<?= $row['id_transaksi']; ?>">
                                                        <button name="selesai-btn" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Selesaikan </button>
                                                    </form>
                                                    <form method="post" action="../CRUD/transaction_cancel_admin.php?id=<?= $row['id_transaksi']; ?>">
                                                        <button onclick="confirmcancel()" name="batal-btn" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Batalkan </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            if (mysqli_num_rows($resultOnlyProsesTransaksi) == 0) {
                                echo "<h5 class='text-center bg-secondary text-white p-4'>Data tidak tersedia</h5>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <section class="col-4 mb-3">
                    <div class="card">
                        <h2 class="card-header">Tambah Produk</h2>
                        <div class="card-body">
                            <form class="form-container" method="POST" enctype="multipart/form-data">

                                <div class="container px-4">
                                    <?php if ($error != '') { ?>
                                        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                                    <?php } ?>

                                    <div class="row gx-5">
                                        <div class="col">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="namaProduk" name="namaProduk" placeholder="Nama Produk">
                                                <label for="namaProduk">Nama Produk</label>
                                                <div id="emailHelp" class="form-text">cth penamaan : Telor 1Kg</div>

                                            </div>
                                            <div class="form-floating">

                                                <select name="cat_id" class="form-select" aria-label="KategoriP">
                                                    <option selected>-</option>
                                                    <?php
                                                    while ($data = mysqli_fetch_array($resultKat)) {
                                                    ?>
                                                        <option value="<?= $data['id_cat']; ?>"><?php echo $data['nama_kategori']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label for="kategoriP">Kategori Produk</label>
                                            </div>
                                            <div class="form-floating mb-3 mt-4">
                                                <input type="number" class="form-control" id="stok" name="stok" placeholder="Harga">
                                                <label for="stok">Stok Awal Produk</label>
                                                <?php if ($validate != '') { ?>
                                                    <p class="text-danger"><?= $validate; ?></p>
                                                <?php } ?>
                                            </div>
                                            <div class="form-floating mb-3 mt-4">
                                                <input type="number" class="form-control" id="hargaProduk" name="hargaProduk" placeholder="Harga">
                                                <label for="hargaProduk">Harga Produk</label>
                                                <?php if ($validate != '') { ?>
                                                    <p class="text-danger"><?= $validate; ?></p>
                                                <?php } ?>
                                            </div>
                                            <div class="mt-3 mb-3">
                                                <label for="gambarProduk">Gambar Produk</label>
                                                <div class="text-secondary fw-light">
                                                    <p>Ketentuan : </p>
                                                    <ul>
                                                        <li>Format file : PNG, JPG, JPEG</li>
                                                        <li>Ukuran file maksimal : 1MB</li>
                                                    </ul>
                                                </div>
                                                <input type="file" class="form-control" id="gambarProduk" name="gambarProduk" accept=".png, .jpg, .jpeg">
                                            </div>
                                            <div id="previewContainer" class="mb-3 mt-3">
                                                <p>Preview Gambar : </p>
                                                <img id="previewImage" src="#" alt="Preview">
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Nama Produk">
                                                <label for="keterangan">Keterangan (opsional)</label>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block mt-3"><i class="fa fa-check"></i></i> Submit</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </section>
                <section class="col-8">

                    <?php
                    if (isset($_SESSION['alert'])) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['alert'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                    }
                    unset($_SESSION['alert']);

                    ?>
                    <form action="index.php" method="get">

                        <div class="row justify-content-end">
                            <h5>Cari : </h5>
                            <div class="col form">
                                <select name="search-type" class="form-select" aria-label="KategoriP">
                                    <option value="all" selected>Semua Kolom</option>
                                    <option <?php if ($type == "nama_produk") {
                                                echo "selected";
                                            } ?> value="nama_produk">Nama Produk</option>
                                    <option <?php if ($type == "harga") {
                                                echo "selected";
                                            } ?> value="harga">Harga Produk</option>
                                    <option <?php if ($type == "nama_kategori") {
                                                echo "selected";
                                            } ?> value="nama_kategori">Kategori</option>
                                    <option <?php if ($type == "keterangan") {
                                                echo "selected";
                                            } ?> value="keterangan">Keterangan</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input class="form-control border-end-0 border rounded-pill" placeholder="Cari Produk..." value="<?php echo $cari; ?>" type="text" name="cari" id="example-search-input">
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="submit" value="cari">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table mt-3">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Harga Produk</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>


                        <?php

                        $index = 0;
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?php echo $index + 1 ?></td>
                                <td><?php echo $row['nama_produk']; ?></td>
                                <td><?php if ($row['stok'] == 0) { ?>
                                        <div class="text-danger">Stok Habis</div>
                                    <?php
                                    } elseif ($row['stok'] < 10) { ?>
                                        <div class="text-warning"><?= $row['stok']; ?></div>
                                    <?php
                                        # code...
                                    } else {
                                        echo $row['stok'];
                                    }
                                    ?>
                                </td>
                                <td>Rp. <?= number_format($row['harga'], 0, '', '.') ?></td>
                                <td><?php echo $row['nama_kategori']; ?></td>
                                <td><?php if ($row['keterangan'] == '') {
                                        echo "-";
                                    } else {
                                        echo $row['keterangan'];
                                    }  ?></td>
                                <td>
                                    <div class="d-flex justify-content-evenly">
                                        <div class="mr-2">
                                            <button class="btn btn-sm btn-info text-white updateStok" data-id="<?= $row['id']; ?>" data-nama="<?= $row['nama_produk']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-edit"></i></i> Update Stok</button>
                                        </div>
                                        <div class="mr-2">
                                            <form name="myForm" method="POST" action="edit_product.php?id=<?php echo $row['id']; ?>">
                                                <button class="btn btn-sm btn-success"><i class="fa fa-edit"></i></i></button>
                                            </form>
                                        </div>
                                        <div>
                                            <form name="myForm" method="POST" action="../CRUD/delete.php?id=<?php echo $row['id']; ?>">
                                                <button onclick="archiveFunction()" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $index++;
                        }
                        ?>
                    </table>
                    <?php
                    if (mysqli_num_rows($result) == 0) {
                        echo "<h5 class='text-center bg-secondary text-white p-4'>Data tidak tersedia</h5>";
                    }
                    ?>
                </section>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Stok <span class="text-primary" id="name"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="update_stok.php" method="post">

                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">
                            <div class="form-floating">
                                <select name="type" class="form-select" aria-label="type" required>
                                    <option value="" selected>pilih salah satu</option>
                                    <option value="tambah">Penambahan</option>
                                    <option value="kurang">Pengurangan</option>
                                </select>
                                <label for="type">Jenis Update</label>
                            </div>
                            <div class="form-floating mt-3 mb-3">
                                <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" required>
                                <label for="stok">Stok</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <script>
            document.getElementById('gambarProduk').addEventListener('change', function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var previewContainer = document.getElementById('previewContainer');
                        var previewImage = document.getElementById('previewImage');
                        previewContainer.classList.remove('show');
                        previewImage.src = e.target.result;
                        // Menambahkan class 'show' dengan jeda waktu 0ms untuk memicu animasi baru
                        setTimeout(function() {
                            previewContainer.classList.add('show');
                        }, 0);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        </script>
</body>
<!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">



<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels_js; ?>,
            datasets: [{
                label: 'Total Transaksi',
                data: <?php echo $data_js; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    //change font to poppins
                    ticks: {
                        stepSize: 2,
                        font: {
                            family: 'Poppins'
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Poppins'
                        }
                    }
                }
            }
        }
    });


    var ctx = document.getElementById('monthlyResult').getContext('2d');
    var myChart = new Chart(ctx, {

        type: 'line',
        data: {
            labels: <?php echo $labels_js2; ?>,
            datasets: [{
                label: 'Total',
                data: <?php echo $data_js2; ?>,
                borderWidth: 3,
                borderColor: '#2D9CDB',
                fill: true,
                backgroundColor: '#6EC8EF50',
                pointRadius: 11, // Customize the size of the dots
                pointHoverRadius: 11, // Customize the size of the dots on hover
                // pointBackgroundColor: ['transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', '#2D9CDB', 'transparent', 'transparent', 'transparent', 'transparent'],
                // pointBorderColor: ['transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'transparent', 'white', 'transparent', 'transparent', 'transparent', 'transparent'],
                pointBorderWidth: 5,
                pointBackgroundColor: 'rgba(0, 0, 0, 0)',
                pointBorderColor: 'rgba(0, 0, 0, 0)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                display: false,
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {

                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        stepSize: 500000,
                    }
                },
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                    }
                }]
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    displayColors: false, // Disable the display of color boxes
                    mode: 'index', // Use the 'index' mode for tooltip positioning
                    intersect: false, // Prevent tooltip from intersecting with other data points
                    caretSize: 0 // Set the caret size to 0 to remove the square
                }
            },
            elements: {
                line: {
                    tension: 0.2 // Adjust the tension for a smoother curve
                }
            },
            spanGaps: true
        }
    });

    var ctx = document.getElementById('monthlyTransaksi').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $labels_js3; ?>,
            datasets: [{
                label: 'Total Transaksi',
                data: <?php echo $data_js3; ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    //change font to poppins
                    ticks: {
                        stepSize: 10,
                        font: {
                            family: 'Poppins'
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Poppins'
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    $(document).on("click", ".updateStok", function() {
        var name = $(this).data('nama');
        var id = $(this).data('id');
        //add the header name
        $(".modal-header #name").html(name);

        //change the value of the input field
        $(".modal-body #id").val(id);
    });
</script>

<script>
    function archiveFunction() {
        event.preventDefault(); // prevent form submit
        var form = event.target.form; // storing the form
        swal({
                title: "Yakin mau diapus?",
                text: "Datanya ga bakal kembali kalo diapus",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "Yakin",
                cancelButtonText: "Tidak",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    form.submit(); // submitting the form when user press yes
                } else {
                    swal.close()
                }
            });
    }

    function confirmcancel() {
        event.preventDefault(); // prevent form submit
        var form = event.target.form; // storing the form
        swal({
                title: "Pesanan dibatalkan?",
                text: "Kalo dibatalkan, pesanan ga bisa dilanjutin lagi",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yakin",
                cancelButtonText: "Engga",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    form.submit(); // submitting the form when user press yes
                } else {
                    swal.close()
                }
            });
    }
</script>
</body>

</html>