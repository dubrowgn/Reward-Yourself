<?php

// pull in config constants
require(path('/include/config.php'));

// pull in twig library
require(path('/lib/Twig/Autoloader.php'));
Twig_Autoloader::register();

function blank($val) {
	return $val === '' || $val === null || $val === false || $val === array();
} // blank( )

function path($relPath) {
	return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . ltrim($relPath, '/');
} // path( )

function printDebugInfo() {
	echo "<pre>GET\n" . print_r($_GET, true) . "</pre>\n";
	echo "<pre>POST\n" . print_r($_POST, true) . "</pre>\n";
	echo "<pre>SESSION\n" . (isset($_SESSION) ? print_r($_SESSION, true) : '') . "</pre>\n";
	echo "<pre>SERVER\n" . print_r($_SERVER, true) . "</pre>\n";
} // printDebugInfo( )

function twig() {
	$loader = new Twig_Loader_Filesystem(path(\config\twig_path));
	return new Twig_Environment($loader, [
		'cache' => \config\twig_cache
	]);
} // twig( )

/**
 * A class for easily accessing GET/POST variables and related information
 * @package default
 */
class Request {
	/**
	 * Retrieves the specified GET variable, or $default if it is not set
	 * @param string $key The key of the variable to retrieve
	 * @param mixed $default The default value to return if the variable is not set
	 * @return mixed The specified GET variable or $default
	 */
	public function get($key, $default = null) {
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	} // get( )

	/**
	 * Returns true if the request method was GET
	 * @return bool true if the request method was GET
	 */
	public function isGet() {
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	} // isGet( )

	/**
	 * Returns true if the request method was POST
	 * @return bool true if the request method was POST
	 */
	public function isPost() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	} // isPost( )

	/**
	 * Retrieves the specified POST variable, or $default if it is not set
	 * @param string $key The key of the variable to retrieve
	 * @param mixed $default The default value to return if the variable is not set
	 * @return mixed The specified POST variable or $default
	 */
	public function post($key, $default = null) {
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	} // post( )
} // class

?>
