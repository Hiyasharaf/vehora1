<?php $cur = $_SERVER['PHP_SELF']; ?>
<div class="module-switcher">
  <span class="switch-label">VEHORA Modules:</span>

  <a class="mod-btn <?= (strpos($cur, '/calculation/') === false && strpos($cur, '/admin/') === false) ? 'active' : '' ?>"
     href="/vehora1/index.php">Person 1 — User Module</a>

  <a class="mod-btn <?= strpos($cur, '/calculation/') !== false ? 'active' : '' ?>"
     href="/vehora1/calculation/calculator.php">Person 2 — Calculation Module</a>

  <a class="mod-btn <?= strpos($cur, '/admin/') !== false ? 'active' : '' ?>"
     href="/vehora1/admin/admin_dashboard.php">Person 3 — Admin Module</a>

  <span style="margin-left:auto; display:flex; align-items:center; gap:8px;">
    <?php if (isset($_SESSION['user_id'])): ?>
      <span class="user-pill">👤 <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <a class="mod-btn" style="border-color:#ef4444;color:#fca5a5;" href="/vehora1/admin/logout.php">Logout</a>
    <?php else: ?>
      <a class="mod-btn" href="/vehora1/login.php">👤 Login</a>
    <?php endif; ?>
  </span>
</div>