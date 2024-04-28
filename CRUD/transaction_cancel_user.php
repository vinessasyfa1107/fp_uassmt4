<?php

session_start();

include "../koneksi.php"; // Using database connection file here

$id = $_GET['id']; // get id through query string

$query = "UPDATE transaksi SET status='batal' WHERE id_transaksi='$id'";
$result   = mysqli_query($con, $query);

if ($result) {
  $_SESSION['process-alert'] = "Status Berhasil Diubah!";

  header('Location: ../order/index.php');
} else {
  $_SESSION['process-alert'] = "Status gagal Diubah!";

  header('Location: ../order/index.php');
}
