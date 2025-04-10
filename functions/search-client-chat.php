<?php
session_start();

$pastaHash = sha1($_SESSION['email']);
$caminhoBanco = "../customers/{$pastaHash}/meubanco.sqlite";
$db = new SQLite3($caminhoBanco);

$termo = $_GET['termo'] ?? '';
$termo = strtolower(trim($termo));
$limite = 20;
$offset = intval($_GET['offset'] ?? 0);

// Prioriza quem tem notificação > 0
$sql = "SELECT * FROM contatos WHERE 1";
if ($termo !== '') {
    $sql .= " AND (LOWER(nome) LIKE '%$termo%' OR LOWER(telefone) LIKE '%$termo%' OR LOWER(email) LIKE '%$termo%')";
}
$sql .= " ORDER BY CASE WHEN notifica > 0 THEN 0 ELSE 1 END, notifica DESC, id DESC LIMIT $limite OFFSET $offset";

$res = $db->query($sql);
$contatos = [];

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $contatos[] = $row;
}

echo json_encode($contatos);
