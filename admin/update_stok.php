<?php
session_start();

include "../koneksi.php"; // Using database connection file here
//updating the quantity of the product
$stok = $_POST['stok'];
$id = $_POST['id'];
$type = $_POST['type'];

if ($type == 'kurang') {
  // cek apakah stok didalam database tidak akan minus ketika dikurang
  $cek = mysqli_query($con, "SELECT * FROM produk WHERE id='$id'");
  $data = mysqli_fetch_assoc($cek);
  if ($data['stok'] < $stok) {
    $alert = 'Gagal Merubah, Stok tidak boleh kurang dari nol!';
    $_SESSION['alert'] = $alert;
    header("location:index.php"); // redirects to all records page
    exit;
  }
  $update = mysqli_query($con, "UPDATE produk SET stok=stok-'$stok' WHERE id='$id'");
} else if ($type == 'tambah') {
  $update = mysqli_query($con, "UPDATE produk SET stok=stok+'$stok' WHERE id='$id'");
}
// die($update);
if ($update) {
  mysqli_close($con); // Close connection
  $alert = 'Data Stok Berhasil Diupdate!';
  $_SESSION['alert'] = $alert;
  header("location:index.php"); // redirects to all records page
  exit;
} else {
  $alert = 'Error updating record';
  $_SESSION['alert'] = $alert;
  header("location:index.php"); // redirects to all records page
  exit;
}
