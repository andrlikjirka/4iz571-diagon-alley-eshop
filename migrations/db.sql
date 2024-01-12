-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: eshop
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB-1:10.4.22+maria~focal

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`address_id`),
  KEY `addresses_users_user_id_fk` (`user_id`),
  CONSTRAINT `addresses_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,'Jirka Testovič','Ulice','Město','11111',2,0);
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `cart_items_carts_cart_id_fk` (`cart_id`),
  KEY `cart_items_products_product_id_fk` (`product_id`),
  CONSTRAINT `cart_items_carts_cart_id_fk` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cart_items_products_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cart_id`),
  KEY `carts_users_user_id_fk` (`user_id`),
  CONSTRAINT `carts_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,2,'2024-01-11 22:39:52'),(2,2,'2024-01-12 00:49:55'),(3,2,'2024-01-12 01:53:55'),(4,NULL,'2024-01-12 01:53:57');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `showed` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`category_id`),
  KEY `categories_categories_category_id_fk` (`parent_id`),
  CONSTRAINT `categories_categories_category_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (12,'Létající košťata','letajici-kostata',NULL,1,0),(13,'Kouzelnické hůlky','kouzelnicke-hulky',NULL,1,0),(14,'Oblečení','obleceni',NULL,1,0),(15,'Čarodějnické knihy','carodejnicke-knihy',NULL,1,0),(16,'Nimbus Racing Broom Company','nimbus-racing-broom-company',12,1,0),(17,'Cleansweep Broom Company','cleansweep-broom-company',12,1,0),(18,'Comet Trading Company','comet-trading-company',12,0,0),(19,'Kolejní hábity','kolejni-habity',14,1,0),(20,'Přeměňování','premenovani',15,1,0),(21,'Obrana proti černé magii','obrana-proti-cerne-magii',15,1,0),(22,'Lektvary','lektvary',15,1,0),(23,'Klasické hůlky','klasicke-hulky',13,1,0),(24,'Hůlky pro začátečníky','hulky-pro-zacatecniky',13,1,0),(25,'Hůlky pro pokročilé','hulky-pro-pokrocile',13,1,0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourite_products`
--

DROP TABLE IF EXISTS `favourite_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favourite_products` (
  `favourite_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`favourite_product_id`),
  KEY `favourite_products_products_product_id_fk` (`product_id`),
  KEY `favourite_products_users_user_id_fk` (`user_id`),
  CONSTRAINT `favourite_products_products_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE,
  CONSTRAINT `favourite_products_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourite_products`
--

LOCK TABLES `favourite_products` WRITE;
/*!40000 ALTER TABLE `favourite_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `favourite_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forgotten_passwords`
--

