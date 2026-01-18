<div class="container">
	<?php require_once view_path('components/heading'); ?>
</div>

<div class="container-fluid p-4">
	<div class="row justify-content-center">
		<div class="col-xl-8 col-xxl-7">

			<form action="/admins" method="POST" novalidate>
				<?= csrf() ?>

				<div class="card border-0 shadow-sm mb-4">
					<div class="card-header bg-white py-3">
						<h5 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Új admin adatai</h5>
					</div>
					<div class="card-body">
						<div class="row g-3">
							<div class="col-lg-6">
								<label for="name" class="form-label fw-semibold">Név <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="name" name="name" required
									data-validate="required|min:3|max:100"
									value="<?= htmlspecialchars(old('name') ?? '') ?>" placeholder="Teljes név">
							</div>
							<div class="col-lg-6">
								<label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
								<input type="email" class="form-control" id="email" name="email" required
									data-validate="required|email|max:150"
									value="<?= htmlspecialchars(old('email') ?? '') ?>" placeholder="admin@example.com">
							</div>
							<div class="col-lg-6">
								<label for="role" class="form-label fw-semibold">Szerepkör</label>
								<?php $currentRole = old('role') ?? 'admin'; ?>
								<select class="form-select" id="role" name="role">
									<option value="admin" <?php if ($currentRole === 'admin') echo 'selected'; ?>>Admin</option>
									<option value="editor" <?php if ($currentRole === 'editor') echo 'selected'; ?>>Szerkesztő</option>
								</select>
								<div class="form-text">Alapértelmezés: admin.</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card border-0 shadow-sm">
					<div class="card-body d-flex justify-content-between align-items-center">
						<a href="/admin" class="btn btn-outline-secondary">
							<i class="bi bi-x-circle me-2"></i>Mégse
						</a>
						<div class="d-flex gap-2">
							<button type="reset" class="btn btn-outline-warning">
								<i class="bi bi-arrow-counterclockwise me-1"></i>Visszaállítás
							</button>
							<button type="submit" class="btn btn-primary px-4">
								<i class="bi bi-save me-2"></i>Létrehozás
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
