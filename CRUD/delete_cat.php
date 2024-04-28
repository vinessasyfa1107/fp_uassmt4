<?php
session_start();

include "../koneksi.php"; // Using database connection file here

$id = $_GET['id']; // get id through query string

$delC = mysqli_query($con, "delete from kategori where id_cat='" . $id . "'");

if ($delC) {
    mysqli_close($con); // Close connection
    $alert = 'Data Berhasil Dihapus!';
    $_SESSION['alert'] = $alert;

    header("location:../admin/category.php"); // redirects to all records page

    exit;
} else {
    $alert = 'Error deleting record';
    $_SESSION['alert'] = $alert;
    header("location:../admin/category.php"); // redirects to all records page

}
