<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Porta de Entrada</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item active">Porta de Entrada</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
              <form action="cadastro-municipio" method="post">
                <p><button type="submit" class="btn btn-primary small"><i class="bi bi-file-earmark-plus me-1"></i> Cadastrar Porta de Entrada</button></p>
              </form>

              <div id="boxListaMunicipios"></div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
