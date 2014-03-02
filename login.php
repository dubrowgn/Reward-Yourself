<?php

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
		url::redirect(""); // index page
} // if

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Reward Yourself Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen">
		<style>
			.container {
				max-width: 360px;
			}
			h1 {
				text-align: center;
			}

	</style> 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="../../assets/js/html5shiv.js"></script>
			<script src="../../assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<h1>Reward Yourself</h1>
			<form role="form" action="login.php" method="post">
				<div class="form-group">
	 			 <label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username) ?>" maxlength="255" />
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" maxlength="255" ></input>
				</div>
				<button type="submit" class="btn btn-default">Submit</button> <a href="createUser.html">Create an Account</a>
			</form>
		</div>
	</body>
</html>
