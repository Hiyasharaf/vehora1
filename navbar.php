<?php $cur = $_SERVER['PHP_SELF']; ?>
<nav class="navbar">
  <a class="brand" href="/vehora1/index.php">
    <div class="brand-icon">V</div> VEHORA
  </a>

  <div class="nav-links">
    <a class="nav-link <?= basename($cur) === 'index.php' ? 'active' : '' ?>" href="/vehora1/index.php">Home</a>
    <a class="nav-link <?= strpos($cur, '/user/vehicles') !== false || strpos($cur, 'vehicle_details') !== false ? 'active' : '' ?>" href="/vehora1/user/vehicles.php">Vehicles</a>
    <a class="nav-link <?= strpos($cur, 'compare.php') !== false ? 'active' : '' ?>" href="/vehora1/user/compare.php">Compare</a>
    <a class="nav-link <?= strpos($cur, '/calculation/') !== false ? 'active' : '' ?>" href="/vehora1/calculation/calculator.php">Calculator</a>
    <a class="nav-link <?= strpos($cur, 'about.php') !== false ? 'active' : '' ?>" href="/vehora1/user/about.php">About</a>

    <!-- Modules Dropdown -->
    <div class="nav-dropdown" id="modDropdown">
      <button type="button" class="nav-link drop-btn <?= strpos($cur, '/admin/') !== false ? 'active' : '' ?>" onclick="toggleModMenu(event)">
        Modules ▾
      </button>
      <div class="drop-menu" id="modMenu">
        <a href="/vehora1/index.php">👤 Person 1 — User Module</a>
        
        <a href="/vehora1/admin/admin_dashboard.php">🛡️ Person 2 — Admin Module</a>
      </div>
    </div>
  </div>
 <a class="user-pill" href="admin/logout.php">👤 <?= htmlspecialchars($_SESSION['user_name']) ?> (Logout)</a>

  <!-- <a class="user-pill" href="login.php">👤 Login</a> -->
</nav>

<script>
function toggleModMenu(e) {
  e.stopPropagation();
  document.getElementById('modMenu').classList.toggle('show');
}
document.addEventListener('click', function() {
  const m = document.getElementById('modMenu');
  if (m) m.classList.remove('show');
});
</script>