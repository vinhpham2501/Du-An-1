# Phân công: Phạm Trường Vinh

Tài liệu này tổng hợp **tất cả trang liên quan** và **luồng xử lý (Route → Controller → Model → View → JS/AJAX)** cho các chức năng mà thành viên **Phạm Trường Vinh** phụ trách.

---

## 1) Phạm vi chức năng

- **Frontend (khách hàng)**
  - Trang chủ
  - Danh sách sản phẩm + lọc/tìm kiếm
  - Chi tiết sản phẩm (phần hiển thị + điểm nối sang giỏ hàng/đặt hàng)
  - Giỏ hàng (thêm/cập nhật/xóa/clear)
  - Thanh toán (checkout) + trang thành công
  - Đơn hàng của tôi + chi tiết đơn + hủy đơn

- **Admin**
  - Dashboard thống kê
  - Quản lý đơn hàng (danh sách + chi tiết + cập nhật trạng thái + xóa theo điều kiện)

> Không bao gồm: đăng nhập/đăng ký, quản lý user, quản lý sản phẩm/danh mục, liên hệ, đánh giá.

---

## 2) Danh sách trang/route liên quan

Nguồn khai báo route: `app/Core/App.php` (method `setupRoutes()`).

### 2.1 Frontend

- **Trang chủ**
  - `GET /` → `HomeController@index`
  - View: `app/Views/home/index.php`

- **Danh sách sản phẩm**
  - `GET /products` → `HomeController@products`
  - View: `app/Views/home/products.php`
  - Query params thường dùng: `page`, `sort`, `category_id`, `search`, `price_max`

- **Chi tiết sản phẩm**
  - `GET /product/{id}` → `ProductController@detail`
  - View: `app/Views/product/detail.php`

- **Giỏ hàng**
  - `GET /cart` → `CartController@index`
  - View: `app/Views/cart/index.php`

- **Giỏ hàng (AJAX)**
  - `POST /cart/add` → `CartController@add` (JSON)
  - `POST /cart/update` → `CartController@update` (JSON)
  - `POST /cart/remove` → `CartController@remove` (JSON)
  - `POST /cart/clear` → `CartController@clear` (JSON)
  - `GET /cart/count` → `CartController@getCount` (JSON)

- **Checkout / Thanh toán**
  - `GET /checkout` → `OrderController@checkout`
  - View: `app/Views/order/checkout.php`
  - `POST /checkout` → `OrderController@placeOrder`
  - View thành công: `app/Views/order/success.php`

- **Đơn hàng của tôi**
  - `GET /my-orders` → `OrderController@myOrders`
  - View: `app/Views/order/my-orders.php`

- **Chi tiết đơn hàng (user)**
  - `GET /orders/{id}` → `OrderController@orderDetail`
  - View: `app/Views/order/detail.php`

- **Hủy đơn (user)**
  - `POST /orders/cancel` → `OrderController@cancelOrder` (JSON)


### 2.2 Admin

- **Dashboard Admin**
  - `GET /admin/dashboard` → `Admin\DashboardController@index`
  - View: `app/Views/admin/dashboard.php`

- **API thống kê dashboard**
  - `GET /admin/statistics` → `Admin\DashboardController@statistics` (JSON)

- **Đơn hàng (Admin)**
  - `GET /admin/orders` → `Admin\OrderController@index`
  - View: `app/Views/admin/orders/index.php`

  - `GET /admin/orders/{id}` → `Admin\OrderController@show`
  - View: `app/Views/admin/orders/show.php`

  - `POST /admin/orders/{id}/update-status` → `Admin\OrderController@updateStatus` (JSON)

  - `POST /admin/orders/{id}/delete` → `Admin\OrderController@delete` (JSON)
  - Ràng buộc: chỉ xóa khi trạng thái `cancelled`.

---

## 3) Cây thư mục (các file liên quan trực tiếp)

```text
app/
  Core/
    App.php
    Controller.php
  Controllers/
    HomeController.php
    CartController.php
    ProductController.php
    OrderController.php
    Admin/
      DashboardController.php
      OrderController.php
  Models/
    Product.php
    Category.php
    Order.php
    OrderItem.php
    Address.php
  Views/
    layouts/
      app.php
      admin.php
    home/
      index.php
      products.php
      product.php
    product/
      detail.php
    cart/
      index.php
    order/
      checkout.php
      success.php
      my-orders.php
      detail.php
    admin/
      dashboard.php
      orders/
        index.php
        show.php
public/
  index.php
  js/
    app.js
```

