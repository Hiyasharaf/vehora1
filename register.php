<?php
require_once 'db.php';
$msg = ''; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $name, $email, $pass);
    if ($stmt->execute()) $msg = "Account created! You can now login.";
    else $err = "Email already registered.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Register — VEHORA</title><link rel="stylesheet" href="css/style.css?v=3"></head>
<body>
  <div class="glass-frame auth-card">
    <div class="brand" style="margin-bottom:18px;"><div class="brand-icon">V</div> Create Account</div>
    <?php if ($msg): ?><p class="msg-ok"><?= $msg ?> <a href="login.php" style="color:#fff;">Login</a></p><?php endif; ?>
    <?php if ($err): ?><p class="msg-err"><?= $err ?></p><?php endif; ?>
    <form method="POST">
      <div class="field" style="margin-bottom:12px;"><label>Full Name</label><input type="text" name="name" required></div>
      <div class="field" style="margin-bottom:12px;"><label>Email</label><input type="email" name="email" required></div>
      <div class="field" style="margin-bottom:16px;"><label>Password</label><input type="password" name="password" minlength="4" required></div>
      <button type="submit" class="btn-primary btn-sm">Register</button>
    </form>
    <p style="font-size:0.86rem;margin-top:16px;"><a href="login.php" style="color:var(--purple-light);">Back to Login</a></p>
  </div>
</body>
</html>