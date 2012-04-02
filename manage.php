<?php
/**
 * Manage links and settings of your Twurlo installation 
 */
session_start();
require_once 'includes/config.php';
require_once 'includes/Database.php';
$installed = false;
$db = new Database($config);
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>
<!doctype html>
<html>
    <head>
        <title>
            Twurlo Management Console
        </title>
        <link rel="stylesheet" href="css/reset.css" />
        <link rel="stylesheet" href="css/twurlo.css" />
    </head>
    <body>
        <div id="body_wrapper">
            <div id="heading_wrapper">

            </div>
            <div id="content_wrapper">

            </div>
            <div id="footer_wrapper">

            </div>
        </div>
    </body>        
</html>
