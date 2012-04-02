<?php

/**
 * @author lpcustom 
 * Configuration file for twurlo.
 */

/************************Database configuration*********************************
 * We currently support MySQL. We plan to support PostgreSQL in a later release.
 * YOU MUST CREATE THE MYSQL DATABASE BEFORE YOU CAN CONNECT TO IT.
 *******************************************************************************/
$config['db_type'] = "mysql";	    // current only supports mysql
$config['db_name'] = "twurlo";	    // name of the database you created
$config['db_host'] = "localhost";   // hostname of db server, usually localhost
$config['db_user'] = "twurlo";	    // name of the database user you created
$config['db_pass'] = "twurlo";	    // password for the database user
/*******************************************************************************/

/************************Admin user infomration*********************************
 * CHANGE THIS!!!
 * THIS IS YOUR LOGIN INFORMATION TO MANAGE THE SITE
 *******************************************************************************/
$config['username'] = "admin";
$config['password'] = "admin";
/*******************************************************************************/
?>