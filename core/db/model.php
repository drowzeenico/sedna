<?php

namespace Core\Db;

	class Model {

		public $table;

		public $QB;

		public $DB;

		public function __construct() {
			if($this->QB == null)
				$this->QB = &\App::QB();

			if($this->DB == null)
				$this->DB = &\App::DB();
		}

		public function all() {
			$sql = $this->QB->select('*')->from($this->table)->build();
			print_r($sql);
			return $this->DB->select($sql);
		}

		public function find($id) {
			if($id == null)
				return null;

			$sql = $this->QB->select('*')->
				from($this->table)->
				where('id', '=', $id)->
				limit(1)->build();
			$data = $this->DB->select($sql);
			if($data != null)
				return $data[0];

			return null;
		}

		public function findBy($key, $value) {
			if($key == null || $value == null)
				return null;

			$sql = $this->QB->select('*')->from($this->table)->where($key, '=', $value)->build();
			return $this->DB->select($sql);
		}

		public function create($data) {
			if($data == null)
				return null;

			$this->QB->insertInto($this->table);
			foreach ($data as $key => $value) {
				$this->QB->fields($key);
				$this->QB->values($value);
			}
			$sql = $this->QB->build();
			return $this->DB->insert($sql);
		}

	}
?>
