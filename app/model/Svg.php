<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 04.11.15
 * Time: 17:03
 */

namespace App\Model;

/**
 * Persistent object Svg.
 */
class Svg extends \Nette\Database\Table\Selection {
	private $db;
	private $table = "svg";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}

	/**
	 * Truncate the similarity table
	 */
	public function truncateSimilarityTable()
	{
		$this->context->getConnection()->pdo->query("TRUNCATE TABLE `svg_similarity`;");
	}
}
