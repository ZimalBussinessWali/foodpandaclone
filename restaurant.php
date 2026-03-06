<?php
require_once 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$res_stmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
$res_stmt->bind_param("i", $id);
$res_stmt->execute();
$restaurant = $res_stmt->get_result()->fetch_assoc();

if (!$restaurant) {
    redirect('restaurants.php');
}

// Get menu items grouped by category
$menu_stmt = $conn->prepare("SELECT * FROM food_items WHERE restaurant_id = ? ORDER BY category");
$menu_stmt->bind_param("i", $id);
$menu_stmt->execute();
$menu_result = $menu_stmt->get_result();

$menu = [];
while ($item = $menu_result->fetch_assoc()) {
    $menu[$item['category']][] = $item;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $restaurant['name']; ?> | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .res-hero {
            background: white;
            padding: 40px 0;
            border-bottom: 1px solid #eee;
        }
        .res-header {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        .res-header img {
            width: 150px;
            height: 150px;
            border-radius: 15px;
            object-fit: cover;
        }
        .res-details h1 { font-size: 36px; }
        .res-details p { color: var(--text-muted); margin: 5px 0; }
        
        .menu-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 40px;
            margin-top: 40px;
        }
        .menu-nav {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .menu-nav ul { list-style: none; }
        .menu-nav li { margin-bottom: 10px; }
        .menu-nav a { display: block; padding: 10px 15px; border-radius: 8px; transition: var(--transition); }
        .menu-nav a:hover { background: #eee; }

        .item-card {
            display: flex;
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            gap: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: var(--transition);
        }
        .item-card:hover { transform: translateX(5px); box-shadow: var(--shadow); }
        .item-info { flex-grow: 1; }
        .item-name { font-size: 18px; font-weight: 700; margin-bottom: 5px; }
        .item-desc { color: var(--text-muted); font-size: 14px; margin-bottom: 15px; }
        .item-price { font-weight: 800; color: var(--primary-color); }
        .item-image { width: 120px; height: 120px; border-radius: 10px; object-fit: cover; }
        
        .add-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .menu-container { grid-template-columns: 1fr; }
            .menu-nav { display: none; }
            .res-header { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="cart.php" id="cart-nav">Cart</a>
            <?php if (isLoggedIn()): ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="res-hero">
    <div class="container res-header">
        <img src="<?php echo $restaurant['image_url']; ?>" alt="<?php echo $restaurant['name']; ?>">
        <div class="res-details">
            <h1><?php echo $restaurant['name']; ?></h1>
            <p><?php echo $restaurant['category']; ?></p>
            <p>★ <?php echo $restaurant['rating']; ?> (100+ ratings)</p>
            <p>Delivery: <?php echo $restaurant['delivery_time']; ?></p>
        </div>
    </div>
</div>

<div class="container animate">
    <div class="menu-container">
        <aside class="menu-nav">
            <ul>
                <?php foreach($menu as $cat => $items): ?>
                    <li><a href="#cat-<?php echo strtolower(str_replace(' ', '-', $cat)); ?>"><?php echo $cat; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main class="menu-items">
            <?php foreach($menu as $cat => $items): ?>
                <div id="cat-<?php echo strtolower(str_replace(' ', '-', $cat)); ?>">
                    <h2 class="section-title"><?php echo $cat; ?></h2>
                    <?php foreach($items as $item): ?>
                        <div class="item-card">
                            <div class="item-info">
                                <div class="item-name"><?php echo $item['name']; ?></div>
                                <p class="item-desc"><?php echo $item['description']; ?></p>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="item-price"><?php echo formatPrice($item['price']); ?></span>
                                    <button class="add-btn" onclick="addToCart(<?php echo $item['id']; ?>)">Add to cart</button>
                                </div>
                            </div>
                            <img src="<?php echo $item['image_url']; ?>" class="item-image" alt="<?php echo $item['name']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </main>
    </div>
</div>

<script>
function addToCart(itemId) {
    fetch('menu.php?action=add&id=' + itemId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Added to cart!');
                // Update cart count if needed
            } else {
                if(data.error === 'login_required') {
                    window.location.href = 'login.php';
                } else {
                    alert('Error: ' + data.error);
                }
            }
        });
}
</script>

</body>
</html>
