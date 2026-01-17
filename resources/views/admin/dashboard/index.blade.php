<?php
$statCards = [
  [
    'label' => 'Adminok',
    'value' => $stats['admins'] ?? 0,
    'icon' => 'bi-people',
    'color' => 'primary',
  ],
  [
    'label' => 'Blogbejegyzések',
    'value' => $stats['blogs'] ?? 0,
    'icon' => 'bi-journal-text',
    'color' => 'success',
  ],
  [
    'label' => 'Programok',
    'value' => $stats['programs'] ?? 0,
    'icon' => 'bi-calendar-event',
    'color' => 'info',
  ],
  [
    'label' => 'Galéria képek',
    'value' => $stats['gallery_images'] ?? 0,
    'icon' => 'bi-image',
    'color' => 'warning',
  ],
  [
    'label' => 'Fájlok',
    'value' => $stats['files'] ?? 0,
    'icon' => 'bi-folder2-open',
    'color' => 'danger',
  ],
];
?>

<div class="container p-0">
  <div class="d-flex flex-column h-lg-full">
    <div class="h-screen flex-grow-1 overflow-y-lg-auto">
      <?php require_once view_path('components/heading'); ?>

      <main class="pt-4 pb-6 bg-surface-secondary">
        <div class="container-fluid px-3 px-lg-5">
          <div class="row g-3 g-lg-4 mb-5">
            <?php foreach ($statCards as $card): ?>
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="card shadow-sm border-0 h-100 hover-lift">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                  <div>
                    <span class="text-muted text-uppercase fw-semibold small"><?= $card['label'] ?></span>
                    <div class="fs-3 fw-bold mb-0"><?= (int) $card['value'] ?></div>
                  </div>
                  <div class="icon icon-shape bg-<?= $card['color'] ?> text-white rounded-circle p-3">
                    <i class="bi <?= $card['icon'] ?>"></i>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-12 col-xl-8">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                  <div>
                    <h5 class="mb-1 fw-bold">Legutóbbi blogbejegyzések</h5>
                    <p class="text-muted small mb-0">Friss cikkek publikálási idővel.</p>
                  </div>
                  <a href="/admin/blog" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-right-short"></i>Összes blog
                  </a>
                </div>
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th class="py-3">Cím</th>
                        <th class="py-3">Publikálva</th>
                        <th class="py-3">Létrehozva</th>
                        <th class="text-end py-3">Művelet</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($recentBlogs)): ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted py-5">Nincs még blogbejegyzés.</td>
                      </tr>
                      <?php else: ?>
                      <?php foreach ($recentBlogs as $blog): ?>
                      <tr>
                        <td class="fw-semibold text-truncate py-3" style="max-width: 280px;">
                          <?= htmlspecialchars($blog->title ?? '—') ?>
                        </td>
                        <td class="py-3"><?= !empty($blog->published_at) ? date('Y.m.d H:i', strtotime($blog->published_at)) : '—' ?></td>
                        <td class="py-3"><?= !empty($blog->created_at) ? date('Y.m.d H:i', strtotime($blog->created_at)) : '—' ?></td>
                        <td class="text-end py-3">
                          <a href="/admin/blog/<?= $blog->id ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="/admin/blog/edit/<?= $blog->id ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                          </a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-12 col-xl-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                  <h5 class="mb-0 fw-bold">Legutóbbi adminok</h5>
                  <a href="/admins" class="btn btn-outline-secondary btn-sm">Lista</a>
                </div>
                <div class="list-group list-group-flush">
                  <?php if (empty($recentAdmins)): ?>
                  <div class="list-group-item text-muted text-center py-5">Nincs admin felhasználó.</div>
                  <?php else: ?>
                  <?php foreach ($recentAdmins as $admin): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                    <div class="me-2">
                      <div class="fw-semibold"><?= htmlspecialchars($admin->name ?? '—') ?></div>
                      <div class="text-muted small"><?= htmlspecialchars($admin->email ?? '') ?></div>
                    </div>
                    <div class="text-end">
                      <span class="badge bg-light text-dark border"><?= htmlspecialchars($admin->role ?? '—') ?></span>
                      <div class="text-muted small">
                        <?= !empty($admin->created_at) ? date('Y.m.d', strtotime($admin->created_at)) : '' ?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-4">
            <div class="col-12 col-xl-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                  <h6 class="mb-0 fw-bold">Legutóbbi programok</h6>
                  <a href="/admin/programs" class="btn btn-outline-primary btn-sm">Összes</a>
                </div>
                <div class="list-group list-group-flush">
                  <?php if (empty($recentPrograms)): ?>
                  <div class="list-group-item text-muted text-center py-5">Nincs program.</div>
                  <?php else: ?>
                  <?php foreach ($recentPrograms as $program): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                    <div class="me-2">
                      <div class="fw-semibold text-truncate" style="max-width: 220px;">
                        <?= htmlspecialchars($program->title ?? '—') ?>
                      </div>
                      <div class="text-muted small">
                        <?= !empty($program->date) ? date('Y.m.d H:i', strtotime($program->date)) : '—' ?>
                      </div>
                    </div>
                    <span class="badge <?= !empty($program->published_at) ? 'bg-success' : 'bg-secondary' ?> text-white">
                      <?= !empty($program->published_at) ? 'Publikált' : 'Vázlat' ?>
                    </span>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-12 col-xl-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                  <h6 class="mb-0 fw-bold">Legutóbbi galéria képek</h6>
                  <a href="/admin/gallery" class="btn btn-outline-primary btn-sm">Galéria</a>
                </div>
                <div class="list-group list-group-flush">
                  <?php if (empty($recentGalleryImages)): ?>
                  <div class="list-group-item text-muted text-center py-5">Még nincs kép feltöltve.</div>
                  <?php else: ?>
                  <?php foreach ($recentGalleryImages as $image): ?>
                  <div class="list-group-item d-flex align-items-center gap-3 py-3">
                    <?php if (!empty($image->fileName)): ?>
                    <img src="<?= public_file('images/gallery/' . $image->fileName) ?>" alt="<?= htmlspecialchars($image->title ?? '') ?>"
                      class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                    <?php else: ?>
                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                      style="width: 56px; height: 56px;">
                      <i class="bi bi-image text-muted"></i>
                    </div>
                    <?php endif; ?>
                    <div>
                      <div class="fw-semibold text-truncate" style="max-width: 180px;">
                        <?= htmlspecialchars($image->title ?? 'Kép') ?>
                      </div>
                      <div class="text-muted small">
                        <?= !empty($image->created_at) ? date('Y.m.d', strtotime($image->created_at)) : '' ?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-12 col-xl-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                  <h6 class="mb-0 fw-bold">Legutóbbi fájlok</h6>
                  <a href="/admin/files" class="btn btn-outline-primary btn-sm">Összes</a>
                </div>
                <div class="list-group list-group-flush">
                  <?php if (empty($recentFiles)): ?>
                  <div class="list-group-item text-muted text-center py-5">Nincs feltöltött fájl.</div>
                  <?php else: ?>
                  <?php foreach ($recentFiles as $file): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                    <div class="me-2">
                      <div class="fw-semibold text-truncate" style="max-width: 200px;">
                        <?= htmlspecialchars($file->name ?? $file->fileName ?? 'Fájl') ?>
                      </div>
                      <div class="text-muted small">
                        <?= !empty($file->created_at) ? date('Y.m.d', strtotime($file->created_at)) : '' ?>
                      </div>
                    </div>
                    <a href="/admin/files/edit/<?= $file->id ?>" class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-pencil"></i>
                    </a>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</div>
