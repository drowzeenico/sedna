<?php
	
	class Tables extends \Core\Schema{

		public $tables = array ();

		public function __construct() {
			// list of tables
			$this->tables = array (
				'users',
				'clients',
				'products',
				'articles'
			);
		}

		protected function users() {
			// fields of table 'users'
			$this->int('id')->primary()->autoincrement();
			$this->varchar('email', '255')->unique();
		}
	}

?>