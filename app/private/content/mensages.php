<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-secondary py-3 mb-4 text-center bg-dark d-md-none aside-toggler"><i class="mdi mdi-menu mr-0 icon-md"></i></button>
            <div class="card chat-app-wrapper">
                <div class="row mx-0">
                    <div class="col-lg-3 col-md-4 chat-list-wrapper px-0">
                        <div class="sidebar-spacer">
                            <div class="input-group chat-search-input">
                                <input type="text" class="form-control" placeholder="Search Inbox" aria-label="Username">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-dark">
                                        <i class="mdi mdi-magnify"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="chat-list-item-wrapper" id="lista-contatos-chat">
                            <!-- Contatos serão inseridos aqui via JavaScript -->
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 px-0 chat-col-wrapper">
                        <div class="chat-container-wrapper" id="painel-conversa"></div>

                        <div class="chat-text-field mt-auto">
                            <form id="formResposta">
                                <input type="hidden" name="numero" id="numeroLead">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text"><i class="mdi mdi-emoticon-happy-outline icon-sm"></i></button>
                                    </div>
                                    <input type="text" name="mensagem" id="mensagemTexto" class="form-control" placeholder="Type a message here">
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text"><i class="mdi mdi-paperclip icon-sm"></i></button>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text"><i class="mdi mdi-send icon-sm"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="col-lg-3 d-none d-lg-block px-0 chat-sidebar">
                        <div class="px-4 pt-4">
                            <!-- Seção 1: Configurações -->
                            <h5 class="font-weight-medium">Configurações do Cliente</h5>

                            <!-- Agente IA -->


                            <div class="form-check form-switch my-2">
                                <input class="form-check-input float-end" type="checkbox" id="gptToggle">
                                <label class="form-check-label" for="gptToggle">Agente IA</label>
                            </div>

                            <!-- Grupo A -->
                            <div class="form-group my-2">
                                <label for="grupoASelect" class="form-label">Grupo A</label>
                                <select class="form-select form-control-sm" id="grupoASelect">
                                    <option value="">Selecionar</option>
                                    <option value="Interessado">Interessado</option>
                                    <option value="Cliente">Cliente</option>
                                    <option value="VIP">VIP</option>
                                    <option value="Blacklist">Blacklist</option>
                                </select>
                            </div>

                            <!-- Botão editar -->
                            <a id="btnEditarContato" href="#" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                <i class="mdi mdi-account-edit"></i> Editar Contato
                            </a>

                            <hr>

                            <!-- Seção 2: Agendamento -->
                            <h5 class="font-weight-medium">Agendar Retorno</h5>
                            <div class="form-group my-2">
                                <label for="dataRetorno">Data</label>
                                <input type="date" id="dataRetorno" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label for="textoRetorno">Mensagem</label>
                                <textarea id="textoRetorno" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                            <button id="btnSalvarAgendamento" class="btn btn-sm btn-success w-100 mt-2">Salvar Agendamento</button>
                            <hr>

                            <!-- Seção 3: Arquivos -->
                            <h5 class="font-weight-medium">Arquivos do Contato</h5>

                            <ul class="nav nav-tabs mb-2" id="fileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio" type="button" role="tab">Áudios</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image" type="button" role="tab">Imagens</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file" type="button" role="tab">Arquivos</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="fileTabsContent" style="max-height: 250px; overflow-y: auto;">
                                <div class="tab-pane fade show active" id="audio" role="tabpanel">
                                    <div id="lista-audio">Carregando áudios...</div>
                                </div>
                                <div class="tab-pane fade" id="image" role="tabpanel">
                                    <div id="lista-imagem">Carregando imagens...</div>
                                </div>
                                <div class="tab-pane fade" id="file" role="tabpanel">
                                    <div id="lista-arquivo">Carregando arquivos...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let offset = 0;
        let carregando = false;
        let termoBusca = "";
        let idContatoAtual = null; // ID do contato ativo

        // 👉 Busca dinâmica e scroll infinito para carregar contatos
        function carregarContatosChat(append = false) {
            if (carregando) return;
            carregando = true;

            fetch(`functions/search-client-chat.php?offset=${offset}&termo=${encodeURIComponent(termoBusca)}`)
                .then(res => res.json())
                .then(contatos => {
                    const container = document.getElementById('lista-contatos-chat');
                    if (!append) container.innerHTML = '';

                    contatos.forEach(c => {
                        const div = document.createElement('div');
                        div.className = 'list-item';

                        div.addEventListener('click', () => {
                            offset = 0; // reseta scroll se trocar de contato
                            abrirConversaPainel(c.id, c.nome, c.telefone);
                            carregarArquivosContato();
                            preencherSidebarContato(c);
                        });

                        div.innerHTML = `
                            <div class="profile-image">
                                ${c.notifica > 0 ? '<div class="dot-indicator sm bg-success"></div>' : ''}
                                <img class="img-sm rounded-circle" src="assets/images/faces/face1.jpg" alt="">
                            </div>
                            <p class="user-name">${c.nome}</p>
                            ${c.gpt > 0 ? '<p class="chat-time"><i class="mdi mdi-robot text-secondary"></i></p>' : '<p class="chat-time"></p>'}
                            <p class="chat-text">${c.telefone}</p>
                        `;
                        container.appendChild(div);
                    });

                    if (contatos.length > 0) offset += contatos.length;
                    carregando = false;
                });
        }

        // 👉 Busca dinâmica ao digitar
        const inputBusca = document.querySelector('.chat-search-input input');
        if (inputBusca) {
            inputBusca.addEventListener('input', function() {
                termoBusca = this.value.trim();
                offset = 0;
                carregarContatosChat(false);
            });
        }

        // 👉 Scroll infinito no chat lateral
        document.getElementById('lista-contatos-chat').addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 20) {
                carregarContatosChat(true);
            }
        });

        // 👉 Primeira carga dos contatos
        carregarContatosChat();

        // 👉 Abre conversa no painel central
        function abrirConversaPainel(id, nome, numero) {
            idContatoAtual = id;
            document.getElementById('numeroLead').value = numero;
            document.getElementById('mensagemTexto').value = '';
            carregarMensagensPainel();
        }

        // 👉 Carrega as mensagens do contato selecionado
        function carregarMensagensPainel() {
            if (!idContatoAtual) return;
            const box = document.getElementById('painel-conversa');

            box.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            `;

            fetch(`system/carregar_mensagens.php&id=${idContatoAtual}`)
                .then(res => res.json())
                .then(mensagens => {
                    box.innerHTML = '';
                    if (mensagens.length === 0) {
                        box.innerHTML = '<div class="chat-message text-center text-muted">Nenhuma mensagem ainda.</div>';
                        return;
                    }

                    mensagens.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = `chat-bubble ${msg.tipo === 'enviada' ? 'outgoing-chat' : 'incoming-chat'}`;

                        div.innerHTML = `
                            <div class="chat-message">
                                ${msg.nome ? `<p class="font-weight-bold mb-1">${msg.nome}</p>` : ''}
                                <p>${msg.mensagem}</p>
                            </div>
                            <div class="sender-details">
                                <img class="sender-avatar img-xs rounded-circle" src="../../../assets/images/faces/face${msg.tipo === 'enviada' ? '3' : '2'}.jpg" alt="profile image">
                                <p class="seen-text">${msg.data || ''}</p>
                            </div>
                        `;
                        box.appendChild(div);
                    });

                    box.scrollTop = box.scrollHeight;
                });
        }

        // 👉 Envia nova mensagem
        document.getElementById('formResposta').addEventListener('submit', function(e) {
            e.preventDefault();
            enviarMensagemPainel();
        });

        async function enviarMensagemPainel() {
            const numero = document.getElementById('numeroLead').value;
            const mensagemInput = document.getElementById('mensagemTexto');
            const mensagem = mensagemInput.value.trim();

            if (!mensagem) return;
            mensagemInput.value = '';

            try {
                await fetch('init/enviar_mensagem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `numero=${encodeURIComponent(numero)}&mensagem=${encodeURIComponent(mensagem)}`
                });
                carregarMensagensPainel();
            } catch (err) {
                console.error('Erro ao enviar mensagem:', err);
            }
        }

        // 👉 Salvar configurações e agendamento do contato
        function salvarAgendamento(id) {
            const data = document.getElementById('dataRetorno').value;
            const retorno = document.getElementById('textoRetorno').value;
            const grupoA = document.getElementById('grupoASelect').value;
            const gpt = document.getElementById('gptToggle').checked ? 1 : 0;

            const body = `id=${id}&data=${encodeURIComponent(data)}&retorno=${encodeURIComponent(retorno)}&grupoA=${encodeURIComponent(grupoA)}&gpt=${gpt}`;

            fetch('functions/salvar-configuracoes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body
                })
                .then(res => res.text())
                .then(resp => {
                    console.log('Configurações salvas:', resp);

                    // Toast de sucesso
                    const toast = document.createElement('div');
                    toast.className = 'toast position-fixed bottom-0 end-0 m-3 text-bg-success show';
                    toast.setAttribute('role', 'alert');
                    toast.innerHTML = `<div class="toast-body">Configurações salvas com sucesso!</div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2500);
                    setTimeout(() => carregarContatosChat(false), 300);
                })
                .catch(err => {
                    console.error('Erro ao salvar configurações:', err);
                });
        }

        // 👉 Carrega áudios, imagens e arquivos do contato
        function carregarArquivosContato() {
            ['audio', 'images', 'arq'].forEach(tipo => {
                fetch(`functions/listar-arquivos.php?tipo=${tipo}`)
                    .then(res => res.json())
                    .then(lista => {
                        const id = tipo === 'images' ? 'imagem' : tipo;
                        const container = document.getElementById('lista-' + id);

                        if (!container) return; // evita erro se o elemento não existir

                        container.innerHTML = '';

                        if (lista.length === 0) {
                            container.innerHTML = '<p class="text-muted small">Nenhum arquivo encontrado.</p>';
                            return;
                        }

                        lista.forEach(arquivo => {
                            const item = document.createElement('div');
                            item.className = 'mb-2 small text-truncate';
                            item.textContent = arquivo;
                            item.draggable = true;
                            item.title = "Arraste para o campo de mensagem";

                            item.addEventListener('dragstart', function(e) {
                                e.dataTransfer.setData("text", `[Arquivo: ${arquivo}]`);
                            });

                            container.appendChild(item);
                        });
                    });
            });
        }

        // 👉 Drag and drop para inserir arquivos no campo de mensagem
        document.getElementById('mensagemTexto').addEventListener('drop', function(e) {
            e.preventDefault();
            const texto = e.dataTransfer.getData("text");
            this.value += ` ${texto}`;
        });
        document.getElementById('mensagemTexto').addEventListener('dragover', function(e) {
            e.preventDefault();
        });

        // 👉 Preenche lateral com dados do contato clicado
        function preencherSidebarContato(contato) {
            idContatoAtual = contato.id;
            document.getElementById('gptToggle').checked = contato.gpt == 1;
            document.getElementById('grupoASelect').value = contato.grupoA || "";
            document.getElementById('dataRetorno').value = contato.data || "";
            document.getElementById('textoRetorno').value = contato.retorno || "";
            document.getElementById('btnEditarContato').href = `dashboard&page=clients-form&id=${contato.id}`;
        }

        // 👉 Botão de salvar configurações do cliente
        document.getElementById('btnSalvarAgendamento').addEventListener('click', () => {
            if (idContatoAtual) {
                salvarAgendamento(idContatoAtual);
            }
        });
        // Salva automaticamente ao mudar Agente IA
        document.getElementById('gptToggle').addEventListener('change', () => {
            if (idContatoAtual) {
                salvarAgendamento(idContatoAtual);
            }
        });

        // Salva automaticamente ao mudar Grupo A
        document.getElementById('grupoASelect').addEventListener('change', () => {
            if (idContatoAtual) {
                salvarAgendamento(idContatoAtual);
            }
        });
    });
</script>