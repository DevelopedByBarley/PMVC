
<div class="container">
  <div class="d-flex flex-column h-lg-full">
    <div class="h-screen flex-grow-1 overflow-y-lg-auto">
      <?php require_once view_path('components/heading') ?>

      <main class="pt-3 pb-5 bg-surface-secondary">
        <div class="container-fluid px-3 px-lg-5">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between bg-white">
              <div>
                <h5 class="mb-1">Aktivitásnapló</h5>
                <p class="text-muted small mb-0">Események listája státusz, kategória és érintett elem szerint.</p>
              </div>
              <form action="/admin/activities/delete-all" method="POST" class="d-flex gap-2">
                <?= csrf() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-trash3 me-1"></i>Összes törlése
                </button>
              </form>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Admin Id</th>
                    <th>Admin neve</th>
                    <th>Akció</th>
                    <th>Státusz</th>
                    <th>Kategória</th>
                    <th>Leírás</th>
                    <th>Cél</th>
                    <th>Létrehozva</th>
                    <th class="text-end">Műveletek</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($activities->data)) : ?>
                    <tr>
                      <td colspan="9" class="text-center py-4 text-muted">Nincs megjeleníthető aktivitás.</td>
                    </tr>
                  <?php else : ?>
                    <?php foreach ($activities->data as $activity) : ?>
                      <tr>
                        <td>#<?= $activity->id ?></td>
                        <td><?= $activity->admin_id ?? '—' ?></td>
                        <td><?= $activity->adminName ?? '—' ?></td>
                        <td><?= $activity->action ?? '—' ?></td>
                        <td>
                          <?php $status = strtolower($activity->status ?? ''); ?>
                          <?php
                          if ($status === 'success') {
                            $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                          } elseif ($status === 'failed' || $status === 'error') {
                            $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                          } else {
                            $badgeClass = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                          }
                          ?>
                          <span class="badge <?= $badgeClass ?> border"><?= $activity->status ?? '—' ?></span>
                        </td>
                        <td><?= $activity->category ?? '—' ?></td>
                        <td class="text-truncate" style="max-width: 200px;"><?= $activity->description ?? '—' ?></td>
                        <td>
                          <?php if (!empty($activity->target_type) || !empty($activity->target_id)) : ?>
                            <span class="badge bg-light text-dark border">
                              <?= $activity->target_type ?? '—' ?>
                              <?= $activity->target_id ? "#{$activity->target_id}" : '' ?>
                            </span>
                          <?php else : ?>
                            <span class="text-muted">—</span>
                          <?php endif; ?>
                        </td>
                        <td><?= date_format(date_create($activity->created_at), 'Y-m-d H:i') ?? '—' ?></td>
                        <td class="text-end">
                          <form action="/admin/activities/<?= $activity->id ?>" method="POST" class="d-inline">
                            <?= csrf() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                              <i class="bi bi-trash"></i> Törlés
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
              <?php if (!empty($activities->data)) : ?>
                <div class="card-footer border-0 py-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
                  <?= paginate($activities) ?>
                  <span class="text-muted text-sm mt-2 mt-md-0">Showing <?= $activities->current_page ?> / <?= $activities->total_pages ?> items out of <?= $activities->total_records ?> results found</span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</div>
