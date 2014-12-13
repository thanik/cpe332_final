# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.21)
# Database: asset
# Generation Time: 2014-12-13 14:48:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table asset_id
# ------------------------------------------------------------

DROP TABLE IF EXISTS `asset_id`;

CREATE TABLE `asset_id` (
  `asset_id` varchar(10) NOT NULL,
  `asset_name` varchar(50) NOT NULL,
  `asset_type` varchar(50) NOT NULL,
  `unit` varchar(10) NOT NULL DEFAULT '',
  `yearly_depreciation` decimal(4,2) NOT NULL,
  `purchase_value` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `beginning_value` decimal(10,2) NOT NULL,
  `depreciated_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `current_value` decimal(10,2) NOT NULL,
  `total_component` int(11) NOT NULL,
  `total_component_value` decimal(10,2) NOT NULL,
  `current_location` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`asset_id`),
  KEY `asset_type` (`asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `asset_id` WRITE;
/*!40000 ALTER TABLE `asset_id` DISABLE KEYS */;

INSERT INTO `asset_id` (`asset_id`, `asset_name`, `asset_type`, `unit`, `yearly_depreciation`, `purchase_value`, `purchase_date`, `beginning_value`, `depreciated_value`, `current_value`, `total_component`, `total_component_value`, `current_location`)
VALUES
	('A0001','HP Z420 Workstation','Desktop','unit',5.00,62000.00,'2014-09-18',62000.00,3100.00,58900.00,3,28500.00,'room20'),
	('A0002','Dell Precision T3610','Desktop','unit',10.00,75000.00,'2014-09-20',75000.00,7500.00,67500.00,1,4000.00,'room06'),
	('A0003','iMac 27-inch with 4K display','Desktop','unit',0.00,56000.00,'2014-09-25',56000.00,0.00,56000.00,2,4580.00,'room16'),
	('A0004','Macbook Pro 15-inch with Retina Display','Laptop','pcs',3.50,75000.00,'2014-10-01',75000.00,2625.00,72375.00,3,3370.00,'room15'),
	('A0005','Dell Inspiron 5547','Laptop','pcs',6.75,45000.00,'2014-10-02',45000.00,3037.50,41962.50,2,9000.00,'room45'),
	('A0006','Lenovo Z510','Laptop','pcs',15.00,32250.00,'2014-10-02',32250.00,4837.50,27412.50,1,590.00,'room03'),
	('A0007','Canon PIXMA IX7000','Printer','pcs',10.00,13750.00,'2014-09-18',13750.00,1375.00,12375.00,2,4580.00,'room01'),
	('A0008','BROTHER MFC-J825DW','Printer','pcs',10.00,8400.00,'2014-09-19',8400.00,840.00,7560.00,2,1600.00,'room16'),
	('A0009','Cisco UCS C220 M3','Server','unit',8.00,86000.00,'2014-08-30',86000.00,6880.00,79120.00,1,22000.00,'room19'),
	('A0010','Samsung CLP-325','Printer','pcs',15.00,7470.00,'2014-10-01',7470.00,1120.50,6349.50,1,1590.00,'room07'),
	('A0011','IBM System x3250 M5','Server','unit',5.00,27900.00,'2014-10-02',27900.00,1395.00,26505.00,4,18000.00,'room45'),
	('A0012','Dell PowerEdge R210 II','Server','unit',1.00,32000.00,'2014-10-05',32000.00,320.00,31680.00,2,3450.00,'room01'),
	('A0013','Cisco SF302-08MPP','Switch','pcs',0.00,13600.00,'2014-09-17',13600.00,0.00,13600.00,1,1200.00,'room16'),
	('A0014','D-Link DES-3828P','Switch','pcs',5.50,65900.00,'2014-09-24',65900.00,3624.50,62275.50,1,450.00,'room37'),
	('A0015','D-Link DES-3052','Switch','pcs',4.50,22470.00,'2014-10-02',22470.00,1011.15,21458.85,1,12000.00,'room09');

/*!40000 ALTER TABLE `asset_id` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table asset_id_lineitem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `asset_id_lineitem`;

CREATE TABLE `asset_id_lineitem` (
  `no` int(11) NOT NULL,
  `asset_id` varchar(11) NOT NULL DEFAULT '',
  `component_name` varchar(50) NOT NULL,
  `component_type` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `rough_value` decimal(10,2) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`no`,`asset_id`),
  KEY `asset_id` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `asset_id_lineitem` WRITE;
/*!40000 ALTER TABLE `asset_id_lineitem` DISABLE KEYS */;

INSERT INTO `asset_id_lineitem` (`no`, `asset_id`, `component_name`, `component_type`, `quantity`, `rough_value`, `notes`)
VALUES
	(1,'A0001','WD Black 750GB','Part',1,4500.00,'Storage Expansion'),
	(1,'A0002','WD Blue 1TB','Part',1,4000.00,'Storage Expansion'),
	(1,'A0003','Apple Magic Mouse','Equipment',1,2290.00,NULL),
	(1,'A0004','Apple Mouse','Equipment',1,1590.00,NULL),
	(1,'A0005','Razer Deathadder 2014 Mouse','Equipment',1,4500.00,NULL),
	(1,'A0006','AC/DC Adapter','Equipment',1,590.00,NULL),
	(1,'A0007','Printer Ink','Equipment',2,2290.00,NULL),
	(1,'A0008','Printer Ink','Equipment',1,1100.00,NULL),
	(1,'A0009','PROMISE Pegasus PR401US 4TB','Part',1,22000.00,'Storage Expansion'),
	(1,'A0010','Printer Ink','Equipment',1,1590.00,NULL),
	(1,'A0011','SUPERMICRO SNK-P0050AP4 Heatsink','Part',1,1200.00,NULL),
	(1,'A0012','Mediasonic HP1-SU3 PCI Express USB 3.0 Card','Part',1,450.00,'USB3'),
	(1,'A0013','Spare Parts','Part',1,1200.00,'Fix the LAN connector'),
	(1,'A0014','Spare Parts','Part',1,450.00,'Fix the power connector'),
	(1,'A0015','UPS ICON-1200W-LCD-2B9-V','Equipment',1,12000.00,'UPS for preventing power failure'),
	(2,'A0001','Corsair RAM 32GB','Part',2,12000.00,'RAM Upgrade'),
	(2,'A0003','Apple Wireless Keyboard','Equipment',1,2290.00,NULL),
	(2,'A0004','Mini DisplayPort to VGA Adapter','Equipment',1,1090.00,NULL),
	(2,'A0005','Razer Blackwidow 2014 Keyboard','Equipment',1,4500.00,NULL),
	(2,'A0008','Spare Part','Part',1,500.00,NULL),
	(2,'A0011','HighPoint RocketRAID 2720SGL SATA / SAS Controller','Part',1,5000.00,NULL),
	(2,'A0012','iStarUSA 3U Rackmount Sever Chassis','Part',1,3000.00,'Chassis replacement'),
	(3,'A0004','Apple HDMI to HDMI Cable','Equipment',1,690.00,NULL),
	(3,'A0011','Crucial 16GB 240-Pin DDR3 SDRAM','Part',1,5800.00,'RAM Upgrade'),
	(4,'A0011','Intel Xeon E3-1220V3 3.1GHz LGA 1150 CPU','Part',1,6000.00,'CPU replacement');

/*!40000 ALTER TABLE `asset_id_lineitem` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table asset_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `asset_type`;

CREATE TABLE `asset_type` (
  `asset_type` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `asset_type` WRITE;
/*!40000 ALTER TABLE `asset_type` DISABLE KEYS */;

INSERT INTO `asset_type` (`asset_type`)
VALUES
	('Desktop'),
	('Equipment'),
	('Laptop'),
	('Part'),
	('Printer'),
	('Server'),
	('Switch');

/*!40000 ALTER TABLE `asset_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table assetlocation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assetlocation`;

CREATE TABLE `assetlocation` (
  `location` varchar(20) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `assetlocation` WRITE;
/*!40000 ALTER TABLE `assetlocation` DISABLE KEYS */;

INSERT INTO `assetlocation` (`location`, `description`)
VALUES
	('room01',NULL),
	('room02',NULL),
	('room03',NULL),
	('room04',NULL),
	('room05',NULL),
	('room06',NULL),
	('room07',NULL),
	('room08',NULL),
	('room09',NULL),
	('room10',NULL),
	('room12',NULL),
	('room14',NULL),
	('room15',NULL),
	('room16',NULL),
	('room17',NULL),
	('room19',NULL),
	('room20',NULL),
	('room37',NULL),
	('room39',NULL),
	('room45',NULL);

/*!40000 ALTER TABLE `assetlocation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table assetmove
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assetmove`;

CREATE TABLE `assetmove` (
  `assetmoveNo` varchar(10) NOT NULL,
  `movementDate` date NOT NULL,
  `assetmoveReason` varchar(10) NOT NULL,
  PRIMARY KEY (`assetmoveNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `assetmove` WRITE;
/*!40000 ALTER TABLE `assetmove` DISABLE KEYS */;

INSERT INTO `assetmove` (`assetmoveNo`, `movementDate`, `assetmoveReason`)
VALUES
	('M0001','2014-10-05','purchase'),
	('M0002','2014-10-05','transfer'),
	('M0003','2014-10-06','sales'),
	('M0004','2014-10-07','purchase'),
	('M0005','2014-10-07','sales'),
	('M0006','2014-10-08','sales'),
	('M0007','2014-10-09','transfer'),
	('M0008','2014-10-09','purchase'),
	('M0009','2014-10-09','purchase'),
	('M0010','2014-10-09','sales'),
	('M0011','2014-10-10','purchase'),
	('M0012','2014-10-10','transfer'),
	('M0013','2014-10-10','sales'),
	('M0014','2014-10-11','purchase'),
	('M0015','2014-10-11','sales');

/*!40000 ALTER TABLE `assetmove` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table assetmoveline
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assetmoveline`;

CREATE TABLE `assetmoveline` (
  `assetmoveNo` varchar(10) NOT NULL,
  `asset_id` varchar(10) NOT NULL,
  `currentLocation` varchar(50) DEFAULT NULL,
  `newLocation` varchar(50) NOT NULL,
  `moveList` int(11) NOT NULL,
  `asset_name` varchar(50) NOT NULL,
  PRIMARY KEY (`assetmoveNo`,`asset_id`),
  KEY `asset_id` (`asset_id`),
  KEY `currentLocation` (`currentLocation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `assetmoveline` WRITE;
/*!40000 ALTER TABLE `assetmoveline` DISABLE KEYS */;

INSERT INTO `assetmoveline` (`assetmoveNo`, `asset_id`, `currentLocation`, `newLocation`, `moveList`, `asset_name`)
VALUES
	('M0001','A0004',NULL,'room37',2,'Macbook Pro 15-inch with Retina Display'),
	('M0001','A0005',NULL,'room39',1,'Dell Inspiron 5547'),
	('M0002','A0004','room37','room12',1,'Macbook Pro 15-inch with Retina Display'),
	('M0003','A0013',NULL,'room16',1,'Cisco SF302-08MPP'),
	('M0004','A0014',NULL,'room37',1,'D-Link DES-3828P'),
	('M0005','A0012',NULL,'room01',1,'Dell PowerEdge R210 II'),
	('M0006','A0001',NULL,'room05',1,'HP Z420 Workstation'),
	('M0006','A0002',NULL,'room06',2,'Dell Precision T3610'),
	('M0006','A0005','room39','room45',3,'Dell Inspiron 5547'),
	('M0007','A0004','room12','room15',2,'Macbook Pro 15-inch with Retina Display'),
	('M0007','A0007',NULL,'room01',1,'Canon PIXMA IX7000'),
	('M0008','A0003',NULL,'room16',1,'iMac 27-inch with 4K display'),
	('M0009','A0006',NULL,'room03',3,'Lenovo Z510'),
	('M0009','A0011',NULL,'room02',2,'IBM System x3250 M5'),
	('M0009','A0015',NULL,'room14',1,'D-Link DES-3052'),
	('M0010','A0009',NULL,'room05',1,'Cisco UCS C220 M3'),
	('M0011','A0008',NULL,'room17',1,'BROTHER MFC-J825DW'),
	('M0012','A0008','room17','room16',1,'BROTHER MFC-J825DW'),
	('M0013','A0009','room05','room19',1,'Cisco UCS C220 M3'),
	('M0014','A0010',NULL,'room07',1,'Samsung CLP-325'),
	('M0014','A0015',NULL,'room09',2,'D-Link DES-3052'),
	('M0015','A0001','room05','room20',2,'HP Z420 Workstation'),
	('M0015','A0011','room09','room45',1,'IBM System x3250 M5');

/*!40000 ALTER TABLE `assetmoveline` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table customer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `Code` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(200) NOT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;

INSERT INTO `customer` (`Code`, `Name`, `Address`)
VALUES
	('C0001','King Mongkut’s University of Technology Thonburi ','126 Pracha Uthit Rd., Bang Mod, Thung Khru, Bangkok 10140'),
	('C0002','Kasikorn Bank Head Office','1 Soi Rat Burana 27/1, Rat Burana Road, Rat Burana District, Rat Burana, Bangkok 10140'),
	('C0003','Asiasoft Corporation Public Company Limited','9 U.M. Tower, Room 9/283-5, 28th Floor, Ramkhamhaeng Road, Suanluang, Bangkok 10250, Thailand '),
	('C0004','Rose Media & Entertainment Co., Ltd.','1 Pishid Building Ratchadapisek Road, Bangphor, Yanawa, Bangkok 10120'),
	('C0005','VIRIYAH INSURANCE ','121/28, 121/65 RS Tower, Ratchadapisek Rd., Dindang, Bangkok 10400'),
	('C0006','Tiga Co Ltd.','2076/8 Nara Thiwas Rajanakarin Road, Chongnonsi Yannawa Bangkok,  10120 Thailand'),
	('C0007','King Mongkut\'s Institute of Technology Ladkrabang.','Chalongkrung Rd. Ladkrabang, Bangkok Thailand 10520'),
	('C0008','Central Retail Corporation Ltd.','ZEN World Level 16 , 4, 4/5 Rajadamri Road, Pathumwan,Bangkok 10330 Thailand '),
	('C0009','TOT Public Company Limited','89/2 Moo 3, Chaeng Watthana Road, Thungsong-Hong, Laksi, Bangkok 10210'),
	('C0010','True Corporation Public Company Limited ','18 True Tower, Ratchadaphisek Road, Huai Khwang, Bangkok 10310, Thailand'),
	('C0011','Thailand Post Co., Ltd.','111 M.3 Changwattana Road, Laksi, Bangkok, Thailand 10210-0299'),
	('C0012','PTT Public Company Limited','555 Vibhavadi Rangsit Road, Chatuchak Bangkok 10900 Thailand'),
	('C0013','Nationgroup','1858/118-119, 121-122, 124-130 Interlink Tower, 27th-32nd Floor, Bangna -Trad Road, Bangna, Bangkok 10260'),
	('C0014','SIAM INTER MULTIMEDIA PUBLIC COMPANY LIMITED','459 Soi Ladprao 48. Samsen-nork, Huay Kwang, Bangkok 10310 Thailand '),
	('C0015','Vibulkij Publishing Group','101/1 Soi Sukhumvit 36, Sukhumvit 22, Wattana, Bangkok 10260.');

/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table depreciation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `depreciation`;

CREATE TABLE `depreciation` (
  `depreciation_no` varchar(10) NOT NULL,
  `depreciation_date` date NOT NULL,
  `for_month` varchar(10) NOT NULL,
  `for_year` varchar(10) NOT NULL,
  `total_depreciation` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`depreciation_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `depreciation` WRITE;
/*!40000 ALTER TABLE `depreciation` DISABLE KEYS */;

INSERT INTO `depreciation` (`depreciation_no`, `depreciation_date`, `for_month`, `for_year`, `total_depreciation`)
VALUES
	('D001','2014-10-15','10','2014',573.33),
	('D002','2014-11-19','11','2014',1943.28),
	('D003','2014-12-02','12','2014',3138.07);

/*!40000 ALTER TABLE `depreciation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table depreciation_line
# ------------------------------------------------------------

DROP TABLE IF EXISTS `depreciation_line`;

CREATE TABLE `depreciation_line` (
  `depreciation_no` varchar(10) NOT NULL,
  `asset_type` varchar(50) NOT NULL,
  `asset_id` varchar(10) NOT NULL,
  `asset_name` varchar(50) NOT NULL,
  `depreciation_percent` decimal(10,2) NOT NULL,
  `purchase_value` decimal(10,2) NOT NULL,
  `beginning value` decimal(10,2) NOT NULL,
  `depreciation_value` decimal(10,2) NOT NULL,
  `current_value` decimal(10,2) NOT NULL,
  `depreciation_value_month` decimal(10,2) NOT NULL,
  `new_depreciation_value_month` decimal(10,2) NOT NULL,
  `item_no` int(10) NOT NULL,
  PRIMARY KEY (`depreciation_no`,`asset_id`),
  KEY `asset_id` (`asset_id`),
  KEY `asset_type` (`asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `depreciation_line` WRITE;
/*!40000 ALTER TABLE `depreciation_line` DISABLE KEYS */;

INSERT INTO `depreciation_line` (`depreciation_no`, `asset_type`, `asset_id`, `asset_name`, `depreciation_percent`, `purchase_value`, `beginning value`, `depreciation_value`, `current_value`, `depreciation_value_month`, `new_depreciation_value_month`, `item_no`)
VALUES
	('D001','Server','A0009','Cisco UCS C220 M3',8.00,86000.00,86000.00,6880.00,79120.00,573.33,85426.67,1),
	('D002','Desktop','A0001','HP Z420 Workstation',5.00,62000.00,62000.00,3100.00,58900.00,258.33,61741.67,1),
	('D003','Desktop','A0001','HP Z420 Workstation',5.00,62000.00,62000.00,3100.00,58900.00,258.33,61741.67,1);

/*!40000 ALTER TABLE `depreciation_line` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table purchases
# ------------------------------------------------------------

DROP TABLE IF EXISTS `purchases`;

CREATE TABLE `purchases` (
  `InvoiceNo` varchar(10) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `SupplierCode` varchar(10) NOT NULL,
  `PaymentDueDate` date NOT NULL,
  `PaymentTerm` varchar(10) NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `VAT` decimal(10,2) NOT NULL,
  `AmountDue` decimal(10,2) NOT NULL,
  PRIMARY KEY (`InvoiceNo`),
  KEY `SupplierCode` (`SupplierCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;

INSERT INTO `purchases` (`InvoiceNo`, `InvoiceDate`, `SupplierCode`, `PaymentDueDate`, `PaymentTerm`, `Total`, `VAT`, `AmountDue`)
VALUES
	('IN0001','2014-08-30','S0009','2014-09-01','check',86000.00,6020.00,92020.00),
	('IN0002','2014-09-17','S0008','2014-09-17','cash',13600.00,952.00,14552.00),
	('IN0003','2014-09-18','S0007','2014-09-18','cash',62000.00,4340.00,66340.00),
	('IN0004','2014-09-18','S0008','2014-10-18','cash',13750.00,962.50,14712.50),
	('IN0005','2014-09-19','S0010','2014-09-21','check',8400.00,588.00,8988.00),
	('IN0006','2014-09-20','S0003','2014-09-20','cash',75000.00,5250.00,80250.00),
	('IN0007','2014-09-24','S0011','2014-09-24','cash',88370.00,6185.90,94555.90),
	('IN0008','2014-09-25','S0012','2014-09-25','cash',56000.00,3920.00,69920.00),
	('IN0009','2014-10-01','S0012','2014-10-03','check',75000.00,5250.00,80250.00),
	('IN0010','2014-10-01','S0006','2014-10-01','cash',7470.00,529.00,7999.00),
	('IN0011','2014-10-02','S0013','2014-10-02','cash',32250.00,2257.50,34506.50),
	('IN0012','2014-10-02','S0004','2014-10-02','cash',27900.00,1953.00,29853.00),
	('IN0013','2014-10-02','S0003','2014-10-02','cash',45000.00,3150.00,48150.00),
	('IN0014','2014-10-02','S0011','2014-10-02','cash',22470.00,1572.90,24042.90),
	('IN0015','2014-10-05','S0003','2014-10-07','check',32000.00,2240.00,34240.00);

/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table purchaseslineitem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `purchaseslineitem`;

CREATE TABLE `purchaseslineitem` (
  `InvoiceNo` varchar(10) NOT NULL,
  `ItemNo` int(10) NOT NULL,
  `AssetID` varchar(10) NOT NULL,
  `AssetName` varchar(50) NOT NULL DEFAULT '',
  `Units` varchar(10) NOT NULL DEFAULT '',
  `Price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`InvoiceNo`,`ItemNo`),
  KEY `AssetID` (`AssetID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `purchaseslineitem` WRITE;
/*!40000 ALTER TABLE `purchaseslineitem` DISABLE KEYS */;

INSERT INTO `purchaseslineitem` (`InvoiceNo`, `ItemNo`, `AssetID`, `AssetName`, `Units`, `Price`)
VALUES
	('IN0001',1,'A0009','Cisco UCS C220 M3','unit',86000.00),
	('IN0002',1,'A0013','Cisco SF302-08MPP','pcs',13600.00),
	('IN0003',1,'A0001','HP Z420 Workstation','unit',62000.00),
	('IN0004',1,'A0007','Canon PIXMA IX7000','pcs',13750.00),
	('IN0005',1,'A0008','BROTHER MFC-J825DW','pcs',8400.00),
	('IN0006',1,'A0002','Dell Precision T3610','unit',75000.00),
	('IN0007',1,'A0014','D-Link DES-3828P','pcs',65900.00),
	('IN0007',2,'A0015','D-Link DES-3052','pcs',22470.00),
	('IN0008',1,'A0003','iMac 27-inch with 4K display','unit',56000.00),
	('IN0009',1,'A0004','Macbook Pro 15-inch with Retina Display','pcs',75000.00),
	('IN0010',1,'A0010','Samsung CLP-325','pcs',7470.00),
	('IN0011',1,'A0006','Lenovo Z510','pcs',32250.00),
	('IN0012',1,'A0011','IBM System x3250 M5','unit',27900.00),
	('IN0013',1,'A0005','Dell Inspiron 5547','pcs',45000.00),
	('IN0014',1,'A0015','D-Link DES-3052','pcs',22470.00),
	('IN0015',1,'A0012','Dell PowerEdge R210 II','unit',32000.00),
	('IN0016',1,'A0003','iMac 27-inch with 4K display','unit',66000.00);

/*!40000 ALTER TABLE `purchaseslineitem` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sales
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `InvoiceNo` varchar(10) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `CustomerCode` varchar(10) NOT NULL,
  `PaymentDueDate` date NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `VAT` decimal(10,2) NOT NULL,
  `AmountDue` decimal(10,2) NOT NULL,
  PRIMARY KEY (`InvoiceNo`),
  KEY `1` (`CustomerCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;

INSERT INTO `sales` (`InvoiceNo`, `InvoiceDate`, `CustomerCode`, `PaymentDueDate`, `Total`, `VAT`, `AmountDue`)
VALUES
	('IN0001','2014-10-01','C0001','2014-10-04',58900.00,5890.00,64790.00),
	('IN0002','2014-10-02','C0002','2014-10-05',67500.00,6750.00,74250.00),
	('IN0003','2014-10-03','C0003','2014-10-05',79120.00,7912.00,87032.00),
	('IN0004','2014-10-06','C0004','2014-10-09',26505.00,2650.50,29155.50),
	('IN0005','2014-10-08','C0005','2014-10-10',31680.00,3168.00,34848.00),
	('IN0006','2014-10-08','C0005','2014-10-10',67500.00,6750.00,74250.00),
	('IN0007','2014-10-09','C0006','2014-10-13',72375.00,7237.50,79612.50),
	('IN0008','2014-10-10','C0007','2014-10-14',13600.00,1360.00,14960.00),
	('IN0009','2014-10-13','C0008','2014-10-17',58900.00,5890.00,64790.00),
	('IN0010','2014-10-14','C0009','2014-10-17',56000.00,5600.00,61600.00),
	('IN0011','2014-10-20','C0010','2014-10-25',7560.00,756.00,8316.00),
	('IN0012','2014-10-22','C0012','2014-10-26',27412.50,2741.25,30153.75),
	('IN0013','2014-10-25','C0011','2014-10-29',62275.50,6227.55,68503.05),
	('IN0014','2014-10-27','C0014','2014-10-30',21458.85,2145.89,23604.74),
	('IN0015','2014-10-31','C0015','2014-11-01',72375.00,7237.50,79612.50);

/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table saleslineitem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `saleslineitem`;

CREATE TABLE `saleslineitem` (
  `InvoiceNo` varchar(10) NOT NULL,
  `ItemNo` int(10) NOT NULL,
  `AssetID` varchar(10) NOT NULL,
  `AssetName` varchar(50) DEFAULT NULL,
  `Units` varchar(10) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ItemNo`,`InvoiceNo`),
  KEY `1` (`AssetID`),
  KEY `InvoiceNo` (`InvoiceNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `saleslineitem` WRITE;
/*!40000 ALTER TABLE `saleslineitem` DISABLE KEYS */;

INSERT INTO `saleslineitem` (`InvoiceNo`, `ItemNo`, `AssetID`, `AssetName`, `Units`, `Price`)
VALUES
	('IN0001',1,'A0001','HP Z420 Workstation','unit',58900.00),
	('IN0002',1,'A0002','Dell Precision T3610','unit',67500.00),
	('IN0003',1,'A0009','Cisco UCS C220 M3','unit',79120.00),
	('IN0004',1,'A0011','IBM System x3250 M5','unit',26505.00),
	('IN0005',1,'A0012','Dell PowerEdge R210 II','unit',31680.00),
	('IN0006',1,'A0002','Dell Precision T3610','unit',67500.00),
	('IN0007',1,'A0004','Macbook Pro 15-inch with Retina Display','pcs',72375.00),
	('IN0008',1,'A0013','Cisco SF302-08MPP','pcs',13600.00),
	('IN0009',1,'A0001','HP Z420 Workstation','unit',58900.00),
	('IN0010',1,'A0003','iMac 27-inch with 4K display','unit',56000.00),
	('IN0011',1,'A0008','BROTHER MFC-J825DW','pcs',7560.00),
	('IN0012',1,'A0006','Lenovo Z510','pcs',27412.50),
	('IN0013',1,'A0014','D-Link DES-3828P','pcs',62275.50),
	('IN0014',1,'A0015','D-Link DES-3052','pcs',21458.85),
	('IN0015',1,'A0004','Macbook Pro 15-inch with Retina Display','pcs',72375.00);

/*!40000 ALTER TABLE `saleslineitem` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table supplier
# ------------------------------------------------------------

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `Code` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(200) NOT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;

INSERT INTO `supplier` (`Code`, `Name`, `Address`)
VALUES
	('S0001','Acer Computer Co., Ltd.','191/62-63 16th Floor CTI Tower RachadapisedKlongToey'),
	('S0002','Powell Computer Co., Ltd.','100/199 Rama 9 Huakwang'),
	('S0003','Dell Computer Co., Ltd','1558/40 Similan Office, Sanfran 9 ,Office Park , Bangna, Bangkok 10260 Thailand'),
	('S0004','IBM Thailand Co., Ltd','IBM Building 388 Phaholyothin Road Bangkok 10400. Thailand'),
	('S0005','Jebsen & Jessen Marketing (T) Ltd','23/110-113, Sorachai Building. 25th-28th floor., Sukhumvit 63 (Ekamai) Sukhumvit, North Klongton, Wattana'),
	('S0006','Thai Samsung Electronics Co.,Ltd.','313 Moo 1  Sriracha Industrail Park  Sukhapibal 8  Banbung  Sriracha  Chonburi  20230'),
	('S0007','Hewlett-Packard (Thailand) Ltd.','2nd- 3rd Floor U Chu Liang Building, 968 Rama IV Road, Silom, Bangrak, Bangkok 10500'),
	('S0008','Canon Marketing (Thailand) Co., Ltd.','98 Sathorn Square Office Tower 21st - 24th floor, North Sathorn Rd. Silom,Bangrak, Bangkok 10500'),
	('S0009','Cisco systems (Thailand) Ltd.','Central World 999/9 Rama I Road, Patumwan Bangkok 10330'),
	('S0010','Brother Commercial (Thailand) Limited. ','Screen Printing Products Supplier, Bangkok, Bangkok 10330, Thailand'),
	('S0011','SCT Systems Co.,Ltd. Thailand','370/7 Supatra Building 4th floor Rama IX Road 2 10320'),
	('S0012','Applu Chu Shop','46 Borommaratchachonnani Road, Arun Amarin, Bangko'),
	('S0013','Lenovo (Thailand) Limited','89 Ratchadaphisek Road Dindaeng, Bangkok 10400.');

/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
