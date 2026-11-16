<?php
require_once '../db.php';
$type = $_GET['type'] ?? 'All';
if ($type === 'All') {
    $vehicles = $conn->query("SELECT * FROM vehicles ORDER BY price ASC")->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE type = ? ORDER BY price ASC");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Vehicles — VEHORA</title><link rel="stylesheet" href="../css/style.css?v=3"></head>
<body>

  <div class="app-shell"><div class="glass-frame"><?php include '../navbar.php'; ?>
    <!-- <nav class="navbar">
      <a class="brand" href="../index.php"><div class="brand-icon">V</div> VEHORA</a>
      <div class="nav-links">
        <a class="nav-link" href="../index.php">Home</a>
        <a class="nav-link active" href="vehicles.php">Vehicles</a>
        <a class="nav-link" href="compare.php">Compare</a>
        <a class="nav-link" href="../calculation/calculator.php">Calculator</a>
        <a class="nav-link" href="about.php">About</a>
      </div>
    </nav> -->
    <div class="section-pad">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;">
        <div class="section-title" style="margin:0;">🚗 All Vehicles (<?= count($vehicles) ?>)</div>
        <form method="GET" class="field">
          <select name="type" onchange="this.form.submit()">
            <?php foreach (['All','Car','Bike','Scooter'] as $t): ?><option value="<?= $t ?>" <?= $type===$t?'selected':'' ?>><?= $t ?></option><?php endforeach; ?>
          </select>
        </form>
      </div>
      <div class="vehicle-grid">
        <?php foreach ($vehicles as $v): ?>
          <div class="v-card">
            <div class="v-img-box"><img src="<?= htmlspecialchars($v['image'] ?? ($v['image_url'] ?? '')) ?>" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=fallbackSvg;" /></div>
            <div class="v-name"><?= htmlspecialchars($v['name']) ?></div>
            <div class="v-price"><?= fmtINR($v['price']) ?></div>
            <div class="v-meta">Mileage: <?= $v['mileage'] ?> km/l • <?= htmlspecialchars($v['type']) ?></div>
            <a class="btn-primary btn-sm" href="vehicle_details.php?id=<?= $v['id'] ?>">View Details</a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div></div>
  <script src="../js/script.js"></script>
</body>
</html>