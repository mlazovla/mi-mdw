-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

SET time_zone = '+00:00';# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

SET foreign_key_checks = 0;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


DROP TABLE IF EXISTS `article`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `article_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `article` (`id`, `category_id`, `created_at`, `name`, `author_id`, `text`) VALUES
(1,	1,	'2015-04-06 22:11:21',	'Spouštíme nový web',	1,	'Fuckin awesome!!!! Jsme online!');# Ovlivněn 1 řádek.


DROP TABLE IF EXISTS `author`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bio` text NOT NULL COMMENT 'Biography about author.',
  `name` varchar(255) NOT NULL COMMENT 'Author name to display',
  `user_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL COMMENT 'Author avatar',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `author_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `author_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `author` (`id`, `bio`, `name`, `user_id`, `file_id`) VALUES
(1,	'Jsem studentem infotmatiky na ČVUT. Ingress je mým koníčkem. Hraju téměř od začátku, hru jsem začal hrát spolu se svým provním chytrým telefonem a dnes se hře věnuji se svými přáteli, cestujeme za anomáliemi a obracíme města na stranu osvícení. FIGHT THE RESISTANCE!',	'Vláďa',	2,	1);# Ovlivněn 1 řádek.


DROP TABLE IF EXISTS `category`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(63) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `category` (`id`, `code`, `name`) VALUES
(1,	'update',	'Aktualizace'),
(2,	'mechanism',	'Herní mechanismy'),
(3,	'interview',	'Rozhovory');# Ovlivněny 3 řádky.


DROP TABLE IF EXISTS `file`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL COMMENT 'Path to file',
  `type` enum('picture','document','other') DEFAULT 'other' COMMENT 'picture, document, other',
  `user_id` int(11) DEFAULT NULL COMMENT 'Owner',
  `nice_name` varchar(255) DEFAULT NULL COMMENT 'Name as title',
  `keywords` varchar(255) DEFAULT NULL COMMENT 'Keywords',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `file_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Paths to pictures, documents and other files.';# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `file` (`id`, `path`, `type`, `user_id`, `nice_name`, `keywords`) VALUES
(1,	'author/vml.jpg',	'picture',	2,	'vml',	'author profile picture, vml');# Ovlivněn 1 řádek.


DROP TABLE IF EXISTS `ingress_acount`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `ingress_acount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Ingress name',
  `faction` enum('ENL','RES','RED','BAN') DEFAULT NULL COMMENT 'faction: ENL, RES, RED, BAN',
  `level` tinyint(4) DEFAULT NULL COMMENT 'Level',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `ingress_acount` (`id`, `name`, `faction`, `level`) VALUES
(1,	'vml',	'ENL',	15);# Ovlivněn 1 řádek.


DROP TABLE IF EXISTS `ingress_version`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `ingress_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `teardown` varchar(255) DEFAULT 'Link to teardown',
  `release` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `ingress_version` (`id`, `name`, `description`, `teardown`, `release`) VALUES
(1,	'1.78.0',	'MUFG Capsule, čeština, reorganizace medailí',	'https://fevgames.net/ingress-apk-teardown-1-78-0/',	'2002-06-20 15:00:00'),
(2,	'1.79.0',	'Ultra Link Amp, barevné spojnice mezi rezonátory a portálem, linkovatelnost se řeší nejprve na klientské straně a poté na serveru',	'https://fevgames.net/ingress-apk-teardown-1-79-0/',	'2015-06-16 22:39:59');# Ovlivněny 2 řádky.


DROP TABLE IF EXISTS `role`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1,	'visitor',	'Čtenář webu, zákazník.'),
(2,	'moderator',	'Moderátor webu, správce diskuzí, autor článků.'),
(3,	'client',	'Klient webu dodávající zboží.'),
(4,	'admin',	'Administrátor webu.');# Ovlivněny 4 řádky.


DROP TABLE IF EXISTS `user`;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL COMMENT 'email and login',
  `password` varchar(255) NOT NULL COMMENT 'hashed password',
  `role_id` int(11) DEFAULT NULL COMMENT 'user_role',
  `ingress_acount_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `ingress_acount_id` (`ingress_acount_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`ingress_acount_id`) REFERENCES `ingress_acount` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).


INSERT INTO `user` (`id`, `email`, `password`, `role_id`, `ingress_acount_id`) VALUES
(2,	'mlazovla@gmail.com',	'*02A2758B5D1D639CE9FCC3403DF9EDE1FFD9DC03',	4,	1);# Ovlivněn 1 řádek.


-- 2015-07-06 13:53:12# MySQL vrátil prázdný výsledek (tj. nulový počet řádků).
