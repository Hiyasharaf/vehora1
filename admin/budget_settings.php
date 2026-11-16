<?php
require_once '../db.php';
requireAdmin();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pct = max(0, min(50, (int)$_POST['pct']));
    $stmt = $conn->prepare("UPDATE settings SET allow_above_budget_pct = ? WHERE id = 1");
    $stmt->bind_param("i", $pct);
    $stmt->execute();
    $msg = "Budget tolerance updated to {$pct}%!";
}
$pct = getBudgetTolerance($conn);
$dashOffset = 264 - ($pct / 50) * 264 * 0.8;
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Budget Settings — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body><?php include 'navbar.php'; ?>
  <div class="app-shell"><div class="glass-frame"><div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-brand"><div class="brand"><div class="brand-icon">V</div> VEHORA</div></div>
      <a class="side-btn" href="admin_dashboard.php">🏠 Dashboard</a>
      <a class="side-btn" href="add_vehicle.php">➕ Add Vehicle</a>
      <a class="side-btn" href="manage_vehicles.php">🚗 Manage Vehicles</a>
      <a class="side-btn" href="manage_users.php">👥 Manage Users</a>
      <a class="side-btn active" href="budget_settings.php">⚙️ Budget Settings</a>
      <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>
    </aside>
    <main class="admin-main">
      <div class="panel-box" style="max-width:520px;">
        <h3 style="margin-top:0;">⚙️ Budget Tolerance Settings</h3>
        <?php if ($msg): ?><p class="msg-ok"><?= $msg ?></p><?php endif; ?>
        <div style="font-size:0.9rem;">Allow Above Budget: <strong style="color:var(--purple-light);" id="sliderPctLabel"><?= $pct ?>%</strong></div>
        <form method="POST" class="budget-widget">
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
    </main>
  </div></div></div>
  <script src="../js/script.js"></script>
</body>
</html>