---

## 4) Luồng xử lý chi tiết theo chức năng

> Mục này đã mô tả luồng tổng quan. Bên dưới mỗi chức năng có bổ sung thêm:
> - **Kỹ thuật code**: dữ liệu vào/ra, cấu trúc session, payload AJAX, nơi render.
> - **Logic nghiệp vụ**: các ràng buộc trạng thái/điều kiện theo yêu cầu bài toán.

### 4.1 Trang chủ (Home)

- **Route:** `GET /`
- **Controller:** `app/Controllers/HomeController.php` → `index()`
- **Models:**
  - `Product`: `getAll()`, `count()`, `getFeaturedProducts()`, `getTopSelling()`, `getSaleProducts()`, `getNewProducts()`
  - `Category`: `getAll()`
- **View:** `app/Views/home/index.php`
- **Luồng:**
  1. Nhận query `page`, `sort`, `category_id`, `search`.
  2. Build `$filters` (limit/offset/sort + điều kiện).
  3. Load dữ liệu sản phẩm + các section (featured/topSelling/sale/new).
  4. Render view + phân trang.

- **Kỹ thuật code**
  - Nơi xử lý: `app/Controllers/HomeController.php@index`.
  - Phân trang:
    - `itemsPerPage = 12`
    - `offset = (page - 1) * itemsPerPage`
  - Dữ liệu section trang chủ:
    - Hero/featured: `Product->getFeaturedProducts(4)`
    - Bán chạy: `Product->getTopSelling(8)`
    - Sale: `Product->getSaleProducts(8)`
    - Mới: `Product->getNewProducts(8)`
  - View nhận các biến:
    - `$products`, `$categories`, `$featuredProducts`, `$topSellingProducts`, `$saleProducts`, `$newProducts`, `$pagination`

- **Logic nghiệp vụ**
  - Hiển thị sản phẩm dựa trên filter (danh mục/tìm kiếm/sort).
  - Sản phẩm không khả dụng (theo `TrangThai/is_available`) thường sẽ được model loại khỏi danh sách (tùy cách query trong `Product` model).


### 4.2 Danh sách sản phẩm (Products)

- **Route:** `GET /products`
- **Controller:** `HomeController@products`
- **Models:** `Product`, `Category`
- **View:** `app/Views/home/products.php`
- **Luồng:**
  1. Nhận filter: `category_id`, `search`, `price_max`, `sort`, `page`.
  2. `Product->getAll($filters)` để lấy list.
  3. `Product->count($filters)` để tính phân trang.
  4. Render view.

- **Kỹ thuật code**
  - Nơi xử lý: `HomeController@products`.
  - Query params:
    - `category_id`: lọc theo danh mục
    - `search`: tìm theo tên
    - `price_max`: lọc giá tối đa
    - `sort`: `newest` (mặc định) và các kiểu khác tùy implement trong `Product->getAll()`
  - Pagination giống trang chủ: `limit=12`, `offset` theo `page`.

- **Logic nghiệp vụ**
  - Cho phép user lọc/tìm kiếm mà không cần đăng nhập.
  - Nếu không có kết quả → view hiển thị trạng thái rỗng.


### 4.3 Chi tiết sản phẩm (Product detail)

- **Route:** `GET /product/{id}`
- **Controller:** `app/Controllers/ProductController.php` → `detail($id)`
- **Models:**
  - `Product` (thông tin sản phẩm)
  - `ProductImage`, `ProductColor`, `ProductSize` (ảnh/biến thể)
  - `Review` (tổng sao + list đánh giá; dùng để hiển thị)
  - `Category` (breadcrumb)
- **View:** `app/Views/product/detail.php`
- **Luồng:**
  1. `Product->findById($id)`; không có → render 404.
  2. Load ảnh/màu/size theo sản phẩm.
  3. Tính tổng rating: `Review->getAverageRating($id)`.
  4. Lấy list review: `Review->findByProductId($id, 10)`.
  5. Lấy sản phẩm liên quan trong cùng danh mục.
  6. Render view.

- **Kỹ thuật code**
  - Nơi xử lý: `app/Controllers/ProductController.php@detail($id)`.
  - View `app/Views/product/detail.php` dùng:
    - `$product` (có bổ sung `avg_rating`, `total_reviews` ngay trong controller)
    - `$images`, `$colors`, `$sizes`
    - `$reviews` (tối đa 10)
    - `$canReview` (true/false)
  - Helper ảnh:
    - `\App\Helpers\ImageHelper::getImageSrc(...)` normalize path ảnh.

