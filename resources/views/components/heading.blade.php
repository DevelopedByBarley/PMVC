<header class="bg-surface-primary  pt-6 pb-3">
    <div class="container-fluid mb-3">
        <div class="mb-npx">
            <div class="row align-items-center g-3">
                <div class="col-sm-6 col-12">
                    <h1 class="h2 mb-0 ls-tight pr-font text-5xl"><?= $title ?? 'Nincs cím megadva' ?></h1>
                    <p class="text-lg">Üdv, <strong class="pr-font"><?= session('admin')->email ?? session('user')->email ?></strong></p>
                </div>
                <!-- Actions -->
                <div class="col-sm-6 col-12 text-sm-end">
                    <div class="d-flex flex-wrap justify-content-sm-end gap-2">
                        <?php if(isset($documentation_route)):?>
                        <a href="<?= $documentation_route ?>"
                            class="btn d-inline-flex btn-pill btn-sm border border-2 border-sky-500 text-sky-500 hover-text-slate-50 hover-bg-sky-500 py-2">
                            <span class="pe-2">
                                <i class="bi bi-book"></i>
                            </span>
                            <span>Dokumentáció</span>
                        </a>
                        <?php endif?>
                        <a href="/admins/edit/<?= session('admin')->id?>"
                            class="btn d-inline-flex btn-pill btn-sm border border-2 border-orange-500 text-orange-500 hover-text-slate-50 hover-bg-orange-500 py-2">
                            <span class="pe-2">
                                <i class="bi bi-pencil"></i>
                            </span>
                            <span>Profil szerkesztése</span>
                        </a>
                        <?php if(session('admin')->role === 'admin'):?>
                        <a href="/admins/create"
                            class="btn d-inline-flex btn-pill btn-sm border border-2 border-indigo-500 bg-indigo-500 hover-bg-indigo-600 text-white py-2">
                            <span class="pe-2">
                                <i class="bi bi-plus"></i>
                            </span>
                            <span>Admin hozzáadása</span>
                        </a>
                        <?php endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
