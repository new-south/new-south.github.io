<?php
session_start();
require_once __DIR__ . '/../config.php';

/**
 *
 * @param
 * @return
 * 
 */
function getGuildMember($id) {
    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/guilds/' . GUILD_ID . '/members/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot (' . BASE_URL . ', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $guildMember = json_decode(curl_exec($ch));

    curl_close($ch);

    return $guildMember;
}

/**
 *
 * @param
 * @return
 *
 */
function getGuildMemberRoles($id) {
    $guildMember = getGuildMember($id);
    return $guildMember->roles;
}

/**
 * Get permissions
 *
 *
 * @param string $id A user's Discord ID
 * @return int permission level
 */
function checkAdminPermissions($id) {
    global $ADMINROLES;
    $roles = getGuildMemberRoles($id);

    foreach ($ADMINROLES as $roleid) {
        if (in_array($roleid, $roles)) {
            return 1;
        }
    }

}

function checkWhitelist($id, $whitelistroles) {
    $roles = getGuildMemberRoles($id);
    $whitelistroles_array = explode(',', $whitelistroles);

    foreach ($whitelistroles_array as $roleid) {
        if (in_array($roleid, $roles)) {
            return 1;
        }
    }
}


if (isset($_GET['deleteForm']))
{
    deleteForm();
}
function deleteForm()
{
    $discordid = $_SESSION['staffid'];
    $deleteFormID = htmlspecialchars($_GET['deleteForm']);

    try{
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch(PDOException $ex)
    {
        echo "Could not connect -> ".$ex->getMessage();
        die();
    }

    if ($_SESSION['adminperms'] == 1) {
        
        $result = $pdo->query("DELETE FROM forms WHERE ID='$deleteFormID'");

        header('Location: ../admin/index.php');
    }
    else {
        header('Location: ../index.php');
    }
}


if (isset($_POST['update_form_btn']))
{
    updateForm();
}
function updateForm()
{
    $discordid = $_SESSION['staffid'];
    $form_title = htmlspecialchars($_POST['form_title']);
    $form_subtext = htmlspecialchars($_POST['form_subtext']);
    $form_embedcolor = htmlspecialchars($_POST['form_embedcolor']);
    $form_webhook = htmlspecialchars($_POST['form_webhook']);
    $form_whitelisted = htmlspecialchars($_POST['form_whitelisted']);
    $form_id = htmlspecialchars($_POST['form_id']);

    if ($_SESSION['adminperms'] == 1)
    {
       try{
           $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        } catch(PDOException $ex)
        {
            echo "Could not connect -> ".$ex->getMessage();
            die();
        }

        $result = $pdo->query("SELECT * FROM forms WHERE ID='$form_id'");
        foreach ($result as $row)
        {
           $forms = json_decode($row['info'], true);
        }

        $forms['title'] = $form_title;
        $forms['subtext'] = $form_subtext;
        $forms['embedcolor'] = $form_embedcolor;
        $forms['webhook'] = $form_webhook;
        $forms['whitelisted'] = $form_whitelisted;


        $forms = json_encode($forms, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE);

        $result2 = $pdo->query("UPDATE forms SET info='$forms' WHERE ID='$form_id'");

         header('Location: ../admin/index.php');
    }
    else 
    {
         header('Location: ../index.php');
    }
}


if (isset($_GET['statusUpdate']))
{
    updateStatus();
}
function updateStatus()
{
    $discordid = $_SESSION['staffid'];
    $statusUpdateValue = htmlspecialchars($_GET['statusUpdate']);
    $formid = htmlspecialchars($_GET['formid']);

    try{
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch(PDOException $ex)
    {
        echo "Could not connect -> ".$ex->getMessage();
        die();
    }

    if ($_SESSION['adminperms'] == 1) {
        
        $result = $pdo->query("SELECT * FROM forms WHERE ID='$formid'");
        foreach ($result as $row)
        {
           $forms = json_decode($row['info'], true);
        }

        $forms['status'] = $statusUpdateValue;

        echo $forms = json_encode($forms, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE);

        $result2 = $pdo->query("UPDATE forms SET info='$forms' WHERE ID='$formid'");

        header('Location: ../admin/index.php');
    }
    else {
        header('Location: ../index.php');
    }
}
?>