DROP TABLE IF EXISTS `forgotten_passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forgotten_passwords` (
  `forgotten_password_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`forgotten_password_id`),
  KEY `forgotten_passwords_users_user_id_fk` (`user_id`),
  CONSTRAINT `forgotten_passwords_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forgotten_passwords`
--

LOCK TABLES `forgotten_passwords` WRITE;
/*!40000 ALTER TABLE `forgotten_passwords` DISABLE KEYS */;
/*!40000 ALTER TABLE `forgotten_passwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `checksum` char(32) NOT NULL,
  `executed` datetime NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_file` (`group`,`file`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'structures','2023-11-22-154625-init.sql','a2c370ca1bcf139b2f0c040d30919f52','2024-01-11 21:47:31',1),(2,'structures','2023-11-22-221023-order-typo.sql','84dc88287e0e985d9e288be92f64aa72','2024-01-11 21:47:31',1),(3,'basic-data','2023-11-23-085340-roles.sql','94cdad4e919a7e512342a1f0b26a2ce7','2024-01-11 21:47:31',1),(4,'dummy-data','2023-11-23-090251-user.sql','c8f62df5cb3c5e2fa4bbc6d11af1f3fb','2024-01-11 21:47:31',1),(5,'structures','2023-11-25-011809-unique-role-name.sql','33e2543a265ae110328b61dbdc88a4b6','2024-01-11 21:47:31',1),(6,'structures','2023-11-27-142917-product-price.sql','d4aa0da1b0ebf527f7192e9da00526d4','2024-01-11 21:47:31',1),(7,'structures','2023-11-27-143131-order-price.sql','67f21322b8b5d03e114df1886ced40dd','2024-01-11 21:47:31',1),(8,'structures','2023-11-27-143331-order-items-price.sql','db716e72ded3dc32cf7bd6a43a07f6b3','2024-01-11 21:47:31',1),(9,'structures','2023-11-27-144008-order-user-name.sql','49fab07e3238e5ed2eb4c2617dea3056','2024-01-11 21:47:31',1),(10,'structures','2023-11-29-154932-product-category-nullable.sql','0a824ec3734c911fea80964f653e1fdb','2024-01-11 21:47:31',1),(11,'dummy-data','2023-12-02-234625-dummy-categories.sql','e5ec6cdee57ea5dc220500b2fe1d296d','2024-01-11 21:47:31',1),(12,'structures','2023-12-03-134747-product-summary.sql','a3bac38d1829f69cd6cbcb0a1c0b6b9d','2024-01-11 21:47:31',1),(13,'dummy-data','2023-12-03-140651-dummy-products.sql','890ab1781944417587344645593050b3','2024-01-11 21:47:31',1),(14,'structures','2023-12-23-002920-cartitems-quantity-no-default.sql','fa7ebdfd2e24022c4b5d72893f7e729b','2024-01-11 21:47:31',1),(15,'structures','2023-12-25-222111-carts-user-nullable.sql','0b29ff80b741ecd4ff2f16a5d7d60e46','2024-01-11 21:47:31',1),(16,'structures','2023-12-27-230048-reviews-added-date.sql','2cf93df3f579db845700bf74ffa4a44e','2024-01-11 21:47:31',1),(17,'structures','2023-12-31-205809-order.sql','f32cb5c74152f35fa3e973202557af8d','2024-01-11 21:47:31',1),(18,'structures','2024-01-03-220404-product-photo-name.sql','2ea56bdb3448a70a791345a6ac26948b','2024-01-11 21:47:31',1),(19,'structures','2024-01-05-172521-fix-address-orders-user-null.sql','86a20347550c4989c83a60fba3af4879','2024-01-11 21:47:31',1),(20,'structures','2024-01-05-211019-categories-deleted-flag.sql','40dce8cae00b4430db25914075dbde52','2024-01-11 21:47:31',1),(21,'structures','2024-01-11-130633-orders-name-email.sql','56fded8d50d5fe6584c1de4e01984ed4','2024-01-11 21:47:31',1),(22,'structures','2024-01-11-205411-slug-products-and-categories.sql','9aff9b1fca7ff6771b7c8e16c64fdceb','2024-01-11 21:47:31',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `galleon_price` int(11) NOT NULL DEFAULT 0,
  `sickle_price` int(11) NOT NULL DEFAULT 0,
  `knut_price` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_item_id`),
  KEY `order_items_orders_order_id_fk` (`order_id`),
  KEY `order_items_products_product_id_fk` (`product_id`),
  CONSTRAINT `order_items_orders_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_items_products_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status`
--

