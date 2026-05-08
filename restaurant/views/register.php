<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up — RestaurantMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">

    <div class="auth-logo">
      <div class="icon">🍽️</div>
      <span>RestaurantMS</span>
    </div>

    <h2>Create Account</h2>
    <p class="sub">Sign up as a customer</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" class="form">
      <div class="field-row">
        <div class="field">
          <label>Full Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($old['name']) ?>" placeholder="Your name" required>
        </div>
        <div class="field">
          <label>Contact</label>
          <input type="text" name="contact" value="<?= htmlspecialchars($old['contact']) ?>" placeholder="Phone / email" required>
        </div>
      </div>
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($old['username']) ?>" placeholder="Choose a username" required>
      </div>
      <div class="field-row">
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Min 6 chars" required>
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input type="password" name="confirm" placeholder="Repeat password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    </form>

    <p class="auth-foot">Already have an account? <a href="index.php?page=login">Sign in</a></p>
  </div>
</div>
</body>
</html>
