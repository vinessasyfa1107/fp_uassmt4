<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');
//inisialisasi session
session_start();

$resultTransaksi = mysqli_query($con,  "SELECT * FROM transaksi INNER JOIN users ON transaksi.id_user = users.id ORDER BY created_at DESC");


$error = '';
$validate = '';
if (!isset($_COOKIE['username_admin'])) {
  $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
  header('Location: login.php');
}

function formatDateTime($dateString)
{
  $date = DateTime::createFromFormat("Y-m-d H:i:s", $dateString);
  $formattedDate = $date->format("d-m-Y H:i:s");
  return $formattedDate;
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Document</title>
</head>

<body>
  <nav class='navbar navbar-expand-lg navbar-dark bg-dark text-light '>
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="../img/relaxed.png" width="30" height="30" class="d-inline-block align-top" alt="">
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
  <div class="mt-5" style="height:70vh">
    <div class="container">
      <!-- back button -->
      <a href="index.php" class="btn btn-secondary mb-3 float-start me-3"><i class="fa fa-arrow-left"></i> Kembali</a>
      <h3>Daftar Semua Transaksi</h3>
      <table class="table table-striped">
        <thead class="thead-dark">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Tanggal Transaksi</th>
            <th scope="col">Nama Pembeli</th>
            <th scope="col">Nama Produk</th>
            <th scope="col">Qty</th>
            <th scope="col">Total Harga</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $index = 0;

          while ($row = mysqli_fetch_array($resultTransaksi)) {
          ?>
            <tr>
              <td><?php echo $index + 1 ?></td>
              <td><?php echo formatDateTime($row['created_at']); ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['nama_produk']; ?></td>
              <td><?php echo $row['qty']; ?></td>
              <td>Rp. <?= number_format($row['total_harga'], 0, '', '.') ?></td>

              <td>
                <?php $colorbadge = "";
                if ($row['status'] == 'proses') {
                  $colorbadge = 'text-dark bg-warning';
                } else if ($row['status'] == 'selesai') {
                  $colorbadge = 'bg-success';
                } else if ($row['status'] == 'batal') {
                  $colorbadge = 'bg-danger';
                } ?>
                <span class="badge <?= $colorbadge; ?>">
                  <?php echo $row['status']; ?>
                </span>
              </td>

            </tr>
          <?php
            $index++;
          }
          ?>
        </tbody>
      </table>
      <?php
      if (mysqli_num_rows($resultTransaksi) == 0) {
        echo "<h5 class='text-center bg-secondary text-white p-4'>Data tidak tersedia</h5>";
      }
      ?>
      </section>
    </div>
</body>

</html>