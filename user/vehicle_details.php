<?php
require_once '../db.php';
require_once '../calculation/calculation.php';

// Handle Delete Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    $delStmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $delStmt->bind_param("i", $delId);
    $delStmt->execute();
    header("Location: vehicles.php");
    exit;
}

$id = (int)($_GET['id'] ?? 1);
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$v = $stmt->get_result()->fetch_assoc();
if (!$v) { header("Location: vehicles.php"); exit; }
$m = calculateVehicleMetrics($v);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($v['name']) ?> — VEHORA</title>
  <link rel="stylesheet" href="../css/style.css?v=3">
  <script src="../js/script.js"></script>
</head>
<body>
  <div class="app-shell"><div class="glass-frame">
    <?php include '../navbar.php'; ?>

    <div class="calc-grid">
      <div class="calc-preview">
        <img src="<?= htmlspecialchars($v['image']) ?>" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=fallbackSvg;" />
        <h3><?= htmlspecialchars($v['name']) ?></h3>
        <div style="color:var(--purple-light);font-weight:700;"><?= fmtINR($v['price']) ?> • <?= htmlspecialchars($v['type']) ?></div>
      </div>

      <div class="panel-box">
        <h3 style="margin-top:0;">Vehicle Specifications & Cost Overview</h3>
        <div class="spec-row"><span>Brand</span><strong><?= htmlspecialchars($v['brand']) ?></strong></div>
        <div class="spec-row"><span>Mileage</span><strong><?= $v['mileage'] ?> km/l</strong></div>
        <div class="spec-row"><span>Maintenance / Year</span><strong><?= fmtINR($v['maint']) ?></strong></div>
        <div class="spec-row"><span>Insurance / Year</span><strong><?= fmtINR($v['ins']) ?></strong></div>
        <div class="spec-row"><span>Estimated Monthly EMI</span><strong><?= fmtINR($m['emi']) ?></strong></div>
        <div class="spec-row"><span>Yearly Total Expenses</span><strong style="color:#34d399;"><?= fmtINR($m['yearly_total']) ?></strong></div>

        <div style="margin-top:18px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
          <a class="btn-primary" href="../calculation/calculator.php?id=<?= $v['id'] ?>">Open Expense Calculator</a>
          <a class="btn-primary" href="compare.php?v1=<?= $v['id'] ?>">Compare Vehicles</a>

          <form method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');" style="margin:0;">
            <input type="hidden" name="delete_id" value="<?= $v['id'] ?>" />
            <button type="submit" class="btn-primary" style="background:#ef4444;box-shadow:none;">🗑️ Delete Vehicle</button>
          </form>
        </div>
      </div>
    </div>
  </div></div>
</body>
</html>