<div class="container-fluid border-bottom fixed-top bg-slate-50 dark-bg-slate-900">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg">
          <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <a class="navbar-brand mt-2 mt-lg-0" href="#">
                <img src="<?= public_file('images/mfs.png') ?>" width="40" alt="Logo" loading="lazy" />
              </a>
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link px-4 rounded <?= urlIs('/user') ? 'bg-purple-500 text-white' : '' ?>" href="/">Kezdőlap</a>
                </li>
              </ul>
            </div>
            <div class="d-flex align-items-center">
              <div class="dropdown">
                <a class="text-reset me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-bell"></i>
                  <span class="badge rounded-pill bg-danger">1</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item" href="#">Some news</a></li>
                  <li><a class="dropdown-item" href="#">Another news</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
              <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="25" alt="User Avatar" loading="lazy" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                  <li><a class="dropdown-item" href="#">My profile</a></li>
                  <li><a class="dropdown-item" href="#">Settings</a></li>
                  <li>
                    <span>
                      <form action="/logout" method="POST">

                        <button class="dropdown-item" type="submit">Logout</button>
                      </form>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</div>