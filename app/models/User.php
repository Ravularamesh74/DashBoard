<?php

require_once "BaseModel.php";
require_once "Traits/HasPagination.php";

class User extends BaseModel {

    use HasPagination;

    protected $table = "users";

    /* =========================
       FIND BY EMAIL
    ========================= */
    public function findByEmail($email) {
        return $this->query(
            "SELECT * FROM users WHERE email=? LIMIT 1",
            [$email],
            "s"
        )->fetch_assoc();
    }

    /* =========================
       CREATE USER
    ========================= */
    public function create($email, $password, $role = 'user') {
        return $this->query(
            "INSERT INTO users (email, password, role) VALUES (?, ?, ?)",
            [$email, $password, $role],
            "sss"
        );
    }

    /* =========================
       UPDATE USER
    ========================= */
    public function updateUser($id, $email, $role) {
        return $this->query(
            "UPDATE users SET email=?, role=? WHERE id=?",
            [$email, $role, $id],
            "ssi"
        );
    }

    /* =========================
       UPDATE PASSWORD
    ========================= */
    public function updatePassword($id, $password) {
        return $this->query(
            "UPDATE users SET password=? WHERE id=?",
            [$password, $id],
            "si"
        );
    }

    /* =========================
       SEARCH + PAGINATION
    ========================= */
    public function search($search, $limit, $offset) {

        $searchTerm = "%" . $search . "%";

        return $this->query(
            "SELECT * FROM users WHERE email LIKE ? LIMIT ? OFFSET ?",
            [$searchTerm, $limit, $offset],
            "sii"
        );
    }

    /* =========================
       COUNT USERS
    ========================= */
    public function count($search = "") {

        if ($search) {
            $searchTerm = "%" . $search . "%";

            return $this->query(
                "SELECT COUNT(*) as total FROM users WHERE email LIKE ?",
                [$searchTerm],
                "s"
            )->fetch_assoc()['total'];
        }

        return $this->query(
            "SELECT COUNT(*) as total FROM users"
        )->fetch_assoc()['total'];
    }

    /* =========================
       BULK DELETE
    ========================= */
    public function bulkDelete($ids) {

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $types = str_repeat('i', count($ids));

        return $this->query(
            "DELETE FROM users WHERE id IN ($placeholders)",
            $ids,
            $types
        );
    }
}