<?php

/**
 * Class for interacting with the database.
 * @author lpcustom
 * 
 */
class Database {

    private $type;
    private $user;
    private $host;
    private $pass;
    private $name;
    private $db;

    /**
     * Constructor for Database class; Establishes a PDO connection with the 
     * configured database.
     * @param Array $config 
     */
    public function Database($config) {
	$this->type = $config['db_type'];
	switch($this->type) {
	    case "mysql":
		$this->user = $config['db_user'];
		$this->host = $config['db_host'];
		$this->pass = $config['db_pass'];
		$this->name = $config['db_name'];
		$this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->name, $this->user, $this->pass);
		break;
	}
    }

    public function newShort() {
	try {
	    $q = "SELECT MAX(`id`) as `maxID` FROM `links`;";
	    $query = $this->db->prepare($q);
	    $results = $query->fetchAll();
	    if($results != false && count($results) > 0) {
		return $results[0]['maxID'];
	    }
	    else { 
		return false;
	    }
	}
	catch(PDOException $ex) {
	    error_log($ex);
	    return false;
	}
    }
    
    public function addLink($destination, $shortname) {
	$now = time();
	$q = "INSERT INTO `links`(`shortname`, `destination`, `timestamp`) VALUES(:shortname, :destination, :timestamp);";
	$query = $this->db->prepare($q);
	return $this->db->execute(array(
	    ":shortname"    => $shortname,
	    ":destination"  => $destination, 
	    ":timestamp"    => $now));
    }
    
    public function getLinks($sort = "timestamp", $direction = "DESC", $page = 1, $search = "") {
	$offset = (($page - 1) * 20) + 1;
	if(isset($search) && $search != "") {
	    $q = "SELECT * FROM `links` WHERE `destination` LIKE :search OR `shortname` LIKE :search ORDER BY :sort :direction LIMIT 20,:offset;";
	    $query = $this->db->prepare($q);
	    $results = $query->execute(array(
		":search"   => "%" . $search . "%",
		":search"   => "%" . $search . "%",
		":sort"	    => $sort,
		":direction"=> $direction,
		":offset"   => $offset));
	}
	else {
	    $q = "SELECT * FROM `links` ORDER BY :sort :direction LIMIT 20,:offset;";
	    $query = $this->db->prepare($q);
	    $results = $query->execute(array(
		":sort"	    => $sort,
		":direction"=> $direction,
		":offset"   => $offset));
	    
	}
	if($results !== false && count($results) > 0) {
	    return $results;
	}
	else {
	    return false;
	}
    }
    
    /**
     * Initial database tables
     * @return boolean 
     */
    public function initDB() {
	switch($this->type) {
	    case "mysql":
		return $this->__initMySQL();
		break;
	    default:
		return false;
	}
    }

    /**
     * Check to see if the tables have been initialized
     * @return boolean 
     */
    public function checkInitialized() {
	try {
	    $q = "SELECT * FROM `links` WHERE id=1;";
	    $query = $this->db->prepare($q);
	    $results = $query->execute();
	    if($results !== false && count($results) > 0) {
		return true;
	    } else {
		return false;
	    }
	} catch(PDOException $ex) {
	    return false;
	}
    }

    private function __initMySQL() {
	try {
	    $q = "CREATE TABLE `links`(`id` int(11) PRIMARY KEY AUTO_INCREMENT, `shortname` VARCHAR(128), `destination` TEXT, `timestamp` TIMESTAMP);";
	    $query = $this->db->prepare($q);
	    return $query->execute();
	} catch(PDOException $ex) {
	    return false;
	}
    }

    
    
}

?>
