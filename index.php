<?php
/**
 * @author lpcustom 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 **/

require_once 'includes/config.php';
require_once 'includes/Database.php';
$db = new Database($config);
if(!$db->checkInitialized()) {
    die("Database isn't initialized. You need to setup your database in manage.php.");
}


?>
