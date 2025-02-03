<header class="bg-surface-primary  pt-6 pb-3">
    <div class="container-fluid mb-3">
        <div class="mb-npx">
            <div class="row align-items-center">
                <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                    <h1 class="h2 mb-0 ls-tight pr-font text-5xl"><?= $title ?? 'Nincs cím megadva' ?></h1>
                    <p class="text-lg">Üdv, <strong class="pr-font"><?= session('admin')->email ??  session('user')->email ?></strong></p>
                </div>
                <!-- Actions -->
                <div class="col-sm-6 col-12 text-sm-end">
                    <div class="mx-n1">
                        <a href="#" class="btn d-inline-flex btn-pill btn-sm border border-2 border-indigo-500  text-indigo-500 hover-text-slate-50 hover-bg-indigo-500  py-2 mx-1">
                            <span class="pe-2">
                                <i class="bi bi-pencil"></i>
                            </span>
                            <span>Profil szerkesztése</span>
                        </a>
                        <a href="#" class="btn d-inline-flex btn-pill btn-sm border border-2 border-indigo-500 bg-indigo-500 hover-bg-indigo-600 text-white  py-2 mx-1">
                            <span class=" pe-2">
                                <i class="bi bi-plus"></i>
                            </span>
                            <span>Admin hozzáadása</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>