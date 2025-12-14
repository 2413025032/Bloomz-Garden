<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login DAN role-nya admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// HANDLE CREATE PRODUCT
if (isset($_POST['save'])) {
    $id_kategori = $_POST['id_kategori'];
    $nm_produk   = $_POST['nm_produk'];
    $deskripsi   = $_POST['deskripsi'];
    $harga       = $_POST['harga'];
    $is_best_seller       = $_POST['is_best_seller'];
    $stok        = $_POST['stok'];

    // === HANDLE UPLOAD GAMBAR ===
    $fileName = $_FILES['images']['name'];
    $tmpName  = $_FILES['images']['tmp_name'];

    // ambil ekstensi file
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    // buat nama random
    $newName = uniqid("img_") . "." . strtolower($ext);

    // folder simpan
    $uploadPath = "images/" . $newName;

    // upload file ke folder
    move_uploaded_file($tmpName, $uploadPath);

    // INSERT ke database
    $insert = mysqli_query($conn, "INSERT INTO produk 
        (id_kategori, nm_produk, deskripsi, harga, stok, is_best_seller, images)
        VALUES 
        ('$id_kategori', '$nm_produk', '$deskripsi', '$harga', '$stok', '$is_best_seller' , '$newName')
    ");

    if ($insert) {
        echo "<script>
                alert('Produk berhasil ditambahkan');
                window.location='dashboard-admin.php';
            </script>";
        exit();
    } else {
        echo "<script>alert('Gagal menambah produk');</script>";
    }
}

// HANDLE DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // 1. Ambil data produk
    $q = mysqli_query($conn, "SELECT images FROM produk WHERE id_produk = '$id'");
    $p = mysqli_fetch_assoc($q);

    // 2. Hapus file jika ada
    $filePath = "images/" . $p['images'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // 3. Hapus data di database
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");

    // 4. Redirect kembali
    header("Location: dashboard-admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="logo">Bloom'z Garden</div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="about.php">Tentang Kami</a></li>

                <?php if (isset($_SESSION['status_login'])): ?>

                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <li><a href="order.php">Order</a></li>
                        <li><a href="dashboard-customer.php">Dashboard Customer</a></li>

                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="dashboard-admin.php" class="active">Dashboard Admin</a></li>
                    <?php endif; ?>

                    <li><a href="logout.php" class="btn-logout">Logout</a></li>

                <?php else: ?>

                    <li><a href="login.php" class="btn-login">Login</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <div class="" style="width: 100%; margin-top: 150px; display: flex; justify-content: center; gap: 25px;">
        <a class="section-dashboard active-dashboard" href="dashboard-admin.php">
            Products
        </a>
        <a class="section-dashboard" href="dashboard-admin-order.php">
            Orders
        </a>
    </div>


    <section class="dashboard">

        <center>
            <p class="section-title" style=" margin-top: 30px 0 100px; background-color: #ff96d7; border: none;" class="">
                Tambah Produk
            </p>
        </center>

        <form method="POST" class="form-produk" enctype="multipart/form-data">

            <label>Kategori</label>
            <select name="id_kategori" required>
                <option value="">--Pilih Kategori--</option>
                <option value="1">Fresh</option>
                <option value="2">Artificial</option>
            </select>

            <label>Nama Produk</label>
            <input type="text" name="nm_produk" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" required></textarea>

            <label>Harga</label>
            <input type="number" name="harga" required>

            <label>Stok</label>
            <input type="number" name="stok" required>

            <label>Best Seller</label>
            <select name="is_best_seller">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <label>Image</label>
            <input type="file" name="images" required>

            <button type="submit" name="save">Simpan Produk</button>
        </form>

        <hr><br>

        <center>
            <p class="section-title" style=" margin-top: 30px 0 100px; background-color: #ff96d7; border: none;" class="">
                Daftar Produk
            </p>
        </center>

        <table border="1" width="100%" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Best Seller</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>

            <?php
            $produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
            $no = 1; // inisialisasi counter
            while ($p = mysqli_fetch_assoc($produk)):
            ?>
                <tr>
                    <td><?= $no ?></td> <!-- nomor urut -->
                    <td>
                        <?= $p['id_kategori'] == 1 ? "Fresh" : "Artificial" ?>
                    </td>
                    <td><?= $p['nm_produk'] ?></td>
                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td><?= $p['stok'] ?></td>
                    <td><?= $p['is_best_seller'] == 1 ? "Yes" : "No" ?></td>
                    <td><img src="images/<?= $p['images'] ?>" width="60"></td>
                    <td>
                        <a href="dashboard-admin.php?delete=<?= $p['id_produk'] ?>"
                            onclick="return confirm('Hapus produk ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
                $no++; // increment counter
            endwhile;
            ?>

        </table>

    </section>

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>

</body>

</html>