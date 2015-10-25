# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21)
# Database: alena
# Generation Time: 2015-08-18 06:03:27 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table article
# ------------------------------------------------------------

CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `annotation` longtext,
  `text` longtext NOT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `url` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;

INSERT INTO `article` (`id`, `created_at`, `name`, `user_id`, `annotation`, `text`, `menu`, `url`)
VALUES
  (1,'2015-04-06 22:11:21','První článek z editoru',1,'<p>Tohle se zobraz&iacute; jako n&aacute;hled, možn&aacute; to ani na webu nebude potřeba.</p>','<p>Asi bych to sem nměl ps&aacute;t, ale heslo je ale pst ;-)</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>','První článek',''),
  (3,'2015-07-19 11:13:42','O projektu',3,'<h2>O projektu Hudební S.O.S</h2>\n<ul>\n<li>o čem to má být</li>\n<li>jak to vzniklo</li>\n<li>co to vše zahrnuje</li>\n</ul>','<h1>Zakladatelka (1)</h1>\n<h2>Sponzoři (2)</h2>\n<h3>Spolupracujeme (3)</h3>\n<p>další informace a odkazy na weby spolupracovníků Hudebního S.O.S.</p>\n<p>povídání o Majce vzdělání atd.</p>\n<p>publikační činnost... náhledy obálek publikací a popis</p>\n<p>youtube fotoprezenatce &sbquo;z mého života&lsquo; (vložená nebo odkaz) nebo pár fotek</p>\n<p>Soukromé pedagogické nakladateství dělá bla bla atd... jak sponzoruje Hudební S.O.S.</p>\n<p>kde můžete koupit publikace apod.</p>\n<p>odkaz na web</p>\n<p>další informace a odkazy na weby sponzorů Hudebního S.O.S.</p>\n<p>Ficae voluptaepro dolupta distius andanda con parcia doluptiae. Et dus duntiorum nullab il eum sum, sam sequi quasped magnim nia por am essequa ernatur?</p>\n<p>Metur, sollam natis audis alibusdam ea dolore nos ab ium fugitis eosae. Nam aut laut maxim quo quamento mi, sandici ducimporum quidi culluptatur, sus res ipitatur? Quibus esequo que perum experchit occuptaquae porepro bea dolumqui te delenet laborehentia volupitat magnat velecepta eos ditatur re mint evelitatem et am quiae ium ea prat aliciet anis id mos ipsunt am aborehe ndelentet quaerfe rnatemporro eatempore ma comniscium aut experem rent, quaes dolor saest aspicipic te con pelecto cum hitem rem liquatem quatent ioribus ex est explab ipic tem rendis ut et qui vent vellore rcillandaes entotasim quame pori utempor eseritat quam, volendamus cumquas pellento is dolor magnat.</p>',NULL,NULL);

/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table category
# ------------------------------------------------------------

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '1',
  `parent` int(11) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `name`, `visible`, `parent`, `description`)
VALUES
  (1,'Důlní stroje',1,NULL,'<p>Toto je pecka super duper kategorie. ňuf</p>'),
  (5,'Stroje',1,NULL,'<p>VRCHN&Iacute; kategorie</p>');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table category_goods
# ------------------------------------------------------------

CREATE TABLE `category_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `goods_id` (`goods_id`),
  CONSTRAINT `category_goods_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_goods_ibfk_2` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='M:N category:goods';



# Dump of table goods
# ------------------------------------------------------------

CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` longtext NOT NULL,
  `stock` int(11) NOT NULL,
  `recommended` tinyint(4) NOT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `manufcturer_id` (`manufacturer_id`),
  CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `goods` WRITE;
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;

INSERT INTO `goods` (`id`, `name`, `category_id`, `description`, `stock`, `recommended`, `manufacturer_id`)
VALUES
  (1,'Zelený bagřík',1,'<p>Toto je zelen&yacute; bagř&iacute;k.</p>',12,0,1),
  (2,'Montérky',NULL,'<p>Mont&eacute;rky do pr&aacute;ce.</p>',2,0,2);

/*!40000 ALTER TABLE `goods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table manufacturer
# ------------------------------------------------------------

CREATE TABLE `manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `manufacturer` WRITE;
/*!40000 ALTER TABLE `manufacturer` DISABLE KEYS */;

INSERT INTO `manufacturer` (`id`, `name`, `description`)
VALUES
  (1,'Pan Bagr','<p>Pan bagr je super chap co vyr&aacute;b&iacute; bagry.</p>'),
  (2,'Obleky CZ','<p>Čeks&yacute; v&yacute;rovce obleků prvotř&iacute;dn&iacute; kvality.</p>');

/*!40000 ALTER TABLE `manufacturer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table photo
# ------------------------------------------------------------

CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `goods_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `photo` WRITE;
/*!40000 ALTER TABLE `photo` DISABLE KEYS */;

INSERT INTO `photo` (`id`, `name`, `description`, `goods_id`)
VALUES
  (1,'1001-BA.jpg','',NULL),
  (2,'1001-BA.jpg','',NULL),
  (3,'1001-BA.jpg','',NULL),
  (4,'1001-BA.jpg','',NULL),
  (5,'1001-BA.jpg','',NULL),
  (6,'1001-BA.jpg','',NULL),
  (7,'1001-BA.jpg','',NULL),
  (8,'1001-BA.jpg','',2),
  (9,'1001-BA.jpg','',2),
  (12,'bagrbagr','',1),
  (13,'bagrbagr','',1);

/*!40000 ALTER TABLE `photo` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL COMMENT 'email and login',
  `password` varchar(255) NOT NULL COMMENT 'hashed password',
  `role` varchar(255) DEFAULT 'guest' COMMENT 'user_role',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `password`, `role`)
VALUES
  (3,'liskovamaj@gmail.com','$2y$10$FIrpbNKoJLM7RHvfLwVwgOP6d/ct..o8xHzwXQA.q/plDMvbXMV8m','guest'),
  (4,'mlazovla@gmail.com','$2y$10$6RVFte.MWVs2YuYD.w83OeGpkZg2GGrfT2.RRgq2H2H3.jk6AVrlK','guest');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
