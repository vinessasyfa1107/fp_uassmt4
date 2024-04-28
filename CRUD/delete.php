<?php
session_start();

include "../koneksi.php"; // Using database connection file here

$id = $_GET['id']; // get id through query string

$del = mysqli_query($con, "delete from produk where id = '$id'"); // delete query

if ($del) {
    mysqli_close($con); // Close connection
    $alert = 'Data Berhasil Dihapus!';
    $_SESSION['alert'] = $alert;
    header("location:../admin/index.php"); // redirects to all records page

    exit;
} else {
    $alert = 'Error deleting record';
    $_SESSION['alert'] = $alert;
}
session_destroy();
