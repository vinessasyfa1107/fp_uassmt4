-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2023 at 03:43 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warungsantuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `email`, `password`) VALUES
(1, '', 'firman', 'firman.agus777@gmail.com', '$2y$10$w.NzTEJ8s2raz3irGzgUZ.RmBsme5NEU6JmlY2NpoUYMap/8jNVaO'),
(2, '', 'firman', 'nugget@foodmedia.us', '$2y$10$TsPr./XpQfRq74X/OVKyCuvS45yVjCuz0UIuqov9QeGP2d8wvZkzK'),
(3, '', 'firman-195410261', 'nugget@foodmedia.us', '$2y$10$DoErh00B/Ux2JvH3z.TnDO6qHr93CytJVK4Zv5x3wuU47XPo3D2r.'),
(4, '', 'firman', 'firman.agus777@gmail.com', '$2y$10$wiin1cTUS9sBMVTQ4NygTeWxcBIQOnHKvmijSRO.vfB4.hj.815m6'),
(5, '', 'firman-195410261', 'firman.agus777@gmail.com', '$2y$10$oxwy//4CTmkrHq8nb.ypce3Hv3gacIxMtSKujdzn1b1m4zYwWkMnm'),
(6, '', 'firman-195410261', 'firman.agus777@gmail.com', '$2y$10$sESp7RS8C690ZlF2hIlT3.ASvAu2K2Yhks9pSfTl.hmN.EeDU8VNq'),
(7, '', 'firman', 'nugget@foodmedia.us', '$2y$10$DyrDfup6y4/WY92SSXgPWekTXQZkOEB3CkLSjlAJOGMHXD1hxPGU.'),
(8, '', 'khafidhfuadi', 'muhammadkhafidfuadi@gmail.com', '$2y$10$p9IYJdo9uY1y9bZRMEvC1OnA4N0olBYfNkWZBkuaBX47zaTWJNEe2'),
(10, '', 'kemas', 'kemas@gmail.com', '$2y$10$yGNWivH07szG38gWepOA1.0XMkRamNYsYqYTI1tTr8sXxnkpb55DC'),
(12, '', 'kha', 'khafidhfuadi0173@gmail.com', '$2y$10$pDR/vGgM3z1VzmaKOb5SFexp0svfNGxPuaQE9yOnjGu3etbVwRLNy');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_cat` int(11) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_cat`, `nama_kategori`) VALUES
(15, 'Makanan'),
(16, 'Alat Mandi'),
(17, 'Minuman'),
(18, 'Sembako');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(15) NOT NULL,
  `cat_id` int(15) NOT NULL,
  `nama_produk` varchar(20) NOT NULL,
  `harga` int(20) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `cat_id`, `nama_produk`, `harga`, `keterangan`, `gambar_produk`, `stok`) VALUES
(56, 15, 'Indomie Goreng', 3500, '', 'indomie.jpeg', 93),
(57, 16, 'Shampoo Dove 290ML', 40000, '', 'dove.jpg', 28),
(58, 17, 'Le Minerale 600ML', 3000, '', 'le-minarale.jpg', 198);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_produk` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` varchar(11) NOT NULL DEFAULT 'proses',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_produk`, `nama_produk`, `qty`, `total_harga`, `status`, `created_at`, `updated_at`) VALUES
(28, 5, 56, 'Indomie Goreng', 2, 7000, 'selesai', '2023-06-10 11:54:49', '2023-06-10 11:55:16'),
(29, 5, 58, 'Le Minerale 600ML', 2, 6000, 'batal', '2023-06-10 11:56:13', '2023-06-10 13:39:19'),
(30, 5, 57, 'Shampoo Dove 290ML', 2, 80000, 'selesai', '2023-06-10 11:57:17', '2023-06-10 11:57:50'),
(31, 5, 56, 'Indomie Goreng', 5, 17500, 'proses', '2023-06-10 13:38:30', NULL);

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `update_qty` AFTER INSERT ON `transaksi` FOR EACH ROW UPDATE produk
    SET stok = stok - NEW.qty
    WHERE produk.id = NEW.id_produk
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(250) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('f','m') NOT NULL,
  `city` varchar(250) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `name`, `gender`, `city`, `no_telepon`) VALUES
(1, 'vinessagab@gmail.com', 'vinessagabby', 'Vinessa Gabby', '', 'f', 'Jakarta Selatan', '0852178624488'),
(2, 'arifdharma@study.com', 'arifdharma11', 'Arif Dharma', '', 'm', 'Bekasi', '0852178624488'),
(3, 'vanialyssa21@gmail.com', '$2y$10$uI4XXHs3mAXkhNirclA8W.d3lD9lBCtr/AcnuYP6GYxL6TtVOoWsS', 'vanialyss_', '', 'f', 'padang', '0852178624488'),
(4, 'kemasghani123@gmail.com', '$2y$10$qWJ8v8Zw4JWKXwVKAnfPZOC1F6BatAIjgCMKb0dvdiGPx5WzsyA4W', 'kemasghani', 'kemas', 'm', 'jakarta', ''),
(5, 'muhammadkhafidfuadi@gmail.com', '$2y$10$2DjobkhutF/cwZL.3FFDa.P/KgjGbrn8wlZuGPRTuSXHkyqv/jiV6', 'khafidhfuadi', 'Muhamad Khafidh Fuadi', 'm', 'tangerang', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_cat`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
