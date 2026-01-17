<?php if (!session('admin')) {
    return null;
} ?>

<nav class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light border-bottom border-bottom-lg-0 border-end-lg"
    id="navbarVertical">
    <div class="container">
        <!-- Toggler -->
        <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse"
            aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- User menu (mobile) -->
        <div class="navbar-user d-lg-none">
            <!-- Dropdown -->
            <div class="dropdown">
                <!-- Toggle -->
                <a href="#" id="sidebarAvatar" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" class="d-xl-none">
                    <div class="avatar-parent-child text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <span class="avatar-child avatar-badge bg-success"></span>
                    </div>
                </a>
                <!-- Menu -->
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarAvatar">
                    <a href="/admins/edit/<?= session('admin')->id ?>"" class="dropdown-item">
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($_SESSION['admin']->name) ?></strong>
                            <div class="text-muted">
                                <?= htmlspecialchars($_SESSION['admin']->email) ?>
                            </div>
                        </div>
                    </a>

                    <a class="dropdown-item" href="/admin/activities">
                        <i class="bi bi-list-check me-2"></i> Tevékenységnapló
                    </a>
                    <a class="dropdown-item" href="/admins">
                        <i class="bi bi-people me-2"></i> Adminok kezelése
                    </a>
                    <hr>
                    <form action="/admin/logout" method="POST">
                        <?= csrf() ?>
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Kijelentkezés
                        </button>
                    </form>

                </div>
            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <!-- Navigation -->

            <div class="d-flex justify-content-between w-100">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link rounded <?= urlIs('/admin/dashboard') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="/admin/dashboard">
                            <i class="bi bi-house"></i>
                            Kezdőlap
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= urlIs('/admin/gallery') || urlIs('/admin/gallery/categories') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="#" id="galleryDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-bookmarks"></i> Galéria
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="galleryDropdown">
                            <li><a class="dropdown-item <?= urlIs('/admin/gallery') ? 'active' : '' ?>"
                                    href="/admin/gallery"><i class="bi bi-image me-2"></i>Képek</a>
                            </li>
                            <li><a class="dropdown-item <?= urlIs('/admin/gallery/categories') ? 'active' : '' ?>"
                                    href="/admin/gallery/categories"><i
                                        class="bi bi-folder2-open me-2"></i>Kategóriák</a></li>
                        </ul>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= urlIs('/admin/blog') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="/admin/blog">
                            <i class="bi bi-journal-text"></i> Blog
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= urlIs('/admin/files') || urlIs('/admin/files/categories') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="#" id="filesDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-folder2-open"></i> Fájlkezelő
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="filesDropdown">
                            <li><a class="dropdown-item <?= urlIs('/admin/files') ? 'active' : '' ?>"
                                    href="/admin/files"><i class="bi bi-file-earmark me-2"></i>Fájlok</a>
                            </li>
                            <li><a class="dropdown-item <?= urlIs('/admin/files/categories') ? 'active' : '' ?>"
                                    href="/admin/files/categories"><i class="bi bi-folder2-open me-2"></i>Fájl
                                    kategóriák</a></li>
                        </ul>

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= urlIs('/admin/programs') || urlIs('/admin/program/categories') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="#" id="programsDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-calendar-event"></i> Programok
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="programsDropdown">
                            <li><a class="dropdown-item <?= urlIs('/admin/programs') ? 'active' : '' ?>"
                                    href="/admin/programs"><i class="bi bi-calendar-event me-2"></i>Programok</a>
                            </li>
                            <li><a class="dropdown-item <?= urlIs('/admin/program/categories') ? 'active' : '' ?>"
                                    href="/admin/program/categories"><i class="bi bi-calendar-event me-2"></i>Program
                                    kategóriák</a></li>
                        </ul>

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= urlIs('/admin/town-hall-posts') || urlIs('/admin/journals') || urlIs('/admin/notable-entries') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="#" id="heritageDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-book"></i> További cikkek
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="heritageDropdown">
                            <li><a class="dropdown-item <?= urlIs('/admin/town-hall-posts') ? 'active' : '' ?>"
                                    href="/admin/town-hall-posts"><i class="bi bi-building me-2"></i>Helytörténeti
                                    cikkek</a></li>
                            <li><a class="dropdown-item <?= urlIs('/admin/journals') ? 'active' : '' ?>"
                                    href="/admin/journals"><i class="bi bi-journal-text me-2"></i>Budafok és
                                    vidéke</a></li>
                            <li><a class="dropdown-item <?= urlIs('/admin/notable-entries') ? 'active' : '' ?>"
                                    href="/admin/notable-entries"><i class="bi bi-star-fill me-2"></i>Kiemelt
                                    események
                                    és híres személyek</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-orange-500 text-white rounded <?= urlIs('/admin/community-stories') ? 'bg-indigo-500 text-white' : '' ?>"
                            href="/admin/community-stories">
                            <i class="bi bi-house"></i>
                            Közösségi történetek
                        </a>
                    </li>
                </ul>

                <div class="position-relative">
                    <a href="#" id="sidebarAvatar" role="button" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" class="d-none d-xl-block">
                        <div class="avatar-parent-child text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                            <span class="avatar-child avatar-badge bg-success"></span>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute left-0" aria-labelledby="sidebarAvatar">
                        <a href="/admins/edit/<?= session('admin')->id ?>"" class="dropdown-item">
                            <div class="dropdown-header">
                                <strong><?= htmlspecialchars($_SESSION['admin']->name) ?></strong>
                                <div class="text-muted">
                                    <?= htmlspecialchars($_SESSION['admin']->email) ?>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item" href="/admin/activities">
                            <i class="bi bi-list-check me-2"></i> Tevékenységnapló
                        </a>
                        <a class="dropdown-item" href="/admins">
                            <i class="bi bi-people me-2"></i> Adminok kezelése
                        </a>
                        <hr>

                        <form action="/admin/logout" method="POST">
                            <?= csrf() ?>
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> Kijelentkezés
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
</nav>
