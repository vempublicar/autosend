<?php
$pastaHash = sha1($_SESSION['email']);
$caminhoBanco = "customers/{$pastaHash}/meubanco.sqlite";
$db = new SQLite3($caminhoBanco);

// Verifica se instância do WhatsApp está conectada
$instanciaConectada = true; // <- altere conforme sua lógica real de verificação

// Busca campanhas
$result = $db->query("SELECT * FROM campanhas ORDER BY criada_em DESC LIMIT 20");
?>

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Minhas Campanhas</h3>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="input-group mb-4">
                        <div class="input-group-prepend bg-transparent">
                            <i class="input-group-text border-0 mdi mdi-magnify bg-transparent text-primary"></i>
                        </div>
                        <input type="text" id="busca-campanhas" class="form-control bg-transparent border-0" placeholder="Buscar por nome ou origem...">
                    </div>

                    <div class="row overflow-auto">
                        <div class="col-12" style="max-height: 60vh; overflow-y: auto;">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-white">
                                        <th>Nome</th>
                                        <th>Qtde</th>
                                        <th>Origem</th>
                                        <th>Data</th>
                                        <th>Info</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tabela-campanhas">
                                    <?php if ($result): ?>
                                        <?php while ($camp = $result->fetchArray(SQLITE3_ASSOC)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($camp['nome']) ?></td>
                                                <td><?= htmlspecialchars($camp['qtde_numeros']) ?></td>
                                                <td><?= ucfirst($camp['origem']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($camp['criada_em'])) ?></td>
                                                <td><?= htmlspecialchars($camp['info']) ?></td>
                                                <td class="d-flex gap-1">                                                    
                                                    <?php
                                                    $qtde = (int) $camp['qtde_numeros'];
                                                    if ($qtde > 1): ?>
                                                    <a href="dashboard&page=view_campaing&tabela=<?= urlencode($camp['tabela_fila']) ?>&camp=<?= $camp['id'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                                                        <form action="functions/iniciar_envio.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="tabela" value="<?= htmlspecialchars($camp['tabela_fila']) ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-success"><i class="mdi mdi-play"></i> Iniciar Envio</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-danger" disabled><i class="mdi mdi-play"></i> Off</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">Nenhuma campanha encontrada.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
<?= $_SESSION['erro_envio'] ?>
                </div>
            </div>
        </div>
    </div>
</div>