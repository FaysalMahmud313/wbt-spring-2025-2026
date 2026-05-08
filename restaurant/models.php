<?php
// ============================================================
// MODELS — all DB access via procedural mysqli + prepared stmts
// ============================================================

/* -------- Admin -------- */
function authAdmin($conn, $u, $p) {
    $s = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($s, 's', $u);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return ($row && password_verify($p, $row['password'])) ? $row : false;
}

/* -------- Staff -------- */
function authStaff($conn, $u, $p) {
    $s = mysqli_prepare($conn, "SELECT id, name, username, password FROM staff WHERE username = ?");
    mysqli_stmt_bind_param($s, 's', $u);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return ($row && password_verify($p, $row['password'])) ? $row : false;
}

function getStaffList($conn) {
    $r = mysqli_query($conn, "SELECT id, name, contact, username FROM staff ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getStaffById($conn, $id) {
    $s = mysqli_prepare($conn, "SELECT id, name, contact, username FROM staff WHERE id = ?");
    mysqli_stmt_bind_param($s, 'i', $id);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return $row;
}

function getStaffPassword($conn, $id) {
    $s = mysqli_prepare($conn, "SELECT password FROM staff WHERE id = ?");
    mysqli_stmt_bind_param($s, 'i', $id);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return $row ? $row['password'] : '';
}

function addStaff($conn, $name, $contact, $username, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $s = mysqli_prepare($conn, "INSERT INTO staff (name, contact, username, password) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($s, 'ssss', $name, $contact, $username, $hash);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function updateStaff($conn, $id, $name, $contact, $username) {
    $s = mysqli_prepare($conn, "UPDATE staff SET name=?, contact=?, username=? WHERE id=?");
    mysqli_stmt_bind_param($s, 'sssi', $name, $contact, $username, $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function updateStaffPassword($conn, $id, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $s = mysqli_prepare($conn, "UPDATE staff SET password=? WHERE id=?");
    mysqli_stmt_bind_param($s, 'si', $hash, $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function deleteStaff($conn, $id) {
    $s = mysqli_prepare($conn, "DELETE FROM staff WHERE id=?");
    mysqli_stmt_bind_param($s, 'i', $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function staffUsernameExists($conn, $username, $excludeId = null) {
    if ($excludeId) {
        $s = mysqli_prepare($conn, "SELECT id FROM staff WHERE username=? AND id!=?");
        mysqli_stmt_bind_param($s, 'si', $username, $excludeId);
    } else {
        $s = mysqli_prepare($conn, "SELECT id FROM staff WHERE username=?");
        mysqli_stmt_bind_param($s, 's', $username);
    }
    mysqli_stmt_execute($s);
    mysqli_stmt_store_result($s);
    $exists = mysqli_stmt_num_rows($s) > 0;
    mysqli_stmt_close($s);
    return $exists;
}

/* -------- Customer -------- */
function authCustomer($conn, $u, $p) {
    $s = mysqli_prepare($conn, "SELECT id, name, username, password FROM customers WHERE username=?");
    mysqli_stmt_bind_param($s, 's', $u);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return ($row && password_verify($p, $row['password'])) ? $row : false;
}

function getCustomers($conn) {
    $r = mysqli_query($conn, "SELECT id, name, contact, username FROM customers ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getCustomerById($conn, $id) {
    $s = mysqli_prepare($conn, "SELECT id, name, contact, username FROM customers WHERE id=?");
    mysqli_stmt_bind_param($s, 'i', $id);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return $row;
}

function addCustomer($conn, $name, $contact, $username, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $s = mysqli_prepare($conn, "INSERT INTO customers (name, contact, username, password) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($s, 'ssss', $name, $contact, $username, $hash);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function updateCustomer($conn, $id, $name, $contact, $username) {
    $s = mysqli_prepare($conn, "UPDATE customers SET name=?, contact=?, username=? WHERE id=?");
    mysqli_stmt_bind_param($s, 'sssi', $name, $contact, $username, $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function customerUsernameExists($conn, $username, $excludeId = null) {
    if ($excludeId) {
        $s = mysqli_prepare($conn, "SELECT id FROM customers WHERE username=? AND id!=?");
        mysqli_stmt_bind_param($s, 'si', $username, $excludeId);
    } else {
        $s = mysqli_prepare($conn, "SELECT id FROM customers WHERE username=?");
        mysqli_stmt_bind_param($s, 's', $username);
    }
    mysqli_stmt_execute($s);
    mysqli_stmt_store_result($s);
    $exists = mysqli_stmt_num_rows($s) > 0;
    mysqli_stmt_close($s);
    return $exists;
}

/* -------- Menu -------- */
function getMenuItems($conn) {
    $r = mysqli_query($conn, "SELECT id, name, unit, price FROM menu_items ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getMenuItem($conn, $id) {
    $s = mysqli_prepare($conn, "SELECT id, name, unit, price FROM menu_items WHERE id=?");
    mysqli_stmt_bind_param($s, 'i', $id);
    mysqli_stmt_execute($s);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return $row;
}

function addMenuItem($conn, $name, $unit, $price) {
    $s = mysqli_prepare($conn, "INSERT INTO menu_items (name, unit, price) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($s, 'ssd', $name, $unit, $price);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function updateMenuItem($conn, $id, $name, $unit, $price) {
    $s = mysqli_prepare($conn, "UPDATE menu_items SET name=?, unit=?, price=? WHERE id=?");
    mysqli_stmt_bind_param($s, 'ssdi', $name, $unit, $price, $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function deleteMenuItem($conn, $id) {
    $s = mysqli_prepare($conn, "DELETE FROM menu_items WHERE id=?");
    mysqli_stmt_bind_param($s, 'i', $id);
    $ok = mysqli_stmt_execute($s);
    mysqli_stmt_close($s);
    return $ok;
}

function searchMenuItems($conn, $term) {
    $like = '%' . $term . '%';
    $s = mysqli_prepare($conn, "SELECT id, name, unit, price FROM menu_items WHERE name LIKE ? OR unit LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($s, 'ss', $like, $like);
    mysqli_stmt_execute($s);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($s), MYSQLI_ASSOC);
    mysqli_stmt_close($s);
    return $rows;
}
?>
