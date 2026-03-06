<?php
require_once 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT * FROM restaurants WHERE 1=1";
if ($search) {
    $query .= " AND (name LIKE '%$search%' OR category LIKE '%$search%')";
}
if ($category) {
    $query .= " AND category = '$category'";
}
$result = $conn->query($query);

// Get all categories for filter
$cat_query = "SELECT DISTINCT category FROM restaurants";
$cat_result = $conn->query($cat_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Restaurants | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .page-header {
            background: white;
            padding: 40px 0;
            border-bottom: 1px solid #eee;
        }
        .filter-container {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        .filter-btn {
            padding: 10px 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 20px;
            white-space: nowrap;
            cursor: pointer;
            transition: var(--transition);
        }
        .filter-btn.active, .filter-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .res-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        .no-results {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 12px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <?php if (isLoggedIn()): ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="page-header">
    <div class="container">
        <h1>Restaurants in your area</h1>
        <p><?php echo $result->num_rows; ?> restaurants found</p>
    </div>
</div>

<div class="container">
    <div class="filter-container">
        <a href="restaurants.php" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">All</a>
        <?php while($cat = $cat_result->fetch_assoc()): ?>
            <a href="restaurants.php?category=<?php echo $cat['category']; ?>" 
               class="filter-btn <?php echo $category == $cat['category'] ? 'active' : ''; ?>">
                <?php echo $cat['category']; ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="restaurant-grid">
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="restaurant.php?id=<?php echo $row['id']; ?>" class="card animate">
                    <div class="res-image" style="background-image: url('<?php echo $row['image_url']; ?>')"></div>
                    <div class="res-info">
                        <div class="res-name"><?php echo $row['name']; ?></div>
                        <div class="res-meta">
                            <span><?php echo $row['category']; ?></span>
                            <span class="rating">★ <?php echo $row['rating']; ?></span>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results animate">
                <h3>No restaurants found</h3>
                <p>Try searching for something else.</p>
                <a href="restaurants.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">View All</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
