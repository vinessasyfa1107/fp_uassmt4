<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');

$error = '';
$validate = '';
if (isset($_COOKIE['username_admin'])) header('Location: index.php');
//mengecek apakah data username yang diinpukan user kosong atau tidak
if (isset($_POST['submit'])) {

    // menghilangkan backshlases
    $username = stripslashes($_POST['username']);
    //cara sederhana mengamankan dari sql injection
    $username = mysqli_real_escape_string($con, $username);
    $name     = stripslashes($_POST['name']);
    $name     = mysqli_real_escape_string($con, $name);
    $email    = stripslashes($_POST['email']);
    $email    = mysqli_real_escape_string($con, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $repass   = stripslashes($_POST['repassword']);
    $repass   = mysqli_real_escape_string($con, $repass);
    //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
    if (!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($repass))) {
        //mengecek apakah password yang diinputkan sama dengan re-password yang diinputkan kembali
        if ($password == $repass) {
            //memanggil method cek_nama untuk mengecek apakah user sudah terdaftar atau belum
            if (cek_nama($name, $con) == 0) {
                //hashing password sebelum disimpan didatabase
                $pass  = password_hash($password, PASSWORD_DEFAULT);
                //insert data ke database
                $query = "INSERT INTO admin (username,name,email, password ) VALUES ('$username','$nama','$email','$pass')";
                $result   = mysqli_query($con, $query);
                if ($result) {

                    header('Location: login.php');

                    //jika gagal maka akan menampilkan pesan error
                } else {
                    $error =  'Register User Gagal !!';
                }
            } else {
                $error =  'Username sudah terdaftar !!';
            }
        } else {
            $validate = 'Password tidak sama !!';
        }
    } else {
        $error =  'Data tidak boleh kosong !!';
    }
}

//fungsi untuk mengecek username apakah sudah terdaftar atau belum
function cek_nama($username, $con)
{
    $nama = mysqli_real_escape_string($con, $username);
    $query = "SELECT * FROM admin WHERE username = '$nama'";
    if ($result = mysqli_query($con, $query)) return mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../img/relaxed.png">
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- costum css -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* mengatur warna backgroud form */
        form {
            background: #fff;
        }

        /* mengatur border dan padding class form-container */
        .form-container {
            border-radius: 10px;
            padding: 30px;
        }
    </style>
</head>

<body style="padding-top: 10vh;">
    <section class="container-fluid mb-4">
        <!-- justify-content-center untuk mengatur posisi form agar berada di tengah-tengah -->
        <section class="row justify-content-center">
            <section class="col-12 col-sm-6 col-md-4">
                <form class="form-container" action="register.php" method="POST">
                    <h4 class="text-center font-weight-bold"> Sign-Up </h4>
                    <?php if ($error != '') { ?>
                        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama">
                        <label for="name">Nama</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="InputEmail" name="email" aria-describeby="emailHelp" placeholder="Masukkan email">
                        <label for="InputEmail">Alamat Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password">
                        <label for="InputPassword">Password</label>
                        <?php if ($validate != '') { ?>
                            <p class="text-danger"><?= $validate; ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="InputRePassword" name="repassword" placeholder="Re-Password">
                        <label for="InputPassword">Re-Password</label>
                        <?php if ($validate != '') { ?>
                            <p class="text-danger"><?= $validate; ?></p>
                        <?php } ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                    <div class="form-footer mt-2">
                        <p> Sudah punya account? <a href="login.php">Login</a></p>
                    </div>
                </form>
            </section>
        </section>
    </section>

    <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>