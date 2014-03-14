<?php
//creatReward.php

require('include/api.php');

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

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create Reward</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<!-- Bootstrap -->
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" media="screen"/>
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
			<h1>Create Reward</h1>
			<div class="alert alert-danger"> <?php echo htmlspecialchars($error) ?> </div>
			<p> Please enter the details for your reward below. </p>
			<form role="form" action="createReward.php" method="post">
				<div class="form-group">
	 				<label for="rewardName">Reward Name</label>
					<input type="text" class="form-control" id="rewardName" name="rewardName" placeholder="Reward Name" value="<?php echo htmlspecialchars($rewardName) ?>" maxlength="255" />
				</div>
				<div class="form-group">
					<label for="unitPrice">Unit Price</label>
					<input type="number" class="form-control" id="unitPrice" name="unitPrice" placeholder="Unit Price" min="0" />
				</div>
				<div class="form-group">
					<label for="unitID">Unit</label>
					<select class="form-control" id="unitID" name="unitID" >
						<option value="" disabled selected > -- select unit -- </option>
						<?php 
							$units = unit::get_units($mysqli);
							foreach($units as $unitID => $name) : ?>
						    	<option value="<?php echo $unitID; ?>"><?php echo $name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>					
				<button type="submit" class="btn btn-info">Submit</button> 
				<a href="createUnit.php">Create an Unit</a>
			</form>
		</div>
	</body>
</html>

<?php

// close database
$mysqli->close();

?>