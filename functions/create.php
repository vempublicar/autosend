<?php

function checkCreate($email)
{
    if (!$email) return;

    // Caminho base do cliente
    $pastaHash = sha1($email);
    $basePath = "../customers/{$pastaHash}";

    // Subpastas obrigatórias
    $subpastas = ['block', 'send', 'arq', 'config', 'mesages', 'images', 'audio'];

    // 1. Criar pasta base se não existir
    if (!file_exists($basePath)) {
        mkdir($basePath, 0777, true);
    }

    // 2. Garantir que as subpastas existem
    foreach ($subpastas as $pasta) {
        $subPath = "$basePath/$pasta";
        if (!file_exists($subPath)) {
            mkdir($subPath, 0777, true);
        }
    }

    // 3. Criar ou abrir banco
    $caminhoBanco = "$basePath/meubanco.sqlite";
    $db = new SQLite3($caminhoBanco);

    // 4. Executar script SQL (reexecutar sempre para garantir atualizações)
    $sqlPath ='../sql/schema.sql';
    if (file_exists($sqlPath)) {
        $schema = file_get_contents($sqlPath);
        $db->exec($schema);
    }

    $db->close();
}
