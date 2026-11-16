<?php
require_once '../db.php';
require_once '../calculation/calculation.php';
$all = $conn->query("SELECT * FROM vehicles ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
$v1Id = (int)($_GET['v1'] ?? ($all[1]['id'] ?? 1));
$v2Id = (int)($_GET['v2'] ?? ($all[0]['id'] ?? 1));

$map = [];
foreach ($all as $item) $map[$item['id']] = $item;
$c1 = $map[$v1Id] ?? $all[0];
$c2 = $map[$v2Id] ?? $all[0];
$m1 = calculateVehicleMetrics($c1);
$m2 = calculateVehicleMetrics($c2);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Compare — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>
  <div class="app-shell"><div class="glass-frame"><?php include '../navbar.php'; ?>
    <!-- <nav class="navbar">
      <a class="brand" href="../index.php"><div class="brand-icon">V</div> VEHORA</a>
      <div class="nav-links"><a class="nav-link" href="../index.php">Home</a><a class="nav-link" href="vehicles.php">Vehicles</a><a class="nav-link active" href="compare.php">Compare</a><a class="nav-link" href="../calculation/calculator.php">Calculator</a></div>
    </nav> -->
    <div class="section-pad">
      <div class="section-title">⚖️ Side-by-Side Vehicle Comparison</div>
      <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end;">
        <div class="field" style="flex:1;"><label>Vehicle 1</label>
          <select name="v1" onchange="this.form.submit()"><?php foreach ($all as $a): ?><option value="<?= $a['id'] ?>" <?= $a['id']==$c1['id']?'selected':'' ?>><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?></select>
        </div>
        <div class="field" style="flex:1;"><label>Vehicle 2</label>
          <select name="v2" onchange="this.form.submit()"><?php foreach ($all as $a): ?><option value="<?= $a['id'] ?>" <?= $a['id']==$c2['id']?'selected':'' ?>><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?></select>
        </div>
      </form>
      <div class="compare-grid">
        <?php foreach ([[$c1, $m1], [$c2, $m2]] as [$v, $m]): ?>
          <div class="compare-col">
            <h3 style="margin:0;"><?= htmlspecialchars($v['name']) ?></h3>
            <img src="<?= htmlspecialchars($v['image']) ?>" onerror="this.src=fallbackSvg;" />
            <div class="spec-row"><span>Price</span><strong><?= fmtINR($v['price']) ?></strong></div>
            <div class="spec-row"><span>Type</span><strong><?= htmlspecialchars($v['type']) ?></strong></div>
            <div class="spec-row"><span>Mileage</span><strong><?= $v['mileage'] ?> km/l</strong></div>
            <div class="spec-row"><span>Est. Monthly EMI</span><strong><?= fmtINR($m['emi']) ?></strong></div>
            <div class="spec-row"><span>Fuel / Month</span><strong><?= fmtINR($m['monthly_fuel']) ?></strong></div>
            <div class="spec-row"><span>Yearly Total Cost</span><strong style="color:#34d399;"><?= fmtINR($m['yearly_total']) ?></strong></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div></div>
  <script src="../js/script.js"></script>
</body>
</html>