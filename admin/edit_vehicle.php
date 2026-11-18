<?php
require_once '../db.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get vehicle details
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();

if (!$vehicle) {
    die("Vehicle not found!");
}

$message = "";

// Update vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $type = trim($_POST['type']);
    $price = (float)$_POST['price'];
    $mileage = (float)$_POST['mileage'];
    $maint = (float)$_POST['maint'];
    $ins = (float)$_POST['ins'];
    $image = trim($_POST['image']);

    $stmt = $conn->prepare(
        "UPDATE vehicles
         SET name = ?, brand = ?, type = ?, price = ?,
             mileage = ?, maint = ?, ins = ?, image = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "sssddddsi",
        $name,
        $brand,
        $type,
        $price,
        $mileage,
        $maint,
        $ins,
        $image,
        $id
    );

    if ($stmt->execute()) {
        header("Location: manage_vehicles.php");
        exit;
    } else {
        $message = "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle - VEHORA</title>
    <link rel="stylesheet" href="../css/style.css?v=3">
</head>

<body>

<div class="app-shell">
<div class="glass-frame">
<div class="admin-layout">

<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand">
            <div class="brand-icon">V</div> VEHORA
        </div>
    </div>

    <a class="side-btn" href="admin_dashboard.php">🏠 Dashboard</a>
    <a class="side-btn" href="add_vehicle.php">➕ Add Vehicle</a>
    <a class="side-btn active" href="manage_vehicles.php">🚗 Manage Vehicles</a>
    <a class="side-btn" href="manage_users.php">👥 Manage Users</a>
    <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
    <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>

</aside>

<main class="admin-main">

<div class="panel-box">

    <h3>✏️ Edit Vehicle</h3>

    <?php if ($message): ?>
        <p style="color:red;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Vehicle Name</label>
        <input type="text" name="name"
        value="<?= htmlspecialchars($vehicle['name']) ?>" required>

        <label>Brand</label>
        <input type="text" name="brand"
        value="<?= htmlspecialchars($vehicle['brand']) ?>" required>

        <label>Vehicle Type</label>
        <select name="type" required>
            <option value="car" <?= strtolower($vehicle['type']) == 'car' ? 'selected' : '' ?>>Car</option>
            <option value="bike" <?= strtolower($vehicle['type']) == 'bike' ? 'selected' : '' ?>>Bike</option>
            <option value="scooter" <?= strtolower($vehicle['type']) == 'scooter' ? 'selected' : '' ?>>Scooter</option>
        </select>

        <label>Price</label>
        <input type="number" name="price" step="0.01"
        value="<?= htmlspecialchars($vehicle['price']) ?>" required>

        <label>Mileage</label>
        <input type="number" name="mileage" step="0.1"
        value="<?= htmlspecialchars($vehicle['mileage']) ?>" required>

        <label>Maintenance</label>
        <input type="number" name="maint" step="0.01"
        value="<?= htmlspecialchars($vehicle['maint'] ?? '') ?>" required>

        <label>Insurance</label>
        <input type="number" name="ins" step="0.01"
        value="<?= htmlspecialchars($vehicle['ins']) ?>" required>

        <label>Image Filename or Path</label>
        <input type="text" name="image"
        value="<?= htmlspecialchars($vehicle['image']) ?>">

        <br><br>

        <button type="submit">Update Vehicle</button>

        <a href="manage_vehicles.php">Cancel</a>

    </form>

</div>
</main>

</div>
</div>
</div>

</body>
</html>