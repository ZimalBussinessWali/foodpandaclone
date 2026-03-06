<?php
require_once 'db.php';

// Get featured restaurants
$query = "SELECT * FROM restaurants LIMIT 6";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodpanda | Food Delivery</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f7f7f7 0%, #ffeaf1 100%);
            padding: 80px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hero-content h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .hero-content p {
            font-size: 18px;
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .search-container {
            background: white;
            padding: 10px;
            border-radius: 12px;
            display: flex;
            box-shadow: var(--shadow);
            max-width: 600px;
        }

        .search-container input {
            border: none;
            padding: 15px;
            flex-grow: 1;
            font-size: 16px;
            outline: none;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 30px;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Restaurants Section */
        .section-title {
            margin: 50px 0 30px;
            font-size: 28px;
            font-weight: 800;
        }

        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .res-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .delivery-badge {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .res-info {
            padding: 20px;
        }

        .res-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .res-meta {
            color: var(--text-muted);
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .rating {
            color: #ffa41c;
            font-weight: 600;
        }

        /* Offers Section */
        .offers {
            display: flex;
            gap: 20px;
            margin-top: 40px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .offer-card {
            min-width: 300px;
            height: 150px;
            background: var(--primary-color);
            border-radius: 15px;
            padding: 25px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-image: linear-gradient(45deg, rgba(0,0,0,0.1), transparent);
        }

        @media (max-width: 768px) {
            .hero { flex-direction: column; text-align: center; }
            .hero-content h1 { font-size: 32px; }
            .search-container { flex-direction: column; }
            .search-container button { margin-top: 10px; }
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">
            <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/><path d="M11 7h2v5h3l-4 4-4-4h3z"/></svg>
            foodpanda
        </a>
        <div class="nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="cart.php">Cart</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php" class="btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="hero">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <div class="hero-content animate">
            <h1>It’s the food you love, <br>delivered</h1>
            <p>Order from your favorite restaurants near you.</p>
            <form action="restaurants.php" method="GET" class="search-container">
                <input type="text" name="search" placeholder="Enter your restaurant or food...">
                <button type="submit" class="btn-primary">Find Food</button>
            </form>
        </div>
        <div class="hero-image animate" style="display: block;">
            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1981&auto=format&fit=crop" alt="Food" width="500">
        </div>
    </div>
</div>

<div class="container">
    <div class="section-title">Deals you'll love</div>
    <div class="offers">
        <div class="offer-card">
            <h3>50% OFF</h3>
            <p>On your first order</p>
        </div>
        <div class="offer-card" style="background-color: #333;">
            <h3>Free Delivery</h3>
            <p>For orders above Rs. 1000</p>
        </div>
        <div class="offer-card" style="background-color: #f7bb01;">
            <h3>Flash Sale</h3>
            <p>Pizza Hut exclusive</p>
        </div>
    </div>

    <div class="section-title">Popular Restaurants</div>
    <div class="restaurant-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <a href="restaurant.php?id=<?php echo $row['id']; ?>" class="card animate">
                <div class="res-image" style="background-image: url('<?php echo $row['image_url']; ?>')">
                    <div class="delivery-badge"><?php echo $row['delivery_time']; ?></div>
                </div>
                <div class="res-info">
                    <div class="res-name"><?php echo $row['name']; ?></div>
                    <div class="res-meta">
                        <span><?php echo $row['category']; ?></span>
                        <span class="rating">★ <?php echo $row['rating']; ?></span>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <a href="restaurants.php" class="btn-outline">Show all restaurants</a>
    </div>
</div>

<footer>
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px;">
        <div>
            <div class="logo" style="color: white; margin-bottom: 20px;">foodpanda</div>
            <p style="color: #bbb;">Order food from the best restaurants online.</p>
        </div>
        <div>
            <h4>Popular Categories</h4>
            <ul style="list-style: none; margin-top: 15px; color: #bbb;">
                <li>Fast Food</li>
                <li>Chinese</li>
                <li>BBQ</li>
                <li>Desserts</li>
            </ul>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul style="list-style: none; margin-top: 15px; color: #bbb;">
                <li><a href="signup.php">Join us</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="restaurants.php">Restaurants</a></li>
            </ul>
        </div>
    </div>
    <div style="text-align: center; margin-top: 50px; color: #777;">
        &copy; 2026 Foodpanda Clone. All rights reserved.
    </div>
</footer>

</body>
</html>
