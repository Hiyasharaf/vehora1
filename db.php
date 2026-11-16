<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "vehora_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Auto-seed default Admin (admin@vehora.in / admin123)
$chk = $conn->query("SELECT id FROM users WHERE email='admin@vehora.in' LIMIT 1");
if ($chk && $chk->num_rows === 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@vehora.in', ?, 'admin')");
    $stmt->bind_param("s", $hash);
    $stmt->execute();
}

function fmtINR($num) {
    return "₹" . number_format((int)round($num));
}

function getBudgetTolerance($conn) {
    $res = $conn->query("SELECT allow_above_budget_pct FROM settings WHERE id = 1");
    $row = $res ? $res->fetch_assoc() : null;
    return $row ? (int)$row['allow_above_budget_pct'] : 20;
}

function requireAdmin() {
    $_SESSION['user_id']   = $_SESSION['user_id'] ?? 1;
    $_SESSION['user_name'] = 'Admin';
    $_SESSION['role']      = 'admin';

}
?>