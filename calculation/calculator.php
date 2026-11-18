<?php
require_once '../db.php';
require_once 'calculation.php';

$all = $conn->query("SELECT * FROM vehicles ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
$id      = (int)($_GET['id'] ?? 2);
$budget = isset($_GET['budget']) ? (int)$_GET['budget'] : 100000;
$type = $_GET['type'] ?? 'All';
$downPct = (int)($_GET['down_pct'] ?? 20);
$tenure  = (int)($_GET['tenure'] ?? 24);
$dailyKm = (int)($_GET['daily_km'] ?? 20);
$fuelPr  = (int)($_GET['fuel_price'] ?? 104);

$selected = $all[0];
foreach ($all as $item) { if ($item['id'] == $id) $selected = $item; }
$m = calculateVehicleMetrics($selected, $downPct, $tenure, $dailyKm, $fuelPr);

// Find best recommendation within budget
$candidates = array_filter($all, fn($x) => $x['price'] <= $budget);
if (empty($candidates)) $candidates = $all;
usort($candidates, function($a, $b) use ($downPct, $tenure, $dailyKm, $fuelPr) {
    return calculateVehicleMetrics($a, $downPct, $tenure, $dailyKm, $fuelPr)['yearly_total']
       <=> calculateVehicleMetrics($b, $downPct, $tenure, $dailyKm, $fuelPr)['yearly_total'];
});
$best = $candidates[0];
$bestMetrics = calculateVehicleMetrics($best, $downPct, $tenure, $dailyKm, $fuelPr);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Expense Calculator — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>

  <div class="app-shell"><div class="glass-frame">
    <nav class="navbar">
      <a class="brand" href="../index.php"><div class="brand-icon">V</div> VEHORA</a>
      <div class="nav-links">
        <a class="nav-link active" href="calculator.php">Calculator</a>
        <a class="nav-link" href="../user/compare.php">Compare</a>
      <!-- <a class="nav-link"
   href="recommendation.php?budget=<?= (int)$budget ?>&type=<?= urlencode($type) ?>">
   Recommendation
</a>
         -->
      </div>
      <a class="btn-primary" style="padding:7px 14px;" href="../index.php">🏠 Home</a>
    </nav>

    <div class="calc-header">
      <div><h2>🧮 Vehicle Expense Calculator</h2><p>Know Your Total Cost Before You Buy</p></div>
    </div>

    <form class="calc-controls" method="GET">
    <input type="hidden" name="budget" value="<?= (int)$budget ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

      <div class="field"><label>Select Vehicle</label>
        <select name="id" onchange="this.form.submit()">
          <?php foreach ($all as $v): ?><option value="<?= $v['id'] ?>" <?= $v['id']==$selected['id']?'selected':'' ?>><?= htmlspecialchars($v['name']) ?> (<?= fmtINR($v['price']) ?>)</option><?php endforeach; ?>
        </select>
      </div>
      <div class="field"><label>Down Payment (%)</label><input type="number" name="down_pct" value="<?= $downPct ?>" min="0" max="80" /></div>
      <div class="field"><label>Loan Tenure (Months)</label><input type="number" name="tenure" value="<?= $tenure ?>" min="6" max="84" /></div>
      <div class="field"><label>Daily Commute (km/day)</label><input type="number" name="daily_km" value="<?= $dailyKm ?>" min="5" max="150" /></div>
      <div class="field"><label>Fuel Price (₹/L)</label><input type="number" name="fuel_price" value="<?= $fuelPr ?>" min="60" max="150" /></div>
      <div style="display:flex;align-items:end;"><button type="submit" class="btn-primary btn-sm">Recalculate</button></div>
    </form>

    <div class="calc-grid">
      <div class="calc-preview">
        <div>
          <img src="<?= htmlspecialchars($selected['image']) ?>" onerror="this.src=fallbackSvg;" />
          <h3 style="margin:6px 0 4px;"><?= htmlspecialchars($selected['name']) ?></h3>
          <div style="color:var(--purple-light);font-weight:700;"><?= fmtINR($selected['price']) ?> • <?= htmlspecialchars($selected['type']) ?></div>
        </div>
        <div style="margin-top:12px;font-size:0.84rem;color:var(--muted);">Brand: <?= htmlspecialchars($selected['brand']) ?> • Mileage: <?= $selected['mileage'] ?> km/l</div>
      </div>

      <div class="calc-metrics">
        <div class="metric-card"><div class="metric-icon" style="color:#fbbf24;">₹</div><div><div class="metric-label">Monthly EMI</div><div class="metric-val"><?= fmtINR($m['emi']) ?></div></div></div>
        <div class="metric-card"><div class="metric-icon" style="color:#60a5fa;">🔧</div><div><div class="metric-label">Maintenance / Year</div><div class="metric-val"><?= fmtINR($selected['maint']) ?></div></div></div>
        <div class="metric-card"><div class="metric-icon" style="color:#f59e0b;">⛽</div><div><div class="metric-label">Fuel Cost / Month</div><div class="metric-val"><?= fmtINR($m['monthly_fuel']) ?></div></div></div>
        <div class="metric-card"><div class="metric-icon" style="color:#fb923c;">📊</div><div><div class="metric-label">Monthly Expenses</div><div class="metric-val"><?= fmtINR($m['monthly_total']) ?></div></div></div>
        <div class="metric-card"><div class="metric-icon" style="color:#818cf8;">🛡️</div><div><div class="metric-label">Insurance / Year</div><div class="metric-val"><?= fmtINR($selected['ins']) ?></div></div></div>
        <div class="metric-card"><div class="metric-icon" style="color:#34d399;">📅</div><div><div class="metric-label">Yearly Expenses</div><div class="metric-val"><?= fmtINR($m['yearly_total']) ?></div></div></div>
      </div>
    </div>

    <div class="rec-banner">
      <div style="display:flex;align-items:center;gap:14px;">
        <div style="font-size:2rem;">🏆</div>
        <div>
          <div style="font-size:0.84rem;color:var(--purple-light);font-weight:600;">Our Recommendation</div>
          <div style="font-size:1.18rem;font-weight:800;color:#34d399;"><?= htmlspecialchars($best['name']) ?></div>
          <div style="font-size:0.86rem;color:#cbd5e1;">Lowest yearly cost (<?= fmtINR($bestMetrics['yearly_total']) ?>/yr) within your budget!</div>
        </div>
      </div>
      <a class="btn-primary" href="../user/compare.php?v1=<?= $selected['id'] ?>&v2=<?= $best['id'] ?>">Compare Vehicles →</a>
    </div>
  </div></div>
  <script src="../js/script.js"></script>
</body>
</html>