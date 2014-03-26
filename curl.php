<?php

require('application/application.php');
require('application/class/fitocracy-client.php');

// populate request variables
$r = new Request();
$errors = [];
$username = $r->post('username');
$xp = null;

// validation
if ($r->isPost() && blank($username))
	$errors['username'] = 'A valid Fitocracy username is required';

// look-up total xp from Fitocracy
if ($r->isPost() && !blank($username)) {
	// create new fitocracy client
	$fc = new FitocracyClient(\config\fitocracy_username, \config\fitocracy_password);

	// attempt to get total xp stat for user
	$fcResult = $fc->getTotalXp($username);
	if (isset($fcResult['error']))
		$errors[] = $fcResult['error'];
	else if (isset($fcResult['xp']))
		$xp = $fcResult['xp'];
} // if

// output view
$twig = twig();
echo $twig->render('admin/fitocracy-test.html', [
	'errors' => $errors,
	'xp' => $xp,
	'username' => $username,
	'invalidUsername' => $r->isPost() && blank($username)
]);

?>