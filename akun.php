<!DOCTYPE html>
<html>

<head>
    <title>My App</title>
    <link rel="stylesheet" href="css/akun.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/user.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        #alertContainer {
            color: red;
            margin: 5px 10px;
        }
    </style>
</head>

<body>
    <?php
    require('koneksi.php');
    $error = '';

    if (!isset($_COOKIE['username_user'])) header('Location: login_user.php');
    $username = $_COOKIE['username_user'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    // Function to update email
    function updateEmail($newEmail, $con, $username)
    {
        $updateQuery = "UPDATE users SET email = '$newEmail' WHERE username = '$username'";
        mysqli_query($con, $updateQuery);
    }

    // Function to update phone number
    function updatePhoneNumber($newPhoneNumber, $con, $username)
    {

        $updateQuery = "UPDATE users SET no_telepon = '$newPhoneNumber' WHERE username = '$username'";
        mysqli_query($con, $updateQuery);
    }

    // Check if the form is submitted for updating email
    if (isset($_POST['updateEmail'])) {
        $newEmail = $_POST['newEmail'];
        updateEmail($newEmail, $con, $username);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }

    // Check if the form is submitted for updating phone number
    if (isset($_POST['updatePhoneNumber'])) {
        $newPhoneNumber = $_POST['newPhoneNumber'];
        updatePhoneNumber($newPhoneNumber, $con, $username);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
    //ngecek passwordnya sama ga
    if (isset($_POST['checkPassword'])) {
        $password = $_POST['currentPassword'];
        $hash   = mysqli_fetch_assoc($result)['password'];
        if (!password_verify($password, $hash)) {
            $error = "Kata Sandi Salah";
        }
    }

    // if (isset($_POST['updatePassword'])) {
    //
    // }
    ?>

    <div class="sidebar">
        <div class="topSidebar">
            <img src="img/relaxed.png" alt="image" class="logoWeb">
            <p>Warung Santuy</p>
        </div>
        <ul class="side">
            <li class="dashboardList">
                <a href="index.php">
                    <img src="img/dashboardLogo.png" alt="image" class="menuLogo dashboard">
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="dashboardList pesan">
                <a href="order/index.php">
                    <img src="img/pesananLogo.png" alt="image" class="menuLogo dashboard">
                    <span>Pesanan Saya</span>
                </a>
            </li>
            <li class="dashboardList active">
                <a href="akun.php">
                    <img src="img/pengaturanLogo.png" alt="image" class="menuLogo dashboard">
                    <p>Akun</p>
                </a>
            </li>
        </ul>
    </div>
    <div class="contentContainer">
        <header>
            <p>Hi, <?= $_COOKIE['username_user'] ?> </p>
            <p>|</p>
            <a href="logout.php"><img src="img/keluar.png" alt="image" class="keluar"></a>
        </header>
        <div class="content">
            <div class="headAkun">
            </div>
            <br><br>
            <div class="contentAkun">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="telpon">
                        <div class="flex">
                            <div><img src="img/telepon.png" alt="image"></div>
                            <p class="content2">Telepon</p>
                        </div>
                        <p class="content3"><?= $row['no_telepon'] ?></p>
                        <button class="ubah" onclick="openModal1()">Ubah</button>
                    </div>
                    <div class="telpon2">
                        <div class="flex">
                            <div> <img src="img/email.png" alt="image"></div>
                            <p class="content2">Email</p>
                        </div>
                        <p class="content3">&nbsp; &nbsp;<?= $row['email'] ?></p>
                        <button class="ubah" onclick="openModal2()">Ubah</button>
                    </div>
                    <div class="telpon3">
                        <div class="flex">
                            <div><img src="img/password.png" alt="image"></div>
                            <p class="content2">Katasandi Akun</p>
                        </div>
                        <p class="content3">Harap diganti katasandi secara berkala</p>
                        <button class="ubah" onclick="openModal()">Ubah</button>
                    </div>
                <?php } ?>
            </div>
            <div id="myModal2" class="modal">
                <div class="modal-content">
                    <form method="post" action="">
                        <br>
                        <p>Email</p>
                        <input type="email" name="newEmail" placeholder="Email" required>
                        <div class="modal-buttons">
                            <button type="submit" name="updateEmail" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="myModal1" class="modal">
                <div class="modal-content">
                    <form method="post" action="">
                        <br>
                        <p>Nomor Telepon</p>
                        <input type="tel" name="newPhoneNumber" placeholder="Nomor Telepon" required>
                        <div class="modal-buttons">
                            <button type="submit" name="updatePhoneNumber" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- First Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <br>
                    <form id="modalForm1" onsubmit="submitModal(event)">
                        <p>Username Saat Ini</p>
                        <input type="text" name="currentUsername" id="currentUsername" placeholder="Username" required>
                        <div class="modal-buttons">
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Second Modal -->
            <div id="myModal3" class="modal">
                <div class="modal-content">
                    <br>
                    <form id="modalForm3" method="post" action="change_password.php" onsubmit="return validateForm()">
                        <input type="hidden" name="currentUsername" id="hiddenUsername">
                        <p>Kata Sandi Baru</p>
                        <input type="password" name="newPassword" id="newPassword" placeholder="Katasandi" required>
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Konfirmasi katasandi" required>
                        <div id="alertContainer"></div>
                        <div class="modal-buttons">
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </form>
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
            <!-- Existing JavaScript code for modals -->
            <script>
                function validateForm() {
                    var newPassword = document.getElementById("newPassword").value;
                    var confirmPassword = document.getElementById("confirmPassword").value;

                    if (newPassword !== confirmPassword) {
                        var alertContainer = document.getElementById("alertContainer");
                        alertContainer.innerHTML = '<div class="alert alert-danger" role="alert">&#42 Katasandi dan Konfirmasi katasandi harus sama.</div>';
                        return false;
                    }

                    return true;
                }

                function openModal() {
                    document.getElementById("myModal").style.display = "block";
                }

                function closeModal() {
                    document.getElementById("myModal").style.display = "none";
                }

                function submitModal(event) {
                    event.preventDefault(); // Prevent form submission

                    // Get the current username
                    var currentUsername = document.getElementById("currentUsername").value;

                    // Set the username in the hidden input field of the second form
                    document.getElementById("hiddenUsername").value = currentUsername;

                    // Close both modals
                    closeModal();
                    openModal3();
                }

                function openModal3() {
                    document.getElementById("myModal3").style.display = "block";
                }

                function closeModal3() {
                    document.getElementById("myModal3").style.display = "none";
                }


                function openModal1() {
                    document.getElementById("myModal1").style.display = "block";
                }

                function closeModal1() {
                    document.getElementById("myModal1").style.display = "none";
                }

                function submitModal1() {
                    // Perform actions on modal submit
                    // You can add your custom logic here
                    closeModal1();
                }

                function openModal2() {
                    document.getElementById("myModal2").style.display = "block";
                }

                function closeModal2() {
                    document.getElementById("myModal2").style.display = "none";
                }

                function submitModal2() {
                    // Perform actions on modal submit
                    // You can add your custom logic here
                    closeModal2();
                }
            </script>
        </div>

</body>

</html>