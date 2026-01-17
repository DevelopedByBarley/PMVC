<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Jelszó beállítása</h3>
                        <p class="text-muted">Az admin fiók aktiválásához állítsa be jelszavát</p>
                    </div>

                    <form action="/admin/invite/accept" method="POST" novalidate>
                        <?= csrf() ?>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Jelszó <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required 
                                placeholder="Legalább 8 karakter"
                                data-validate="required|password">
                            <div class="form-text">Legalább 8 karakter hosszúnak kell lennie.</div>
                            <?= errors('password', session('errors')) ?>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Jelszó megerősítése <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required 
                                placeholder="Írd be újra a jelszót"
                                data-validate="required|password">
                            <?= errors('password_confirmation', session('errors')) ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Fiók aktiválása
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            Már van fiókja? <a href="/admin/login" class="fw-semibold text-decoration-none">Jelentkezzen be</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-shield-lock"></i> Az Ön adatai biztonságos szerveren tárolódnak.
                </p>
            </div>
        </div>
    </div>
</div>
