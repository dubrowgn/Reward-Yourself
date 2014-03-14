<?php



?>

<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
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
			ul.nav a:hover{
				color: rgb(14, 146, 193) !important;
				background-color: rgb(102, 102, 102);
			}
			.navbar {
				margin-bottom: 0px;
				min-height: 0px;
			}
			.navbar-brand {
				height: 0px;
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
		<h1>HOME</h1>
		</div>
		<nav class="navbar navbar-default" role="navigation" style="background-image: -webkit-linear-gradient(top, rgb(242, 242, 242), rgb(14, 146, 193));">
			<div class"container-fluid">
				<a class="navbar-brand" href="">Icon</a>
						<ul class="nav nav-justified">
							<li>
								<a href="home.php" style="color: rgb(102, 102, 102);">Home</a>
							</li>
							<li>
								<a href="createReward.php" style="color: rgb(102, 102, 102);">Create a Reward</a>
							</li>
							<li>
								<a href="purchase.php" style="color: rgb(102, 102, 102);">Purchase a Reward</a>
							</li>
							<li>
								<a href="history.php" style="color: rgb(102, 102, 102);">View History</a>
							</li>
						</ul>
			</div>
		</nav>
	</body>
</html>
