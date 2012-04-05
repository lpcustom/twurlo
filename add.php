<?php
// start session so we can work with session
session_start();

// include configuration file and database class
require_once("includes/config.php");
require_once("includes/Database.php");

// create our object to work with the database
$db = new Database($config);

// check to make sure that admin is logged in; if not, redirect to login
// no need to send link to login as referral; add.php should only be called
// from manage.php, never directly.
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    die("redirected to login");
}

// get our destination url from form
if(isset($_REQUEST['url']) && trim($_REQUEST['url']) != "") { 
    $url = $_REQUEST['url']; 
} 
else { 
    $_SESSION['message'] = "URL required in 'add a link' field.";
    header("Location: manage.php");
    die("this shouldn't be happening");
}

// get our short name from form if provided
if(isset($_REQUEST['short']) && $_REQUEST['short']!="") { 
    $short = $_REQUEST['short']; 
} 
else {
    // if not provided, create a new short name automagically
    $short = $db->newShort();
}

if(!$db->shortnameAvailable($short)) {
    // flash error message
    $_SESSION['message'] = "Short Name already exists";
    header("Location: manage.php?a=" . $url);
}

if($db->addLink($url, $short)) {
    // using "message" in the session to communicate our messages
    $_SESSION["message"] = "Link added";
    // redirect to manage.php
    header("Location: manage.php");
}

?>
