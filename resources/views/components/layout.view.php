<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/node_modules/bootstrap/dist/css/bootstrap.min.css?v=<?= time() ?>">
  <link rel="stylesheet" href="/resources/css/main.css">
  <title><?= APP_NAME ?></title>
</head>

<body data-bs-theme="dark">
  
  <?php require_once view_path('components/navbar') ?>

  <?= $root ?>

  <!--Footer-->

  <script type="module" src="/resources/js/main.js"></script>
  <script src="/public/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js?v=<?= time() ?>"></script>
</body>

</html>