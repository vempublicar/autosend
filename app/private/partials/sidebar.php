<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Início -->
    <li class="nav-item">
      <a class="nav-link" href="dashboard&page=inicio">
        <span class="menu-title">Início</span>
        <i class="mdi mdi-view-dashboard menu-icon"></i>
      </a>
    </li>

    <!-- Monitor -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#monitor" aria-expanded="false" aria-controls="monitor">
        <span class="menu-title">Monitor</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-chart-line menu-icon"></i>
      </a>
      <div class="collapse" id="monitor">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=indicadores">Indicadores</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=envios">Envios</a></li>
        </ul>
      </div>
    </li>

    <!-- CRM -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#crm" aria-expanded="false" aria-controls="crm">
        <span class="menu-title">CRM</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse" id="crm">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=tags">Etiquetas</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=mensages">Mensagens</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=agenda">Agenda</a></li>
        </ul>
      </div>
    </li>

    <!-- AutoSend -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#autosend" aria-expanded="false" aria-controls="autosend">
        <span class="menu-title">AutoSend</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-send menu-icon"></i>
      </a>
      <div class="collapse" id="autosend">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=create-campaign">Criar Campanha</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=send">Programar Envio</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=sending">Enviados</a></li>
        </ul>
      </div>
    </li>

    <!-- Clientes -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#clientes" aria-expanded="false" aria-controls="clientes">
        <span class="menu-title">Clientes</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-box menu-icon"></i>
      </a>
      <div class="collapse" id="clientes">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=clients-form">Formulario</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=clients-up">Carregar CSV</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=clients-list">Meus Clientes</a></li>
        </ul>
      </div>
    </li>

    <!-- Leads -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#leads" aria-expanded="false" aria-controls="leads">
        <span class="menu-title">Leads</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-plus menu-icon"></i>
      </a>
      <div class="collapse" id="leads">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=leads-up">Carregar CSV</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=leads-list">Lista de Leads</a></li>
        </ul>
      </div>
    </li>

    <!-- Configuração -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#configuracao" aria-expanded="false" aria-controls="configuracao">
        <span class="menu-title">Configuração</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-settings menu-icon"></i>
      </a>
      <div class="collapse" id="configuracao">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="dashboard&page=conectar-numero">Conectar Número</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=sobre-empresa">Sobre a Empresa</a></li>
          <li class="nav-item"><a class="nav-link" href="dashboard&page=ajustes-agent">Ajustes do Agent IA</a></li>
        </ul>
      </div>
    </li>

  </ul>
</nav>
