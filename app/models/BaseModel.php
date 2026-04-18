<?php

require_once "Database.php";

class BaseModel {

    protected $table;
    protected $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    protected function query($sql, $params = [], $types = "") {

        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    public function all() {
        return $this->query("SELECT * FROM {$this->table}");
    }

    public function find($id) {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE id=?",
            [$id],
            "i"
        )->fetch_assoc();
    }

    public function delete($id) {
        return $this->query(
            "DELETE FROM {$this->table} WHERE id=?",
            [$id],
            "i"
        );
    }
}