- **Logic nghiệp vụ**
  - Sản phẩm không tồn tại → trả 404.
  - Điều kiện đánh giá (biến `$canReview`):
    - User phải đăng nhập.
    - User phải đã mua sản phẩm.
    - User chưa đánh giá sản phẩm trước đó.
  - Sản phẩm liên quan lấy cùng danh mục và loại trừ chính nó.


### 4.4 Giỏ hàng (Cart)

- **Route:** `GET /cart`
- **Controller:** `app/Controllers/CartController.php` → `index()`
- **Model:** `Product`
- **View:** `app/Views/cart/index.php`
- **Luồng:**
  1. Đọc `$_SESSION['cart']`.
  2. Với mỗi item, parse key dạng `productId_color_size`.
  3. `Product->findById(productId)` → lấy giá, ảnh, tên.
  4. Tính `item_total`, `total`.
  5. Render view.

- **Kỹ thuật code**
  - Session cart đang dùng cấu trúc:
    - `$_SESSION['cart'][cartKey] = ['product_id' => int, 'color' => string, 'size' => string, 'quantity' => int]`
    - `cartKey = productId + '_' + color + '_' + size`
  - View `cart/index.php`:
    - Render từng item, có input number (min=1, max=5).
    - Gọi AJAX qua `fetch()` tới `/cart/update`, `/cart/remove`, `/cart/clear`.
    - Sau khi cập nhật thường `location.reload()` để tính lại tổng.

- **Logic nghiệp vụ**
  - Giới hạn số lượng mua tối đa **5 sản phẩm / 1 loại biến thể** (theo controller + view cảnh báo).
  - Nếu giỏ trống → hiển thị trạng thái rỗng + link về `/products`.


### 4.5 Giỏ hàng (AJAX add/update/remove/clear)

- **Route:** `POST /cart/add`
- **Controller:** `CartController@add`
- **Luồng:**
  1. Đọc JSON body hoặc `$_POST`.
  2. Validate `product_id`, `quantity`.
  3. Load sản phẩm: `Product->findById()` và kiểm tra `is_available`.
  4. Tạo `cartKey = productId_color_size`, cập nhật `$_SESSION['cart'][cartKey]`.
  5. Tính `cartCount` → trả JSON.

- **Route:** `POST /cart/update` / `POST /cart/remove` / `POST /cart/clear`
- **Controller:** `CartController@update/remove/clear`
- **JS gọi:** nằm trong `app/Views/cart/index.php` (fetch tới các endpoint trên).

- **Kỹ thuật code**
  - `POST /cart/add`:
    - Accept cả JSON body (từ fetch) và form-urlencoded.
    - Response JSON mẫu:
      - `success: boolean`
      - `message: string`
      - `cartCount: number`
  - `POST /cart/update`:
    - Body: `cart_key`, `quantity`
  - `POST /cart/remove`:
    - Body: `cart_key`
  - `POST /cart/clear`:
    - Không cần body

- **Logic nghiệp vụ**
  - Không cho thêm nếu:
    - `product_id` rỗng
    - `quantity < 1`
    - sản phẩm không tồn tại hoặc không khả dụng
  - **Giới hạn 5/sp**:
    - Nếu tổng quantity sau khi cộng vượt 5 → trả JSON lỗi và không thêm.


### 4.6 Checkout / Đặt hàng

- **Routes:**
  - `GET /checkout` → `OrderController@checkout`
  - `POST /checkout` → `OrderController@placeOrder`
- **Controller:** `app/Controllers/OrderController.php`
- **Models:** `Order`, `OrderItem`, `Product`, `Address`
- **Views:**
  - `app/Views/order/checkout.php`
  - `app/Views/order/success.php`

- **Luồng GET (`checkout`)**
  1. `requireAuth()`.
  2. Nếu cart trống → redirect `/cart`.
  3. Tạo `cartItems` + `total` bằng cách load `Product->findById()`.
  4. Lấy `savedInfo` (địa chỉ/điện thoại gợi ý) → render `checkout.php`.

