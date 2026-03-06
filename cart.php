<?php
require_once 'db.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get cart items with food details
$query = "SELECT c.id as cart_id, c.quantity, f.name, f.price, f.image_url, f.id as food_id 
          FROM carts c 
          JOIN food_items f ON c.food_item_id = f.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .cart-container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-info { display: flex; align-items: center; gap: 20px; flex-grow: 1; }
        .cart-item img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; }
        .qty-controls { display: flex; align-items: center; gap: 15px; }
        .qty-btn { background: #eee; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-weight: bold; }
        .qty-btn:hover { background: var(--primary-color); color: white; }
        .summary { margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; text-align: right; }
        .summary h2 { margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="restaurants.php">Restaurants</a>
            <a href="profile.php">Profile</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="cart-container animate">
        <h1>Your Cart</h1>
        
        <?php if(empty($items)): ?>
            <div style="text-align: center; padding: 40px;">
                <p>Your cart is empty.</p>
                <a href="restaurants.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">Browse Food</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($items as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <img src="<?php echo $item['image_url']; ?>" alt="">
                            <div>
                                <div style="font-weight: 700;"><?php echo $item['name']; ?></div>
                                <div style="color: var(--primary-color);"><?php echo formatPrice($item['price']); ?></div>
                            </div>
                        </div>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                            <span><?php echo $item['quantity']; ?></span>
                            <button class="qty-btn" onclick="updateQty(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                        </div>
                        <div style="font-weight: 700; width: 100px; text-align: right;">
                            <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                        </div>
                        <button style="border: none; background: transparent; color: #ff4d4d; cursor: pointer; margin-left: 20px;" onclick="updateQty(<?php echo $item['cart_id']; ?>, 0)">
                            ✕
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary">
                <p style="color: var(--text-muted);">Subtotal</p>
                <h2><?php echo formatPrice($total); ?></h2>
                <a href="checkout.php" class="btn-primary" style="display: inline-block; padding: 15px 40px;">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateQty(cartId, qty) {
    fetch('menu.php?action=update&cart_id=' + cartId + '&qty=' + qty)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error updating cart');
            }
        });
}
</script>

</body>
</html>
