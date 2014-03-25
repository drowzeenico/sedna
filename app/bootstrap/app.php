<?php

namespace Bootstrap;

	class App extends \Core\Controller {

		public function index_before() {
			echo "before()<br>";
		}

		public function index_after() {
			echo "after()";
		}
		
	}
	
?>
