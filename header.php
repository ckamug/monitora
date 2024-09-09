<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="area-restrita" class="logo d-flex align-items-center">
    <img src="<?php echo URL ?>images/logo_politica_sobre_drogas.png" alt="">
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li><!-- End Search Icon-->

    <li class="nav-item dropdown" style="display:none;">

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number">3</span>
      </a><!-- End Notification Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
          Você tem 3 novas notificações
          <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li class="notification-item">
        <i class="bi bi-check-circle text-success"></i>
          <div>
            <h4>Prestação de Contas</h4>
            <p>A NF 13.045 foi aprovada</p>
          </div>
        </li>

        <li>
          <hr class="dropdown-divider">
        </li>

        <li class="notification-item">
          <i class="bi bi-x-circle text-danger"></i>
          <div>
            <h4>Prestação de Contas</h4>
            <p>A NF 12.001 foi reprovada</p>
          </div>
        </li>

        <li>
          <hr class="dropdown-divider">
        </li>

        <li class="notification-item">
          <i class="bi bi-check-circle text-success"></i>
          <div>
            <h4>Vagas</h4>
            <p>Liberação de vaga</p>
          </div>
        </li>

        <li>
          <hr class="dropdown-divider">
        </li>


      </ul><!-- End Notification Dropdown Items -->

    </li><!-- End Notification Nav -->

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?php echo URL ?>assets/img/profile-img.jpg" alt="Profile" class="rounded-circle" style="display:none;">
        <span class="d-none d-md-block dropdown-toggle ps-2 vwNome"></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6 class="vwNome"></h6>
          <span class="vwPerfil"></span><br>
          <span class="vwLocal"></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo URL ?>login">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sair</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->