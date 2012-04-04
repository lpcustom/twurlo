<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/Database.php';
$db = new Database($config);

if(isset($_REQUEST['a'])) {
    $add_link = $_REQUEST['a'];
}

if(isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    if(($_REQUEST['username'] == $config['username']) &&  ($_REQUEST['password'] == $config['password'])) {
	$_SESSION['username'] = $_REQUEST['username'];
	if(isset($add_link)) {
	    header("Location: manage.php?a=" . $add_link);
	}
	else {
	    header("Location: manage.php");
	}
    } 
    else {
	$error_message = "username/password combination incorrect";
    }
}
?>
<html>
    <head>
        <title>Login to Twurlo</title>
	<link rel="stylesheet" href="css/reset.css" />
	<link rel="stylesheet" href="css/twurlo.css" />
	<script type="text/javascript" src="includes/jquery.js"></script>
	<script type="text/javascript">
	    $(document).ready(function(){
		$("#username").focus();
	    });
	</script>
	    
    </head>

    <body>
	<div id="login_wrapper">
	    <form action="" method="post">
		<?php if(isset($add_link) && $add_link != ""):?>
		<input type="hidden" name="a" id="a" value="<?php echo $add_link; ?>" />
		<?php endif;?>
		<table id="login">
		    <tr>
			<td colspan="2" class="bold center">login to twurlo</td>
		    </tr>
		    <tr>
			<td>username</td>
			<td><input type="text" name="username" id="username" /></td>
		    </tr>
		    <tr>
			<td>password</td>
			<td><input type="password" name="password" id="password" /></td>
		    </tr>
		    <tr>
			<td colspan="2" class="center">
			    <input style="padding: 5px 30px; " type="submit" value="login" />
			</td>
		    </tr>
		    <tr>
			<td colspan="2" class="center"><?php if(isset($error_message)) { echo($error_message);} ?></td>
		    </tr>
		</table>
	    </form>
	</div>
    </body>
</html>
