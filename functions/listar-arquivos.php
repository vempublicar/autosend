<?php
session_start();

$tipo = $_GET['tipo'] ?? '';
$permitidas = ['audio', 'images', 'arq'];

if (!in_array($tipo, $permitidas)) {
    echo json_encode([]);
    exit;
}

$pastaHash = sha1($_SESSION['email']);
$caminho = "customers/{$pastaHash}/{$tipo}";

$arquivos = [];

if (is_dir($caminho)) {
    foreach (scandir($caminho) as $arq) {
        if (!in_array($arq, ['.', '..']) && is_file("$caminho/$arq")) {
            $arquivos[] = $arq;
        }
    }
}

echo json_encode($arquivos);
