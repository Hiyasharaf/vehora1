<?php
require_once '../db.php';
require_once 'calculation.php';
$budget = (int)($_GET['budget'] ?? 100000);
$all = $conn->query("SELECT * FROM vehicles")->fetch_all(MYSQLI_ASSOC);
$candidates = array_filter($all, fn($x) => $x['price'] <= $budget);
if (empty($candidates)) $candidates = $all;
usort($candidates, fn($a, $b) => calculateVehicleMetrics($a)['yearly_total'] <=> calculateVehicleMetrics($b)['yearly_total']);
$best = $candidates[0];
$bm = calculateVehicleMetrics($best);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recommendation — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>
  <div class="app-shell"><div class="glass-frame">
    <nav class="navbar">
      <a class="brand" href="../index.php"><div class="brand-icon">V</div> VEHORA</a>
      <div class="nav-links"><a class="nav-link" href="../index.php">Home</a><a class="nav-link" href="calculator.php">Calculator</a><a class="nav-link active" href="recommendation.php">Recommendation</a></div>
    </nav>
    <div class="section-pad">
      <div class="section-title">🏆 Best Vehicle Recommendation for Budget <?= fmtINR($budget) ?></div>
      <div class="rec-banner" style="margin:0;">
        <div style="display:flex;align-items:center;gap:18px;flex-wrap:wrap;">
          <img src="<?= htmlspecialchars($best['image']) ?>" style="width:180px;height:120px;object-fit:cover;border-radius:10px;" onerror="this.src=fallbackSvg;" />
          <div>
            <h2 style="margin:0 0 6px;color:#34d399;"><?= htmlspecialchars($best['name']) ?></h2>
            <p style="margin:0;color:#cbd5e1;">Price: <?= fmtINR($best['price']) ?> • Mileage: <?= $best['mileage'] ?> km/l • Yearly Cost: <strong><?= fmtINR($bm['yearly_total']) ?>/yr</strong></p>
          </div>
        </div>
        <a class="btn-primary" href="calculator.php?id=<?= $best['id'] ?>">View Expense Breakdown →</a>
      </div>
    </div>
  </div></div>
  <script src="../js/script.js"></script>
</body>
</html>