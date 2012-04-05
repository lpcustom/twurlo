<?php

/**
 * @author lpcustom 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 **/

require_once 'includes/config.php';
require_once 'includes/Database.php';
$db = new Database($config);

@$referrer = $_SERVER['HTTP_REFERER'];

if(!isset($_REQUEST['l'])) {
    die("No redirect link specified.");
} else {
    $short = $_REQUEST['l'];
}

if($short !== false) {
    $link = $db->getLinkByShort($short);
    $db->recordClick($link, $referrer);
    switch ($config['redirect_type']) {
	case "302":
	    header("Location: " . $link['destination']);
	    break;
	case "301":
	    header('HTTP/1.1 301 Moved Permanently');
	    header("Location: " . $link['destination']);
	    break;
    }
    
}
?>
