<?php
$currentPage = (int)($_GET['offset'] ?? 1);
$totalPages = (int)($paginated->total_pages ?? 0);
$searchParameter = isset($_GET['search']) ? '?search=' . urlencode($_GET['search']) : '';

// Intelligens oldal tartomány számítás
$range = 2; // Hány oldalt mutassunk az aktuális előtt és után
$start = max(1, $currentPage - $range);
$end = min($totalPages, $currentPage + $range);

// Biztosítjuk, hogy mindig legalább 5 oldal látszódjon (ha van annyi)
if ($end - $start < 4) {
    if ($start == 1) {
        $end = min($totalPages, $start + 4);
    } else {
        $start = max(1, $end - 4);
    }
}
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 my-4">
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            <!-- Előző gomb -->
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="<?= $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . max(1, $currentPage - 1) ?>" 
                   aria-label="Előző"
                   <?= $currentPage <= 1 ? 'tabindex="-1"' : '' ?>>
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Első oldal -->
            <?php if ($start > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=1' ?>">1</a>
                </li>
                <?php if ($start > 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Középső oldalak -->
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="<?= $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . $i ?>"
                       <?= $currentPage == $i ? 'aria-current="page"' : '' ?>>
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Utolsó oldal -->
            <?php if ($end < $totalPages): ?>
                <?php if ($end < $totalPages - 1): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . $totalPages ?>"><?= $totalPages ?></a>
                </li>
            <?php endif; ?>

            <!-- Következő gomb -->
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="<?= $searchParameter . (empty($searchParameter) ? '?' : '&') . 'offset=' . min($totalPages, $currentPage + 1) ?>" 
                   aria-label="Következő"
                   <?= $currentPage >= $totalPages ? 'tabindex="-1"' : '' ?>>
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

    <?php if ($with_search): ?>
        <form class="d-flex gap-2" action="" method="GET">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Keresés..." 
                   value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   style="min-width: 200px;">
            <button type="submit" class="btn btn-primary px-4">Keresés</button>
        </form>
    <?php endif; ?>
</div>