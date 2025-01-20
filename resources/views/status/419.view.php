<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Session Expired</title>
</head>

<body data-bs-theme="dark" class="d-flex align-items-center justify-content-center vh-100">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">419</h1>
            <p class="fs-3"><span class="text-warning">Oops!</span> Page expired.</p>
            <p class="lead">
                Your page has expired. Please refresh the page or log in again to continue.
            </p>
            <a href="/" class="btn btn-primary">Go Home</a>
        </div>
    </div>
    <script type="module" src="/resources/dist/bundle.js?v=<?= time() ?>"></script>
</body>

</html>
