<?php $title = 'Đăng nhập - Sắc Việt'; ?>

<style>
.auth-wrapper { max-width: 980px; }
.auth-card { border: none; border-radius: 14px; overflow: hidden; }
.auth-left { padding: 40px 36px; }
.auth-right { background: url('https://i.pinimg.com/736x/a6/20/c9/a620c98d65ee8582f8fabfeb602eab51.jpg') center/cover no-repeat; min-height: 520px; }
.auth-title { color: #e74c3c; font-weight: 700; }
.socials a { width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb; color: #6b7280; transition: .2s; }
.socials a:hover { background: #f3f4f6; color: #111827; }
.btn-outline { border-color: #ff6b6b; color: #ff6b6b; }
.btn-outline:hover { background: #ff6b6b; color: #fff; }
.muted { color: #6b7280; }
.link-muted { color: #6b7280; text-decoration: none; }
.link-muted:hover { color: #ef4444; text-decoration: underline; }
</style>

<div class="container py-5">
  <div class="mx-auto auth-wrapper">
    <div class="card shadow auth-card">
      <div class="row g-0">
        <div class="col-lg-6 auth-left">
          <div class="mb-3 text-center">
            <h3 class="auth-title mb-1">Đăng nhập</h3>
          </div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="/login">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Mật khẩu</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">Nhớ mật khẩu</label>
              </div>
              <a class="link-muted" href="/forgot-password">Quên mật khẩu?</a>
            </div>
            <div>
              <div class="muted">hoặc đăng nhập với:</div>
              <div class="d-flex gap-2 justify-content-center mt-2 socials">
                <a href="#" aria-label="Google"><i class="fab fa-google"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Github"><i class="fab fa-github"></i></a>
              </div>
            </div>
            <div class="row g-2">
              <div class="col-6 d-grid">
                <button type="submit" class="btn btn-danger">Đăng nhập</button>
              </div>
              <div class="col-6 d-grid">
                <a href="/register" class="btn btn-outline btn-outline">Đăng ký</a>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-6 d-none d-lg-block auth-right"></div>
      </div>
    </div>
  </div>
</div>
