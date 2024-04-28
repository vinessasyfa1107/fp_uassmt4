<?php

session_start();

include "../koneksi.php"; // Using database connection file here

$id_user = $_POST['id_user'];
$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$qty = $_POST['qty'];
$total_harga = $_POST['total_harga'];

$sql = "INSERT INTO transaksi (id_user, id_produk, nama_produk, qty, total_harga) VALUES ('$id_user', '$id_produk', '$nama_produk', '$qty', '$total_harga')";
// die($sql);
$query = mysqli_query($con, $sql);

if ($query) {
  $_SESSION['status'] = "<b>Berhasil memproses pesanan kamu!</b> Cek status pesanan kamu di menu pesanan ya!";
  header('Location: ../index.php');
} else {
  $_SESSION['status'] = "Gagal memproses pesanan kamu!";
  header('Location: ../index.php');
}
