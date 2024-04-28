<?php
    if(setcookie('username_admin', "", time() - 3600)) 
    //mengatur nilai cookie menjadi kosong dan mengatur waktu kadaluarsa ke satu jam sebelumnya
    {
        header("Location: login.php"); //jika berhasil maka akan diredirect ke file login_user.php
    }
?>