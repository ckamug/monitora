<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Usuários</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item active">Usuários</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
              <form action="cadastro-usuario" method="post">
                <p><button type="submit" class="btn btn-primary small"><i class="bi bi-file-earmark-plus me-1"></i> Cadastrar Usuário</button></p>
              </form>

              <div id="boxListaUsuarios"></div>

            </div>
          </div>

        </div>
      </div>
    </section>

    <div class="modal fade" id="confirmacaoModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning bg-gradient" id="tituloModal"></div>
          <div class="modal-body" id="corpoModal"></div>
          <div class="modal-footer" id="boxBotoesModal"></div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->