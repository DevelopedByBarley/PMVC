  <?php $toast = $_SESSION['_flash']['toast'] ?? null; ?>

  <?php if (isset($toast)): ?>
    <!-- Toast Container -->
    <div class="toast-container position-fixed <?= $toast['position'] ?? 'top-0 end-0' ?> p-3" style="z-index: 1080;">
      <div id="toast"
        class="toast show border-0 shadow-lg"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-autohide="true"
        data-bs-delay="<?= $toast['delay'] ?? '5000' ?>">

        <!-- Toast Header -->
        <div class="toast-header bg-<?= $toast['bg'] ?? 'primary' ?> text-white border-0">
          <!-- Icon based on toast type -->
          <div class="me-2">
            <?php
            $type = $toast['type'] ?? 'info';
            switch ($type) {
              case 'success':
                $icon = '<i class="fas fa-check-circle"></i>';
                break;
              case 'danger':
              case 'error':
                $icon = '<i class="fas fa-exclamation-circle"></i>';
                break;
              case 'warning':
                $icon = '<i class="fas fa-exclamation-triangle"></i>';
                break;
              case 'info':
                $icon = '<i class="fas fa-info-circle"></i>';
                break;
              default:
                $icon = '<i class="fas fa-bell"></i>';
                break;
            }
            echo $icon;
            ?>
          </div>

          <strong class="me-auto">
            <?php
            if (isset($toast['title'])) {
              echo $toast['title'];
            } else {
              $type = $toast['type'] ?? 'info';
              switch ($type) {
                case 'success':
                  echo 'Siker';
                  break;
                case 'danger':
                case 'error':
                  echo 'Hiba';
                  break;
                case 'warning':
                  echo 'Figyelmeztetés';
                  break;
                case 'info':
                  echo 'Információ';
                  break;
                default:
                  echo 'Üzenet';
                  break;
              }
            }
            ?>
          </strong>

          <?php if (isset($toast['time'])): ?>
            <small class="opacity-75"><?= $toast['time'] ?></small>
          <?php endif; ?>

          <button type="button"
            class="btn-close btn-close-white ms-2"
            data-bs-dismiss="toast"
            aria-label="Bezárás"></button>
        </div>

        <!-- Toast Body -->
        <div class="toast-body bg-white text-dark p-3">
          <div class="d-flex align-items-start">
            <?php if (isset($toast['icon_body'])): ?>
              <div class="me-3 text-<?= $toast['bg'] ?? 'primary' ?>">
                <i class="<?= $toast['icon_body'] ?> fa-lg"></i>
              </div>
            <?php endif; ?>

            <div class="flex-grow-1">
              <?= $toast['message'] ?? '' ?>

              <?php if (isset($toast['description'])): ?>
                <div class="small text-muted mt-1">
                  <?= $toast['description'] ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Action buttons if needed -->
          <?php if (isset($toast['actions']) && is_array($toast['actions'])): ?>
            <div class="mt-3 pt-2 border-top">
              <div class="d-flex gap-2">
                <?php foreach ($toast['actions'] as $action): ?>
                  <button type="button"
                    class="btn btn-sm <?= $action['class'] ?? 'btn-outline-primary' ?>"
                    onclick="<?= $action['onclick'] ?? '' ?>">
                    <?= $action['label'] ?? 'Művelet' ?>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>



  <?php endif; ?>