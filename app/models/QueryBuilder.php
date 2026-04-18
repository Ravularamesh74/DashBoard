<?php

class QueryBuilder {

    private $conn;
    private $table;
    private $select = "*";
    private $joins = [];
    private $wheres = [];
    private $bindings = [];
    private $types = "";
    private $orderBy = "";
    private $limit = "";
    private $offset = "";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /* =========================
       TABLE
    ========================= */
    public function table($table) {
        $this->table = $table;
        return $this;
    }

    /* =========================
       SELECT
    ========================= */
    public function select($columns = "*") {
        $this->select = $columns;
        return $this;
    }

    /* =========================
       WHERE
    ========================= */
    public function where($column, $operator, $value) {

        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        $this->types .= $this->getType($value);

        return $this;
    }

    public function orWhere($column, $operator, $value) {

        if (!empty($this->wheres)) {
            $this->wheres[] = "OR $column $operator ?";
        }

        $this->bindings[] = $value;
        $this->types .= $this->getType($value);

        return $this;
    }

    /* =========================
       JOIN
    ========================= */
    public function join($table, $first, $operator, $second) {
        $this->joins[] = "JOIN $table ON $first $operator $second";
        return $this;
    }

    /* =========================
       ORDER
    ========================= */
    public function orderBy($column, $direction = "ASC") {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    /* =========================
       LIMIT / OFFSET
    ========================= */
    public function limit($limit) {
        $this->limit = "LIMIT ?";
        $this->bindings[] = $limit;
        $this->types .= "i";
        return $this;
    }

    public function offset($offset) {
        $this->offset = "OFFSET ?";
        $this->bindings[] = $offset;
        $this->types .= "i";
        return $this;
    }

    /* =========================
       BUILD QUERY
    ========================= */
    private function buildQuery() {

        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" ", $this->wheres);
        }

        if ($this->orderBy) {
            $sql .= " " . $this->orderBy;
        }

        if ($this->limit) {
            $sql .= " " . $this->limit;
        }

        if ($this->offset) {
            $sql .= " " . $this->offset;
        }

        return $sql;
    }

    /* =========================
       EXECUTE
    ========================= */
    public function get() {

        $sql = $this->buildQuery();

        $stmt = $this->conn->prepare($sql);

        if (!empty($this->bindings)) {
            $stmt->bind_param($this->types, ...$this->bindings);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function first() {

        $this->limit(1);

        $result = $this->get();

        return $result[0] ?? null;
    }

    public function count() {

        $this->select("COUNT(*) as total");

        $result = $this->first();

        return $result['total'] ?? 0;
    }

    /* =========================
       INSERT
    ========================= */
    public function insert($data) {

        $columns = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));

        $values = array_values($data);

        $types = "";
        foreach ($values as $value) {
            $types .= $this->getType($value);
        }

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        return $this->conn->insert_id;
    }

    /* =========================
       UPDATE
    ========================= */
    public function update($data) {

        $set = [];
        $values = [];

        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
            $values[] = $value;
            $this->types .= $this->getType($value);
        }

        $sql = "UPDATE {$this->table} SET " . implode(",", $set);

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" ", $this->wheres);
        }

        $stmt = $this->conn->prepare($sql);

        $allValues = array_merge($values, $this->bindings);
        $allTypes = $this->types;

        $stmt->bind_param($allTypes, ...$allValues);
        $stmt->execute();

        return $stmt->affected_rows;
    }

    /* =========================
       DELETE
    ========================= */
    public function delete() {

        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" ", $this->wheres);
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($this->bindings)) {
            $stmt->bind_param($this->types, ...$this->bindings);
        }

        $stmt->execute();

        return $stmt->affected_rows;
    }

    /* =========================
       TYPE DETECTOR
    ========================= */
    private function getType($value) {
        return match (true) {
            is_int($value) => "i",
            is_float($value) => "d",
            default => "s"
        };
    }

    /* =========================
       RESET (REUSABILITY)
    ========================= */
    public function reset() {
        $this->select = "*";
        $this->joins = [];
        $this->wheres = [];
        $this->bindings = [];
        $this->types = "";
        $this->orderBy = "";
        $this->limit = "";
        $this->offset = "";
        return $this;
    }
}