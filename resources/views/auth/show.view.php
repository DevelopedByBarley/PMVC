<div class="container vh-100" style="margin-top: 90px;">
  <div class="row w-100">
    <!-- Profile Sidebar -->
    <div class="col-12 col-md-8 mx-auto text-center">
      <div class="card">
        <div class="card-body">
          <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle mb-3" style="height: 100px; width: 100px;" alt="User Avatar">
          <h3><?= $user->name ?></h3>
          <p class="text-muted"><?= $user->email ?></p>
        </div>
      </div>
    </div>

    <!-- Profile Details -->
    <div class="col-12 col-md-8 mt-5 mx-auto">
      <div class="card">
        <div class="card-header">
          <h4>Profile Information</h4>
        </div>
        <div class="card-body">
          <form class="row gap-5" action="/user/update" method="POST">

            <input type="hidden" name="_method" value="UPDATE">

            <div class="col-12 form-group row">
              <label for="firstName" class="col-md-3 col-form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="name" value=" <?= old('name', $user->name) ?>">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="email" class="col-md-3 col-form-label">Email Address</label>
              <div class="col-md-9">
                <input type="email" class="form-control bg-secondary" readonly id="email" value=" <?= old('name', $user->email) ?>">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="phone" class="col-md-3 col-form-label">Phone</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="phone" value=" <?= old('name', $user->phone) ?>">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="bio" class="col-md-3 col-form-label">Bio</label>
              <div class="col-md-9">
                <textarea class="form-control" id="bio" rows="3"> <?= old('name', $user->bio) ?></textarea>
              </div>
            </div>

            <div class="col-12  form-group row">
              <div class="col-md-9 offset-md-3">
                <button type="submit" class="btn btn-outline-warning">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>