-- Tạo database ecommerce nếu chưa có
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    category_id INT,
    is_available BOOLEAN DEFAULT true,
    is_featured BOOLEAN DEFAULT false,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Bảng orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'delivering', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'cash', 'bank_transfer') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'cod',
    delivery_address TEXT NOT NULL,
    delivery_phone VARCHAR(20) NOT NULL,
    delivery_name VARCHAR(100) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu cho categories
INSERT INTO categories (name, description) VALUES
('Món chính', 'Các món ăn chính ngon miệng'),
('Món khai vị', 'Các món khai vị hấp dẫn'),
('Món tráng miệng', 'Các món tráng miệng ngọt ngào'),
('Đồ uống', 'Các loại đồ uống giải khát'),
('Món chay', 'Các món ăn chay thanh đạm'),
('Món nướng', 'Các món nướng thơm ngon');

-- Thêm dữ liệu mẫu cho products
INSERT INTO products (name, description, price, category_id, is_available, is_featured) VALUES
-- Món chính
('Phở bò', 'Phở bò truyền thống với nước dùng đậm đà, bánh phở dai ngon', 45000, 1, true, true),
('Bún chả', 'Bún chả Hà Nội với thịt nướng thơm ngon, nước mắm pha chế đặc biệt', 35000, 1, true, true),
('Cơm tấm sườn nướng', 'Cơm tấm với sườn nướng thơm ngon, bì chả trứng', 40000, 1, true, false),
('Bún bò Huế', 'Bún bò Huế cay nồng với nước dùng đậm đà', 38000, 1, true, true),
('Cơm gà xối mỡ', 'Cơm gà xối mỡ với da gà giòn tan', 42000, 1, true, false),
('Bún riêu cua', 'Bún riêu cua với nước dùng ngọt thanh', 32000, 1, true, false),
('Cơm tấm chả trứng', 'Cơm tấm với chả trứng thơm ngon', 35000, 1, true, false),
('Bún thịt nướng', 'Bún thịt nướng với nước mắm pha chế đặc biệt', 30000, 1, true, false),

-- Món khai vị
('Gỏi cuốn', 'Gỏi cuốn tôm thịt tươi ngon với bánh tráng và rau sống', 25000, 2, true, false),
('Chả giò', 'Chả giò giòn tan với nhân thịt tôm', 20000, 2, true, true),
('Gỏi gà', 'Gỏi gà với rau răm, hành tây giòn ngon', 28000, 2, true, false),
('Chả cá', 'Chả cá Lã Vọng đặc biệt với nghệ và thì là', 55000, 2, true, true),
('Nem nướng', 'Nem nướng Nha Trang với bánh tráng và rau sống', 30000, 2, true, false),
('Gỏi bưởi', 'Gỏi bưởi với tôm khô và rau răm', 22000, 2, true, false),

-- Món tráng miệng
('Chè ba màu', 'Chè ba màu truyền thống với đậu xanh, bột báng', 15000, 3, true, false),
('Chè hạt sen', 'Chè hạt sen long nhãn thanh mát', 18000, 3, true, true),
('Bánh flan', 'Bánh flan mềm mịn với caramel', 12000, 3, true, false),
('Chè đậu đỏ', 'Chè đậu đỏ với nước cốt dừa', 16000, 3, true, false),
('Bánh tiramisu', 'Bánh tiramisu với cà phê và mascarpone', 25000, 3, true, true),
('Chè sầu riêng', 'Chè sầu riêng với nước cốt dừa', 20000, 3, true, false),

-- Đồ uống
('Cà phê sữa đá', 'Cà phê sữa đá Việt Nam truyền thống', 12000, 4, true, false),
('Trà sữa', 'Trà sữa trân châu đường đen', 25000, 4, true, true),
('Nước mía', 'Nước mía tươi mát lạnh', 8000, 4, true, false),
('Sinh tố bơ', 'Sinh tố bơ với sữa đặc', 22000, 4, true, false),
('Nước cam', 'Nước cam tươi nguyên chất', 15000, 4, true, false),
('Cà phê đen', 'Cà phê đen đậm đà', 10000, 4, true, false),

-- Món chay
('Phở chay', 'Phở chay với nước dùng rau củ thanh đạm', 35000, 5, true, false),
('Bún chay', 'Bún chay với chả chay và rau sống', 28000, 5, true, false),
('Cơm chay', 'Cơm chay với các món chay đa dạng', 30000, 5, true, true),
('Gỏi chay', 'Gỏi chay với đậu phụ và rau củ', 20000, 5, true, false),
('Canh chua chay', 'Canh chua chay với đậu bắp và cà chua', 25000, 5, true, false),

-- Món nướng
('Thịt nướng', 'Thịt heo nướng với nước mắm pha chế', 45000, 6, true, true),
('Gà nướng', 'Gà nướng với da giòn tan', 55000, 6, true, false),
('Tôm nướng', 'Tôm nướng với muối ớt', 60000, 6, true, true),
('Cá nướng', 'Cá nướng với nghệ và thì là', 50000, 6, true, false),
('Bò nướng', 'Bò nướng với nước mắm pha chế', 65000, 6, true, true);

-- Thêm user admin mẫu (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@restaurant.com', 'admin123', 'Administrator', 'admin'); 