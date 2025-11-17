-- Script thêm món ăn nổi bật vào database
-- Chạy script này để thêm dữ liệu cho slider homepage

-- Thêm danh mục nếu chưa có
INSERT IGNORE INTO categories (id, name, description, created_at, updated_at) VALUES
(1, 'Món chính', 'Các món ăn chính phong phú và đa dạng', NOW(), NOW()),
(2, 'Phở', 'Các loại phở truyền thống Việt Nam', NOW(), NOW()),
(3, 'Bún', 'Các món bún đặc sắc từ các vùng miền', NOW(), NOW()),
(4, 'Bánh mì', 'Bánh mì Việt Nam với nhiều loại nhân', NOW(), NOW()),
(5, 'Cơm', 'Các món cơm đậm đà hương vị', NOW(), NOW());

-- Thêm sản phẩm nổi bật cho slider (phù hợp với cấu trúc bảng hiện có)
INSERT INTO products (name, description, price, sale_price, image_url, category_id, is_available, is_featured, stock_quantity, created_at, updated_at) VALUES
-- Phở Bò Đặc Biệt
('Phở Bò Đặc Biệt', 
 'Hương vị truyền thống với nước dùng niêu trong suốt 24 giờ, thịt bò tươi ngon và bánh phở dai ngon. Phở được chế biến theo công thức gia truyền với các loại gia vị tự nhiên, tạo nên hương vị đậm đà khó quên.', 
 120000, 
 89000,
 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 
 2, 
 1, 
 1,
 50,
 NOW(), 
 NOW()),

-- Bún Bò Huế Cay
('Bún Bò Huế Cay', 
 'Hương vị đậm đà từ miền Trung với nước dùng cay nồng, thịt bò và chả cua thơm ngon. Món ăn mang đậm nét văn hóa ẩm thực cố đô Huế với màu đỏ đặc trưng từ ớt và mắm ruốc.', 
 75000, 
 75000,
 'https://images.unsplash.com/photo-1555126634-323283e090fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 
 3, 
 1, 
 1,
 30,
 NOW(), 
 NOW()),

-- Bánh Mì Hải Sản
('Bánh Mì Hải Sản', 
 'Bánh mì giòn rụm với nhân hải sản tươi ngon: tôm, mực, cua được chế biến đậm đà. Kết hợp hoàn hảo giữa bánh mì Pháp và hương vị biển cả Việt Nam, tạo nên món ăn độc đáo và hấp dẫn.', 
 80000, 
 65000,
 'https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 
 4, 
 1, 
 1,
 25,
 NOW(), 
 NOW()),

-- Cơm Tấm Sườn Nướng
('Cơm Tấm Sườn Nướng', 
 'Cơm tấm thơm ngon với sườn nướng mật ong, chả trứng, bì và nước mắm pha chua ngọt. Món ăn dân dã của miền Nam với hương vị đậm đà, sườn nướng thơm lừng và cơm tấm dẻo ngon.', 
 110000, 
 85000,
 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 
 5, 
 1, 
 1,
 40,
 NOW(), 
 NOW());

-- Thêm một số món ăn khác để làm phong phú thực đơn
INSERT INTO products (name, description, price, sale_price, image_url, category_id, is_available, is_featured, stock_quantity, created_at, updated_at) VALUES
('Phở Gà', 'Phở gà thanh đạm với nước dùng trong vắt và thịt gà thơm ngon', 65000, 65000, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 2, 1, 0, 35, NOW(), NOW()),
('Bún Chả Hà Nội', 'Bún chả truyền thống Hà Nội với thịt nướng thơm lừng', 70000, 70000, 'https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 3, 1, 0, 20, NOW(), NOW()),
('Bánh Mì Thịt Nướng', 'Bánh mì với thịt nướng BBQ đậm đà hương vị', 45000, 45000, 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 4, 1, 0, 50, NOW(), NOW()),
('Cơm Gà Xối Mỡ', 'Cơm gà Hải Nam với gà luộc mềm và cơm thơm béo', 80000, 80000, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 5, 1, 0, 15, NOW(), NOW()),
('Phở Tái Nạm', 'Phở bò với tái và nạm, hương vị đậm đà truyền thống', 75000, 75000, 'https://images.unsplash.com/photo-1547592166-23ac45744acd?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 2, 1, 0, 30, NOW(), NOW()),
('Bún Riêu Cua', 'Bún riêu cua đồng với nước dùng chua ngọt đậm đà', 68000, 68000, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 3, 1, 0, 25, NOW(), NOW()),
('Bánh Mì Pate', 'Bánh mì pate truyền thống với pate gan và rau thơm', 35000, 35000, 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 4, 1, 0, 60, NOW(), NOW()),
('Cơm Chiên Dương Châu', 'Cơm chiên Dương Châu với tôm, xúc xích và trứng', 75000, 75000, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 5, 1, 0, 20, NOW(), NOW()),
('Phở Chay', 'Phở chay với nước dùng từ rau củ và đậu hũ', 55000, 55000, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 2, 1, 0, 40, NOW(), NOW()),
('Bún Bò Nam Bộ', 'Bún bò Nam Bộ với thịt bò xào và rau sống', 72000, 72000, 'https://images.unsplash.com/photo-1555126634-323283e090fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80', 3, 1, 0, 18, NOW(), NOW());

-- Thông báo hoàn thành
SELECT 'Đã thêm thành công các món ăn nổi bật vào database!' as message;
