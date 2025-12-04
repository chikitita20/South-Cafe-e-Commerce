# South Cafe Online Ordering System

A simple e-commerce website for South Cafe built with PHP and MySQL, designed to run on XAMPP.

## Features

- **Home Page**: Welcome page with featured products and cafe information
- **User Authentication**: Login and registration system
- **Menu/Food Selection**: Browse products by category with filtering
- **Shopping Cart**: Add items, update quantities, and manage orders
- **Responsive Design**: Mobile-friendly interface

## Requirements

- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Safari, etc.)

## Installation Instructions

### 1. Install XAMPP

Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

### 2. Setup Project Files

1. Copy the entire `South-Cafe-e-Commerce` folder to your XAMPP `htdocs` directory:
   ```
   /Applications/XAMPP/htdocs/South-Cafe-e-Commerce/  (macOS)
   C:/xampp/htdocs/South-Cafe-e-Commerce/  (Windows)
   ```

### 3. Setup Database

1. Start XAMPP and ensure Apache and MySQL are running

2. Open phpMyAdmin in your browser:
   ```
   http://localhost/phpmyadmin
   ```

3. Import the database:
   - Click on "Import" tab
   - Click "Choose File" and select `database.sql` from the project folder
   - Click "Go" to import

   **OR** create manually:
   - Click "New" to create a new database
   - Name it `south_cafe`
   - Click on the `south_cafe` database
   - Go to "SQL" tab
   - Copy and paste the contents of `database.sql`
   - Click "Go"

### 4. Access the Website

Open your web browser and navigate to:
```
http://localhost/South-Cafe-e-Commerce/
```

## Default Configuration

The application is configured with the following default settings in `config.php`:

- **Database Host**: localhost
- **Database User**: root
- **Database Password**: (empty)
- **Database Name**: south_cafe

If your XAMPP MySQL has different credentials, update the `config.php` file accordingly.

## Project Structure

```
South-Cafe-e-Commerce/
├── config.php              # Database configuration
├── database.sql            # Database schema and sample data
├── index.php              # Home page
├── login.php              # Login page
├── register.php           # Registration page
├── menu.php               # Food selection/menu page
├── cart.php               # Shopping cart
├── logout.php             # Logout handler
├── style.css              # Main stylesheet
├── includes/
│   ├── header.php         # Common header
│   └── footer.php         # Common footer
├── images/
│   └── placeholder.jpg    # Placeholder image
└── README.md              # This file
```

## Usage

### For Customers

1. **Register an Account**:
   - Click "Register" in the navigation
   - Fill in your details
   - Submit the form

2. **Login**:
   - Click "Login" in the navigation
   - Enter your username and password

3. **Browse Menu**:
   - Click "Menu" to view all food items
   - Filter by category (Coffee, Tea, Pastries, etc.)

4. **Add to Cart**:
   - Select quantity
   - Click "Add to Cart"

5. **View Cart**:
   - Click "Cart" to review your items
   - Update quantities or remove items
   - Proceed to checkout

## Sample Products

The database comes pre-loaded with sample products:

- **Coffee**: Espresso, Cappuccino, Latte, Iced Coffee
- **Tea**: Green Tea, Chai Latte
- **Pastries**: Croissant, Chocolate Muffin
- **Sandwiches**: Club Sandwich, BLT
- **Desserts**: Cheesecake, Brownie

## Troubleshooting

### Database Connection Error

If you see "Connection failed":
- Make sure MySQL is running in XAMPP
- Check database credentials in `config.php`
- Ensure the `south_cafe` database exists

### Page Not Found

- Verify the project is in the correct `htdocs` folder
- Check the URL: `http://localhost/South-Cafe-e-Commerce/`
- Ensure Apache is running in XAMPP

### Images Not Showing

- The project uses a placeholder image by default
- To add real product images, place them in the `images/` folder
- Update the database with the correct image filenames

## Technologies Used

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Server**: Apache (XAMPP)

## Security Notes

This is a simple educational project. For production use, consider:
- Using prepared statements (already implemented)
- Adding CSRF protection
- Implementing proper password policies
- Adding input validation and sanitization
- Using HTTPS
- Implementing proper error handling

## License

This project is created for educational purposes.

## Support

For issues or questions, please check:
1. XAMPP is properly installed and running
2. Database is correctly imported
3. Project files are in the correct directory
4. All file permissions are set correctly
