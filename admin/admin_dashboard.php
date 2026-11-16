<?php
require_once '../db.php';
requireAdmin();

$totalVehicles = $conn->query("SELECT COUNT(*) c FROM vehicles")->fetch_assoc()['c'];
$totalUsers    = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalCars     = $conn->query("SELECT COUNT(*) c FROM vehicles WHERE type='Car'")->fetch_assoc()['c'];
$totalBikes    = $conn->query("SELECT COUNT(*) c FROM vehicles WHERE type!='Car'")->fetch_assoc()['c'];
$recent        = $conn->query("SELECT * FROM vehicles ORDER BY id DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
$pct           = getBudgetTolerance($conn);
$dashOffset    = 264 - ($pct / 50) * 264 * 0.8;
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Admin Dashboard — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>

  <div class="app-shell"><div class="glass-frame"><div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-brand"><div class="brand"><div class="brand-icon">V</div> VEHORA</div><div style="font-size:0.78rem;color:var(--muted);margin-top:4px;">Admin Panel</div></div>
      <a class="side-btn active" href="admin_dashboard.php">🏠 Dashboard</a>
      <a class="side-btn" href="add_vehicle.php">➕ Add Vehicle</a>
      <a class="side-btn" href="manage_vehicles.php">🚗 Manage Vehicles</a>
      <a class="side-btn" href="manage_users.php">👥 Manage Users</a>
      <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
      <a class="side-btn" href="../index.php">🌐 View Site</a>
      <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>
    </aside>

    <main class="admin-main">
      <div class="admin-top">
        <div><h2 style="margin:0 0 4px;">Admin Dashboard</h2><div style="font-size:0.88rem;color:var(--muted);">Overview of Your Platform</div></div>
       <div class="user-pill"><a class="nav-link <?= basename($cur) === 'index.php' ? 'active' : '' ?>" href="/vehora1/index.php">🏠Home</a></div>
      </div>

      <div class="stats-row">
        <div class="stat-box"><div class="metric-icon">🚗</div><div><div class="stat-num"><?= $totalVehicles ?></div><div class="metric-label">Total Vehicles</div></div></div>
        <div class="stat-box"><div class="metric-icon">👥</div><div><div class="stat-num"><?= $totalUsers ?></div><div class="metric-label">Total Users</div></div></div>
        <div class="stat-box"><div class="metric-icon">🚙</div><div><div class="stat-num"><?= $totalCars ?></div><div class="metric-label">Total Cars</div></div></div>
        <div class="stat-box"><div class="metric-icon">🏍️</div><div><div class="stat-num"><?= $totalBikes ?></div><div class="metric-label">Total Bikes/Scooters</div></div></div>
      </div>

      <div class="admin-bottom">
        <div class="panel-box">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <strong>Recent Vehicles</strong>
            <a style="color:var(--purple-light);font-size:0.84rem;text-decoration:none;" href="manage_vehicles.php">View All →</a>
          </div>
          <table>
            <thead><tr><th>Image</th><th>Name</th><th>Brand</th><th>Type</th><th>Price</th></tr></thead>
            <tbody>
              <?php foreach ($recent as $v): ?>
                <tr>
                  <td><img class="tbl-img" src="<?= htmlspecialchars($v['image']) ?>" onerror="this.src=fallbackSvg;" /></td>
                  <td><strong><?= htmlspecialchars($v['name']) ?></strong></td>
                  <td><?= htmlspecialchars($v['brand']) ?></td>
                  <td><?= htmlspecialchars($v['type']) ?></td>
                  <td><?= fmtINR($v['price']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="panel-box">
          <strong>⚙️ Budget Settings</strong>
          <div style="margin-top:12px;font-size:0.9rem;">Allow Above Budget: <strong style="color:var(--purple-light);" id="sliderPctLabel"><?= $pct ?>%</strong></div>
          <form method="POST" action="budget_settings.php" class="budget-widget">
            <div style="flex:1;">
              <input type="range" name="pct" min="0" max="50" value="<?= $pct ?>" style="width:100%;" oninput="updateGaugePreview(this.value)" />
              <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--muted);margin-bottom:12px;"><span>0%</span><span>50%</span></div>
              <button type="submit" class="btn-primary btn-sm">Save Settings</button>
            </div>
            <div class="gauge">
              <svg viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="42" stroke="#1e1b4b" stroke-width="8" fill="none"/>
                <circle id="gaugeCircle" cx="50" cy="50" r="42" stroke="#10b981" stroke-width="8" fill="none" stroke-dasharray="264" stroke-dashoffset="<?= $dashOffset ?>" stroke-linecap="round"/>
              </svg>
              <div class="gauge-text"><strong id="gaugeVal"><?= $pct ?>%</strong><span>Current Limit</span></div>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div></div></div>
  <script src="../js/script.js"></script>
</body>
</html>