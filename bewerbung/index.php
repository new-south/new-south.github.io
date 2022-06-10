<?php
session_start();
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/actions/functions.php");

if (empty($_SESSION['logged_in']))
{
	header('Location: login.php');
}

try{
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
  echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$discordid = $_SESSION['staffid'];
$name = $_SESSION['staffname'];
$tag = $_SESSION['stafftag'];
$avatarid = $_SESSION['staffavatar'];

$discordname = $name . "#" . $tag . "";
$format = "jpg";
$gif = "a_";

if(strpos($avatarid, $gif) !== false) {
     $format = "gif";
} else {
     $format = "jpg";
}

$avatar = "https://cdn.discordapp.com/avatars/" . $discordid . "/" . $avatarid . "." . $format;

if(isset($_GET['errorForm']))
{
  	$actionMessage = '<div class="alert alert-danger alert-dismissible fade show" style="text-align: center;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> AN ERROR HAS OCCURED, TRY AGAIN!</div>';
}

$result = $pdo->query("SELECT * FROM forms");
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

	<title><?php echo SERVER_NAME; ?> Form | Home</title>

	<style>
		body {
		  --scrollbarBG: <?php echo SCROLLBAR_BG_COLOR; ?>;
		  --thumbBG: <?php echo ACCENT_COLOR; ?>;
		  overflow-x: hidden;
		}
	</style>
</head>

<body class="stretched">
	<div id="wrapper" class="clearfix">
		<!-- HEADER -->
		<header id="header" class="border-bottom-0 no-sticky dark transparent-header" data-responsive-class="not-dark">
						<?php if($actionMessage){echo $actionMessage;} ?>
			<div id="header-wrap">
				<div class="container">
					<div class="header-row">

						<!-- SERVER NAME -->
						<div id="logo">
							<span style="font-family: 'Roboto', sans-serif !important; font-size: 1.5em;"><?php echo SERVER_NAME; ?></span>
						</div>
						<div id="logo">
							<img src="<?php echo $avatar; ?>" style="border-radius: 50%; height: 55px;">
						</div>

						<div id="primary-menu-trigger">
							<svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
						</div>

						<nav class="primary-menu not-dark with-arrows">
							<?php
							if ($_SESSION['adminperms'] == 1)
							{
							?>
							<ul class="mr-0 border-0 menu-container" style="color: white !important;">
								<li class="menu-item"><a class="menu-link" href="admin/index.php"><div>Admin</div></a></li>
							</ul>	
							<?php
							}
							?>
							<ul class="mr-0 border-0 menu-container" style="color: white !important;">
								<li class="menu-item"><a class="menu-link" href="actions/logout.php"><div>Logout</div></a></li>
							</ul>					
						</nav>

					</div>
				</div>
			</div>
		</header>

		<!-- APPS LIST -->
		<section id="slider" class="slider-element dark min-vh-100 include-header" style="background-image: url('<?php echo BACKGROUND_IMAGE; ?>');">  
			<div class="slider-inner flex-column">
					<div class="container" style="text-align: center; margin-top: 22em;">
                        <?php
                          $length = count($result);
                          $count = 1;
                          foreach ($result as $row) 
                          {
                          	$ID = $row['ID'];
                          	$forms = json_decode($row['info'], true);

							if (!empty($forms['whitelisted']))
							{
								if (checkWhitelist($discordid, $forms['whitelisted']) == 1)
								{
									$whitelisted = true;
								}
							} else {
									$whitelisted = true;
							}

                          	if ($forms['status'] == 1)
							{
								if ($whitelisted == true)
								{
		                          	if ($count == $length)
		                          	{
		                        ?>
									<a href="form/index.php?ID=<?php echo $ID; ?>" style="margin-top: 60px;" class="button button-border button-circle button-light button-white"><?php echo $forms['title']; ?></a>
		                        <?php
		                    		} else {
		                    	?>
									<a href="form/index.php?ID=<?php echo $ID; ?>" style="margin-top: 60px;" class="button button-border button-circle button-light button-white"><?php echo $forms['title']; ?></a> | 
		                    	<?php
		                    		}
                    			}
                    		}
                          }
                        ?>
					</div>
				</div>
			</div>
		</section>

	</div>
</body>
</html>
