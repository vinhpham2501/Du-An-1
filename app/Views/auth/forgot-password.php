<?php $title = 'Quên mật khẩu - Sắc Việt'; ?>

<style>
.auth-wrapper { max-width: 820px; }
.auth-card { border: none; border-radius: 14px; overflow: hidden; }
.auth-left { padding: 40px 36px; }
.auth-right { background: url('https://i.pinimg.com/1200x/71/9e/42/719e42d55f7db5f20f5db93c040e6a4e.jpg') center/cover no-repeat; min-height: 480px; }
.auth-title { color: #e74c3c; font-weight: 700; }
.link-muted { color: #6b7280; text-decoration: none; }
.link-muted:hover { color: #ef4444; text-decoration: underline; }
</style>

<div class="container py-5">
  <div class="mx-auto auth-wrapper">
    <div class="card shadow auth-card">
      <div class="row g-0">
        <div class="col-lg-6 auth-left">
          <div class="mb-3 text-center">
            <h3 class="auth-title mb-1">Quên mật khẩu</h3>
            <div class="text-muted">Nhập email để nhận liên kết đặt lại mật khẩu</div>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
          <?php endif; ?>

          <form method="POST" action="/forgot-password">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
              </div>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-danger">Gửi liên kết đặt lại</button>
              <a href="/login" class="btn btn-light">Quay về đăng nhập</a>
            </div>
          </form>
        </div>
        <div class="col-lg-6 d-none d-lg-block auth-right"></div>
      </div>
    </div>
  </div>
</div>
