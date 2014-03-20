<?php

namespace Bootstrap;

	class Admin extends \Core\Controller {

		public function index_before() {
			echo "before()<br>";
		}

		public function index_after() {
			echo "after()";
		}
		
	}
	
?>
