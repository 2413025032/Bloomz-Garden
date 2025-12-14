<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
        <div class="logo">Bloom'z Garden</div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php" >Produk</a></li>
                <li><a href="about.php" class="active">Tentang Kami</a></li>

                <?php if (isset($_SESSION['status_login'])): ?>

                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <li><a href="order.php">Order</a></li>
                        <li><a href="dashboard-customer.php">Dashboard Customer</a></li>

                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="dashboard-admin.php">Dashboard Admin</a></li>
                    <?php endif; ?>

                    <li><a href="logout.php" class="btn-logout">Logout</a></li>

                <?php else: ?>

                    <li><a href="login.php" class="btn-login">Login</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="about">
        <h2>Tentang Kami</h2>

        <div class="about-container">
            <div class="about-text">
                <h3>Bloom'z Garden</h3>
                <p>Bloom'z Garden adalah toko tanaman hias yang berdiri sejak tahun 2025. Kami menyediakan berbagai jenis tanaman seperti tanaman outdoor, indoor, bunga segar, hingga pot dan perlengkapan berkebun lainnya.</p>

                <p>Kami percaya bahwa tanaman bukan hanya hiasan, tetapi juga sahabat yang dapat menghadirkan ketenangan dan suasana nyaman di setiap tempat. Dengan pelayanan ramah, harga terjangkau, dan kualitas terbaik, kami berkomitmen membantu setiap pelanggan menemukan tanaman yang tepat.</p>

                <p>Terima kasih telah mengunjungi Bloom'z Garden. Mari wujudkan lingkungan hijau bersama!</p>
            </div>

            <div>
                <img src="assets/foto.jpeg" alt="tokobunga" width="400">
            </div>
        </div>
    </section>

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>

</body>

</html>