<?php

class AuthController {

    private $db;
    private $userModel;

    public function __construct() {
        $this->db = DB::connect();
        $this->userModel = new User();
    }

    /* =========================
       LOGIN
    ========================= */
    public function login() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/login');
        }

        verifyCSRF($_POST['csrf'] ?? '');

        $data = [
            'email' => sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        // VALIDATION
        $errors = validate($data, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        if (!empty($errors)) {
            return jsonResponse($errors, "Validation failed", false, 422);
        }

        // RATE LIMIT
        if (!$this->checkRateLimit($data['email'])) {
            return jsonResponse([], "Too many attempts. Try later.", false, 429);
        }

        // FIND USER
        $user = $this->userModel->findByEmail($data['email']);

        if (!$user || !verifyPassword($data['password'], $user['password'])) {
            $this->logAttempt($data['email'], false);
            return jsonResponse([], "Invalid credentials", false, 401);
        }

        // SESSION SECURITY
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // UPDATE LAST LOGIN
        $this->userModel->updateUser($user['id'], $user['email'], $user['role']);

        $this->logAttempt($data['email'], true);

        // REMEMBER ME
        if (!empty($_POST['remember'])) {
            $this->remember($user['id']);
        }

        return jsonResponse([], "Login successful", true);
    }

    /* =========================
       REGISTER
    ========================= */
    public function register() {

        verifyCSRF($_POST['csrf'] ?? '');

        $data = [
            'email' => sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? ''
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

        $hashed = hashPassword($data['password']);

        $this->userModel->create($data['email'], $hashed);

        return jsonResponse([], "Registered successfully", true);
    }

    /* =========================
       LOGOUT
    ========================= */
    public function logout() {

        logout();

        setcookie("remember_token", "", time() - 3600, "/", "", true, true);

        return redirect('/login');
    }

    /* =========================
       AUTO LOGIN (REMEMBER ME)
    ========================= */
    public function autoLogin() {

        if (isAuth() || empty($_COOKIE['remember_token'])) {
            return;
        }

        $token = hash('sha256', $_COOKIE['remember_token']);

        $stmt = $this->db->prepare("
            SELECT user_id FROM remember_tokens WHERE token=?
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            $user = $this->userModel->find($result['user_id']);

            $_SESSION['user'] = $user;
        }
    }

    /* =========================
       RATE LIMIT SYSTEM
    ========================= */
    private function checkRateLimit($email) {

        $ip = $_SERVER['REMOTE_ADDR'];

        $stmt = $this->db->prepare("
            SELECT COUNT(*) as attempts
            FROM login_attempts
            WHERE ip=? AND created_at > NOW() - INTERVAL 10 MINUTE
        ");

        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $attempts = $stmt->get_result()->fetch_assoc()['attempts'];

        return $attempts < 5;
    }

    private function logAttempt($email, $success) {

        $ip = $_SERVER['REMOTE_ADDR'];

        $stmt = $this->db->prepare("
            INSERT INTO login_attempts (email, ip, success)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("ssi", $email, $ip, $success);
        $stmt->execute();
    }

    /* =========================
       REMEMBER ME
    ========================= */
    private function remember($userId) {

        $token = generateToken(32);
        $hashed = hash('sha256', $token);

        $stmt = $this->db->prepare("
            INSERT INTO remember_tokens (user_id, token)
            VALUES (?, ?)
        ");

        $stmt->bind_param("is", $userId, $hashed);
        $stmt->execute();

        setcookie(
            "remember_token",
            $token,
            time() + (86400 * 30),
            "/",
            "",
            true,
            true
        );
    }
}