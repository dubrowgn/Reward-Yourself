<?php
//creatReward

require('include/application.php');
require('include/api.php');

// check if the user is already logged in
if (auth::revalidate() === false)
	url::redirect("login.php"); // login page

// connect to database
$mysqli = db_connect();

//init variables
$error = "";
$rewardName = isset($_POST['rewardName']) ? $_POST['rewardName'] : "";
$unitPrice = isset($_POST['unitPrice']) ? $_POST['unitPrice'] : "";
$unitID = isset($_POST['unitID']) ? $_POST['unitID'] : "";

// check if the user has submitted their details.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = reward::create_reward($mysqli, $rewardName, $unitPrice, $unitID);
	if (empty($error))
		url::redirect("home.php"); // index page
} // if

//create twig object
$twig = twig();

//get units from DB
$units = unit::get_units($mysqli);


$body = $twig->render('createReward.html', [
	'error' => $error,
	'rewardName' => $rewardName,
	'units' => $units
]);

echo $twig->render('page.html', [
	'title' => 'Create Reward',
	'body' => $body
]);


?>