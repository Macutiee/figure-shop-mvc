-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.16.0.7229
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table figure_shop.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table figure_shop.order_details: ~8 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 1, 1, 750000),
	(2, 1, 14, 1, 1200000),
	(3, 2, 8, 2, 4000000),
	(4, 2, 5, 1, 800000),
	(5, 2, 3, 1, 2000000),
	(6, 2, 1, 1, 750000),
	(7, 3, 18, 2, 1200000),
	(8, 3, 3, 1, 2000000);

-- Dumping structure for table figure_shop.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_note` text COLLATE utf8mb4_unicode_ci,
  `total_price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table figure_shop.orders: ~3 rows (approximately)
INSERT INTO `orders` (`id`, `customer_name`, `customer_phone`, `customer_address`, `customer_note`, `total_price`, `created_at`) VALUES
	(1, 'Loan', '0123456789', '123 Hồ Chí Minh', 'Giao giờ hành chính nha shop', 1950000, '2026-03-11 09:24:08'),
	(2, 'Cát', '0145623789', '1233 Hà Nội', '', 11550000, '2026-03-11 09:27:26'),
	(3, 'cat', '123456789', 'dffafd', '', 4400000, '2026-03-11 09:30:14');

-- Dumping structure for table figure_shop.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL DEFAULT '0',
  `old_price` int NOT NULL DEFAULT '0',
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '10',
  `sold_count` int NOT NULL DEFAULT '0',
  `is_flash_sale` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table figure_shop.products: ~16 rows (approximately)
INSERT INTO `products` (`id`, `name`, `price`, `old_price`, `brand`, `image`, `stock`, `sold_count`, `is_flash_sale`) VALUES
	(1, 'HATSUNE MIKU - Vocaloid - Luminasta', 750000, 1000000, 'SEGA', 'https://down-vn.img.susercontent.com/file/vn-11134201-7qukw-lfs0rsnqen7b3d.webp', 8, 2, 1),
	(2, 'Ookami to Koushinryou: Merchant Meets the Wise Wolf - Holo', 1200000, 0, 'Good Smile', 'https://otakustore.vn/image/cache/catalog/2024/05/ookami-to-koushinryou-merchant-meets-the-wise-wolf-holo-pop-up-parade-2024-ver-good-smile-company-1-750x750h.jpg', 10, 0, 0),
	(3, 'Chihaya Anon - BanG Dream!', 2000000, 2500000, 'Good Smile', 'https://cdn.hstatic.net/products/200000462939/bang_dream__anon_chihaya_l_size_complete_figure_good_smile_company__02_4e6f3d95b9e34709a9abfdc2cb3bf409_master.jpg', 8, 2, 1),
	(4, 'Yukihana Lamy - Hololive', 1450000, 0, 'Good Smile', 'https://pos.nvncdn.com/f625c0-33854/ps/20260203_mKlWpVXEAx.jpeg?v=1770092320', 10, 0, 0),
	(5, 'Nyatasha Nyanners', 800000, 1000000, 'Good Smile', 'https://www.goodsmile.com/gsc-webrevo-sdk-storage-prd/product/image/product/20221025/13455/106432/large/2d04779765ed98d0e3a21422a834d44b.jpg', 9, 1, 1),
	(6, 'Gawr Gura - Hololive Production', 1900000, 0, 'Good Smile', 'https://product.hstatic.net/200000462939/product/10001_a398ee7d41e34a1987497d307ae8beb5_master.jpg', 10, 0, 0),
	(7, 'Nekopara Chocola Chinese Dress 1/7 Ver.', 4100000, 0, 'Good Smile', 'https://bizweb.dktcdn.net/thumb/1024x1024/100/387/684/products/9c94d3f84e5bfd5625eb1c92e65f9fc2-result.jpg?v=1626171154123', 10, 0, 0),
	(8, 'NEKOPARA: Vanilla Chinese Dress 1/7 Ver.', 4000000, 4500000, 'Good Smile', 'https://bizweb.dktcdn.net/100/342/840/products/a2-11bb71c2-2707-46a0-81e8-eae46c4643f7.jpg?v=1737711397240', 8, 2, 1),
	(9, 'MG 1/100 Gundam Astray Red Dragon', 3200000, 0, 'Bandai', 'https://product.hstatic.net/200000326537/product/mg_gundam_astray_red_dragon__2__280d25804f69479bb2459cb65e0a5717_master.jpg', 10, 0, 0),
	(10, 'HG BM 1/144 Typhoeus Gundam Chimera', 2000000, 0, 'Bandai', 'https://product.hstatic.net/200000326537/product/hgbm-gundam_build_metaverse_large_unit-o4-660x660_f188cf3d5acc4b039f10aabac2d68ce9_grande.jpg', 10, 0, 0),
	(11, 'Hatsune Miku - F:Nex - 1/7 - Water Lily Ver.', 4600000, 0, 'FuRyu', 'https://cdn.hstatic.net/products/200000462939/figure-197378_03_8c8baf31aca54b93bd2008fc9beaebbd_master.jpg', 10, 0, 0),
	(12, 'Scale Figure Azur Lane Illustrated μ Armored 1/6', 8200000, 0, 'GOLDENHEAD', 'https://m.media-amazon.com/images/I/81pXVhKqtqL._AC_SL1500_.jpg', 10, 0, 0),
	(13, 'Gojo Satoru 1/7 Scale', 3500000, 0, 'Shibuya Scramble', 'https://resize.cdn.otakumode.com/ex/700.932/shop/product/534c619ed7e5402d960dbedc855422e9.jpg.webp', 10, 0, 0),
	(14, 'Hatsune Miku Symphony', 1200000, 0, 'Good Smile', 'https://www.goodsmile.com/gsc-webrevo-sdk-storage-prd/product/image/product/20210908/11760/90088/large/0b83e1ec25f979e41675827771cb5870.jpg', 9, 1, 0),
	(17, 'Naruto Uzumaki Grandista', 550000, 0, 'Banpresto', 'https://cdn.hstatic.net/products/200000462939/2801435-dc018_faa23e21a79d4e96a7fe0e33cf0d7ed5_master.jpg', 10, 0, 0),
	(18, 'Hatsune Miku Sakura Hanafigure Outfit Ver', 1200000, 0, 'Good Smile', 'https://product.hstatic.net/200000462939/product/4386329r1746070060_071c898d7d824025963dcd5a7ea74272_master.jpeg', 8, 2, 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
