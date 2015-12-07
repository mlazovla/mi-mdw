<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 04.11.15
 * Time: 17:03
 */

namespace App\Model;

/**
 * Persistent object SvgSimilarity.
 */
class SvgSimilarity extends \Nette\Database\Table\Selection {
	private $db;
	private $table = "svg_similarity";

	const CREATE_SYNTAX = "CREATE TABLE `svg_similarity` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`src_svg_id` int(11) unsigned NOT NULL,
			`dst_svg_id` int(11) unsigned NOT NULL,
			`angle` float DEFAULT NULL COMMENT 'Similarity coef.',
			`colors` float DEFAULT NULL COMMENT 'Similarity coef.',
			`total_similariry` float DEFAULT NULL COMMENT 'Similarity coef.',
			PRIMARY KEY (`id`),
			KEY `src_svg_id` (`src_svg_id`),
			KEY `dst_svg_id` (`dst_svg_id`),
			CONSTRAINT `svg_similarity_ibfk_1` FOREIGN KEY (`src_svg_id`) REFERENCES `svg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT `svg_similarity_ibfk_2` FOREIGN KEY (`dst_svg_id`) REFERENCES `svg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}
}