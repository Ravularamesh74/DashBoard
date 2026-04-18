<?php

trait HasPagination {

    public function paginate($limit, $offset) {
        return $this->query(
            "SELECT * FROM {$this->table} LIMIT ? OFFSET ?",
            [$limit, $offset],
            "ii"
        );
    }
}