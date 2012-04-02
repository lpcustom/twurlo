<?php
/**
 * @author lpcustom
 * Class for interacting with the database.
 */
class Database {
    private $type;
    private $user;
    private $host;
    private $pass;
    private $name;
    private $db;
    
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
    
    
    
}

?>
