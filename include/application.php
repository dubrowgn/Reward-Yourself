<?php

// pull in config constants
require(path('/include/config.php'));

// pull in twig library
require(path('/lib/Twig/Autoloader.php'));
Twig_Autoloader::register();

function path($relPath) {
	return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . ltrim($relPath, '/');
} // path( )

function twig() {
	$loader = new Twig_Loader_Filesystem(path(\config\twig_path));
	return new Twig_Environment($loader, [
		'cache' => \config\twig_cache
	]);
} // twig( )

class Request {
	public function get($key, $default = null) {
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	} // get( )

	public function post($key, $default = null) {
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	} // post( )
} // class
?>
