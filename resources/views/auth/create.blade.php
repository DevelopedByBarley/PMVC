


<section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5 h-100 mt-3">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg "
                    style="border-radius: 1.5rem; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                    <div class="card-body p-5">

                        <!-- Header Section -->
                        <div class="text-center mb-5">
                            <h2 class="display-6 fw-semibold mb-3 text-dark" style="letter-spacing: -0.02em;">
                                Csatlakozz hozzánk! ✨
                            </h2>
                            <p class="text-muted mb-0 fs-6" style="font-weight: 400; line-height: 1.5;">
                                Készítsd el fiókod néhány egyszerű lépésben
                            </p>
                        </div>

                        <form action="/register" method="POST" enctype="multipart/form-data">
                            <?= csrf() ?>

                            <!-- Email Input -->
                            <div class="form-floating mb-4">
                                <input type="email" id="email" name="email" value="<?= old('email') ?>"
                                    class="form-control form-control-lg  shadow-sm text-sm"
                                    data-validate="required|email|min:5" placeholder="email@example.com"
                                    style="border-radius: 1rem; background: #f8f9fa;" required />
                                <label for="email" class="text-muted text-xs">
                                    <i class="fas fa-envelope me-2"></i>Email cím
                                </label>
                                <?= errors('email', $errors) ?>
                            </div>

                            <!-- Password Input -->
                            <div class="form-floating mb-4">
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg  shadow-sm text-sm"
                                    data-validate="required|password" placeholder="Jelszó"
                                    style="border-radius: 1rem; background: #f8f9fa;" required />
                                <label for="password" class="text-muted text-xs">
                                    <i class="fas fa-lock me-2"></i>Jelszó
                                </label>
                                <?= errors('password', $errors) ?>
                            </div>

                            <!-- File Upload Section -->
                            <div class="mb-4">
                                <label for="formFile" class="form-label fw-semibold text-dark mb-3">
                                    <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Profilkép feltöltése
                                    (opcionális)
                                </label>
                                <div class="border-2 border-dashed rounded-3 p-4 text-center "
                                    style="border-color: #dee2e6 !important; background: #f8f9fa;">
                                    <i class="fas fa-images fa-2x text-muted mb-2 d-block my-2"></i>
                                    <input class="form-control form-control-sm" type="file" name="file" multiple
                                        id="formFile" style="background: transparent;">
                                    <small class="text-muted">PNG, JPG, GIF formátumok támogatottak</small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button class="btn btn-primary text-sm shadow-sm fw-semibold rounded-pill w-200 mx-auto"
                                    type="submit">
                                    <i class="fas fa-user-plus me-2"></i>Regisztráció
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Már van fiókod?
                                    <a href="/login" class="text-primary text-decoration-none fw-semibold">
                                        <i class="fas fa-sign-in-alt me-1"></i>Bejelentkezés
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
