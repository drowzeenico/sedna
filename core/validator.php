<?php

namespace Core {

	use Closure;

	class Validator {

		public $errors = null;

		private $rules = array ();
		private $field;
		private $message;

		public function __construct() {
			$this->types = array (
				'int' => function ($value) {
					return is_numeric($value) && (int) $value == $value ? true : false;
				},
				'float' => function($value) {
					return is_numeric($value) && (float) $value == $value ? true : false;
				},
				'alpha' => '[A-zА-я]+',
				'alphanum' => '[A-zА-я0-9__]+',
				'email' => '[A-z0-9_]+([.-][A-z0-9_-]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}'
			);	
		}

		public function set($type, $rule) {
			$this->types[$type] = $rule;
		}

		private function notValid($criteria) {
			$this->errors[$this->field][$criteria] = $this->message;
		}

		private function required($value) {
			if(empty($value))
				$this->notValid(__FUNCTION__);
		}

		private function max($value, $val) {
			if($value > $val)
				$this->notValid(__FUNCTION__);
		}

		private function min($value, $val) {
			if($value < $val)
				$this->notValid(__FUNCTION__);
		}

		private function strlen($value, array $val) {
			$len = mb_strlen($value);
			if($val[1] == '*')
				$val[1] = PHP_INT_MAX;
			if($len < $val[0] || $len > $val[1])
				$this->notValid(__FUNCTION__);
		}

		private function in($value, array $vals) {
			if(!in_array($value, $vals))
				$this->notValid(__FUNCTION__);
		}

		private function not_in($value, array $vals) {
			if(in_array($value, $vals))
				$this->notValid(__FUNCTION__);
		}

		private function match($value, $val) {
			if(!preg_match('/^' . $val . '$/', $value))
				$this->notValid(__FUNCTION__);
		}

		private function between($value, array $vals) {
			$first = $vals[0];
			$last = $vals[1];

			if($value < $first || $value > $last)
				$this->notValid(__FUNCTION__);
		}

		private function type($value, $type) {
			if(is_object($this->types[$type]) && get_class($this->types[$type]) == 'Closure') {
				if($this->types[$type]($value) === false)
					$this->notValid(__FUNCTION__);
			} else {
				$regexp = '/^'.$this->types[$type].'$/Ui';
				if(!preg_match($regexp, $value))
					$this->notValid(__FUNCTION__);
			}
		}

		private function check($value, $rule) {

			$itemRules = $rule[0];
			$errors = $rule[1];

			$rules = explode ('|', $itemRules);

			$errorIndex = 0;
			foreach ($rules as $rule) {
				$params = explode(':', $rule);
				$criteria = $params[0];
				$values = array ();

				if(isset($params[1])) {
					$values = explode(',', $params[1]);
					if(count($values) == 1)
						$values = $values[0];
				}

				if(isset($errors[$errorIndex]))
					$this->message = $errors[$errorIndex];
				else
					$this->message = false;
				$errorIndex++;

				$this->{$criteria}($value, $values);
			}

			if($this->errors === null)
				return true;

			return false;
		}

		public function validate($data, $rules) {
			$this->errors = null;
			$this->rules = array ();
			$this->message = null;

			$this->rules = $rules;

			foreach ($data as $key => $value) {
				if(isset($this->rules[$key])) {
					$this->field = $key;
					$this->check($value, $this->rules[$key]);
				}
			}
		}
	}
}?>