- **Luồng POST (`placeOrder`)**
  1. Validate thông tin giao hàng.
  2. Lưu/cập nhật địa chỉ mặc định: `Address->createOrUpdateDefault(...)`.
  3. Tạo đơn: `Order->create($orderData)`.
  4. Tạo chi tiết đơn: `OrderItem->createMultiple($orderId, $cartItems)`.
  5. Xóa `$_SESSION['cart']`.
  6. Render `success.php`.

- **Kỹ thuật code**
  - `checkout.php` submit form `POST /checkout` gồm:
    - `delivery_name`, `delivery_phone`, `delivery_address`, `note`, `payment_method`
  - Controller tạo `$orderData`:
    - `user_id`, `address_id`, `total_amount`, `notes`, `status='pending'`, `payment_method`
  - Model `Order` lưu trạng thái về DB theo nhãn VN (qua `mapStatus`):
    - internal `pending` → DB `Chờ duyệt`
    - internal `completed` → DB `Hoàn tất`

- **Logic nghiệp vụ**
  - User phải đăng nhập mới được checkout.
  - Nếu cart trống → redirect về `/cart`.
  - Validate form giao hàng bắt buộc 3 trường: tên / SĐT / địa chỉ.
  - Giới hạn 5/sp vẫn áp dụng (controller có check danh sách sản phẩm vượt ngưỡng trước khi tạo đơn).


### 4.7 Đơn hàng của tôi (User orders)

- **Route:** `GET /my-orders`
- **Controller:** `OrderController@myOrders`
- **Model:** `Order`, `Address`
- **View:** `app/Views/order/my-orders.php`
- **Luồng:**
  1. `requireAuth()`.
  2. `Order->getByUserId($_SESSION['user_id'])`.
  3. Chuẩn hóa thông tin giao hàng (fallback từ session + địa chỉ mặc định).
  4. Render view.

- **Kỹ thuật code**
  - View `order/my-orders.php`:
    - Nút “Chi tiết” → `/orders/{id}`
    - Nút “Hủy đơn” gọi `POST /orders/cancel` (AJAX)

- **Logic nghiệp vụ**
  - Chỉ hiển thị các đơn thuộc `user_id` đang đăng nhập.
  - Chỉ cho hủy đơn khi trạng thái đang ở bước đầu (pending/confirmed tùy logic ở controller).


### 4.8 Chi tiết đơn hàng (User)

- **Route:** `GET /orders/{id}`
- **Controller:** `OrderController@orderDetail($id)`
- **Models:** `Order`, `Address`
- **View:** `app/Views/order/detail.php`
- **Luồng:**
  1. `requireAuth()`.
  2. `Order->findById($id)` và check owner (`user_id` trong đơn phải trùng session).
  3. Chuẩn hóa trạng thái đơn (`Order->normalizeStatus`).
  4. Load items: `Order->getOrderItems($id)`.
  5. Render view (có nút hủy nếu `pending`, và link đánh giá nếu `completed`).

- **Kỹ thuật code**
  - View `order/detail.php`:
    - Hiển thị timeline trạng thái.
    - JS `cancelOrder()` gọi `POST /orders/cancel`.
    - Nút “Đặt lại đơn hàng này” (reorder) sẽ gọi nhiều lần `/cart/add` để add lại từng item.

- **Logic nghiệp vụ**
  - Chỉ chủ đơn được xem.
  - Nút hủy chỉ hiện khi `status === 'pending'`.
  - Nút đánh giá chỉ gợi ý khi `status === 'completed'`.


### 4.9 Hủy đơn hàng (User)

- **Route:** `POST /orders/cancel`
- **Controller:** `OrderController@cancelOrder`
- **Model:** `Order`
- **JS gọi:** `app/Views/order/my-orders.php` và `app/Views/order/detail.php`
- **Luồng:**
  1. `requireAuth()`.
  2. Verify đơn tồn tại và thuộc user.
  3. Chỉ cho hủy khi trạng thái `pending` hoặc `confirmed`.
  4. Update status qua `Order->updateStatus($orderId, 'cancelled')`.
  5. Trả JSON.

- **Kỹ thuật code**
  - Request body: `order_id=...`.
  - Response JSON:
    - `success: boolean`
    - `message: string`

- **Logic nghiệp vụ**
  - Không cho hủy nếu đơn đang chuẩn bị/giao/hoàn thành.
  - Trạng thái sau hủy là `cancelled` (DB sẽ map về `Hủy`).


### 4.10 Dashboard Admin

