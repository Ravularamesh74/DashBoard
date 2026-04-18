<?php

trait HasSoftDeletes {

    public function softDelete($id) {
        return $this->query(
            "UPDATE {$this->table} SET deleted_at = NOW() WHERE id=?",
            [$id],
            "i"
        );
    }
}