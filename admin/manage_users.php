<?php
require_once '../db.php';
requireAdmin();
$users = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Manage Users — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>
<?php include 'navbar.php'; ?>
  <div class="app-shell"><div class="glass-frame"><div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-brand"><div class="brand"><div class="brand-icon">V</div> VEHORA</div></div>
      <a class="side-btn" href="admin_dashboard.php">🏠 Dashboard</a>
      <a class="side-btn" href="add_vehicle.php">➕ Add Vehicle</a>
      <a class="side-btn" href="manage_vehicles.php">🚗 Manage Vehicles</a>
      <a class="side-btn active" href="manage_users.php">👥 Manage Users</a>
      <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
      <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>
    </aside>
    <main class="admin-main">
      <div class="panel-box">
        <h3 style="margin-top:0;">👥 Registered Users</h3>
        <table>
          <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr><td>#<?= $u['id'] ?></td><td><?= htmlspecialchars($u['name']) ?></td><td><?= htmlspecialchars($u['email']) ?></td><td><?= htmlspecialchars($u['role']) ?></td><td><?= $u['created_at'] ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div></div></div>
</body>
</html>