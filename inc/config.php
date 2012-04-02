<?php
/**
 * @author lpcustom 
 * Configuration file for twurlo.
 */

/* Database configuration
 * ----------------------
 * We currently support SQLite and MySQL. The default configuration will use
 * SQLite. For MySQL, enter "mysql" as the db_type. If your host doesn't support
 * SQLite with PDO, you should use MySQL.
 * YOU MUST CREATE THE MYSQL DATABASE BEFORE YOU CAN CONNECT TO IT.
 * Below you will find examples of both types of configurations. Comment the one
 * you don't want to use, and remove comments (//) from the one you want to use.
 * 
 */

/* SQLite */
$config['db_type'] = "sqlite";
$config['db_name'] = "twurlo.sqlite";
$config['db_host'] = "";
$config['db_user'] = "";
$config['db_pass'] = "";

/* MySQL */
//$config['db_type'] = "mysql";
//$config['db_name'] = "twurlo";
//$config['db_host'] = "localhost";
//$config['db_user'] = "twurlo";
//$config['db_pass'] = "twurlo";

?>
