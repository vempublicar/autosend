<?php
$dados = [
    'id' => '',
    'telefone' => '',
    'nome' => '',
    'email' => '',
    'status' => '',
    'etiqueta' => '',
    'mensagem' => '',
    'informacao' => '',
    'retorno' => '',
    'data' => '',
    'prioridade' => '',
    'variavelA' => '',
    'variavelB' => '',
    'variavelC' => '',
    'data_alteracao' => '',
    'data_criacao' => '',
    'ultimo_envio' => '',
    'grupoA' => '',
    'grupoB' => '',
    'grupoC' => '',
    'notifica' => '',
    'arquivo_extra' => '',
    'gpt' => '',
    'foto' => ''
];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pastaHash = sha1($_SESSION['email']);
    $caminhoBanco = "customers/{$pastaHash}/meubanco.sqlite";
    $db = new SQLite3($caminhoBanco);

    $stmt = $db->prepare("SELECT * FROM contatos WHERE id = :id");
    $stmt->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($cliente = $result->fetchArray(SQLITE3_ASSOC)) {
        $dados = $cliente;
    }
}
?>

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"><?= $dados['id'] ? 'Editar Cliente' : 'Formulário de Clientes' ?></h3>
    </div>
    <div class="card card-custom mb-4 shadow-sm">
        <div class="card-body" id="form-lead">
            <form action="functions/cadastro_contatos.php" method="POST" enctype="multipart/form-data">
                <div class="row g-2">
                    <!-- Campo GPT -->
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label">Ativar conversação com o Agente IA para este contato?</label>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gpt" value="true" <?= $dados['gpt'] === 'true' ? 'checked' : '' ?>> Sim
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gpt" value="false" <?= $dados['gpt'] === 'false' ? 'checked' : '' ?>> Não
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- ID -->
                    <div class="col-md-1 mb-2">
                        <label class="form-label">ID</label>
                        <input type="text" name="id" value="<?= $dados['id'] ?>" readonly class="form-control form-control-custom text-secondary">
                    </div>

                    <!-- Nome -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" class="form-control form-control-custom">
                    </div>

                    <!-- Telefone -->
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Telefone *</label>
                        <input type="text" name="telefone" value="<?= htmlspecialchars($dados['telefone']) ?>" class="form-control form-control-custom phone" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" class="form-control form-control-custom">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-control-custom">
                            <option value="ativo" <?= $dados['status'] == 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="pendente" <?= $dados['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="inadimplente" <?= $dados['status'] == 'inadimplente' ? 'selected' : '' ?>>Inadimplente</option>
                        </select>
                    </div>

                    <!-- Prioridade -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Prioridade</label>
                        <select name="prioridade" class="form-select form-control-custom">
                            <option value="crítica" <?= $dados['prioridade'] == 'crítica' ? 'selected' : '' ?>>Crítica</option>
                            <option value="alta" <?= $dados['prioridade'] == 'alta' ? 'selected' : '' ?>>Alta</option>
                            <option value="média" <?= $dados['prioridade'] == 'média' ? 'selected' : '' ?>>Média</option>
                            <option value="baixa" <?= $dados['prioridade'] == 'baixa' ? 'selected' : '' ?>>Baixa</option>
                        </select>
                    </div>

                    <!-- Etiqueta -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Etiqueta</label>
                        <select name="etiqueta" class="form-select form-control-custom">
                            <option value="">Selecione</option>
                            <?php foreach ($etiquetas as $etiqueta): ?>
                                <option value="<?= htmlspecialchars($etiqueta) ?>" <?= $dados['etiqueta'] == $etiqueta ? 'selected' : '' ?>>
                                    <?= ucwords(str_replace('_', ' ', $etiqueta)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Variáveis -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Variável A</label>
                        <input type="text" name="variavelA" value="<?= htmlspecialchars($dados['variavelA']) ?>" class="form-control form-control-custom">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Variável B</label>
                        <input type="text" name="variavelB" value="<?= htmlspecialchars($dados['variavelB']) ?>" class="form-control form-control-custom">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Variável C</label>
                        <input type="text" name="variavelC" value="<?= htmlspecialchars($dados['variavelC']) ?>" class="form-control form-control-custom">
                    </div>

                    <!-- Grupos -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Grupo A</label>
                        <input type="text" name="grupoA" value="<?= htmlspecialchars($dados['grupoA']) ?>" class="form-control form-control-custom">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Grupo B</label>
                        <input type="text" name="grupoB" value="<?= htmlspecialchars($dados['grupoB']) ?>" class="form-control form-control-custom">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Grupo C</label>
                        <input type="text" name="grupoC" value="<?= htmlspecialchars($dados['grupoC']) ?>" class="form-control form-control-custom">
                    </div>

                    <!-- Motivo do Retorno -->
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Motivo do Retorno</label>
                        <textarea name="retorno" class="form-control form-control-custom" rows="2"><?= htmlspecialchars($dados['retorno']) ?></textarea>
                    </div>

                    <!-- Data Retorno -->
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Data Retorno</label>
                        <input type="text" name="data" value="<?= htmlspecialchars($dados['data']) ?>" class="form-control form-control-custom date-mask" placeholder="dd/mm/aaaa">
                    </div>

                    <!-- Arquivo Extra -->
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Arquivo Extra</label>
                        <input type="text" name="arquivo_extra" value="<?= htmlspecialchars($dados['arquivo_extra']) ?>" class="form-control form-control-custom" placeholder="Ex: histórico, documento...">
                    </div>

                    <!-- Foto do Cliente -->
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Foto do Cliente</label>
                        <input type="file" name="foto" class="form-control form-control-custom">
                    </div>

                    <!-- Datas readonly -->
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Data de Criação</label>
                        <input type="text" class="form-control form-control-custom text-secondary" value="<?= $dados['data_criacao'] ?>" readonly>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Última Alteração</label>
                        <input type="text" class="form-control form-control-custom text-secondary" value="<?= $dados['data_alteracao'] ?>" readonly>
                    </div>

                    <!-- Ações -->
                    <div class="col-md-12 d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-custom-outline btn-sm w-25" id="cancelar-edicao">Cancelar</button>
                        <button type="submit" class="btn btn-custom btn-sm w-25"><?= $dados['id'] ? 'Atualizar' : 'Salvar' ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Máscara para telefone
        document.querySelectorAll('.phone').forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                this.value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            });
        });

        // 2. Máscara para data
        document.querySelectorAll('.date-mask').forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '').slice(0, 8);
                this.value = value.replace(/^(\d{2})(\d{2})(\d{0,4})$/, '$1/$2/$3');
            });
        });

        // 3. Toggle do formulário
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('toggle-form')) {
                const formBody = document.getElementById('form-lead');
                formBody.style.display = (formBody.style.display === 'none') ? 'block' : 'none';
            }
        });

        // 4. Botão de cancelar
        document.getElementById('cancelar-edicao').addEventListener('click', function() {
            const form = document.querySelector('form');
            form.reset();
            if (form.querySelector('input[name="id"]')) {
                form.querySelector('input[name="id"]').value = '';
            }
            document.getElementById('form-lead').style.display = 'none';
        });

    });
</script>