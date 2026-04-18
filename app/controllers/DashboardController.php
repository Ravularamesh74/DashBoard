<?php

require_once "../app/models/User.php";

class DashboardController {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* =========================
       MAIN DASHBOARD VIEW
    ========================= */
    public function index() {

        $this->authorize();

        $stats = $this->getStats();

        require "../resources/views/dashboard/index.php";
    }

    /* =========================
       API: DASHBOARD DATA (AJAX)
    ========================= */
    public function getDashboardData() {

        $this->authorize();

        header('Content-Type: application/json');

        echo json_encode([
            "stats" => $this->getStats(),
            "chart" => $this->getChartData(),
            "recentUsers" => $this->getRecentUsers()
        ]);
        exit();
    }

    /* =========================
       STATS (KPI CARDS)
    ========================= */
    private function getStats() {

        $totalUsers = $this->conn->query("SELECT COUNT(*) as total FROM users")
            ->fetch_assoc()['total'];

        $activeUsers = $this->conn->query("
            SELECT COUNT(*) as total 
            FROM users 
            WHERE last_login > NOW() - INTERVAL 7 DAY
        ")->fetch_assoc()['total'];

        $newUsers = $this->conn->query("
            SELECT COUNT(*) as total 
            FROM users 
            WHERE created_at > NOW() - INTERVAL 7 DAY
        ")->fetch_assoc()['total'];

        return [
            "totalUsers" => (int)$totalUsers,
            "activeUsers" => (int)$activeUsers,
            "newUsers" => (int)$newUsers
        ];
    }

    /* =========================
       CHART DATA (ANALYTICS)
    ========================= */
    private function getChartData() {

        $data = [];

        $result = $this->conn->query("
            SELECT DATE(created_at) as date, COUNT(*) as count
            FROM users
            GROUP BY DATE(created_at)
            ORDER BY date ASC
            LIMIT 7
        ");

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "date" => $row['date'],
                "count" => (int)$row['count']
            ];
        }

        return $data;
    }

    /* =========================
       RECENT USERS
    ========================= */
    private function getRecentUsers() {

        $users = [];

        $result = $this->conn->query("
            SELECT id, email, created_at 
            FROM users 
            ORDER BY created_at DESC 
            LIMIT 5
        ");

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    /* =========================
       ADVANCED FILTER (DATE RANGE)
    ========================= */
    public function filterData() {

        $this->authorize();

        $start = $_GET['start'] ?? null;
        $end   = $_GET['end'] ?? null;

        if (!$start || !$end) {
            $this->response("Invalid date range", false);
        }

        $stmt = $this->conn->prepare("
            SELECT DATE(created_at) as date, COUNT(*) as count
            FROM users
            WHERE created_at BETWEEN ? AND ?
            GROUP BY DATE(created_at)
        ");

        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $this->response($data, true);
    }

    /* =========================
       AUTHORIZATION
    ========================= */
    private function authorize() {

        if (empty($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        // Optional role-based check
        if ($_SESSION['user']['role'] !== 'admin') {
            die("Access denied");
        }
    }

    /* =========================
       RESPONSE HELPER
    ========================= */
    private function response($data, $success = true) {

        header('Content-Type: application/json');

        echo json_encode([
            "success" => $success,
            "data" => $data
        ]);
        exit();
    }
}