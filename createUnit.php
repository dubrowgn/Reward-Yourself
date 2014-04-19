<?php
//create unit

require('include/application.php');
require('include/api.php');

// check if the user is already logged in
if (auth::revalidate() === false)
	url::redirect("login.php"); // login page

// connect to database
$mysqli = db_connect();

//init variables
$error = "";
$unitName = isset($_POST['unitName']) ? $_POST['unitName'] : "";

// check if the user has submitted their details.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = auth::create_user($mysqli, $unitName);
	if (empty($error))
		url::redirect("createReward.php"); // index page
} // if

// render template
echo twig()->render('createUnit.html', [
	'error' => $error,
	'unitName' => $unitName
]);

// close database
$mysqli->close();

?>