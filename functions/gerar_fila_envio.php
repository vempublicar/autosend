<?php
session_start();
$pastaHash = sha1($_SESSION['email'] ?? '');
$caminhoBanco = "../customers/{$pastaHash}/meubanco.sqlite";

if (!file_exists($caminhoBanco)) {
    die(json_encode(['erro' => 'Banco de dados não encontrado.']));
}

$db = new SQLite3($caminhoBanco);
$db->exec("PRAGMA journal_mode = WAL");

// Cria tabela de campanhas se não existir
$db->exec("CREATE TABLE IF NOT EXISTS campanhas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT,
    tabela_fila TEXT,
    filtros TEXT,
    mensagem TEXT,
    qtde_numeros TEXT,
    info TEXT,
    origem TEXT,
    gpt BOOLEAN,
    criada_em TEXT DEFAULT CURRENT_TIMESTAMP
)");

// Coleta os dados do POST
$origem         = $_POST['origem'] ?? 'contatos';
$filtro1_coluna = $_POST['filtro1_coluna'] ?? '';
$filtro1_valor  = $_POST['filtro1_valor'] ?? '';
$filtro2_coluna = $_POST['filtro2_coluna'] ?? '';
$filtro2_valor  = $_POST['filtro2_valor'] ?? '';
$mensagem       = trim($_POST['mensagem'] ?? '');
$nomeCampanha   = trim($_POST['nome_campanha'] ?? 'Campanha');
$limite         = (int) ($_POST['limite_envios'] ?? 100);
$gpt            = isset($_POST['gpt']) ? 1 : 0;
$incluirAtivos = isset($_POST['incluir_ativos']) ? 1 : 0;


// Validação mínima
if (!$filtro1_coluna || !$filtro1_valor || !$mensagem) {
    header("Location: ../dashboard&page=create-campaign");
}

// Garante que a origem é segura
$tabelaOrigem = ($origem === 'leads') ? 'leads' : 'contatos';

// Consulta com filtros
$sql = "SELECT * FROM {$tabelaOrigem} WHERE {$filtro1_coluna} = ?";
$params = [$filtro1_valor];

if ($filtro2_coluna && $filtro2_valor) {
    $sql .= " AND {$filtro2_coluna} = ?";
    $params[] = $filtro2_valor;
}

// Se o cliente NÃO marcar o checkbox, filtra números com campanha ativa
if (!$incluirAtivos) {
    $sql .= " AND (
        campanha_ativa IS NULL OR 
        campanha_ativa = 0 OR 
        (
            campanha_ativa > 1 AND 
            date(data_envio) <= date('now', '-10 days')
        )
    )";
}

$sql .= " LIMIT ?";
$params[] = $limite;

$stmt = $db->prepare($sql);
foreach ($params as $i => $val) {
    $stmt->bindValue($i + 1, $val);
}
$res = $stmt->execute();

// Gera base para nome e hash
$baseHash = 'fila_' . sha1($nomeCampanha . $filtro1_coluna . $filtro1_valor . time());
$contatos = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    if (!empty($row['telefone'])) {
        $contatos[] = $row;
    }
}

$totalContatos = count($contatos);
$totalCampanhas = ceil($totalContatos / 100);

for ($i = 0; $i < $totalCampanhas; $i++) {
    $subLista = array_slice($contatos, $i * 100, 100);

    $indice = $i + 1;
    $tabelaFila = "{$baseHash}_{$indice}";
    $nomeCampanhaFinal = "{$nomeCampanha} - {$indice}";

    // Cria tabela da fila
    $db->exec("CREATE TABLE IF NOT EXISTS $tabelaFila (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        telefone TEXT,
        nome TEXT,
        status TEXT DEFAULT 'pendente',
        tentativa INTEGER DEFAULT 0,
        criado_em TEXT DEFAULT CURRENT_TIMESTAMP
    )");

    // Insere os dados na fila
    foreach ($subLista as $contato) {
        $telefone = $contato['telefone'];
        $nome = $contato['nome'] ?? '';

        $ins = $db->prepare("INSERT INTO $tabelaFila (telefone, nome) VALUES (?, ?)");
        $ins->bindValue(1, $telefone);
        $ins->bindValue(2, $nome);
        $ins->execute();

        $db->exec("UPDATE {$tabelaOrigem} 
           SET campanha_ativa = COALESCE(campanha_ativa, 0) + 1, 
               data_envio = date('now') 
           WHERE telefone = '{$telefone}'");
    }

    // Registra campanha
    // Verifica quantos contatos foram inseridos na tabela
    $qtdeNumeros = count($subLista);
    $info = ($qtdeNumeros === 0)
        ? 'Os filtros selecionados não encontraram números.'
        : 'Campanha gerada com sucesso.';

    $stmtCamp = $db->prepare("INSERT INTO campanhas (
    nome, tabela_fila, filtros, mensagem, qtde_numeros, info, origem, gpt
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtCamp->bindValue(1, $nomeCampanhaFinal);
    $stmtCamp->bindValue(2, $tabelaFila);
    $stmtCamp->bindValue(3, json_encode([
        'filtro1' => [$filtro1_coluna, $filtro1_valor],
        'filtro2' => [$filtro2_coluna, $filtro2_valor]
    ]));
    $stmtCamp->bindValue(4, $mensagem);
    $stmtCamp->bindValue(5, (string) $qtdeNumeros);
    $stmtCamp->bindValue(6, $info);
    $stmtCamp->bindValue(7, $origem);
    $stmtCamp->bindValue(8, $gpt);
    $stmtCamp->execute();
}

header("Location: ../dashboard&page=create-campaign");
exit;
