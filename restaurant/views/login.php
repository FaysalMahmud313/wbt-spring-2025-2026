<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — RestaurantMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">

    <div class="auth-logo">
      <div class="icon">🍽️</div>
      <span>RestaurantMS</span>
    </div>

    <h2>Welcome Back</h2>
    <p class="sub">Sign in to your account to continue</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
      <div class="alert alert-success">Account created! You can now log in.</div>
    <?php endif; ?>

    <form method="post" class="form">
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter username" required autofocus>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <p class="auth-foot">New customer? <a href="index.php?page=register">Create account</a></p>
    <div class="hint">🔑 Default admin: <strong>admin</strong> / <strong>admin123</strong></div>
  </div>
</div>
</body>
</html>
