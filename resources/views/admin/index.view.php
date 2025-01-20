<div class="controller mt-5">
  <div class="row">
    <div class="col-12">
      <h5>Dashboard <?php echo session('admin')->email ?? '' ?></h5>
      <a href="/admin/logout" class="btn">Logout</a>
    </div>

    <div class="col-12">
        <?= paginate($paginated) ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Bio</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($paginated['data'] as $user) : ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['email']; ?></td>
              <td><?php echo $user['phone']; ?></td>
              <td><?php echo $user['bio']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
    </div>
  </div>
</div>
