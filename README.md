# FoodPanda Clone - Online Food Delivery Platform

A fully functional online food delivery platform inspired by Foodpanda, built with PHP, CSS, and JavaScript.

## Features

✅ **Homepage** - Featured restaurants, trending dishes, and special offers  
✅ **User Authentication** - Secure signup, login, and profile management  
✅ **Restaurant Listing** - Browse and filter restaurants by cuisine, ratings, and location  
✅ **Food Ordering** - Add items to cart and place orders  
✅ **Real-time Order Tracking** - Track order status (Processing, Preparing, On the Way, Delivered)  
✅ **Payment System** - Cash on Delivery (COD) and dummy online payment options  
✅ **Responsive Design** - Works seamlessly on desktop and mobile devices  

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser

## Installation

### 1. Database Setup

1. Create a MySQL database
2. Import the database schema:
   ```sql
   mysql -u root -p < database.sql
   ```
   Or use phpMyAdmin to import `database.sql`

3. Update database credentials in `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'foodpanda_clone');
   ```

### 2. Web Server Configuration

#### Using PHP Built-in Server (Development)
```bash
php -S localhost:8000
```
Then visit `http://localhost:8000` in your browser.

#### Using Apache
1. Place the project folder in your web server directory (e.g., `htdocs`, `www`)
2. Ensure mod_rewrite is enabled
3. Access via `http://localhost/foodpandaclone`

#### Using XAMPP/WAMP
1. Copy the project folder to `htdocs` (XAMPP) or `www` (WAMP)
2. Start Apache and MySQL services
3. Access via `http://localhost/foodpandaclone`

### 3. Images Setup

The application expects restaurant and menu item images in the `assets/images/` directory. You can:

1. Add your own images with matching filenames from the database
2. Use placeholder images - update the `onerror` handlers in PHP files to use a default placeholder service
3. For development, you can use placeholder image services by modifying image src attributes

Example placeholder URLs you can use:
- `https://via.placeholder.com/300x200?text=Restaurant`
- `https://picsum.photos/300/200`

## Project Structure

```
foodpandaclone/
├── api/
│   ├── cart.php              # Cart API endpoints
│   ├── cancel_order.php      # Cancel order API
│   └── process_payment.php   # Payment processing API
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   ├── main.js           # Main JavaScript
│   │   ├── cart.js           # Cart functionality
│   │   └── tracking.js       # Order tracking
│   └── images/               # Restaurant and menu images
├── config.php                # Database configuration
├── database.sql              # Database schema
├── index.php                 # Homepage
├── login.php                 # Login page
├── signup.php                # Registration page
├── logout.php                # Logout handler
├── restaurants.php           # Restaurant listing
├── restaurant.php            # Restaurant detail page
├── cart.php                  # Shopping cart
├── checkout.php              # Checkout page
├── orders.php                # Order history
├── order_tracking.php        # Order tracking page
└── profile.php               # User profile

```

## Usage

### For Users

1. **Sign Up/Login**: Create an account or login to existing account
2. **Browse Restaurants**: View featured restaurants or browse all restaurants
3. **Filter Restaurants**: Use filters to find restaurants by cuisine, ratings, or search
4. **View Menu**: Click on a restaurant to view its menu
5. **Add to Cart**: Add items to your cart
6. **Checkout**: Review cart, enter delivery details, and select payment method
7. **Track Order**: View order status and track delivery in real-time

### For Administrators

To add restaurants and menu items, you can directly insert data into the database or create an admin panel.

Example SQL to add a restaurant:
```sql
INSERT INTO restaurants (name, cuisine, description, image, rating, delivery_time, min_order, location, is_featured) 
VALUES ('Restaurant Name', 'Cuisine Type', 'Description', 'image.jpg', 4.5, 30, 15.00, 'Location', TRUE);
```

## Payment System

### Cash on Delivery (COD)
- Available for all orders
- Payment collected upon delivery

### Credit/Debit Card (Dummy)
- Simulated payment processing
- For demonstration purposes only
- In production, integrate with actual payment gateways (Stripe, PayPal, etc.)

## Order Status Flow

1. **Processing** - Order has been placed
2. **Preparing** - Restaurant is preparing the order
3. **On the Way** - Order is out for delivery
4. **Delivered** - Order has been delivered
5. **Cancelled** - Order has been cancelled

## Features in Detail

### Authentication System
- Secure password hashing using PHP's `password_hash()`
- Session-based authentication
- Profile management

### Restaurant Filtering
- Search by name, cuisine, or description
- Filter by cuisine type
- Sort by ratings, delivery time, or minimum order

### Shopping Cart
- Session-based cart storage
- Add, update, and remove items
- Quantity management
- Minimum order validation

### Order Tracking
- Real-time status updates (auto-refreshes every 10 seconds)
- Visual timeline showing order progress
- Detailed order information

### Responsive Design
- Mobile-first approach
- Responsive grid layouts
- Touch-friendly interface
- Optimized for all screen sizes

## Customization

### Styling
Modify `assets/css/style.css` to customize colors, fonts, and layouts. CSS variables are defined at the top of the file for easy theming.

### Database
Update restaurant and menu data in the database. Sample data is included in `database.sql`.

### Payment Integration
To integrate real payment gateways, modify `api/process_payment.php` and add the necessary payment gateway SDKs.

## Security Considerations

- Passwords are hashed using bcrypt
- SQL injection protection using prepared statements
- Session security
- Input validation and sanitization
- XSS protection with `htmlspecialchars()`

## Troubleshooting

### Images not displaying
- Ensure images are in the `assets/images/` directory
- Check file permissions
- Verify image filenames match database entries
- Check browser console for 404 errors

### Database connection errors
- Verify database credentials in `config.php`
- Ensure MySQL service is running
- Check database name matches `DB_NAME` in config

### Session issues
- Ensure PHP sessions are enabled
- Check `php.ini` for session configuration
- Verify write permissions for session directory

## Future Enhancements

- Admin panel for restaurant and menu management
- Real-time notifications using WebSockets
- Restaurant owner dashboard
- Delivery rider app/interface
- Review and rating system
- Wishlist/favorites
- Order history with reorder functionality
- Multiple payment gateway integrations
- Email/SMS notifications
- Advanced search with filters
- Location-based restaurant recommendations

## License

This project is for educational purposes.

## Support

For issues and questions, please check the code comments or create an issue in the repository.

## Credits

Built with:
- PHP (Backend)
- MySQL (Database)
- HTML5 & CSS3 (Frontend)
- JavaScript (Interactivity)
- Font Awesome (Icons)

---

**Note**: This is a clone project for educational purposes. For production use, additional security measures, error handling, and features would be required.