- **Routes:**
  - `GET /admin/dashboard` → `Admin\DashboardController@index`
  - `GET /admin/statistics` → `Admin\DashboardController@statistics` (JSON)
- **Models:** `Order`, `Product`, `User`, `Category`
- **View:** `app/Views/admin/dashboard.php`
- **Luồng `index`:**
  1. `requireAdmin()`.
  2. Nhận `date_from`, `date_to` (mặc định 30 ngày).
  3. Thống kê: `Order->getStatistics($filters)`, `Order->getDailyRevenueByRange(...)`.
  4. Top sản phẩm: `Product->getTopSelling(5)`.
  5. Recent orders: `Order->getAll(limit=10, date_from/date_to)`.
  6. Render view + chart.

- **Luồng `statistics` (AJAX):**
  1. Nhận `range` hoặc `date_from/date_to`.
  2. Tính summary so sánh kỳ trước: `Order->getSummaryWithComparison(...)`.
  3. Trả JSON cho chart update.

- **Kỹ thuật code**
  - View `admin/dashboard.php` dùng Chart.js và gọi AJAX:
    - `fetch('/admin/statistics?...')`
  - Response JSON gồm:
    - `summary.current`/`summary.previous`/`summary.change_pct`
    - `dailyRevenue[]` (date, orders, revenue)

- **Logic nghiệp vụ**
  - Thống kê doanh thu chỉ tính cho đơn có trạng thái hoàn tất/hoàn thành (`completed` hoặc nhãn VN tương đương).
  - Không yêu cầu đăng nhập user thường, nhưng admin bắt buộc `requireAdmin()`.


### 4.11 Quản lý đơn hàng (Admin)

- **Routes:**
  - `GET /admin/orders` → `Admin\OrderController@index`
  - `GET /admin/orders/{id}` → `Admin\OrderController@show`
  - `POST /admin/orders/{id}/update-status` → `Admin\OrderController@updateStatus` (JSON)
  - `POST /admin/orders/{id}/delete` → `Admin\OrderController@delete` (JSON)

- **Views:**
  - `app/Views/admin/orders/index.php`
  - `app/Views/admin/orders/show.php`

- **Luồng list (`index`)**
  1. `requireAdmin()`.
  2. Nhận filter: `status`, `date_from`, `date_to`.
  3. `Order->getAll($filters)` và `Order->count($filters)`.
  4. Render bảng đơn hàng.

- **Luồng detail (`show`)**
  1. Load order: `Order->findById($id)`.
  2. Load user/address để hiển thị thông tin giao.
  3. Chuẩn hóa status: `Order->normalizeStatus()`.
  4. Load items: `Order->getOrderItems($id)`.
  5. Render view; JS gửi request cập nhật status.

- **Luồng cập nhật trạng thái (`updateStatus`)**
  1. Validate method POST.
  2. Validate status thuộc tập: `pending/confirmed/preparing/delivering/completed/cancelled`.
  3. Đọc trạng thái hiện tại, kiểm tra transition qua `Order->canTransitionStatus()`.
  4. Update DB: `Order->updateStatus($id, $target)`.
  5. Trả JSON.

- **Luồng xóa đơn (`delete`)**
  1. Validate method POST.
  2. Load order và normalize status.
  3. **Chỉ cho xóa khi `cancelled`** (đã chặn xóa đơn hoàn thành).
  4. `Order->deleteOrderItems($id)` rồi `Order->delete($id)`.
  5. Trả JSON.

- **Kỹ thuật code**
  - List view `admin/orders/index.php`:
    - Có filter `status`, `date_from`, `date_to`.
    - Nút delete chỉ render khi `status === 'cancelled'`.
    - JS `deleteOrder(orderId)` gọi `POST /admin/orders/{id}/delete`.
  - Detail view `admin/orders/show.php`:
    - Form update status dùng `fetch('/admin/orders/{id}/update-status')`.
    - UI disable update khi đơn ở terminal state `completed/cancelled`.
  - Model `Order`:
    - `normalizeStatus()` map nhãn VN ↔ internal.
    - `canTransitionStatus()` chặn chuyển trạng thái sai luồng.

- **Logic nghiệp vụ**
  - Luồng trạng thái hợp lệ:
    - `pending` → `confirmed` → `preparing` → `delivering` → `completed`
    - Cho phép hủy (`cancelled`) từ `pending` hoặc `confirmed`.
    - Khi đã `completed` hoặc `cancelled` thì **khóa** (không đổi trạng thái nữa).
  - Xóa đơn:
    - Chỉ cho xóa đơn **đã hủy**.
    - Không cho xóa đơn đã hoàn thành.

