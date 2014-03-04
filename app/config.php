<?php
	
	// Global config file
	$Config = &App::config();

	$Config->subApps = array ('admin'); // subapplications, required
	$Config->langs = array ('en' => 'Английский', 'ru' => 'Русcкий'); // supported languages, required
	$Config->lang = 'en'; //default language, required

	// It'ss necessarily. development|production
	$Config->environment = 'development';

	// databse connections
	$Config->connections = array (
		'development' => array (
		    'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => 'bongo',
            'username'  => 'root',
            'password'  => 'unreal',
            'charset'   => 'utf8', // Optional
            'collation' => 'utf8_unicode_ci' // Optional
            //'prefix'    => '', // Table prefix, optional
		)
	);

	//set current connection
	$Config->default_connection = $Config->environment;
?>