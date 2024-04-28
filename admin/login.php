<?php
//menyertakan file program koneksi.php pada register
require('../koneksi.php');
$error = '';
$validate = '';

//mengecek apakah sesssion username tersedia atau tidak jika tersedia maka akan diredirect ke halaman index
if (isset($_COOKIE['username_admin'])) header('Location: index.php');

//mengecek apakah form disubmit atau tidak
if (isset($_POST['submit'])) {

    // menghilangkan backshlases
    $username = stripslashes($_POST['username']);
    //cara sederhana mengamankan dari sql injection
    $username = mysqli_real_escape_string($con, $username);
    // menghilangkan backshlases
    $password = stripslashes($_POST['password']);
    //cara sederhana mengamankan dari sql injection
    $password = mysqli_real_escape_string($con, $password);

    //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
    if (!empty(trim($username)) && !empty(trim($password))) {

        //select data berdasarkan username dari database
        $query      = "SELECT * FROM admin WHERE username = '$username'";
        $result     = mysqli_query($con, $query);
        $rows       = mysqli_num_rows($result);

        if ($rows != 0) {
            $hash   = mysqli_fetch_assoc($result)['password'];
            if (password_verify($password, $hash)) {
                setcookie('username_admin', $username, time() + (86400 * 7));

                header('Location: index.php');
            }

            //jika gagal maka akan menampilkan pesan error
        } else {
            $error =  'Login User Gagal !!';
        }
    } else {
        $error =  'Data tidak boleh kosong !!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../img/relaxed.png">

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
        <section class="row justify-content-center ">
            <section class="col-12 col-sm-6 col-md-4">
                <form class="form-container" action="login.php" method="POST">
                    <h1 class="text-center font-weight-bold"> Welcome to Warung Santuy </h1>
                    <?php if ($error != '') { ?>
                        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                    <?php } ?>

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

                    <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
                    <div class="form-footer mt-2">
                        <p> Belum punya account? <a href="register.php">Register</a></p>
                    </div>
                </form>
            </section>
        </section>
    </section>

    <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>

</html>