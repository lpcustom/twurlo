<?php
/**
 * Manage links and settings of your Twurlo installation 
 */
require_once 'includes/config.php';
require_once 'includes/Database.php';
$installed = false;
$db = new Database($config);
if(!$db->checkInitialized()) {
    if(!$db->initDB()) {
        die("Couldn't initialize database. Verify that your configuration is correct.");
    }
}
else {
    $installed = true;
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
        </div>
    </body>        
</html>
