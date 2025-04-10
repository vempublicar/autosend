<?php
session_start();
$pastaHash = sha1($_SESSION['email']);
$db = new SQLite3("../customers/{$pastaHash}/meubanco.sqlite");

$termo = $_GET['termo'] ?? '';
$termo = '%' . $termo . '%';

$stmt = $db->prepare("SELECT * FROM contatos 
                      WHERE nome LIKE :termo OR telefone LIKE :termo 
                         OR email LIKE :termo OR grupoA LIKE :termo 
                      ORDER BY id DESC LIMIT 20");
$stmt->bindValue(':termo', $termo);
$result = $stmt->execute();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    ?>
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
        <td>
            <a href="#" class="btn btn-outline-info btn-xs">CRM</a>
        </td>
        <td>
            <button
                type="button"
                class="btn btn-outline-primary btn-xs editar-lead"
                data-id="<?= $row['id'] ?>"
                data-nome="<?= htmlspecialchars($row['nome']) ?>"
                data-telefone="<?= htmlspecialchars($row['telefone']) ?>"
                data-email="<?= htmlspecialchars($row['email']) ?>"
                data-status="<?= $row['status'] ?>"
                data-prioridade="<?= $row['prioridade'] ?>"
                data-etiqueta="<?= $row['etiqueta'] ?>"
                data-retorno="<?= htmlspecialchars($row['retorno']) ?>"
                data-data="<?= $row['data'] ?>"
                data-grupoa="<?= $row['grupoA'] ?>">Editar
            </button>
        </td>
        <td>
            <form action="init/excluir_lead.php" method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir este contato?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-outline-danger btn-xs">Excluir</button>
            </form>
        </td>
    </tr>
    <?php
}
?>
