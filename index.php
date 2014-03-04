<?php
	/*Senda PHP-framework*/

	ini_set('display_errors', 1);
	mb_internal_encoding('UTF-8');
	header('X-Powered-By: Sedna');

	// init paths at Sedna
	define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);
	define('SYSPATH', DOCROOT . '/core');
	define('MODPATH', DOCROOT . '/modules');
	define('APPPATH', DOCROOT . '/app');

	// get autoloader class
	include (SYSPATH . '/autoload.php');

	// init autoloader and mapping namespaces
	$Autoloader = new \Core\Autoload('core', SYSPATH);
	$Autoloader->addNamespace('Model', APPPATH .'/model');

	// register our autoloader
	$Autoloader->register();

	// App is the Regisrty pattern. 
	// Will use it as container for frameworks services
	\App::set('view', 'Core\View');
	\App::set('session', 'Core\Session');
	\App::set('validator', 'Core\Validator');
	\App::set('config', 'Core\Config');
	\App::set('i18n', 'Core\i18n');
	\App::set('DB', 'Core\Db\PDO\Adapter');
	\App::set('QB', 'Core\Db\QB');

	// special variable for saving app states
	\App::$state = new StdClass();

	// include users config file
	include_once ('app/config.php');

	// init Router with subapps
	$Router = new \Core\Router(\App::config()->subApps);
	$Router->setLang(\App::config()->lang);
	$Router->parseUrl();

	// save app current language
	\App::$state->lang = $Router->lang;

	// save current app
	\App::$state->app = $Router->app;

	// controllers namespaces mapping
	$Autoloader->addNamespace('Controller', APPPATH .'/controller');
	$Autoloader->addNamespace('Exception', APPPATH .'/exceptions');

	$Autoloader->addNamespace('\Core\Paris', SYSPATH . '/paris');

	foreach (\App::config()->subApps as $app) {
		$dir = ucfirst($app);
		$Autoloader->addNamespace('Controller\\' . $dir, APPPATH .'/controller/' . $app);
		// register error handlers
		$Autoloader->addNamespace('Exception\\' . $dir, APPPATH .'/exceptions/' . $app);
	}

	// find route and start app
	include_once (APPPATH . '/routes/' . \App::$state->app . '.php');
?>