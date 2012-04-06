<?php

session_start();
if(!isset($_SESSION['username'])) {
    $results['success'] = false;
    $results['message'] = "You are not logged in.";
    die(json_encode($results));
}

if(!isset($_REQUEST['id'])) {
    $results['success'] = false;
    $results['message'] = "No link id specified.";
    die(json_encode($results));
}
else {
    $id = $_REQUEST['id'];
}

require_once('includes/config.php');
require_once('includes/Database.php');
$db = new Database($config);
if($db->deleteLink($id)) {
    $results['success'] = true;
    $results['message'] = "Link deleted";
    die(json_encode($results));
}
else {
    $results['success'] = false;
    $results['message'] = "Unable to delete the link.";
    die(json_encode($results));
}
    
    
?>
