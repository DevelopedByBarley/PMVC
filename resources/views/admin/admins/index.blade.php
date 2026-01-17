<div class="container">
	<div class="d-flex flex-column h-lg-full">
		<div class="h-screen flex-grow-1 overflow-y-lg-auto">
			<?php require_once view_path('components/heading'); ?>

			<main class="pt-3 pb-5 bg-surface-secondary">
				<div class="container-fluid px-3 px-lg-5">
					<div class="card border-0 shadow-sm mb-4">
						<div
							class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between bg-white">
							<div>
								<h5 class="mb-1">Adminok</h5>
								<p class="text-muted small mb-0">Rendszerhez hozzáférő adminisztrátorok listája.</p>
							</div>
							<div class="d-flex flex-column flex-md-row gap-2">
								<form class="d-flex" method="GET" action="">
									<input type="text" name="search" class="form-control form-control-sm me-2"
										placeholder="Keresés név, email vagy szerepkör"
										value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
									<button class="btn btn-outline-secondary btn-sm d-flex" type="submit">
										<i class="bi bi-search me-1"></i>Keresés
									</button>
								</form>
								<a href="/admins/create" class="btn btn-primary btn-sm">
									<i class="bi bi-plus-lg me-1"></i>Új admin
								</a>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table align-middle mb-0">
								<thead class="table-light">
									<tr>
										<th>ID</th>
										<th>Név</th>
										<th>Email</th>
										<th>Szerepkör</th>
										<th>Igazolva</th>
										<th>Létrehozva</th>
										<th class="text-end">Műveletek</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($admins->data)) : ?>
									<tr>
										<td colspan="7" class="text-center py-4 text-muted">Nincs megjeleníthető admin.</td>
									</tr>
									<?php else : ?>
									<?php foreach ($admins->data as $admin) : ?>
									<tr>
										<td>#<?= $admin->id ?></td>
										<td class="fw-semibold"><?= htmlspecialchars($admin->name ?? '—') ?></td>
										<td><?= htmlspecialchars($admin->email ?? '—') ?></td>
										<td>
											<span class="badge bg-light text-dark border"><?= htmlspecialchars($admin->role ?? '—') ?></span>
										</td>
										<td>
											<?php if (!empty($admin->email_verified_at)) : ?>
											<span class="badge bg-success text-white">Igen</span>
											<?php else : ?>
											<span class="badge bg-secondary text-white">Nem</span>
											<?php endif; ?>
										</td>
										<td><?= !empty($admin->created_at) ? date('Y.m.d H:i', strtotime($admin->created_at)) : '—' ?>
										</td>
										<td class="text-end">
											<div class="d-inline-flex gap-1">
												<a href="/admins/<?= $admin->id ?>" class="btn btn-outline-primary btn-sm">
													<i class="bi bi-eye"></i>
												</a>
												<a href="/admins/edit/<?= $admin->id ?>" class="btn btn-outline-secondary btn-sm">
													<i class="bi bi-pencil"></i>
												</a>
												<form action="/admins/<?= $admin->id ?>" method="POST" class="d-inline">
													<?= csrf() ?>
													<input type="hidden" name="_method" value="DELETE">
													<button type="submit" class="btn btn-outline-danger btn-sm"
														onclick="return confirm('Biztosan törlöd?');">
														<i class="bi bi-trash"></i>
													</button>
												</form>
											</div>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>

							<?php if (!empty($admins->data)) : ?>
							<div
								class="card-footer border-0 py-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
								<?= paginate($admins) ?>
								<span class="text-muted text-sm mt-2 mt-md-0">Showing <?= $admins->current_page ?> /
									<?= $admins->total_pages ?> items out of <?= $admins->total_records ?> results found</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>
</div>
