<?php
require_once 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    if ($u && password_verify($pass, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        $_SESSION['role'] = $u['role'];
        header("Location: " . ($u['role'] === 'admin' ? "admin/admin_dashboard.php" : "index.php"));
        exit;
    }
    $err = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Login — VEHORA</title><link rel="stylesheet" href="css/style.css?v=3"></head>
<body>
  <div class="glass-frame auth-card">
    <div class="brand" style="margin-bottom:18px;"><div class="brand-icon">V</div> VEHORA Login</div>
    <?php if ($err): ?><p class="msg-err"><?= htmlspecialchars($err) ?></p><?php endif; ?>
    <form method="POST">
      <div class="field" style="margin-bottom:12px;"><label>Email</label><input type="email" name="email" placeholder="admin@vehora.in" required></div>
      <div class="field" style="margin-bottom:16px;"><label>Password</label><input type="password" name="password" placeholder="admin123" required></div>
      <button type="submit" class="btn-primary btn-sm">Login</button>
    </form>
    <p style="font-size:0.86rem;color:var(--muted);margin-top:16px;">New user? <a href="register.php" style="color:var(--purple-light);">Register</a> • <a href="index.php" style="color:var(--purple-light);">Home</a></p>
  </div>
</body>
</html>