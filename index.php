<?php

/**
 * @author Randy Yates
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
//    switch($link['type']) {
//        case "302":
//            header("Location: " . $link['destination']);
//            break;
//        case "301":
//            header('HTTP/1.1 301 Moved Permanently');
//            header("Location: " . $link['destination']);
//            break;
//    }

}
?>

<!doctype html>
<html>
<head>
    <title><?php echo $link['title'];?></title>
    <meta property="og:title" content="<?php echo $link['title'];?>" />
    <meta property="og:site_name" content="<?php echo $link['site_name'];?>" />
    <meta property="og:image" content="<?php echo $link['image'];?>" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="<?php echo $link['description'];?>" />
</head>
    <body onload="window.location = '<?php echo $link['destination'];?>'">Redirecting...</body>
</html>
