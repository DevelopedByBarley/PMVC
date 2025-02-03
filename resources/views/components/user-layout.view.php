<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.css?v=<?= time() ?>">
  <link rel="stylesheet" href="/resources/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <title><?= APP_NAME ?></title>
</head>

<body>

  <?php require_once view_path('components/user-navbar') ?>
  <?php require_once view_path('components/toast') ?>

  <?= $root ?>


  <script type="module" src="/resources/js/main.js"></script>
  <script src="/resources/bootstrap/js/bootstrap.bundle.js?v=<?= time() ?>"></script>
</body>

</html>