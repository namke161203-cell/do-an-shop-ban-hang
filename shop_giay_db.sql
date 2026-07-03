-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 05:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_giay_db`
--
CREATE DATABASE IF NOT EXISTS `shop_giay_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop_giay_db`;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`) VALUES
(1, 'Nike', NULL),
(2, 'Adidas', NULL),
(3, 'Mizuno', NULL),
(4, 'Puma', NULL),
(6, 'Apple Việt Nam', NULL),
(7, 'Apple DSDS', NULL),
(8, 'Apple DSDS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `size`, `created_at`) VALUES
(6, 6, 1, 1, '40', '2026-01-08 19:19:22'),
(13, 4, 5, 1, '40', '2026-01-30 13:41:26'),
(14, 1, 5, 2, 'XL', '2026-04-18 08:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Giày Cỏ Nhân Tạo (TF)', NULL),
(2, 'Giày Cỏ Tự Nhiên (FG)', NULL),
(4, 'Giày Futsal', NULL),
(5, 'Phụ kiện', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL,
  `total_money` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `fullname`, `phone`, `address`, `note`, `total_money`, `status`, `created_at`, `payment_method`) VALUES
(1, 3, 'hai nam', '112', 'ni', '', 3450000.00, 'cancelled', '2025-12-26 09:12:22', 'COD'),
(2, 3, 'hai nam', '112', 'ni', '', 4800000.00, 'cancelled', '2025-12-26 14:44:53', 'COD'),
(3, 1, 'Admin Quản Trị', '112', 'ni', '', 3700000.00, 'completed', '2025-12-26 15:13:18', 'COD'),
(4, 4, 'nam', '123', '22', '', 1600000.00, 'completed', '2025-12-27 06:44:39', 'COD'),
(5, 4, 'nam', '123', '2222', '', 1600000.00, 'cancelled', '2025-12-27 07:02:40', 'COD'),
(6, 4, 'nam', '123', '345\r\n', '', 1600000.00, 'cancelled', '2025-12-27 09:06:13', 'COD'),
(7, 4, 'nam', '123', '11', '', 1600000.00, 'completed', '2025-12-27 09:11:07', 'COD'),
(8, 4, 'nam', '112', 'qq', '', 1850000.00, 'cancelled', '2025-12-27 09:13:54', 'COD'),
(9, 4, 'nam', '123', '222', '', 1600000.00, 'completed', '2025-12-27 09:19:05', 'COD'),
(10, 4, 'nam', '123', '222', '', 1600000.00, 'cancelled', '2025-12-27 09:25:20', 'COD'),
(11, 4, 'nam', '22', '2222', '', 2200000.00, 'completed', '2025-12-27 09:29:30', 'BANKING'),
(12, 4, 'nam', '112', 'qq', 'nb', 1850000.00, 'cancelled', '2025-12-27 14:19:02', 'COD'),
(13, 4, 'nam', '123', 'ss', '', 2350000.00, 'completed', '2025-12-27 17:42:51', 'BANKING'),
(14, 4, 'nam', '123', 'w', 'ư', 1850000.00, 'completed', '2025-12-27 17:43:11', 'BANKING'),
(15, 6, 'Đinh Hải Nam', '22', '3', '', 1850000.00, 'cancelled', '2025-12-29 02:26:46', 'BANKING'),
(16, 6, 'Đinh Hải Nam', '111', '111', '', 2350000.00, 'completed', '2025-12-29 06:11:37', 'BANKING'),
(17, 6, 'Đinh Hải Nam', '22', 'nnnn', '', 1850000.00, 'completed', '2026-01-12 05:03:56', 'COD'),
(18, 7, 'Hải Nam', '0332755735', 'Ninh Bình', '', 5790000.00, 'completed', '2026-01-12 05:58:56', 'COD'),
(19, 5, 'Hồng Hải', '22', 'nnnn', '', 5790000.00, 'cancelled', '2026-01-13 15:07:23', 'BANKING'),
(20, 1, 'Admin Quản Trị', '2401264845', 'nnnn', '', 17370000.00, 'cancelled', '2026-01-13 17:15:16', 'COD'),
(21, 5, 'Hồng Hải', '2401264845', 'nnnn', '', 11580000.00, 'completed', '2026-01-13 17:16:05', 'COD'),
(22, 4, 'nam', '123', 'hanoi', '', 1850000.00, 'pending', '2026-01-16 00:45:43', 'COD'),
(23, 1, 'Nguyen An', '098765432', 'Ha Noi', 'Giao nhanh', 500000.00, 'pending', '2026-04-18 08:00:35', 'COD'),
(25, 1, 'Nguyễn Văn A', '0987654321', '123 Đường ABC, Quận XYZ, TP HCM', 'Giao hàng trong giờ hành chính', 500000.00, 'pending', '2026-06-16 14:16:47', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(1, 1, 1, 40, 1, 1850000.00),
(2, 1, 2, 41, 1, 1600000.00),
(3, 2, 2, 40, 3, 1600000.00),
(4, 3, 1, 39, 2, 1850000.00),
(5, 4, 2, 40, 1, 1600000.00),
(6, 5, 2, 40, 1, 1600000.00),
(7, 6, 2, 40, 1, 1600000.00),
(8, 7, 2, 41, 1, 1600000.00),
(9, 8, 1, 39, 1, 1850000.00),
(10, 9, 2, 41, 1, 1600000.00),
(11, 11, 3, 40, 1, 2200000.00),
(12, 12, 1, 39, 1, 1850000.00),
(13, 13, 5, 39, 1, 2350000.00),
(14, 14, 1, 39, 1, 1850000.00),
(15, 15, 1, 40, 1, 1850000.00),
(16, 16, 5, 39, 1, 2350000.00),
(17, 17, 1, 40, 1, 1850000.00),
(18, 18, 4, 40, 1, 5790000.00),
(19, 19, 4, 40, 1, 5790000.00),
(20, 20, 4, 39, 2, 5790000.00),
(21, 20, 4, 40, 1, 5790000.00),
(22, 21, 4, 39, 1, 5790000.00),
(23, 21, 4, 40, 1, 5790000.00),
(24, 22, 1, 40, 1, 1850000.00),
(25, 23, 1, 0, 1, 500000.00),
(28, 25, 1, 0, 2, 150000.00),
(29, 25, 2, 0, 1, 200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(3, 'namke161203@gmail.com', '32f1ac20db7044502ddbe0850bfa4039', '2026-01-15 17:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sale_price` decimal(10,2) DEFAULT 0.00,
  `sale_start` datetime DEFAULT NULL,
  `sale_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `price`, `old_price`, `image`, `description`, `views`, `created_at`, `sale_price`, `sale_start`, `sale_end`) VALUES
(1, 1, 1, 'Giày đá bóng Nike Air Zoom Mercurial Superfly 9 Academy TF Mad Brilliance - Pink Foam/Black DJ5629-601', 1850000.00, 2000000.00, 'assets/uploads/1766947035_anh_sp_add-01-01-01-04-07173_b2f507e560034980bc664a712deefce5_1024x1024.jpg', 'Nhãn Hiệu: NIKE\r\nSKU: DJ5629-601-36', 0, '2025-12-26 08:28:50', 160000.00, '2026-01-09 01:41:00', '2026-01-09 03:56:00'),
(2, 1, 2, 'adidas Predator Accuracy .3 TF Crazyrush - Footwear White/Core Black/Lucid Lemon', 1600000.00, 0.00, 'assets/uploads/1766947074_anh_sp_add_web_3puma-02-02-02-01_f2737239f73e41bc8d6329a781516657_1024x1024.jpg', 'Nhãn Hiệu: ADIDAS\r\nSKU: GZ0004-40', 1, '2025-12-26 08:28:50', 0.00, NULL, NULL),
(3, 1, 3, 'Mizuno Morelia Sala Pro AS TF - White/Gold Q1GB261350', 2200000.00, 2600000.00, 'assets/uploads/1766947110_anh_sp_add77-017778901-53-2_21be6ade7f954301bfb6fa4cf3adf4f5_1024x1024.jpg', 'Nhãn Hiệu: MIZUNO\r\nSKU: Q1GB261350-38.5', 0, '2025-12-26 08:28:50', 0.00, NULL, NULL),
(4, 2, 1, 'Nike Tiempo Legend 10 Elite FG United - Vast Grey/Racer Blue IB2467-001', 5790000.00, 7739000.00, 'assets/uploads/1766847491_2200002124126-2-2_d8828f6b03914f0fbd912e338811a0e6_1024x1024.jpg', 'Những cầu thủ nổi tiếng đại diện: F. De Jong, Van Dijk và Jordan Henderson.....\r\nBộ Sưu Tập: United 002\r\nNăm sản xuất: 2025.\r\nChất liệu: FlyTouch Plus - da tổng hợp thay thế da K.\r\nCông nghệ: Cổ thun Flyknit ôm chân, khuôn đế thiết kế mới bám sân, lót giày Nike-Grip chống trượt và đường kẻ Microdots giúp người chơi kiểm soát tốt hơn.\r\nTrọng lượng: 205 gram/chiếc (Size 41).\r\nPhong cách: Kiểm soát, kỹ thuật.\r\nVị trí: Hậu vệ, tiền vệ trung tâm.\r\nForm giày: Tương đối thoải mái, phù hợp chân bè.\r\nMặt sân: Cỏ tự nhiên 11 người.', 0, '2025-12-27 14:58:11', 0.00, NULL, NULL),
(5, 2, 1, 'Nike Air Zoom Mercurial Superfly 10 Academy MG Max Voltage ', 2350000.00, 2999000.00, 'assets/uploads/1766857322_g.jpg', 'Nhãn Hiệu: NIKE\r\nSKU: FQ1456-300-38.5', 0, '2025-12-27 17:42:02', 0.00, NULL, NULL),
(14, 1, 1, 'NIKE LUNAR GATO II - 580456-802 - ĐỎ/ĐEN/TRẮNG', 2000000.00, 2400000.00, 'assets/uploads/1768489162_-hung-futsal-giay-da-bong-nike-lunar-gato-ii-580456-802-do-den-trang-1_ec9f5491e60c430c94a9feeedc7be192_master.jpg', '', 0, '2026-05-10 05:09:32', 0.00, NULL, NULL),
(15, 1, 1, 'NIKE air', 2400000.00, 2800000.00, 'assets/uploads/1768489162_-hung-futsal-giay-da-bong-nike-lunar-gato-ii-580456-802-do-den-trang-1_ec9f5491e60c430c94a9feeedc7be192_master.jpg', '', 0, '2026-05-10 05:11:59', 0.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `image_url`) VALUES
(1, 5, '', 'assets/uploads/1766859265_0_dsc08196_431906e275bd4fd5b963e714281ad48c_1024x1024.jpg'),
(2, 5, '', 'assets/uploads/1766859265_1_dsc08195_465de194a4144eb08d941e680e4fc995_1024x1024.jpg'),
(3, 1, '', 'assets/uploads/1766947035_0_nms00372_96fc114bc4174f44bc9cc63930cbdd4c_1024x1024.jpg'),
(4, 1, '', 'assets/uploads/1766947035_1_nms00367_307049dc447a47a5a3075bb7ef0f7a41_1024x1024.jpg'),
(5, 2, '', 'assets/uploads/1766947074_0_nms00564_dc4357e467744d82bca4c8cfd39aa3de_1024x1024.jpg'),
(6, 2, '', 'assets/uploads/1766947074_1_nms00563_212ba3bd218e46c9ba7220221a167173_1024x1024.jpg'),
(7, 3, '', 'assets/uploads/1766947110_0_dsc07939_dccd66974404470ab9595f35cfab3e61_1024x1024.jpg'),
(8, 3, '', 'assets/uploads/1766947110_1_dsc07932_4527e2732d564e1386ce99f3c54adc8c_1024x1024.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `stock`) VALUES
(28, 4, '39', 9),
(29, 4, '40', 7),
(30, 4, '41', 10),
(31, 4, '42', 10),
(32, 4, '43', 5),
(33, 4, '44', 5),
(57, 5, '39', 5),
(58, 5, '40', 7),
(59, 5, '41', 5),
(60, 5, '42', 5),
(64, 2, '40', 2),
(65, 2, '41', 13),
(66, 2, '42', 3),
(67, 3, '39', 2),
(68, 3, '40', 5),
(69, 3, '41', 5),
(79, 1, '39', 6),
(80, 1, '40', 13),
(81, 1, '41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_imports`
--

CREATE TABLE `stock_imports` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `import_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_imports`
--

INSERT INTO `stock_imports` (`id`, `product_id`, `size`, `quantity`, `import_price`, `created_at`) VALUES
(1, 5, '40', 2, 1500000.00, '2026-01-10 01:00:29'),
(2, 1, '39', 1, 1500000.00, '2026-01-10 01:00:52'),
(3, 1, '39', 5, 1500000.00, '2026-01-10 01:25:27'),
(4, 1, '40', 5, 1500000.00, '2026-01-10 01:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer','staff') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) DEFAULT 1 COMMENT '1: Active, 0: Locked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `status`) VALUES
(1, 'Admin Quản Trị', 'admin@gmail.com', '$2y$10$R7GMSWWPw5ENtQKcsisoHeRLM6G4cX.FkdiElHIcJ9QPsZE3EAr96', NULL, NULL, 'admin', '2025-12-26 08:28:50', 1),
(2, 'Trần Văn B Update', 'khachhang@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0988888888', '789 Đường Mới, TP Cần Thơ', 'admin', '2025-12-26 08:28:50', 1),
(3, 'hai nam', 'namke161203@gmail.com', '$2y$10$WTSgC4zh.afPu7Wqy6DNBuMnGzvWo1JWW/Fm1nSpQSKp9XvNzwq3u', '112', 'null', 'customer', '2025-12-26 09:11:55', 1),
(4, 'nam', '123@gmail.com', '$2y$10$Tcbsq8sfARMpVCLJEB4a/.9QQ1/3iMJ.wgh5hYHCLYXKVkyoaCviy', '11111', 'hanoi\r\n', '', '2025-12-27 05:42:00', 1),
(5, 'Hồng Hải', 'honghai@gmail.com', '$2y$10$JGwCHNUv8t84cgW..3QLqesy4sA3s0CwGV/N0rPYfy3mNdv/HVI9S', '111111', 'ninh bình', 'customer', '2025-12-27 18:39:33', 1),
(6, 'Đinh Hải Nam', 'hainam@gmail.com', '$2y$10$MhPyIlMPEZRXbdAunPy3ve/YwIKTD0jfeJ3T12cYQastSMo7AWN.a', '12333', 'Ninh Bình', 'customer', '2025-12-27 18:40:34', 1),
(7, 'Hải Nam', 'dinhnam@gmail.com', '$2y$10$8XwfSwrzu3XJo4axFn9R6u8SO8rMkKIhtuSQpRN5c63ywaQM0Jidy', '0332755735', 'Ninh Bình', 'admin', '2026-01-12 05:58:31', 1),
(8, 'duyanh', 'duy2005@gmail.com', '$2y$10$zLqPQA5P8tQPoRgTljVkpuZEPVNyHSKf2Cgg6Pz3HyH6Hvf4WGRJW', '67876', 'ha noi', 'customer', '2026-01-16 00:43:46', 1),
(9, 'Trần Văn B', 'tranvanb@gmail.com', '$2y$10$CpqyOsrfm8F0SVJSLZSs7uOttlK1s45NMlZKr5fWpAM/rf6G5oAba', '0912345678', '456 Đường DEF, Quận UVW, TP Hà Nội', 'customer', '2026-06-16 15:24:30', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stock_imports`
--
ALTER TABLE `stock_imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_imports`
--
ALTER TABLE `stock_imports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_imports`
--
ALTER TABLE `stock_imports`
  ADD CONSTRAINT `stock_imports_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
