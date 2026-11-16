<?php
require_once '../db.php';
requireAdmin();
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $brand   = trim($_POST['brand']);
    $type    = $_POST['type'];
    $price   = (int)$_POST['price'];
    $mileage = (float)$_POST['mileage'];
    $maint   = (int)($_POST['maint'] ?: 2500);
    $ins     = (int)($_POST['ins'] ?: 4500);
    $image   = trim($_POST['image']);

    try {
        $stmt = $conn->prepare("INSERT INTO vehicles (name, brand, type, price, mileage, maint, ins, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidiis", $name, $brand, $type, $price, $mileage, $maint, $ins, $image);
        if ($stmt->execute()) {
            $msg = "Vehicle added to VEHORA!";
        }
    } catch (Exception $e) {
        $err = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Vehicle — VEHORA</title>
  <link rel="stylesheet" href="../css/style.css?v=3">
</head>
<body>


  <div class="app-shell"><div class="glass-frame"> <?php include '../navbar.php'; ?><div class="admin-layout">
    <aside class="sidebar">
      <div><div class="brand">
        <div></div> </div></div>
      <a class="side-btn" href="admin_dashboard.php">🏠 Dashboard</a>
      <a class="side-btn active" href="add_vehicle.php">➕ Add Vehicle</a>
      <a class="side-btn" href="manage_vehicles.php">🚗 Manage Vehicles</a>
      <a class="side-btn" href="manage_users.php">👥 Manage Users</a>
      <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
      <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>
    </aside>
    <main class="admin-main">
      <div class="panel-box">
        <h3 style="margin-top:0;">➕ Add New Vehicle</h3>
        <?php if ($msg): ?><p class="msg-ok"><?= htmlspecialchars($msg) ?> <a href="manage_vehicles.php" style="color:#fff;">View List →</a></p><?php endif; ?>
        <?php if ($err): ?><p class="msg-err"><?= htmlspecialchars($err) ?></p><?php endif; ?>
        <form method="POST" class="form-grid">
          <div class="field"><label>Vehicle Name</label><input name="name" required /></div>
          <div class="field"><label>Brand</label><input name="brand" required /></div>
          <div class="field"><label>Type</label><select name="type"><option value="Bike">Bike</option><option value="Scooter">Scooter</option><option value="Car">Car</option></select></div>
          <div class="field"><label>Price (₹)</label><input name="price" type="number" required /></div>
          <div class="field"><label>Mileage (km/l)</label><input name="mileage" type="number" step="0.1" required /></div>
          <div class="field"><label>Maintenance / Year (₹)</label><input name="maint" type="number" value="2500" /></div>
          <div class="field"><label>Insurance / Year (₹)</label><input name="ins" type="number" value="4500" /></div>
          <div class="field"><label>Image URL</label><input name="image" placeholder="https://... or /vehora1/images/car.jpg" /></div>
          <div style="grid-column:1/-1;"><button type="submit" class="btn-primary">Save Vehicle</butt