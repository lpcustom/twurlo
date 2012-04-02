<?php
/**
 * @author lpcustom 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 **/

require_once 'inc/config.php';
require_once 'inc/Database.php';
$db = new Database($config);
if(!$db->checkInitialized()) {
    echo "Database isn't initialized. You need to setup your database in manage.php.";
}

?>
