<?php
function gerarJob($tabelaFila, $pastaHash)
{
    $caminhoBanco = "../customers/{$pastaHash}/meubanco.sqlite";

    if (!file_exists($caminhoBanco)) {
        return "Banco não encontrado.";
    }

    $db = new SQLite3($caminhoBanco);
    @mkdir('../jobs');

    // Verifica se a tabela existe
    $check = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tabelaFila}'");
    if (!$check) {
        return "Tabela da fila não encontrada.";
    }

    // Coleta registros pendentes
    $stmt = $db->prepare("SELECT * FROM {$tabelaFila} WHERE status = 'pendente' AND tentativa < 3 LIMIT 1000");
    $res = $stmt->execute();

    $fila = [];
    while ($msg = $res->fetchArray(SQLITE3_ASSOC)) {
        $fila[] = [
            'id' => $msg['id'],
            'telefone' => $msg['telefone'],
            'nome' => $msg['nome'] ?? '',
            'tentativa' => $msg['tentativa']
        ];
    }

    if (empty($fila)) {
        return "Nenhuma mensagem pendente.";
    }

    // Monta JSON de job
    $total = count($fila);
    $minutos = ceil($total / 4);
    $inicio = date('Y-m-d H:i:s');
    $fim = date('Y-m-d H:i:s', strtotime("+{$minutos} minutes"));

    $json = [
        'cliente_hash' => $pastaHash,
        'instancia' => null, // será definida na tela de monitoramento
        'tabela' => $tabelaFila,
        'inicio_previsto' => $inicio,
        'fim_previsto' => $fim,
        'mensagens_totais' => $total,
        'tempo_estimado' => "$minutos minutos",
        'dados' => $fila,
        'finalizado' => false
    ];

    file_put_contents("../jobs/{$pastaHash}_{$tabelaFila}.json", json_encode($json, JSON_PRETTY_PRINT));
    return "processando";
}
