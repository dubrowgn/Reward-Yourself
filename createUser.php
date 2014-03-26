<?php

require('include/application.php');
require('include/api.php');

// connect to database
$mysqli = db_connect();

//init variables
$error = "";
$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

// check if the user has submitted their details.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = auth::create_user($mysqli, $username, $password);
	if (empty($error))
		url::redirect("home.php"); // index page
} // if

//create twig object
$twig = twig();

$body = $twig->render('createUser.html', [
	'error' => $error,
	'username' => $username
]);

echo $twig->render('logo.html', [
	'title' => 'Create User',
	'body' => $body
]);

// close database
$mysqli->close();

?>
