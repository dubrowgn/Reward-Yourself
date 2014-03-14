<?php

require('include/api.php');

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

// close database
$mysqli->close();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create User</title>
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
			<h1>Create Unit</h1>
			<p class="alert alert-danger"> <?php echo htmlspecialchars($error) ?> </p>
			<p2> Please enter your details below. </p>
			<form role="form" action="createUnit.php" method="post">
				<div class="form-group">
	 			 <label for="unitName">Unite Name</label>
					<input type="text" class="form-control" id="unitName" name="unitName" placeholder="Name" value="<?php echo htmlspecialchars($unitName) ?>" maxlength="255" />
				</div>
				<button type="submit" class="btn btn-info">Submit</button> 
			</form>
		</div>
	</body>
</html>
