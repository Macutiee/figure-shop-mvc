-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for figure_shop
DROP DATABASE IF EXISTS `figure_shop`;
CREATE DATABASE IF NOT EXISTS `figure_shop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `figure_shop`;

-- Dumping structure for table figure_shop.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL DEFAULT '0',
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table figure_shop.products: ~15 rows (approximately)
INSERT INTO `products` (`id`, `name`, `price`, `brand`, `image`) VALUES
	(1, 'HATSUNE MIKU - Vocaloid - Luminasta', 750000, 'SEGA', 'https://down-vn.img.susercontent.com/file/vn-11134201-7qukw-lfs0rsnqen7b3d.webp'),
	(2, 'Ookami to Koushinryou: Merchant Meets the Wise Wolf - Holo', 1200000, 'Good Smile', 'https://otakustore.vn/image/cache/catalog/2024/05/ookami-to-koushinryou-merchant-meets-the-wise-wolf-holo-pop-up-parade-2024-ver-good-smile-company-1-750x750h.jpg'),
	(3, 'Chihaya Anon - BanG Dream!', 2000000, 'Good Smile', 'https://cdn.hstatic.net/products/200000462939/bang_dream__anon_chihaya_l_size_complete_figure_good_smile_company__02_4e6f3d95b9e34709a9abfdc2cb3bf409_master.jpg'),
	(4, 'Yukihana Lamy - Hololive', 1450000, 'Good Smile', 'https://pos.nvncdn.com/f625c0-33854/ps/20260203_mKlWpVXEAx.jpeg?v=1770092320'),
	(5, 'Nyatasha Nyanners', 800000, 'Good Smile', 'https://www.goodsmile.com/gsc-webrevo-sdk-storage-prd/product/image/product/20221025/13455/106432/large/2d04779765ed98d0e3a21422a834d44b.jpg'),
	(6, 'Gawr Gura - Hololive Production', 1900000, 'Good Smile', 'https://product.hstatic.net/200000462939/product/10001_a398ee7d41e34a1987497d307ae8beb5_master.jpg'),
	(7, 'Nekopara Chocola Chinese Dress 1/7 Ver.', 4100000, 'Good Smile', 'https://bizweb.dktcdn.net/thumb/1024x1024/100/387/684/products/9c94d3f84e5bfd5625eb1c92e65f9fc2-result.jpg?v=1626171154123'),
	(8, 'NEKOPARA: Vanilla Chinese Dress 1/7 Ver.', 4000000, 'Good Smile', 'https://bizweb.dktcdn.net/100/342/840/products/a2-11bb71c2-2707-46a0-81e8-eae46c4643f7.jpg?v=1737711397240'),
	(9, 'MG 1/100 Gundam Astray Red Dragon', 3200000, 'Bandai', 'https://product.hstatic.net/200000326537/product/mg_gundam_astray_red_dragon__2__280d25804f69479bb2459cb65e0a5717_master.jpg'),
	(10, 'HG BM 1/144 Typhoeus Gundam Chimera', 2000000, 'Bandai', 'https://product.hstatic.net/200000326537/product/hgbm-gundam_build_metaverse_large_unit-o4-660x660_f188cf3d5acc4b039f10aabac2d68ce9_grande.jpg'),
	(11, 'Hatsune Miku - F:Nex - 1/7 - Water Lily Ver.', 4600000, 'FuRyu', 'https://cdn.hstatic.net/products/200000462939/figure-197378_03_8c8baf31aca54b93bd2008fc9beaebbd_master.jpg'),
	(12, 'Scale Figure Azur Lane Illustrated μ Armored 1/6', 8200000, 'GOLDENHEAD', 'https://m.media-amazon.com/images/I/81pXVhKqtqL._AC_SL1500_.jpg'),
	(13, 'Gojo Satoru 1/7 Scale', 3500000, 'Shibuya Scramble', 'https://resize.cdn.otakumode.com/ex/700.932/shop/product/534c619ed7e5402d960dbedc855422e9.jpg.webp'),
	(14, 'Hatsune Miku Symphony', 1200000, 'Good Smile', 'https://www.goodsmile.com/gsc-webrevo-sdk-storage-prd/product/image/product/20210908/11760/90088/large/0b83e1ec25f979e41675827771cb5870.jpg'),
	(17, 'Naruto Uzumaki Grandista', 550000, 'Banpresto', 'https://cdn.hstatic.net/products/200000462939/2801435-dc018_faa23e21a79d4e96a7fe0e33cf0d7ed5_master.jpg'),
	(18, 'Hatsune Miku Sakura Hanami Outfit Ver', 1200000, 'Good Smile', 'https://product.hstatic.net/200000462939/product/4386329r1746070060_071c898d7d824025963dcd5a7ea74272_master.jpeg');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
