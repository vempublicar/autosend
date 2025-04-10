<?php
function badgeStatusCRM($contato): string {
    if (!is_array($contato) || empty($contato)) {
        return 'Dados inválidos!';
    }

    $html = '<div class="mt-2 d-flex flex-wrap gap-1">';

    // STATUS
    $status = strtolower($contato['status'] ?? '');
    $iconeStatus = 'mdi mdi-alert-circle-outline';
    $corStatus = 'secondary';

    switch ($status) {
        case 'lead':
            $iconeStatus = 'mdi mdi-account-plus';
            $corStatus = 'primary';
            break;
        case 'pago':
            $iconeStatus = 'mdi mdi-check-circle';
            $corStatus = 'success';
            break;
        case 'vencido':
            $iconeStatus = 'mdi mdi-close-circle';
            $corStatus = 'danger';
            break;
    }

    $html .= "<div class='badge badge-outline-{$corStatus} badge-pill'><i class='{$iconeStatus} me-1' data-bs-toggle='tooltip' title='Status: " . ucfirst($status) . "'></i>" . ucfirst($status) . "</div>";

    // PRIORIDADE
    if (!empty($contato['prioridade'])) {
        $prioridade = strtolower($contato['prioridade']);
        $iconePrioridade = 'mdi mdi-flag-outline';
        $corPrioridade = 'secondary';

        switch ($prioridade) {
            case 'crítica':
                $iconePrioridade = 'mdi mdi-alert';
                $corPrioridade = 'danger';
                break;
            case 'alta':
                $iconePrioridade = 'mdi mdi-fire';
                $corPrioridade = 'warning';
                break;
            case 'média':
                $iconePrioridade = 'mdi mdi-timer-sand';
                $corPrioridade = 'primary';
                break;
            case 'baixa':
                $iconePrioridade = 'mdi mdi-check-circle-outline';
                $corPrioridade = 'secondary';
                break;
        }

        $html .= "<div class='badge badge-outline-{$corPrioridade} badge-pill'><i class='{$iconePrioridade}' data-bs-toggle='tooltip' title='Prioridade: " . ucfirst($prioridade) . "'></i></div>";
    }

    // VARIÁVEL A
    if (!empty($contato['variavelA'])) {
        $html .= "<div class='badge badge-outline-dark badge-pill'><i class='mdi mdi-tag' data-bs-toggle='tooltip' title='Etiqueta: " . htmlspecialchars($contato['variavelA']) . "'></i></div>";
    }

    // GRUPO A
    if (!empty($contato['grupoA'])) {
        $html .= "<div class='badge badge-outline-dark badge-pill'><i class='mdi mdi-folder' data-bs-toggle='tooltip' title='Grupo: " . htmlspecialchars($contato['grupoA']) . "'></i></div>";
    }

    $html .= '</div>';
    return $html;
}
?>
