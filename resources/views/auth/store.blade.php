<section class="min-h-screen d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
  <div class="container py-5 h-100 mt-3">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-lg border-0" style="border-radius: 1.5rem; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
          <div class="card-body p-5">

            <!-- Header Section -->
            <div class="text-center mb-5">
              <h2 class="display-6 fw-semibold mb-3 text-dark" style="letter-spacing: -0.02em;">
                Bejelentkezés! ✨
              </h2>
              <p class="text-muted mb-0 fs-6" style="font-weight: 400; line-height: 1.5;">
                Kérjük, add meg a bejelentkezési adataidat
              </p>
            </div>

            <form action="/login" method="POST" enctype="multipart/form-data">
              @csrf

              <!-- Email Input -->
              <div class="form-floating mb-4">
                <input type="email"
                  id="email"
                  name="email"
                  value="<?= old('email') ?>"
                  class="form-control form-control-lg border-0 shadow-sm text-sm"
                  data-validate="required|email|min:5"
                  placeholder="email@example.com"
                  style="border-radius: 1rem; background: #f8f9fa;"
                  required />
                <label for="email" class="text-muted text-xs">
                  <i class="fas fa-envelope me-2"></i>Email cím
                </label>
                <?= errors('email', $errors) ?>
              </div>

              <!-- Password Input -->
              <div class="form-floating mb-4">
                <input type="password"
                  name="password"
                  id="password"
                  class="form-control form-control-lg border-0 shadow-sm text-sm"
                  data-validate="required|password"
                  placeholder="Jelszó"
                  style="border-radius: 1rem; background: #f8f9fa;"
                  required />
                <label for="password" class="text-muted text-xs">
                  <i class="fas fa-lock me-2"></i>Jelszó
                </label>
                <?= errors('password', $errors) ?>
              </div>

              <!-- Submit Button -->
              <div class="d-grid mb-4">
                <button class="btn btn-primary text-sm shadow-sm fw-semibold rounded-pill w-200 mx-auto"
                  type="submit">
                  <i class="fas fa-user-plus me-2"></i>Bejelentkezés
                </button>
              </div>

              <!-- Login Link -->
              <div class="text-center">
                <p class="text-muted mb-0">
                  Nincs még fiókod?
                  <a href="/register" class="text-primary text-decoration-none fw-semibold">
                    <i class="fas fa-user-plus me-1"></i>Regisztráció
                  </a>
                </p>
              </div>
            </form>

          </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-4">
          <p class="text-white-50 small mb-0">
            <i class="fas fa-shield-alt me-1"></i>
            Adataid biztonságban vannak nálunk
          </p>
        </div>

      </div>
    </div>
  </div>
</section>