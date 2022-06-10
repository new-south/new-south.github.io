<?php
require_once __DIR__ . '/../actions/functions.php';
require_once __DIR__ . '/../config.php';
session_start();
$formID = $_GET["ID"];

if (empty($_SESSION['logged_in']))
{
	header('Location: ../actions/register.php');
}

if(isset($_GET['success']))
{
  	$actionMessage = '<div class="alert alert-success alert-dismissible fade show" style="text-align: center;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Form Submitted Successfully!</div>';
}

try{
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
  echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$csrf = sha1(session_id());

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

if (is_numeric($formID))
{
	$result = $pdo->query("SELECT * FROM forms WHERE ID='$formID'");
}

foreach ($result as $row)
{
	//$forms = unserialize(base64_decode($row['info']));
	$forms = json_decode($row['info'], true);
}

if (empty($forms))
{
	header('Location: ../index.php');
}

if ($forms['status'] == 2)
{
	header('Location: ../index.php');
}

if (!empty($forms['whitelisted']))
{
	if (checkWhitelist($discordid, $forms['whitelisted']) != 1)
	{
		header('Location: ../index.php');
	}
}


if (isset($_POST['submit_btn']))
{

	$discordid = $_SESSION['staffid'];
	$discordtag = "<@".$discordid."> | " . $name ."#". $tag;

	if ($_POST['_csrf'] != sha1(session_id())) {
	    header('Location: ../index.php?errorForm');
	}
	elseif ($discordid == "") {
		header('Location: ../actions/register.php');
	} 
	else {

    $timestamp = date("c", strtotime("now"));

	$embed = array (
	    "username" => $forms['title'],

	    "tts" => false,

	    "embeds" => [
	        [
	            "title" => "Form Title",

	            "type" => "rich",

	            "timestamp" => $timestamp,

	            "color" => hexdec($forms['embedcolor']),

	            "fields" => [
	                [
	                    "name" => 'Discord User',
	                    "value" => $discordtag,
	                    "inline" => false
	                ],
	            ]
	        ]
	    ]
	);

	$forms_keys = $forms['fields'];
	foreach ($forms_keys as $forms_key) 
	{
		$id = str_replace(' ', '', $forms_key['name']);
		$id = preg_replace('/[0-9]+/', '', $id);
		$id = str_replace(['.','[',']','(',')','.','?'],'',$id);

		$value = htmlspecialchars($_POST[$id]);
		if (empty($value))
		{
			$value = "Empty";
		}

		echo $totalvaluelength = $valuelength + strlen($value);
		echo $valuelength = strlen($value);

		if ($valuelength > 1024)
		{
			$embed2 = array (
			    "username" => "Form Username",

			    "content" => "**".$forms_key['name']."** | By ".$discordtag."\n```".$value."```",
			);
		} else {

			$fields = [
		        "name" => $forms_key['name'],
		        "value" => $value,
		        "inline" => false
			];

			array_push($embed['embeds'][0]['fields'], $fields);
		}
	}

    $json_data = json_encode($embed, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    $ch = curl_init($forms['webhook']);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'User-Agent: Hamz Forms'));
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $response = curl_exec( $ch );
    curl_close( $ch );

    $json_data2 = json_encode($embed2, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    $ch2 = curl_init($forms['webhook']);
    curl_setopt( $ch2, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'User-Agent: Hamz Forms'));
    curl_setopt( $ch2, CURLOPT_POST, 1);
    curl_setopt( $ch2, CURLOPT_POSTFIELDS, $json_data2);
    curl_setopt( $ch2, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch2, CURLOPT_HEADER, 0);
    curl_setopt( $ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch2, CURLOPT_VERBOSE, 1);
    $response2 = curl_exec( $ch2 );
    curl_close( $ch2 );

    header('Location: index.php?ID='.$formID.'&success');

	}
    
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- CSS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Roboto:300,400,500,900&display=swap" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../assets/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/style.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/dark.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/font-icons.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/animate.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/magnific-popup.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/landing.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/fonts.css" type="text/css" />
	<link rel="stylesheet" href="../assets/css/custom.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--OPEN GRAPH FOR DISCORD RICH PRESENCE-->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo SERVER_NAME; ?>" />
    <meta property="og:description" content="Forms System by Hamz#0001">

    <link rel="shortcut icon" href="<?php echo LOGO; ?>" type="image/x-icon">

	<title><?php echo SERVER_NAME ." | ". $forms['title']; ?></title>

	<style>
		body {
		  --scrollbarBG: <?php echo SCROLLBAR_BG_COLOR; ?>;
		  --thumbBG: <?php echo ACCENT_COLOR; ?>;
		  overflow-x: hidden;
		}
	</style>
</head>

<body class="stretched" style="background-image: url('<?php echo BACKGROUND_IMAGE; ?>'); background-size: cover; height: 100%;">
	<div id="wrapper" class="clearfix" >
		<!-- HEADER -->
		<header id="header" class="border-bottom-0 no-sticky dark transparent-header" data-responsive-class="not-dark">
			<div id="header-wrap" style="background: <?php echo ACCENT_COLOR; ?>">
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

							<ul class="mr-0 border-0 menu-container" style="color: white !important;">
								<li class="menu-item"><a class="menu-link" href="../index.php"><div>Back</div></a></li>
							</ul>					
						</nav>

					</div>
				</div>
			</div>
		</header>

		<section id="content">
			<?php if($actionMessage){echo $actionMessage;} ?>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div style="background-color: white; padding: 50px; border-radius: 10px; position: absolute; z-index: 1; margin-top: 60px; width: 100%;">
						<form action="#" method="post">
							<h3><?php echo $forms['title']; ?></h3>
							<p><i><?php echo $forms['subtext']; ?></i></p>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Discord Name</label>
									<input type="text" class="form-control" placeholder="<?php echo $discordname; ?>" disabled>
								</div>
								<div class="form-group col-md-6">
									<label>Discord ID</label>
									<input type="text" class="form-control" placeholder="<?php echo $discordid; ?>" disabled>
								</div>
								<?php
								$forms_keys = $forms['fields'];
								foreach ($forms_keys as $forms_key) 
								{
									$id = str_replace(' ', '', $forms_key['name']);
									$id = preg_replace('/[0-9]+/', '', $id);
									$id = str_replace(['.','[',']','(',')','.','?'],'',$id);
								?>
								<div class="form-group col-md-<?php echo $forms_key['size']; ?>">
									<label for="<?php echo $id; ?>"><?php echo $forms_key['name']; if ($forms_key['tags'] == "required") { echo "*"; }?></label>
									<<?php echo $forms_key['type']; ?> type="text" class="form-control" name="<?php echo $id; ?>" id="<?php echo $id; ?>" placeholder="<?php echo $forms_key['placeholder']; ?>" value="<?php echo $forms_key['value']; ?>" <?php echo $forms_key['tags']; ?>></<?php echo $forms_key['type']; ?>>
								</div>
								<?php
								}
								?>
							</div>
							<div style="text-align: center;">
								<br>
								<button type="submit" name="submit_btn" class="btn btn-lg btn-outline-<?php echo BUTTON_ACCENT_COLOR; ?>">Submit</button>
							</div>
							<input type="hidden" name="_csrf" value="<?php echo $csrf ?>"/>
						</form>
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