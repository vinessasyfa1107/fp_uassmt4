<?php
require('koneksi.php');

if (!isset($_COOKIE['username_user'])) header('Location: login_user.php');

if (isset($_POST['buyNow'])) {
    $id_user = $_POST['id_user'];
    $id_produk = $_POST['id_produk'];

    // Perform the insertion into the "transaksi" table
    $insertQuery = "INSERT INTO transaksi (id_user, id_produk, timestamp) VALUES ('$id_user', '$id_produk', NOW())";
    mysqli_query($con, $insertQuery);

    // Update the stock of the product
    $updateQuery = "UPDATE produk SET stock = stock - 1 WHERE id = '$id_produk'";
    mysqli_query($con, $updateQuery);

    // Redirect to a success page or perform any other necessary actions
    header("Location: index.php");
    exit();
}
