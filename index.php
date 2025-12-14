<?php
session_start();
echo $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom'z Garden </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">Bloom'z Garden</div>

        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="produk.php">Produk</a></li>
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


    <section class="home" id="home">
        <div class="home-content">
            <div class="text">
                <div class="text-one"></div>
                <div class="text-two">Bloom'z Garden</div>
                <div class="text-three">Fresh and Artificial Flowers</div>
            </div>
        </div>
    </section>

    <!-- ===========================
         BEST SELLER OTOMATIS
    ============================ -->
    <section class="featured">
        <h2>Best Seller</h2>
        <div class="cards">

            <?php
            include "koneksi.php";

            // BEST SELLER OTOMATIS:
            // Ambil 3 produk dengan best_seller tertinggi dan stok masih ada
            $query = mysqli_query(
                $conn,
                "SELECT * FROM produk 
                    WHERE stok > 0
                    AND is_best_seller = 1
                    LIMIT 3"
            );


            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
            ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/<?php echo $row['images']; ?>"
                                alt="<?php echo $row['nm_produk']; ?>">
                        </div>

                        <div class="product-info">
                            <h3 class="product-title"><?php echo $row['nm_produk']; ?></h3>

                            <p class="product-category">
                                <?php echo ($row['id_kategori'] == 1) ? "Fresh" : "Artificial"; ?>
                            </p>

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
            <?php
                }
            } else {
                echo "<p style='text-align:center; width:100%;'>
                    Belum ada produk best seller atau stok habis.
                  </p>";
            }
            ?>
        </div>

        <a href="produk.php">
            <button style="cursor: pointer;; margin-top: 30px; background-color: #ff96d7; border: none;" class="btn-login">
                Lihat Selengkapnya
            </button>
        </a>
    </section>

    <!-- =========================== -->

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>

    <!-- Modal Zoom -->
    <div id="zoomModal" class="zoom-modal">
        <span class="zoom-close">&times;</span>
        <img class="zoom-content" id="zoomImage">
    </div>

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
            const modal = document.getElementById("zoomModal");
            const modalImg = document.getElementById("zoomImage");
            const closeBtn = document.querySelector(".zoom-close");

            const productImages = document.querySelectorAll(".product-img");

            productImages.forEach(img => {
                img.addEventListener("click", function() {
                    modal.style.display = "flex";
                    modalImg.src = this.src;
                });
            });

            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            modal.onclick = function(e) {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>

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