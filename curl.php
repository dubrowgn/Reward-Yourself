<?php

require('include/application.php');
require("include/class.fitocracy-client.php");

$errors = [];

$r = new Request();
$username = $r->post('username');
$xp = null;

if ($username === '')
	$errors[] = 'A valid Fitocracy username is required';

if (!empty($username)) {
	// create new fitocracy client
	$fc = new FitocracyClient(\config\fitocracy_username, \config\fitocracy_password);

	// attempt to get total xp stat for user
	$fcResult = $fc->getTotalXp($username);
	if (isset($fcResult['error']))
		$errors[] = $fcResult['error'];
	else if (isset($fcResult['xp']))
		$xp = $fcResult['xp'];
} // if

$twig = twig();

$body = $twig->render('admin/fitocracy-test.html', [
	'error' => implode("\n", $errors),
	'xp' => $xp,
	'invalidUsername' => $username === '',
	'username' => $username
]);

echo $twig->render('page.html', [
	'title' => 'Fitocracy Integration Test',
	'head' => '
		<style>
		form { max-width: 450px; }
		</style>', 
	'body' => $body
]);

?>