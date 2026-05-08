<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu — RestaurantMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app">

<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php?page=customer" class="nav-brand">
      <div class="icon">🍽️</div> RestaurantMS
    </a>
    <div class="nav-right">
      <span class="nav-user-info">👤 <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong></span>
      <a href="index.php?page=logout" class="btn-logout">Logout</a>
    </div>
  </div>
</nav>

<div class="main">

  <!-- Menu Section -->
  <div class="section-title">🍜 Our Menu</div>
  <p class="section-sub">Browse and search all available food items</p>

  <div class="card">
    <div class="card-head">
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" id="menuSearch" class="search-input" placeholder="Search by name or unit...">
      </div>
      <span class="count-badge" id="itemCount">Loading...</span>
    </div>

    <!-- Card grid view -->
    <div style="padding:16px;">
      <div class="menu-grid" id="menuGrid"></div>
      <div id="no-results">No items match your search</div>
    </div>
  </div>

  <!-- Profile Section -->
  <div class="section-title" style="margin-top:8px;">My Profile</div>
  <p class="section-sub">Update your account details</p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:500px;">
    <div class="card-head"><h3>✏️ Edit Profile</h3></div>
    <div class="card-body">
      <form method="post" class="form">
        <div class="field">
          <label>Full Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
        </div>
        <div class="field">
          <label>Contact</label>
          <input type="text" name="contact" value="<?= htmlspecialchars($customer['contact']) ?>" required>
        </div>
        <div class="field">
          <label>Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($customer['username']) ?>" required>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

</div>

<footer class="footer">RestaurantMS &copy; <?= date('Y') ?> — College Project</footer>

<script>
const grid     = document.getElementById('menuGrid');
const noRes    = document.getElementById('no-results');
const countBadge = document.getElementById('itemCount');
let debounceTimer;

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function renderCards(items) {
  countBadge.textContent = items.length + ' item' + (items.length !== 1 ? 's' : '');
  if (!items.length) {
    grid.innerHTML = '';
    noRes.style.display = 'block';
    return;
  }
  noRes.style.display = 'none';
  grid.innerHTML = items.map(m => `
    <div class="menu-card">
      <div class="food-name">${escHtml(m.name)}</div>
      <div class="food-unit">per ${escHtml(m.unit)}</div>
      <div class="food-price">৳${parseFloat(m.price).toFixed(2)} <span>/ ${escHtml(m.unit)}</span></div>
    </div>`).join('');
}

function fetchMenu(q = '') {
  fetch(`index.php?page=ajax&type=menu&q=${encodeURIComponent(q)}`)
    .then(r => r.json())
    .then(renderCards)
    .catch(() => { grid.innerHTML = '<p style="padding:20px;color:#ef4444;">Failed to load menu.</p>'; });
}

document.getElementById('menuSearch').addEventListener('input', function() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    // Live word-by-word matching — filter locally after full load
    const q = this.value.trim();
    fetchMenu(q);
  }, 150);
});

// Initial load
fetchMenu();
</script>
</body>
</html>
