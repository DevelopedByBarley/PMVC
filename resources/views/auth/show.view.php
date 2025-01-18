<div class="container vh-100 d-flex align-items-center justify-content-center" style="margin-top: 200px;">
  <div class="row w-100">
    <!-- Profile Sidebar -->
    <div class="col-12 col-md-5 mx-auto">
      <div class="card">
        <div class="card-body text-center">
          <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle mb-3 img-fluid" alt="User Avatar">
          <h3>John Doe</h3>
          <p class="text-muted"><?= $user->email ?></p>
          <a href="#" class="btn btn-primary btn-block">Edit Profile</a>
        </div>
      </div>
    </div>

    <!-- Profile Details -->
    <div class="col-12 col-md-8 mx-auto mt-5">
      <div class="card">
        <div class="card-header">
          <h4>Profile Information</h4>
        </div>
        <div class="card-body">
          <form class="row gap-5">
            <div class="col-12 form-group row">
              <label for="firstName" class="col-md-3 col-form-label">First Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="firstName" value="John">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="lastName" class="col-md-3 col-form-label">Last Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="lastName" value="Doe">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="email" class="col-md-3 col-form-label">Email Address</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="email" value="johndoe@example.com">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="phone" class="col-md-3 col-form-label">Phone</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="phone" value="+123 456 7890">
              </div>
            </div>

            <div class="col-12  form-group row">
              <label for="bio" class="col-md-3 col-form-label">Bio</label>
              <div class="col-md-9">
                <textarea class="form-control" id="bio" rows="3">A passionate web developer.</textarea>
              </div>
            </div>

            <div class="col-12  form-group row">
              <div class="col-md-9 offset-md-3">
                <button type="submit" class="btn btn-success">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>