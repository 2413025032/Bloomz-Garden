<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "blom_garden"; // ganti sesuai nama database kamu
$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>