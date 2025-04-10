<?php
session_start();
$pastaHash = sha1($_SESSION['email']);
$db = new SQLite3("../customers/{$pastaHash}/meubanco.sqlite");

$termo = $_GET['termo'] ?? '';
$termo = '%' . $termo . '%';

$stmt = $db->prepare("SELECT * FROM leads 
                      WHERE nome LIKE :termo OR telefone LIKE :termo 
                         OR email LIKE :termo 
                      ORDER BY id DESC LIMIT 20");
$stmt->bindValue(':termo', $termo);
$result = $stmt->execute();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
?>
  <tr>
    <td><?= htmlspecialchars($row['nome']) ?></td>
    <td><?= htmlspecialchars($row['telefone']) ?></td>
    <td><?= htmlspecialchars($row['grupoB']) ?></td>
    <td>
      <?php if (empty($row['etiqueta']) && ($_SESSION['crm'] ?? 'on') !== 'off'): ?>
        <button type="button"
          class="btn btn-outline-primary btn-sm"
          onclick="converterParaCliente(<?= $row['id'] ?>)">
          Contato
        </button>
      <?php endif; ?>
    </td>
    <td>
      <form action="init/excluir_lead.php" method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir este lead?');">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <input type="hidden" name="tabela" value="leads">
        <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
      </form>
    </td>
  </tr>
<?php
}
?>