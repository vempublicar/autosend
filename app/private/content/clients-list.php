<?php

$pastaHash = sha1($_SESSION['email']);
$caminhoBanco = "customers/{$pastaHash}/meubanco.sqlite";
$db = new SQLite3($caminhoBanco);

// Busca últimos 20 contatos
$result = $db->query("SELECT * FROM contatos ORDER BY id DESC LIMIT 20");
$etiquetas = [];
$etc = $db->query("SELECT * FROM etapas_crm ORDER BY id ASC");

while ($row = $etc->fetchArray(SQLITE3_ASSOC)) {
  $etiquetas[] = $row['nome'];
}
?>

<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title"> Meus Clientes </h3>
  </div>
  <div class="row">
    <div class="col-12">
      <!-- Tabela em card com estilo novo -->
      <div class="card">

        <div class="card-body">
          <div class="input-group mb-4">
            <div class="input-group-prepend bg-transparent">
              <i class="input-group-text border-0 mdi mdi-magnify bg-transparent text-warning"></i>
            </div>
            <input type="text" id="busca-contato" class="form-control bg-transparent border-0" placeholder="Buscar por nome, telefone, email ou grupo...">
          </div>
          <div class="row overflow-auto">
            <div class="col-12" style="max-height: 60vh; overflow-y: auto;">
              <table class="table table-striped" cellspacing="0" width="100%" >
                <thead>
                  <tr class="text-white">
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Prioridade</th>
                    <th colspan="3" class="text-center">Ações</th>
                  </tr>
                </thead>
                <tbody id="tabela-contatos">
                  <?php if ($result): ?>
                    <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['telefone']) ?></td>
                        <td>
                          <?php
                          $status = strtolower($row['status']);
                          $badgeStatus = [
                            'ativo' => 'success',
                            'pendente' => 'warning',
                            'inadimplente' => 'danger'
                          ];
                          $corStatus = $badgeStatus[$status] ?? 'secondary';
                          ?>
                          <label class="badge badge-<?= $corStatus ?>">
                            <?= ucfirst($status) ?>
                          </label>
                        </td>
                        <td>
                          <?php
                          $prioridade = strtolower($row['prioridade']);
                          $badgePrioridade = [
                            'crítica' => 'danger',
                            'alta' => 'warning',
                            'média' => 'info',
                            'baixa' => 'secondary'
                          ];
                          $corPrioridade = $badgePrioridade[$prioridade] ?? 'secondary';
                          ?>
                          <label class="text-<?= $corPrioridade ?>">
                            <?= ucfirst($prioridade) ?>
                          </label>
                        </td>
                        <td class="text-center">
                          <?php if (empty($row['etiqueta']) && ($_SESSION['crm'] ?? 'on') !== 'off'): ?>
                            <button type="button"
                              class="btn btn-outline-info btn-xs"
                              onclick="adicionarLead(<?= $row['id'] ?>)"> CRM
                            </button>
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <a href="dashboard&page=adicionar-clientes&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-xs">Editar</a>
                        </td>
                        <td class="text-center">
                          <form action="init/excluir_lead.php" method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir este contato?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="tabela" value="contatos">
                            <button type="submit" class="btn btn-outline-danger btn-xs"> Excluir
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">Nenhum dado encontrado.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>

    // 6. Busca dinâmica + reaplica os botões
    document.getElementById('busca-contato').addEventListener('input', function() {
        const termo = this.value.trim();

        fetch('functions/search-client.php?termo=' + encodeURIComponent(termo))
            .then(res => res.text())
            .then(html => {
                document.querySelector('#tabela-contatos').innerHTML = html;
                aplicarEventosEditar(); // <- Aqui reaplicamos o clique em "Editar"
            });
    });

    function adicionarLead(id) {
        fetch('functions/update-tag.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}&etiqueta=Base`
        }).then(() => location.reload());
    }
</script>