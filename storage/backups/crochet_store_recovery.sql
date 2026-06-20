-- MySQL dump 10.13  Distrib 5.7.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: crochet_store
-- ------------------------------------------------------
-- Server version	5.7.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'product.created','App\\Models\\Product',27,'Created product Eye',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 04:45:24','2026-06-16 04:45:24'),(2,1,'order.status','App\\Models\\Order',17,'Order CRS-2026-0017: confirmed → delivered','{\"to\": \"delivered\", \"from\": \"confirmed\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:09:47','2026-06-16 05:09:47'),(3,1,'payment.verified','App\\Models\\Order',2,'Verified 1300.00 for order CRS-2026-0002',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:15:14','2026-06-16 05:15:14'),(4,1,'payment.verified','App\\Models\\Order',10,'Verified 605.00 for order CRS-2026-0010',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:15:15','2026-06-16 05:15:15'),(5,1,'payment.verified','App\\Models\\Order',10,'Verified 605.00 for order CRS-2026-0010',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:15:16','2026-06-16 05:15:16'),(6,NULL,'order.status','App\\Models\\Order',5,'Order CRS-2026-0005: processing → delivered','{\"to\": \"delivered\", \"from\": \"processing\"}','127.0.0.1','Symfony','2026-06-16 05:15:59','2026-06-16 05:15:59'),(7,1,'order.status','App\\Models\\Order',19,'Order CRS-2026-0019: confirmed → processing','{\"to\": \"processing\", \"from\": \"confirmed\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:17:11','2026-06-16 05:17:11'),(8,1,'payment.verified','App\\Models\\Order',20,'Verified 300.00 for order CRS-2026-0020',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:25:45','2026-06-16 05:25:45'),(9,1,'payment.verified','App\\Models\\Order',18,'Verified 475.00 for order CRS-2026-0018',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:33:34','2026-06-16 05:33:34'),(10,1,'order.status','App\\Models\\Order',18,'Order CRS-2026-0018: partially_paid → delivered','{\"to\": \"delivered\", \"from\": \"partially_paid\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','2026-06-16 05:33:43','2026-06-16 05:33:43'),(11,1,'payment.verified','App\\Models\\Order',23,'Verified 410.00 for order CRS-2026-0023',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-16 08:42:10','2026-06-16 08:42:10'),(12,1,'product.updated','App\\Models\\Product',1,'Updated product Cuddly Bear Amigurumi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 00:51:56','2026-06-17 00:51:56'),(13,1,'product.updated','App\\Models\\Product',2,'Updated product Tiny Bunny Plush',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 00:53:16','2026-06-17 00:53:16'),(14,1,'product.updated','App\\Models\\Product',3,'Updated product Crochet Octopus',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 00:53:28','2026-06-17 00:53:28'),(15,1,'product.updated','App\\Models\\Product',4,'Updated product Mini Dinosaur Set',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:22:26','2026-06-17 01:22:26'),(16,1,'product.updated','App\\Models\\Product',4,'Updated product Mini Dinosaur Set',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:23:07','2026-06-17 01:23:07'),(17,1,'product.updated','App\\Models\\Product',26,'Updated product Flower Brooch Pin',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:23:30','2026-06-17 01:23:30'),(18,1,'product.updated','App\\Models\\Product',19,'Updated product Market Mesh Bag',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:23:43','2026-06-17 01:23:43'),(19,1,'product.updated','App\\Models\\Product',15,'Updated product Granny Square Cushion Cover',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:23:55','2026-06-17 01:23:55'),(20,1,'product.updated','App\\Models\\Product',8,'Updated product Striped Winter Scarf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:24:06','2026-06-17 01:24:06'),(21,1,'product.updated','App\\Models\\Product',5,'Updated product Sleepy Cat Doll',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:24:50','2026-06-17 01:24:50'),(22,1,'product.updated','App\\Models\\Product',6,'Updated product Penguin Keychain',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:25:33','2026-06-17 01:25:33'),(23,1,'product.updated','App\\Models\\Product',9,'Updated product Cozy Cardigan Sweater',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:26:07','2026-06-17 01:26:07'),(24,1,'product.updated','App\\Models\\Product',10,'Updated product Baby Booties Set',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:26:22','2026-06-17 01:26:22'),(25,1,'product.updated','App\\Models\\Product',11,'Updated product Slouchy Wool Hat',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:27:27','2026-06-17 01:27:27'),(26,1,'product.updated','App\\Models\\Product',11,'Updated product Slouchy Wool Hat',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:27:34','2026-06-17 01:27:34'),(27,1,'product.updated','App\\Models\\Product',12,'Updated product Boho Plant Hanger',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:27:48','2026-06-17 01:27:48'),(28,1,'product.updated','App\\Models\\Product',13,'Updated product Round Coaster Set (4)',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:28:00','2026-06-17 01:28:00'),(29,1,'product.updated','App\\Models\\Product',16,'Updated product Mandala Table Mat',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:28:40','2026-06-17 01:28:40'),(30,1,'product.updated','App\\Models\\Product',17,'Updated product Crochet Tote Bag',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:28:54','2026-06-17 01:28:54'),(31,1,'product.updated','App\\Models\\Product',18,'Updated product Floral Coin Pouch',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:29:42','2026-06-17 01:29:42'),(32,1,'product.updated','App\\Models\\Product',20,'Updated product Mini Crossbody Bag',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:31:28','2026-06-17 01:31:28'),(33,1,'product.updated','App\\Models\\Product',23,'Updated product Tulip Trio',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:32:01','2026-06-17 01:32:01'),(34,1,'product.updated','App\\Models\\Product',24,'Updated product Crochet Hair Scrunchie',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-17 01:32:42','2026-06-17 01:32:42');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('shipping','billing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'shipping',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nepal',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,4,'shipping','Home','Aarati Sharma','9800000001','House 7, Ward 1',NULL,'Kathmandu','Kathmandu','Bagmati','44601','Nepal',1,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(2,5,'shipping','Home','Bishal Thapa','9800000002','House 14, Ward 2',NULL,'Kathmandu','Kathmandu','Bagmati','44602','Nepal',1,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(3,6,'shipping','Home','Sushmita Gurung','9800000003','House 21, Ward 3',NULL,'Kathmandu','Kathmandu','Bagmati','44603','Nepal',1,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(4,7,'shipping','Home','Niraj Karki','9800000004','House 28, Ward 4',NULL,'Kathmandu','Kathmandu','Bagmati','44604','Nepal',1,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(5,8,'shipping','Home','Pratima Rai','9800000005','House 35, Ward 5',NULL,'Kathmandu','Kathmandu','Bagmati','44605','Nepal',1,'2026-06-16 03:58:29','2026-06-16 03:58:29');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_value_product_variant`
--

DROP TABLE IF EXISTS `attribute_value_product_variant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_value_product_variant` (
  `product_variant_id` bigint(20) unsigned NOT NULL,
  `attribute_value_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_variant_id`,`attribute_value_id`),
  KEY `attribute_value_product_variant_attribute_value_id_foreign` (`attribute_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_value_product_variant`
--

LOCK TABLES `attribute_value_product_variant` WRITE;
/*!40000 ALTER TABLE `attribute_value_product_variant` DISABLE KEYS */;
/*!40000 ALTER TABLE `attribute_value_product_variant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_values_attribute_id_foreign` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_values`
--

LOCK TABLES `attribute_values` WRITE;
/*!40000 ALTER TABLE `attribute_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('hero','promo','sidebar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hero',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Handmade Crochet, Made with Love','Discover unique amigurumi, wearables & home decor','banners/hero-crochet.jpg',NULL,'/shop','Shop Now','hero',1,0,'2026-06-16 03:58:29','2026-06-17 03:37:17'),(2,'Flash Sale Live Now','Up to 20% off selected handmade pieces','',NULL,'/shop?sort=popular','Grab the Deal','hero',1,1,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(3,'Free WhatsApp Support','Tap to learn more','',NULL,'/about',NULL,'promo',1,0,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(4,'Custom Orders Welcome','Tap to learn more','',NULL,'/custom-order',NULL,'promo',1,1,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(5,'Cash on Delivery Available','Tap to learn more','',NULL,'/about',NULL,'promo',1,2,'2026-06-16 03:58:29','2026-06-16 03:58:29');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `cache` VALUES ('crochet-store-cache-crochet.settings','a:21:{s:10:\"store_name\";s:9:\"Sistrella\";s:13:\"store_tagline\";s:40:\"Handmade with love, one stitch at a time\";s:11:\"store_email\";s:23:\"hello@crochetstore.test\";s:11:\"store_phone\";s:13:\"977-9803404215\";s:13:\"store_address\";s:16:\"Kathmandu, Nepal\";s:13:\"currency_code\";s:3:\"NPR\";s:15:\"currency_symbol\";s:3:\"NPR\";s:20:\"prepayment_threshold\";d:500;s:18:\"prepayment_percent\";d:50;s:8:\"tax_rate\";d:0;s:13:\"flat_shipping\";d:100;s:9:\"bank_name\";s:21:\"Nepal Investment Bank\";s:17:\"bank_account_name\";s:23:\"Crochet Store Pvt. Ltd.\";s:19:\"bank_account_number\";s:13:\"0123456789012\";s:8:\"esewa_id\";s:13:\"9779761612457\";s:9:\"khalti_id\";s:13:\"977-9761612457\";s:19:\"low_stock_threshold\";i:5;s:15:\"whatsapp_number\";s:15:\"+977 9803404215\";s:13:\"instagram_url\";s:34:\"https://instagram.com/crochetstore\";s:12:\"facebook_url\";s:33:\"https://facebook.com/crochetstore\";s:10:\"tiktok_url\";s:0:\"\";}',2096968539),('sistrella-cache-crochet.settings','a:21:{s:10:\"store_name\";s:9:\"Sistrella\";s:13:\"store_tagline\";s:40:\"Handmade with love, one stitch at a time\";s:11:\"store_email\";s:23:\"hello@crochetstore.test\";s:11:\"store_phone\";s:13:\"977-9761612457\";s:13:\"store_address\";s:16:\"Kathmandu, Nepal\";s:13:\"currency_code\";s:3:\"NPR\";s:15:\"currency_symbol\";s:3:\"NPR\";s:20:\"prepayment_threshold\";d:500;s:18:\"prepayment_percent\";d:50;s:8:\"tax_rate\";d:0;s:13:\"flat_shipping\";d:100;s:9:\"bank_name\";s:21:\"Nepal Investment Bank\";s:17:\"bank_account_name\";s:23:\"Crochet Store Pvt. Ltd.\";s:19:\"bank_account_number\";s:13:\"0123456789012\";s:8:\"esewa_id\";s:13:\"977-9761612457\";s:9:\"khalti_id\";s:13:\"977-9761612457\";s:19:\"low_stock_threshold\";i:5;s:15:\"whatsapp_number\";s:15:\"+977 9803404215\";s:13:\"instagram_url\";s:34:\"https://instagram.com/crochetstore\";s:12:\"facebook_url\";s:33:\"https://facebook.com/crochetstore\";s:10:\"tiktok_url\";s:0:\"\";}',2096968591);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `saved_for_later` tinyint(1) NOT NULL DEFAULT '0',
  `options` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  KEY `cart_items_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (8,4,4,NULL,1,0,NULL,'2026-06-16 06:30:51','2026-06-16 06:30:51');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_coupon_id_foreign` (`coupon_id`),
  KEY `carts_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,10,NULL,NULL,NULL,'2026-06-16 05:02:18','2026-06-16 05:02:18'),(2,NULL,'PI0GTi6ZcmSrXH8MqNTPlCO8R3BnLHZPLYb06iUE',NULL,NULL,'2026-06-16 05:24:38','2026-06-16 05:24:38'),(3,4,NULL,NULL,NULL,'2026-06-16 05:35:08','2026-06-16 05:35:08'),(4,NULL,'NZEG1QlUEsUjPCD0bl9vU1nJZNBBBQSbAWSzdvnf',NULL,NULL,'2026-06-16 06:30:51','2026-06-16 06:30:51');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_is_active_index` (`parent_id`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Amigurumi','amigurumi','Handmade crochet Amigurumi crafted with premium yarn.',NULL,'bi-emoji-smile',1,1,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(2,1,'Animals','amigurumi-animals',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(3,1,'Dolls','amigurumi-dolls',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(4,1,'Keychains','amigurumi-keychains',NULL,NULL,NULL,1,0,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(5,NULL,'Wearables','wearables','Handmade crochet Wearables crafted with premium yarn.',NULL,'bi-bag-heart',1,1,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(6,5,'Beanies & Hats','wearables-beanies-hats',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(7,5,'Scarves','wearables-scarves',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(8,5,'Sweaters','wearables-sweaters',NULL,NULL,NULL,1,0,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(9,5,'Baby Wear','wearables-baby-wear',NULL,NULL,NULL,1,0,3,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(10,NULL,'Home Decor','home-decor','Handmade crochet Home Decor crafted with premium yarn.',NULL,'bi-house-heart',1,1,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(11,10,'Coasters','home-decor-coasters',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(12,10,'Plant Hangers','home-decor-plant-hangers',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(13,10,'Cushion Covers','home-decor-cushion-covers',NULL,NULL,NULL,1,0,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(14,10,'Wall Hangings','home-decor-wall-hangings',NULL,NULL,NULL,1,0,3,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(15,NULL,'Bags & Pouches','bags-pouches','Handmade crochet Bags & Pouches crafted with premium yarn.',NULL,'bi-handbag',1,1,3,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(16,15,'Tote Bags','bags-pouches-tote-bags',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(17,15,'Pouches','bags-pouches-pouches',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(18,15,'Market Bags','bags-pouches-market-bags',NULL,NULL,NULL,1,0,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(19,NULL,'Flowers & Bouquets','flowers-bouquets','Handmade crochet Flowers & Bouquets crafted with premium yarn.',NULL,'bi-flower1',1,1,4,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(20,19,'Single Flowers','flowers-bouquets-single-flowers',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(21,19,'Bouquets','flowers-bouquets-bouquets',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(22,NULL,'Accessories','accessories','Handmade crochet Accessories crafted with premium yarn.',NULL,'bi-stars',1,0,5,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(23,22,'Hair Accessories','accessories-hair-accessories',NULL,NULL,NULL,1,0,0,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(24,22,'Jewellery','accessories-jewellery',NULL,NULL,NULL,1,0,1,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28'),(25,22,'Bookmarks','accessories-bookmarks',NULL,NULL,NULL,1,0,2,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Curious Customer','curious@example.com','9812345678','Do you ship outside Kathmandu?','Hi! I love your work. Do you deliver to Pokhara, and how long does it take?',1,'2026-06-16 03:58:30','2026-06-16 08:51:44'),(2,'Ruby Chaudhary','rubymahato32@gmail.com','9829274742','test1','ok',1,'2026-06-16 08:51:55','2026-06-16 08:53:04');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_user`
--

DROP TABLE IF EXISTS `coupon_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_user_coupon_id_foreign` (`coupon_id`),
  KEY `coupon_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_user`
--

LOCK TABLES `coupon_user` WRITE;
/*!40000 ALTER TABLE `coupon_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `value` decimal(12,2) NOT NULL,
  `min_order_amount` decimal(12,2) DEFAULT NULL,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `usage_limit_per_user` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','10% off your first order','percent',10.00,500.00,500.00,NULL,1,0,1,NULL,'2026-09-16 03:58:29','2026-06-16 03:58:29','2026-06-16 03:58:29'),(2,'FLAT200','NPR 200 off orders above NPR 2000','fixed',200.00,2000.00,NULL,NULL,NULL,0,1,NULL,'2026-07-16 03:58:29','2026-06-16 03:58:29','2026-06-16 03:58:29'),(3,'FESTIVE15','Festive 15% off (capped)','percent',15.00,NULL,1000.00,NULL,NULL,0,1,NULL,'2026-06-30 03:58:29','2026-06-16 03:58:29','2026-06-16 03:58:29');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_request_images`
--

DROP TABLE IF EXISTS `custom_request_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_request_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `custom_request_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inspiration',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_request_images_custom_request_id_foreign` (`custom_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_request_images`
--

LOCK TABLES `custom_request_images` WRITE;
/*!40000 ALTER TABLE `custom_request_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_request_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_requests`
--

DROP TABLE IF EXISTS `custom_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `preferred_delivery_date` date DEFAULT NULL,
  `quoted_price` decimal(12,2) DEFAULT NULL,
  `quote_note` text COLLATE utf8mb4_unicode_ci,
  `quoted_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','under_review','quoted','accepted','in_production','ready','delivered','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_requests_request_number_unique` (`request_number`),
  KEY `custom_requests_user_id_foreign` (`user_id`),
  KEY `custom_requests_order_id_foreign` (`order_id`),
  KEY `custom_requests_status_created_at_index` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_requests`
--

LOCK TABLES `custom_requests` WRITE;
/*!40000 ALTER TABLE `custom_requests` DISABLE KEYS */;
INSERT INTO `custom_requests` VALUES (1,'CCR-2026-0001',4,'Aarati Sharma','aarati@example.com','9800000001','Custom Elephant Amigurumi','Please make it in soft pastel colours if possible.','Cream','Medium',1,'2026-07-07',NULL,NULL,NULL,NULL,'pending',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(2,'CCR-2026-0002',7,'Niraj Karki','niraj@example.com','9800000004','Personalised Name Bunting','Please make it in soft pastel colours if possible.','Mint','Medium',1,'2026-06-30',NULL,NULL,NULL,NULL,'under_review',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(3,'CCR-2026-0003',8,'Pratima Rai','pratima@example.com','9800000005','Wedding Bouquet (Roses & Lilies)','Please make it in soft pastel colours if possible.','Mint','Small',1,'2026-07-07',3500.00,'Includes premium yarn and gift packaging.','2026-06-11 03:58:30',NULL,'quoted',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(4,'CCR-2026-0004',4,'Aarati Sharma','aarati@example.com','9800000001','Matching Baby Set (Hat + Booties)','Please make it in soft pastel colours if possible.','Sky Blue','Small',1,'2026-06-30',1800.00,'Includes premium yarn and gift packaging.','2026-06-11 03:58:30',NULL,'in_production',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(5,'CCR-2026-0005',5,'Bishal Thapa','bishal@example.com','9800000002','Graduation Bear with Gown','Please make it in soft pastel colours if possible.','Sky Blue','Small',1,'2026-06-30',1200.00,'Includes premium yarn and gift packaging.','2026-06-13 03:58:30',NULL,'delivered',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30');
/*!40000 ALTER TABLE `custom_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_01_000001_add_profile_fields_to_users_table',1),(5,'2025_01_01_000002_create_rbac_tables',1),(6,'2025_01_01_000003_create_settings_table',1),(7,'2025_01_01_000004_create_categories_table',1),(8,'2025_01_01_000005_create_products_table',1),(9,'2025_01_01_000006_create_product_images_table',1),(10,'2025_01_01_000007_create_product_variants_table',1),(11,'2025_01_01_000008_create_product_relations_table',1),(12,'2025_01_01_000009_create_reviews_table',1),(13,'2025_01_01_000010_create_addresses_table',1),(14,'2025_01_01_000011_create_coupons_table',1),(15,'2025_01_01_000012_create_orders_table',1),(16,'2025_01_01_000013_create_order_items_table',1),(17,'2025_01_01_000014_create_payments_table',1),(18,'2025_01_01_000015_create_order_status_history_table',1),(19,'2025_01_01_000016_create_carts_table',1),(20,'2025_01_01_000017_create_wishlists_table',1),(21,'2025_01_01_000018_create_custom_requests_table',1),(22,'2025_01_01_000019_create_marketing_tables',1),(23,'2025_01_01_000020_create_activity_logs_table',1),(24,'2026_06_15_114440_create_personal_access_tokens_table',1),(25,'2026_06_16_000001_create_notifications_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (1,'fan1@example.com',1,'2026-05-17 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(2,'fan2@example.com',1,'2026-05-27 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(3,'fan3@example.com',1,'2026-06-06 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('077b9c34-6f9b-4087-bf28-d49034b076e5','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0022 (Ruby Chaudhary) \\u2014 NPR 300.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0022\"}',NULL,'2026-06-16 05:36:58','2026-06-16 05:36:58'),('08d5648c-dac0-4727-a9e9-85820031ab3c','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"New contact message\",\"message\":\"Ruby Chaudhary: ok\",\"icon\":\"bi-envelope\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/marketing\\/messages\"}','2026-06-17 00:20:54','2026-06-16 08:51:55','2026-06-17 00:20:54'),('0c8e90c4-2a61-4556-914b-fb06656b27c1','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0023 (Ruby Chaudhary) \\u2014 NPR 820.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:22','2026-06-16 06:28:22'),('1113b609-c6a5-4b25-bb35-0f8c60569011','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"New contact message\",\"message\":\"Ruby Chaudhary: ok\",\"icon\":\"bi-envelope\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/marketing\\/messages\"}','2026-06-16 08:53:01','2026-06-16 08:51:55','2026-06-16 08:53:01'),('12ca714d-7ee4-42a7-a69f-c60fdf474736','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"Test order\",\"message\":\"A test order came in\",\"icon\":\"bi-receipt\",\"url\":\"\\/admin\"}',NULL,'2026-06-16 05:23:49','2026-06-16 05:23:49'),('17dff8a0-d679-4eb6-81d8-360ffeb8bff9','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"Payment to verify\",\"message\":\"NPR 410.00 submitted for order CRS-2026-0023 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:47','2026-06-16 06:28:47'),('19a20498-3d4a-46ce-8f68-6c437918559a','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0020 (Notif Tester) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8131\\/admin\\/orders\\/CRS-2026-0020\"}',NULL,'2026-06-16 05:24:39','2026-06-16 05:24:39'),('1fb71b8a-a057-418c-8e63-e3aaf871f6ab','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0018\",\"status\":\"partially_paid\",\"title\":\"Payment verified\",\"message\":\"We verified your payment of NPR 475.00 for order CRS-2026-0018.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0018\"}','2026-06-16 05:36:23','2026-06-16 05:33:34','2026-06-16 05:36:23'),('21e39390-d5d6-4067-8439-ba60f93928da','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0020 (Notif Tester) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8131\\/admin\\/orders\\/CRS-2026-0020\"}','2026-06-16 05:25:15','2026-06-16 05:24:39','2026-06-16 05:25:15'),('2753ba8b-f5c9-4c1c-b661-3d1ed2c628b0','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0022 (Ruby Chaudhary) \\u2014 NPR 300.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0022\"}',NULL,'2026-06-16 05:36:58','2026-06-16 05:36:58'),('2845200a-73e8-4305-bb7b-c0e9de408ea9','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"Payment to verify\",\"message\":\"NPR 410.00 submitted for order CRS-2026-0023 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}','2026-06-16 08:42:29','2026-06-16 06:28:47','2026-06-16 08:42:29'),('29ea8bf7-299e-45eb-a1e1-a18a6b5139b8','App\\Notifications\\OrderStatusNotification','App\\Models\\User',7,'{\"order_number\":\"CRS-2026-0002\",\"status\":\"partially_paid\",\"title\":\"Payment verified\",\"message\":\"We verified your payment of NPR 1,300.00 for order CRS-2026-0002.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0002\"}',NULL,'2026-06-16 05:15:14','2026-06-16 05:15:14'),('2d5cd6a7-24b8-4edd-99dd-b54be297ee70','App\\Notifications\\OrderStatusNotification','App\\Models\\User',7,'{\"order_number\":\"CRS-2026-0010\",\"status\":\"partially_paid\",\"title\":\"Payment verified\",\"message\":\"We verified your payment of NPR 605.00 for order CRS-2026-0010.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0010\"}',NULL,'2026-06-16 05:15:16','2026-06-16 05:15:16'),('2efa7984-e610-4aea-a647-f15dbf2d6493','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"Payment to verify\",\"message\":\"NPR 475.00 submitted for order CRS-2026-0018 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0018\"}',NULL,'2026-06-16 05:33:06','2026-06-16 05:33:06'),('3500d72f-db99-4b8a-93ef-92d87598af28','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"Payment to verify\",\"message\":\"NPR 410.00 submitted for order CRS-2026-0023 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:47','2026-06-16 06:28:47'),('38e0571b-eff8-413d-9204-f172345fcb3b','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0021 (Aarati Sharma) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8133\\/admin\\/orders\\/CRS-2026-0021\"}',NULL,'2026-06-16 05:35:09','2026-06-16 05:35:09'),('401c1ff1-5a83-40c1-9e7b-6b35946bda99','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"Payment to verify\",\"message\":\"NPR 475.00 submitted for order CRS-2026-0018 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0018\"}',NULL,'2026-06-16 05:33:06','2026-06-16 05:33:06'),('681f3b47-8c0f-48f4-8ac4-a4f485e8e24c','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0019\",\"status\":\"processing\",\"title\":\"Order Processing\",\"message\":\"Your order CRS-2026-0019 is now Processing.\",\"icon\":\"bi-gear\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0019\"}','2026-06-16 05:23:59','2026-06-16 05:17:11','2026-06-16 05:23:59'),('6931ba88-7847-4d64-81d2-3bc310f89ec2','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"Payment to verify\",\"message\":\"NPR 410.00 submitted for order CRS-2026-0023 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:47','2026-06-16 06:28:47'),('6da29aef-6442-4eb4-abc5-8aeba125bb12','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0022 (Ruby Chaudhary) \\u2014 NPR 300.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0022\"}','2026-06-16 08:42:29','2026-06-16 05:36:58','2026-06-16 08:42:29'),('6f31ac28-7033-4706-8eca-b8fc795a7e0d','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0023 (Ruby Chaudhary) \\u2014 NPR 820.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:22','2026-06-16 06:28:22'),('788c5f1b-f6a4-4409-9bd4-4ed68027ae3c','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0021 (Aarati Sharma) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8133\\/admin\\/orders\\/CRS-2026-0021\"}','2026-06-16 08:42:29','2026-06-16 05:35:09','2026-06-16 08:42:29'),('799818c3-c0c6-4508-b130-c23ff3f8cb3b','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0020 (Notif Tester) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8131\\/admin\\/orders\\/CRS-2026-0020\"}',NULL,'2026-06-16 05:24:39','2026-06-16 05:24:39'),('a0116956-d50c-4342-ab2b-8143f917fce1','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"Test order\",\"message\":\"A test order came in\",\"icon\":\"bi-receipt\",\"url\":\"\\/admin\"}',NULL,'2026-06-16 05:23:49','2026-06-16 05:23:49'),('a2fcaa68-f551-41b5-8323-0b30b074295a','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"New contact message\",\"message\":\"Ruby Chaudhary: ok\",\"icon\":\"bi-envelope\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/marketing\\/messages\"}',NULL,'2026-06-16 08:51:55','2026-06-16 08:51:55'),('a53e549c-bfe3-4db9-ae84-2b4616d5ecc2','App\\Notifications\\AdminEventNotification','App\\Models\\User',2,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0022 (Ruby Chaudhary) \\u2014 NPR 300.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0022\"}',NULL,'2026-06-16 05:36:58','2026-06-16 05:36:58'),('a6e69c12-c0f9-4cf7-ad80-b53b07a4a715','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0023\",\"status\":\"partially_paid\",\"title\":\"Payment verified\",\"message\":\"We verified your payment of NPR 410.00 for order CRS-2026-0023.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 08:42:12','2026-06-16 08:42:12'),('a7afbab8-77ab-4ffe-b967-7d66456ae206','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0021 (Aarati Sharma) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8133\\/admin\\/orders\\/CRS-2026-0021\"}',NULL,'2026-06-16 05:35:09','2026-06-16 05:35:09'),('aa3d0184-b935-42c2-8e65-f3c0ea16a475','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0023\",\"status\":\"pending_payment\",\"title\":\"Order placed\",\"message\":\"Thanks! We\'ve received your order CRS-2026-0023 (NPR 820.00). Please pay the advance to confirm it.\",\"icon\":\"bi-bag-check\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0023\"}','2026-06-16 06:30:11','2026-06-16 06:28:22','2026-06-16 06:30:11'),('b0dbc480-eecb-4b28-9d7a-0ef2096284fd','App\\Notifications\\OrderStatusNotification','App\\Models\\User',7,'{\"order_number\":\"CRS-2026-0010\",\"status\":\"partially_paid\",\"title\":\"Payment verified\",\"message\":\"We verified your payment of NPR 605.00 for order CRS-2026-0010.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0010\"}',NULL,'2026-06-16 05:15:15','2026-06-16 05:15:15'),('b60183b7-d376-43b3-b334-4e1bb87c424b','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0018\",\"status\":\"delivered\",\"title\":\"Order Delivered\",\"message\":\"Your order CRS-2026-0018 is now Delivered.\",\"icon\":\"bi-box-seam\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0018\"}','2026-06-16 05:34:34','2026-06-16 05:33:43','2026-06-16 05:34:34'),('bcb8ac69-32bc-49a6-8255-4e288e5e34cf','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0021 (Aarati Sharma) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8133\\/admin\\/orders\\/CRS-2026-0021\"}',NULL,'2026-06-16 05:35:09','2026-06-16 05:35:09'),('c1eb044b-9954-4466-9dc9-5af5e87c7ff2','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"Payment to verify\",\"message\":\"NPR 475.00 submitted for order CRS-2026-0018 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0018\"}','2026-06-16 05:33:25','2026-06-16 05:33:06','2026-06-16 05:33:25'),('cb102f6d-ead3-4c29-bde4-2a53bf573d5f','App\\Notifications\\AdminEventNotification','App\\Models\\User',9,'{\"title\":\"Test order\",\"message\":\"A test order came in\",\"icon\":\"bi-receipt\",\"url\":\"\\/admin\"}',NULL,'2026-06-16 05:23:49','2026-06-16 05:23:49'),('cd009fae-d9f3-4edd-ae5e-77e4b365ab3d','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"Test order\",\"message\":\"A test order came in\",\"icon\":\"bi-receipt\",\"url\":\"\\/admin\"}','2026-06-16 05:24:17','2026-06-16 05:23:49','2026-06-16 05:24:17'),('da505e57-6b05-48b8-a0d8-bea49ff7d32c','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0020 (Notif Tester) \\u2014 NPR 950.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8131\\/admin\\/orders\\/CRS-2026-0020\"}',NULL,'2026-06-16 05:24:39','2026-06-16 05:24:39'),('e0da1701-790c-4465-b0c6-213f52f2b48b','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"Payment to verify\",\"message\":\"NPR 475.00 submitted for order CRS-2026-0018 \\u2014 please verify.\",\"icon\":\"bi-cash-coin\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0018\"}',NULL,'2026-06-16 05:33:06','2026-06-16 05:33:06'),('e9e5351e-3ff9-4da6-ad75-ef40880e3b41','App\\Notifications\\AdminEventNotification','App\\Models\\User',3,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0023 (Ruby Chaudhary) \\u2014 NPR 820.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}',NULL,'2026-06-16 06:28:22','2026-06-16 06:28:22'),('eab99204-0dcc-4b00-a140-5100922c37fc','App\\Notifications\\AdminEventNotification','App\\Models\\User',1,'{\"title\":\"New order placed\",\"message\":\"Order CRS-2026-0023 (Ruby Chaudhary) \\u2014 NPR 820.00.\",\"icon\":\"bi-receipt\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/CRS-2026-0023\"}','2026-06-16 08:42:29','2026-06-16 06:28:22','2026-06-16 08:42:29'),('ead1aff0-00b6-45f1-bf40-ae7ac6fa1055','App\\Notifications\\OrderStatusNotification','App\\Models\\User',4,'{\"order_number\":\"CRS-2026-0021\",\"status\":\"pending_payment\",\"title\":\"Order placed\",\"message\":\"Thanks! We\'ve received your order CRS-2026-0021 (NPR 950.00). Please pay the advance to confirm it.\",\"icon\":\"bi-bag-check\",\"url\":\"http:\\/\\/127.0.0.1:8133\\/account\\/orders\\/CRS-2026-0021\"}',NULL,'2026-06-16 05:35:09','2026-06-16 05:35:09'),('f7e88d87-69ab-4981-be12-0b50909880b4','App\\Notifications\\OrderStatusNotification','App\\Models\\User',10,'{\"order_number\":\"CRS-2026-0022\",\"status\":\"confirmed\",\"title\":\"Order placed\",\"message\":\"Thanks! We\'ve received your order CRS-2026-0022 (NPR 300.00). It\'s confirmed for Cash on Delivery.\",\"icon\":\"bi-bag-check\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/account\\/orders\\/CRS-2026-0022\"}',NULL,'2026-06-16 05:36:58','2026-06-16 05:36:58'),('fa462705-3d14-49a9-bd6a-aa75f39dda6f','App\\Notifications\\OrderStatusNotification','App\\Models\\User',4,'{\"order_number\":\"CRS-2026-0005\",\"status\":\"delivered\",\"title\":\"Order Delivered\",\"message\":\"Your order CRS-2026-0005 is now Delivered.\",\"icon\":\"bi-box-seam\",\"url\":\"http:\\/\\/localhost\\/account\\/orders\\/CRS-2026-0005\"}',NULL,'2026-06-16 05:15:59','2026-06-16 05:15:59');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` json DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,26,NULL,'Flower Brooch Pin','CRS-FLO-GT4I',NULL,256.00,1,256.00,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(2,1,1,NULL,'Cuddly Bear Amigurumi','CRS-CUD-QAK1',NULL,850.00,2,1700.00,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(3,1,8,NULL,'Striped Winter Scarf','CRS-STR-C4PS',NULL,950.00,1,950.00,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(4,2,4,NULL,'Mini Dinosaur Set','CRS-MIN-VQBP',NULL,1250.00,2,2500.00,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(5,3,26,NULL,'Flower Brooch Pin','CRS-FLO-GT4I',NULL,256.00,1,256.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(6,3,5,NULL,'Sleepy Cat Doll','CRS-SLE-OOKF',NULL,780.00,1,780.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(7,3,23,NULL,'Tulip Trio','CRS-TUL-NMCA',NULL,750.00,1,750.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(8,4,19,NULL,'Market Mesh Bag','CRS-MAR-ZFQS',NULL,1100.00,3,3300.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(9,4,10,NULL,'Baby Booties Set','CRS-BAB-OOOO',NULL,450.00,1,450.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(10,4,12,NULL,'Boho Plant Hanger','CRS-BOH-VCCA',NULL,550.00,3,1650.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(11,5,4,NULL,'Mini Dinosaur Set','CRS-MIN-VQBP',NULL,1250.00,2,2500.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(12,5,24,NULL,'Crochet Hair Scrunchie','CRS-CRO-OMOB',NULL,180.00,2,360.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(13,5,23,NULL,'Tulip Trio','CRS-TUL-NMCA',NULL,750.00,2,1500.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(14,6,2,NULL,'Tiny Bunny Plush','CRS-TIN-XN5H',NULL,650.00,2,1300.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(15,6,9,NULL,'Cozy Cardigan Sweater','CRS-COZ-Y45T',NULL,2800.00,2,5600.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(16,7,12,NULL,'Boho Plant Hanger','CRS-BOH-VCCA',NULL,550.00,2,1100.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(17,7,24,NULL,'Crochet Hair Scrunchie','CRS-CRO-OMOB',NULL,180.00,1,180.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(18,8,16,NULL,'Mandala Table Mat','CRS-MAN-UNSE',NULL,720.00,3,2160.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(19,9,16,NULL,'Mandala Table Mat','CRS-MAN-UNSE',NULL,720.00,2,1440.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(20,9,10,NULL,'Baby Booties Set','CRS-BAB-OOOO',NULL,450.00,2,900.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(21,9,9,NULL,'Cozy Cardigan Sweater','CRS-COZ-Y45T',NULL,2800.00,3,8400.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(22,10,12,NULL,'Boho Plant Hanger','CRS-BOH-VCCA',NULL,550.00,1,550.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(23,10,11,NULL,'Slouchy Wool Hat','CRS-SLO-YQJQ',NULL,560.00,1,560.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(24,11,3,NULL,'Crochet Octopus','CRS-CRO-TTXF',NULL,720.00,3,2160.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(25,12,19,NULL,'Market Mesh Bag','CRS-MAR-ZFQS',NULL,1100.00,3,3300.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(26,12,2,NULL,'Tiny Bunny Plush','CRS-TIN-XN5H',NULL,650.00,2,1300.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(27,13,25,NULL,'Beaded Bookmark','CRS-BEA-RH0K',NULL,220.00,3,660.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(28,14,26,NULL,'Flower Brooch Pin','CRS-FLO-GT4I',NULL,256.00,2,512.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(29,15,4,NULL,'Mini Dinosaur Set','CRS-MIN-VQBP',NULL,1250.00,3,3750.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(30,15,9,NULL,'Cozy Cardigan Sweater','CRS-COZ-Y45T',NULL,2800.00,2,5600.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(31,15,8,NULL,'Striped Winter Scarf','CRS-STR-C4PS',NULL,950.00,2,1900.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(32,16,16,NULL,'Mandala Table Mat','CRS-MAN-UNSE',NULL,720.00,2,1440.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(33,16,22,NULL,'Single Sunflower Stem','CRS-SIN-HQC6',NULL,300.00,1,300.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(34,16,15,NULL,'Granny Square Cushion Cover','CRS-GRA-DAJC',NULL,1200.00,3,3600.00,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(35,17,27,NULL,'Eye','CRS-EYE-BIEX',NULL,200.00,1,200.00,'2026-06-16 05:02:43','2026-06-16 05:02:43'),(36,18,1,NULL,'Cuddly Bear Amigurumi','CRS-CUD-QAK1',NULL,850.00,1,850.00,'2026-06-16 05:13:43','2026-06-16 05:13:43'),(37,19,27,NULL,'Eye','CRS-EYE-BIEX',NULL,200.00,1,200.00,'2026-06-16 05:14:44','2026-06-16 05:14:44'),(38,20,1,NULL,'Cuddly Bear Amigurumi','CRS-CUD-QAK1',NULL,850.00,1,850.00,'2026-06-16 05:24:39','2026-06-16 05:24:39'),(39,21,1,NULL,'Cuddly Bear Amigurumi','CRS-CUD-QAK1',NULL,850.00,1,850.00,'2026-06-16 05:35:09','2026-06-16 05:35:09'),(40,22,6,NULL,'Penguin Keychain','CRS-PEN-UKJE',NULL,200.00,1,200.00,'2026-06-16 05:36:58','2026-06-16 05:36:58'),(41,23,3,NULL,'Crochet Octopus','CRS-CRO-TTXF',NULL,720.00,1,720.00,'2026-06-16 06:28:22','2026-06-16 06:28:22');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `from_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_history_changed_by_foreign` (`changed_by`),
  KEY `order_status_history_order_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES (1,1,NULL,'pending_payment','Seeded demo order.',NULL,'2026-06-16 03:58:29','2026-06-16 03:58:29'),(2,2,NULL,'payment_submitted','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(3,3,NULL,'partially_paid','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(4,4,NULL,'confirmed','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(5,5,NULL,'processing','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(6,6,NULL,'shipped','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(7,7,NULL,'delivered','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(8,8,NULL,'cancelled','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(9,9,NULL,'pending_payment','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(10,10,NULL,'payment_submitted','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(11,11,NULL,'partially_paid','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(12,12,NULL,'confirmed','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(13,13,NULL,'processing','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(14,14,NULL,'shipped','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(15,15,NULL,'delivered','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(16,16,NULL,'cancelled','Seeded demo order.',NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(17,17,NULL,'confirmed','Order placed.',10,'2026-06-16 05:02:43','2026-06-16 05:02:43'),(18,17,'confirmed','delivered',NULL,1,'2026-06-16 05:09:47','2026-06-16 05:09:47'),(19,18,NULL,'pending_payment','Order placed.',10,'2026-06-16 05:13:43','2026-06-16 05:13:43'),(20,19,NULL,'confirmed','Order placed.',10,'2026-06-16 05:14:44','2026-06-16 05:14:44'),(21,2,'payment_submitted','partially_paid','Payment verified by admin.',1,'2026-06-16 05:15:14','2026-06-16 05:15:14'),(22,10,'payment_submitted','partially_paid','Payment verified by admin.',1,'2026-06-16 05:15:15','2026-06-16 05:15:15'),(23,5,'processing','delivered','Verify notif',NULL,'2026-06-16 05:15:59','2026-06-16 05:15:59'),(24,19,'confirmed','processing',NULL,1,'2026-06-16 05:17:11','2026-06-16 05:17:11'),(25,20,NULL,'pending_payment','Order placed.',NULL,'2026-06-16 05:24:39','2026-06-16 05:24:39'),(26,18,'pending_payment','payment_submitted','Customer submitted payment proof.',10,'2026-06-16 05:33:06','2026-06-16 05:33:06'),(27,18,'payment_submitted','partially_paid','Payment verified by admin.',1,'2026-06-16 05:33:34','2026-06-16 05:33:34'),(28,18,'partially_paid','delivered',NULL,1,'2026-06-16 05:33:43','2026-06-16 05:33:43'),(29,21,NULL,'pending_payment','Order placed.',4,'2026-06-16 05:35:09','2026-06-16 05:35:09'),(30,22,NULL,'confirmed','Order placed.',10,'2026-06-16 05:36:58','2026-06-16 05:36:58'),(31,23,NULL,'pending_payment','Order placed.',10,'2026-06-16 06:28:22','2026-06-16 06:28:22'),(32,23,'pending_payment','payment_submitted','Customer submitted payment proof.',10,'2026-06-16 06:28:47','2026-06-16 06:28:47'),(33,23,'payment_submitted','partially_paid','Payment verified by admin.',1,'2026-06-16 08:42:10','2026-06-16 08:42:10');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` json DEFAULT NULL,
  `billing_address` json DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `requires_prepayment` tinyint(1) NOT NULL DEFAULT '0',
  `prepayment_threshold` decimal(12,2) NOT NULL DEFAULT '0.00',
  `prepayment_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `advance_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cod_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('cod','prepayment','full_prepaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `status` enum('pending_payment','payment_submitted','partially_paid','confirmed','processing','shipped','delivered','cancelled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  KEY `orders_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'CRS-2026-0001',6,'Sushmita Gurung','sushmita@example.com','9800000003','{\"city\": \"Kathmandu\", \"line1\": \"House 21, Ward 3\", \"line2\": null, \"phone\": \"9800000003\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Sushmita Gurung\", \"postal_code\": \"44603\"}',NULL,2906.00,0.00,0.00,100.00,3006.00,1,500.00,50.00,1503.00,1503.00,0.00,NULL,NULL,'prepayment','pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-01 03:58:29','2026-06-16 03:58:29'),(2,'CRS-2026-0002',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,2500.00,0.00,0.00,100.00,2600.00,1,500.00,50.00,1300.00,1300.00,1300.00,NULL,NULL,'prepayment','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-05 03:58:29','2026-06-16 05:15:14'),(3,'CRS-2026-0003',6,'Sushmita Gurung','sushmita@example.com','9800000003','{\"city\": \"Kathmandu\", \"line1\": \"House 21, Ward 3\", \"line2\": null, \"phone\": \"9800000003\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Sushmita Gurung\", \"postal_code\": \"44603\"}',NULL,1786.00,0.00,0.00,100.00,1886.00,1,500.00,50.00,943.00,943.00,943.00,NULL,NULL,'prepayment','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-05 03:58:30','2026-06-16 03:58:30'),(4,'CRS-2026-0004',5,'Bishal Thapa','bishal@example.com','9800000002','{\"city\": \"Kathmandu\", \"line1\": \"House 14, Ward 2\", \"line2\": null, \"phone\": \"9800000002\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Bishal Thapa\", \"postal_code\": \"44602\"}',NULL,5400.00,0.00,0.00,100.00,5500.00,1,500.00,50.00,2750.00,2750.00,2750.00,NULL,NULL,'prepayment','confirmed',NULL,NULL,NULL,'2026-06-15 03:58:30',NULL,NULL,NULL,'2026-05-25 03:58:30','2026-06-16 03:58:30'),(5,'CRS-2026-0005',4,'Aarati Sharma','aarati@example.com','9800000001','{\"city\": \"Kathmandu\", \"line1\": \"House 7, Ward 1\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Aarati Sharma\", \"postal_code\": \"44601\"}',NULL,4360.00,0.00,0.00,100.00,4460.00,1,500.00,50.00,2230.00,2230.00,2230.00,NULL,NULL,'prepayment','delivered',NULL,NULL,NULL,'2026-06-14 03:58:30',NULL,'2026-06-16 05:15:59',NULL,'2026-06-15 03:58:30','2026-06-16 05:15:59'),(6,'CRS-2026-0006',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,6900.00,0.00,0.00,100.00,7000.00,1,500.00,50.00,3500.00,3500.00,3500.00,NULL,NULL,'prepayment','shipped',NULL,NULL,NULL,'2026-06-08 03:58:30','2026-06-15 03:58:30',NULL,NULL,'2026-05-27 03:58:30','2026-06-16 03:58:30'),(7,'CRS-2026-0007',6,'Sushmita Gurung','sushmita@example.com','9800000003','{\"city\": \"Kathmandu\", \"line1\": \"House 21, Ward 3\", \"line2\": null, \"phone\": \"9800000003\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Sushmita Gurung\", \"postal_code\": \"44603\"}',NULL,1280.00,0.00,0.00,100.00,1380.00,1,500.00,50.00,690.00,690.00,1380.00,NULL,NULL,'prepayment','delivered',NULL,NULL,NULL,'2026-06-15 03:58:30','2026-06-12 03:58:30','2026-06-16 03:58:30',NULL,'2026-06-11 03:58:30','2026-06-16 03:58:30'),(8,'CRS-2026-0008',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,2160.00,0.00,0.00,100.00,2260.00,1,500.00,50.00,1130.00,1130.00,0.00,NULL,NULL,'prepayment','cancelled',NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(9,'CRS-2026-0009',4,'Aarati Sharma','aarati@example.com','9800000001','{\"city\": \"Kathmandu\", \"line1\": \"House 7, Ward 1\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Aarati Sharma\", \"postal_code\": \"44601\"}',NULL,10740.00,0.00,0.00,100.00,10840.00,1,500.00,50.00,5420.00,5420.00,0.00,NULL,NULL,'prepayment','pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-26 03:58:30','2026-06-16 03:58:30'),(10,'CRS-2026-0010',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,1110.00,0.00,0.00,100.00,1210.00,1,500.00,50.00,605.00,605.00,605.00,NULL,NULL,'prepayment','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-14 03:58:30','2026-06-16 05:15:15'),(11,'CRS-2026-0011',6,'Sushmita Gurung','sushmita@example.com','9800000003','{\"city\": \"Kathmandu\", \"line1\": \"House 21, Ward 3\", \"line2\": null, \"phone\": \"9800000003\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Sushmita Gurung\", \"postal_code\": \"44603\"}',NULL,2160.00,0.00,0.00,100.00,2260.00,1,500.00,50.00,1130.00,1130.00,1130.00,NULL,NULL,'prepayment','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-13 03:58:30','2026-06-16 03:58:30'),(12,'CRS-2026-0012',4,'Aarati Sharma','aarati@example.com','9800000001','{\"city\": \"Kathmandu\", \"line1\": \"House 7, Ward 1\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Aarati Sharma\", \"postal_code\": \"44601\"}',NULL,4600.00,0.00,0.00,100.00,4700.00,1,500.00,50.00,2350.00,2350.00,2350.00,NULL,NULL,'prepayment','confirmed',NULL,NULL,NULL,'2026-06-14 03:58:30',NULL,NULL,NULL,'2026-06-15 03:58:30','2026-06-16 03:58:30'),(13,'CRS-2026-0013',4,'Aarati Sharma','aarati@example.com','9800000001','{\"city\": \"Kathmandu\", \"line1\": \"House 7, Ward 1\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Aarati Sharma\", \"postal_code\": \"44601\"}',NULL,660.00,0.00,0.00,100.00,760.00,1,500.00,50.00,380.00,380.00,380.00,NULL,NULL,'prepayment','processing',NULL,NULL,NULL,'2026-06-14 03:58:30',NULL,NULL,NULL,'2026-06-02 03:58:30','2026-06-16 03:58:30'),(14,'CRS-2026-0014',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,512.00,0.00,0.00,100.00,612.00,1,500.00,50.00,306.00,306.00,306.00,NULL,NULL,'prepayment','shipped',NULL,NULL,NULL,'2026-06-07 03:58:30','2026-06-12 03:58:30',NULL,NULL,'2026-06-05 03:58:30','2026-06-16 03:58:30'),(15,'CRS-2026-0015',7,'Niraj Karki','niraj@example.com','9800000004','{\"city\": \"Kathmandu\", \"line1\": \"House 28, Ward 4\", \"line2\": null, \"phone\": \"9800000004\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Niraj Karki\", \"postal_code\": \"44604\"}',NULL,11250.00,0.00,0.00,100.00,11350.00,1,500.00,50.00,5675.00,5675.00,11350.00,NULL,NULL,'prepayment','delivered',NULL,NULL,NULL,'2026-06-14 03:58:30','2026-06-12 03:58:30','2026-06-15 03:58:30',NULL,'2026-06-05 03:58:30','2026-06-16 03:58:30'),(16,'CRS-2026-0016',5,'Bishal Thapa','bishal@example.com','9800000002','{\"city\": \"Kathmandu\", \"line1\": \"House 14, Ward 2\", \"line2\": null, \"phone\": \"9800000002\", \"country\": \"Nepal\", \"district\": \"Kathmandu\", \"province\": \"Bagmati\", \"full_name\": \"Bishal Thapa\", \"postal_code\": \"44602\"}',NULL,5340.00,0.00,0.00,100.00,5440.00,1,500.00,50.00,2720.00,2720.00,0.00,NULL,NULL,'prepayment','cancelled',NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-02 03:58:30','2026-06-16 03:58:30'),(17,'CRS-2026-0017',10,'Ruby Chaudhary','ruby@gmail.com','9803404215','{\"city\": \"ktm\", \"line1\": \"kalanki\", \"line2\": \"lri\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"42000\"}','{\"city\": \"ktm\", \"line1\": \"kalanki\", \"line2\": \"lri\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"42000\"}',200.00,0.00,0.00,100.00,300.00,0,500.00,50.00,0.00,300.00,0.00,NULL,NULL,'cod','delivered','okkk',NULL,NULL,'2026-06-16 05:02:43',NULL,'2026-06-16 05:09:47',NULL,'2026-06-16 05:02:43','2026-06-16 05:09:47'),(18,'CRS-2026-0018',10,'Ruby Chaudhary','ruby@gmail.com','9803404215','{\"city\": \"d\", \"line1\": \"df\", \"line2\": \"d\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"d\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"edff\"}','{\"city\": \"d\", \"line1\": \"df\", \"line2\": \"d\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"d\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"edff\"}',850.00,0.00,0.00,100.00,950.00,1,500.00,50.00,475.00,475.00,475.00,NULL,NULL,'prepayment','delivered','ff',NULL,NULL,NULL,NULL,'2026-06-16 05:33:43',NULL,'2026-06-16 05:13:43','2026-06-16 05:33:43'),(19,'CRS-2026-0019',10,'Ruby Chaudhary','ruby@gmail.com','9803404215','{\"city\": \"ff\", \"line1\": \"ff\", \"line2\": \"ff\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"ff\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"ff\"}','{\"city\": \"ff\", \"line1\": \"ff\", \"line2\": \"ff\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"ff\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"ff\"}',200.00,0.00,0.00,100.00,300.00,0,500.00,50.00,0.00,300.00,0.00,NULL,NULL,'cod','processing','ff',NULL,NULL,'2026-06-16 05:14:44',NULL,NULL,NULL,'2026-06-16 05:14:44','2026-06-16 05:17:11'),(20,'CRS-2026-0020',NULL,'Notif Tester',NULL,'9811111111','{\"city\": \"Kathmandu\", \"line1\": \"Lazimpat\", \"line2\": null, \"phone\": \"9811111111\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Notif Tester\", \"postal_code\": null}','{\"city\": \"Kathmandu\", \"line1\": \"Lazimpat\", \"line2\": null, \"phone\": \"9811111111\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Notif Tester\", \"postal_code\": null}',850.00,0.00,0.00,100.00,950.00,1,500.00,50.00,475.00,475.00,300.00,NULL,NULL,'prepayment','pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-16 05:24:39','2026-06-16 05:25:45'),(21,'CRS-2026-0021',4,'Aarati Sharma','aarati@example.com','9800000001','{\"city\": \"Kathmandu\", \"line1\": \"Baluwatar\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Aarati Sharma\", \"postal_code\": null}','{\"city\": \"Kathmandu\", \"line1\": \"Baluwatar\", \"line2\": null, \"phone\": \"9800000001\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Aarati Sharma\", \"postal_code\": null}',850.00,0.00,0.00,100.00,950.00,1,500.00,50.00,475.00,475.00,0.00,NULL,NULL,'prepayment','pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-16 05:35:09','2026-06-16 05:35:09'),(22,'CRS-2026-0022',10,'Ruby Chaudhary','ruby@gmail.com','9803404215','{\"city\": \",kk,\", \"line1\": \"jkkk\", \"line2\": \"ll\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"889\"}','{\"city\": \",kk,\", \"line1\": \"jkkk\", \"line2\": \"ll\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": null, \"full_name\": \"Ruby Chaudhary\", \"postal_code\": \"889\"}',200.00,0.00,0.00,100.00,300.00,0,500.00,50.00,0.00,300.00,0.00,NULL,NULL,'cod','confirmed',NULL,NULL,NULL,'2026-06-16 05:36:58',NULL,NULL,NULL,'2026-06-16 05:36:58','2026-06-16 05:36:58'),(23,'CRS-2026-0023',10,'Ruby Chaudhary','ruby@gmail.com','9803404215','{\"city\": \"gg\", \"line1\": \"hjh\", \"line2\": \"gg\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"gg\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": null}','{\"city\": \"gg\", \"line1\": \"hjh\", \"line2\": \"gg\", \"phone\": \"9803404215\", \"country\": \"Nepal\", \"district\": null, \"province\": \"gg\", \"full_name\": \"Ruby Chaudhary\", \"postal_code\": null}',720.00,0.00,0.00,100.00,820.00,1,500.00,50.00,410.00,410.00,410.00,NULL,NULL,'prepayment','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-16 06:28:22','2026-06-16 08:42:10');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `kind` enum('advance','balance','full','refund') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'advance',
  `amount` decimal(12,2) NOT NULL,
  `method` enum('esewa','khalti','bank_transfer','cash','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('submitted','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `note` text COLLATE utf8mb4_unicode_ci,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  KEY `payments_order_id_status_index` (`order_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,2,'advance',1300.00,'esewa','ESWGVLSXSVT',NULL,'verified','Paid the advance via eSewa, please verify.',NULL,1,'2026-06-16 05:15:14','2026-06-16 03:58:30','2026-06-16 05:15:14'),(2,3,'advance',943.00,'bank_transfer','TXNA70ZMKMC',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(3,4,'advance',2750.00,'bank_transfer','TXNPOBBECAN',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(4,5,'advance',2230.00,'bank_transfer','TXNNTOKFEE2',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(5,6,'advance',3500.00,'bank_transfer','TXNS0CRXOKL',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(6,7,'full',1380.00,'bank_transfer','TXNP58B8AVH',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(7,10,'advance',605.00,'esewa','ESWJPPM8QQM',NULL,'verified','Paid the advance via eSewa, please verify.',NULL,1,'2026-06-16 05:15:16','2026-06-16 03:58:30','2026-06-16 05:15:16'),(8,11,'advance',1130.00,'bank_transfer','TXNLRYPLD1W',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(9,12,'advance',2350.00,'bank_transfer','TXN6LJPWUJX',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(10,13,'advance',380.00,'bank_transfer','TXN7GNIENVX',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(11,14,'advance',306.00,'bank_transfer','TXNTAZ6KRJO',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(12,15,'full',11350.00,'bank_transfer','TXNB9HIZ8SW',NULL,'verified',NULL,NULL,NULL,'2026-06-16 03:58:30','2026-06-16 03:58:30','2026-06-16 03:58:30'),(13,20,'advance',300.00,'esewa',NULL,NULL,'verified',NULL,'Recorded by admin.',1,'2026-06-16 05:25:45','2026-06-16 05:25:45','2026-06-16 05:25:45'),(14,18,'full',475.00,'esewa','ADJ-032','payments/Si5y0nh7kPuQJ85OoRDhls0D92hcyzCNU529dFNK.jpg','verified',NULL,NULL,1,'2026-06-16 05:33:34','2026-06-16 05:33:06','2026-06-16 05:33:34'),(15,23,'advance',410.00,'cash','sfsdg','payments/xhOW25vr51RAiAVDSlDuPkAU3LmXFU6dt1MEfcmB.jpg','verified','vgdg',NULL,1,'2026-06-16 08:42:10','2026-06-16 06:28:47','2026-06-16 08:42:10');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(1,3),(2,3),(3,3),(4,3),(7,3),(12,3);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dashboard.access','Access admin panel','Dashboard','2026-06-16 03:58:27','2026-06-16 03:58:27'),(2,'orders.view','View orders','Orders','2026-06-16 03:58:27','2026-06-16 03:58:27'),(3,'orders.manage','Manage orders','Orders','2026-06-16 03:58:27','2026-06-16 03:58:27'),(4,'payments.manage','Verify & manage payments','Payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(5,'products.manage','Manage products','Catalogue','2026-06-16 03:58:27','2026-06-16 03:58:27'),(6,'categories.manage','Manage categories','Catalogue','2026-06-16 03:58:27','2026-06-16 03:58:27'),(7,'inventory.manage','Manage inventory','Catalogue','2026-06-16 03:58:27','2026-06-16 03:58:27'),(8,'coupons.manage','Manage coupons','Marketing','2026-06-16 03:58:27','2026-06-16 03:58:27'),(9,'marketing.manage','Manage banners & marketing','Marketing','2026-06-16 03:58:27','2026-06-16 03:58:27'),(10,'customers.view','View customers','Customers','2026-06-16 03:58:27','2026-06-16 03:58:27'),(11,'customers.manage','Manage customers','Customers','2026-06-16 03:58:27','2026-06-16 03:58:27'),(12,'custom.manage','Manage custom requests','Custom Requests','2026-06-16 03:58:27','2026-06-16 03:58:27'),(13,'reports.view','View reports','Reports','2026-06-16 03:58:27','2026-06-16 03:58:27'),(14,'settings.manage','Manage store settings','Settings','2026-06-16 03:58:27','2026-06-16 03:58:27'),(15,'roles.manage','Manage roles & staff','Settings','2026-06-16 03:58:27','2026-06-16 03:58:27');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_is_primary_index` (`product_id`,`is_primary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,27,'products/fgXL0K3sz8RHz9ul45lnpObqdCbQhkESyPJBPwaz.jpg',NULL,1,0,'2026-06-16 04:45:24','2026-06-16 04:45:24'),(2,1,'products/zZdBVXkzlTo6J7cfSkwKvpYXX69HdpImBWDSHkSW.jpg',NULL,1,0,'2026-06-17 00:51:56','2026-06-17 00:51:56'),(3,2,'products/rstmCrkUFWP5Y6pqK5HVlgFIlbIFVZoCOeJDEpFu.jpg',NULL,1,0,'2026-06-17 00:53:16','2026-06-17 00:53:16'),(4,3,'products/eFOb3JFaZdJKR2chTAxJTNIAwVfJDB6o0A9EKEIt.jpg',NULL,1,0,'2026-06-17 00:53:28','2026-06-17 00:53:28'),(5,4,'products/SRjPmUwbduWgQUyrb1ZhO1VtQqZEvaON98RHp3BG.jpg',NULL,1,0,'2026-06-17 01:22:26','2026-06-17 01:22:26'),(6,26,'products/2OtMZdKIc4rL768jTitGmwMFpTrBAO82FUPJ0tdj.jpg',NULL,1,0,'2026-06-17 01:23:30','2026-06-17 01:23:30'),(7,19,'products/J6qLo2YYDzyNI810yxenhKFg51ZE2Yytl7bjVkeh.jpg',NULL,1,0,'2026-06-17 01:23:43','2026-06-17 01:23:43'),(8,15,'products/u0sV6wf57KfcH6RysDbD6uCT4Z7z0k6KnZTfki72.jpg',NULL,1,0,'2026-06-17 01:23:55','2026-06-17 01:23:55'),(9,8,'products/uYcZh79Nz8VCITkpubTpCilJZoEDBVd2FAhELlUi.jpg',NULL,1,0,'2026-06-17 01:24:06','2026-06-17 01:24:06'),(10,5,'products/gb3wtbddIX6861Y32AZzMqOk7I2mpyLXPu3SIFa7.jpg',NULL,1,0,'2026-06-17 01:24:50','2026-06-17 01:24:50'),(11,6,'products/1IOVvRYmiMVPsC04KRA4jWmgBXwyTxQ5VSTKPaWj.jpg',NULL,1,0,'2026-06-17 01:25:33','2026-06-17 01:25:33'),(12,9,'products/Nn1ObYILpLr5Dx9ciTDmDO5EUxcjuuPZpFXgaVYp.jpg',NULL,1,0,'2026-06-17 01:26:07','2026-06-17 01:26:07'),(13,10,'products/5Ne85dH4qtaDPVAvto9BgE0CriFyWN7w7S76c834.jpg',NULL,1,0,'2026-06-17 01:26:22','2026-06-17 01:26:22'),(14,11,'products/vx5GVanJu1FNZY3c2Jbqhm3BBl3sGgjogElPBeaz.jpg',NULL,1,0,'2026-06-17 01:27:34','2026-06-17 01:27:34'),(15,12,'products/pUCkWWZQ3U8Dw9NFHWDP1zzpBO38sLx6dvVQnpMi.jpg',NULL,1,0,'2026-06-17 01:27:48','2026-06-17 01:27:48'),(16,13,'products/V16m7ikUdLQU1CCTP7CAg5uzOf7EIUhYH7O3wtao.jpg',NULL,1,0,'2026-06-17 01:28:00','2026-06-17 01:28:00'),(17,16,'products/ND27udL1bKEJAA8ueuXuHKiUIsdNMEt3s9RiixSN.jpg',NULL,1,0,'2026-06-17 01:28:40','2026-06-17 01:28:40'),(18,17,'products/uZqmdGx43jDmsMvYMmAuOCB0H64WrRDgGfycWd4S.jpg',NULL,1,0,'2026-06-17 01:28:54','2026-06-17 01:28:54'),(19,18,'products/LXtlOGUiVYSVZxEYWmdmxbnCoXoNxoJwj2H1xfTo.jpg',NULL,1,0,'2026-06-17 01:29:42','2026-06-17 01:29:42'),(20,20,'products/no3INi008vJr9FTwdCcYWLR8dWD6W3keZ6eWUwoc.jpg',NULL,1,0,'2026-06-17 01:31:28','2026-06-17 01:31:28'),(21,23,'products/vf347hv3QXzRPeJKz2Q6ILctKaGC9QFpwioRIaJ6.jpg',NULL,1,0,'2026-06-17 01:32:01','2026-06-17 01:32:01'),(22,24,'products/pL2iSplH4FTQGMv5Cj6pLmBuyysZgiiDWuseOlbj.jpg',NULL,1,0,'2026-06-17 01:32:42','2026-06-17 01:32:42');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_links`
--

DROP TABLE IF EXISTS `product_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `linked_product_id` bigint(20) unsigned NOT NULL,
  `type` enum('related','bundle','cross_sell','up_sell') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'related',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_link_unique` (`product_id`,`linked_product_id`,`type`),
  KEY `product_links_linked_product_id_foreign` (`linked_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_links`
--

LOCK TABLES `product_links` WRITE;
/*!40000 ALTER TABLE `product_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `compare_at_price` decimal(12,2) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `track_inventory` tinyint(1) NOT NULL DEFAULT '1',
  `stock` int(11) NOT NULL DEFAULT '0',
  `low_stock_threshold` int(10) unsigned NOT NULL DEFAULT '5',
  `type` enum('simple','variable','bundle','custom') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_trending` tinyint(1) NOT NULL DEFAULT '0',
  `is_best_seller` tinyint(1) NOT NULL DEFAULT '0',
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT '0',
  `is_customizable` tinyint(1) NOT NULL DEFAULT '0',
  `flash_sale_price` decimal(12,2) DEFAULT NULL,
  `flash_sale_starts_at` timestamp NULL DEFAULT NULL,
  `flash_sale_ends_at` timestamp NULL DEFAULT NULL,
  `views` bigint(20) unsigned NOT NULL DEFAULT '0',
  `sales_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` decimal(8,2) DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_is_active_is_featured_index` (`is_active`,`is_featured`),
  KEY `products_is_active_category_id_index` (`is_active`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Cuddly Bear Amigurumi','cuddly-bear-amigurumi','CRS-CUD-QAK1','Handmade Cuddly Bear Amigurumi crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Cuddly Bear Amigurumi</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',850.00,1100.00,467.50,1,11,5,'simple',1,1,1,1,0,0,NULL,NULL,NULL,565,143,0.00,0,342.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 05:35:09',NULL),(2,1,'Tiny Bunny Plush','tiny-bunny-plush','CRS-TIN-XN5H','Handmade Tiny Bunny Plush crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Tiny Bunny Plush</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',650.00,NULL,357.50,1,22,5,'simple',1,0,1,0,1,0,NULL,NULL,NULL,338,16,0.00,0,148.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(3,1,'Crochet Octopus','crochet-octopus','CRS-CRO-TTXF','Handmade Crochet Octopus crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Crochet Octopus</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',720.00,900.00,396.00,1,8,5,'simple',1,0,0,1,0,0,NULL,NULL,NULL,383,43,0.00,0,315.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 06:29:45',NULL),(4,1,'Mini Dinosaur Set','mini-dinosaur-set','CRS-MIN-VQBP','Handmade Mini Dinosaur Set crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Mini Dinosaur Set</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1250.00,NULL,687.50,1,6,5,'simple',1,1,0,0,0,1,NULL,NULL,NULL,112,12,0.00,0,552.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(5,1,'Sleepy Cat Doll','sleepy-cat-doll','CRS-SLE-OOKF','Handmade Sleepy Cat Doll crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Sleepy Cat Doll</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',780.00,NULL,429.00,1,0,5,'simple',1,0,0,0,1,0,NULL,NULL,NULL,232,25,5.00,1,515.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 06:27:45',NULL),(6,1,'Penguin Keychain','penguin-keychain','CRS-PEN-UKJE','Handmade Penguin Keychain crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Penguin Keychain</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',250.00,350.00,137.50,1,39,5,'simple',1,0,1,0,0,0,200.00,'2026-06-15 03:58:00','2026-06-19 03:58:00',182,6,0.00,0,488.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 01:25:33',NULL),(7,5,'Chunky Knit Beanie','chunky-knit-beanie','CRS-CHU-UDZR','Handmade Chunky Knit Beanie crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Chunky Knit Beanie</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',600.00,800.00,330.00,1,18,5,'simple',1,1,0,1,0,0,NULL,NULL,NULL,768,59,5.00,1,355.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(8,5,'Striped Winter Scarf','striped-winter-scarf','CRS-STR-C4PS','Handmade Striped Winter Scarf crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Striped Winter Scarf</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',950.00,NULL,522.50,1,12,5,'simple',1,0,1,0,0,0,NULL,NULL,NULL,258,26,3.00,1,157.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(9,5,'Cozy Cardigan Sweater','cozy-cardigan-sweater','CRS-COZ-Y45T','Handmade Cozy Cardigan Sweater crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Cozy Cardigan Sweater</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',2800.00,3500.00,1540.00,1,4,5,'simple',1,1,0,0,0,1,NULL,NULL,NULL,599,21,4.00,2,245.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(10,5,'Baby Booties Set','baby-booties-set','CRS-BAB-OOOO','Handmade Baby Booties Set crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Baby Booties Set</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',450.00,NULL,247.50,1,25,5,'simple',1,0,0,0,1,0,NULL,NULL,NULL,218,12,5.00,1,521.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(11,5,'Slouchy Wool Hat','slouchy-wool-hat','CRS-SLO-YQJQ','Handmade Slouchy Wool Hat crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Slouchy Wool Hat</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',700.00,950.00,385.00,1,8,5,'simple',1,0,0,0,0,0,560.00,'2026-06-15 03:58:00','2026-06-19 03:58:00',68,6,4.33,3,233.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 01:27:27',NULL),(12,10,'Boho Plant Hanger','boho-plant-hanger','CRS-BOH-VCCA','Handmade Boho Plant Hanger crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Boho Plant Hanger</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',550.00,NULL,302.50,1,16,5,'simple',1,1,1,0,0,0,NULL,NULL,NULL,446,12,3.50,2,420.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 04:06:20',NULL),(13,10,'Round Coaster Set (4)','round-coaster-set-4','CRS-ROU-UWB7','Handmade Round Coaster Set (4) crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Round Coaster Set (4)</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',480.00,600.00,264.00,1,30,5,'simple',1,0,0,1,0,0,NULL,NULL,NULL,84,87,0.00,0,547.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(14,10,'Macrame Wall Hanging','macrame-wall-hanging','CRS-MAC-2DXC','Handmade Macrame Wall Hanging crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Macrame Wall Hanging</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1650.00,2000.00,907.50,1,5,5,'simple',1,1,0,0,0,1,NULL,NULL,NULL,112,3,0.00,0,306.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(15,10,'Granny Square Cushion Cover','granny-square-cushion-cover','CRS-GRA-DAJC','Handmade Granny Square Cushion Cover crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Granny Square Cushion Cover</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1200.00,NULL,660.00,1,10,5,'simple',1,0,0,0,1,0,NULL,NULL,NULL,748,24,0.00,0,367.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(16,10,'Mandala Table Mat','mandala-table-mat','CRS-MAN-UNSE','Handmade Mandala Table Mat crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Mandala Table Mat</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',900.00,1200.00,495.00,1,7,5,'simple',1,0,0,0,0,0,720.00,'2026-06-15 03:58:00','2026-06-19 03:58:00',789,16,5.00,2,364.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 01:28:40',NULL),(17,15,'Crochet Tote Bag','crochet-tote-bag','CRS-CRO-2YBR','Handmade Crochet Tote Bag crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Crochet Tote Bag</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1400.00,1800.00,770.00,1,11,5,'simple',1,1,1,1,0,0,NULL,NULL,NULL,665,56,3.67,3,185.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(18,15,'Floral Coin Pouch','floral-coin-pouch','CRS-FLO-VHQY','Handmade Floral Coin Pouch crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Floral Coin Pouch</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',350.00,NULL,192.50,1,28,5,'simple',1,0,0,0,1,0,NULL,NULL,NULL,709,11,4.50,2,171.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 05:39:51',NULL),(19,15,'Market Mesh Bag','market-mesh-bag','CRS-MAR-ZFQS','Handmade Market Mesh Bag crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Market Mesh Bag</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1100.00,NULL,605.00,1,9,5,'simple',1,0,1,0,0,1,NULL,NULL,NULL,145,6,0.00,0,284.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(20,15,'Mini Crossbody Bag','mini-crossbody-bag','CRS-MIN-NAYU','Handmade Mini Crossbody Bag crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Mini Crossbody Bag</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1600.00,2000.00,880.00,1,6,5,'simple',1,0,0,0,0,0,1280.00,'2026-06-15 03:58:00','2026-06-19 03:58:00',579,26,0.00,0,490.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 01:31:28',NULL),(21,19,'Eternal Rose Bouquet','eternal-rose-bouquet','CRS-ETE-S4P1','Handmade Eternal Rose Bouquet crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Eternal Rose Bouquet</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',1900.00,2400.00,1045.00,1,8,5,'simple',1,1,0,1,0,0,1520.00,'2026-06-15 03:58:28','2026-06-19 03:58:28',253,88,0.00,0,362.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 03:31:33',NULL),(22,19,'Single Sunflower Stem','single-sunflower-stem','CRS-SIN-HQC6','Handmade Single Sunflower Stem crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Single Sunflower Stem</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',300.00,NULL,165.00,1,35,5,'simple',1,0,0,0,1,0,NULL,NULL,NULL,109,27,5.00,1,335.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(23,19,'Tulip Trio','tulip-trio','CRS-TUL-NMCA','Handmade Tulip Trio crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Tulip Trio</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',750.00,900.00,412.50,1,14,5,'simple',1,0,1,0,0,0,NULL,NULL,NULL,158,17,5.00,1,92.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:30',NULL),(24,22,'Crochet Hair Scrunchie','crochet-hair-scrunchie','CRS-CRO-OMOB','Handmade Crochet Hair Scrunchie crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Crochet Hair Scrunchie</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',180.00,250.00,99.00,1,50,5,'simple',1,0,0,1,1,0,NULL,NULL,NULL,326,66,0.00,0,441.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(25,22,'Beaded Bookmark','beaded-bookmark','CRS-BEA-RH0K','Handmade Beaded Bookmark crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Beaded Bookmark</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',220.00,NULL,121.00,1,26,5,'simple',1,0,1,0,0,0,NULL,NULL,NULL,730,20,0.00,0,259.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-16 03:58:28',NULL),(26,22,'Flower Brooch Pin','flower-brooch-pin','CRS-FLO-GT4I','Handmade Flower Brooch Pin crocheted with soft, premium yarn — a perfect gift.','<p>This <strong>Flower Brooch Pin</strong> is lovingly handmade by our artisans using high-quality, skin-friendly cotton yarn. Each piece is unique and crafted to last.</p><ul><li>100% handmade crochet</li><li>Premium soft yarn</li><li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul><p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>',320.00,400.00,176.00,1,19,5,'simple',1,0,0,0,0,0,256.00,'2026-06-15 03:58:00','2026-06-19 03:58:00',99,1,0.00,0,574.00,NULL,NULL,'2026-06-16 03:58:28','2026-06-17 01:23:29',NULL),(27,9,'Eye','eye','CRS-EYE-BIEX','Eye is good','gooo',200.00,300.00,250.00,1,0,5,'simple',1,0,0,0,0,0,NULL,NULL,NULL,2,2,0.00,0,NULL,NULL,NULL,'2026-06-16 04:45:23','2026-06-16 05:35:28',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recently_viewed`
--

DROP TABLE IF EXISTS `recently_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recently_viewed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recently_viewed_user_id_foreign` (`user_id`),
  KEY `recently_viewed_product_id_foreign` (`product_id`),
  KEY `recently_viewed_session_id_index` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_viewed`
--

LOCK TABLES `recently_viewed` WRITE;
/*!40000 ALTER TABLE `recently_viewed` DISABLE KEYS */;
/*!40000 ALTER TABLE `recently_viewed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `reviews_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,9,5,3,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(2,9,7,5,'Great quality','Very happy with my purchase, lovely neat stitching.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(3,5,6,5,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(4,23,8,5,'Absolutely adorable!','Even cuter in person. The craftsmanship is amazing.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(5,17,4,5,'Great quality','Very happy with my purchase, lovely neat stitching.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(6,17,6,3,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(7,17,8,3,'Great quality','Very happy with my purchase, lovely neat stitching.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(8,7,4,5,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(9,16,6,5,'Absolutely adorable!','Even cuter in person. The craftsmanship is amazing.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(10,16,8,5,'Absolutely adorable!','Even cuter in person. The craftsmanship is amazing.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(11,22,6,5,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(12,18,6,5,'Beautiful work','Gorgeous colours, exactly as pictured. Thank you!',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(13,18,8,4,'Perfect gift','Bought it for my niece and she adores it.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(14,11,5,3,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(15,11,6,5,'Perfect gift','Bought it for my niece and she adores it.',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(16,11,8,5,'Absolutely adorable!','Even cuter in person. The craftsmanship is amazing.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(17,12,5,4,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(18,12,6,3,'Loved it','Soft, well-made and arrived quickly. Highly recommend.',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(19,10,6,5,'Beautiful work','Gorgeous colours, exactly as pictured. Thank you!',1,0,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(20,8,6,3,'Beautiful work','Gorgeous colours, exactly as pictured. Thank you!',1,1,'2026-06-16 03:58:30','2026-06-16 03:58:30'),(21,3,10,5,'eret','wefe',0,0,'2026-06-16 06:29:45','2026-06-16 06:29:45');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1),(2,2),(3,3),(4,4),(4,5),(4,6),(4,7),(4,8),(2,9),(4,10);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Administrator','Full access to everything.',1,'2026-06-16 03:58:27','2026-06-16 03:58:27'),(2,'manager','Store Manager','Runs day-to-day store operations.',1,'2026-06-16 03:58:27','2026-06-16 03:58:27'),(3,'staff','Staff','Limited operational access.',1,'2026-06-16 03:58:27','2026-06-16 03:58:27'),(4,'customer','Customer','Shop customer (no admin access).',1,'2026-06-16 03:58:27','2026-06-16 03:58:27');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('3lHUar8HqXpiZsXFFMBxO6q2HUtxc2xfmUfoH7Sg',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidmppaVoxY3FzY0l1MDdneGdXSURpNlNBR3haTm1xcjhvMTJBMW5NNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbj9fPTE3ODE2ODgzNjUiO3M6NToicm91dGUiO3M6MTE6ImFkbWluLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1781688367),('5Spgg73b43PTMmVYvLu3mU2uML0MmyFPhnIk5RAK',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2ZNVHpmZ2xuQ0lPNzhWeXdRTlJVN3RiQU5jTmUzdWZPOGFOS0wzRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MDg3IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688087),('ArrdjetV01xFJfSNIP7mo39K206PWOFMFCoRno1c',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzZtTkh1N0F0MzVkVWk4cVJxRzVwbUNRNFNIRFd2WlNjbWhTSTFVTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MDY3IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688072),('dKtnfeYql30uVVyW4Ekz6moXsVpI7xrZB6mgFAjX',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3B4UGdKTTNWcloyRUxyeDliM1J1a1hKOFBYclFyTHhkcW1zY3ltaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MDY3IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688071),('FyEoQmcBouAorDHrTLWaJdiufmpQ6CCeodrMbE2M',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibUVqc3EySmFrUEN6SE0yc2tRYXhwazVrd3libXg5YWxJeHBVZWJIeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MDY3IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688073),('IScLfMUkhX5MzvAUOlHhjeEUwohg1j8Hqvdky48x',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZGtFMnlVRWpTUFRBbFpXOWZSNHp0N21zb1NtOXgwVnloUE1SbTkyaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE1OiJyZWNlbnRseV92aWV3ZWQiO2E6MTp7aTowO2k6MjE7fX0=',1781688763),('N3Iphl9XhBpYveeDQC3krK4hxukFaFSzN5V9HH86',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1hkenl1cjQ5eGYwTlkzYXJwMmpJQkZJQ0JnMjVWb2x5OXRSc25MSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MzY1IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688366),('obB5DEllESE6RIoUnkV9CJLNhkvpUHVc2vpxLUpK',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVV0aVlMalg5Y2llRUhyRGxqSG5vZ3pqYUo3U2p5ZDNGVW5ITjg2ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MTUwIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688150),('t9w4utFXqcymnGRFcGOiHeITAKMt2Ng5ZKvv1mtN',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiajFESEV1QkppeG5sU3YzWUpmalBTSXJ3V3NVNU0wNFVFNk9wblFBdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MzY1IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688368),('TKMMxMvIZZxT5LBz9FAwE8OyTZc6pqbATcm8LXKP',NULL,'127.0.0.1','curl/8.8.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTVZMm5wYTAwWXpWbUVTajJVV1doRENyZ2ZSa1VPOHVwYXhuQ05RVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Xz0xNzgxNjg4MzY1IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781688367);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'store_name','Sistrella','string','general','2026-06-16 03:58:27','2026-06-16 05:30:39'),(2,'store_tagline','Handmade with love, one stitch at a time','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(3,'store_email','hello@crochetstore.test','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(4,'store_phone','977-9761612457','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(5,'store_address','Kathmandu, Nepal','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(6,'currency_code','NPR','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(7,'currency_symbol','NPR','string','general','2026-06-16 03:58:27','2026-06-16 03:58:27'),(8,'prepayment_threshold','500','float','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(9,'prepayment_percent','50','float','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(10,'tax_rate','0','float','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(11,'flat_shipping','100','float','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(12,'bank_name','Nepal Investment Bank','string','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(13,'bank_account_name','Crochet Store Pvt. Ltd.','string','payments','2026-06-16 03:58:27','2026-06-16 03:58:27'),(14,'bank_account_number','0123456789012','string','payments','2026-06-16 03:58:28','2026-06-16 03:58:28'),(15,'esewa_id','977-9761612457','string','payments','2026-06-16 03:58:28','2026-06-16 03:58:28'),(16,'khalti_id','977-9761612457','string','payments','2026-06-16 03:58:28','2026-06-16 03:58:28'),(17,'low_stock_threshold','5','integer','inventory','2026-06-16 03:58:28','2026-06-16 03:58:28'),(18,'whatsapp_number','+977 9803404215','string','social','2026-06-16 03:58:28','2026-06-16 05:30:25'),(19,'instagram_url','https://instagram.com/crochetstore','string','social','2026-06-16 03:58:28','2026-06-16 03:58:28'),(20,'facebook_url','https://facebook.com/crochetstore','string','social','2026-06-16 03:58:28','2026-06-16 03:58:28'),(21,'tiktok_url','','string','social','2026-06-16 03:58:28','2026-06-16 03:58:28');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Store Admin','admin@crochetstore.test','9779800000001',NULL,1,'2026-06-17 00:19:27','2026-06-16 03:58:27','$2y$12$xCezCmE0vS3Guv7HMWEToOm.sTUpbgTQacAYfKf1eGk7rfqc0hUAq','nPRxpt4xxlQG4ocjOoHR5VuU6D2a5eJWhabWtgv76YUdKKTmxAFGWKIS5dOp','2026-06-16 03:58:27','2026-06-17 00:19:27',NULL),(2,'Store Manager','manager@crochetstore.test','9779800000002',NULL,1,NULL,'2026-06-16 03:58:27','$2y$12$/k5LPTdQHjZsvf7vCJBi.uGVXQXd3VxHOYW7pqvEozGta5SNjsKpa',NULL,'2026-06-16 03:58:27','2026-06-16 03:58:27',NULL),(3,'Store Staff','staff@crochetstore.test','9779800000003',NULL,1,'2026-06-16 04:04:40','2026-06-16 03:58:27','$2y$12$tuGjDAloOb/l2qYZ0b939utyRQ77N.bXuGGaJauJVlZ2wMYtJbOum','SteSInyuLZvfa8kPYHlD2VosM1IyZUZUleAwhMv3q7Muj572xZNZhh8ThmSm','2026-06-16 03:58:27','2026-06-16 04:04:40',NULL),(4,'Aarati Sharma','aarati@example.com','9800000001',NULL,1,'2026-06-16 05:35:05','2026-06-16 03:58:28','$2y$12$INwsb.dEcF71CRpE572LZuF2O5qdJKk65Z7qbjKFxRsR2aWv610WW',NULL,'2026-06-16 03:58:28','2026-06-16 05:35:05',NULL),(5,'Bishal Thapa','bishal@example.com','9800000002',NULL,1,NULL,'2026-06-16 03:58:29','$2y$12$oBEyTzXqosk.GftZ2xzuiOzGswIrH94aRRuyOdCAelx/6m5kPajiu',NULL,'2026-06-16 03:58:29','2026-06-16 03:58:29',NULL),(6,'Sushmita Gurung','sushmita@example.com','9800000003',NULL,1,NULL,'2026-06-16 03:58:29','$2y$12$wWfBZJcWYzKFwPCLgWJjdurtA/L/YuvQvLwFKTKIOpc27iYb6dL6i',NULL,'2026-06-16 03:58:29','2026-06-16 03:58:29',NULL),(7,'Niraj Karki','niraj@example.com','9800000004',NULL,1,NULL,'2026-06-16 03:58:29','$2y$12$0pNB4ZA1qGjUzJ/7oLC.AeH.AJZYH5O3CfHa/FZNB0FF0M8imZ4B6',NULL,'2026-06-16 03:58:29','2026-06-16 03:58:29',NULL),(8,'Pratima Rai','pratima@example.com','9800000005',NULL,1,NULL,'2026-06-16 03:58:29','$2y$12$se3xAlYbDV2.Bd/.DgyWhejtxaYTV0lPXHrLKVyhKR1agF74mDppu',NULL,'2026-06-16 03:58:29','2026-06-16 03:58:29',NULL),(9,'Ruby Chaudhary','rubymahato32@gmail.com','9803404215',NULL,1,'2026-06-16 08:52:45',NULL,'$2y$12$lgncHMsoGuHu1UPniL38a.FjMoeVDOIwRgBJRz.vK.Fc9/fMbDHWW',NULL,'2026-06-16 04:09:55','2026-06-16 08:52:45',NULL),(10,'Ruby Chaudhary','ruby@gmail.com','9803404215',NULL,1,NULL,NULL,'$2y$12$tdx8I8zdtWjRGWbYATS.2O/cWkIKlM7H8GBLxD9DeKEES.J02XFci','rDloneq0LVxPWF3O31eip9b28DNmxkAb0jmBNSJ8snVGmSQxEnz9X7p6h9GS','2026-06-16 04:12:16','2026-06-16 04:12:16',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'crochet_store'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-17 16:04:26
