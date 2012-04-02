<?php
session_start();

require_once("includes/config.php");
require_once("includes/Database.php");

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
}

if(isset($_REQUEST['url']) && $_REQUEST['url']!="") { 
    $url = $_REQUEST['url']; 
} 
else { 
    die("Destination URL is required."); 
}

if(isset($_REQUEST['short']) && $_REQUEST['short']!="") { 
    $short = $_REQUEST['short']; 
} 
else {
    $short = $db->newShort();
}

?>
