<?php
require_once '../db.php';
require_once 'calculation.php';

$budget = isset($_GET['budget']) ? (int)$_GET['budget'] : 100000 ;
$type = $_GET['type'] ?? 'All';

// Get vehicles within budget and selected type
$extraPct = getBudgetTolerance($conn);
$maxSearch = (int)round($budget * (1 + $extraPct / 100));

if ($type === 'All') {
    $stmt = $conn->prepare(
        "SELECT * FROM vehicles WHERE price <= ? ORDER BY price ASC"
    );
    $stmt->bind_param("i", $maxSearch);
} else {
    $stmt = $conn->prepare(
        "SELECT * FROM vehicles WHERE type = ? AND price <= ? ORDER BY price ASC"
    );
    $stmt->bind_param("si", $type, $maxSearch);
}

$stmt->execute();
$candidates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Sort vehicles by yearly running cost
usort($candidates, function($a, $b) {
    return calculateVehicleMetrics($a)['yearly_total']
        <=> calculateVehicleMetrics($b)['yearly_total'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommendation - VEHORA</title>
    <link rel="stylesheet" href="../css/style.css?v=3">
</head>

<body>

<div class="app-shell">
<div class="glass-frame">

<nav class="navbar">
    <a class="brand" href="../index.php">
        <div class="brand-icon">V</div> VEHORA
    </a>

    <div class="nav-links">
        <a class="nav-link" href="../index.php">Home</a>
        <a class="nav-link" href="calculator.php">Calculator</a>
        <!-- <a class="nav-link active" href="recommendation.php">Recommendation</a> -->
    </div>
</nav>

<div class="section-pad">

    <div class="section-title">
        🏆 Vehicle Recommendations
        <br>
        <small>Budget: <?= fmtINR($budget) ?></small>
    </div>

    <?php if (empty($candidates)): ?>

        <div class="rec-banner">
            <h2>No vehicles found within your budget.</h2>
            <p>Please increase your budget to see available vehicles.</p>
        </div>

    <?php else: ?>

        <div class="section-title">
            <?= count($candidates) ?> Vehicles Found
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;">

        <?php foreach ($candidates as $index => $vehicle): ?>

            <?php $metrics = calculateVehicleMetrics($vehicle); ?>

            <div class="panel-box">

                <img
                    src="<?= htmlspecialchars($vehicle['image']) ?>"
                    style="width:100%;height:180px;object-fit:cover;border-radius:10px;"
                    onerror="this.style.display='none';"
                >

                <?php if ($index === 0): ?>
                    <h3 style="color:#34d399;">🏆 Best Recommendation</h3>
                <?php endif; ?>

                <h3><?= htmlspecialchars($vehicle['name']) ?></h3>

                <p>Brand: <?= htmlspecialchars($vehicle['brand']) ?></p>

                <p>Type: <?= htmlspecialchars($vehicle['type']) ?></p>

                <p>Price: <strong><?= fmtINR($vehicle['price']) ?></strong></p>

                <p>Mileage: <?= htmlspecialchars($vehicle['mileage']) ?> km/l</p>

                <p>
                    Yearly Cost:
                    <strong><?= fmtINR($metrics['yearly_total']) ?>/yr</strong>
                </p>

                <a class="btn-primary"
                   href="calculator.php?id=<?= (int)$vehicle['id'] ?>">
                    View Expense Breakdown →
                </a>

            </div>

        <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>
</div>
</div>

<script src="../js/script.js"></script>

</body>
</html>