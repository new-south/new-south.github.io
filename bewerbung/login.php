<?php
require_once(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- CSS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Roboto:300,400,500,900&display=swap" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/dark.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/font-icons.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/animate.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/magnific-popup.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/landing.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/fonts.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/custom.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--OPEN GRAPH FOR DISCORD RICH PRESENCE-->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo SERVER_NAME; ?>" />
    <meta property="og:description" content="Forms System by Hamz#0001">

    <link rel="shortcut icon" href="<?php echo LOGO; ?>" type="image/x-icon">

	<title><?php echo SERVER_NAME; ?> Forms | Login</title>

</head>

<body class="stretched">
</div>

	<div id="wrapper" class="clearfix">
		<!-- LOGIN -->
		<section id="slider" class="slider-element dark min-vh-100 include-header" style="background-image: url('<?php echo BACKGROUND_IMAGE; ?>');"> 
			<div class="slider-inner flex-column">
				<div class="vertical-middle">
					<div class="row">
						<div class="col-md-4"></div>
							<div class="col-md-4">
								<div class="card text-center">
								  <div class="card-header">
								    <b><?php echo SERVER_NAME; ?> Application Portal</b>
								  </div>
								  <div class="card-body">
								    <p class="card-text">Login Using Discord</p>
								    <a href="actions/register.php" class="btn btn-outline-light">Login</a>
								  </div>
								  <div class="card-body">
								    Made with ❤ by <a style="color: inherit;" href="https://discord.gg/3DDWp6w">Hamz</a>
								  </div>
								</div>
							</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- JS -->
	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/plugins.min.js"></script>
	<script src="../assets/js/functions.js"></script>
</body>
</html>
