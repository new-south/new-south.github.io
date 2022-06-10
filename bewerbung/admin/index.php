<?php
require_once __DIR__ . '/../actions/functions.php';
require_once __DIR__ . '/../config.php';
session_start();

if (empty($_SESSION['logged_in']))
{
	header('Location: ../actions/register.php');
}

if ($_SESSION['adminperms'] != 1)
{
	header('Location: ../index.php');
}

if(isset($_GET['success']))
{
  	$actionMessage = '<div class="alert alert-success alert-dismissible fade show" style="text-align: center;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Form Created Successfully!</div>';
}

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);

$discordid = $_SESSION['staffid'];
$avatarid = $_SESSION['staffavatar'];

$format = "jpg";
$gif = "a_";

if(strpos($avatarid, $gif) !== false) {
     $format = "gif";
} else {
     $format = "jpg";
}

$avatar = "https://cdn.discordapp.com/avatars/" . $discordid . "/" . $avatarid . "." . $format;

if (isset($_POST['submit_btn']))
{
	$discordid = $_SESSION['staffid'];
	$form_title = $_POST['form_title'];
	$form_subtext = $_POST['form_subtext'];
	$form_embedcolor = $_POST['form_embedcolor'];
	$form_webhook = $_POST['form_webhook'];
	$form_whitelisted = $_POST['form_whitelisted'];
	$form_fields = $_POST['form_fields'];

	if ($discordid == "") {
		header('Location: ../index.php');
	}
	else {

	$forms = array (
		'title' => $form_title,
		'subtext' => $form_subtext,
		'embedcolor' => $form_embedcolor,
		'webhook' => $form_webhook,
		'whitelisted' => $form_whitelisted,
		'status' => '1', // 1 = Open, 2 = Close
		'fields' => $form_fields
	);

	$forms_str = json_encode($forms, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE);

	$stmt = $pdo->prepare("INSERT INTO forms (discordid, info) VALUES (?, ?)");
	$result = $stmt->execute(array($discordid, $forms_str));

    header('Location: index.php?success');

	}
}

