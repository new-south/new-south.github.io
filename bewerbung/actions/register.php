<?php
session_start([
    'cookie_lifetime' => 86400,
]);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';
$USR_username; 
$USR_discrim; 
$USR_id; 
$USR_avatar;
$USR_SERVER = false; 
$USR_guild_active = "false"; 

$redirect_uri = BASE_URL."/actions/register.php";
$DISCORD_LOGIN_URL = "https://discord.com/api/oauth2/authorize?client_id=".OAUTH2_CLIENT_ID."&redirect_uri=".BASE_URL."%2Factions%2Fregister.php&response_type=code&scope=identify%20email%20guilds"; 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET["error"])) {
        echo json_encode(array("message" => "Authorization Error"));
    } elseif (isset($_GET["code"])) {
		
		$data = array(
				"client_id" => OAUTH2_CLIENT_ID,
				"client_secret" => OAUTH2_CLIENT_SECRET,
				"grant_type" => "authorization_code",
				"code" => $_GET["code"],
				"redirect_uri" => $redirect_uri,
				"scope" => "identify guilds"
			);
			
			$token = curl_init();
			curl_setopt($token, CURLOPT_URL, "https://discordapp.com/api/oauth2/token");
			curl_setopt($token, CURLOPT_POST, 1);
			curl_setopt($token, CURLOPT_POSTFIELDS, http_build_query($data));		
			curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
			$resp = json_decode(curl_exec($token));
			curl_close($token);
		
		// GET USER OBJECTS
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discordapp.com/api/users/@me";
			$headers = array("Authorization: Bearer {$access_token}");
			
			$info = curl_init();
			curl_setopt($info, CURLOPT_URL, $info_request);
			curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
			
            $user = json_decode(curl_exec($info));
            curl_close($info);

            $USR_username = $user->username;
			$USR_discrim = $user->discriminator;
			$USR_id = $user->id;
			$USR_avatar = $user->avatar;

        } else {
            echo json_encode(array("message" => "Couldn't get user object!"));
        }
		
		// GET GUILD OBJECTS
		if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discordapp.com/api/users/@me/guilds";
			$headers = array("Authorization: Bearer {$access_token}");
			
			$info = curl_init();
			curl_setopt($info, CURLOPT_URL, $info_request);
			curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($info, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($info, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($info, CURLOPT_VERBOSE, 1);
			curl_setopt($info, CURLOPT_SSL_VERIFYPEER, 0);
			
            $guilds = curl_exec($info);
            curl_close($info);
			
			// Convert JSON string to Array
			$NewArray = json_decode($guilds, true);
			foreach ($NewArray as $key => $value) {
				if($value["id"] == GUILD_ID)
				{
					$USR_SERVER = true;
					
				}else{}
			}
			
			if($USR_SERVER == false)
			{
				 header("Location: ".DISCORD_INVITE);
			}
			else
			{
				setupAndSendOnline();
			}
			
        } else {
            echo json_encode(array("message" => "Couldn't get guilds object!"));
        }
		
    } else {
        header("Location:" . $DISCORD_LOGIN_URL);
    }
	
function setupAndSendOnline(){

	global $USR_id, $USR_username, $USR_avatar, $USR_discrim;
	
	//ASSIGN THE SESSIONS
	$_SESSION['logged_in'] = 'YES';
    $_SESSION['staffid'] = $USR_id;
    $_SESSION['staffavatar'] = $USR_avatar;
    $_SESSION['staffname'] = $USR_username;
    $_SESSION['stafftag'] = $USR_discrim;
    $_SESSION['adminperms'] = checkAdminPermissions($USR_id);

	header("Location: ../index.php");
}
	
?>