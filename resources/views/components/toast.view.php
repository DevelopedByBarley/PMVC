  <?php $toast = $_SESSION['_flash']['toast'] ?? null ?>

  
  <?php if (isset($toast)): ?>
    <div id="toast" class="toast show position-fixed z-3 bg-<?= $toast['bg'] ?? '' ?> text-<?= $toast['color'] ?? '' ?>" style="right: 0;top: 100px;" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
       <!--  <img src="..." class="rounded me-2" alt="..."> -->
        <strong class="me-auto"><?= 'Üzenet' ?> </strong>
        <small><?= $toast['time'] ?? '' ?></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?= $toast['message'] ?? '' ?>
      </div>
    </div>
  <?php endif ?>