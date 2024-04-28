<?php
//menyertakan file program koneksi.php pada register
require('koneksi.php');

$error = '';
$validate = '';
if (isset($_COOKIE['username_user'])) header('Location: index.php');
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

    $no_tlp    = stripslashes($_POST['no_tlp']);
    $no_tlp    = mysqli_real_escape_string($con, $no_tlp);

    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);

    $gender   = stripslashes($_POST['gender']);
    $gender   = mysqli_real_escape_string($con, $gender);

    $city     = stripslashes($_POST['city']);
    $city     = mysqli_real_escape_string($con, $city);

    $repass   = stripslashes($_POST['repassword']);
    $repass   = mysqli_real_escape_string($con, $repass);
    //cek apakah nilai yang diinputkan pada form ada yang kosong atau tidak
    if (!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($gender)) && !empty(trim($city)) && !empty(trim($repass))) {
        //mengecek apakah password yang diinputkan sama dengan re-password yang diinputkan kembali
        if ($password == $repass) {
            //memanggil method cek_nama untuk mengecek apakah user sudah terdaftar atau belum
            if (cek_nama($name, $con) == 0) {
                // Hashing password using MD5 before storing it in the database
                $pass = md5($password);

                // Insert data into the database
                $query = "INSERT INTO users (username, name, email, password, gender, city, no_telepon) VALUES ('$username','$name','$email','$pass','$gender','$city','$no_tlp')";
                $result = mysqli_query($con, $query);

                if ($result) {
                    header('Location: login.php');
                } else {
                    $error =  'Register Admin Gagal !!';
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
    $query = "SELECT * FROM users WHERE username = '$nama'";
    if ($result = mysqli_query($con, $query)) return mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="img/relaxed.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- costum css -->
    <link rel="stylesheet" href="./css/style.css">
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

                    <label for="gender">Jenis Kelamin</label>
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="f">
                        <label class="form-check-label" for="female">
                            Wanita
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="m">
                        <label class="form-check-label" for="male">
                            Pria
                        </label>
                    </div>
                    <br>

                    <div class="form-floating mb-3">
                        <select class="form-control" id="city" name="city">
                            <option value="" disabled selected style="display: none;">~Pilih Kota~</option>
                            <option value="tangerang">Tangerang</option>
                            <option value="bekasi">Bekasi</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="surabaya">Surabaya</option>
                            <option value="solo">Solo</option>
                            <option value="malang">Malang</option>
                            <option value="bandaaceh">Banda Aceh</option>
                            <option value="palembang">Palembang</option>
                            <option value="padang">Padang</option>
                            <option value="jayapura">Jayapura</option>
                            <option value="makassar">Makassar</option>
                            <option value="manado">Manado</option>
                        </select>
                        <label for="city">Kota</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="InputEmail" name="email" aria-describeby="emailHelp" placeholder="Masukkan email">
                        <label for="InputEmail">Alamat Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="no_tlp" placeholder="Masukkan Nomor Telepon">
                        <label for="name">Nomor Telepon</label>
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