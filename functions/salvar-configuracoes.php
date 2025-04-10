<?php
session_start();

$pastaHash = sha1($_SESSION['email']);
$db = new SQLite3("../customers/{$pastaHash}/meubanco.sqlite");

$id = $_POST['id'] ?? null;
$data = $_POST['data'] ?? null;
$retorno = $_POST['retorno'] ?? null;
$grupoA = $_POST['grupoA'] ?? null;
$gpt = $_POST['gpt'] ?? 0;

if ($id) {
    $stmt = $db->prepare("UPDATE contatos SET data = ?, retorno = ?, grupoA = ?, gpt = ? WHERE id = ?");
    $stmt->bindValue(1, $data);
    $stmt->bindValue(2, $retorno);
    $stmt->bindValue(3, $grupoA);
    $stmt->bindValue(4, $gpt);
    $stmt->bindValue(5, $id);
    $stmt->execute();
    echo "Sucesso";
} else {
    echo "ID não informado";
}
