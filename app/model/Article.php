<?php

namespace App\Model;

/**
 * Persistent object Article. 
 */
class Article extends \Nette\Database\Table\Selection {
    private $db;
    private $table = "article";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}
    
}
