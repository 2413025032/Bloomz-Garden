<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status_login'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // CEK KE TABEL USER SAJA
    $cek_user = mysqli_query($conn, "SELECT * FROM user WHERE username = '" . $user . "'");

    if (mysqli_num_rows($cek_user) > 0) {
        $u = mysqli_fetch_object($cek_user);

        // Cocokkan password dengan password_hash
        if (password_verify($pass, $u->password)) {
            $_SESSION['status_login'] = true;
            $_SESSION['role'] = $u->role;
            $_SESSION['user_global'] = $u;
            $_SESSION['user_id'] = $u->id;

            echo '<script>window.location="index.php"</script>';
        } else {
            $error = "Password Salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Akun - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #ffe1f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            text-align: center;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            background: #ff96d7;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2 style="color: #ff4fb0;">Login</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit" name="login" class="btn">Masuk</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>

    </div>
</body>

</html>