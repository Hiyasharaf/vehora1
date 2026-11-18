<?php
require_once '../db.php';
requireAdmin();

$message = '';

// Delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {

    $user_id = (int)$_POST['user_id'];

    // Prevent deleting the currently logged-in account
    if ($user_id === (int)$_SESSION['user_id']) {
        $message = "You cannot delete your own account.";
    } else {

        // Delete normal users only
        $stmt = $conn->prepare(
            "DELETE FROM users WHERE id = ? AND role = 'user'"
        );

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "User deleted successfully.";
        } else {
            $message = "User not found or cannot be deleted.";
        }

        $stmt->close();
    }
}

// Get users in correct ID order
$users = $conn->query(
    "SELECT id, name, email, role, created_at
     FROM users
     ORDER BY id ASC"
)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users — VEHORA</title>
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
        <a class="side-btn" href="manage_vehicles.php">🚗 Manage Vehicles</a>
        <a class="side-btn active" href="manage_users.php">👥 Manage Users</a>
        <a class="side-btn" href="budget_settings.php">⚙️ Budget Settings</a>
        <a class="side-btn" style="margin-top:auto;color:#fca5a5;" href="logout.php">🚪 Logout</a>

    </aside>

    <main class="admin-main">

        <div class="panel-box">

            <h3 style="margin-top:0;">👥 Registered Users</h3>

            <?php if ($message): ?>
                <p style="color:var(--purple-light);">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($users) > 0): ?>
                      <?php $i = 1; ?>

                        <?php foreach ($users as $u): ?>

                            <tr>

                                <td><?= $i++ ?></td>

                                <td>
                                    <?= htmlspecialchars($u['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($u['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($u['role']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($u['created_at'] ?? '') ?>
                                </td>

                                <td>

                                    <?php if ($u['role'] === 'admin'): ?>

                                        <span>Admin</span>

                                    <?php else: ?>

                                        <form method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">

                                            <input type="hidden"
                                                name="user_id"
                                                value="<?= (int)$u['id'] ?>">

                                            <button type="submit"
                                                name="delete_user"
                                                class="btn-primary btn-sm"
                                                style="background:#dc2626;">

                                                Delete

                                            </button>

                                        </form>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">No users found.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>
</div>
</div>

</body>
</html>