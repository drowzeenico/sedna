<?php

namespace Controller;
	
	class Index extends \Core\Controller {

		public function __construct() {
			parent::__construct();
		}

		public function index() {
			$this->view->title = 'Sedna';

			$user = \Model\Account::find(1);
			if($user == null)
				throw new \Exception\PageNotFound();

			$this->view->users = \Model\Account::all();

			$data = array (
				'name' => 'Nico',
				'lastname' => '',
				'year' => '19.5'
			);

			$rules = array (
				'name' => 'required|type:alpha|strlen:6,*',
				'lastname' => 'required|type:alpha|strlen:6,*',
				'year' => 'required|type:int|min:20'
			);

			if(\App::validator()->validate($data, $rules))
				echo 'success';
			else
				print_r(\App::validator('errors'));
		}
	}

?>