<?php

require('include/application.php');

$twig = twig();

echo $twig->render('page.html', [
	'title' => 'Home',
	'head' => '', 
	'body' => '<p> How cool am I! </p>'
]);

?>