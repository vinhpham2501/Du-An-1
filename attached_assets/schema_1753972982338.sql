-- Create database
CREATE DATABASE IF NOT EXISTS restaurant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    image VARCHAR(255),
    category_id INT NOT NULL,
    unit VARCHAR(50) DEFAULT 'Phần',
    status ENUM('available', 'out_of_stock', 'discontinued') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    delivery_address TEXT NOT NULL,
    delivery_phone VARCHAR(20) NOT NULL,
    delivery_name VARCHAR(255) NOT NULL,
    note TEXT,
    status ENUM('pending', 'preparing', 'delivering', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Insert default admin user
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@restaurant.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('Món chính', 'Các món ăn chính của nhà hàng'),
('Món khai vị', 'Các món ăn khai vị'),
('Món tráng miệng', 'Các món tráng miệng'),
('Đồ uống', 'Các loại đồ uống'),
('Món ăn nhanh', 'Các món ăn nhanh');

-- Insert sample products
INSERT INTO products (name, description, price, sale_price, category_id, unit) VALUES 
('Phở bò', 'Phở bò truyền thống với nước dùng đậm đà', 45000, 40000, 1, 'Tô'),
('Cơm tấm sườn bì chả', 'Cơm tấm với sườn nướng, bì và chả trứng', 35000, NULL, 1, 'Phần'),
('Gỏi cuốn', 'Gỏi cuốn tôm thịt với rau sống', 25000, NULL, 2, 'Phần'),
('Chè ba màu', 'Chè ba màu truyền thống', 15000, NULL, 3, 'Ly'),
('Cà phê sữa đá', 'Cà phê sữa đá Việt Nam', 12000, NULL, 4, 'Ly'),
('Bánh mì thịt nướng', 'Bánh mì với thịt nướng và rau sống', 20000, NULL, 5, 'Cái'),
('Bún chả', 'Bún chả Hà Nội với thịt nướng', 30000, 28000, 1, 'Phần'),
('Gà nướng lá chanh', 'Gà nướng với lá chanh thơm ngon', 120000, NULL, 1, 'Con'),
('Canh chua cá lóc', 'Canh chua cá lóc với rau đồng', 25000, NULL, 2, 'Tô'),
('Kem dừa', 'Kem dừa mát lạnh', 18000, NULL, 3, 'Ly'); 