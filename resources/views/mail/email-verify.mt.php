<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #333;
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      background-color: #f4f4f4;
      padding: 20px;
    }

    .header {
      background-color: #4CAF50;
      color: white;
      padding: 10px 0;
      text-align: center;
    }

    .content {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
    }

    .footer {
      text-align: center;
      padding: 10px;
      font-size: 12px;
      color: #777;
      background-color: #f4f4f4;
      border-top: 1px solid #ddd;
    }

    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="header">
      <h2>Üdvözöljük a szolgáltatásunkban!</h2>
    </div>

    <div class="content">
      <h3>Üdvözöljük rendszerünkben,</h3>
      <p>A lenti linkre kattintva megerősítheti regisztrációját, ezután beléphet a profiljába.</p>
      <p>Ha bármilyen kérdése van, bátran vegye fel velünk a kapcsolatot.</p>
      <div style="display: flex;">
        <a href="https://example.com" class="btn">További információk</a>
        <a href="<?= $_ENV['APP_URL'] . '/verify-email?token=' . $token ?>" class="btn">Regisztráció megerősítése</a>
        </div>
    </div>

    <div class="footer">
      <p>&copy; 2025 Cég neve | Minden jog fenntartva.</p>
    </div>
  </div>

</body>

</html>