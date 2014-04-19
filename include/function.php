<?php

function blank($val) {
	return $val === '' || $val === null || $val === false || $val === array();
} // blank( )

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

?>