<?php

class Database {

    private static $instance = null;
    private $conn;

    private function __construct() {

        $this->conn = new mysqli(
            "localhost",
            "root",
            "",
            "dashboard"
        );

        if ($this->conn->connect_error) {
            die("DB Connection Failed");
        }
    }

    public static function getInstance() {

        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}