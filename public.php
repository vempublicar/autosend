<?php
// Supõe que session_start() já foi chamado no index.php

// Pegar o componente dinâmico com base em 'pg', padrão é 'home' se não especificado
$component = isset($_GET['pg']) ? basename($_GET['pg']) : 'home';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AutoSend</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/demo_2/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <header>
        <!-- Conteúdo do Header -->
    </header>

    <main>
        <?php
        // Incluir o conteúdo dinâmico baseado em 'pg'
        $contentPath = "app/public/$component.php";
        if (file_exists($contentPath)) {
            include $contentPath;
        } else {
            echo "<p>Página não encontrada.</p>";
            // Alternativamente, você pode incluir uma página de erro genérica
            // include 'path/to/error/page.php';
        }
        ?>
    </main>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/jquery.cookie.js"></script>
    <!-- endinject -->
  </body>
</html>
