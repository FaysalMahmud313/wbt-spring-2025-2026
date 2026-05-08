<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — RestaurantMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app">

<!-- Navbar -->
<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php?page=admin" class="nav-brand">
      <div class="icon">🍽️</div> RestaurantMS
    </a>
    <div class="nav-right">
      <span class="nav-user-info">👤 <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong> &mdash; Admin</span>
      <a href="index.php?page=logout" class="btn-logout">Logout</a>
    </div>
  </div>
</nav>

<!-- Main -->
<div class="main">

  <div class="section-title">Admin Dashboard</div>
  <p class="section-sub">Manage staff, menu items, and view customers</p>

  <!-- Flash messages -->
  <?php if (!empty($_GET['msg'])): ?>
    <?php $msgs = ['added'=>'Record added!','updated'=>'Record updated!','deleted'=>'Record deleted!']; ?>
    <div class="alert alert-success"><?= $msgs[$_GET['msg']] ?? '' ?></div>
  <?php endif; ?>

  <!-- Tabs -->
  <div class="tabs">
    <a href="index.php?page=admin&section=staff"    class="tab <?= $section==='staff'    ? 'active':'' ?>">👥 Staff</a>
    <a href="index.php?page=admin&section=menu"     class="tab <?= $section==='menu'     ? 'active':'' ?>">🍜 Menu</a>
    <a href="index.php?page=admin&section=customers" class="tab <?= $section==='customers'? 'active':'' ?>">🙋 Customers</a>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- ============ STAFF SECTION ============ -->
  <?php if ($section === 'staff'): ?>

    <!-- Add / Edit Form -->
    <div class="card">
      <div class="card-head">
        <h3><?= $editing ? '✏️ Edit Staff' : '➕ Add Staff' ?></h3>
        <?php if ($editing): ?><a href="index.php?page=admin&section=staff" class="btn btn-outline" style="padding:6px 14px;font-size:13px;">Cancel</a><?php endif; ?>
      </div>
      <div class="card-body">
        <?php
          $act  = $editing ? "index.php?page=admin&section=staff&action=update&id={$editing['id']}" : "index.php?page=admin&section=staff&action=add";
          $vals = $editing ?? ['name'=>'','contact'=>'','username'=>''];
        ?>
        <form method="post" action="<?= $act ?>" class="form">
          <div class="field-row">
            <div class="field">
              <label>Full Name</label>
              <input type="text" name="name" value="<?= htmlspecialchars($vals['name']) ?>" placeholder="Staff name" required>
            </div>
            <div class="field">
              <label>Contact</label>
              <input type="text" name="contact" value="<?= htmlspecialchars($vals['contact']) ?>" placeholder="Phone / email" required>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label>Username</label>
              <input type="text" name="username" value="<?= htmlspecialchars($vals['username']) ?>" placeholder="Login username" required>
            </div>
            <?php if (!$editing): ?>
            <div class="field">
              <label>Password</label>
              <input type="password" name="password" placeholder="Min 6 chars" required>
            </div>
            <?php endif; ?>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Staff' : 'Add Staff' ?></button>
          </div>
        </form>
      </div>
    </div>

    <!-- Staff List -->
    <div class="card">
      <div class="card-head">
        <h3>Staff List</h3>
        <span class="count-badge"><?= count($staffList) ?> members</span>
      </div>
      <div class="tbl-wrap">
        <table class="data-tbl">
          <thead><tr>
            <th>#</th><th>Name</th><th>Contact</th><th>Username</th><th>Actions</th>
          </tr></thead>
          <tbody>
          <?php if (empty($staffList)): ?>
            <tr class="empty-row"><td colspan="5">No staff added yet</td></tr>
          <?php else: $i=1; foreach ($staffList as $s): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($s['name']) ?></td>
              <td><?= htmlspecialchars($s['contact']) ?></td>
              <td><?= htmlspecialchars($s['username']) ?></td>
              <td>
                <a href="index.php?page=admin&section=staff&action=edit&id=<?= $s['id'] ?>" class="act act-edit">Edit</a>
                <a href="index.php?page=admin&section=staff&action=delete&id=<?= $s['id'] ?>"
                   class="act act-del" onclick="return confirm('Delete this staff?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  <!-- ============ MENU SECTION ============ -->
  <?php elseif ($section === 'menu'): ?>

    <!-- Add / Edit Form -->
    <div class="card">
      <div class="card-head">
        <h3><?= $editing ? '✏️ Edit Menu Item' : '➕ Add Menu Item' ?></h3>
        <?php if ($editing): ?><a href="index.php?page=admin&section=menu" class="btn btn-outline" style="padding:6px 14px;font-size:13px;">Cancel</a><?php endif; ?>
      </div>
      <div class="card-body">
        <?php
          $act  = $editing ? "index.php?page=admin&section=menu&action=update&id={$editing['id']}" : "index.php?page=admin&section=menu&action=add";
          $vals = $editing ?? ['name'=>'','unit'=>'','price'=>''];
        ?>
        <form method="post" action="<?= $act ?>" class="form">
          <div class="field-row">
            <div class="field">
              <label>Food Name</label>
              <input type="text" name="name" value="<?= htmlspecialchars($vals['name']) ?>" placeholder="e.g. Chicken Burger" required>
            </div>
            <div class="field">
              <label>Unit</label>
              <input type="text" name="unit" value="<?= htmlspecialchars($vals['unit']) ?>" placeholder="e.g. plate, piece, cup" required>
            </div>
          </div>
          <div class="field" style="max-width:200px;">
            <label>Price (৳)</label>
            <input type="number" name="price" value="<?= htmlspecialchars($vals['price']) ?>" placeholder="0.00" step="0.01" min="0" required>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Item' : 'Add Item' ?></button>
          </div>
        </form>
      </div>
    </div>

    <!-- Menu List -->
    <div class="card">
      <div class="card-head">
        <h3>Menu Items</h3>
        <span class="count-badge"><?= count($menuItems) ?> items</span>
      </div>
      <div class="tbl-wrap">
        <table class="data-tbl">
          <thead><tr>
            <th>#</th><th>Food Name</th><th>Unit</th><th class="text-right">Price (৳)</th><th>Actions</th>
          </tr></thead>
          <tbody>
          <?php if (empty($menuItems)): ?>
            <tr class="empty-row"><td colspan="5">No menu items yet</td></tr>
          <?php else: $i=1; foreach ($menuItems as $m): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td><?= htmlspecialchars($m['unit']) ?></td>
              <td class="text-right"><?= number_format($m['price'], 2) ?></td>
              <td>
                <a href="index.php?page=admin&section=menu&action=edit&id=<?= $m['id'] ?>" class="act act-edit">Edit</a>
                <a href="index.php?page=admin&section=menu&action=delete&id=<?= $m['id'] ?>"
                   class="act act-del" onclick="return confirm('Delete this item?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  <!-- ============ CUSTOMERS SECTION ============ -->
  <?php elseif ($section === 'customers'): ?>
    <div class="card">
      <div class="card-head">
        <h3>Registered Customers</h3>
        <span class="count-badge"><?= count($customers) ?> customers</span>
      </div>
      <div class="tbl-wrap">
        <table class="data-tbl">
          <thead><tr>
            <th>#</th><th>Name</th><th>Contact</th><th>Username</th>
          </tr></thead>
          <tbody>
          <?php if (empty($customers)): ?>
            <tr class="empty-row"><td colspan="4">No customers registered yet</td></tr>
          <?php else: $i=1; foreach ($customers as $c): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($c['name']) ?></td>
              <td><?= htmlspecialchars($c['contact']) ?></td>
              <td><?= htmlspecialchars($c['username']) ?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div><!-- /main -->

<footer class="footer">RestaurantMS &copy; <?= date('Y') ?> — College Project</footer>
</body>
</html>
