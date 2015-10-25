<?php

namespace App\Model;

/**
 * Persistent object File.
 */
class File extends \Nette\Database\Table\Selection {
    private $db;
    private $table = "file";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}

}
