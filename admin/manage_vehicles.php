<?php
require_once '../db.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_vehicles.php");
    exit;
}
$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Manage Vehicles — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>
    
  <div class="app-shell"><div class="glass-frame"><div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-brand"><div class="brand"><div class="brand-icon">V</div> VEHORA</div></div>
      <a class="side-btn" href="admin_dashboard.php">🏠 Dashboard</a>
      <a class="side-btn" href="add_vehicle.php">➕ Add Vehicle</a>
      <a class="side-btn active" href="manage_vehicles.php">🚗 Manage Vehicles</a>
      <a class="side-btn" href="manage_users.php">👥 Manage Users</a>
      <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
      <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>
    </aside>
    <main class="admin-main">
      <div class="panel-box" style="overflow-x:auto;">
        <h3 style="margin-top:0;">🚗 Manage All Vehicles</h3>
        <table>
          <thead><tr><th>Image</th><th>Name</th><th>Brand</th><th>Type</th><th>Price</th><th>Mileage</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($vehicles as $v): ?>
              <tr>
                <td><img class="tbl-img" src="<?= htmlspecialchars($v['image']) ?>" onerror="this.src=fallbackSvg;" /></td>
                <td><strong><?= htmlspecialchars($v['name']) ?></strong></td>
                <td><?= htmlspecialchars($v['brand']) ?></td>
                <td><?= htmlspecialchars($v['type']) ?></td>
                <td><?= fmtINR($v['price']) ?></td>
                <td><?= $v['mileage'] ?> km/l</td>
               <td style="white-space:nowrap;">
    <a href="edit_vehicle.php?id=<?= (int)$v['id'] ?>"
       style="background:#6366f1;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;">
        Edit
    </a>

    <a href="?delete=<?= (int)$v['id'] ?>"
       onclick="return confirm('Are you sure you want to delete this vehicle?')"
       style="background:#ef4444;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;margin-left:5px;">
        Delete
    </a>
</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div></div></div>
  <script src="../js/script.js"></script>
</body>
</html>