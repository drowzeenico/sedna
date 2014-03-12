<?php
/**
 * A simple wrapper class for the Laravel Database package.  This is only
 * to be used outside of a Laravel application.
 *
 * @author  Dan Horrigan <dan@dhorrigan.com>
 */

namespace Capsule;

/**
 * A simple wrapper class for the Laravel Database package.
 */
class DB {

    /**
     * Passes calls through to the Connection object.
     *
     * @param   string  The method name
     * @param   array   The method parameters sent
     * @return  mixed   The result of the call
     */
    public static function __callStatic($method, $parameters) {

    	if(!\App::config('eloquent')) {
			require_once SYSPATH . '/eloquent/autoload.php';

			$default = \App::config('default_connection');
			$connections = \App::config('connections');
			\Capsule\Database\Connection::make('default', $connections[$default], true);

			\App::config('eloquent', true);
		}

        return call_user_func_array(array(Database\Connection::get(), $method), $parameters);
    }
}
