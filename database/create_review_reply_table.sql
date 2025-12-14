-- Tạo bảng lưu phản hồi của admin cho đánh giá/bình luận
-- Yêu cầu: mỗi đánh giá (MaBL) có tối đa 1 phản hồi active (TrangThai = 1)

CREATE TABLE IF NOT EXISTS BINH_LUAN_DANH_GIA_TRA_LOI (
    MaBL INT NOT NULL,
    MaAdmin INT NULL,
    NoiDung TEXT NOT NULL,
    NgayDang DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TrangThai TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (MaBL)
);

-- Nếu muốn lưu admin_id theo bảng KHACH_HANG (admin cũng là user),
-- bạn có thể thêm foreign key tùy theo schema DB của bạn.
