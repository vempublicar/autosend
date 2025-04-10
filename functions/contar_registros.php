<?php
session_start();
$pastaHash = sha1($_SESSION['email'] ?? '');
$dbPath = "../customers/{$pastaHash}/meubanco.sqlite";

if (!file_exists($dbPath)) {
    echo json_encode(['total' => 0]);
    exit;
}

$db = new SQLite3($dbPath);

$origem = $_GET['origem'] ?? 'contatos';
$tabela = ($origem === 'leads') ? 'leads' : 'contatos';

$f1_col = $_GET['filtro1_coluna'] ?? '';
$f1_val = $_GET['filtro1_valor'] ?? '';
$f2_col = $_GET['filtro2_coluna'] ?? '';
$f2_val = $_GET['filtro2_valor'] ?? '';

$colunasPermitidas = ['status','etiqueta','data','prioridade','variavelA','variavelB','variavelC','grupoA','grupoB','grupoC'];
if (!in_array($f1_col, $colunasPermitidas)) {
    echo json_encode(['total' => 0]);
    exit;
}

$sql = "SELECT COUNT(*) as total FROM $tabela WHERE $f1_col = ?";
$params = [$f1_val];

if ($f2_col && in_array($f2_col, $colunasPermitidas) && $f2_val) {
    $sql .= " AND $f2_col = ?";
    $params[] = $f2_val;
}

$stmt = $db->prepare($sql);
foreach ($params as $i => $v) {
    $stmt->bindValue($i + 1, $v);
}

$res = $stmt->execute();
$dados = $res->fetchArray(SQLITE3_ASSOC);

echo json_encode(['total' => $dados['total'] ?? 0]);
