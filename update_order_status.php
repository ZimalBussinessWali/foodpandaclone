<?php
require_once 'db.php';

if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Handler for AJAX polling (GET)
if (isset($_GET['action']) && $_GET['action'] == 'get_status') {
    header('Content-Type: application/json');
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    echo json_encode(['status' => $res['status']]);
    exit();
}

// Handler for Admin status update (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = getCurrentUser($conn);
    if ($user['role'] != 'admin') {
        redirect('dashboard.php');
    }

    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    redirect('dashboard.php');
}
?>
