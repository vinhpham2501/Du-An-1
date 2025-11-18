@echo off
echo === AUTO UPLOAD TO GITHUB: Du-An-1 ===

REM 👉 Chuyển đến folder dự án
cd /d "E:\Du-An-1"

REM Thêm tất cả thay đổi
git add .

REM Commit chỉ khi có thay đổi (M, D, A, U...)
git diff-index --quiet HEAD || git commit -m "Auto update"

REM Đẩy lên GitHub branch main
git push origin main

echo === DONE ===
pause
