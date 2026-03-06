# Quick Setup Guide

## Step 1: Database Setup

1. Open phpMyAdmin or MySQL command line
2. Create a new database named `foodpanda_clone`
3. Import the `database.sql` file into this database

**OR** use command line:
```bash
mysql -u root -p -e "CREATE DATABASE foodpanda_clone;"
mysql -u root -p foodpanda_clone < database.sql
```

## Step 2: Configure Database Connection

Edit `config.php` and update these lines if needed:
```php
define('DB_HOST', 'localhost');  // Usually 'localhost'
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('DB_NAME', 'foodpanda_clone');
```

## Step 3: Start the Server

### Option A: PHP Built-in Server (Recommended for Testing)
```bash
php -S localhost:8000
```
Then open: http://localhost:8000

### Option B: XAMPP/WAMP
1. Copy the project folder to `htdocs` (XAMPP) or `www` (WAMP)
2. Start Apache and MySQL from XAMPP/WAMP control panel
3. Open: http://localhost/foodpandaclone

### Option C: MAMP
1. Copy project to `htdocs` folder
2. Start MAMP servers
3. Open: http://localhost:8888/foodpandaclone

## Step 4: Test the Application

1. Open the homepage in your browser
2. Click "Sign Up" to create a test account
3. Browse restaurants and add items to cart
4. Complete checkout process
5. Track your order

## Default Sample Data

The database includes:
- 6 sample restaurants (Italian, American, Japanese, Indian, Chinese, Mexican)
- 30+ menu items across different categories
- All restaurants are ready to use

## Images Note

The application uses placeholder images from via.placeholder.com for missing images. 
To use real images:
1. Add images to `assets/images/` folder
2. Name them exactly as in the database (e.g., `pizza.jpg`, `burger.jpg`)
3. Supported formats: JPG, PNG, GIF

## Troubleshooting

**Database connection error?**
- Check MySQL is running
- Verify credentials in config.php
- Ensure database exists

**Page shows errors?**
- Check PHP error logs
- Ensure PHP version is 7.4 or higher
- Verify all files are uploaded correctly

**Images not showing?**
- Check `assets/images/` folder exists
- Verify file permissions (should be readable)
- Check browser console for errors

**Session issues?**
- Ensure PHP sessions are enabled
- Check write permissions in session directory
- Clear browser cookies and try again

## Admin Access (Optional)

To manually add/edit restaurants and menu items:
1. Use phpMyAdmin
2. Navigate to `foodpanda_clone` database
3. Edit `restaurants` and `menu_items` tables directly
4. Or create SQL INSERT/UPDATE queries

## Next Steps

- Customize colors and styles in `assets/css/style.css`
- Add more restaurants via database
- Customize the homepage content
- Add real payment gateway integration (replace dummy payment)

Enjoy your FoodPanda Clone! 🍕🍔🍜


