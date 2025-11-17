-- Thêm cột payment_method vào bảng orders nếu chưa có

-- Kiểm tra và thêm cột payment_method
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'cod' AFTER payment_status;

-- Cập nhật các đơn hàng cũ có payment_status = 'pending' thành payment_method = 'cod'
UPDATE orders 
SET payment_method = 'cod' 
WHERE payment_method IS NULL AND payment_status = 'pending';

-- Cập nhật các đơn hàng cũ có payment_status khác thành payment_method tương ứng
UPDATE orders 
SET payment_method = CASE 
    WHEN payment_status = 'bank_transfer' THEN 'bank_transfer'
    WHEN payment_status = 'cod' THEN 'cod'
    ELSE 'cod'
END
WHERE payment_method IS NULL;

SELECT 'Đã thêm cột payment_method vào bảng orders thành công!' as message;
