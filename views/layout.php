<!doctype html>
<html lang="fr" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

    <link rel="stylesheet" href="/css/style.css">

    <script>
        // Apply saved theme before the page is displayed
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme) {
            document.documentElement.setAttribute(
                'data-bs-theme',
                savedTheme
            );
        }
    </script>
</head>

<body>

    <?php if ($withMenu) {
        echo $this->fetch('menu.php');
    } ?>

    <?= $content ?>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
    </script>

    <script>
        function updateThemeButton() {
            const currentTheme =
                document.documentElement.getAttribute('data-bs-theme');

            const button = document.getElementById('darkModeToggle');

            if (!button) {
                return;
            }

            if (currentTheme === 'dark') {
                button.textContent = '☀️ Light mode';
            } else {
                button.textContent = '🌙 Dark mode';
            }
        }


        function toggleDarkMode() {
            const currentTheme =
                document.documentElement.getAttribute('data-bs-theme');

            const newTheme =
                currentTheme === 'dark'
                    ? 'light'
                    : 'dark';

            document.documentElement.setAttribute(
                'data-bs-theme',
                newTheme
            );

            localStorage.setItem('theme', newTheme);

            updateThemeButton();
        }


        document.addEventListener('DOMContentLoaded', function () {

            const button = document.getElementById('darkModeToggle');

            if (button) {
                button.addEventListener('click', toggleDarkMode);
            }

            updateThemeButton();
        });
    </script>

</body>
</html>