<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Fazer Upload de Planilha</h3>
        <a href="modelo_leads.xlsx" class="btn btn-outline-secondary btn-sm" download>Baixar Modelo de Planilha</a>
    </div>
    <div class="card mb-3">
        <div class="card-body">


            <?php if (!empty($_SESSION['mensagem'])): ?>
                <div class="alert alert-warning text-dark"><?= $_SESSION['mensagem'] ?></div>
                <?php unset($_SESSION['mensagem']); ?>
            <?php else: ?>
            <div class="alert alert-success text-dark">
                Para evitar erros, abra sua planilha no Google Sheets, ajuste as colunas conforme o modelo e faça o download no formato <strong>.TSV </strong>.
            </div>
            <?php endif; ?>
            <form action="functions/action-up-plan.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tabela" value="contatos">
                <div class="row align-items-end g-3">
                    <div class="col-md-9">
                        <label for="arquivo" class="form-label">Selecione o arquivo TSV</label>
                        <input type="file" name="arquivo" id="arquivo" class="form-control bg-transparent text-warning" accept=".tsv" required>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-primary w-100">Incorporar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Importação</h4>
                    <div id="graficoImportacao"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Situação</h4>
                    <div id="graficoSituacao"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Deixar a legenda dos gráficos com texto branco */
    .c3-legend-item text {
        fill: #fff !important;
    }
</style>
<?php
$situacoes = $_SESSION['situacoes'] ?? [];
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inseridos = <?= $_SESSION['importados'] ?? 0 ?>;
        const atualizados = <?= $_SESSION['atualizados'] ?? 0 ?>;

        // Situações dinâmicas vinda do PHP
        const situacoes = <?= json_encode($situacoes) ?>;

        const coresGrafico = ['#6153F9', '#8E97FC', '#A7B3FD', '#F99D53', '#F97070', '#33D9B2'];

        const chartImportacao = c3.generate({
            bindto: '#graficoImportacao',
            data: {
                columns: [
                    ['Inseridos', inseridos],
                    ['Atualizados', atualizados],
                ],
                type: 'donut',
            },
            color: {
                pattern: coresGrafico
            },
            padding: {
                bottom: 30
            },
            legend: {
                position: 'bottom'
            }
        });

        // Monta as colunas dinamicamente a partir do objeto situacoes
        const situacaoData = Object.entries(situacoes).map(([key, value]) => [key, value]);

        const chartSituacao = c3.generate({
            bindto: '#graficoSituacao',
            data: {
                columns: situacaoData,
                type: 'donut',
            },
            color: {
                pattern: coresGrafico
            },
            padding: {
                bottom: 30
            },
            legend: {
                position: 'bottom'
            }
        });
    });
</script>