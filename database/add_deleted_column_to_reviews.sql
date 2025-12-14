-- Thêm cột DaXoa để đánh dấu đánh giá bị xóa (soft delete)
ALTER TABLE BINH_LUAN_DANH_GIA 
ADD COLUMN DaXoa TINYINT(1) DEFAULT 0 COMMENT 'Đánh dấu đánh giá bị xóa bởi user (0: chưa xóa, 1: đã xóa)';

-- Tạo index để tối ưu query
CREATE INDEX idx_daxoa ON BINH_LUAN_DANH_GIA(DaXoa);