---

## 5) Layout/khung giao diện liên quan

- Frontend layout: `app/Views/layouts/app.php`
- Admin layout: `app/Views/layouts/admin.php`
- Entry point: `public/index.php`

---

## 6) Checklist test nhanh (để báo cáo)

- Frontend
  - Vào `/` xem list sản phẩm + phân trang.
  - Vào `/products?search=...&category_id=...`.
  - Vào `/product/{id}` và thử thêm vào giỏ.
  - Vào `/cart` thử update/remove/clear.
  - Vào `/checkout` đặt hàng → `/order/success`.
  - Vào `/my-orders` xem list, vào `/orders/{id}`, thử hủy đơn khi còn pending.

- Admin
  - Vào `/admin/dashboard` và đổi range.
  - Vào `/admin/orders` lọc theo status/date.
  - Vào `/admin/orders/{id}` cập nhật status theo step.
  - Kiểm tra xóa đơn: chỉ xóa được đơn **đã hủy**.

---

## 7) FAQ / Vấn đáp nhanh (khi giáo viên hỏi)

### 7.1 Dashboard lấy dữ liệu ở đâu? Truy vấn ở đâu?

- **Route**
  - Trang dashboard: `GET /admin/dashboard` (khai báo trong `app/Core/App.php`).
  - API cập nhật số liệu: `GET /admin/statistics` (khai báo trong `app/Core/App.php`).

- **Controller (nơi “đổ dữ liệu” vào view)**
  - File: `app/Controllers/Admin/DashboardController.php`
  - Hàm: `index()`
    - Nhận `date_from`, `date_to` từ query, mặc định 30 ngày.
    - Gọi model và truyền dữ liệu qua:
      - `render('admin/dashboard', [...])`
      - Các biến chính: `stats`, `dailyRevenue`, `topProducts`, `recentOrders`, `categories`, `totalUsers`, `totalProducts`, `totalCategories`.

- **Model (nơi chạy SQL)**
  - Doanh thu/đơn hàng (dashboard): `app/Models/Order.php`
    - `getStatistics($filters)`
    - `getDailyRevenueByRange($dateFrom, $dateTo)`
    - `getAll([...])` (lấy recent orders)
    - `getSummaryWithComparison($dateFrom, $dateTo)` (dùng cho API `/admin/statistics`)
  - Top sản phẩm bán chạy: `app/Models/Product.php`
    - `getTopSelling($limit)`
  - Đếm tổng: `app/Models/User.php`, `app/Models/Product.php`, `app/Models/Category.php`
    - `count()`

- **View/JS (nơi hiển thị và vẽ chart)**
  - File view: `app/Views/admin/dashboard.php`
  - KPI hiển thị trực tiếp từ biến PHP được controller truyền sang.
  - Chart.js:
    - Dữ liệu ban đầu lấy từ biến `$dailyRevenue` (PHP) render ra JS.
    - Khi đổi range/ngày → JS gọi `fetch('/admin/statistics?...')` lấy JSON và cập nhật chart.

### 7.2 Nếu giáo viên hỏi “đổi ngày trên dashboard thì cập nhật kiểu gì?”

- Dashboard có dropdown range + input date.
- Khi thay đổi, JS gọi API `GET /admin/statistics`.
- API trả JSON gồm:
  - `summary` (current/previous/change_pct)
  - `dailyRevenue[]` (date, orders, revenue)
- View cập nhật:
  - KPI: `applySummary(summary)`
  - Chart: `applyDailyRevenue(dailyRevenue)`

### 7.3 Nghiệp vụ đơn hàng: trạng thái và điều kiện hủy/xóa

- **Luồng trạng thái hợp lệ (Admin cập nhật)**
  - `pending` → `confirmed` → `preparing` → `delivering` → `completed`
  - Có thể `cancelled` từ `pending` hoặc `confirmed`
  - Khi đã `completed` hoặc `cancelled` thì khóa không cập nhật nữa

- **Hủy đơn (User)**
  - Endpoint: `POST /orders/cancel`
  - Chỉ hủy khi trạng thái `pending` hoặc `confirmed`

- **Xóa đơn (Admin)**
  - Endpoint: `POST /admin/orders/{id}/delete`
  - Chỉ xóa đơn đã `cancelled` (không xóa đơn hoàn thành)

