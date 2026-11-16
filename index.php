

<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$budget = isset($_GET['budget']) ? (int)$_GET['budget'] : 100000;
$type   = $_GET['type'] ?? 'All';
$extraPct = getBudgetTolerance($conn);
$maxSearch = (int)round($budget * (1 + $extraPct / 100));

if ($type === 'All') {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE price <= ? ORDER BY price ASC");
    $stmt->bind_param("i", $maxSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE type = ? AND price <= ? ORDER BY price ASC");
    $stmt->bind_param("si", $type, $maxSearch);
}
$stmt->execute();
$vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VEHORA — Find Your Perfect Vehicle</title>
  <link rel="stylesheet" href="css/style.css?v=3" />
</head>
<body>

<div class="app-shell">
    <div class="glass-frame">
     
        <!-- <div class="nav-links">
          <a class="nav-link active" href="index.php">Home</a>
          <a class="nav-link" href="user/vehicles.php">Vehicles</a>
          <a class="nav-link" href="user/compare.php">Compare</a>
          <a class="nav-link" href="calculation/calculator.php">Calculator</a>
          <a class="nav-link" href="user/about.php">About</a>
        </div> -->
        <?php include 'navbar.php'; ?>
       <?php if (isset($_SESSION['user_id'])): ?>
  
<?php else: ?>
  <!-- <a class="user-pill" href="login.php">👤 Login</a> -->
<?php endif; ?>
      

      <div class="hero">
        <div>
          <h1>Find Your Perfect Vehicle</h1>
          <p class="sub">Smart Choice. Better Tomorrow.</p>

          <form class="search-bar" method="GET" action="index.php">
            <div class="field">
              <label>Your Budget (₹)</label>
              <input type="number" name="budget" value="<?= $budget ?>" min="20000" step="5000" />
            </div>
            <div class="field">
              <label>Vehicle Type</label>
              <select name="type">
                <?php foreach (['All' => 'All Vehicles', 'Car' => 'Cars', 'Bike' => 'Bikes', 'Scooter' => 'Scooters'] as $k => $label): ?>
                  <option value="<?= $k ?>" <?= $type === $k ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn-primary">Find Vehicles</button>
          </form>
          
          <div class="max-price-note">Max Search Price: <span><?= fmtINR($maxSearch) ?></span> (<?= $extraPct ?>% extra)</div>
        </div>

        <div style="text-align:center;">
          <svg viewBox="0 0 420 190" width="100%" style="max-width:390px;filter:drop-shadow(0 12px 24px rgba(124,58,237,0.4));">
            <defs><linearGradient id="carBody" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#312e81"/><stop offset="50%" stop-color="#1e1b4b"/><stop offset="100%" stop-color="#090718"/></linearGradient></defs>
            <ellipse cx="210" cy="162" rx="175" ry="12" fill="rgba(124,58,237,0.28)"/>
            <path d="M38 132 C42 108 75 94 115 90 L165 56 C198 46 262 46 302 68 L355 96 C382 102 396 116 394 134 L375 144 L52 144 Z" fill="url(#carBody)" stroke="#8b5cf6" stroke-width="1.5"/>
            <path d="M132 88 L172 62 C202 54 252 54 288 72 L318 88 Z" fill="#0f172a" stroke="#6366f1" stroke-width="1"/>
            <path d="M352 108 L382 114 L374 124 L348 118 Z" fill="#a78bfa"/>
            <circle cx="108" cy="140" r="26" fill="#090718" stroke="#6366f1" stroke-width="3"/><circle cx="108" cy="140" r="13" fill="#312e81"/>
            <circle cx="322" cy="140" r="26" fill="#090718" stroke="#6366f1" stroke-width="3"/><circle cx="322" cy="140" r="13" fill="#312e81"/>
          </svg>
        </div>
      </div>

      <div class="section-pad">
        <div class="section-title">🚗 Available Vehicles (<?= count($vehicles) ?>)</div>
        <div class="vehicle-grid">
          <?php if (empty($vehicles)): ?>
            <div class="panel-box" style="grid-column:1/-1;">No vehicles found under <?= fmtINR($maxSearch) ?>. Increase your budget or change Vehicle Type.</div>
          <?php else: foreach ($vehicles as $v): $within = $v['price'] <= $budget; ?>
            <div class="v-card">
              <div class="v-img-box"><img src="<?= htmlspecialchars($v['image'] ?? ($v['image_url'] ?? '')) ?>" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src=fallbackSvg;" /></div>
              <div class="v-name"><?= htmlspecialchars($v['name']) ?></div>
              <div class="v-price-row">
                <span class="v-price"><?= fmtINR($v['price']) ?></span>
                <span class="badge <?= $within ? 'badge-green' : 'badge-amber' ?>"><?= $within ? 'Within Budget' : 'Slightly Above' ?></span>
              </div>
              <div class="v-meta">Mileage: <?= $v['mileage'] ?> km/l • <?= htmlspecialchars($v['type']) ?></div>
              <a class="btn-primary btn-sm" href="calculation/calculator.php?id=<?= $v['id'] ?>&budget=<?= $budget ?>">View Details</a>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="js/script.js"></script>
</body>
</html>