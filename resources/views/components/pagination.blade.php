<?php
$currentPage = $_GET['offset'] ?? 1;

$totalPages = (int)$paginated->total_pages; // összes oldalszám
$searchParameter = isset($_GET['search']) ? '?search=' . $_GET['search'] : '';


?>

<nav class="d-flex justify-content-between">
  <ul class="pagination">
    <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . max(1, $currentPage - 1); ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
      <li class="page-item <?php echo $currentPage == $i ? 'active' : ''; ?>">
        <a class="page-link" href="<?php echo $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . $i; ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?php echo $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . min($totalPages, $currentPage + 1); ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
    <?php if ($with_search): ?>
      <li class="page-item">
        <form class="form-inline" action="" method="GET">
          <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </li>
    <?php endif; ?>
  </ul>
</nav>