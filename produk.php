<?php
session_start();
include "koneksi.php";

// Query ambil semua produk
$fresh = mysqli_query($conn, "SELECT * FROM produk WHERE id_kategori = 1 ORDER BY id_produk DESC");
$artificial = mysqli_query($conn, "SELECT * FROM produk WHERE id_kategori = 2 ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <header>
        <div class="logo">Bloom'z Garden</div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php" class="active">Produk</a></li>
                <li><a href="about.php">Tentang Kami</a></li>

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


    <section class="produk">
        <h2>Katalog Produk Kami</h2>
        <p class="desc">Temukan koleksi bunga segar & artificial terbaik 💐</p>

        <p class="section-title" style=" margin-top: 30px; background-color: #ff96d7; border: none;" class="">
            Fresh
        </p>

        <div class="cards">
            <?php while ($row = mysqli_fetch_assoc($fresh)) { ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/<?php echo $row['images']; ?>"
                            alt="<?php echo $row['nm_produk']; ?>">
                    </div>

                    <div class="product-info">
                        <h3 class="product-title"><?php echo $row['nm_produk']; ?></h3>
                        <p class="product-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    </div>
                    <?php if (isset($_SESSION['status_login']) && $_SESSION['role'] !== 'admin'): ?>
                        <button class="btn-buy"
                            data-nama="<?php echo $row['nm_produk']; ?>"
                            data-harga="<?php echo $row['harga']; ?>">
                            Beli Sekarang
                        </button>
                    <?php endif; ?>

                </div>
            <?php } ?>
        </div>

        <p class="section-title" style=" margin-top: 70px; background-color: #ff96d7; border: none;" class="">
            Artificial
        </p>

        <div class="cards">
            <?php while ($row = mysqli_fetch_assoc($artificial)) { ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/<?php echo $row['images']; ?>"
                            alt="<?php echo $row['nm_produk']; ?>">
                    </div>

                    <div class="product-info">
                        <h3 class="product-title"><?php echo $row['nm_produk']; ?></h3>
                        <p class="product-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    </div>
                    <?php if (isset($_SESSION['status_login']) && $_SESSION['role'] !== 'admin'): ?>
                        <button class="btn-buy"
                            data-nama="<?php echo $row['nm_produk']; ?>"
                            data-harga="<?php echo $row['harga']; ?>">
                            Beli Sekarang
                        </button>
                    <?php endif; ?>

                </div>
            <?php } ?>
        </div>

    </section>

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>


    <!-- Modal Order -->
    <div id="orderModal" class="order-modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Detail Pesanan Anda</h2>

            <div class="product-summary">
                <p>Produk: <strong id="modal-product-name"></strong></p>
                <p>Harga: <strong id="modal-product-price"></strong></p>
            </div>

            <form action="order.php" method="POST">
                <input type="hidden" id="form-product-name" name="product_name">
                <input type="hidden" id="form-product-price" name="product_price">

                <label>Kuantitas</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1">

                <label>Catatan</label>
                <textarea id="notes" name="notes"></textarea>

                <label>Nama</label>
                <input type="text" id="nama" name="nama" required>

                <label>Alamat</label>
                <textarea id="alamat" name="alamat" required></textarea>

                <label>No. WhatsApp</label>
                <input type="text" id="telp" name="telp" required>

                <button type="submit" class="submit-order-btn">Pesan via WA</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const orderModal = document.getElementById("orderModal");
            const closeBtn = document.querySelector(".close-btn");

            const modalName = document.getElementById("modal-product-name");
            const modalPrice = document.getElementById("modal-product-price");

            const formName = document.getElementById("form-product-name");
            const formPrice = document.getElementById("form-product-price");

            document.querySelectorAll(".btn-buy").forEach(btn => {
                btn.addEventListener("click", function() {

                    const nama = this.dataset.nama;
                    const harga = this.dataset.harga;

                    modalName.textContent = nama;
                    modalPrice.textContent = "Rp " + harga;

                    formName.value = nama;
                    formPrice.value = harga;

                    orderModal.style.display = "flex";
                });
            });

            closeBtn.addEventListener("click", () => orderModal.style.display = "none");

            window.onclick = function(e) {
                if (e.target === orderModal) {
                    orderModal.style.display = "none";
                }
            }
        });
    </script>

</body>

</html>