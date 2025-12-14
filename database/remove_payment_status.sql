-- Xóa cột trang thái thanh toán khỏi bảng DON_HANG
-- Vì bây giờ chỉ cần lưu phương thức thanh toán do người dùng chọn

ALTER TABLE DON_HANG DROP COLUMN IF EXISTS TrangThaiThanhToan;

-- Kiểm tra lại cấu trúc bảng sau khi xóa
DESC DON_HANG;

-- Nếu muốn xem các cột còn lại của bảng
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'DON_HANG' AND TABLE_SCHEMA = 'ecommerce';
