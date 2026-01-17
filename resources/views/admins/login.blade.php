<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-4 mx-auto">
            <form method="POST" action="/admin/login">
                <?= csrf() ?>
                <h2>Admin</h2>
                <hr class="mb-3">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cím</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?= old('email') ?>"
                        aria-describedby="emailHelp">
                    <?php errors('email', $errors); ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Jelszó</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>
                <button type="submit" class="btn btn-primary mt-2">Bejelentkezés</button>
            </form>
        </div>
    </div>
</div>
