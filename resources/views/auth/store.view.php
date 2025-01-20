<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <form action="/login" method="POST">
              <?= csrf() ?>
              <div class="mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                <p class="-50 mb-5">Please enter your login and password!</p>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="email" id="email" name="email" value="<?= old('email') ?>" class="form-control" />
                  <label class="form-label" for="email">Email</label>
                  <?= errors('email', $errors) ?>
                </div>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="password" name="password" id="password" class="form-control" />
                  <label class="form-label" for="password">Password</label>
                  <?= errors('password', $errors) ?>
                </div>

                <!-- <p class="small mb-5 pb-lg-2"><a class="-50" href="">Forgot password?</a></p> -->

                <button class="btn btn-lg px-5" type="submit">Login</button>

              </div>
            </form>

            <div>
              <p class="mb-0">Don't have an account? <a href="#!" class="-50 fw-bold">Sign Up</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>