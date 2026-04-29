-- Athletiqo Database Setup Script
-- Run this script to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS athletiqo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE athletiqo_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order (order_id)
);

-- Create products table (for future use)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    subcategory VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(500),
    buy_link VARCHAR(500),
    keywords TEXT,
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_subcategory (subcategory),
    INDEX idx_active (is_active)
);

-- Create user_sessions table for enhanced security
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_session (session_id),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
);

-- Insert sample admin user (password: admin123)
INSERT INTO users (name, email, password) VALUES 
('Admin User', 'admin@athletiqo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE email = email;

-- Insert sample products
INSERT INTO products (name, description, category, subcategory, price, image_url, buy_link, keywords, stock_quantity) VALUES 
('Basketball BT900 - Size 7', 'Professional FIBA-approved basketball with superior grip and durability for competitive play.', 'equipment', 'basketball', 1999.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1voFYoBEsu09Wk_ZzJ0Vjfwp8TajnE3snCg&s', 'https://www.decathlon.in/p/8648080/basketball-bt900-size-7fiba-approved-for-boys-and-adults', 'basketball,ball,sports,equipment', 50),
('Cult Men\'s Running Shoes-Off White', 'Professional running shoes with advanced cushioning technology for maximum comfort and performance.', 'wear', 'shoes', 2299.00, 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT-73mzz_opcEj925iNhtiTrMGr2Hw6zfmhMdm38YcIfBe0yrbOJi8fMW5iw48gWyvD7e3YfCnGXpthhxWP8kMxOFAUu5IsImYiY8ViHSc1kA2jscvitd3zTw&usqp=CAc', 'https://cultstore.com/products/cult-men-s-traverse-running-shoes-off-white', 'shoes,running,footwear,sports,wear', 30),
('SS Ikon Kashmir Willow Cricket Bat', 'Premium Kashmir willow bat with traditional craftsmanship for superior performance on the pitch.', 'equipment', 'cricket', 2654.00, 'https://www.sstoncricket.com/wp-content/uploads/2023/04/56A51367-scaled-370x493.jpg', 'https://www.sstoncricket.com/ss-ikon-kashmir-willow-cricket-bat-sh.html', 'cricket,bat,sports,equipment', 25),
('Gym Weight Training Station Full Body Workout at Home', 'Complete full-body workout station for home training. Professional-grade construction for serious athletes.', 'equipment', 'gym', 35999.00, 'https://contents.mediadecathlon.com/p2300404/5f3428e7ca71a6b8d65702dd8bffdcdc/p2300404.jpg', 'https://www.decathlon.in/p/8484134/gym-weight-training-station-full-body-workout-at-home-home-gym-900-black', 'gym,workout,training,equipment,fitness', 10),
('Boldfit Adjustable Hand Grip', 'Perfect for building grip strength and forearm muscles. Adjustable resistance for all fitness levels.', 'equipment', 'gym', 299.00, 'https://m.media-amazon.com/images/I/61eYoqYP2TL._AC_UL480_FMwebp_QL65_.jpg', 'https://amzn.in/d/04hoirIg', 'hand,grip,gym,fitness,equipment', 100),
('DOMYOS - Men Gym Trackpant Convertible', 'Convertible trackpants with zip pockets. Quick-dry fabric perfect for intense workout sessions.', 'wear', 'trackpant', 999.00, 'https://contents.mediadecathlon.com/p2273704/45979084bc75f77fb9b7c3a4efc12751/p2273704.jpg', 'https://www.decathlon.in/p/8731703/men-gym-trackpant-convertible-jog-fit-quick-dry-zip-pockets-500-black', 'trackpant,pants,gym,wear,sports', 40),
('Reebok Unisex Ri Vector Knit Tracktop', 'Premium knit tracktop with moisture-wicking technology. Perfect for outdoor training sessions.', 'wear', 'tracktop', 1799.00, 'https://imagescdn.reebok.in/img/app/product/9/957428-12396402.jpg?auto=format&w=390', 'https://reebok.abfrl.in/p/reebok-unisex-ri-vector-knit-tracktop-957428.html?source=plp', 'tracktop,jacket,wear,sports,reebok', 35),
('Men\'s Full Sleeve Compression T-Shirt', 'Advanced compression technology for muscle support and enhanced blood circulation during workouts.', 'wear', 'tshirt', 899.00, 'https://m.media-amazon.com/images/I/61J2-dVzIgL._SY879_.jpg', 'https://www.amazon.in/FUAARK-Mens-Sleeve-Compression-T-Shirt/dp/B0C5SVCGM6?ref_=ast_sto_dp&th=1&psc=1', 'tshirt,shirt,compression,wear,sports', 60)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Show database setup completion
SELECT 'Database setup completed successfully!' as message;
