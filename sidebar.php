<?php
    include_once "public/componentes/area-restrita/model/sidebar-actions.php";
?>

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item d-none" id="titDashboard">
            <a class="nav-link <?php echo $nav_group_collapse_dashboard ?>" href="<?php echo URL ?>area-restrita">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item d-none" id="titEncaminhamentosOscs">
            <a class="nav-link <?php echo $nav_group_collapse_encaminhamentos_oscs ?>" href="<?php echo URL ?>encaminhamentos-oscs">
            <i class="bi bi-list-check"></i>
            <span>Encaminhamentos &agrave;s OSCs</span>
            </a>
        </li>

        <li class="nav-item d-none" id="titCadastros">
            <a class="nav-link <?php echo $nav_group_collapse_cadastros ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Cadastros</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse <?php echo $nav_group_cadastros ?>" data-bs-parent="#sidebar-nav">
                <li class="d-none" id="mnuCadAcolhidos">
                    <a href="<?php echo URL ?>acolhidos" class="<?php echo $nav_item_acolhido ?>">
                    <i class="bi bi-circle"></i><span>Acolhidos</span>
                    </a>
                </li>
                <li class="d-none" id="mnuCadDesligamentos">
                    <a href="<?php echo URL ?>lista-desligamentos" class="<?php echo $nav_item_desligamentos ?>">
                    <i class="bi bi-circle"></i><span>Desligamentos</span>
                    </a>
                </li>
                <li class="d-none" id="mnuCadCelebrante">
                    <a href="<?php echo URL ?>celebrante" class="<?php echo $nav_item_celebrante ?>">
                    <i class="bi bi-circle"></i><span>Celebrante</span>
                    </a>
                </li>
                <li class="d-none" id="mnuCadMunicipio">
                    <a href="<?php echo URL ?>municipio" class="<?php echo $nav_item_municipio ?>">
                    <i class="bi bi-circle"></i><span>Porta de Entrada</span>
                    </a>
                </li>
                <li class="d-none" id="mnuCadExecutora">
                    <a href="<?php echo URL ?>executora" class="<?php echo $nav_item_executora ?>">
                    <i class="bi bi-circle"></i><span>OSC Executora</span>
                    </a>
                </li>
                <li class="d-none" id="mnuCadUsuario">
                    <a href="<?php echo URL ?>usuarios" class="<?php echo $nav_item_usuarios ?>">
                    <i class="bi bi-circle"></i><span>Usuários</span>
                    </a>
                </li>
                
            </ul>
        </li>

        <li class="nav-item d-none" id="titProntuarios">
            <a class="nav-link <?php echo $nav_group_collapse_prontuarios ?>" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Prontuários</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse <?php echo $nav_group_prontuarios ?> " data-bs-parent="#sidebar-nav">
            <li class="d-none" id="mnuProntAcolhidos">
                <a href="<?php echo URL ?>acolhidos" class="<?php echo $nav_item_acolhidos ?>">
                <i class="bi bi-circle"></i><span>Acolhidos</span>
                </a>
            </li>
            <li class="d-none" id="mnuProntProntuarios">
                <a href="<?php echo URL ?>prontuarios" class="<?php echo $nav_item_prontuarios ?>">
                <i class="bi bi-circle"></i><span>Prontuários</span>
                </a>
            </li>
            </ul>
        </li>

        <li class="nav-item d-none" id="titVagas">
            <a class="nav-link <?php echo $nav_group_collapse_vagas ?>" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Administrar Vagas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse <?php echo $nav_group_vagas ?> " data-bs-parent="#sidebar-nav">
            <li class="d-none" id="mnuAdmVagas">
                <a href="<?php echo URL ?>cadastrar-vagas" class="<?php echo $nav_item_cadastrar_vagas ?>">
                <i class="bi bi-circle"></i><span>Vagas</span>
                </a>
            </li>
            <li class="d-none" id="mnuAdmVagas">
                <a href="<?php echo URL ?>liberar-vagas" class="<?php echo $nav_item_liberar_vagas ?>">
                <i class="bi bi-circle"></i><span>Distribuir vagas</span>
                </a>
            </li>
            </ul>
        </li>

        <li class="nav-item d-none" id="titPrestacao">
            <a class="nav-link <?php echo $nav_group_collapse_prestacao ?>" data-bs-target="#prestacao-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-currency-dollar"></i><span>Prestação de Contas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="prestacao-nav" class="nav-content collapse <?php echo $nav_group_prestacoes ?> " data-bs-parent="#sidebar-nav">
                <li class="d-none" id="mnuPrestPrestacoes">
                    <a href="<?php echo URL ?>prestacoes" class="<?php echo $nav_item_prestacoes ?>">
                    <i class="bi bi-circle"></i><span>Gerenciar Prestações</span>
                    </a>
                </li>
                <li class="d-none" id="mnuPrestCabecalhos">
                    <a href="<?php echo URL ?>cabecalho" class="<?php echo $nav_item_cabecalho ?>">
                    <i class="bi bi-circle"></i><span>Gerenciar Cabeçalhos</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item d-none" id="titRelatorios">
            <a class="nav-link <?php echo $nav_group_collapse_relatorios ?>" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bar-chart"></i><span>Relatórios</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse <?php echo $nav_group_relatorios ?> " data-bs-parent="#sidebar-nav">
            <li class="d-none" id="mnuRelMensal">
                <a href="<?php echo URL ?>relatorios" class="<?php echo $nav_item_relatorios ?>">
                <i class="bi bi-circle"></i><span>Relatório mensal</span>
                </a>
            </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $nav_group_collapse_sair ?>" href="<?php echo URL ?>login">
            <i class="bi bi-dash-circle"></i>
            <span>Sair</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->
