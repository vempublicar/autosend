<?php
session_start();

require_once 'app/system/auth.php';

$page = isset($_GET['pg']) ? $_GET['pg'] : 'home';

if($_GET['pg'] == 'sair'){
    session_destroy();
    header("Location: login");
    exit;
}

// Redireciona usuário autenticado para dashboard se ele tentar acessar login ou register
if (isset($_SESSION['email']) && verificarTokenOuRenovar()) {
    if (in_array($page, ['login', 'register', 'login.php', 'register.php'])) {
        header("Location: dashboard");
        exit;
    }
}

// Verifica se é um acesso direto a function ou system
if (str_starts_with($page, 'function/') || str_starts_with($page, 'system/')) {
    loadFunctionPage($page);
} else {
    // Remove .php se vier no final (ex: home.php → home)
    $pageName = str_ends_with($page, '.php') ? basename($page, '.php') : $page;
    loadMainPage($pageName);
}

// Carrega arquivos específicos de system ou function
function loadFunctionPage($requestedPath) {
    $filePath = 'app/' . $requestedPath;

    if (file_exists($filePath)) {
        include $filePath;
    } else {
        include 'landingpage.php'; // ou erro 404
    }
}

// Carrega páginas normais com base nas pastas padrão
function loadMainPage($requestedPage) {
    $folders = [
        'public' => ['folder' => 'app/public', 'file' => 'public.php'],
        'private' => ['folder' => 'app/private', 'file' => 'private.php', 'requiresAuth' => true],
        'system' => ['folder' => 'app/system', 'file' => 'system.php', 'requiresAuth' => true]
    ];

    foreach ($folders as $info) {
        $path = "{$info['folder']}/$requestedPage.php";
        if (file_exists($path)) {
            if (!empty($info['requiresAuth']) && !isset($_SESSION['email'])) {
                include 'public.php';
                return;
            }
            include $info['file'];
            return;
        }
    }

    include 'landingpage.php';
}
