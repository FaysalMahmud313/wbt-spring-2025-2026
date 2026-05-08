<?php
// ============================================================
// CONTROLLERS
// ============================================================

/* -------- Login -------- */
function loginCtrl($conn) {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        if ($u === '' || $p === '') {
            $error = 'Please fill in both fields.';
        } else {
            if ($admin = authAdmin($conn, $u, $p)) {
                $_SESSION['user'] = ['id' => $admin['id'], 'username' => $admin['username'], 'name' => 'Admin', 'role' => 'admin'];
                header('Location: index.php?page=admin'); exit;
            }
            if ($staff = authStaff($conn, $u, $p)) {
                $_SESSION['user'] = ['id' => $staff['id'], 'username' => $staff['username'], 'name' => $staff['name'], 'role' => 'staff'];
                header('Location: index.php?page=staff'); exit;
            }
            if ($cust = authCustomer($conn, $u, $p)) {
                $_SESSION['user'] = ['id' => $cust['id'], 'username' => $cust['username'], 'name' => $cust['name'], 'role' => 'customer'];
                header('Location: index.php?page=customer'); exit;
            }
            $error = 'Invalid username or password.';
        }
    }
    require 'views/login.php';
}

/* -------- Register (customer signup) -------- */
function registerCtrl($conn) {
    $error = $success = '';
    $old = ['name' => '', 'contact' => '', 'username' => ''];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name    = trim($_POST['name'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';
        $old = compact('name', 'contact', 'username');

        if (!$name || !$contact || !$username || !$password)
            $error = 'All fields are required.';
        elseif (strlen($password) < 6)
            $error = 'Password must be at least 6 characters.';
        elseif ($password !== $confirm)
            $error = 'Passwords do not match.';
        elseif (customerUsernameExists($conn, $username))
            $error = 'Username is already taken.';
        else {
            if (addCustomer($conn, $name, $contact, $username, $password)) {
                $success = 'Account created! You can now log in.';
                $old = ['name' => '', 'contact' => '', 'username' => ''];
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }
    require 'views/register.php';
}

/* -------- Admin Dashboard -------- */
function adminCtrl($conn) {
    $section = $_GET['section'] ?? 'staff';
    $action  = $_GET['action'] ?? 'list';
    $error   = '';
    $editing = null;
    $staffList = $customers = $menuItems = [];

    /* ---- Staff section ---- */
    if ($section === 'staff') {
        if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$name || !$contact || !$username || !$password)
                $error = 'All fields are required.';
            elseif (strlen($password) < 6)
                $error = 'Password min 6 characters.';
            elseif (staffUsernameExists($conn, $username))
                $error = 'Username already taken.';
            else {
                if (addStaff($conn, $name, $contact, $username, $password)) {
                    header('Location: index.php?page=admin&section=staff&msg=added'); exit;
                }
                $error = 'Failed to add staff.';
            }
        }
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id      = intval($_GET['id'] ?? 0);
            $name    = trim($_POST['name'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            $username = trim($_POST['username'] ?? '');
            if (!$name || !$contact || !$username) {
                $error = 'All fields are required.';
                $editing = compact('id', 'name', 'contact', 'username');
            } elseif (staffUsernameExists($conn, $username, $id)) {
                $error = 'Username taken by another staff.';
                $editing = compact('id', 'name', 'contact', 'username');
            } else {
                if (updateStaff($conn, $id, $name, $contact, $username)) {
                    header('Location: index.php?page=admin&section=staff&msg=updated'); exit;
                }
                $error = 'Update failed.';
                $editing = compact('id', 'name', 'contact', 'username');
            }
        }
        if ($action === 'edit' && !$editing) {
            $editing = getStaffById($conn, intval($_GET['id'] ?? 0));
        }
        if ($action === 'delete') {
            deleteStaff($conn, intval($_GET['id'] ?? 0));
            header('Location: index.php?page=admin&section=staff&msg=deleted'); exit;
        }
        $staffList = getStaffList($conn);
    }

    /* ---- Menu section ---- */
    if ($section === 'menu') {
        if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $unit  = trim($_POST['unit'] ?? '');
            $price = trim($_POST['price'] ?? '');
            if (!$name || !$unit || $price === '')
                $error = 'All fields are required.';
            elseif (!is_numeric($price) || floatval($price) < 0)
                $error = 'Price must be a positive number.';
            else {
                if (addMenuItem($conn, $name, $unit, floatval($price))) {
                    header('Location: index.php?page=admin&section=menu&msg=added'); exit;
                }
                $error = 'Failed to add item.';
            }
        }
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id    = intval($_GET['id'] ?? 0);
            $name  = trim($_POST['name'] ?? '');
            $unit  = trim($_POST['unit'] ?? '');
            $price = trim($_POST['price'] ?? '');
            if (!$name || !$unit || $price === '') {
                $error = 'All fields are required.';
                $editing = compact('id', 'name', 'unit', 'price');
            } elseif (!is_numeric($price) || floatval($price) < 0) {
                $error = 'Price must be a positive number.';
                $editing = compact('id', 'name', 'unit', 'price');
            } else {
                if (updateMenuItem($conn, $id, $name, $unit, floatval($price))) {
                    header('Location: index.php?page=admin&section=menu&msg=updated'); exit;
                }
                $error = 'Update failed.';
                $editing = compact('id', 'name', 'unit', 'price');
            }
        }
        if ($action === 'edit' && !$editing) {
            $editing = getMenuItem($conn, intval($_GET['id'] ?? 0));
        }
        if ($action === 'delete') {
            deleteMenuItem($conn, intval($_GET['id'] ?? 0));
            header('Location: index.php?page=admin&section=menu&msg=deleted'); exit;
        }
        $menuItems = getMenuItems($conn);
    }

    /* ---- Customers section ---- */
    if ($section === 'customers') {
        $customers = getCustomers($conn);
    }

    require 'views/admin.php';
}