$result2 = $pdo->query("SELECT * FROM forms");
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

	<title><?php echo SERVER_NAME; ?> | Admin</title>

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
				<div class="col-md-1"></div>
				<div class="col-md-6">
					<div style="background-color: white; padding: 50px; border-radius: 10px; position: absolute; z-index: 1; margin-top: 60px; width: 100%;">
						<form action="#" method="post">
							<h3>Create a Form</h3>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="form_title">Form Title *</label>
									<input type="text" class="form-control" placeholder="Eg. Staff Application" id="form_title" name="form_title" required>
								</div>
								<div class="form-group col-md-6">
									<label for="form_subtext">Form Sub Text</label>
									<input type="text" class="form-control" placeholder="Eg. Oi!" id="form_subtext" name="form_subtext">
								</div>
								<div class="form-group col-md-6">
									<label for="form_embedcolor">Embed Color *</label>
									<input type="text" class="form-control" placeholder="Eg. #00B2FF" id="form_embedcolor" name="form_embedcolor">
								</div>
								<div class="form-group col-md-6">
									<label for="form_webhook">Discord Webhook *</label>
									<input type="text" class="form-control" placeholder="" id="form_webhook" name="form_webhook" required>
								</div>
								<div class="form-group col-md-12">
									<label for="form_whitelisted">Whitelisted Roles (Leave Blank for Un-Whitelisted Form)</label>
									<input type="text" class="form-control" placeholder="Enter Discord Role ID's which are whitelisted to fill out this form. Seperate ID's by a ," id="form_whitelisted" name="form_whitelisted">
								</div>
							</div>
							<h4>Fields: <input type="button" class="ml-2 btn btn-outline-info" onclick="moreFields()" value="Add Field" /><br></h4>
							<div class="form-row" id="writeroot">

							</div>
							<div style="text-align: center;">
								<br>
								<button type="submit" name="submit_btn" class="btn btn-lg btn-outline-<?php echo BUTTON_ACCENT_COLOR; ?>">Create</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-1"></div>
				<div class="col-md-3">
					<div style="background-color: white; padding: 50px; border-radius: 10px; position: absolute; z-index: 1; margin-top: 60px; width: 100%;">
							<h3>Manage Forms</h3>
                        <table class="table text-center">
                          <thead>
                            <tr>
                              <th>Title</th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                          	<?php
                             foreach ($result2 as $row) 
                             {
                             	$ID = $row['ID'];
                             	//$forms = unserialize(base64_decode($row['info']));
                             	$forms = json_decode($row['info'], true);

			                      $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			                      $charactersLength = strlen($characters);
			                      for ($i = 0; $i < 10; $i++) {
			                          $modalstr1 .= $characters[rand(0, $charactersLength - 1)];
			                          $modalstr2 .= $characters[rand(0, $charactersLength - 1)];
			                      }
                            ?>
                            <tr>
                                <td><?php echo $forms['title']; ?></td>
                                <td><a type="submit" data-toggle="modal" data-target="#<?php echo $modalstr1; ?>" class="btn btn-outline-info">Edit</a></td>
                                <td><a type="submit" data-toggle="modal" data-target="#<?php echo $modalstr2; ?>" class="btn btn-outline-danger">Delete</a></td>
                                <?php
                                if ($forms['status'] == 1)
                                {
                                ?>
                                	<td><a type="submit" class="btn btn-outline-danger" href="../actions/functions.php?statusUpdate=2&formid=<?php echo $ID; ?>">Close</a></td>
                                <?php
                            	} else {
                                ?>
                                	<td><a type="submit" class="btn btn-outline-success" href="../actions/functions.php?statusUpdate=1&formid=<?php echo $ID; ?>">Open</a></td>
                            	<?php
                            	}
                            	?>
                            </tr>

<!-- Delete Modal -->
<div class="modal fade" id="<?php echo $modalstr2; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalstr2; ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?php echo $modalstr2; ?>">Are you sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <a type="submit" href="../actions/functions.php?deleteForm=<?php echo $ID; ?>" class="btn btn-outline-danger">Delete Form</a>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="<?php echo $modalstr1; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalstr1; ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?php echo $modalstr1; ?>">Update Form #<?php echo $ID; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form action="../actions/functions.php" method="post">
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="form_title">Form Title *</label>
					<input type="text" class="form-control" placeholder="Eg. Staff Application" value="<?php echo $forms['title']; ?>" id="form_title" name="form_title" required>
				</div>
				<div class="form-group col-md-6">
					<label for="form_subtext">Form Sub Text</label>
					<input type="text" class="form-control" placeholder="Eg. Oi!" value="<?php echo $forms['subtext']; ?>" id="form_subtext" name="form_subtext">
				</div>
				<div class="form-group col-md-6">
					<label for="form_embedcolor">Embed Color *</label>
					<input type="text" class="form-control" placeholder="Eg. #00B2FF" value="<?php echo $forms['embedcolor']; ?>" id="form_embedcolor" name="form_embedcolor">
				</div>
				<div class="form-group col-md-6">
					<label for="form_webhook">Discord Webhook *</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo $forms['webhook']; ?>" id="form_webhook" name="form_webhook">
				</div>
				<div class="form-group col-md-12">
					<label for="form_whitelisted">Whitelisted Roles</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo $forms['whitelisted']; ?>" id="form_whitelisted" name="form_whitelisted">
					<input type="hidden" class="form-control" placeholder="" value="<?php echo $ID; ?>" id="form_id" name="form_id">
				</div>
			</div>
			<div style="text-align: center;">
				<hr>
				<p><i>Ability to edit fields will come in the future.</i></p>
				<hr>
				<button type="submit" name="update_form_btn" class="btn btn-outline-<?php echo BUTTON_ACCENT_COLOR; ?>">Update</button>
				<br>
			</div>
		</form>
      </div>
    </div>
  </div>
</div>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
					</div>
				</div>
				<div class="col-md-1"></div>
			</div>
		</section>
	</div>

	<div id="readroot" class="mb-3" style="display: none;">
		<label>Question: *</label>
		<input type="text" class="form-control mb-2" placeholder="Eg. How old are you?" id="[name]" name="[name]" required>

		<label>Type: *</label>
		<input type="text" class="form-control mb-2" placeholder="Eg. 'input' or 'textarea'" id="[type]" name="[type]" required>

		<label>Placeholder: </label>
		<input type="text" class="form-control mb-2" placeholder="" id="[placeholder]" name="[placeholder]">

		<label>Value: </label>
		<input type="text" class="form-control mb-2" placeholder="" id="[value]" name="[value]">

		<label>Tags: </label>
		<input type="text" class="form-control mb-2" placeholder="Eg. 'required' or 'disabled'" id="[tags]" name="[tags]">

		<label>Box Size: *</label>
		<input type="text" class="form-control mb-3" placeholder="Eg. '6' or '12'." id="[size]" name="[size]" required>

		<input type="button" class="btn btn-outline-danger" value="Remove Field" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
		<hr>
	</div>

	<script>
	var counter = 0;

	function moreFields() {
		counter++;
		var newFields = document.getElementById('readroot').cloneNode(true);
		newFields.id = '';
		newFields.style.display = null;
		var newField = newFields.childNodes;
		for (var i=0;i<newField.length;i++) {
			var theName = newField[i].name
			if (theName)
				newField[i].name = "form_fields["+counter+"]"+theName;
				newField[i].id = "form_fields["+counter+"]"+theName;
		}
		var insertHere = document.getElementById('writeroot');
		insertHere.parentNode.insertBefore(newFields,insertHere);
	}

	window.onload = moreFields;
	</script>

	<!-- JS -->
	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/plugins.min.js"></script>
	<script src="../assets/js/functions.js"></script>
</body>
</html>