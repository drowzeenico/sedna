<?php
	/*Senda PHP-framework*/

	ini_set('display_errors', 1);
	mb_internal_encoding('UTF-8');
	header('X-Powered-By: Sedna');

	// init paths at Sedna
	define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);
	define('SYSPATH', DOCROOT . '/core');
	define('MODPATH', DOCROOT . '/modules');
	define('APP', DOCROOT . '/app');

	// get autoloader class
	include (SYSPATH . '/autoload.php');

	// init autoloader and mapping namespaces
	$Autoloader = new \Core\Autoload('core', SYSPATH);
	$Autoloader->addNamespace('Model', APP .'/model');

	$Autoloader->addNamespace('Capsule', SYSPATH .'/eloquent/dhorrigan/capsule/lib/capsule');
	$Autoloader->addNamespace('Capsule\Database', SYSPATH .'/eloquent/dhorrigan/capsule/lib/capsule/database');

	// register our autoloader
	$Autoloader->register();

	// App is the Regisrty pattern. 
	// Will use it as container for frameworks services
	\App::set('view', 'Core\View');
	\App::set('session', 'Core\Session');
	\App::set('validator', 'Core\Validator');
	\App::set('config', 'Core\Config');
	\App::set('i18n', 'Core\i18n');

	// special variable for saving app states
	\App::$state = new StdClass();

	// include users config file
	include_once ('config.php');

	\App::set('DB', function () {
		if(!\App::config('eloquent')) {

			require_once SYSPATH . '/eloquent/autoload.php';

			$default = \App::config('default_connection');
			$connections = \App::config('connections');
			\Capsule\Database\Connection::make('default', $connections[$default], true);

			\App::config('eloquent', true);
		}

		return new \Capsule\Db;
	});

	// init Router with subapps
	$Router = new \Core\Router(\App::config()->subApps);
	$Router->setLang(\App::config()->lang);
	$Router->parseUrl();

	// save app current language
	\App::$state->lang = $Router->lang;

	if($Router->app == 'app')
		$Router->app = '';

	// save current app
	\App::$state->app = $Router->app;

	define ('APPPATH', APP . '/' . $Router->app);

	// controllers namespaces mapping
	$Autoloader->addNamespace($Router->app . '\Controller', APPPATH .'/controller');
	$Autoloader->addNamespace($Router->app . '\Exception', APPPATH .'/exceptions');

	/*foreach (\App::config()->subApps as $app) {
		$dir = ucfirst($app);
		$Autoloader->addNamespace('Controller\\' . $dir, APPPATH .'/controller/' . $app);
		// register error handlers
		$Autoloader->addNamespace('Exception\\' . $dir, APPPATH .'/exceptions/' . $app);
	}*/

	// find route and start app
	include_once (APPPATH . '/routes.php');
?>