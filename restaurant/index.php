<?php
session_start();
require 'config.php';
require 'models.php';
require 'controllers.php';

$page = $_GET['page'] ?? 'login';

/* --- Logout --- */
if ($page === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

/* --- AJAX search endpoint --- */
if ($page === 'ajax') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    $type = $_GET['type'] ?? '';
    $q    = trim($_GET['q'] ?? '');
    if ($type === 'menu') {
        echo json_encode($q === '' ? getMenuItems($conn) : searchMenuItems($conn, $q));
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Bad type']);
    }
    exit;
}

/* --- Auth gates --- */
$public = ['login', 'register'];

if (in_array($page, $public) && isset($_SESSION['user'])) {
    header('Location: index.php?page=' . $_SESSION['user']['role']);
    exit;
}
if (!in_array($page, $public) && !isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

if ($page === 'admin'    && $_SESSION['user']['role'] !== 'admin')    { header('Location: index.php?page=login'); exit; }
if ($page === 'staff'    && $_SESSION['user']['role'] !== 'staff')    { header('Location: index.php?page=login'); exit; }
if ($page === 'customer' && $_SESSION['user']['role'] !== 'customer') { header('Location: index.php?page=login'); exit; }

/* --- Dispatch --- */
switch ($page) {
    case 'login':    loginCtrl($conn);    break;
    case 'register': registerCtrl($conn); break;
    case 'admin':    adminCtrl($conn);    break;
    case 'staff':    staffCtrl($conn);    break;
    case 'customer': customerCtrl($conn); break;
    default:
        header('Location: index.php?page=login');
        exit;
}

mysqli_close($conn);
?>
