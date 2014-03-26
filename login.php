<?php

require('include/application.php');
require('include/api.php');

// check if the user is already logged in
if (auth::revalidate())
	url::redirect(""); // index page
	
// connect to database
$mysqli = db_connect();

// init variables
$error = "";
$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

// check if the user has submitted their details.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = auth::login($mysqli, $username, $password);
	if (empty($error))
		url::redirect("home.php"); // index page
} // if

//create twig object
$twig = twig();

$body = $twig->render('login.html', [
	'error' => $error,
	'username' => $username
]);

echo $twig->render('logo.html', [
	'title' => 'Login',
	'body' => $body
]);


// close database
$mysqli->close();


?>