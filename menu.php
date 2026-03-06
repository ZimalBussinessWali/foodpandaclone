<?php
require_once 'db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'login_required']);
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = $_SESSION['user_id'];

if ($action == 'add') {
    $item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Check if item exists
    $check_item = $conn->prepare("SELECT id FROM food_items WHERE id = ?");
    $check_item->bind_param("i", $item_id);
    $check_item->execute();
    if ($check_item->get_result()->num_rows == 0) {
        echo json_encode(['success' => false, 'error' => 'Item not found']);
        exit();
    }

    // Check if item already in cart
    $check_cart = $conn->prepare("SELECT id, quantity FROM carts WHERE user_id = ? AND food_item_id = ?");
    $check_cart->bind_param("ii", $user_id, $item_id);
    $check_cart->execute();
    $cart_res = $check_cart->get_result();

    if ($cart_res->num_rows > 0) {
        $cart_item = $cart_res->fetch_assoc();
        $new_qty = $cart_item['quantity'] + 1;
        $update = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $cart_item['id']);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO carts (user_id, food_item_id, quantity) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $user_id, $item_id);
        $insert->execute();
    }

    echo json_encode(['success' => true]);
    exit();
}

if ($action == 'remove') {
    $cart_id = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;
    $delete = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $cart_id, $user_id);
    $delete->execute();
    echo json_encode(['success' => true]);
    exit();
}

if ($action == 'update') {
    $cart_id = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;
    $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
    if ($qty <= 0) {
        $delete = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
        $delete->bind_param("ii", $cart_id, $user_id);
        $delete->execute();
    } else {
        $update = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
        $update->bind_param("iii", $qty, $cart_id, $user_id);
        $update->execute();
    }
    echo json_encode(['success' => true]);
    exit();
}

echo json_encode(['success' => false, 'error' => 'Invalid action']);
?>
