<?php

namespace Core {

	class Dispatcher {

		private $controller;

		public function __construct() {
			session_start();
			try {
				$this->run();
			} catch (\Exception $e) {
				if(method_exists($e, 'process'))
					$e->process($e);
				else
					print_r($e);
				exit;
			}
		}

		protected function run() {
			$controller = \App::route()->controller;
			$action = \App::route()->action;

			$View = \App::view();
			\App::$state->_sessionId = Session::$id;

			include_once (APPPATH . '/bootstrap.php');
			$class = '\bootstrap';
			$bootstrap = new $class;

			$clazz = \App::$state->app . '\Controller\\' . $controller;

			try {
				$this->controller = new $clazz();
			} catch(Exception $e) {
				throw new \Exception\PageNotFound();
			}

			$this->controller->before();
			if(method_exists($bootstrap, $action . '_before'))
				$bootstrap->{$action . '_before'}();

			if(!method_exists($this->controller, $action))
				throw new \Exception\PageNotFound();

			$this->controller->{$action}();
			if(method_exists($bootstrap, $action . '_after'))
				$bootstrap->{$action . '_after'}();

			$this->controller->after();

			Response::render();
		}

	}

}

?>
