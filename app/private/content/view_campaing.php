<?php
$pastaHash = sha1($_SESSION['email'] ?? '');
$caminhoBanco = "customers/{$pastaHash}/meubanco.sqlite";
$idCampanha = $_GET['camp'] ?? '';

if (!file_exists($caminhoBanco)) {
  echo "<div class='alert alert-danger'>Banco de dados não encontrado.</div>";
  exit;
}

$db = new SQLite3($caminhoBanco);

$tabela = $_GET['tabela'] ?? '';
if (!$tabela || !preg_match('/^fila_[a-f0-9]{40}_\d+$/', $tabela)) {
  echo "<div class='alert alert-warning'>Tabela inválida.</div>";
  exit;
}

// Verifica se a tabela existe
$check = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tabela}'");
if (!$check) {
  echo "<div class='alert alert-danger'>Tabela da campanha não encontrada.</div>";
  exit;
}

// Busca os registros
$res = $db->query("SELECT * FROM {$tabela} ORDER BY id DESC");
$dados = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
  $dados[] = $row;
}

// Status da instância
$estadoInstancia = json_decode($_SESSION['instancia_valida'] ?? '{}', true);
$instanciaConectada = ($estadoInstancia['state'] ?? '') === 'OPEN';
?>

<div class="content-wrapper">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title mb-0">Detalhes da Campanha</h3>
  </div>

  <div class="row mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if ($instanciaConectada): ?>
            <div class="alert alert-success py-2">Número conectado.</div>
          <?php else: ?>
            <div class="alert alert-danger py-2">Número desconectado. Atenção! O envio será realizado quando o número estiver conectado.</div>
          <?php endif; ?>

          <table class="table table-hover table-striped">
            <thead class="text-white">
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Tentativas</th>
                <th>Enviado em</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($dados)): ?>
                <?php foreach ($dados as $d): ?>
                  <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['nome'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($d['telefone']) ?></td>
                    <td><?= ucfirst($d['status'] ?? 'pendente') ?></td>
                    <td><?= $d['tentativa'] ?? 0 ?></td>
                    <td><?= $d['criado_em'] ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6">Nenhum dado encontrado nesta campanha.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-4 d-flex justify-content-between flex-wrap gap-2">
    <a href="dashboard&page=send" class="btn btn-outline-secondary btn-sm">← Voltar</a>

    <div class="d-flex flex-wrap gap-2">
      <form action="functions/excluir_campanha.php" method="POST"
        onsubmit="return confirm('Tem certeza que deseja excluir esta campanha? Esta ação não poderá ser desfeita.')">
        <input type="hidden" name="id" value="<?= $idCampanha ?>">
        <input type="hidden" name="tabela" value="<?= htmlspecialchars($tabela) ?>">
        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir Campanha</button>
      </form>
      <form action="functions/iniciar_envio.php" method="POST" class="d-inline">
        <input type="hidden" name="tabela" value="<?= htmlspecialchars($tabela) ?>">
        <button type="submit" class="btn btn-sm btn-outline-success"><i class="mdi mdi-play"></i> Iniciar Envio</button>
      </form>
    </div>
  </div>
</div>