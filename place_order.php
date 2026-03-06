<?php
require_once 'db.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('index.php');
}

$user_id = $_SESSION['user_id'];
$address = $_POST['address'];
$payment_method = $_POST['payment'];

// Calculate total
$query = "SELECT SUM(f.price * c.quantity) as total 
          FROM carts c 
          JOIN food_items f ON c.food_item_id = f.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_row = $stmt->get_result()->fetch_assoc();
$total_price = $total_row['total'] + 100; // Add delivery fee

// Start transaction
$conn->begin_transaction();

try {
    // 1. Create Order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, delivery_address) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total_price, $address);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // 2. Move items from cart to order_items
    $get_cart = $conn->prepare("SELECT food_item_id, quantity, (SELECT price FROM food_items WHERE id = food_item_id) as price FROM carts WHERE user_id = ?");
    $get_cart->bind_param("i", $user_id);
    $get_cart->execute();
    $cart_items = $get_cart->get_result();

    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, food_item_id, quantity, price) VALUES (?, ?, ?, ?)");
    while ($item = $cart_items->fetch_assoc()) {
        $item_stmt->bind_param("iiid", $order_id, $item['food_item_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }

    // 3. Create Payment record
    $payment_status = ($payment_method == 'COD') ? 'pending' : 'completed';
    $pay_stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, payment_status) VALUES (?, ?, ?)");
    $pay_stmt->bind_param("iss", $order_id, $payment_method, $payment_status);
    $pay_stmt->execute();

    // 4. Clear Cart
    $clear_cart = $conn->prepare("DELETE FROM carts WHERE user_id = ?");
    $clear_cart->bind_param("i", $user_id);
    $clear_cart->execute();

    $conn->commit();
    redirect("order_tracking.php?id=$order_id");

} catch (Exception $e) {
    $conn->rollback();
    echo "Order failed: " . $e->getMessage();
}
?>