---

## 8) Chức năng từng file trong cây thư mục (file dùng làm trang gì?)

Mục này giúp trả lời nhanh khi bị hỏi: “File này dùng làm gì? Trang nào gọi nó? Logic nằm ở đâu?”.

### 8.1 Core (khung chạy hệ thống)

- **`app/Core/App.php`**
  - **Vai trò:** khai báo toàn bộ route của hệ thống (frontend + admin).
  - **Liên quan trực tiếp:** map URL như `/admin/dashboard`, `/admin/orders`, `/cart`, `/checkout`, `/my-orders`… tới đúng controller.

- **`app/Core/Controller.php`**
  - **Vai trò:** controller base.
  - **Chức năng:**
    - `render($view, $data)`: chọn layout và include view.
      - `admin/...` → `app/Views/layouts/admin.php`
      - còn lại → `app/Views/layouts/app.php`
    - `requireAuth()`: chặn trang cần đăng nhập.
    - `requireAdmin()`: chặn trang quản trị.

### 8.2 Controllers (xử lý request → gọi model → render view)

#### 8.2.1 Frontend

- **`app/Controllers/HomeController.php`**
  - **Trang/route:**
    - `GET /` → trang chủ (`app/Views/home/index.php`)
    - `GET /products` → danh sách sản phẩm (`app/Views/home/products.php`)
  - **Vai trò:** nhận filter (category/search/sort/page), gọi `Product/Category` model để lấy data, render view.

- **`app/Controllers/ProductController.php`**
  - **Trang/route:** `GET /product/{id}` → chi tiết sản phẩm (`app/Views/product/detail.php`).
  - **Vai trò:** lấy chi tiết sản phẩm, ảnh/màu/size, rating & reviews, sản phẩm liên quan.

- **`app/Controllers/CartController.php`**
  - **Trang/route:** `GET /cart` → giỏ hàng (`app/Views/cart/index.php`).
  - **API/AJAX:** `/cart/add`, `/cart/update`, `/cart/remove`, `/cart/clear`, `/cart/count`.
  - **Vai trò:** quản lý `$_SESSION['cart']`, validate số lượng, trả JSON để UI cập nhật.

- **`app/Controllers/OrderController.php`**
  - **Trang/route:**
    - `GET /checkout` + `POST /checkout` → thanh toán/đặt hàng (`app/Views/order/checkout.php`, `app/Views/order/success.php`).
    - `GET /my-orders` → danh sách đơn của user (`app/Views/order/my-orders.php`).
    - `GET /orders/{id}` → chi tiết đơn (`app/Views/order/detail.php`).
  - **API/AJAX:** `POST /orders/cancel`.
  - **Vai trò:** tạo đơn + chi tiết đơn, kiểm tra quyền sở hữu, chặn hủy sai trạng thái.

#### 8.2.2 Admin

- **`app/Controllers/Admin/DashboardController.php`**
  - **Trang/route:** `GET /admin/dashboard` → `app/Views/admin/dashboard.php`.
  - **API:** `GET /admin/statistics` trả JSON cho Chart.js.
  - **Vai trò:** lấy thống kê doanh thu/đơn hàng, top sản phẩm, recent orders.

- **`app/Controllers/Admin/OrderController.php`**
  - **Trang/route:**
    - `GET /admin/orders` → `app/Views/admin/orders/index.php`
    - `GET /admin/orders/{id}` → `app/Views/admin/orders/show.php`
  - **API:**
    - `POST /admin/orders/{id}/update-status`
    - `POST /admin/orders/{id}/delete`
  - **Vai trò:** quản lý đơn hàng admin, ràng buộc chuyển trạng thái, chỉ cho xóa đơn `cancelled`.

### 8.3 Models (truy vấn DB + nghiệp vụ ở tầng dữ liệu)

- **`app/Models/Product.php`**
  - **Dùng cho trang:** `/`, `/products`, `/product/{id}`, `/cart`, `/checkout`.
  - **Vai trò:** truy vấn sản phẩm (list/detail), nhóm sản phẩm (featured/top/new/sale), sản phẩm liên quan.

- **`app/Models/Category.php`**
  - **Dùng cho trang:** `/`, `/products`, `/product/{id}`.
  - **Vai trò:** truy vấn danh mục phục vụ filter và breadcrumb.

