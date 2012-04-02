<?php
/* Manage links and settings of your Twurlo installation */

// Create session
session_start();

// include configuration file and database class
require_once 'includes/config.php';
require_once 'includes/Database.php';

// create database object
$db = new Database($config);

// if the database tables have not been created
if(!$db->checkInitialized()) {
    // try to create them and die if that fails
    if(!$db->initDB()) {
	die("Unable to initialize database. Check your configuration.");
    }
}

// "a" POST or GET parameter is used for adding a link; check if it's present
// we check this before checking for a valid user because we'll be passing this
// to the login form if there's no user logged in, so we can resume adding the
// link after login
if(isset($_REQUEST['a'])) {
    $add_link = $_REQUEST['a'];
}

// Admin logged in?
if(!isset($_SESSION['username'])) {
    if(isset($add_link)) {
	header("Location: login.php?a=" . $add_link);
    } else {
	header("Location: login.php");
    }
}

// Do we have a sort order on the link list table?
if(isset($_REQUEST['s'])) {
    $sort = $_REQUEST['s'];
} else {
    $sort = "timestamp";
}

// is there a sort order direction set?
if(isset($_REQUEST['d'])) {
    $direction = $_REQUEST['d'];
} else {
    $direction = "DESC";
}

// Do we have a page number request for pagination?
if(isset($_REQUEST['p'])) {
    $page = $_REQUEST['p'];
} else {
    $page = "1";
}

// Is there a search query?
if(isset($_REQUEST['q'])) {
    $search = $_REQUEST['q'];
} else {
    $search = "";
}

// Get links for the the link table;
$links = $db->getLinks($sort, $direction, $page, $search);
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
		<h2 class="center">Welcome to your Twurlo Management Console - <a style="color: blue; text-decoration: none;"href="logout.php">logout</a></h2>
            </div>
            <div id="content_wrapper">
		<form action="add.php" method="post">
		    <table id="add_link">
			<tr>
			    <td>add a link</td>
			    <td colspan="3"><input type="text" name="url" id="url" size="80" value="<?php echo (isset($add_link) && $add_link != "") ? $add_link : ""; ?>"></td>
			</tr>
			<tr>
			    <td>custom short name</td>
			    <td><input type="text" name="short" id="short" /></td>
			    <td>(leave blank to generate automatically)</td>
			    <td><input class="pad5" style="padding: 5px 30px;" type="submit" value="add" /></td>
			</tr>
		    </table>
		</form>
		<?php if(isset($links) && $links !== false && count($links) > 0): ?>
    		<table id="link_list">
    		    <tr>
    			<td class="center">timestamp</td>
    			<td>destination</td>
    			<td>short name</td>
    		    </tr>
		    <?php foreach($links as $link): ?>
		    <tr>
			<td><?php echo $link['timestamp']; ?></td>
			<td><?php echo $link['destination']; ?></td>
			<td><?php echo $config['baseurl'] . $link['shortname']; ?></td>
		    </tr>
		    <?php endforeach; ?>
    		</table>
		<?php endif; ?>
            </div>
            <div id="footer_wrapper">

            </div>
        </div>
    </body>        
</html>
