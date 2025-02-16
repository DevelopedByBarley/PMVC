<nav class="navbar navbar-expand-lg border-bottom fixed-top pr-font bg-light dark-bg-slate-800">
  <div class="container ">
    <a class="navbar-brand " href="/">Brand</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link btn-dark " href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn-dark " href="/admin">Admin</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link btn-dark dropdown-toggle " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Lang
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li>
              <form action="/lang" method="POST" class="d-inline">
                <input type="hidden" name="lang" value="hu-Hu">
                <button type="submit" class="dropdown-item">Hu</button>
              </form>
            </li>
            <li>
              <form action="/lang" method="POST" class="d-inline">
                <input type="hidden" name="lang" value="en-En">
                <button type="submit" class="dropdown-item">En</button>
              </form>
            </li>
          </ul>

        </li>
        <li class="nav-item">
          <a class="nav-link btn-dark disabled " href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <?php if (session('user')) : ?>
        <div class="btn-group dropstart d-none d-lg-block">
          <div class="dropdown">
            <button class="btn  dropdown-toggle p-1 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-profiles/avatar-1.webp" class="avatar img-fluid rounded-circle" style="height: 30px; width: 30px;" alt="">
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/profile">Profile</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
              <li>
                <form action="/logout" class="px-5" method="POST">
                  <?= csrf() ?>
                  <button class="btn btn-danger " type="submit">Logout</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
        <div class="btn-group dropend d-lg-none">
          <div class="dropdown">
            <button class="btn  dropdown-toggle p-1 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-profiles/avatar-1.webp" class="avatar img-fluid rounded-circle" style="height: 30px; width: 30px;" alt="">
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
              <li>
                <form action="/logout" class="px-5" method="POST">
                  <?= csrf() ?>
                  <button class="btn btn-danger " type="submit">Logout</button>
                </form>
              </li>
            </ul>
          </div>


        </div>
      <?php else : ?>
        <div>
          <a href="/register" class="btn btn-dark m-1 border-0" type="submit">Register</a>
          <a href="/login" class="btn btn-dark m-1 border-0" type="submit">Login</a>
        </div>
      <?php endif ?>
      <div class="form-check form-switch theme-switcher p-0 mx-xl-3 mt-2 mt-xl-0">
        <input type="checkbox" class="form-check-input checkbox text-2xl" role="switch" id="theme-toggle">
        <label for="theme-toggle" class="dark-bg-sky-700 bg-gray-300 checkbox-label">
          <i class="fas fa-moon"></i>
          <i class="fas fa-sun"></i>
          <span class="ball"></span>
        </label>
      </div>
    </div>
  </div>
</nav>