/* -------- Staff Dashboard -------- */
function staffCtrl($conn) {
    $id      = $_SESSION['user']['id'];
    $error   = $success = '';
    $staff   = getStaffById($conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['type'] ?? '';

        if ($type === 'profile') {
            $name    = trim($_POST['name'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            $username = trim($_POST['username'] ?? '');
            if (!$name || !$contact || !$username)
                $error = 'All fields are required.';
            elseif (staffUsernameExists($conn, $username, $id))
                $error = 'Username already taken.';
            else {
                if (updateStaff($conn, $id, $name, $contact, $username)) {
                    $_SESSION['user']['name']     = $name;
                    $_SESSION['user']['username'] = $username;
                    $success = 'Profile updated successfully!';
                    $staff   = getStaffById($conn, $id);
                } else {
                    $error = 'Update failed.';
                }
            }
        }

        if ($type === 'password') {
            $current = $_POST['current'] ?? '';
            $new     = $_POST['new'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            $hash    = getStaffPassword($conn, $id);
            if (!password_verify($current, $hash))
                $error = 'Current password is incorrect.';
            elseif (strlen($new) < 6)
                $error = 'New password min 6 characters.';
            elseif ($new !== $confirm)
                $error = 'New passwords do not match.';
            else {
                if (updateStaffPassword($conn, $id, $new))
                    $success = 'Password changed successfully!';
                else
                    $error = 'Failed to update password.';
            }
        }
    }

    require 'views/staff.php';
}

/* -------- Customer Dashboard -------- */
function customerCtrl($conn) {
    $id      = $_SESSION['user']['id'];
    $error   = $success = '';
    $customer = getCustomerById($conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name    = trim($_POST['name'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        if (!$name || !$contact || !$username)
            $error = 'All fields are required.';
        elseif (customerUsernameExists($conn, $username, $id))
            $error = 'Username already taken.';
        else {
            if (updateCustomer($conn, $id, $name, $contact, $username)) {
                $_SESSION['user']['name']     = $name;
                $_SESSION['user']['username'] = $username;
                $success = 'Profile updated!';
                $customer = getCustomerById($conn, $id);
            } else {
                $error = 'Update failed.';
            }
        }
    }

    require 'views/customer.php';
}
?>
