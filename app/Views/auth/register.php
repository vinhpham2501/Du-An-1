<?php $title = 'Đăng ký - Sắc Việt'; ?>

<style>
.auth-wrapper { max-width: 980px; }
.auth-card { border: none; border-radius: 14px; overflow: hidden; }
.auth-left { padding: 40px 36px; }
.auth-right { background: url('https://i.pinimg.com/736x/d1/05/54/d10554ae2dc3ec0099c952efce1b0e9c.jpg') center/cover no-repeat; min-height: 620px; }
.auth-title { color: #e74c3c; font-weight: 700; }
.btn-outline { border-color: #ff6b6b; color: #ff6b6b; }
.btn-outline:hover { background: #ff6b6b; color: #fff; }
.muted { color: #6b7280; }
</style>

<div class="container py-5">
  <div class="mx-auto auth-wrapper">
    <div class="card shadow auth-card">
      <div class="row g-0">
        <div class="col-lg-6 auth-left">
          <div class="mb-3 text-center">
            <h3 class="auth-title mb-1">Đăng ký</h3>
            <div class="muted">Tạo tài khoản để bắt đầu mua sắm</div>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="/register">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Họ và tên *</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mật khẩu *</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-text">Tối thiểu 6 ký tự</div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu *</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Số điện thoại</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Giới tính</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="gender_male" value="Nam" <?= (($_POST['gender'] ?? '') === 'Nam') ? 'checked' : '' ?>>
                  <label class="form-check-label" for="gender_male">Nam</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Nữ" <?= (($_POST['gender'] ?? '') === 'Nữ') ? 'checked' : '' ?> >
                  <label class="form-check-label" for="gender_female">Nữ</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="gender_other" value="Khác" <?= (($_POST['gender'] ?? '') === 'Khác') ? 'checked' : '' ?> >
                  <label class="form-check-label" for="gender_other">Khác</label>
                </div>
              </div>
            </div>

            <div class="row g-2">
              <div class="col-6 d-grid">
                <button type="submit" class="btn btn-danger">Đăng ký</button>
              </div>
              <div class="col-6 d-grid">
                <a href="/login" class="btn btn-outline">Đăng nhập</a>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-6 d-none d-lg-block auth-right"></div>
      </div>
    </div>
  </div>
</div>
