
<div class="container p-0">

  <div class="d-flex flex-column  h-lg-full "> <!-- Main content -->
    <div class="h-screen flex-grow-1 overflow-y-lg-auto">
      <!-- Header -->
      <?php require_once view_path('components/heading') ?>
      <main class="pt-3 pb-6 bg-surface-secondary">
        <div class="container-fluid">
          <!-- Card stats -->
          <div class="row g-6 mb-6">
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col p-3">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">Budget</span>
                      <span class="h3 font-bold mb-0">$750.90</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-tertiary text-white text-lg rounded-circle">
                        <i class="bi bi-credit-card"></i>
                      </div>
                    </div>
                  </div>
                  <div class="mt-2 mb-0 text-sm">
                    <span class="badge badge-pill bg-soft-success text-success me-2">
                      <i class="bi bi-arrow-up me-1"></i>13%
                    </span>
                    <span class="text-nowrap text-xs text-muted">Since last month</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col p-3">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">New projects</span>
                      <span class="h3 font-bold mb-0">215</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white text-lg rounded-circle py-3 px-4">
                        <i class="bi bi-people"></i>
                      </div>
                    </div>
                  </div>
                  <div class="mt-2 mb-0 text-sm">
                    <span class="badge badge-pill bg-soft-success text-success me-2">
                      <i class="bi bi-arrow-up me-1"></i>30%
                    </span>
                    <span class="text-nowrap text-xs text-muted">Since last month</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col p-3">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total hours</span>
                      <span class="h3 font-bold mb-0">1.400</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white text-lg rounded-circle py-3 px-4">
                        <i class="bi bi-clock-history"></i>
                      </div>
                    </div>
                  </div>
                  <div class="mt-2 mb-0 text-sm">
                    <span class="badge badge-pill bg-soft-danger text-danger me-2">
                      <i class="bi bi-arrow-down me-1"></i>-5%
                    </span>
                    <span class="text-nowrap text-xs text-muted">Since last month</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col p-3">
                      <span class="h6 font-semibold text-muted text-sm d-block mb-2">Work load</span>
                      <span class="h3 font-bold mb-0">95%</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white text-lg rounded-circle py-3 px-4">
                        <i class="bi bi-minecart-loaded"></i>
                      </div>
                    </div>
                  </div>
                  <div class="mt-2 mb-0 text-sm">
                    <span class="badge badge-pill bg-soft-success text-success me-2">
                      <i class="bi bi-arrow-up me-1"></i>10%
                    </span>
                    <span class="text-nowrap text-xs text-muted">Since last month</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow border-0 mb-7">
            <div class="card-header">
              <h5 class="mb-0">Felhasználók</h5>

            </div>
            <div class="table-responsive">
              <table class="table table-hover table-nowrap">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Created At</th>
        
                    <th scope="col">Meeting</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($paginated->data as $admin): ?>
                    <tr>
                      <td>
                        <img alt="..." src="<?= public_file('images/icons/coach.png') ?>" class="avatar w-45 avatar w-45-sm rounded-circle me-2">
                        <a class="text-decoration-none pr-font  font-semibold" href="#">
                          <?= $user->name ?>
                        </a>
                      </td>
                      <td>
                        <?= $user->email ?>
                      </td>
                      <td>
                        <img alt="..." src="https://preview.webpixels.io/web/img/other/logos/logo-1.png" class="avatar w-45 avatar w-45-xs rounded-circle me-2">
                        <a class="text-heading font-semibold" href="#">
                          <?= $user->created_at ?> </a>
                      </td>
              
                      <td>
                        <span class="badge badge-lg bg-success">
                          <i class=""></i>Scheduled
                        </span>
                      </td>
                      <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-dark">View</a>
                        <button type="button" class="btn btn-sm btn-square btn-outline-dark text-danger-hover">
                          <i class="bi bi-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <div>
              </div>
            </div>
            <div class="card-footer border-0 py-5">
              <?= paginate($paginated) ?>
              <span class="text-muted text-sm">Showing 10 items out of 250 results found</span>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</div>