LOCK TABLES `order_status` WRITE;
/*!40000 ALTER TABLE `order_status` DISABLE KEYS */;
INSERT INTO `order_status` VALUES (1,'Přijatá'),(2,'Zpracovává se'),(3,'Vyřízená'),(4,'Stornovaná');
/*!40000 ALTER TABLE `order_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status_id` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `shipping` enum('vyzvednuti','bradavice','sova') COLLATE utf8mb4_czech_ci NOT NULL,
  `payment` enum('hotovost','banka','karta') COLLATE utf8mb4_czech_ci NOT NULL,
  `galleon_total_price` int(11) NOT NULL DEFAULT 0,
  `sickle_total_price` int(11) NOT NULL DEFAULT 0,
  `knut_total_price` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`),
  KEY `orders_addresses_address_id_fk` (`address_id`),
  KEY `orders_users_user_id_fk` (`user_id`),
  KEY `orders_order_status_order_status_id_fk` (`order_status_id`),
  CONSTRAINT `orders_addresses_address_id_fk` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`) ON UPDATE CASCADE,
  CONSTRAINT `orders_order_status_order_status_id_fk` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`order_status_id`) ON UPDATE CASCADE,
  CONSTRAINT `orders_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `type` enum('allow','deny') COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'allow',
  PRIMARY KEY (`permission_id`),
  KEY `permissions_resources_resource_id_fk` (`resource_id`),
  KEY `permissions_roles_role_id_fk` (`role_id`),
  CONSTRAINT `permissions_resources_resource_id_fk` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permissions_roles_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,2,1,'default','allow'),(2,4,1,'default','allow'),(3,2,2,'default','allow'),(4,4,2,'default','allow'),(5,3,3,'default','allow'),(6,4,3,'default','allow'),(7,4,4,'default','allow'),(8,3,1,'changeStock','allow'),(9,4,1,'changeStock','allow'),(10,2,2,'add','allow'),(11,4,2,'add','allow'),(12,2,2,'edit','allow'),(13,4,2,'edit','allow'),(14,1,5,'default','allow'),(15,2,5,'default','allow'),(16,3,5,'default','allow'),(17,4,5,'default','allow'),(18,1,6,'default','allow'),(19,2,6,'default','allow'),(20,3,6,'default','allow'),(21,4,6,'default','allow'),(22,3,3,'orderInvoice','allow'),(23,4,3,'orderInvoice','allow'),(24,3,3,'show','allow'),(25,4,3,'show','allow'),(26,2,1,'edit','allow'),(27,4,1,'edit','allow'),(28,2,1,'reviews','allow'),(29,4,1,'reviews','allow'),(30,1,7,'default','allow'),(31,2,7,'default','allow'),(32,3,7,'default','allow'),(33,4,7,'default','allow'),(34,1,7,'edit','allow'),(35,2,7,'edit','allow'),(36,3,7,'edit','allow'),(37,4,7,'edit','allow'),(38,4,4,'add','allow'),(39,4,4,'edit','allow');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_photos`
--

DROP TABLE IF EXISTS `product_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_photos` (
  `product_photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`product_photo_id`),
  KEY `product_photos_products_product_id_fk` (`product_id`),
  CONSTRAINT `product_photos_products_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_photos`
--

LOCK TABLES `product_photos` WRITE;
/*!40000 ALTER TABLE `product_photos` DISABLE KEYS */;
INSERT INTO `product_photos` VALUES (1,'kolejni-habit-nebelvir.jpeg',5),(2,'kolejni-habit-nebelvir2.jpeg',5),(3,'kolejni-habit-zmijozel.jpeg',6),(4,'kolejni-habit-havraspar.jpeg',7),(5,'kolejni-habit-mrzimor.jpeg',8),(6,'kolejni-habit-mrzimor2.jpeg',8),(7,'nimbus-1000.jpeg',9),(8,'nimbus-1700.jpeg',10),(9,'nimbus-2000.jpeg',11),(10,'cleansweep-one.jpeg',12),(11,'cleansweep-2.jpeg',13),(12,'premenovani-pro-zacatecniky.jpeg',14),(13,'premenovani-pokrocile.jpeg',15),(14,'premenovani-stredne-pokrocile.jpeg',16),(15,'stazeny-soubor.jpeg',17),(16,'sebeobrana.jpeg',18),(17,'pokrocila-cerna-magie.jpeg',19),(18,'lektvary.jpeg',20),(19,'dubova.png',21),(20,'brizova.png',22),(21,'jasanova.png',23),(22,'vrbova.png',24),(23,'habrova.png',25);
/*!40000 ALTER TABLE `product_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `showed` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `galleon_price` int(11) NOT NULL DEFAULT 0,
  `sickle_price` int(11) NOT NULL DEFAULT 0,
  `knut_price` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`),
  KEY `products_categories_category_id_fk` (`category_id`),
  CONSTRAINT `products_categories_category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (5,'Kolejní hábit Nebelvír','kolejni-habit-nebelvir','Elegantní a kouzelnický hábit Nebelvíru - symbol loajality, odvahy a moudrosti. Vynikající volba pro každého, kdo si chce přinést kousek kouzla do svého každodenního života.','<p>Kolejní hábit Nebelvír je více než jen oblečení; je to symbol identity a příslušnosti k jedné z nejvzácnějších kolejí v kouzelnickém světě. Tento elegantní hábit je pečlivě navržen s důrazem na detail, aby přesně reflektoval charakter Nebelvíru - kolej, která si váží loajality, odvahy a moudrosti.</p>\n<p>Výrazné barvy, charakteristický střih a kvalitní materiály společně tvoří toto unikátní oblečení, které vás vtáhne do atmosféry kouzelnického světa. Kolejní hábit Nebelvír je výjimečným způsobem, jak vyjádřit svou příslušnost k této ušlechtilé kolejní tradici.</p>\n<p>Pro ty, kteří hledají víc než jen oblečení, je Kolejní hábit Nebelvír skvělou volbou. Je to investice do vlastního kouzelnického stylu a jedinečného spojení s hodnotami Nebelvíru. Oblečte se do této symbolické róby a nechte se unést kouzlem Nebelvíru ve vašem srdci.</p>','2024-01-11 23:46:27','2024-01-12 01:18:55',20,19,1,0,10,8,1),(6,'Kolejní nábit Zmijozel','kolejni-nabit-zmijozel','Elegance s nádechem tajemství - Kolejní hábit Zmijozel. Výrazné barvy a sofistikovaný střih, který zvýrazňuje chytrost a ambice. Dokonalý oděv pro ty, kteří si chtějí užít stylový a kouzelnický život.','<p>Kolejní hábit Zmijozel je vyjádřením výjimečnosti a ambicí, které jsou charakteristické pro tuto ušlechtilou kolej. Elegantní střih, výrazné barvy a kvalitní provedení tvoří unikátní kus oděvu, který září sofistikovaností a tajemstvím.</p>\n<p>Tento hábit je navržen tak, aby podtrhl jedinečnost a chytrost Zmijozelu, a zároveň poskytoval pohodlí a styl. Každý detail hábitu zdůrazňuje tradice a hodnoty této kolejní společnosti. Pro ty, kteří si přejí vyjádřit svou oddanost Zmijozelu, je tento hábit nezbytným doplňkem k jejich kouzelnickému stylu.</p>\n<p>Oblečení Kolejní hábit Zmijozel je více než jen oděv; je to výraz identity a hrdosti na to, být součástí jedné z nejvýznamnějších kolejí s kouzelnickou historií. Vstupte do světa tajemství a ambicí s tímto exkluzivním hábitem, který zvýrazní vaši kouzelnickou osobnost.</p>','2024-01-11 23:52:00','2024-01-12 01:08:15',20,19,1,0,11,8,2),(7,'Kolejní hábit Havraspár','kolejni-habit-havraspar','Hrdost, elegance a tradice spojené v jednom - Kolejní hábit Havraspár. Pro ty, kteří ctí hodnoty vzdělání, moudrosti a odvahy.','<p>Kolejní hábit Havraspár je vyjádřením intelektu, moudrosti a odhodlání. Tento symbolický kousek oděvu spojuje hrdost s elegancí, čímž vytváří unikátní zážitek pro ty, kteří se chtějí identifikovat s hodnotami Havraspáru.</p>\n<p>S precizním střihem a výběrem barev reflektujících tradice kolejí Havraspáru nabízí tento hábit nejen stylový vzhled, ale také pohodlí a kvalitu provedení. Pro každého, kdo ctí vzdělání, intelektuální růst a vznešenost, je Kolejní hábit Havraspár skvělou volbou.</p>\n<p>Každý detail hábitu nese důležitou symboliku, ať už jde o logo kolejního domu nebo specifický střih, který zdůrazňuje osobitost Havraspáru. Oblečení tohoto hábitu je nejen výrazem hrdosti na vlastní kolej, ale také způsobem, jak projevit vášeň pro vzdělání, moudrost a výjimečnost.</p>','2024-01-11 23:53:21','2024-01-12 01:09:47',20,19,1,0,9,15,2),(8,'Kolejní hábit Mrzimor','kolejni-habit-mrzimor','Elegance s temným šarmem - Kolejní hábit Mrzimor. Oblečte se do tohoto exkluzivního oděvu, který v sobě nese mystiku a jedinečnost.','<p>Kolejní hábit Mrzimor spojuje temné tóny s výrazným designem, vytvářející unikátní a působivý kousek oděvu. Tento hábit je navržen s ohledem na výjimečnost kolejního domu Mrzimor, který je známý pro svou odvahu, nekompromisnost a touhu po síle.</p>\n<p>Elegantní střih a kvalitní materiály zvýrazňují jedinečný charakter tohoto hábitu, který se stává výrazem individuality a mystiky. Získáte nejen oblečení, ale také symbolický prvek spojený s temným šarmem Mrzimoru.</p>\n<p>Oblečte se do Kolejního hábitu Mrzimor a nechte se unést do atmosféry síly a odvahy. Pro ty, kteří se chtějí vyjádřit prostřednictvím kouzelnického stylu s temným nádechem, je tento hábit nezbytným doplňkem k jejich osobnosti.</p>','2024-01-11 23:55:05','2024-01-12 01:10:23',20,19,1,0,12,5,2),(9,'Nimbus 1000','nimbus-1000','Létající koště Nimbus 1000 - kouzelnická dokonalost v podobě jedinečného doplňku. Elegantní design, kvalitní provedení a autentický pocit přinášejí kouzlo každodenních okamžiků. Pro všechny, kteří hledají magii ve svém životě.','<p>Vstupte do světa kouzelnické elegance s létajícím koštětem Nimbus 1000. Jeho jedinečný design a kvalitní zpracování nejenže dotvářejí atmosféru mističky, ale také vám umožňují prožívat každodenní okamžiky s nádechem magie.</p>\n<p>Nimbus 1000 není pouhým předmětem, je to umělecké dílo, které spojuje precizní řemeslnou práci s vizuální elegancí. Ergonomický tvar rukojeti a pečlivě zpracované detaily vytvářejí autentický pocit a zároveň dodávají prostoru kouzelnický nádech.</p>\n<p>Toto létající koště není jen dekorační prvek, ale zároveň symbol osobní svobody a touhy po dobrodružství. Jeho pohyblivost a lehkost vám umožní uniknout rutině a prozkoumat svět s novým pohledem. Nimbus 1000 je více než jen doplněk, je to prostředek, jak přinést kouzlo do každého okamžiku vašeho života.</p>','2024-01-12 00:02:56',NULL,10,16,1,0,35,12,4),(10,'Nimbus 1700','nimbus-1700','Inovativní létající koště Nimbus 1700 přináší vzrušující dobrodružství do každodenních chvil. S elegantním designem a technologickými vylepšeními je dokonalým spojením stylového doplňku a kouzelnického zážitku.','<p>Ponořte se do světa magie s létajícím koštětem Nimbus 1700, které znamená revoluci v kouzelnických doplňcích. Jeho inovativní design spojený s pokročilými technologickými prvky zajišťuje nejen dokonalou pohyblivost, ale také bezpečný a pohodlný zážitek z létání.</p>\n<p>Nimbus 1700 přináší do světa magie nový rozměr. Elegantní ergonomický design rukojeti a aerodynamické tvary metly jsou precizně navrženy tak, aby poskytovaly optimální ovladatelnost a komfort při každém vzletu. Integrované technologické vylepšení dodávají košti sílu a rychlost, kterou ocení každý odvážlivec.</p>\n<p>Toto létající koště není pouze doplňkem, ale vstupenkou do světa vzdušných dobrodružství. Bezpečný a spolehlivý mechanismus zajišťuje, že každý let je bezstarostným výletem do neznámého. Nimbus 1700 - dokonalé spojení elegance, technologie a kouzla v jednom.</p>','2024-01-12 00:06:30',NULL,15,16,1,0,42,16,4),(11,'Nimbus 2000','nimbus-2000','Revoluční létající koště Nimbus 2000 přináší do vašeho života magii a dobrodružství. Elegantní design a technologické inovace spojené v jednom kouzelnickém doplňku.','<p>Otevřete brány nebes s létajícím koštětem Nimbus 2000, které znamená vrchol kouzelnické elegance a technologického pokroku. Jeho aerodynamický design a precizní zpracování nejen oslní svým vzhledem, ale také zajišťuje optimální ovladatelnost při každém letu.</p>\n<p>Nimbus 2000 není jen létajícím doplňkem, je to kouzelnický průvodce vzdušnými dobrodružstvími. Ergonomická rukojeť a pohyblivá metla jsou vytvořeny s ohledem na maximální komfort a bezpečnost při každém vzletu. Integrované technologické prvky dodávají košti sílu a stabilitu, což z něj činí ideálního společníka pro odvážné cestovatele vzdušnými prostory.</p>\n<p>S Nimbus 2000 přinášíte do svého života kousek magie, který transformuje každý den na dobrodružství. Prožijte vzlety do neznáma s kouzelnickým koštětem, který spojuje tradici, design a technologii v jedinečném kouzelnickém zážitku.</p>','2024-01-12 00:08:32',NULL,16,16,1,0,53,10,5),(12,'Cleansweep 1','cleansweep-1','Létající koště Cleansweep One přináší do vašeho života novou dimenzi dobrodružství. S moderním designem a inovativními prvky je ideálním společníkem pro všechny, kdo hledají pohodlný a elegantní způsob pohybu.','<p>Osvoboďte svou duši dobrodružstvím s létajícím koštětem Cleansweep One. Jeho moderní design a inovativní technologické prvky přinášejí do vašeho každodenního života nový smysl pro svobodu a eleganci.</p>\n<p>Cleansweep One není jen prostředkem k pohybu, je to umělecké dílo spojující v sobě estetiku, funkčnost a pohodlí. Ergonomická konstrukce a pohyblivá metla zajišťují optimální ovladatelnost a bezproblémový let. S integrovanými technologickými vylepšeními je tento model nejen spolehlivým dopravním prostředkem, ale také stylovým symbolem svobody.</p>\n<p>Cleansweep One je více než jen doplněk, je to cesta ke světu dobrodružství. Bezpečný, elegantní a plný šarmu, toto létající koště vás provede vzdušnými stezkami s lehkostí a grácií. Vstupte do nového věku s Cleansweep One a objevte kouzlo pohybu ve stylu.</p>','2024-01-12 00:12:25',NULL,8,17,1,0,25,12,5),(13,'Cleansweep 2','cleansweep-2','Létající koště Cleansweep Two - inovativní design a technologické prvky spojené v jednom pohodlném a stylovém doplňku pro každodenní dobrodružství.','<p>Osvoboďte se od země s létajícím koštětem Cleansweep Two, které spojuje bezpečnost, pohodlí a elegance ve svěžím designu. Toto kouzelnické dopravní prostředek není pouze prostředkem k cestování, ale symbolem svobody a inovace.</p>\n<p>Cleansweep Two se vyznačuje moderním designem, který zdůrazňuje ergonomii a pohodlí. Jeho pohyblivá metla a technologické inovace zajišťují plynulý let bez kompromisů. S lehkostí a grácií vám umožní prozkoumat okolní krajinu a zažívat dobrodružství ve vzduchu.</p>\n<p>Toto létající koště není jen prostředkem k pohybu, ale také módním a stylovým doplňkem pro každého, kdo ocení spojení estetiky a praktičnosti. Cleansweep Two je představitelem moderní éry létání, která nabízí bezpečnost, pohodlí a neomezené možnosti pro každodenní dobrodružství.</p>','2024-01-12 00:13:58',NULL,16,17,1,0,26,15,18),(14,'Průvodce přeměňováním pro začátečníky','pruvodce-premenovanim-pro-zacatecniky','Vstupte do fascinujícího světa magie s knihou \"Průvodce přeměňováním pro začátečníky\". Skvělý průvodce pro ty, kteří touží po objevování kouzelnických dovedností a tajemství.','<p>Tato kniha otevírá dveře kouzelnického umění před těmi, kteří se chtějí ponořit do světa přeměňování. \"Průvodce přeměňováním pro začátečníky\" je komplexním průvodcem pro ty, kteří hledají hlubší porozumění a ovládnutí magických dovedností.</p>\n<p>Autorka se věnuje nejen praktickým aspektům přeměňování, ale také historii, filozofii a etice spojené s tímto fascinujícím oborem magie. Každá stránka nabízí čtenáři podrobný pohled na techniky, cvičení a rituály, které pomáhají rozvinout a zdokonalit schopnosti přeměňování.</p>\n<p>\"Průvodce přeměňováním pro začátečníky\" není pouze knihou o kouzlech; je to průvodce osobním objevováním, který nabízí inspiraci, povzbuzení a návod, jak proniknout do hlubin vlastní magické síly. Ideální volba pro ty, kteří touží po tom stát se mistrem přeměňování a otevřít dveře k neomezeným možnostem magie ve svém životě.</p>','2024-01-12 00:27:30','2024-01-12 01:26:06',30,20,1,0,1,2,2),(15,'Pokročilý průvodce přeměňováním','pokrocily-pruvodce-premenovanim','Vstupte do tajemného světa magie s knihou \"Pokročilý průvodce přeměňováním\". Skvělý průvodce pro ty, kteří se chtějí posunout dál ve svých kouzelnických dovednostech.','<p>Tato kniha přináší rozšířené a hlubší poznatky do oblasti přeměňování pro ty, kteří touží po zdokonalení svých magických schopností. \"Pokročilý průvodce přeměňováním\" je komplexním průvodcem pro ty, kteří již ovládají základy a hledají pokročilé techniky a návody.</p>\n<p>Autor/autorka se detailně zabývá sofistikovanými metodami, tajnými rituály a pokročilými strategiemi v oblasti přeměňování. Knížka není jen praktickým průvodcem, ale i inspirací pro ty, kteří chtějí prozkoumat hranice svých kouzelnických schopností. Každá kapitola poskytuje hluboké pochopení magického světa a nasměrování na cestu k mistrovství v oboru přeměňování.</p>\n<p>\"Pokročilý průvodce přeměňováním\" je klíčem k odemykání potenciálu magie, který spočívá v nás všech. Ideální volba pro ty, kteří jsou připraveni jít za hranice obvyklého a prohloubit svou kouzelnickou cestu do nových dimenzí.</p>','2024-01-12 00:31:22',NULL,15,20,1,0,2,1,0),(16,'Průvodce přeměňováním pro středně pokročilé','pruvodce-premenovanim-pro-stredne-pokrocile','Prohlubte své kouzelnické schopnosti s knihou \"Průvodce přeměňováním pro středně pokročilé\". Skvělý průvodce pro ty, kteří chtějí posunout své dovednosti na novou úroveň.','<p>Tato kniha je určena těm, kteří již ovládají základní principy přeměňování a hledají další pokročilé techniky a tipy. \"Průvodce přeměňováním pro středně pokročilé\" vás provede dalším stupněm kouzelnického umění a pomůže vám objevit nové možnosti v oblasti proměny a transformace.</p>\n<p>Autor/autorka se zaměřuje na středně pokročilé metody přeměňování, představuje různé přístupy a strategie pro zdokonalení dovedností. Každá kapitola knihy nabízí praktické rady, cvičení a příklady, které vám pomohou posílit vaše kouzelnické schopnosti. \"Průvodce přeměňováním pro středně pokročilé\" slouží jako inspirace a průvodce pro ty, kteří chtějí prohloubit svou magickou cestu.</p>\n<p>Tato kniha je klíčem k otevření dveří kouzelnického světa pro ty, kteří touží po dalším rozvoji svých magických dovedností. Ideální volba pro středně pokročilé kouzelníky, kteří jsou připraveni na další úroveň v jejich magickém putování.</p>','2024-01-12 00:34:10',NULL,16,20,1,0,3,2,1),(17,'Základní obrana proti černé magii','zakladni-obrana-proti-cerne-magii','Ochrana a síla spojené v jedné knize. \"Základní obrana proti černé magii\" je klíčovým průvodcem pro ty, kteří chtějí porozumět a bránit se proti temným silám.','<p>Tato kniha je komplexním průvodcem pro každého, kdo se zajímá o obranu proti černé magii. Bez ohledu na to, zda jste začátečník nebo zkušený kouzelník, \"Základní obrana proti černé magii\" poskytuje nezbytné informace, techniky a rituály k ochraně sebe a svého okolí před temnými silami.</p>\n<p>Autor/autorka se věnuje nejen praktickým strategiím pro odvrácení negativních energií, ale také rozvíjí pochopení etických aspektů v obraně proti černé magii. Každá kapitola nabízí konkrétní rady, vizualizace a cvičení, které vám pomohou posílit svou energetickou ochranu.</p>\n<p>\"Základní obrana proti černé magii\" není pouze knihou o kouzlech; je to průvodce osobním rozvojem a obranou proti nežádoucím vlivům. Ideální volba pro ty, kteří hledají prostředky k posílení své duševní a energetické odolnosti v tváři magických výzev života.</p>','2024-01-12 00:38:10',NULL,22,21,1,0,4,2,0),(18,'Průvodce sebeobranou proti černé magii','pruvodce-sebeobranou-proti-cerne-magii','Ochrana a síla spojené v jednom průvodci. Tato kniha vám poskytne klíčové dovednosti a strategie k sebeobraně před temnými silami.','<p>\"Průvodce sebeobranou proti černé magii\" je komplexním průvodcem pro každého, kdo touží po individuální ochraně a odolnosti vůči temným energiím. Tato kniha poskytuje nejen praktické techniky sebeobrany, ale také hlubší porozumění principům energetické ochrany a odhalení temných intrik a vlivů.</p>\n<p>Autor/autorka prochází různými aspekty sebeobrany, včetně psychologických, fyzických a energetických technik, které vám umožní aktivně se bránit proti negativním silám kolem vás. Každá kapitola je plná praktických cvičení, rad a příběhů, které vám pomohou vyvinout sílu a sebedůvěru v oblasti sebeobrany.</p>\n<p>\"Průvodce sebeobranou proti černé magii\" není pouze knihou o kouzlech; je to průvodce, který vám pomůže objevit vaši vlastní sílu a naučit se efektivně reagovat na výzvy spojené s negativními energiemi. Ideální volba pro ty, kteří chtějí ovládnout dovednosti sebeobrany a posílit svou odolnost v každodenním životě.</p>','2024-01-12 00:55:24',NULL,23,21,1,0,3,4,2),(19,'Pokročilá obrana proti černé magii','pokrocila-obrana-proti-cerne-magii','Staňte se mistrem svého osudu s touto knihou, která vám otevře dveře k pokročilým strategiím a obranným dovednostem proti temným silám.','<p>\"Pokročilá obrana proti černé magii\" představuje komplexní průvodce pro ty, kteří hledají vyšší úroveň ochrany před negativními energiemi. Autor/autorka se v tomto díle věnuje sofistikovaným a pokročilým strategiím obrany, které vás naučí nejen odvracet temné vlivy, ale také aktivně pracovat s energií a sílou své mysli.</p>\n<p>Kniha podrobně rozebírá techniky energetické ochrany, vizualizace, meditace a rituály, které vám pomohou vybudovat neprostupný štít proti černé magii. Bez ohledu na to, zda jste začátečník či zkušený praktikující, každá kapitola nabízí hlubší vhled do světa obrany a posilování duševní odolnosti.</p>\n<p>\"Pokročilá obrana proti černé magii\" je kniha pro ty, kteří jsou připraveni vzít v ruce svou osudovou hůlku a aktivně se postavit všem výzvám spojeným s negativními energiemi a temnými silami.</p>','2024-01-12 01:02:06',NULL,17,21,1,0,4,3,1),(20,'Kouzelné recepty a lektvary','kouzelne-recepty-a-lektvary','Otevřete bránu kouzelnému světu s touto knihou plnou tajemných receptů a lektvarů, které probudí vaši kouzelnickou kreativitu.','<p>Tato kniha je průvodcem do světa nekonečných možností kouzelnických receptů a lektvarů. Autor/autorka přináší jedinečný mix tradičních a moderních receptů, které vás provedou různými aspekty kouzelnické kuchyně.</p>\n<p>Od elixírů pro posílení duše až po tajemné nápoje, které probudí vaše smysly, každá kapitola je plná fascinujících receptů, kouzelnických ingrediencí a návodů k vytvoření magických lektvarů ve vaší vlastní kuchyni. Kniha není jen o vaření; je to průvodce kouzelnickým uměním, který vás inspiruje k objevování nových chutí a kreativnímu experimentování s magií přírody.</p>\n<p>\"Kouzelné recepty a lektvary\" je ideální volbou pro ty, kteří chtějí oživit svou kouzelnickou stránku a prozkoumat, jak může magie obohatit každodenní život. Každý recept je příležitostí k vyjádření vaší kreativity a kouzelnické intuice, ať už jste začátečník nebo zkušený kouzelník.</p>','2024-01-12 01:05:01',NULL,15,22,1,0,3,3,1),(21,'Dubová Hůlka','dubova-hulka','Odolná a spolehlivá, ideální pro silná zaklínadla a obrannou magii.','<p><em>Tato majestátní dubová hůlka je symbolem síly a odolnosti. Každá hůlka je pečlivě vyřezávána z nejkvalitnějšího dubového dřeva, což jí dává nejen nádherný vzhled, ale i vynikající magickou vodivost. Ideální pro čaroděje, kteří hledají hůlku s pevným charakterem a spolehlivými magickými schopnostmi.</em></p>','2024-01-12 01:13:40','2024-01-12 01:45:30',4,23,1,0,10,16,9),(22,'Břízová Hůlka','brizova-hulka','Lehká a ohebná, skvělá pro eleganci a rafinovanost v zaklínání.','<p><em>Lehká a elegantní, břízová hůlka je známá pro svou flexibilitu a přizpůsobivost. Vyrobená z pečlivě vybraného břízového dřeva, tato hůlka je ideální pro obratné a intuitivní kouzelníky. Její hladký povrch a jemné linie zdůrazňují přirozenou krásu břízy.</em></p>','2024-01-12 01:14:34','2024-01-12 01:28:32',53,23,1,0,4,1,2),(23,'Jasanová Hůlka','jasanova-hulka','Silná a pružná, vhodná pro rychlá a dynamická kouzla.','<p><em>Jasanová hůlka, známá pro svou výjimečnou sílu a pružnost, je vyrobena z dřeva starého jasanu. Tato hůlka je vhodná pro kouzelníky, kteří oceňují hloubku a komplexnost v magii. Její temnější odstín a hladký povrch dodávají eleganci a noblesu.</em></p>','2024-01-12 01:15:14','2024-01-12 01:28:51',23,23,1,0,5,3,10),(24,'Vrbová Hůlka','vrbova-hulka','Ohebná a přívětivá pro nováčky, ideální pro základní kouzla.','<p><em>Vrbová hůlka, známá svou flexibilitou a odolností vůči lámání, je ideální pro kouzelníky s citlivým dotekem. Vyrobená z vybraného vrbového dřeva, tato hůlka je obzvláště vhodná pro kouzla zaměřená na obranu a léčení. Její jemně křivkavý tvar je pohodlný a přirozený v ruce.</em></p>','2024-01-12 01:16:03','2024-01-12 01:45:43',10,24,1,0,12,15,12),(25,'Habrová Hůlka','habrova-hulka','Pro zesílení složitých kouzel, oblíbená mezi zkušenými čaroději.','<p><em>Habrová hůlka, pevná a spolehlivá, je vyrobena z dřeva starého habru. Její rovný a hladký povrch je příjemný na dotek, a její neutrální barva zdůrazňuje klasický a tradiční design. Ideální pro kouzelníky, kteří chtějí robustní a odolnou hůlku pro všestranné magické použití.</em></p>','2024-01-12 01:16:39','2024-01-12 01:29:24',0,25,1,0,1,12,10);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` VALUES (1,'Products'),(2,'Categories'),(3,'Orders'),(4,'Users'),(5,'ChangePassword'),(6,'Dashboard'),(7,'Profile');
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `stars` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`review_id`),
  KEY `reviews_products_product_id_fk` (`product_id`),
  KEY `reviews_users_user_id_fk` (`user_id`),
  CONSTRAINT `reviews_products_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE,
  CONSTRAINT `reviews_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `check_stars` CHECK (`stars` >= 1 and `stars` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `unique_role_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (4,'admin'),(1,'customer'),(2,'editor'),(3,'storeman');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `blocked` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  KEY `users_roles_role_id_fk` (`role_id`),
  CONSTRAINT `users_roles_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Martin Testovič','m@shop.cz',NULL,4,'$2y$10$NnqpxmAXVNhSNKmCuGtWMefoa/3j2dErd36ZSseVwVJX9lIyybCCi',0,0),(2,'Jirka Testovič','j@shop.cz',NULL,4,'$2y$10$PnWO/3mMIbI/FiCCCioFcOtCe1KppYTMxL50YqwNTZl5i8ZiuFHy2',0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-12  2:07:24
