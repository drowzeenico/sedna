<?php
	// global system classes container
	class App {

		// system components
		private static $services = array ();

		// applications state
		public static $state;

		protected function __construct() {}
    	protected function __clone() {}

    	/* set service
			@string key - key value
			@string | @object value - service class namespace or service object
    	*/

		public static function set($key, $value) {
			self::$services[$key] = $value;
		}

		/*
			get service
			@string key
			returns @object of service
		*/
		public static function get($key) {
			if(!isset(self::$services[$key]))
				return null;

			if(is_string(self::$services[$key]))
				self::set($key, new self::$services[$key]);

			return self::$services[$key];
		}

		/*
			get service by name
			@string method - service name
			@array args - services variable name
			return @object or @object's value by key
		*/
		public static function __callStatic($method, $args) {
			$service = self::get($method);

			if($args != null) {
				if(count($args) == 1)
					return $service->{$args[0]};
				else if(count($args) == 2)
					$service->{$args[0]} = $args[1];
			}

			return self::get($method);
		}
	}
?>