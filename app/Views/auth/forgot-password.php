<?php $title = 'Quên mật khẩu - Sắc Việt'; ?>

<style>
.auth-wrapper { max-width: 500px; }
.auth-card { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.auth-left { padding: 40px 36px; }
.auth-title { color: #e74c3c; font-weight: 700; }
.btn-outline { border-color: #ff6b6b; color: #ff6b6b; }
.btn-outline:hover { background: #ff6b6b; color: #fff; }
.muted { color: #6b7280; }
</style>

<div class="container py-5">
  <div class="mx-auto auth-wrapper">
    <div class="card auth-card">
      <div class="auth-left">
        <?php if (!isset($step) || $step == 1): ?>
          <!-- STEP 1: Nhập Email -->
          <div class="mb-4 text-center">
            <h3 class="auth-title mb-2">Quên Mật Khẩu</h3>
            <div class="muted">Nhập email để đặt lại mật khẩu</div>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="POST" action="/forgot-password">
            <input type="hidden" name="step" value="1">
            
            <div class="mb-4">
              <label for="email" class="form-label">Email *</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="Nhập email của bạn" required>
              </div>
            </div>

            <div class="d-grid gap-2 mb-3">
              <button type="submit" class="btn btn-danger btn-lg">Tiếp Tục</button>
            </div>

            <div class="text-center">
              <a href="/login" class="text-muted text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Quay lại đăng nhập
              </a>
            </div>
          </form>

        <?php elseif ($step == 2): ?>
          <!-- STEP 2: Nhập Mật Khẩu Mới -->
          <div class="mb-4 text-center">
            <h3 class="auth-title mb-2">Đặt Lại Mật Khẩu</h3>
            <div class="muted">Nhập mật khẩu mới cho tài khoản của bạn</div>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="POST" action="/forgot-password">
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            
            <div class="mb-3">
              <label for="password" class="form-label">Mật Khẩu Mới *</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Nhập mật khẩu mới" required minlength="6">
              </div>
              <small class="text-muted d-block mt-1">Tối thiểu 6 ký tự</small>
            </div>

            <div class="mb-4">
              <label for="password_confirm" class="form-label">Xác Nhận Mật Khẩu *</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password_confirm" 
                       name="password_confirm" placeholder="Nhập lại mật khẩu" 
                       required minlength="6">
              </div>
            </div>

            <div class="d-grid gap-2 mb-3">
              <button type="submit" class="btn btn-danger btn-lg">Cập Nhật Mật Khẩu</button>
            </div>

            <div class="text-center">
              <a href="/forgot-password" class="text-muted text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
              </a>
            </div>
          </form>

        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  if (!form) return;
  
  const password = document.getElementById('password');
  const passwordConfirm = document.getElementById('password_confirm');

  if (password && passwordConfirm) {
    form.addEventListener('submit', function(e) {
      if (password.value !== passwordConfirm.value) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
        passwordConfirm.focus();
      }
    });
  }
});
</script>
