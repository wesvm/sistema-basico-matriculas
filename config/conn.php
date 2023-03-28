<?php
require_once('../config.php');

class Connection
{

    protected $dbconn;

    protected function getConnection()
    {
        try {
            $conn = $this->dbconn = new PDO("mysql:local=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            return $conn;
        } catch (Exception $e) {
            print "Error " . $e->getMessage();
            die();
        }
    }

    public function setNames()
    {
        return $this->dbconn->query("SET NAMES 'utf8'");
    }
}
