<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');
//inisialisasi session
session_start();

$queryKat      = "SELECT * FROM kategori";
$resultKat     = mysqli_query($con, $queryKat);
$row_count = mysqli_num_rows($resultKat);

$id = $_GET['id']; // get id through query string

$query      = "SELECT * FROM produk INNER JOIN kategori ON produk.cat_id = kategori.id_cat WHERE id ='$id'";
$result     = mysqli_query($con, $query);
$row_count = mysqli_num_rows($result);
$defaultData = mysqli_fetch_array($result);


$error = '';
$validate = '';
if (!isset($_COOKIE['username_admin'])) {
  $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
  header('Location: login.php');
}
//mengecek apakah data username yang diinpukan user kosong atau tidak
if (isset($_POST['submit'])) {

  $namaProduk     = stripslashes($_POST['namaProduk']);
  $namaProduk     = mysqli_real_escape_string($con, $namaProduk);
  $harga          = stripslashes($_POST['hargaProduk']);
  $harga          = mysqli_real_escape_string($con, $harga);
  $cat_id         = stripslashes($_POST['cat_id']);
  $cat_id         = mysqli_real_escape_string($con, $cat_id);
  $keterangan         = stripslashes($_POST['keterangan']);
  $keterangan         = mysqli_real_escape_string($con, $keterangan);

  //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
  if (!empty(trim($namaProduk)) && !empty(trim($harga)) && !empty(trim($cat_id))) {
    //memanggil method cek_produk untuk mengecek apakah user sudah terdaftar atau belum
    //insert data ke database
    $query = "UPDATE produk SET nama_produk='$namaProduk', harga='$harga', cat_id='$cat_id', keterangan='$keterangan' WHERE id='$id'";
    $result   = mysqli_query($con, $query);
    //jika insert data berhasil maka akan diredirect ke halaman index.php serta menyimpan data username ke session
    if ($result) {
      $_SESSION['alert'] = "Data Berhasil Diupdate!";

      header('Location: index.php');

      //jika gagal maka akan menampilkan pesan error
    } else {
      $error =  'Update Produk Gagal !!';
    }
  } else {
    $error =  'Semua Data Harus Diisi!!';
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Produk</title>
  <link rel="icon" href="../img/relaxed.png">
  <link rel="stylesheet" href="css/style.css">

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
    <div class="container-fluid">
      <div class="row justify-content-center">
        <section class="col-12 col-sm-6 col-md-4">
          <div class="card">
            <h2 class="card-header">Edit Produk</h5>
              <div class="card-body">
                <form class="form-container" method="post">
                  <input type="hidden" name="id" value="<?php echo $defaultData['id'] ?>">

                  <div class="container px-4">
                    <?php if ($error != '') { ?>
                      <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                    <?php } ?>

                    <div class="row gx-5">
                      <div class="col">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="namaProduk" name="namaProduk" value="<?php echo $defaultData['nama_produk'] ?>" placeholder="Nama Produk">
                          <label for="namaProduk">Nama Produk</label>
                          <div id="emailHelp" class="form-text">cth penamaan : Telor 1Kg</div>
                        </div>
                        <div class="form-floating mb-3">
                          <select name="cat_id" class="form-select" aria-label="KategoriP">
                            <option value="<?php echo $defaultData['id_cat'] ?>" selected><?php echo $defaultData['nama_kategori'] ?></option>
                            <?php
                            while ($data = mysqli_fetch_array($resultKat)) {
                            ?>
                              <option value="<?= $data['id_cat']; ?>"><?php echo $data['nama_kategori']; ?></option>
                            <?php } ?>
                          </select>
                          <label for="kategoriP">Kategori Produk</label>

                        </div>
                        <div class="form-floating mb-3 mt-3">
                          <input type="number" value="<?php echo $defaultData['harga'] ?>" class="form-control" id="hargaProduk" name="hargaProduk" placeholder="Harga">
                          <label for="hargaProduk">Harga Produk</label>

                          <?php if ($validate != '') { ?>
                            <p class="text-danger"><?= $validate; ?></p>
                          <?php } ?>
                        </div>
                        <p><b>Gambar Saat Ini :</b> </p>
                        <?php if ($defaultData['gambar_produk'] != '') { ?>
                          <img src="../img/products/<?= $defaultData['gambar_produk']; ?>" style="max-width: 300px; max-height: 300px;">
                        <?php
                        } else { ?>
                          <span>Tidak ada gambar sebelumnya</span>
                        <?php } ?>
                        <div class="mb-3 mt-3">
                          <label for="gambarProduk">Ubah Gambar (opsional)</label>
                          <input type="file" class="form-control" id="gambarProduk" name="gambarProduk" accept=".png, .jpg, .jpeg">
                        </div>
                        <div id="previewContainer" class="mt-3 mb-3" style="display: none;">
                          <p>Preview Gambar : </p>
                          <img id="previewImage" src="#" alt="Preview" style="max-width: 300px; max-height: 300px;">
                        </div>
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" value="<?php echo $defaultData['keterangan'] ?>" id="keterangan" name="keterangan" placeholder="Nama Produk">
                          <label for="keterangan">Keterangan (opsional)</label>
                        </div>
                      </div>

                      <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-danger btn-block mt-3"><i class="fa fa-arrow-left"></i> Cancel</a>
                        <button type="submit" name="submit" class="btn btn-primary btn-block mt-3"><i class="fa fa-check"></i></i> Submit</button>
                      </div>
                    </div>
                </form>
              </div>
          </div>
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
  <script>
    document.getElementById('gambarProduk').addEventListener('change', function(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('previewImage').src = e.target.result;
          document.getElementById('previewContainer').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    });
  </script>
</body>


</html>