-- South Cafe Online Ordering System Database
-- Create database
CREATE DATABASE IF NOT EXISTS south_cafe;
USE south_cafe;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Products table (Food items)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT,
    contact_number VARCHAR(20),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Coffee', 'Rich, aromatic brews crafted to energize, comfort, and elevate your day.'),
('Non-Coffee', 'Refreshing beverages offering flavorful alternatives for non-coffee drink lovers.'),
('Snacks', 'Light, tasty bites perfect for quick cravings anytime of the day.'),
('Rice Meals', 'Hearty rice plates served with savory dishes for complete satisfying meals.'),
('Korean Food', 'Bold, flavorful Korean dishes inspired by authentic spices, sauces, and traditions.');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, image) VALUES
('Hot Coffee', 'Rich and bold espresso shot', 75, 1, 'hot_coffee.jpg'),
('Iced Coffee', 'Cold, refreshing brew over ice', 85, 1, 'iced_coffee.jpg'),
('Hot Flower Tea', 'Warm floral tea for relaxation', 95, 2, 'hot_flower_tea.jpg'),
('Mango Graham', 'Creamy mango layered graham treat', 85, 2, 'mango_graham.jpg'),
('Iced Choco', 'Cold, rich chocolate drink delight', 65, 2, 'iced_choco.jpg'),
('Lemonade', 'Fresh tangy drink with citrus', 85, 2, 'lemonade.jpg'),
('Fruit Shake', 'Cold blended fruits for refreshment', 65, 2, 'fruit_shakes.jpg'),
('Fruit Soda', 'Fruity sparkling drink with fizz', 75, 2, 'fruit_soda.jpg'),
('Red Velvet Classic Milktea', 'Sweet creamy red-velvet milk tea', 95, 2, 'red_velvet.jpg'),
('Cookies and Cream Special Milk Tea', 'Creamy milk tea with cookie bits', 120, 2, 'cookies_&_cream.jpg'),


('Egg Drop Sandwich', 'Toasted bread filled with fluffy eggs and cheese', 115.00, 3, 'eggdrop.jpg'),
('Cheesecake', 'Creamy New York style cheesecake', 5.50, 5, 'cheesecake.jpg'),
('Brownie', 'Fudgy chocolate brownie', 4.00, 5, 'brownie.jpg'),
('American Waffle', 'Crispy waffle served with syrup', 95.00, 3, 'americanwaffle.jpg'),
('Chicken Poppers', 'Crispy fried chicken bites', 155.00, 3, 'chickenpoppers.jpg'),
('Clubhouse Sandwich', 'Triple-layered sandwich with assorted fillings', 165.00, 3, 'clubhousesandwich.jpg'),
('French Fries', 'Golden crispy fries', 70.00, 3, 'fries.jpg'),
('Fries Overload', 'Loaded fries with cheese and bacon', 155.00, 3, 'overloadfries.jpg'),
('Grilled Cheese Sandwich', 'Melted cheese between toasted bread', 135.00, 3, 'grilledcheese.jpg'),
('Nachos Overload', 'Tortilla chips topped with cheese, jalapenos, and more', 145.00, 3, 'nachos.jpg'),
('Potato Wedges', 'Seasoned potato wedges', 135.00, 3, 'potato.jpg'),
('Special Clubhouse Sandwich', 'Deluxe club sandwich with extra fillings', 265.00, 3, 'special.jpg'),



('Cornbeef', 'Savory canned cornbeef', 180, 4, 'cornbeef.jpg'),
('Hungarian Sausage', 'Spicy smoked sausage', 180, 4, 'sausage.jpg'),
('Pepper Beef', 'Tender beef with pepper', 190, 4, 'pepper.jpg'),
('Spam', 'Classic canned meat', 180, 4, 'spam.jpg'),


('Chadolbagi', 'Thinly sliced beef brisket', 185, 5, 'Chadolbagi Meal.jpg'),
('Chapagetti', 'Korean-style instant noodles', 119, 5, 'Chapagetti.jpg'),
('Cheesy Ramen', 'Creamy cheese-flavored ramen', 129, 5, 'Cheesy Ramen.jpg'),
('Kimchi', 'Spicy fermented cabbage', 85, 5, 'Kimchi.jpg'),
('Nori(Seaweed)', 'Crispy roasted seaweed', 40, 5, 'Nori(Seaweed.jpg'),
('Ramyun Special', 'Loaded Korean instant ramen', 139, 5, 'Ramyun Special.jpg'),
('Samgyupsal Meal', 'Grilled pork belly slices', 165, 5, 'Samgyupsal Meal.jpg'),
('Samyang', 'Spicy Korean instant noodles', 139, 5, 'Samyang.jpg');