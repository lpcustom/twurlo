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

$link_count = $db->getLinkCount($search);
if($link_count > 20) {
    if($link_count % 20) {
        $pages = floor(($link_count / 20)) + 1;
    } else {
        $pages = floor($link_count / 20);
    }
} else {
    $pages = 1;
}
$pagination = "";
if($pages > 1) {
    if($page > 1) {
        $pagination .= "<a href=\"" . $config['baseurl'] . "/manage.php?p=1\">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        $pagination .= "<a href=\"" . $config['baseurl'] . "/manage.php?p=" . ($page - 1) . "\">&lt;</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    }

    $pagination .= "Page " . $page . " of " . $pages . "&nbsp;&nbsp;&nbsp;&nbsp;";
    if($pages > $page) {
        $pagination .= "<a href=\"" . $config['baseurl'] . "/manage.php?p=" . ($page + 1) . "\">&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        $pagination .= "<a href=\"" . $config['baseurl'] . "/manage.php?p=" . $pages . "\">&gt;&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    }
}

// Get links for the the link table;
$links = $db->getLinks($page, $search);
?>
<!doctype html>
<html>
<head>
    <title>Twurlo Management Console</title>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="css/twurlo.css"/>
    <script type="text/javascript" src="includes/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if(typeof($("#url") !== undefined)) {
                $("#url").focus();
            }
        });

        function deleteLink(id) {
            if(confirm("Are you sure you want to delete this link?")) {
                $.ajax({
                    url: "delete.php?id=" + id,
                    dataType: "json",
                    success: function(data) {
                        if(data.success == true) {
                            $("#link_" + id).remove();
                        }
                        else {
                            alert(data.message);
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div id="body_wrapper">
        <div id="heading_wrapper">
            <h2 class="center">Welcome to your Twurlo Management Console - <a style="color: blue; text-decoration: none;" href="logout.php">logout</a></h2>
        </div>
        <div id="content_wrapper">
            <?php if(isset($_SESSION['message'])): ?>
                <div id="message"><?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            <form action="add.php" method="post">
                <table id="add_link">
                    <tr>
                        <td>add a link</td>
                        <td colspan="3">
                            <input type="text" name="url" id="url" style="width: 400px;" value="<?php echo (isset($add_link) && $add_link != "") ? $add_link : ""; ?>">
                            (include http:// or https://)
                        </td>
                    </tr>
                    <tr>
                        <td>custom short name</td>
                        <td><input type="text" name="short" id="short" style="width: 400px;"/></td>
                        <td>(leave blank to generate automatically)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>og:title</td>
                        <td><input type="text" name="title" id="title" style="width: 400px;"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>og:description</td>
                        <td><input type="text" name="description" id="description" style="width: 400px;" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>og:image</td>
                        <td><input type="text" name="image" id="image" style="width: 400px;"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>og:site_name</td>
                        <td><input type="text" name="site_name" id="site_name" style="width: 400px;"/></td>
                        <td><input class="pad5" style="padding: 5px 30px;" type="submit" value="add"/></td>
                    </tr>
                </table>
            </form>
            <?php if(isset($links) && $links !== false && count($links) > 0): ?>
                <div id="link_list_wrapper">
                    <h2>Links</h2>
                    <table id="link_list">
                        <tr>
                            <td colspan="5" class="center pagination"><?php echo $pagination; ?></td>
                        </tr>
                        <tr>
                            <td width="120px" class="bold border_right">created</td>
                            <td class="bold border_right">destination</td>
                            <td class="bold border_right center">short name</td>
                            <td class="bold border_right center">clicks</td>
                            <td class="bold center">options</td>
                        </tr>
                        <?php foreach($links as $link): ?>
                            <tr id="link_<?php echo $link['id']; ?>">
                                <td class="border_right"><?php echo $link['timestamp']; ?></td>
                                <td class="border_right">
                                    <a href="<?php echo $link['destination']; ?>"><?php echo $link['destination']; ?></a>
                                </td>
                                <td class="border_right center">
                                    <a target="_blank" href="<?php echo $config['baseurl'] . "/?l=" . $link['shortname']; ?>"><?php echo $link['shortname']; ?></a>
                                </td>
                                <td class="border_right center"><?php echo $db->getClickCount($link['id']); ?></td>
                                <td class="center">
                                    <span onclick="window.prompt('Copy to clipboard: Ctrl+c, Enter', '<?php echo $config['baseurl'] . "/?l=" . $link['shortname']; ?>')">
                                        <img src="images/clipboard.gif" alt="clipboard"/>
                                    </span>&nbsp;&nbsp;
                                    <span onclick="deleteLink(<?php echo $link['id'] ?>);">
                                        <img src="images/x.gif" alt="delete"/>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" class="center pagination"><?php echo $pagination; ?></td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div id="footer_wrapper">
            <a target="_blank" href="javascript:void(location.href='<?php echo $config['baseurl']; ?>/manage.php?a='+encodeURIComponent(location.href))">twurlo it!</a> &lt;--drag to bookmarks bar
        </div>
    </div>
</body>
</html>
