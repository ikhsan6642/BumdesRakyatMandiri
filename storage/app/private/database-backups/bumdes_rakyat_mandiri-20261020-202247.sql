-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: bumdes_rakyat_mandiri
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `unit_code` varchar(255) DEFAULT NULL,
  `normal_balance` enum('debit','credit') NOT NULL DEFAULT 'debit',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'1-1001','Kas Tunai Kantor BUMDes','asset','pusat','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'1-1002','Bank BRI BUMDes Rakyat Mandiri','asset','pusat','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'1-1003','Kas Tunai Unit Pertanian','asset','pertanian','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(4,'1-1004','Kas Tunai Unit Peternakan','asset','peternakan','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(5,'1-1005','Kas Tunai Unit CFD','asset','cfd','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(6,'1-1006','Kas Tunai Unit Pamsimas','asset','pamsimas','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(7,'1-1010','Piutang Usaha Air Pamsimas','asset','pamsimas','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(8,'1-2001','Aset Tetap Mesin & Alsintan','asset','pertanian','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(9,'1-2002','Aset Ternak Indukan & Bakalan','asset','peternakan','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(10,'1-2003','Aset Jaringan Pipa & Pompa Pamsimas','asset','pamsimas','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(11,'2-1001','Utang Operasional / Mitra Pakan & Pupuk','liability','pusat','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(12,'3-1001','Penyertaan Modal Desa (APBDDes)','equity','pusat','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(13,'3-1002','Penyertaan Modal Masyarakat','equity','pusat','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(14,'3-2001','Cadangan Modal BUMDes','equity','pusat','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(15,'3-3001','Laba Ditahan','equity','pusat','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(16,'4-1001','Pendapatan Penjualan Hasil Tani','revenue','pertanian','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(17,'4-1002','Pendapatan Sewa Alsintan Traktor','revenue','pertanian','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(18,'4-2001','Pendapatan Penjualan Ternak Hidup','revenue','peternakan','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(19,'4-2002','Pendapatan Penjualan Susu & Pupuk Kohe','revenue','peternakan','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(20,'4-3001','Pendapatan Retribusi Karcis Lapak CFD','revenue','cfd','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(21,'4-3002','Pendapatan Parkir Kendaraan CFD','revenue','cfd','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(22,'4-4001','Pendapatan Pemakaian Air Pamsimas','revenue','pamsimas','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(23,'4-4002','Pendapatan Biaya Abonemen Pamsimas','revenue','pamsimas','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(24,'4-4003','Pendapatan Denda Pembayaran Pamsimas','revenue','pamsimas','credit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(25,'5-1001','Beban Benih, Pupuk & Obat Tani','expense','pertanian','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(26,'5-1002','Beban Upah Buruh Tani & BBM Alsintan','expense','pertanian','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(27,'5-2001','Beban Pakan Ternak & Hijauan','expense','peternakan','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(28,'5-2002','Beban Vitamin, Vaksin & Perawatan Kandang','expense','peternakan','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(29,'5-3001','Beban Kebersihan, Sampah & Keamanan CFD','expense','cfd','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(30,'5-3002','Beban Listrik & Sarana Prasarana CFD','expense','cfd','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(31,'5-4001','Beban Listrik PLN Pompa Submersible Pamsimas','expense','pamsimas','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(32,'5-4002','Beban Pemeliharaan Pipa & Kaporit Pamsimas','expense','pamsimas','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(33,'5-5001','Beban Gaji Pengurus & Karyawan BUMDes','expense','pusat','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(34,'5-5002','Beban Operasional Kantor, ATK & Internet','expense','pusat','debit',NULL,1,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agriculture_cycles`
--

DROP TABLE IF EXISTS `agriculture_cycles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agriculture_cycles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `commodity_name` varchar(255) NOT NULL,
  `land_location` varchar(255) NOT NULL,
  `land_area_ha` decimal(8,2) NOT NULL DEFAULT 1.00,
  `start_date` date NOT NULL,
  `estimated_harvest_date` date DEFAULT NULL,
  `actual_harvest_date` date DEFAULT NULL,
  `status` enum('persiapan','tanam','rawat','panen','selesai') NOT NULL DEFAULT 'persiapan',
  `total_yield_kg` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agriculture_cycles_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agriculture_cycles`
--

LOCK TABLES `agriculture_cycles` WRITE;
/*!40000 ALTER TABLE `agriculture_cycles` DISABLE KEYS */;
INSERT INTO `agriculture_cycles` VALUES (1,'TNM-2026-001','Padi Inpari 32 (Musim Tanam Gadu)','Blok Sawah Kas Desa Krajan Selatan',2.50,'2026-05-10','2026-09-05','2026-09-08','selesai',14500.00,'Hasil panen sangat memuaskan, kualitas gabah kering giling (GKG) super.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'TNM-2026-002','Jagung Hibrida Pioneer P35','Lahan Kering Dusun Sukamaju',1.80,'2026-09-15','2026-12-20',NULL,'rawat',0.00,'Fase pertumbuhan vegetatif, sudah dilakukan pemupukan tahap kedua.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `agriculture_cycles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agriculture_machineries`
--

DROP TABLE IF EXISTS `agriculture_machineries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agriculture_machineries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand_model` varchar(255) DEFAULT NULL,
  `procurement_year` year(4) DEFAULT NULL,
  `rental_rate_per_day` decimal(15,2) NOT NULL,
  `condition` enum('sangat_baik','baik','perlu_servis','rusak') NOT NULL DEFAULT 'baik',
  `status` enum('tersedia','disewa','perbaikan') NOT NULL DEFAULT 'tersedia',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agriculture_machineries_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agriculture_machineries`
--

LOCK TABLES `agriculture_machineries` WRITE;
/*!40000 ALTER TABLE `agriculture_machineries` DISABLE KEYS */;
INSERT INTO `agriculture_machineries` VALUES (1,'ALST-001','Traktor Roda 4 Kubota L3218','Kubota 32 HP',2024,450000.00,'sangat_baik','tersedia','Bantuan Kemendesa PDTT tahun 2024, perawatan berkala rutin.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'ALST-002','Traktor Roda 2 Quick G1000 Boxer','Quick Kubota RD85',2023,250000.00,'baik','disewa','Siap pakai di lahan basah maupun kering.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'ALST-003','Mesin Pompa Air Irigasi 3 Inch','Honda GX160 Alkon',2024,100000.00,'baik','tersedia','Lengkap dengan selang spiral 10 meter dan buang 50 meter.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `agriculture_machineries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agriculture_productions`
--

DROP TABLE IF EXISTS `agriculture_productions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agriculture_productions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cycle_id` bigint(20) unsigned NOT NULL,
  `category` enum('bibit','pupuk','pestisida','tenaga_kerja','sewa_lahan','lainnya') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `supplier_or_worker` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agriculture_productions_cycle_id_foreign` (`cycle_id`),
  CONSTRAINT `agriculture_productions_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `agriculture_cycles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agriculture_productions`
--

LOCK TABLES `agriculture_productions` WRITE;
/*!40000 ALTER TABLE `agriculture_productions` DISABLE KEYS */;
INSERT INTO `agriculture_productions` VALUES (1,1,'bibit','Benih Padi Bersertifikat Inpari 32 (10 sak @ 5kg)',10.00,'sak',125000.00,1250000.00,'2026-05-12','Toko Tani Makmur Sejahtera',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,1,'pupuk','Pupuk Urea & NPK Phonska Plus',16.00,'sak',240000.00,3840000.00,'2026-05-25','Kios Pupuk Lengkap Mandiri',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,1,'tenaga_kerja','Upah Bajak Sawah & Tanam Buruh Tani Warga',20.00,'orang-hari',120000.00,2400000.00,'2026-05-18','Kelompok Tani Rukun Santoso',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(4,1,'tenaga_kerja','Upah Panen & Angkut Gabah',25.00,'orang-hari',110000.00,2750000.00,'2026-09-08','Kelompok Buruh Panen Krajan',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(5,2,'bibit','Benih Jagung Hibrida P35 (15 kg)',15.00,'kg',135000.00,2025000.00,'2026-09-16','Toko Tani Makmur Sejahtera',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(6,2,'pupuk','Pupuk Dasar Organik Kohe & NPK',12.00,'sak',180000.00,2160000.00,'2026-09-22','Kios Pupuk Lengkap Mandiri',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `agriculture_productions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agriculture_rentals`
--

DROP TABLE IF EXISTS `agriculture_rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agriculture_rentals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `machinery_id` bigint(20) unsigned NOT NULL,
  `rental_code` varchar(255) NOT NULL,
  `renter_name` varchar(255) NOT NULL,
  `renter_phone` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_days` int(11) NOT NULL DEFAULT 1,
  `rate_per_day` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_status` enum('lunas','belum_lunas') NOT NULL DEFAULT 'lunas',
  `status` enum('berjalan','dikembalikan','dibatalkan') NOT NULL DEFAULT 'berjalan',
  `actual_return_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agriculture_rentals_rental_code_unique` (`rental_code`),
  KEY `agriculture_rentals_machinery_id_foreign` (`machinery_id`),
  CONSTRAINT `agriculture_rentals_machinery_id_foreign` FOREIGN KEY (`machinery_id`) REFERENCES `agriculture_machineries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agriculture_rentals`
--

LOCK TABLES `agriculture_rentals` WRITE;
/*!40000 ALTER TABLE `agriculture_rentals` DISABLE KEYS */;
INSERT INTO `agriculture_rentals` VALUES (1,2,'SWA-202610-001','Pak Marjuki','082133445566','2026-10-15','2026-10-18',3,250000.00,750000.00,'lunas','berjalan',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,1,'SWA-202610-002','Kelompok Tani Maju Lancar','081399887766','2026-10-02','2026-10-06',4,450000.00,1800000.00,'lunas','dikembalikan','2026-10-06','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `agriculture_rentals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agriculture_sales`
--

DROP TABLE IF EXISTS `agriculture_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agriculture_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cycle_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_phone` varchar(255) DEFAULT NULL,
  `quantity_sold_kg` decimal(12,2) NOT NULL,
  `price_per_kg` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `sale_date` date NOT NULL,
  `payment_status` enum('lunas','belum_lunas') NOT NULL DEFAULT 'lunas',
  `payment_method` varchar(255) NOT NULL DEFAULT 'tunai',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agriculture_sales_invoice_number_unique` (`invoice_number`),
  KEY `agriculture_sales_cycle_id_foreign` (`cycle_id`),
  CONSTRAINT `agriculture_sales_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `agriculture_cycles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agriculture_sales`
--

LOCK TABLES `agriculture_sales` WRITE;
/*!40000 ALTER TABLE `agriculture_sales` DISABLE KEYS */;
INSERT INTO `agriculture_sales` VALUES (1,1,'INV-TNI-202609-01','UD Beras Berkah Jaya (Kemitraan Bulog)','085711223344',14500.00,6800.00,98600000.00,'2026-09-12','lunas','transfer_bank','Pembayaran lunas via transfer Bank BRI BUMDes.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `agriculture_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_units`
--

DROP TABLE IF EXISTS `business_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `manager_phone` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'bi-briefcase',
  `color` varchar(255) NOT NULL DEFAULT 'emerald',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_units_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_units`
--

LOCK TABLES `business_units` WRITE;
/*!40000 ALTER TABLE `business_units` DISABLE KEYS */;
INSERT INTO `business_units` VALUES (1,'pusat','Kantor Pengelola BUMDes Pusat','Manajemen pusat, pembukuan terpadu, dan administrasi umum BUMDes Rakyat Mandiri.','Joko Supriyono, S.E.','081234567890','building','indigo',1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'pertanian','Unit Usaha Pertanian & Alsintan','Pengelolaan lahan pangan desa, pengadaan bibit/pupuk, dan penyewaan alat mesin pertanian (Alsintan).','Slamet Riyadi','081234567892','sprout','emerald',1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'peternakan','Unit Usaha Peternakan Terpadu','Penggemukan sapi potong, budidaya kambing perah/potong, dan produksi pupuk organik kohe.','Bambang Sutrisno','081234567893','paw','amber',1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(4,'cfd','Unit Usaha CFD & Pasar UMKM Minggu','Penyelenggaraan Car Free Day mingguan, penataan lapak pedagang UMKM warga, dan parkir terpadu.','Dian Wahyudi','081234567894','store','rose',1,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(5,'pamsimas','Unit Usaha Pamsimas Tirta Mandiri','Penyediaan dan pengelolaan distribusi air bersih perpipaan untuk masyarakat dan fasilitas umum desa.','Hendra Setiawan','081234567895','droplets','sky',1,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `business_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cfd_events`
--

DROP TABLE IF EXISTS `cfd_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfd_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `total_stalls_occupied` int(11) NOT NULL DEFAULT 0,
  `total_stall_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `parking_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cleanliness_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `security_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sound_electricity_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('terjadwal','berlangsung','selesai') NOT NULL DEFAULT 'terjadwal',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cfd_events_event_code_unique` (`event_code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfd_events`
--

LOCK TABLES `cfd_events` WRITE;
/*!40000 ALTER TABLE `cfd_events` DISABLE KEYS */;
INSERT INTO `cfd_events` VALUES (1,'CFD-2026-W41','CFD Rakyat Mandiri Pekan ke-41 (Minggu 11 Okt 2026)','2026-10-11',38,745000.00,480000.00,150000.00,180000.00,75000.00,820000.00,'selesai','Ramai pengunjung, senam pagi bersama ibu-ibu PKK berjalan lancar.','2026-10-19 08:59:23','2026-10-19 08:59:23'),(2,'CFD-2026-W42','CFD Rakyat Mandiri Pekan ke-42 (Minggu 18 Okt 2026)','2026-10-18',42,835000.00,520000.00,160000.00,180000.00,85000.00,930000.00,'selesai','Peningkatan pedagang kuliner dan arena bermain anak-anak.','2026-10-19 08:59:23','2026-10-19 08:59:23'),(3,'CFD-2026-W43','Bloods','2026-10-20',3,56000.00,0.00,0.00,0.00,0.00,56000.00,'selesai','pagi - malam','2026-10-19 23:17:11','2026-10-20 13:14:48'),(8,'CFD-2026-W43-2','CFD pekan 1','2026-10-25',1,17000.00,0.00,0.00,0.00,0.00,17000.00,'terjadwal','Mengundang artis papan atas','2026-10-20 12:54:19','2026-10-20 13:15:04');
/*!40000 ALTER TABLE `cfd_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cfd_tickets`
--

DROP TABLE IF EXISTS `cfd_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfd_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_name_manual` varchar(255) DEFAULT NULL,
  `zone_id` bigint(20) unsigned NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `spot_number` varchar(255) NOT NULL,
  `fee_amount` decimal(15,2) NOT NULL,
  `electricity_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cleanliness_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_status` enum('lunas','belum_bayar') NOT NULL DEFAULT 'lunas',
  `collected_by` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cfd_tickets_ticket_number_unique` (`ticket_number`),
  KEY `cfd_tickets_event_id_foreign` (`event_id`),
  KEY `cfd_tickets_vendor_id_foreign` (`vendor_id`),
  KEY `cfd_tickets_zone_id_foreign` (`zone_id`),
  CONSTRAINT `cfd_tickets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `cfd_events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cfd_tickets_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `cfd_vendors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cfd_tickets_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `cfd_zones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfd_tickets`
--

LOCK TABLES `cfd_tickets` WRITE;
/*!40000 ALTER TABLE `cfd_tickets` DISABLE KEYS */;
INSERT INTO `cfd_tickets` VALUES (1,2,1,NULL,1,'KRC-20261018-001','A-01',20000.00,5000.00,2000.00,27000.00,'lunas','Dian Wahyudi','2026-10-18 06:15:00','2026-10-19 08:59:23','2026-10-19 08:59:23'),(2,2,2,NULL,1,'KRC-20261018-002','A-02',20000.00,0.00,2000.00,22000.00,'lunas','Dian Wahyudi','2026-10-18 06:20:00','2026-10-19 08:59:23','2026-10-19 08:59:23'),(3,2,3,NULL,2,'KRC-20261018-003','B-05',15000.00,0.00,2000.00,17000.00,'lunas','Dian Wahyudi','2026-10-18 06:30:00','2026-10-19 08:59:23','2026-10-19 08:59:23'),(4,2,4,NULL,3,'KRC-20261018-004','C-02',25000.00,10000.00,2000.00,37000.00,'lunas','Dian Wahyudi','2026-10-18 06:40:00','2026-10-19 08:59:23','2026-10-19 08:59:23'),(5,2,5,NULL,2,'KRC-20261018-005','B-08',15000.00,0.00,2000.00,17000.00,'lunas','Dian Wahyudi','2026-10-18 06:45:00','2026-10-19 08:59:23','2026-10-19 08:59:23'),(6,3,6,'sandi',1,'KRC-20261020-001','w43',20000.00,0.00,2000.00,22000.00,'lunas','Dian Wahyudi','2026-10-20 07:28:33','2026-10-20 00:28:33','2026-10-20 00:28:33'),(7,3,3,NULL,1,'KRC-20261020-002','w43',15000.00,0.00,2000.00,17000.00,'lunas','Dian Wahyudi','2026-10-20 19:56:51','2026-10-20 12:56:51','2026-10-20 12:56:51'),(8,3,5,NULL,2,'KRC-20261020-003','B-08',15000.00,0.00,2000.00,17000.00,'lunas','Dian Wahyudi','2026-10-20 20:14:48','2026-10-20 13:14:48','2026-10-20 13:14:48'),(9,8,5,NULL,2,'KRC-20261025-001','B-08',15000.00,0.00,2000.00,17000.00,'lunas','Dian Wahyudi','2026-10-20 20:15:04','2026-10-20 13:15:04','2026-10-20 13:15:04');
/*!40000 ALTER TABLE `cfd_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cfd_vendors`
--

DROP TABLE IF EXISTS `cfd_vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfd_vendors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `preferred_zone_id` bigint(20) unsigned DEFAULT NULL,
  `spot_number` varchar(255) DEFAULT NULL,
  `category` enum('kuliner_makanan','kuliner_minuman','fashion_pakaian','kerajinan_souvenir','mainan_hiburan','pertanian_organik','lainnya') NOT NULL,
  `origin_village` varchar(255) NOT NULL DEFAULT 'Desa Rakyat Mandiri',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cfd_vendors_vendor_code_unique` (`vendor_code`),
  KEY `cfd_vendors_preferred_zone_id_foreign` (`preferred_zone_id`),
  CONSTRAINT `cfd_vendors_preferred_zone_id_foreign` FOREIGN KEY (`preferred_zone_id`) REFERENCES `cfd_zones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfd_vendors`
--

LOCK TABLES `cfd_vendors` WRITE;
/*!40000 ALTER TABLE `cfd_vendors` DISABLE KEYS */;
INSERT INTO `cfd_vendors` VALUES (1,'CFD-V-001','Warung Nasi Uduk & Lontong Sayur Bu Nanik','Ibu Nanik Triastuti','087711223301',1,'A-01','kuliner_makanan','Desa Rakyat Mandiri (Warga Asli)','aktif',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'CFD-V-002','Es Dawet Ayu & Kelapa Muda Segar Cak Ali','Ali Murtadlo','087711223302',1,'A-02','kuliner_minuman','Desa Rakyat Mandiri (Warga Asli)','aktif',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'CFD-V-003','Batik Tulis & Kerajinan Anyaman Bambu Lestari','Sri Wahyuni','087711223303',2,'B-05','fashion_pakaian','Desa Rakyat Mandiri (Warga Asli)','aktif',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(4,'CFD-V-004','Wahana Kereta Ceria & Lukis Anak Melati','Rahmat Hidayat','087711223304',3,'C-02','mainan_hiburan','Desa Rakyat Mandiri (Warga Asli)','aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(5,'CFD-V-005','Aneka Keripik Tempe & Pisang Gurih Mas Eko','Eko Prasetyo','087711223305',2,'B-08','kuliner_makanan','Desa Tetangga (Mitra BUMDes)','aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(6,'CFD-V-006','Minuman Sandi','Sandi','09845678907',1,NULL,'kuliner_minuman','Desa Rakyat Mandiri','aktif',NULL,'2026-10-19 23:35:11','2026-10-19 23:35:11');
/*!40000 ALTER TABLE `cfd_vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cfd_zones`
--

DROP TABLE IF EXISTS `cfd_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfd_zones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `default_fee` decimal(15,2) NOT NULL,
  `capacity_spots` int(11) NOT NULL DEFAULT 50,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cfd_zones_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfd_zones`
--

LOCK TABLES `cfd_zones` WRITE;
/*!40000 ALTER TABLE `cfd_zones` DISABLE KEYS */;
INSERT INTO `cfd_zones` VALUES (1,'Zona A - Kuliner Utama & Sarapan Desa','ZON-A',20000.00,40,'Khusus penjual makanan berat, nasi uduk, lontong sayur, sate, bakso, minuman segar.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'Zona B - Fashion, Kerajinan & Produk Desa','ZON-B',15000.00,30,'Pakaian anak, daster, batik desa, hasil tani organik, sayur segar, olahan camilan kering.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'Zona C - Wahana Mainan Anak & Jasa Hiburan','ZON-C',25000.00,20,'Istana balon, mewarnai gambar anak, odong-odong listrik ramah lingkungan, pancing mainan.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `cfd_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livestock_batches`
--

DROP TABLE IF EXISTS `livestock_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livestock_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cage_location` varchar(255) NOT NULL,
  `initial_quantity` int(11) NOT NULL DEFAULT 0,
  `current_quantity` int(11) NOT NULL DEFAULT 0,
  `male_count` int(11) NOT NULL DEFAULT 0,
  `female_count` int(11) NOT NULL DEFAULT 0,
  `birth_count` int(11) NOT NULL DEFAULT 0,
  `death_count` int(11) NOT NULL DEFAULT 0,
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `initial_purchase_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `entry_date` date NOT NULL,
  `status` enum('aktif','selesai') NOT NULL DEFAULT 'aktif',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `livestock_batches_code_unique` (`code`),
  KEY `livestock_batches_category_id_foreign` (`category_id`),
  CONSTRAINT `livestock_batches_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `livestock_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livestock_batches`
--

LOCK TABLES `livestock_batches` WRITE;
/*!40000 ALTER TABLE `livestock_batches` DISABLE KEYS */;
INSERT INTO `livestock_batches` VALUES (1,1,'BATCH-SP-2026-01','Penggemukan Sapi Periode Semester II 2026','Kandang Komunal BUMDes Blok Krajan Timur',10,8,8,0,0,0,2,145000000.00,'2026-06-01','aktif','Kondisi sapi sehat, bobot harian rata-rata naik 0.9 kg/hari.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,2,'BATCH-KB-2026-01','Kelompok Kambing Boer Pembibitan & Penggemukan','Kandang Panggung Terpadu Blok Utara',25,28,10,18,4,1,0,48000000.00,'2026-04-15','aktif','Lahir 4 anakan sehat, 1 anakan mati saat indukan melahirkan pertama kali.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `livestock_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livestock_categories`
--

DROP TABLE IF EXISTS `livestock_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livestock_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('sapi','kambing_domba','unggas','lainnya') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livestock_categories`
--

LOCK TABLES `livestock_categories` WRITE;
/*!40000 ALTER TABLE `livestock_categories` DISABLE KEYS */;
INSERT INTO `livestock_categories` VALUES (1,'Sapi Potong & Penggemukan Limousin / Simental','sapi','Program penggemukan intensif sapi potong selama 4-6 bulan untuk Idul Adha dan pasar daging.','2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'Kambing Boer & Peranakan Etawa (PE)','kambing_domba','Budidaya pembibitan dan penggemukan kambing pedaging unggulan.','2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `livestock_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livestock_operations`
--

DROP TABLE IF EXISTS `livestock_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livestock_operations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `category` enum('pakan_konsentrat','hijauan','vaksin_obat','vitamin','perawatan_kandang','tenaga_kerja','lainnya') NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `supplier_or_doctor` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livestock_operations_batch_id_foreign` (`batch_id`),
  CONSTRAINT `livestock_operations_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `livestock_batches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livestock_operations`
--

LOCK TABLES `livestock_operations` WRITE;
/*!40000 ALTER TABLE `livestock_operations` DISABLE KEYS */;
INSERT INTO `livestock_operations` VALUES (1,1,'pakan_konsentrat','Konsentrat Sapi Penggemukan Protein 16% (30 sak)',30.00,'sak',185000.00,5550000.00,'2026-08-10','PT Ternak Jaya Abadi',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,1,'vaksin_obat','Vaksinasi PMK, Vitamin B-Complex & Obat Cacing',10.00,'dosis',95000.00,950000.00,'2026-08-18','drh. Ahmad Fauzi (Dinas Peternakan)',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,2,'pakan_konsentrat','Pakan Tambahan Dedak & Mineral Kambing (15 sak)',15.00,'sak',110000.00,1650000.00,'2026-09-05','UD Pakan Makmur',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `livestock_operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livestock_sales`
--

DROP TABLE IF EXISTS `livestock_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livestock_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `sale_type` enum('hewan_hidup','daging','susu','telur','pupuk_kandang','lainnya') NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `sale_date` date NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_phone` varchar(255) DEFAULT NULL,
  `payment_status` enum('lunas','belum_lunas') NOT NULL DEFAULT 'lunas',
  `payment_method` varchar(255) NOT NULL DEFAULT 'tunai',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `livestock_sales_invoice_number_unique` (`invoice_number`),
  KEY `livestock_sales_batch_id_foreign` (`batch_id`),
  CONSTRAINT `livestock_sales_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `livestock_batches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livestock_sales`
--

LOCK TABLES `livestock_sales` WRITE;
/*!40000 ALTER TABLE `livestock_sales` DISABLE KEYS */;
INSERT INTO `livestock_sales` VALUES (1,1,'INV-TRN-202609-01','hewan_hidup','2 Ekor Sapi Limousin Gemuk (Bobot ~520 kg & 540 kg)',2.00,'ekor',26500000.00,53000000.00,'2026-09-28','Haji Abdullah (Jagalan Berkah)','081298765432','lunas','transfer_bank',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,1,'INV-TRN-202610-02','pupuk_kandang','Pupuk Organik Kohe Sapi Fermentasi Halus',120.00,'karung',25000.00,3000000.00,'2026-10-10','Kelompok Tani Hortikultura Sukamaju','081344556677','lunas','tunai',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `livestock_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_19_155210_create_bumdes_core_tables',2),(5,'2026_10_19_155246_create_agriculture_tables',2),(6,'2026_10_19_155247_create_livestock_tables',2),(7,'2026_10_19_155248_create_cfd_tables',2),(8,'2026_10_19_155249_create_pamsimas_tables',2),(9,'2026_10_19_155250_create_profit_distributions_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pamsimas_customers`
--

DROP TABLE IF EXISTS `pamsimas_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pamsimas_customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `hamlet` varchar(255) NOT NULL,
  `rt_rw` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `category` enum('rumah_tangga','sosial','usaha') NOT NULL DEFAULT 'rumah_tangga',
  `initial_meter` int(11) NOT NULL DEFAULT 0,
  `installation_date` date DEFAULT NULL,
  `status` enum('aktif','nonaktif','segel') NOT NULL DEFAULT 'aktif',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pamsimas_customers_customer_number_unique` (`customer_number`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pamsimas_customers`
--

LOCK TABLES `pamsimas_customers` WRITE;
/*!40000 ALTER TABLE `pamsimas_customers` DISABLE KEYS */;
INSERT INTO `pamsimas_customers` VALUES (1,'PAM-0001','Sutrisno / Bpk RT 01','3301011503750001','081322334411','Dusun Krajan','RT 01 / RW 01',NULL,'rumah_tangga',120,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(2,'PAM-0002','H. Muhaimin (Masjid Jami Baiturrahim)','3301011208680002','081322334412','Dusun Krajan','RT 02 / RW 01',NULL,'sosial',245,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(3,'PAM-0003','Wahyudi (Bengkel & Cuci Motor Mandiri)','3301012406850003','081322334413','Dusun Krajan','RT 03 / RW 01',NULL,'usaha',310,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(4,'PAM-0004','Ibu Ratna Juwita','3301014510820004','081322334414','Dusun Sumber Air','RT 01 / RW 02',NULL,'rumah_tangga',180,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(5,'PAM-0005','Bambang Irawan','3301011904790005','081322334415','Dusun Sumber Air','RT 02 / RW 02',NULL,'rumah_tangga',95,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(6,'PAM-0006','Musholla Al-Ikhlas','3301010101700006','081322334416','Dusun Sukamaju','RT 01 / RW 03',NULL,'sosial',88,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(7,'PAM-0007','Karto Suwiryo','3301010702650007','081322334417','Dusun Sukamaju','RT 02 / RW 03',NULL,'rumah_tangga',140,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(8,'PAM-0008','Warung Makan Bu Darto','3301015509780008','081322334418','Dusun Krajan','RT 04 / RW 01',NULL,'usaha',415,NULL,'aktif',NULL,'2026-10-19 08:59:23','2026-10-19 08:59:23');
/*!40000 ALTER TABLE `pamsimas_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pamsimas_maintenances`
--

DROP TABLE IF EXISTS `pamsimas_maintenances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pamsimas_maintenances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('listrik_pompa','perbaikan_pipa','kaporit_kimia','honor_operator','penggantian_meteran','lainnya') NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `technician_or_vendor` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pamsimas_maintenances_maintenance_code_unique` (`maintenance_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pamsimas_maintenances`
--

LOCK TABLES `pamsimas_maintenances` WRITE;
/*!40000 ALTER TABLE `pamsimas_maintenances` DISABLE KEYS */;
INSERT INTO `pamsimas_maintenances` VALUES (1,'MNT-PAM-202610-01','Pembayaran Listrik PLN Token Pompa Submersible Sumur Dalam','listrik_pompa',1850000.00,'2026-10-05','PLN Rayon Desa','Pembelian token listrik 2x @ Rp 925.000 untuk pengisian tandon utama.','2026-10-19 08:59:23','2026-10-19 08:59:23'),(2,'MNT-PAM-202610-02','Penggantian Pipa PVC 2 Inch Pecah & Lem Aksesoris di RT 03 Dusun Krajan','perbaikan_pipa',480000.00,'2026-10-12','Pak Joko Tukang Pipa','Perbaikan kebocoran jalur pipa utama dekat jembatan desa.','2026-10-19 08:59:23','2026-10-19 08:59:23'),(3,'MNT-PAM-202610-03','Pembelian Kaporit Tablet & Tawas Penjernih Air (2 Pail)','kaporit_kimia',350000.00,'2026-10-14','CV Sumber Kimia Tirta','Desinfeksi rutin reservoir penampungan air desa.','2026-10-19 08:59:23','2026-10-19 08:59:23'),(4,'MNT-PAM-202610-004','pipa','perbaikan_pipa',100000.00,'2026-10-20','pipa',NULL,'2026-10-19 23:47:48','2026-10-19 23:47:48');
/*!40000 ALTER TABLE `pamsimas_maintenances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pamsimas_settings`
--

DROP TABLE IF EXISTS `pamsimas_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pamsimas_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate_per_m3_social` decimal(10,2) NOT NULL DEFAULT 1500.00,
  `rate_per_m3_household` decimal(10,2) NOT NULL DEFAULT 2500.00,
  `rate_per_m3_business` decimal(10,2) NOT NULL DEFAULT 4000.00,
  `subscription_fee` decimal(10,2) NOT NULL DEFAULT 10000.00,
  `late_fee` decimal(10,2) NOT NULL DEFAULT 5000.00,
  `due_date_day` int(11) NOT NULL DEFAULT 20,
  `water_source_info` varchar(255) NOT NULL DEFAULT 'Sumur Bor Dalam Dusun Krajan & Sumber Mata Air Gunung',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pamsimas_settings`
--

LOCK TABLES `pamsimas_settings` WRITE;
/*!40000 ALTER TABLE `pamsimas_settings` DISABLE KEYS */;
INSERT INTO `pamsimas_settings` VALUES (1,1500.00,2500.00,4000.00,10000.00,5000.00,20,'Sumur Bor Kedalaman 120m Dusun Krajan & Sumber Mata Air Pegunungan','2026-10-19 08:59:23','2026-10-19 08:59:23');
/*!40000 ALTER TABLE `pamsimas_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pamsimas_usages`
--

DROP TABLE IF EXISTS `pamsimas_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pamsimas_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `billing_period` varchar(7) NOT NULL,
  `bill_number` varchar(255) NOT NULL,
  `previous_meter` int(11) NOT NULL DEFAULT 0,
  `current_meter` int(11) NOT NULL DEFAULT 0,
  `usage_m3` int(11) NOT NULL DEFAULT 0,
  `rate_per_m3` decimal(10,2) NOT NULL,
  `water_charge` decimal(15,2) NOT NULL,
  `subscription_fee` decimal(15,2) NOT NULL,
  `late_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `due_date` date NOT NULL,
  `payment_status` enum('belum_lunas','lunas') NOT NULL DEFAULT 'belum_lunas',
  `paid_at` datetime DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `collected_by` varchar(255) DEFAULT NULL,
  `reading_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pamsimas_usages_bill_number_unique` (`bill_number`),
  KEY `pamsimas_usages_customer_id_foreign` (`customer_id`),
  CONSTRAINT `pamsimas_usages_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `pamsimas_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pamsimas_usages`
--

LOCK TABLES `pamsimas_usages` WRITE;
/*!40000 ALTER TABLE `pamsimas_usages` DISABLE KEYS */;
INSERT INTO `pamsimas_usages` VALUES (1,1,'2026-10','BILL-PAM-202610-0001',120,138,18,2500.00,45000.00,10000.00,0.00,55000.00,'2026-10-20','lunas','2026-10-15 09:30:00','tunai','KW-PAM-202610-001','Hendra Setiawan','2026-10-01','2026-10-19 08:59:23','2026-10-19 08:59:23'),(2,2,'2026-10','BILL-PAM-202610-0002',245,270,25,1500.00,37500.00,10000.00,0.00,47500.00,'2026-10-20','lunas','2026-10-12 14:10:00','tunai','KW-PAM-202610-002','Hendra Setiawan','2026-10-01','2026-10-19 08:59:23','2026-10-19 08:59:23'),(3,3,'2026-10','BILL-PAM-202610-0003',310,345,35,4000.00,140000.00,10000.00,0.00,150000.00,'2026-10-20','belum_lunas',NULL,NULL,NULL,NULL,'2026-10-01','2026-10-19 08:59:23','2026-10-19 08:59:23'),(4,4,'2026-10','BILL-PAM-202610-0004',180,195,15,2500.00,37500.00,10000.00,0.00,47500.00,'2026-10-20','lunas','2026-10-16 11:00:00','tunai','KW-PAM-202610-003','Hendra Setiawan','2026-10-01','2026-10-19 08:59:23','2026-10-19 08:59:23'),(5,8,'2026-10','BILL-PAM-202610-0008',415,448,33,4000.00,132000.00,10000.00,0.00,142000.00,'2026-10-20','lunas','2026-10-17 16:20:00','tunai','KW-PAM-202610-004','Hendra Setiawan','2026-10-01','2026-10-19 08:59:23','2026-10-19 08:59:23'),(6,5,'2026-10','BILL-PAM-202610-0006',95,95,0,2500.00,0.00,10000.00,0.00,10000.00,'2026-10-20','lunas','2026-10-20 06:40:23','tunai','KW-PAM-202610-0006','Hendra Setiawan','2026-10-20','2026-10-19 23:40:06','2026-10-19 23:40:23'),(7,7,'2026-10','BILL-PAM-202610-0007',140,1400,1260,2500.00,3150000.00,10000.00,0.00,3160000.00,'2026-10-20','belum_lunas',NULL,NULL,NULL,NULL,'2026-10-20','2026-10-19 23:45:11','2026-10-19 23:45:11');
/*!40000 ALTER TABLE `pamsimas_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profit_distributions`
--

DROP TABLE IF EXISTS `profit_distributions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profit_distributions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_year` year(4) NOT NULL,
  `total_net_profit` decimal(15,2) NOT NULL,
  `pades_percentage` decimal(5,2) NOT NULL DEFAULT 35.00,
  `pades_amount` decimal(15,2) NOT NULL,
  `capital_reserve_percentage` decimal(5,2) NOT NULL DEFAULT 25.00,
  `capital_reserve_amount` decimal(15,2) NOT NULL,
  `management_incentive_percentage` decimal(5,2) NOT NULL DEFAULT 20.00,
  `management_incentive_amount` decimal(15,2) NOT NULL,
  `social_fund_percentage` decimal(5,2) NOT NULL DEFAULT 10.00,
  `social_fund_amount` decimal(15,2) NOT NULL,
  `education_fund_percentage` decimal(5,2) NOT NULL DEFAULT 10.00,
  `education_fund_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','disetujui_musdes','disalurkan') NOT NULL DEFAULT 'draft',
  `distribution_date` date DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profit_distributions`
--

LOCK TABLES `profit_distributions` WRITE;
/*!40000 ALTER TABLE `profit_distributions` DISABLE KEYS */;
INSERT INTO `profit_distributions` VALUES (1,2025,85000000.00,35.00,29750000.00,25.00,21250000.00,20.00,17000000.00,10.00,8500000.00,10.00,8500000.00,'disalurkan','2026-01-20','Drs. H. Mulyono (Kepala Desa Rakyat Mandiri)','Telah disahkan dalam Musyawarah Desa (Musdes) Laporan Pertanggungjawaban BUMDes TA 2025.','2026-10-19 08:59:23','2026-10-19 08:59:23');
/*!40000 ALTER TABLE `profit_distributions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('M7wJMsgb8Uoo4DjJSIwwaYe6vuWarbnvSJ3mm20x',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSTA3U3k1YmJZa1NYQU9aWXAxM2dkSFpBNW43T2pVZk5VbWRvWFpPRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91bml0L2NmZCI7czo1OiJyb3V0ZSI7czo5OiJjZmQuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJmMDgyM2M3Mzk3YWFiNGJlZTc2YTFkMjhmZDVlMGQyYjY2ZDYyNTU4OWNmNmNmMDgxNWJlZDMyOGE5ZGE1NDk2Ijt9',1792502416),('S07SOpFKEncsVLI853dUmxngVdfUHQoyF3aUAH6i',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoicHVUaklZSkk1Wlc1NmZjU0ptdW5pNEhBT3U3Q0FnMFprVFRQcHRCUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rZXVhbmdhbi90cmFuc2Frc2kiO3M6NToicm91dGUiO3M6MjA6ImZpbmFuY2UudHJhbnNhY3Rpb25zIjt9czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiMTQ2NzY0NzRhNzFiNGI2MDMxOWQwYWE3NGJhZDFjOWIxZGY3OWE2YzIyODY3NWJmM2NiMGU4MTk4M2JmNjY5MyI7fQ==',1792500815);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(255) NOT NULL,
  `unit_code` varchar(255) NOT NULL DEFAULT 'pusat',
  `account_id` bigint(20) unsigned NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `payment_method` enum('tunai','transfer_bank') NOT NULL DEFAULT 'tunai',
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `recipient_or_payer` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  KEY `transactions_account_id_foreign` (`account_id`),
  KEY `transactions_created_by_foreign` (`created_by`),
  CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (10,'BKM-202610-006','cfd',20,'income',1580000.00,'2026-10-18','tunai','cfd',NULL,'Pedagang UMKM CFD Pekan 41 & 42','Penerimaan karcis retribusi lapak CFD Minggu tanggal 11 & 18 Oktober 2026.','BKM-CFD-202610-01',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(11,'BKM-202610-007','cfd',21,'income',1000000.00,'2026-10-18','tunai','cfd',NULL,'Pengunjung CFD Rakyat Mandiri','Penerimaan retribusi parkir kendaraan roda 2 dan roda 4 CFD.','BKM-CFD-202610-02',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(12,'BKK-202610-005','cfd',29,'expense',670000.00,'2026-10-18','tunai','cfd',NULL,'Petugas Kebersihan Sampah & Linmas Desa','Honor petugas kebersihan sampah dan pengamanan linmas CFD 2 pekan.','BKK-CFD-202610-01',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(13,'BKM-202610-008','pamsimas',22,'income',309500.00,'2026-10-17','tunai','pamsimas',NULL,'Warga Pelanggan Pamsimas','Penerimaan pembayaran rekening pemakaian air bersih warga periode Oktober 2026.','BKM-PAM-202610-01',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(14,'BKK-202610-006','pamsimas',31,'expense',1850000.00,'2026-10-05','tunai','pamsimas',NULL,'PLN Rayon Desa','Pembelian token listrik PLN pompa submersible sumur dalam dusun krajan.','MNT-PAM-202610-01',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(15,'BKK-202610-007','pamsimas',32,'expense',830000.00,'2026-10-14','tunai','pamsimas',NULL,'Toko Bangunan & Supplier Kimia','Perbaikan pipa bocor dan pembelian kaporit tablet penjernih air.','MNT-PAM-202610-02',2,'2026-10-19 08:59:23','2026-10-19 08:59:23'),(18,'BKM-202610-009','cfd',1,'income',2500000.00,'2026-10-20','tunai',NULL,NULL,'Pengelola CFD','ini keseluruhan pemasukan minggu ini dari lapak saja','BKM-202610-009',5,'2026-10-19 23:27:41','2026-10-19 23:27:41'),(19,'BKK-202610-010','cfd',1,'expense',500000.00,'2026-10-20','tunai',NULL,NULL,'kebersihan','pengeluaran','BKK-202610-010',5,'2026-10-19 23:33:05','2026-10-19 23:33:05'),(22,'BKM-KRC-20261020-001','cfd',20,'income',22000.00,'2026-10-20','tunai','cfd',6,'Minuman Sandi','Retribusi Lapak w43 (Minuman Sandi) - KRC-20261020-001','KRC-20261020-001',5,'2026-10-20 00:28:34','2026-10-20 00:28:34');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'direktur',
  `unit_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Joko Supriyono, S.E.','direktur@bumdes.desa.id','direktur','pusat','081234567890',NULL,'$2y$12$TO81LJV6ayTOQI8WbxJl.OmgZASwzWX/EQPvdRpf9AMv0Tk1OtU0.',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(2,'Siti Aminah, A.Md.Ak.','bendahara@bumdes.desa.id','bendahara','pusat','081234567891',NULL,'$2y$12$R2Ls6qnCgwWXRg58A2J.7.CKCW2CfGy1OsCNJbIhloCQX/wFOHInm',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(3,'Slamet Riyadi','pertanian@bumdes.desa.id','admin_unit','pertanian','081234567892',NULL,'$2y$12$tLRusDRV5EsW2yX56/halualLrW95nPFH2hRDvux9u8gAN0vYuRvu',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(4,'Bambang Sutrisno','peternakan@bumdes.desa.id','admin_unit','peternakan','081234567893',NULL,'$2y$12$.UA13OmcKjMKJgRuk9BsaOfhtiv.BZ/Ft73UYPEXBaFsNzcSdjp8O',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(5,'Dian Wahyudi','cfd@bumdes.desa.id','admin_unit','cfd','081234567894',NULL,'$2y$12$pnxzx7tKsHU0F.IeckROAu6s2KWLrfJDbtIUWXNi./VWJECwSkA/u',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22'),(6,'Hendra Setiawan','pamsimas@bumdes.desa.id','admin_unit','pamsimas','081234567895',NULL,'$2y$12$IPTS2KQMW95.CzGlvd0l3u7w5qhRjIueBT/lQelLBm0MQ.Ga9ZunG',NULL,'2026-10-19 08:59:22','2026-10-19 08:59:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'bumdes_rakyat_mandiri'
--

--
-- Dumping routines for database 'bumdes_rakyat_mandiri'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-20 20:22:48
