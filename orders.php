<?php
require_once 'db.php';

if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .orders-container {
            max-width: 900px;
            margin: 50px auto;
        }
        .order-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-preparing { background: #dcfce7; color: #16a34a; }
        .status-out { background: #dbeafe; color: #1e40af; }
        .status-delivered { background: #f3f4f6; color: #374151; }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="orders-container">
        <h1>Order History</h1>
        <div style="margin-top: 30px;">
            <?php if($result->num_rows > 0): ?>
                <?php while($order = $result->fetch_assoc()): ?>
                    <div class="order-card animate">
                        <div>
                            <div style="font-weight: 800; font-size: 18px; margin-bottom: 5px;">Order #<?php echo $order['id']; ?></div>
                            <div style="color: var(--text-muted); font-size: 14px;"><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></div>
                            <div style="font-weight: 700; color: var(--primary-color); margin-top: 10px;"><?php echo formatPrice($order['total_price']); ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div class="status-badge status-<?php echo str_replace(' ', '', strtolower($order['status'])); ?>">
                                <?php echo ucwords($order['status']); ?>
                            </div>
                            <div style="margin-top: 15px;">
                                <a href="order_tracking.php?id=<?php echo $order['id']; ?>" class="btn-outline" style="font-size: 14px; padding: 5px 15px;">Track Order</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 50px; background: white; border-radius: 15px;">
                    <p>You haven't placed any orders yet.</p>
                    <a href="restaurants.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">Order Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
