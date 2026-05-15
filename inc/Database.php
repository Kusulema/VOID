<?php
class database {
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;

    function __construct() {
        $this->host = 'localhost';
        $this->user = 'root';
        $this->password = '';
        $this->baseName = 'clothing_shop';
        $this->connect();
    }

    function connect() {
        if (!$this->conn) {
            try {
                $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->baseName.'', $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            } catch (Exception $e) {
                die('Connection failed : ' . $e->getMessage());
            }
        }
        return $this->conn;
    }

    function disconnect() { $this->conn = null; }

    function getOne($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    function getOnePrepared($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    function getAll($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    function getAllPrepared($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    function executeRun($query) {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    function executePrepared($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    function hasColumn($table, $column) {
        $query = 'SHOW COLUMNS FROM `'.$table.'` LIKE :column';
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['column' => $column]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>