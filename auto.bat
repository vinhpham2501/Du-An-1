@echo off
echo === AUTO UPLOAD TO GITHUB: Du-An-1 ===

REM 👉 Chuyển đến folder dự án của bạn
cd "E:\Du-An-1"

REM Thêm tất cả thay đổi
git add .

REM Chỉ commit nếu có thay đổi thực sự
git diff-index --quiet HEAD || git commit -m "Auto update"

REM Đẩy lên GitHub
git push origin main

echo === DONE ===
pause
