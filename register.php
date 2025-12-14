<?php
include 'koneksi.php';

session_start();

if (isset($_SESSION['status_login'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Enkripsi password
    $telp = $_POST['No_telp'];
    $alamat = $_POST['alamat'];
    $role = "";
    if ($user == 'admin') {
        $role = "admin";
    } else {
        $role = "customer";
    }

    $insert = mysqli_query($conn, "INSERT INTO user VALUES (NULL, '$nama','$role', '$user', '$pass', '$telp', '$alamat')");
    
    if($insert){
        echo '<script>alert("Pendaftaran Berhasil! Silakan Login."); window.location="login.php";</script>';
    } else {
        echo '<script>alert("Gagal Daftar!");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #ffe1f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 10px; width: 350px; text-align: center; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;}
        .btn { background: #ff96d7; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="color: #ff4fb0;">Daftar Akun</h2>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <input type="text" name="No_telp" placeholder="No. WhatsApp" required>
            <textarea name="alamat" placeholder="Alamat Lengkap" required></textarea>
            <button type="submit" name="register" class="btn">Daftar Sekarang</button>
        </form>
        <p style="margin-top: 10px;">Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>
</html>