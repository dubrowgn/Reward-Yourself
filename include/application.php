<?php

// pull in config constants
require(path('/include/config.php'));

// pull in global functions
require(path('/include/function.php'));

// pull in classes
require(path('/include/class/request.php'));

// pull in twig library
require(path('/lib/Twig/Autoloader.php'));
Twig_Autoloader::register();

// init session
session_start();

function path($relPath) {
	return rtrim(dirname(__DIR__), '/') . '/' . ltrim($relPath, '/');
} // path( )

?>
