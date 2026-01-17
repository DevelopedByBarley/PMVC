<?php
$permissions = [];
if (!empty($admin->permissions)) {
	$decoded = is_string($admin->permissions) ? json_decode($admin->permissions, true) : $admin->permissions;
	$permissions = is_array($decoded) ? $decoded : [];
}
?>


<div class="container py-3">
	<?php require_once view_path('components/heading'); ?>
</div>

<div class="container-fluid pb-5 px-3 px-md-4">
	<div class="row justify-content-center">
		<div class="col-xl-9 col-xxl-8">
			<div class="card border-0 shadow-sm mb-4">
				<div class="card-header bg-white d-flex justify-content-between align-items-center">
					<div>
						<p class="text-uppercase text-muted small mb-1">Admin profil</p>
						<h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
							<i class="bi bi-person-badge text-primary"></i>
							<span><?= htmlspecialchars($admin->name ?? 'Admin') ?></span>
						</h4>
						<p class="text-muted small mb-0 d-flex align-items-center gap-1">
							<i class="bi bi-envelope"></i>
							<?= htmlspecialchars($admin->email ?? '') ?></p>
					</div>
					<div class="d-flex gap-2">
						<a href="/admins/edit/<?= $admin->id ?>" class="btn btn-outline-secondary btn-sm">
							<i class="bi bi-pencil"></i> Szerkesztés
						</a>
						<a href="/admins" class="btn btn-outline-secondary btn-sm">
							<i class="bi bi-arrow-left"></i> Vissza
						</a>
					</div>
				</div>
				<div class="card-body bg-light-subtle">
					<div class="row g-4">
						<div class="col-lg-7">
							<div class="mb-3">
								<h6 class="fw-bold">Alap adatok</h6>
								<ul class="list-unstyled mb-0 text-muted">
									<li><strong>Név:</strong> <?= htmlspecialchars($admin->name ?? '—') ?></li>
									<li><strong>Email:</strong> <?= htmlspecialchars($admin->email ?? '—') ?></li>
									<li><strong>Szerepkör:</strong>
										<span class="badge bg-light text-dark border"><?= htmlspecialchars($admin->role ?? '—') ?></span>
									</li>
									<li><strong>E-mail igazolva:</strong>
										<?php if (!empty($admin->email_verified_at)) : ?>
										<span class="text-success fw-semibold">Igen</span>
										<span class="text-muted small">(<?= htmlspecialchars($admin->email_verified_at) ?>)</span>
										<?php else : ?>
										<span class="text-danger fw-semibold">Nem</span>
										<?php endif; ?>
									</li>
								</ul>
							</div>

							<?php if (!empty($permissions)) : ?>
							<div class="mb-3">
								<h6 class="fw-bold">Jogosultságok</h6>
								<div class="d-flex flex-wrap gap-2">
									<?php foreach ($permissions as $permission): ?>
									<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
										<?= htmlspecialchars($permission) ?>
									</span>
									<?php endforeach; ?>
								</div>
							</div>
							<?php endif; ?>
						</div>

						<div class="col-lg-5">
							<div class="mb-3">
								<h6 class="fw-bold">Meta</h6>
								<ul class="list-unstyled mb-0 text-muted">
									<li><strong>ID:</strong> <?= htmlspecialchars($admin->id ?? '—') ?></li>
									<li><strong>Létrehozva:</strong> <?= htmlspecialchars($admin->created_at ?? '—') ?></li>
									<li><strong>Módosítva:</strong> <?= htmlspecialchars($admin->updated_at ?? '—') ?></li>
									<li><strong>Utolsó belépés:</strong> <?= htmlspecialchars($admin->last_login_at ?? '—') ?></li>
								</ul>
							</div>

							<div class="mb-0">
								<h6 class="fw-bold">Állapot</h6>
								<div class="d-flex flex-wrap gap-2">
									<?php if (!empty($admin->email_verified_at)) : ?>
									<span class="badge bg-success text-white">Email igazolva</span>
									<?php else : ?>
									<span class="badge bg-secondary text-white">Várakozik igazolásra</span>
									<?php endif; ?>
									<?php if (($admin->locked_at ?? null) !== null) : ?>
									<span class="badge bg-danger text-white">Zárolt fiók</span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
