-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `svg`;
CREATE TABLE `svg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'File name',
  `content` mediumtext COMMENT 'SVG file content',
  `h_color` text COMMENT 'Color histogram',
  `h_angle` text COMMENT 'Angle histogram',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `svg_similarity`;
CREATE TABLE `svg_similarity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `src_svg_id` int(11) unsigned NOT NULL,
  `dst_svg_id` int(11) unsigned NOT NULL,
  `angle` float DEFAULT NULL COMMENT 'Similarity coef.',
  `colors` float DEFAULT NULL COMMENT 'Similarity coef.',
  PRIMARY KEY (`id`),
  KEY `src_svg_id` (`src_svg_id`),
  KEY `dst_svg_id` (`dst_svg_id`),
  CONSTRAINT `svg_similarity_ibfk_1` FOREIGN KEY (`src_svg_id`) REFERENCES `svg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `svg_similarity_ibfk_2` FOREIGN KEY (`dst_svg_id`) REFERENCES `svg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2015-11-04 15:31:59