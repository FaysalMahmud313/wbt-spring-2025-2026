<?php
$conn = mysqli_connect('localhost', 'root', '', 'restaurant_db');
if (!$conn) die('DB Error: ' . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8mb4');

// Auto-seed default admin (admin / admin123)
$chk = mysqli_query($conn, "SELECT id FROM admins LIMIT 1");
if ($chk && mysqli_num_rows($chk) === 0) {
    $h = password_hash('admin123', PASSWORD_DEFAULT);
    $s = mysqli_prepare($conn, "INSERT INTO admins (username, password) VALUES ('admin', ?)");
    mysqli_stmt_bind_param($s, 's', $h);
    mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
}
?>
