<?php
require_once 'db.php';

if (!isLoggedIn()) redirect('login.php');

$user = getCurrentUser($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Foodpanda</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        <?php echo $common_css; ?>
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: #ffeaf1;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 800;
            margin: 0 auto 20px;
        }
        .profile-info {
            text-align: left;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-label { font-weight: 600; color: var(--text-muted); }
        .info-value { font-weight: 700; }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">foodpanda</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="btn-outline">Logout</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="profile-container animate">
        <div class="profile-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
        <h2><?php echo $user['name']; ?></h2>
        <p style="color: var(--text-muted);"><?php echo $user['email']; ?></p>

        <div class="profile-info">
            <div class="info-row">
                <span class="info-label">Full Name</span>
                <span class="info-value"><?php echo $user['name']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone</span>
                <span class="info-value"><?php echo $user['phone']; ?></span>
            </div>
            <div class="info-row" style="flex-direction: column; gap: 5px;">
                <span class="info-label">Delivery Address</span>
                <span class="info-value" style="margin-top: 5px;"><?php echo $user['address']; ?></span>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="edit_profile.php" class="btn-primary" style="display: block;">Edit Profile</a>
        </div>
    </div>
</div>

</body>
</html>
