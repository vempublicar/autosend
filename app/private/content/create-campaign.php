<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Formulário de Campanha</h3>
    </div>

    <div class="card card-custom mb-4 shadow-sm">
        <div class="card-body" id="form-lead">
            <form action="functions/gerar_fila_envio.php" method="POST" id="formCampanha">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome da Campanha</label>
                        <input type="text" name="nome_campanha" class="form-control " placeholder="Campanha Abril 2025" required>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-3">
                        <label class="form-label">Origem dos dados</label>
                        <select name="origem" class="form-select border-secondary" required onchange="atualizarFiltrosDisponiveis()">
                            <option value="contatos">Contatos</option>
                            <option value="leads">Leads</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tamanho da Lista</label>
                        <select name="limite_envios" class="form-select border-secondary" required>
                            <option value="25">25 números</option>
                            <option value="50">50 números</option>
                            <option value="75">75 números</option>
                            <option value="100 " selected>100 números</option>
                        </select>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-4">
                        <label class="form-label">Filtro 1 (obrigatório)</label>
                        <select name="filtro1_coluna" class="form-select border-secondary" onchange="carregarValoresUnicos(1)" required>
                            <option value="">-- Escolha um filtro --</option>
                            <option value="status">Status</option>
                            <option value="etiqueta">Etiqueta</option>
                            <option value="data">Data</option>
                            <option value="prioridade">Prioridade</option>
                            <option value="variavelA">Variável A</option>
                            <option value="variavelB">Variável B</option>
                            <option value="variavelC">Variável C</option>
                            <option value="grupoA">Grupo A</option>
                            <option value="grupoB">Grupo B</option>
                            <option value="grupoC">Grupo C</option>
                        </select>
                        <select name="filtro1_valor" id="valoresFiltro1" class="form-select  border-secondary mt-2" required>
                            <option value="">-- Selecione um valor --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Filtro 2 (opcional)</label>
                        <select name="filtro2_coluna" class="form-select border-secondary" onchange="carregarValoresUnicos(2)">
                            <option value="">-- Nenhum --</option>
                            <option value="status">Status</option>
                            <option value="etiqueta">Etiqueta</option>
                            <option value="data">Data</option>
                            <option value="prioridade">Prioridade</option>
                            <option value="variavelA">Variável A</option>
                            <option value="variavelB">Variável B</option>
                            <option value="variavelC">Variável C</option>
                            <option value="grupoA">Grupo A</option>
                            <option value="grupoB">Grupo B</option>
                            <option value="grupoC">Grupo C</option>
                        </select>
                        <select name="filtro2_valor" id="valoresFiltro2" class="form-select border-secondary mt-2">
                            <option value="">-- Selecione um valor --</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <small id="resultadoFiltro" class="text-muted d-block mt-2"></small>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Mensagem Principal</label>
                        <textarea name="mensagem" class="form-control border-secondary" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch my-2">
                            <input class="form-check-input ms-3 me-3" type="checkbox" name="gpt">
                            <label class="form-check-label ms-3" for="gptToggle">Usar o AgentIA para criar variações desta mensagem.</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch my-2">
                            <input class="form-check-input ms-3 me-3" type="checkbox" name="incluir_ativos" id="incluirAtivos">
                            <label class="form-check-label ms-3" for="incluirAtivos">
                                Permitir envio para números com campanhas ativas
                            </label>
                            <small class="text-muted ms-5 d-block">
                                Campanhas ativas são aquelas enviadas nos últimos 10 dias e ainda sem resposta.
                            </small>
                        </div>
                    </div>
                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-outline-primary ">Gerar Campanha de Envio</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const opcoesContatos = [{
            value: "status",
            text: "Status"
        },
        {
            value: "etiqueta",
            text: "Etiqueta"
        },
        {
            value: "data",
            text: "Data"
        },
        {
            value: "prioridade",
            text: "Prioridade"
        },
        {
            value: "variavelA",
            text: "Variável A"
        },
        {
            value: "variavelB",
            text: "Variável B"
        },
        {
            value: "variavelC",
            text: "Variável C"
        },
        {
            value: "grupoA",
            text: "Grupo A"
        },
        {
            value: "grupoB",
            text: "Grupo B"
        },
        {
            value: "grupoC",
            text: "Grupo C"
        }
    ];

    const opcoesLeads = [{
            value: "status",
            text: "Status"
        },
        {
            value: "etiqueta",
            text: "Etiqueta"
        },
        {
            value: "grupoB",
            text: "Grupo B"
        },
        {
            value: "grupoC",
            text: "Grupo C"
        }
    ];

    function atualizarFiltrosDisponiveis() {
        const origem = document.querySelector('[name="origem"]').value;
        const filtro1 = document.querySelector('[name="filtro1_coluna"]');
        const filtro2 = document.querySelector('[name="filtro2_coluna"]');

        const opcoes = origem === 'leads' ? opcoesLeads : opcoesContatos;

        [filtro1, filtro2].forEach(filtro => {
            const valorAtual = filtro.value;
            filtro.innerHTML = `<option value="">-- ${filtro.name.includes('1') ? 'Escolha um filtro' : 'Nenhum'} --</option>`;
            opcoes.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;
                filtro.appendChild(option);
            });

            // Reaplica valor anterior, se ainda válido
            if (opcoes.some(opt => opt.value === valorAtual)) {
                filtro.value = valorAtual;
            }
        });

        // Limpa os selects de valores
        document.getElementById('valoresFiltro1').innerHTML = `<option value="">-- Selecione um valor --</option>`;
        document.getElementById('valoresFiltro2').innerHTML = `<option value="">-- Selecione um valor --</option>`;
    }

    // Executa ao carregar a página
    document.addEventListener('DOMContentLoaded', atualizarFiltrosDisponiveis);

    function carregarValoresUnicos(filtro) {
        const colunaSelect = document.querySelector(`[name="filtro${filtro}_coluna"]`);
        const valorSelect = document.getElementById(`valoresFiltro${filtro}`);
        const coluna = colunaSelect?.value;
        const origem = document.querySelector('[name="origem"]')?.value || 'contatos';

        if (!coluna) {
            valorSelect.innerHTML = `<option value="">-- Selecione um valor --</option>`;
            return;
        }

        fetch(`functions/valores_unicos.php?coluna=${encodeURIComponent(coluna)}&origem=${origem}`)
            .then(res => res.json())
            .then(valores => {
                valorSelect.innerHTML = `<option value="">-- Selecione um valor --</option>`;
                valores.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v;
                    opt.textContent = v;
                    valorSelect.appendChild(opt);
                });
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const filtro1Valor = document.getElementById('valoresFiltro1');
        const filtro2Valor = document.getElementById('valoresFiltro2');

        filtro1Valor.addEventListener('change', contarRegistros);
        filtro2Valor.addEventListener('change', contarRegistros);
    });

    function contarRegistros() {
        const origem = document.querySelector('[name="origem"]').value;
        const f1_col = document.querySelector('[name="filtro1_coluna"]').value;
        const f1_val = document.querySelector('[name="filtro1_valor"]').value;
        const f2_col = document.querySelector('[name="filtro2_coluna"]').value;
        const f2_val = document.querySelector('[name="filtro2_valor"]').value;

        if (!f1_col || !f1_val) return;

        const url = `functions/contar_registros.php?origem=${origem}&filtro1_coluna=${f1_col}&filtro1_valor=${f1_val}&filtro2_coluna=${f2_col}&filtro2_valor=${f2_val}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                document.getElementById('resultadoFiltro').textContent = `${data.total} número(s) encontrados.`;
            });
    }
</script>