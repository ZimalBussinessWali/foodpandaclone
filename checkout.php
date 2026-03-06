<?php
require_once 'db.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getCurrentUser($conn);
$user_id = $user['id'];

// Check if cart is empty
$query = "SELECT COUNT(*) as count FROM carts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()['count'] == 0) {
    redirect('cart.php');
}

// Get total price
$query = "SELECT SUM(f.price * c.quantity) as total 
          FROM carts c 
          JOIN food_items f ON c.food_item_id = f.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            margin-top: 50px;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }
        .summary-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .payment-option {
            border: 2px solid #eee;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }
        .payment-option input { display: none; }
        .payment-option:hover { border-color: var(--primary-color); }
        .payment-option.active { border-color: var(--primary-color); background: #fff5f8; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit;
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="cart.php">Back to Cart</a>
        </div>
    </div>
</header>

<div class="container">
    <form action="place_order.php" method="POST" class="checkout-grid animate">
        <div class="checkout-form">
            <div class="form-card">
                <h3>Delivery Details</h3>
                <div style="margin-top: 20px;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Delivery Address</label>
                        <textarea name="address" rows="3" required><?php echo $user['address']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h3>Payment Method</h3>
                <div class="payment-methods">
                    <label class="payment-option active">
                        <input type="radio" name="payment" value="COD" checked>
                        <strong>Cash on Delivery</strong>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="Online">
                        <strong>Online Payment</strong>
                    </label>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Order Summary</h3>
            <div style="margin: 20px 0; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Subtotal</span>
                    <span><?php echo formatPrice($total); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Delivery Fee</span>
                    <span>Rs. 100.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 20px; margin-top: 15px;">
                    <span>Total</span>
                    <span><?php echo formatPrice($total + 100); ?></span>
                </div>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; padding: 15px;">Place Order</button>
            <p style="font-size: 12px; color: var(--text-muted); text-align: center; margin-top: 15px;">
                By placing your order, you agree to our terms and conditions.
            </p>
        </div>
    </form>
</div>

<script>
// Toggle active class for payment options
document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>
