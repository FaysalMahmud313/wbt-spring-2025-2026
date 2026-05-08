<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff — RestaurantMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app">

<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php?page=staff" class="nav-brand">
      <div class="icon">🍽️</div> RestaurantMS
    </a>
    <div class="nav-right">
      <span class="nav-user-info">👤 <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong> &mdash; Staff</span>
      <a href="index.php?page=logout" class="btn-logout">Logout</a>
    </div>
  </div>
</nav>

<div class="main">
  <div class="section-title">My Account</div>
  <p class="section-sub">Update your profile details and password</p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="profile-grid">

    <!-- Profile Details -->
    <div class="card">
      <div class="card-head"><h3>✏️ Edit Profile</h3></div>
      <div class="card-body">
        <form method="post" class="form">
          <input type="hidden" name="type" value="profile">
          <div class="field">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($staff['name']) ?>" required>
          </div>
          <div class="field">
            <label>Contact</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($staff['contact']) ?>" required>
          </div>
          <div class="field">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($staff['username']) ?>" required>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Change Password -->
    <div class="card">
      <div class="card-head"><h3>🔒 Change Password</h3></div>
      <div class="card-body">
        <form method="post" class="form">
          <input type="hidden" name="type" value="password">
          <div class="field">
            <label>Current Password</label>
            <input type="password" name="current" placeholder="Your current password" required>
          </div>
          <div class="field">
            <label>New Password</label>
            <input type="password" name="new" placeholder="Min 6 characters" required>
          </div>
          <div class="field">
            <label>Confirm New Password</label>
            <input type="password" name="confirm" placeholder="Repeat new password" required>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Change Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Menu View -->
  <div class="card" style="margin-top:8px;">
    <div class="card-head">
      <h3>🍜 Current Menu</h3>
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" id="menuSearch" class="search-input" placeholder="Search menu...">
      </div>
    </div>
    <div class="tbl-wrap">
      <table class="data-tbl" id="menuTable">
        <thead><tr>
          <th>#</th><th>Food Name</th><th>Unit</th><th class="text-right">Price (৳)</th>
        </tr></thead>
        <tbody id="menuBody">
          <tr><td colspan="4" style="text-align:center;padding:30px;color:#94a3b8;">Loading menu...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

<footer class="footer">RestaurantMS &copy; <?= date('Y') ?> — College Project</footer>

<script>
const menuBody = document.getElementById('menuBody');
let debounceTimer;

function renderMenu(items) {
  if (!items.length) {
    menuBody.innerHTML = '<tr class="empty-row"><td colspan="4">No items found</td></tr>';
    return;
  }
  menuBody.innerHTML = items.map((m, i) => `
    <tr>
      <td>${i+1}</td>
      <td>${escHtml(m.name)}</td>
      <td>${escHtml(m.unit)}</td>
      <td class="text-right">${parseFloat(m.price).toFixed(2)}</td>
    </tr>`).join('');
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function fetchMenu(q = '') {
  fetch(`index.php?page=ajax&type=menu&q=${encodeURIComponent(q)}`)
    .then(r => r.json()).then(renderMenu);
}

document.getElementById('menuSearch').addEventListener('input', function() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => fetchMenu(this.value.trim()), 200);
});

fetchMenu();
</script>
</body>
</html>
