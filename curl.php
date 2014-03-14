<?php

require ("include/class.fitocracy-client.php");

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!empty($username) && !empty($password)) {
	// create new fitocracy client
	$f = new FitocracyClient($username, $password);

	// attempt to get total xp stat for user
	$r = $f->getTotalXp();
} // if

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Curl Test</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.css">
		<style>
		form { max-width: 450px; }
		</style>
	</head>
	<body>
		<p>
			<form name="input" action="curl.php" method="post">
				<p class="bg-danger"><?php echo isset($r['error']) ? htmlspecialchars($r['error']) : ''; ?></p>
				<p class="bg-info"><?php echo isset($r['xp']) ? 'Total XP: ' . $r['xp'] : ''; ?></p>
				<div class="form-group<?php echo isset($_POST['username']) && empty($username) ? ' has-error' : ''; ?>">
					<input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" maxlength="255" placeholder="Username" />
				</div>
				<div class="form-group<?php echo isset($_POST['password']) && empty($password) ? ' has-error' : ''; ?>">
					<input type="password" name="password" class="form-control" maxlength="255" placeholder="Password" />
				</div>
				<button type="submit" class="btn btn-default">Get XP</button>
			</form>
		</p>
	</body>
</html>
