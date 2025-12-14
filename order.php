<?php
session_start();
include "koneksi.php"; // koneksi database sederhana

// Jika belum login → tendang ke login
if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit;
}

// Jika role admin → tidak boleh akses
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: dashboard-admin.php");
    exit;
}

// ======================================================
// 1. KOSONGKAN ORDER
// ======================================================
if (isset($_GET['clear'])) {
    unset($_SESSION['pesanan']);
    unset($_SESSION['data_pembeli']);
    header("Location: order.php");
    exit();
}

// ======================================================
// 2. RESET SETELAH PESAN
// ======================================================
if (isset($_GET['done'])) {
    unset($_SESSION['pesanan']);
    unset($_SESSION['data_pembeli']);
    header("Location: order.php");
    exit();
}

// ======================================================
// 3. TAMBAH PRODUK KE KERANJANG
// ======================================================
if (!empty($_POST['product_name'])) {

    // Jika belum ada keranjang → buat
    if (!isset($_SESSION['pesanan'])) {
        $_SESSION['pesanan'] = [];
    }

    // Tambahkan produk ke dalam array
    $_SESSION['pesanan'][] = [
        "product_name"  => $_POST['product_name'],
        "product_price" => $_POST['product_price'],
        "quantity"      => $_POST['quantity'],
        "notes"         => $_POST['notes']
    ];

    // Data pembeli cukup disimpan sekali
    if (!empty($_POST['nama'])) {
        $_SESSION['data_pembeli'] = [
            "nama"      => $_POST['nama'],
            "alamat"    => $_POST['alamat'],
            "telp"      => $_POST['telp']
        ];
    }
}

// ======================================================
// 4. AMBIL DATA DARI SESSION
// ======================================================
$pesanan = isset($_SESSION['pesanan']) ? $_SESSION['pesanan'] : [];
$data_pembeli = isset($_SESSION['data_pembeli']) ? $_SESSION['data_pembeli'] : [];

// ======================================================
// 5. HITUNG TOTAL SEMUA PRODUK
// ======================================================
$total_all = 0;

foreach ($pesanan as $i) {
    $harga = preg_replace('/[^0-9]/', '', $i['product_price']);
    $total_all += ($harga * $i['quantity']);
}

$total_all_rp = "Rp " . number_format($total_all, 0, ',', '.');

// ======================================================
// 6. GENERATE WHATSAPP
// ======================================================
if (!empty($pesanan)) {

    $wa_msg = "Halo, saya ingin memesan:\n\n";

    foreach ($pesanan as $p) {
        $harga = preg_replace('/[^0-9]/', '', $p['product_price']);
        $subtotal = $harga * $p['quantity'];

        $wa_msg .= "- {$p['product_name']} x {$p['quantity']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
    }

    $wa_msg .= "\nTOTAL: $total_all_rp\n\n";

    if (!empty($data_pembeli)) {
        $wa_msg .= "Nama: {$data_pembeli['nama']}\n";
        $wa_msg .= "Alamat: {$data_pembeli['alamat']}\n";
        $wa_msg .= "Telp: {$data_pembeli['telp']}\n";
    }

    $wa_url = "https://api.whatsapp.com/send?phone=6283160324190&text=" . urlencode($wa_msg);
}

    $customer_id = $_SESSION['user_id'];

    if (isset($_GET['konfirmasi']) && !empty($pesanan)) {

        foreach ($pesanan as $p) {

            // Ubah harga format rupiah → angka
            $harga = preg_replace('/[^0-9]/', '', $p['product_price']);
            $total = $harga * $p['quantity'];

            // Insert ke tabel sesuai struktur table kamu
            mysqli_query($conn, "
                INSERT INTO orders
                (customer_id, product_name, product_price, quantity, total_price, notes,
                customer_name, customer_address, customer_phone)
                VALUES (
                    '$customer_id',
                    '{$p['product_name']}',
                    '$harga',
                    '{$p['quantity']}',
                    '$total',
                    '{$p['notes']}',
                    '{$data_pembeli['nama']}',
                    '{$data_pembeli['alamat']}',
                    '{$data_pembeli['telp']}'
                )
            ");
        }

    // Kosongkan keranjang setelah insert
    unset($_SESSION['pesanan']);
    unset($_SESSION['data_pembeli']);

    header("Location: order.php?sukses=1");
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Order - Bloom'z Garden</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="logo">Bloom'z Garden </div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php" >Produk</a></li>
                <li><a href="about.php">Tentang Kami</a></li>

                <?php if (isset($_SESSION['status_login'])): ?>

                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <li><a href="order.php" class="active">Order</a></li>
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

    <section class="order-confirmation">
        <div class="order-card">
            <div style="display: flex; justify-content: end; margin-bottom: 30px;">
                <a href="order.php?clear=1" class="btn-clear" style="
                        background:#c0392b;
                        padding:10px 15px;
                        color:#fff;
                        text-decoration:none;
                        border-radius:5px;
                    ">Kosongkan Order</a>
                <br><br>
            </div>

            <?php if (!empty($pesanan)): ?>

                <!-- DAFTAR PRODUK -->
                <h3>Produk Dipesan</h3>

                <?php foreach ($pesanan as $item): ?>
                    <?php
                    $price_clean = preg_replace('/[^0-9]/', '', $item['product_price']);
                    $total_item = (int)$price_clean * (int)$item['quantity'];
                    $total_item_formatted = "Rp " . number_format($total_item, 0, ',', '.');
                    ?>

                    <div class="detail-group">
                        <p><strong>Nama Produk:</strong> <?= $item['product_name']; ?></p>
                        <p><strong>Harga Satuan:</strong> <?= $item['product_price']; ?></p>
                        <p><strong>Kuantitas:</strong> <?= $item['quantity']; ?></p>
                        <p><strong>Catatan:</strong> <?= $item['notes']; ?></p>
                        <p><strong>Total Item:</strong> <span style="color:#d34ca7;"><?= $total_item_formatted; ?></span></p>
                        <hr>
                    </div>
                <?php endforeach; ?>

                <!-- TOTAL SEMUA -->
                <h3>Total Keseluruhan:</h3>
                <p style="font-size:20px;color:#d34ca7;"><strong><?= $total_all_rp; ?></strong></p>

                <!-- DATA PEMBELI -->
                <?php if (!empty($data_pembeli)): ?>
                    <div class="detail-group">
                        <h3>Data Pengiriman</h3>
                        <p><strong>Nama:</strong> <?= $data_pembeli['nama']; ?></p>
                        <p><strong>Alamat:</strong> <?= $data_pembeli['alamat']; ?></p>
                        <p><strong>No. Telp:</strong> <?= $data_pembeli['telp']; ?></p>
                    </div>
                <?php endif; ?>

                <!-- WHATSAPP BUTTON -->
                <a href="<?= $wa_url; ?>&redirect=<?= urlencode('order.php?konfirmasi=1'); ?>"
                    onclick="setTimeout(() => { window.location='order.php?konfirmasi=1'; }, 2000);"
                    class="wa-btn"
                    target="_blank">
                    Konfirmasi via WhatsApp
                </a>
            <?php else: ?>
                <h3>Tidak ada pesanan.</h3>
            <?php endif; ?>

            <a href="produk.php" class="wa-btn" style="background-color: #ff96d7;" target="_blank">Tambah Produk</a>


        </div>
    </section>

    <footer>
        <p>Jl. Kp. Baru, Bandar Lampung | 0831-6032-4190</p>
        <p>© 2025 Bloom'z Garden</p>
    </footer>

</body>

</html>