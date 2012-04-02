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
        $this->type = $config['type'];
        switch($this->type) {
            case "mysql":
                $this->user = $config['user'];
                $this->host = $config['host'];
                $this->pass = $config['pass'];
                $this->name = $config['name'];
                $this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
                break;
            case "sqlite":
                $this->name = $config['name'];
                $this->db = new PDO("sqlite:" . $this->name);
                break;
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
            case "sqlite":
                return $this->__initSQLite();
                break;
            default:
                return false;
        }
    }
    
    private function __initMySQL() {
        try {
            $q = "CREATE TABLE `links`(`id` int(11) PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(128), `destination` TEXT);";
            $query = $this->db->prepare($q);
            return $query->execute();
        }
        catch(PDOException $ex) {
            return false;
        }
    }
    
    private function __initSQLite() {
        try {
            $q = "CREATE TABLE links(id INTEGER PRIMARY KEY ASC, name TEXT, destination TEXT);";
            $query = $this->db->prepare($q);
            return $query->execute();
        }
        catch(PDOException $ex) {
            return false;
        }
    }
    
    
}

?>
