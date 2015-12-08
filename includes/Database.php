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
            $query->execute();
            $results = $query->fetchAll();
            print_r($results);
            if($results != false && count($results) > 0) {
                return (string)$results[0]['maxID'] + 1;
            } else {
                return false;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    // Add a link to the database; called from add.php
    public function addLink($url, $short, $site_name, $description, $title, $image) {
        $now = time();
        $q = "INSERT INTO `links`(`shortname`, `destination`, `site_name`, `description`, `title`, `image`) VALUES(:shortname, :destination, :site_name, :description, :title, :image);";
        $query = $this->db->prepare($q);
        return $query->execute(
            array(
                ":shortname"    => $short,
                ":destination"  => $url,
                ":site_name"    => $site_name,
                ":description"  => $description,
                ":title"        => $title,
                ":image"        => $image
            )
        );
    }

    public function deleteLink($id) {
        try {
            $q = "DELETE FROM `links` WHERE `id` = :id;";
            $query = $this->db->prepare($q);
            if($query->execute(array(":id" => $id))) {
                $q = "DELETE FROM `clicks` WHERE `link_id` = :id;";
                $query = $this->db->prepare($q);
                if($query->execute(array(":id" => $id))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    // get a list of links based on given criteria
    public function getLinks($page = 1, $search = "") {
        try {
            $offset = (($page - 1) * 20);
            if(isset($search) && $search != "") {
                $search = '%' . strtolower($search) . '%';
                $q = "SELECT * FROM `links` WHERE LOWER(`destination`) LIKE :search1 OR LOWER(`shortname`) LIKE :search2 ORDER BY `timestamp` DESC LIMIT :offset, 20;";
                $query = $this->db->prepare($q);
                $query->bindParam(":search1", $search, PDO::PARAM_STR);
                $query->bindParam(":search2", $search, PDO::PARAM_STR);
                $query->bindParam(":offset", $offset, PDO::PARAM_INT);
                $query->execute();
            } else {
                $q = "SELECT * FROM `links` ORDER BY `timestamp` DESC LIMIT :offset, 20;";
                $query = $this->db->prepare($q);
                $query->bindParam(":offset", $offset, PDO::PARAM_INT);
                $query->execute();
            }
            $results = $query->fetchAll();
            if($results !== false && count($results) > 0) {
                return $results;
            } else {
                return false;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    public function getLinkCount($search = "") {
        try {
            if($search != "") {
                $q = "SELECT COUNT(`id`) AS `count` FROM `links` WHERE LOWER(`destination`) LIKE :search1 or LOWER(`shortname`) LIKE : search2;";
                $query = $this->db->prepare($q);
                $query->bindParam(":search1", $search, PDO::PARAM_STR);
                $query->bindParam(":search2", $search, PDO::PARAM_STR);
                $query->execute();
            } else {
                $q = "SELECT COUNT(`id`) AS `count` FROM `links`;";
                $query = $this->db->prepare($q);
                $query->execute();
            }
            $results = $query->fetchAll();
            if($results !== false && count($results) > 0) {
                return $results[0]['count'];
            } else {
                return 0;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    public function getClickCount($id) {
        try {
            $q = "SELECT COUNT(`id`) as `sum` FROM `clicks` WHERE `link_id` = :id;";
            $query = $this->db->prepare($q);
            $query->execute(array(
                ":id" => $id
            ));
            $results = $query->fetchAll();
            if($results !== false && count($results) > 0) {
                return $results[0]['sum'];
            } else {
                return false;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    public function shortnameAvailable($short) {
        try {
            $q = "SELECT * FROM `links` WHERE `shortname` = :short;";
            $query = $this->db->prepare($q);
            $query->execute(array(":short" => $short));
            $results = $query->fetchAll();
            if($results !== false && count($results) > 0) {
                return false;
            } else {
                return true;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    public function getLinkByShort($short) {
        try {
            $q = "SELECT * FROM `links` WHERE `shortname` = :short;";
            $query = $this->db->prepare($q);
            $query->execute(array(":short" => $short));
            $results = $query->fetchAll();
            if($results !== false && count($results) > 0) {
                return $results[0];
            } else {
                return false;
            }
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }

    public function recordClick($link, $referrer) {
        $q = "INSERT INTO `clicks`(`link_id`, `referrer`) VALUES(:link_id, :referrer);";
        $query = $this->db->prepare($q);
        return $query->execute(array(
            ":link_id" => $link['id'],
            ":referrer" => $referrer
        ));

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
            error_log($ex);
            return false;
        }
    }

    private function __initMySQL() {
        $flag = true;
        if(!$this->__createLinksTable()) {
            $flag = false;
        }
        if(!$this->__createClicksTable()) {
            $flag = false;
        }
        return $flag;
    }

    private function __createLinksTable() {
        try {
            $q = "
            CREATE TABLE `links` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `shortname` varchar(128) DEFAULT NULL,
                `destination` text,
                `type` enum('301','302') default '301',
                `title` text,
                `image` text,
                `description` text,
                `site_name` text,
                `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `shortname` (`shortname`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $query = $this->db->prepare($q);
            return $query->execute();
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }

    }

    private function __createClicksTable() {
        try {
            $q = "
            CREATE TABLE `clicks`(
                `id` int(11) PRIMARY KEY AUTO_INCREMENT,
                `link_id` int(11), 
                `timestamp` TIMESTAMP, 
                `referrer` TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $query = $this->db->prepare($q);
            return $query->execute();
        } catch(PDOException $ex) {
            error_log($ex);
            return false;
        }
    }
}

?>
