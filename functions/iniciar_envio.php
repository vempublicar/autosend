<?php
session_start();
require 'processar_fila.php';

$pastaHash = sha1($_SESSION['email'] ?? '');
$tabela = $_POST['tabela'] ?? '';

if (!$tabela) {
    $_SESSION['erro_envio'] = 'Tabela inválida.';
    header('Location: ../dashboard&page=send');
    exit;
}

$status = gerarJob($tabela, $pastaHash);

if ($status !== "processando") {
    $_SESSION['erro_envio'] = $status;
    header('Location: ../dashboard&page=send');
    exit;
}

header("Location: ../dashboard&page=monitor");
exit;
