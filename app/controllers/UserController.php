<?php

class UserController {

    private $db;
    private $qb;
    private $userModel;

    public function __construct() {
        $this->db = DB::connect();
        $this->qb = new QueryBuilder($this->db);
        $this->userModel = new User();
    }

    /* =========================
       LIST USERS (ADVANCED)
    ========================= */
    public function index() {

        requireAuth();

        $search = sanitize($_GET['search'] ?? '');
        $page   = (int)($_GET['page'] ?? 1);
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $query = $this->qb->reset()
            ->table("users");

        if ($search) {
            $query->where("email", "LIKE", "%$search%");
        }

        $users = $query
            ->orderBy("id", "DESC")
            ->limit($limit)
            ->offset($offset)
            ->get();

        $total = $this->qb->reset()
            ->table("users")
            ->count();

        return jsonResponse([
            "users" => $users,
            "pagination" => paginate($total, $page, $limit)
        ]);
    }

    /* =========================
       GET SINGLE USER
    ========================= */
    public function show($id) {

        requireAuth();

        $user = $this->qb->reset()
            ->table("users")
            ->where("id", "=", (int)$id)
            ->first();

        if (!$user) {
            return jsonResponse([], "User not found", false, 404);
        }

        return jsonResponse($user);
    }

    /* =========================
       CREATE USER
    ========================= */
    public function store() {

        requireRole('admin');
        verifyCSRF($_POST['csrf'] ?? '');

        $data = [
            'email' => sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'user'
        ];

        $errors = validate($data, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        if (!empty($errors)) {
            return jsonResponse($errors, "Validation failed", false, 422);
        }

        if ($this->userModel->findByEmail($data['email'])) {
            return jsonResponse([], "Email already exists", false, 409);
        }

        $id = $this->qb->reset()
            ->table("users")
            ->insert([
                "email" => $data['email'],
                "password" => hashPassword($data['password']),
                "role" => $data['role']
            ]);

        return jsonResponse(["id" => $id], "User created", true, 201);
    }

    /* =========================
       UPDATE USER
    ========================= */
    public function update($id) {

        requireRole('admin');
        verifyCSRF($_POST['csrf'] ?? '');

        $email = sanitize($_POST['email'] ?? '');
        $role  = $_POST['role'] ?? 'user';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return jsonResponse([], "Invalid email", false, 422);
        }

        $affected = $this->qb->reset()
            ->table("users")
            ->where("id", "=", (int)$id)
            ->update([
                "email" => $email,
                "role" => $role
            ]);

        return jsonResponse([
            "affected_rows" => $affected
        ], "User updated");
    }

    /* =========================
       DELETE USER
    ========================= */
    public function delete($id) {

        requireRole('admin');
        verifyCSRF($_POST['csrf'] ?? '');

        if ((int)$id === $_SESSION['user']['id']) {
            return jsonResponse([], "Cannot delete yourself", false, 400);
        }

        $affected = $this->qb->reset()
            ->table("users")
            ->where("id", "=", (int)$id)
            ->delete();

        return jsonResponse([
            "affected_rows" => $affected
        ], "User deleted");
    }

    /* =========================
       BULK DELETE
    ========================= */
    public function bulkDelete() {

        requireRole('admin');
        verifyCSRF($_POST['csrf'] ?? '');

        $ids = $_POST['ids'] ?? [];

        if (empty($ids)) {
            return jsonResponse([], "No users selected", false, 422);
        }

        // Prevent self delete
        $ids = array_filter($ids, fn($id) => $id != $_SESSION['user']['id']);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids));

        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id IN ($placeholders)"
        );

        $stmt->bind_param($types, ...$ids);
        $stmt->execute();

        return jsonResponse([], "Users deleted");
    }

    /* =========================
       PROFILE
    ========================= */
    public function profile() {

        requireAuth();

        $user = $this->qb->reset()
            ->table("users")
            ->where("id", "=", $_SESSION['user']['id'])
            ->first();

        return jsonResponse($user);
    }

    /* =========================
       CHANGE PASSWORD
    ========================= */
    public function changePassword() {

        requireAuth();
        verifyCSRF($_POST['csrf'] ?? '');

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';

        $user = $this->qb->reset()
            ->table("users")
            ->where("id", "=", $_SESSION['user']['id'])
            ->first();

        if (!$user || !verifyPassword($current, $user['password'])) {
            return jsonResponse([], "Current password incorrect", false, 401);
        }

        if (strlen($new) < 6) {
            return jsonResponse([], "Weak password", false, 422);
        }

        $this->qb->reset()
            ->table("users")
            ->where("id", "=", $user['id'])
            ->update([
                "password" => hashPassword($new)
            ]);

        return jsonResponse([], "Password updated");
    }
}