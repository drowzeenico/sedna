<?php

namespace Exception;

	class PageNotFound extends \Core\Error {
		public function process(\Exception $e) {
			\Core\Response::setHeader(404);
			\App::view()->renderFile('error', 'pagenotfound');
		}
	}
?>