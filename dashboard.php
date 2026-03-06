<?php
require_once 'db.php';

if (!isLoggedIn()) redirect('login.php');

$user = getCurrentUser($conn);
$user_id = $user['id'];
$is_admin = ($user['role'] == 'admin');

// Get active orders (all for admin, user's only for users)
if ($is_admin) {
    $active_query = "SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.status != 'delivered' ORDER BY o.created_at DESC";
    $active_stmt = $conn->prepare($active_query);
} else {
    $active_query = "SELECT * FROM orders WHERE user_id = ? AND status != 'delivered' ORDER BY created_at DESC";
    $active_stmt = $conn->prepare($active_query);
    $active_stmt->bind_param("i", $user_id);
}
$active_stmt->execute();
$active_orders = $active_stmt->get_result();

// Get order stats for dashboard
$stats = [
    'pending' => 0,
    'delivered' => 0,
    'total_spent' => 0
];

if (!$is_admin) {
    $stats_query = "SELECT status, COUNT(*) as count, SUM(total_price) as total FROM orders WHERE user_id = ? GROUP BY status";
    $st_stmt = $conn->prepare($stats_query);
    $st_stmt->bind_param("i", $user_id);
    $st_stmt->execute();
    $st_res = $st_stmt->get_result();
    while ($row = $st_res->fetch_assoc()) {
        if ($row['status'] == 'pending') $stats['pending'] = $row['count'];
        if ($row['status'] == 'delivered') $stats['delivered'] = $row['count'];
        $stats['total_spent'] += $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .dashboard-grid {
            display: grid; grid-template-columns: 300px 1fr; gap: 40px; margin-top: 50px;
        }
        .sidebar {
            background: white; padding: 30px; border-radius: 20px; box-shadow: var(--shadow); height: fit-content;
        }
        .stat-card {
            background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow); text-align: center;
        }
        .stat-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 30px;
        }
        .order-list-card {
            background: white; padding: 30px; border-radius: 20px; box-shadow: var(--shadow);
        }
        .order-row {
            display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid #eee;
        }
        .order-row:last-child { border-bottom: none; }
        .sidebar-links a {
            display: block; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: var(--transition);
        }
        .sidebar-links a:hover, .sidebar-links a.active {
            background: var(--primary-color); color: white !important;
        }
        .status-select {
            padding: 8px; border-radius: 5px; border: 1px solid #ddd; outline: none;
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="logout.php" class="btn-outline">Logout</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="dashboard-grid">
        <aside class="sidebar animate">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 60px; height: 60px; background: #ffeaf1; color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 10px;">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h3><?php echo $user['name']; ?></h3>
                <span class="status-badge" style="background: #eee; font-size: 10px;"><?php echo strtoupper($user['role']); ?></span>
            </div>
            <nav class="sidebar-links">
                <a href="dashboard.php" class="active">Overview</a>
                <a href="orders.php">Order History</a>
                <a href="profile.php">Profile Settings</a>
                <a href="cart.php">Shopping Cart</a>
                <?php if($is_admin): ?>
                    <hr style="margin: 20px 0; opacity: 0.1;">
                    <p style="font-size: 12px; font-weight: 700; margin-bottom: 10px; color: #aaa;">ADMIN TOOLS</p>
                    <a href="dashboard.php?view=all_orders">Manage Orders</a>
                <?php endif; ?>
            </nav>
        </aside>

        <main class="dashboard-main animate">
            <?php if(!$is_admin): ?>
                <div class="stat-grid">
                    <div class="stat-card">
                        <div style="font-size: 14px; color: var(--text-muted);">Active Orders</div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--primary-color);"><?php echo $stats['pending']; ?></div>
                    </div>
                    <div class="stat-card">
                        <div style="font-size: 14px; color: var(--text-muted);">Total Delivered</div>
                        <div style="font-size: 24px; font-weight: 800; color: #16a34a;"><?php echo $stats['delivered']; ?></div>
                    </div>
                    <div class="stat-card">
                        <div style="font-size: 14px; color: var(--text-muted);">Total Spent</div>
                        <div style="font-size: 24px; font-weight: 800;"><?php echo formatPrice($stats['total_spent']); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="order-list-card">
                <h3><?php echo $is_admin ? "Manage Active Orders" : "Active Orders"; ?></h3>
                <div style="margin-top: 20px;">
                    <?php if($active_orders->num_rows > 0): ?>
                        <?php while($order = $active_orders->fetch_assoc()): ?>
                            <div class="order-row">
                                <div>
                                    <div style="font-weight: 700;">Order #<?php echo $order['id']; ?></div>
                                    <?php if($is_admin): ?>
                                        <div style="font-size: 12px; color: var(--text-muted);">Customer: <?php echo $order['user_name']; ?></div>
                                    <?php endif; ?>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($order['created_at'])); ?></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <?php if($is_admin): ?>
                                        <form action="update_order_status.php" method="POST" style="display: flex; gap: 10px;">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="status" class="status-select">
                                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="preparing" <?php echo $order['status'] == 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                                <option value="out for delivery" <?php echo $order['status'] == 'out for delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            </select>
                                            <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 12px;">Update</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="status-badge" style="background: #ffeaf1; color: var(--primary-color);"><?php echo ucwords($order['status']); ?></span>
                                        <a href="order_tracking.php?id=<?php echo $order['id']; ?>" class="btn-outline" style="font-size: 12px; padding: 5px 12px;">Track</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                            No active orders at the moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
