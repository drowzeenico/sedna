<?php

namespace Core {

	abstract class Controller {

		public $view;

		public $route;

		public $config;

		public function __construct() {
			$this->view = &\App::view();
			$this->route = &\App::route();
			$this->config = &\App::config();
		}

		public function before() {

		}

		public function after() {

		}

	}
}

?>