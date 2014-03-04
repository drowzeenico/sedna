<?php

namespace Core\Db\PDO;
	use PDO;
	use PDOException;
	use PDOStatement;

	class Adapter {

		private $dbh;

		private $driver = 'mysql';
	    private $host = 'localhost';
	    private $database;
	    private $username = 'root';
	    private $password;
	    private $port = '';
	    private $prefix = '';
	    private $charset = 'utf8';

	    private $sql;
	    private $params;

	    private $dsnParts = array(
			'host'   => 'host',
			'dbname' => 'database',
			'port'   => 'port',
			'unix_socket' => 'socket',
		);
		
		public function __construct() {
			foreach (\App::config('default_connection') as $key => $value)
				$this->$key = $value;

			$this->connect();
		}
		
		protected function connect() {
			$dsn = $this->getDsn();
			
			try {
				$pdo = new PDO($dsn, $this->username, $this->password);
			}
			catch (PDOException $e) {
				die ('Database connection failed');
			}

			$this->dbh = $pdo;
			$this->dbh->exec("set names $this->charset");
		}

		public function disconnect () {
			$this->pdo = null;
			return true;
		}

		protected function getDsn() {
			$dsn = $this->driver . ':';
			foreach ($this->dsnParts as $name => $key) {
				if (isset($this->$key))
					$dsn .= $name . '=' . $this->$key . ';';
			}

			return $dsn;
		}

		protected function checkArgs($query, $params) {
			if(is_object($query))
				$this->sql = $query->sql;

			if(isset($query->params))
				$this->params = $query->params;
			else
				$this->params = $params;
		}

		protected function checkQuery($query) {
			if(isset($query->params))
				return $params;
			return $params;
		}
		
		public function insert($query, array $params = null) {
			$this->checkArgs($query, $params);
			$statement = $this->dbh->prepare($this->sql);
			$statement->execute($this->params);

			return $this->dbh->lastInsertId();
		}

	
		public function select($query, array $params = null) {
			$this->checkArgs($query, $params);
			$statement = $this->dbh->prepare($this->sql);

			if (!$statement->execute($this->params))
				return false;

			return $statement->fetchAll(PDO::FETCH_OBJ);
		}

		public function update($query, array $params = null) {
			$this->checkArgs($query, $params);
			$statement = $this->dbh->prepare($this->sql);
			$statement->execute($this->params);

			return $statement->rowCount();
		}

		public function delete($query, $params = null) {
			return $this->update($query, $params);
		}

		public function query ($query, array $params = null) {
			return $this->update($query, $params);
		}
	}
?>