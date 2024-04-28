<?php
session_start();
//menyertakan file program koneksi.php pada register
require('../koneksi.php');
//inisialisasi session

$queryKat      = "SELECT * FROM kategori";
$resultKat     = mysqli_query($con, $queryKat);
$row_count = mysqli_num_rows($resultKat);


$error = '';
$validate = '';
//mengecek username pada session
if (!isset($_COOKIE['username_admin'])) {
  $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
  header('Location: login.php');
}
//mengecek apakah data username yang diinpukan user kosong atau tidak
if (isset($_POST['submit'])) {

  $namaKategori     = stripslashes($_POST['namaKategori']);
  $namaKategori     = mysqli_real_escape_string($con, $namaKategori);

  //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
  if (!empty(trim($namaKategori))) {

    if (cek_produk($namaKategori, $con) == 0) {
      //insert data ke database
      $query = "INSERT INTO kategori (nama_kategori) VALUES ('$namaKategori')";
      $result   = mysqli_query($con, $query);
      //jika insert data berhasil maka akan diredirect ke halaman index.php serta menyimpan data username ke session
      if ($result) {
        $alert = 'Kategori Berhasil Dibuat!';
        $_SESSION['alert'] = $alert;

        header('Location: category.php');
        exit;

        //jika gagal maka akan menampilkan pesan error
      } else {
        $error =  'Tambah Kategori Gagal !!';
      }
    } else {
      $error =  'Kategori sudah terdaftar !!';
    }
  } else {
    $error =  'Data Harus Diisi!!';
  }
}

//fungsi untuk mengecek username apakah sudah terdaftar atau belum
function cek_produk($namaKategori, $con)
{
  $nama = mysqli_real_escape_string($con, $namaKategori);
  $query = "SELECT * FROM kategori WHERE nama_kategori = '$namaKategori'";
  if ($result = mysqli_query($con, $query)) return mysqli_num_rows($result);
}

?>

<?php
$cari = "";
//untuk cari kategori
if (isset($_GET['cari'])) {
  $cari = $_GET['cari'];
  $resultKat = mysqli_query($con, "select * from kategori where nama_kategori like '%" . $cari . "%'");
} else {
  $resultKat = mysqli_query($con, "select * from kategori");
} ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kategori</title>
  <link rel="icon" href="../img/relaxed.png">

  <link rel="stylesheet" href="../css/style.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
  <nav class='navbar navbar-expand-lg navbar-dark bg-dark text-light '>
    <div class="container">
      <a class="navbar-brand" href="index.php">
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
  <div class="jumbotron mt-5" style="height:70vh">
    <div class="container">
      <div class="row">
        <section class="col-4 mt-5">
          <div class="card">
            <h2 class="card-header">Tambah Kategori</h5>
              <div class="card-body">
                <form class="form-container" method="POST">

                  <div class="container px-4">
                    <?php if ($error != '') { ?>
                      <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="namaKategori" name="namaKategori" placeholder="Nama Kategori">
                      <label for="namaKategori">Nama Kategori</label>

                      <?php if ($validate != '') { ?>
                        <p class="text-danger"><?= $validate; ?></p>
                      <?php } ?>
                    </div>
                    <div class="d-flex justify-content-between">
                      <a href="index.php" class="btn btn-danger btn-block mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                      <button type="submit" name="submit" class="btn btn-primary btn-block mt-3"><i class="fa fa-check"></i></i> Submit</button>
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </section>
        <section class="col-5">
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
          <div class="row justify-content-end">
            <div class="col-6">
              <form action="category.php" method="get">
                <div class="input-group">
                  <input class="form-control border-end-0 border rounded-pill" placeholder="Cari Kategori..." value="<?php echo $cari; ?>" type="text" name="cari" id="example-search-input">
                  <span class="input-group-append">
                    <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="submit" value="cari">
                      <i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <table class="table mt-3">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Kategori</th>
                <th scope="col" class="text-center">Aksi</th>
              </tr>
            </thead>


            <?php

            $index = 0;

            while ($row = mysqli_fetch_array($resultKat)) {
            ?>
              <tr>
                <td><?php echo $index + 1 ?></td>
                <td><?php echo $row['nama_kategori']; ?></td>
                <td>
                  <div class="d-flex justify-content-evenly">
                    <div class="mr-2">
                      <form name="myForm" method="POST" action="edit_category.php?id=<?php echo $row['id_cat']; ?>">
                        <button class="btn btn-primary"><i class="fa fa-edit"></i></i> Edit</button>
                      </form>
                    </div>
                    <div>
                      <form name="myForm" method="POST" action="../CRUD/delete_cat.php?id=<?php echo $row['id_cat']; ?>">
                        <button onclick="archiveFunction()" class="btn btn-danger ml-2"><i class="fa fa-trash"></i></i> Delete</button>
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
          if (mysqli_num_rows($resultKat) == 0) {
            echo "<h5 class='text-center bg-dark text-white p-4'>Data tidak tersedia</h5>";
          }
          ?>
        </section>

      </div>
    </div>
  </div>



  <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  <script>
    function archiveFunction() {
      event.preventDefault(); // prevent form submit
      var form = event.target.form; // storing the form
      swal({
          title: "Yakin mau diapus?",
          text: "Datanya ga bakal kembali kalo diapus",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yakin",
          cancelButtonText: "Engga, deh",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            form.submit(); // submitting the form when user press yes
          } else {
            swal("Dibatalkan!", "Datanya Aman :D", "error");
          }
        });
    }
  </script>
</body>

</html>