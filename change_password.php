<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php
    require('koneksi.php');

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the submitted username
        $username = $_POST['currentUsername'];

        // Query the database to check if the username exists
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            // Username exists, proceed with changing the password
            $newPassword = md5($_POST['newPassword']);

            // Update the password in the database for the given username
            $updateQuery = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
            mysqli_query($con, $updateQuery);

            // Password changed successfully
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>';
            echo '<script>
        swal({
            title: "Success",
            text: "Password changed successfully!",
            icon: "success"
        }).then((result) => {
            console.log("SweetAlert confirmed:", result);

                console.log("Redirecting to akun.php");
                window.location.href = "akun.php";

        });
      </script>';
        } else {
            // Invalid username
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>';
            echo '<script>
                    swal({
                        title: "Error",
                        text: "Invalid username!",
                        icon: "error"
                    });
                  </script>';
        }
    }
    ?>




</body>

</html>