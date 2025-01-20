<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?></title>
</head>

<body class="bg-red-100" data-bs-theme="dark">

  <?php require_once view_path('components/navbar') ?>

  <?= $root ?>

  <!--Footer-->

  <script type="module" src="/resources/dist/bundle.js?v=<?= time() ?>"></script>
</body>

</html>