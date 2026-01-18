<div class="container py-3">
    <?php require_once view_path('components/heading'); ?>
</div>

<div class="container-fluid pb-5 px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-xxl-7">

            <form action="/admins/<?= $admin->id ?? '' ?>" method="POST" novalidate class="needs-validation shadow">
                <?= csrf() ?>
                <input type="hidden" name="_method" value="PATCH">

                <div class="card border-0 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Admin profil</p>
                            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Adatok
                                szerkesztése</h5>
                        </div>
                        <a href="/admins" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Vissza
                        </a>
                    </div>
                    <div class="card-body bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label for="name" class="form-label fw-semibold">Név <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="<?= htmlspecialchars(old('name', $admin->name ?? '')) ?>"
                                    placeholder="Teljes név">

                                <?= errors('name', session('errors') ?? []) ?>
                            </div>
                            <div class="col-lg-6">
                                <label for="email" class="form-label fw-semibold">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?= htmlspecialchars(old('email', $admin->email ?? '')) ?>"
                                    placeholder="admin@example.com" disabled>
                                <?= errors('email', session('errors') ?? []) ?>
                            </div>
                            <?php if(session('admin')->role === 'admin'): ?>
                            <div class="col-lg-6">
                                <label for="role" class="form-label fw-semibold">Szerepkör</label>
                                <?php $currentRole = old('role', $admin->role ?? 'admin'); ?>
                                <select class="form-select" id="role" name="role">
                                    <option value="admin" <?php if ($currentRole === 'admin') {
                                        echo 'selected';
                                    } ?>>Admin</option>
                                    <option value="editor" <?php if ($currentRole === 'editor') {
                                        echo 'selected';
                                    } ?>>Szerkesztő</option>
                                </select>
                                <div class="form-text">Állítsd be a megfelelő hozzáférési szintet.</div>
                                <?= errors('role', session('errors') ?? []) ?>
                            </div>
                            <?php endif?>
                        </div>
                    </div>
                </div>



                <div class="card border-0">
                    <div
                        class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <a href="/admins" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Mégse
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Visszaállítás
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Mentés
                            </button>
                        </div>
                    </div>
                </div>
            </form>


            <form action="/admin/password-reset" method="POST" novalidate class="shadow mt-5 p-3">
                <?= csrf() ?>
                <input type="hidden" name="_method" value="PATCH">
								<input type="hidden" name="email" value="<?= htmlspecialchars($admin->email ?? '') ?>">
								<input type="hidden" name="id" value="<?= htmlspecialchars($admin->id ?? '') ?>">

                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Jelszó frissítése</p>
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Jelszó
                            szerkesztése</h5>
                    </div>
                </div>
                <div class="card-body bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="current_password" class="form-label fw-semibold">Jelenlegi jelszó</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                data-validate="required|password"
                                autocomplete="current-password" placeholder="••••••••">
                            <div class="form-text">Biztonsági okból szükséges lehet.</div>
                            <?= errors('current_password', session('errors') ?? []) ?>
                        </div>
                        <div class="col-lg-6">
                            <label for="password" class="form-label fw-semibold">Új jelszó <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password"
                                data-validate="required|password" autocomplete="new-password" placeholder="Minimum 8 karakter" required>
                            <?= errors('password', session('errors') ?? []) ?>
                        </div>
                        <div class="col-lg-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Új jelszó megerősítése <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                data-validate="required|password" autocomplete="new-password" placeholder="Írd be újra" required>
                            <?= errors('password_confirmation', session('errors') ?? []) ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 px-3 px-md-4">
                    <a href="/admins" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Mégse
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-lock me-2"></i>Jelszó frissítése
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
