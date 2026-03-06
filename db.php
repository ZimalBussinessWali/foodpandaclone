<?php
// db.php - Database connection and common functions
session_start();

$host = 'localhost';
$dbname = 'rsk80_24';
$username = 'rsk80_24';
$password = '123456';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to get current user details
function getCurrentUser($conn) {
    if (!isLoggedIn()) return null;
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to format price
function formatPrice($price) {
    return "Rs. " . number_format($price, 2);
}

// Common CSS for consistent UI
$common_css = "
:root {
    --primary-color: #d70f64; /* Foodpanda Pink */
    --secondary-color: #ffffff;
    --accent-color: #333333;
    --background-light: #f7f7f7;
    --text-muted: #707070;
    --shadow: 0 4px 15px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Outfit', sans-serif;
}

body {
    background-color: var(--background-light);
    color: var(--accent-color);
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

a {
    text-decoration: none;
    color: inherit;
}

/* Header Styles */
header {
    background: var(--secondary-color);
    box-shadow: var(--shadow);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    font-size: 24px;
    color: var(--primary-color);
}

.nav-links {
    display: flex;
    gap: 25px;
    align-items: center;
}

.nav-links a:hover {
    color: var(--primary-color);
}

.btn-primary {
    background: var(--primary-color);
    color: white !important;
    padding: 10px 25px;
    border-radius: 8px;
    font-weight: 600;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #b50d54;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(215, 15, 100, 0.3);
}

.btn-outline {
    border: 2px solid var(--primary-color);
    color: var(--primary-color) !important;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: var(--transition);
    background: transparent;
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white !important;
}

/* Card Styles */
.card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-10px);
}

/* Footer Style */
footer {
    background: var(--accent-color);
    color: white;
    padding: 50px 0;
    margin-top: 50px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate {
    animation: fadeIn 0.8s ease forwards;
}
";
?>
