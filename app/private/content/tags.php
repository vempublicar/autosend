<?php
include 'functions/listar-etapas.php';
include 'functions/geral.php';
// Garante que existe uma etapa base
$etapaBase = $db->querySingle("SELECT nome FROM etapas_crm ORDER BY id ASC LIMIT 1");
if (!$etapaBase) {
    $stmt = $db->prepare("INSERT INTO etapas_crm (nome) VALUES (?)");
    $stmt->bindValue(1, 'Base');
    $stmt->execute();
    header("Location: painel&loc=crm");
    exit;
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <div class="input-group mb-4">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify bg-transparent text-warning"></i>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0" id="busca-lead" placeholder="Adicione contatos por nome, telefone ou e-mail...">
                    <div id="resultados-leads" class="list-group position-absolute mt-5 z-3" style="width: 300px;"></div>
                </div>
            </div>

            <div class="board-wrapper tapas-scroll ms-4" style="position: relative; width: 80vw; left: calc(-1 * var(--bs-gutter-x)); overflow-x: auto; padding-bottom: 1rem;">
                <?php foreach ($etapas as $etapa): ?>
                    <div class="card board-portlet p-0" style="border: 2px solid <?= htmlspecialchars($etapa['cor'] ?? '#6c757d') ?>; border-radius: 10px; color: #fff;">
                        <div class="card-header d-flex justify-content-between align-items-center"
                            style="background-color: <?= htmlspecialchars($etapa['cor'] ?? '#6c757d') ?>; color: #fff;">
                            <span class="fw-bold"><?= htmlspecialchars($etapa['nome']) ?></span>
                            <?php if (strtolower($etapa['nome']) === 'base'): ?>
                                <span class="badge bg-secondary">Fixo</span>
                            <?php else: ?>
                                <button class="btn btn-sm btn-excluir-etapa text-white"
                                    data-id="<?= $etapa['id'] ?>" title="Excluir etapa"
                                    style="background: transparent; border: none;">🗑️</button>
                            <?php endif; ?>
                        </div>
                        <div class="m-2">
                            <p class="task-number">4 Contatos</p>
                            <input type="text" class="form-control bg-transparent border-1 mb-2" id="busca" placeholder="Adicione contatos por nome, telefone ou e-mail...">

                            <ul id="etapa-<?= $etapa['id'] ?>" class="portlet-card-list" data-etapa="<?= htmlspecialchars($etapa['nome']) ?>" style="max-height: 60vh; overflow-y: auto; padding-right: 10px;">
                                <?php foreach ($contatosPorEtapa[$etapa['nome']] ?? [] as $contato): ?>
                                    <li class="portlet-card p-2" data-id="<?= $contato['id'] ?>">
                                        <div class="content-justify-end">
                                            <span class="float-end ms-3">
                                                <i class="mdi mdi-robot text-secondary "></i>
                                            </span>
                                            <?php if (!empty($contato['notifica']) && $contato['notifica'] > 0): ?>
                                                <span class="text-success float-end">
                                                <?= $contato['notifica'] ?> <i class="mdi mdi-message-text me-1"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="mt-0 mb-1"><?= htmlspecialchars($contato['nome']) ?></h4>
                                        <p class="text-secondary mb-1"><?= htmlspecialchars($contato['telefone']) ?></p>
                                        <div class="d-flex flex-column flex-md-row align-items-center">
                                            <div class="col-6">
                                                <span class=""><?= badgeStatusCRM($contato) ?></span>
                                            </div>
                                            <div class="float-end mt-4 col-6">
                                                <a class="btn btn-outline-success btn-xs float-end " href="dashboard&page=mensages&id=<?= $contato['id'] ?>">Chat</a>
                                            </div>
                                        </div>

                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                <?php endforeach; ?>
                <div class="me-3" style="min-width: 300px;">
                    <div class="card bg-dark text-white mb-3">
                        <div class="card-header text-center fw-bold">
                            <i class="bi bi-plus-circle me-1"></i>Nova Etapa
                        </div>
                        <div class="card-body p-3">
                            <form action="functions/adicionar_etapa.php" method="POST">
                                <p>Cor e Nome da Etiqueta.</p>
                                <div class="mb-2 d-flex text-white">
                                    <input type="color" name="cor" class="form-control form-control-color bg-dark form-control-sm mb-2" value="#6c757d">
                                    <input type="text" name="nome" class="form-control form-control-sm mb-2 bg-dark text-white" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-light w-100">Adicionar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript Revisado -->
<script>
    // OBS.: Removemos a função abrirConversa() e as referências ao modal,
    // pois a ação "Mensagem" será realizada via redirecionamento pelo link.

    // Atualiza a etiqueta ao mover um contato para outra etapa (drag & drop).
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona cada lista de contatos (que possuem id iniciando com "etapa-").
        document.querySelectorAll('[id^="etapa-"]').forEach(coluna => {
            new Sortable(coluna, {
                group: 'etapas',
                animation: 150,
                onAdd: function(evt) {
                    const contatoId = evt.item.dataset.id;
                    const novaEtapa = evt.to.dataset.etapa; // Obtém o nome da nova etapa via atributo data-etapa.
                    fetch('functions/update_etiqueta.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id=${contatoId}&etiqueta=${encodeURIComponent(novaEtapa)}`
                        })
                        .catch(err => console.error("Erro ao atualizar a etiqueta:", err));
                }
            });
        });

        // Busca de leads para inclusão no CRM.
        document.getElementById('busca-lead').addEventListener('input', function() {
            const termo = this.value.trim();
            const container = document.getElementById('resultados-leads');
            if (termo.length < 3) {
                container.innerHTML = '';
                return;
            }
            fetch('functions/search-client-crm.php?termo=' + encodeURIComponent(termo))
                .then(res => res.json())
                .then(leads => {
                    container.innerHTML = '';
                    leads.forEach(lead => {
                        const item = document.createElement('a');
                        item.style.zIndex = '10000';
                        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        item.innerHTML = `
                        <span><strong>${lead.nome}</strong> - ${lead.telefone}</span>
                        <button class="btn btn-sm btn-outline-primary" onclick="adicionarLead(${lead.id})">Adicionar</button>`;
                        container.appendChild(item);
                    });

                });
        });

        // Se houver o formulário de resposta, associa o evento de submit.
        document.getElementById('formResposta')?.addEventListener('submit', function(e) {
            e.preventDefault();
            enviarMensagem();
        });
    });
    // Filtragem dinâmica por nome, telefone ou email dentro de cada etapa
    document.querySelectorAll('input[id="busca"]').forEach(input => {
        input.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const lista = this.nextElementSibling; // ul logo após o input
            lista.querySelectorAll('.portlet-card').forEach(card => {
                const nome = card.querySelector('.task-title')?.textContent.toLowerCase() || '';
                const telefone = card.innerHTML.toLowerCase(); // se quiser buscar por telefone/email também
                if (nome.includes(termo) || telefone.includes(termo)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });


    // Função para adicionar um lead à etapa "Base".
    function adicionarLead(id) {
        fetch('functions/update_etiqueta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}&etiqueta=Base`
            }).then(() => location.reload())
            .catch(err => console.error("Erro ao adicionar lead:", err));
    }
</script>