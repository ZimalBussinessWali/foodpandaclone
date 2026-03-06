<?php
require_once 'db.php';

if (!isLoggedIn()) redirect('login.php');

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) redirect('index.php');

// Simulation: Update status based on time since order (for demo purposes)
// In a real app, this would be updated by the admin/restaurant
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Order #<?php echo $order_id; ?> | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .tracking-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        .status-steps {
            display: flex;
            justify-content: space-between;
            margin: 50px 0;
            position: relative;
        }
        .status-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50px;
            right: 50px;
            height: 4px;
            background: #eee;
            z-index: 1;
        }
        .step {
            position: relative;
            z-index: 2;
            width: 100px;
        }
        .step-icon {
            width: 45px;
            height: 45px;
            background: #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: var(--transition);
            font-weight: 800;
        }
        .step.active .step-icon {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 0 15px rgba(215, 15, 100, 0.4);
        }
        .step.completed .step-icon {
            background: #16a34a;
            color: white;
        }
        .step-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
        }
        .step.active .step-label { color: var(--primary-color); }

        .delivery-animation {
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        .bike { font-size: 60px; animation: drive 4s infinite linear; }
        @keyframes drive {
            0% { transform: translateX(-150px); }
            100% { transform: translateX(150px); }
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="orders.php">My Orders</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="tracking-container animate">
        <h1>Order Tracking</h1>
        <p style="color: var(--text-muted);">Order #<?php echo $order_id; ?> • <?php echo formatPrice($order['total_price']); ?></p>
        
        <div id="status-content">
            <!-- Content will be refreshed via AJAX -->
             <div class="delivery-animation">
                <span class="bike">🛵</span>
             </div>

            <div class="status-steps">
                <?php
                $statuses = ['pending', 'preparing', 'out for delivery', 'delivered'];
                $current_idx = array_search($order['status'], $statuses);
                
                foreach($statuses as $idx => $status):
                    $class = '';
                    if ($idx < $current_idx) $class = 'completed';
                    elseif ($idx == $current_idx) $class = 'active';
                ?>
                    <div class="step <?php echo $class; ?>">
                        <div class="step-icon"><?php echo $idx + 1; ?></div>
                        <div class="step-label"><?php echo ucwords($status); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                <h3>Status: <?php echo ucwords($order['status']); ?></h3>
                <p id="eta">Estimated arrival: <?php echo ($order['status'] == 'delivered') ? 'Delivered' : '25-30 mins'; ?></p>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="dashboard.php" class="btn-outline">Go to Dashboard</a>
        </div>
    </div>
</div>

<script>
// Simulated Real-time Status Check
function checkStatus() {
    fetch('update_order_status.php?action=get_status&id=<?php echo $order_id; ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status !== '<?php echo $order['status']; ?>') {
                window.location.reload();
            }
        });
}

setInterval(checkStatus, 5000); // Check every 5 seconds
</script>

</body>
</html>
