<?php
session_start();
include "koneksi.php"; // pastikan file ini ada
// Cek apakah user sudah login DAN role-nya admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
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
                        <li><a href="dashboard-customer.php" >Dashboard Customer</a></li>

                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="dashboard-admin.php"  class="active">Dashboard Admin</a></li>
                    <?php endif; ?>

                    <li><a href="logout.php" class="btn-logout">Logout</a></li>

                <?php else: ?>

                    <li><a href="login.php" class="btn-login">Login</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="" style="width: 100%; margin-top: 150px; display: flex; justify-content: center; gap: 25px;">
        <a class="section-dashboard " href="dashboard-admin.php">
            Products
        </a>
        <a class="section-dashboard active-dashboard" href="dashboard-admin-order.php">
            Orders
        </a>
    </div>

    <section class="dashboard">

        <h2 style="text-align:center; margin-bottom:20px;">Daftar Order</h2>

        <?php
        // Query mengambil semua data order
        $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
        ?>

        <table border="1" cellpadding="10" cellspacing="0" style="width:90%; margin:auto; background:#fff;">
            <tr style="background:#d34ca7; color:white;">
                <th>ID</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Nama Customer</th>
                <th>Alamat</th>
                <th>Telp</th>
            </tr>

            <?php
            $no = 1; // nomor urut mulai dari 1
            while ($row = mysqli_fetch_assoc($result)) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['product_name']; ?></td>
                    <td>Rp <?= number_format($row['product_price'], 0, ',', '.'); ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td>Rp <?= number_format($row['total_price'], 0, ',', '.'); ?></td>
                    <td><?= $row['customer_name']; ?></td>
                    <td><?= $row['customer_address']; ?></td>
                    <td><?= $row['customer_phone']; ?></td>
                </tr>
            <?php endwhile; ?>


        </table>

    </section>

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>

</body>

</html>