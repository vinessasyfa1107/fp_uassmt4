<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');
//inisialisasi session
session_start();

$id = $_COOKIE['user_id'];

$resultTransaksi = mysqli_query($con,  "SELECT * FROM transaksi INNER JOIN users ON transaksi.id_user = users.id WHERE id_user = $id AND transaksi.status = 'proses' ORDER BY created_at DESC");


$error = '';
$validate = '';
if (!isset($_COOKIE['username_user'])) {
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
  <title>Pesanan Saya | Warung Santuy</title>
  <link rel="icon" href="../img/relaxed.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="../css/user.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
  <div class="sidebar">
    <div class="topSidebar">
      <img src="../img/relaxed.png" alt="image" class="logoWeb">
      <p>Warung Santuy</p>
    </div>
    <ul class="side">
      <li class="dashboardList ">
        <a href="../index.php">
          <img src="../img/dashboardLogo.png" alt="image" class="menuLogo dashboard">
          <span>Dashboard</span>
        </a>
      </li>
      <li class="dashboardList active pesan">
        <a href="../index.php">
          <img src="../img/pesananLogo.png" alt="image" class="menuLogo dashboard">
          <span>Pesanan Saya</span>
        </a>
      </li>
      <li class="dashboardList">
        <a href="akun.php">
          <img src="../img/pengaturanLogo.png" alt="image" class="menuLogo dashboard">
          <span>Akun</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="custom-container">
    <header>
      <p>Halo, <?= $_COOKIE['username_user'] ?> | </p>
      <a href="../logout.php"><img src="../img/keluar.png" alt="image" class="keluar"></a>
    </header>
    <div class="content">
      <div class="mt-5" style="height:70vh">
        <div class="container">
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
          <h3 class="mb-5">Daftar Transaksi Saya</h3>
          <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Semua</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="proses.php">Proses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="selesai.php">Selesai</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="batal.php">Dibatalkan</a>
            </li>
          </ul>
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Tanggal Dipesan</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Qty</th>
                <th scope="col">Total Harga</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal Selesai</th>
                <th scope="col">Aksi</th>
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
                  <td>
                    <?php
                    if ($row['status'] == 'selesai' || $row['status'] == 'batal') {
                      echo formatDateTime($row['updated_at']);
                    } else {
                      echo "-";
                    }
                    ?>
                  </td>
                  <td>
                    <form method="post" action="../CRUD/transaction_cancel_user.php?id=<?= $row['id_transaksi']; ?>">
                      <button onclick="confirmcancel()" name="order-cancel-btn" class="btn btn-danger btn-sm" <?= $row['status'] == 'selesai' || $row['status'] == 'batal' ? 'disabled' : "" ?>><i class="fa fa-times"></i> Batalkan </button>
                    </form>
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
    function confirmcancel() {
      event.preventDefault(); // prevent form submit
      var form = event.target.form; // storing the form
      swal({
          title: "Konfirmasi Pembatalan Pesanan",
          text: "Apakah kamu yakin ingin membatalkan pesanan ini?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "red",
          cancelButtonText: "Tidak",
          confirmButtonText: "Yakin",
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

</body>

</html>