-- Foodpanda Clone Database Structure
-- Database: rsk80_24

CREATE DATABASE IF NOT EXISTS rsk80_24;
USE rsk80_24;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Restaurants Table
CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    rating DECIMAL(2,1) DEFAULT 0.0,
    delivery_time VARCHAR(20) DEFAULT '30-40 min',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Food Items Table
CREATE TABLE IF NOT EXISTS food_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

-- Cart Table
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    food_item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'out for delivery', 'delivered') DEFAULT 'pending',
    delivery_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('COD', 'Online') DEFAULT 'COD',
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert Sample Data for Restaurants
INSERT INTO restaurants (name, category, image_url, rating, delivery_time) VALUES
('Pizza Hut', 'Fast Food', 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070&auto=format&fit=crop', 4.5, '25-35 min'),
('Burger King', 'Fast Food', 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?q=80&w=2072&auto=format&fit=crop', 4.2, '20-30 min'),
('Golden Dragon', 'Chinese', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=2070&auto=format&fit=crop', 4.8, '40-50 min'),
('BBQ Tonight', 'BBQ', 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1974&auto=format&fit=crop', 4.6, '35-45 min'),
('Healthy Greens', 'Salads', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=2080&auto=format&fit=crop', 4.7, '15-25 min');

-- Insert Sample Data for Food Items
INSERT INTO food_items (restaurant_id, name, description, price, image_url, category) VALUES
(1, 'Pepperoni Pizza', 'Classic pepperoni with mozzarella cheese', 12.99, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1780&auto=format&fit=crop', 'Pizza'),
(1, 'Veggie Supreme', 'Loaded with fresh vegetables', 10.99, 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?q=80&w=1925&auto=format&fit=crop', 'Pizza'),
(2, 'Whopper Burger', 'Flame-grilled beef patty', 8.99, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899&auto=format&fit=crop', 'Burger'),
(2, 'Chicken Royale', 'Crispy chicken with lettuce and mayo', 7.99, 'https://images.unsplash.com/photo-1610614819513-58e34989848b?q=80&w=2070&auto=format&fit=crop', 'Burger'),
(3, 'Kung Pao Chicken', 'Spicy diced chicken with peanuts', 11.50, 'https://images.unsplash.com/photo-1525755662778-989d0524087e?q=80&w=1974&auto=format&fit=crop', 'Main Course'),
(3, 'Spring Rolls', 'Crispy vegetable rolls', 5.99, 'https://images.unsplash.com/photo-1623934199716-dc3d82871e72?q=80&w=1548&auto=format&fit=crop', 'Appetizer'),
(4, 'Mixed Grill Platter', 'Variety of grilled meats', 24.99, 'https://images.unsplash.com/photo-1615937657715-bc7b4b7962c1?q=80&w=2070&auto=format&fit=crop', 'Grill'),
(5, 'Quinoa Salad', 'Nutritious quinoa with avocado', 13.50, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=2070&auto=format&fit=crop', 'Salad');
