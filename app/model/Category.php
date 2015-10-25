<?php

namespace App\Model;

/**
 * Persistent object category.
 */
class Category extends \Nette\Database\Table\Selection {
    private $db;
    private $table = "category";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}
    
}
