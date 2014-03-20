<?php

namespace Core {

	abstract class Controller {

		public $view;

		public $route;

		public $config;

		public $db;

		public function __construct() {
			$this->view = &\App::view();
			$this->route = &\App::route();
			$this->config = &\App::config();
			$this->db = &\App::DB();
		}

		public function before() {

		}

		public function after() {

		}

	}
}

?>