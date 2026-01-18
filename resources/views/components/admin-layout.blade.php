<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="/resources/summernote/summernote.css">
    <link rel="stylesheet" href="/resources/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title><?= APP_NAME ?></title>
</head>

<body>
    <!-- Navbar -->
    <?php require_once view_path('components/admin-navbar'); ?>
    <?php require_once view_path('components/toast'); ?>
    <?= $root ?>


    <!--Footer-->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>
    <script src="/resources/bootstrap/js/bootstrap.bundle.js?v=<?= time() ?>"></script>
    <script src="/resources/summernote/summernote.js"></script>
    <script>
        $(document).ready(function() {
            $('.editor').summernote({
                height: 300,
                placeholder: 'Írja be a bejegyzés tartalmát...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            $('.editor-with-image').summernote({
                height: 300,
                placeholder: 'Írja be a bejegyzés tartalmát...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                maximumImageFileSize: 2097152, // 2 MB (2 * 1024 * 1024)
                callbacks: {
                    onImageUpload: function(files) {
                        // Maximum 5 képet engedélyez egyszerre
                        if (files.length > 5) {
                            alert('Maximum 5 képet tölthetsz fel egyszerre!');
                            return;
                        }

                        // Ellenőrizzük minden kép méretét
                        for (let i = 0; i < files.length; i++) {
                            if (files[i].size > 2097152) { // 2 MB
                                alert('A(z) "' + files[i].name +
                                    '" túl nagy! Maximum 2 MB engedélyezett.');
                                return;
                            }
                            // Base64-be konvertálás és beillesztés
                            const reader = new FileReader();
                            reader.onloadend = function() {
                                $('.editor-with-image').summernote('insertImage', reader.result);
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    },
                    onMediaDelete: function(target) {
                        // Kép törlésekor
                        target.remove();
                    }
                }
            });

            if (window.bootstrap && window.bootstrap.Dropdown) {
                document.querySelectorAll('.note-editor .dropdown-toggle').forEach(function(el) {
                    el.setAttribute('data-bs-toggle', 'dropdown');
                    new window.bootstrap.Dropdown(el);
                });
            }
            // Kép előnézet
            $('#image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').html(
                            '<img src="' + e.target.result +
                            '" class="img-thumbnail" style="max-width: 300px;">'
                        );
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').html('');
                }
            });
        });
    </script>

    <script type="module" src="/resources/js/main.js?v=<?= time() ?>"></script>
    <script src="/resources/js/images.js?v=<?= time() ?>"></script>
    <script>
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: {
                autoStart: true
            }
        });
    </script>
</body>

</html>
