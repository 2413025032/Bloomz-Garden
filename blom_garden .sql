-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 12, 2025 at 07:45 AM
-- Server version: 9.4.0
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blom_garden`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nm_kategori` varchar(30) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nm_kategori`) VALUES
(1, 'Fresh'),
(2, 'Artifical');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` int NOT NULL,
  `notes` text,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `product_name`, `product_price`, `quantity`, `total_price`, `notes`, `customer_name`, `customer_address`, `customer_phone`) VALUES
(3, 11, 'Lily White Elegant', 70000, 1, 70000, 'ewrew', 'rew', 'rewr', '3443');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `id_kategori` int NOT NULL,
  `nm_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `is_best_seller` tinyint NOT NULL DEFAULT '0',
  `images` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `nm_produk`, `deskripsi`, `harga`, `stok`, `is_best_seller`, `images`) VALUES
(12, 1, '.Rose Bouquet Premium', 'Buket mawar segar premium dengan 12 tangkai, cocok untuk hadiah romantis.', 150000, 25, 1, 'img_693acb10743cf.jfif'),
(13, 1, 'Sunflower Single Fresh', 'Bunga matahari segar 1 tangkai dengan warna cerah dan tahan lama.', 35000, 40, 0, 'img_693acba2a9013.jfif'),
(14, 1, 'Lily White Elegant', 'Bunga lily putih segar dengan aroma wangi lembut.', 70000, 30, 1, 'img_693acbd2272e4.jfif'),
(15, 2, 'Artificial Rose Bouquet Soft Pink', 'Buket mawar artificial warna soft pink premium, tampilan sangat real.', 85000, 18, 0, 'img_693acc189f978.jpg'),
(16, 2, 'Artificial Sakura Branch', 'Cabang bunga sakura artificial untuk dekorasi ruangan.', 60000, 25, 1, 'img_693acc3716388.jpg'),
(17, 2, 'Artificial Lavender Pot', 'Tanaman lavender artificial dalam pot minimalis.', 55000, 30, 0, 'img_693acc4e24c77.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('customer','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'customer',
  `username` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `No_telp` int NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama_lengkap`, `role`, `username`, `password`, `No_telp`, `alamat`) VALUES
(5, 'shania afni', 'customer', 'shania', '$2y$10$ftpVe5bTnotGKoGIml9YDueKGZJOf8kQeQHayCptYJAzNepz.7bOe', 2147483647, 'metro'),
(10, 'admin', 'admin', 'admin', '$2y$12$9BwPU2yNOk2Q3WzcEz8IwOVoYIBNL0ctVdv9v81B178ZMkFS5.SVe', 72323424, '3424234'),
(11, 'neymar', 'customer', 'neymar', '$2y$12$T0Ab3.pyJ591Ijh9kWej1.eAlQhOXurLwNtXwzC5fs3wpC6oxFBFK', 9709, '23432542'),
(12, 'pedri', 'customer', 'pedri', '$2y$12$eoPZEijiu9a0HX6TQxQF6O9lSO5OmT8Z7uug1S0Pn7aoiJ6H4zj1i', 9999, '23532532');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_customer` (`customer_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