- **`app/Models/Order.php`**
  - **Dùng cho trang:** `/checkout`, `/my-orders`, `/orders/{id}`, `/admin/orders`, `/admin/dashboard`.
  - **Vai trò:**
    - CRUD & query đơn hàng.
    - Nghiệp vụ trạng thái: `normalizeStatus()`, `canTransitionStatus()`, `updateStatus()`.
    - Thống kê dashboard: `getStatistics()`, `getDailyRevenueByRange()`, `getSummaryWithComparison()`.

- **`app/Models/OrderItem.php`**
  - **Dùng cho trang:** `/checkout` (khi tạo đơn).
  - **Vai trò:** lưu các dòng sản phẩm trong đơn (createMultiple), hoặc truy vấn khi hiển thị chi tiết đơn.

- **`app/Models/Address.php`**
  - **Dùng cho trang:** `/checkout`, `/my-orders`, `/admin/orders/{id}`.
  - **Vai trò:** lấy/lưu địa chỉ mặc định để gợi ý khi checkout và hiển thị thông tin giao hàng.

### 8.4 Views (file giao diện “từng trang”)

#### 8.4.1 Layout

- **`app/Views/layouts/app.php`**
  - **Dùng cho:** toàn bộ trang frontend.
  - **Vai trò:** navbar + footer + `<main><?= $content ?></main>`.

- **`app/Views/layouts/admin.php`**
  - **Dùng cho:** toàn bộ trang admin.
  - **Vai trò:** layout quản trị (menu admin + nội dung).

#### 8.4.2 Frontend pages

- **`app/Views/home/index.php`**
  - **Trang:** `/`.
  - **Vai trò:** hiển thị các section sản phẩm + phân trang.

- **`app/Views/home/products.php`**
  - **Trang:** `/products`.
  - **Vai trò:** hiển thị list + filter + pagination.

- **`app/Views/home/product.php`**
  - **Trang:** `/product` (route đang tồn tại).
  - **Vai trò:** trang phụ/legacy (tùy dự án), thường dùng làm trang giới thiệu/hiển thị sản phẩm mẫu.

- **`app/Views/product/detail.php`**
  - **Trang:** `/product/{id}`.
  - **Vai trò:** hiển thị chi tiết sản phẩm, gallery, biến thể, review.

- **`app/Views/cart/index.php`**
  - **Trang:** `/cart`.
  - **Vai trò:** hiển thị giỏ hàng.
  - **JS:** gọi `/cart/update/remove/clear` để cập nhật số lượng.

- **`app/Views/order/checkout.php`**
  - **Trang:** `/checkout` (GET).
  - **Vai trò:** form nhập thông tin giao hàng và chọn phương thức thanh toán.

- **`app/Views/order/success.php`**
  - **Trang:** render sau khi đặt hàng thành công.
  - **Vai trò:** hiển thị mã đơn + tổng tiền + link về `/my-orders`.

- **`app/Views/order/my-orders.php`**
  - **Trang:** `/my-orders`.
  - **Vai trò:** list đơn của user.
  - **JS:** gọi `/orders/cancel` để hủy đơn.

- **`app/Views/order/detail.php`**
  - **Trang:** `/orders/{id}`.
  - **Vai trò:** chi tiết đơn, timeline trạng thái, nút hủy (pending) và reorder.

#### 8.4.3 Admin pages

- **`app/Views/admin/dashboard.php`**
  - **Trang:** `/admin/dashboard`.
  - **Vai trò:** hiển thị KPI + chart doanh thu + top sản phẩm + recent orders.
  - **JS:** gọi `/admin/statistics` để update chart theo range.

- **`app/Views/admin/orders/index.php`**
  - **Trang:** `/admin/orders`.
  - **Vai trò:** list đơn + filter.
  - **JS:** gọi `/admin/orders/{id}/delete` (chỉ đơn cancelled).

- **`app/Views/admin/orders/show.php`**
  - **Trang:** `/admin/orders/{id}`.
  - **Vai trò:** chi tiết đơn + form update status.
  - **JS:** gọi `/admin/orders/{id}/update-status`.

### 8.5 Public (entrypoint + assets)

- **`public/index.php`**
  - **Vai trò:** entrypoint chạy app (load `.env`, autoload, khởi tạo `App`, bắt lỗi 500).

- **`public/js/app.js`**
  - **Vai trò:** JS dùng chung cho frontend (toast/interaction tùy nội dung file).
