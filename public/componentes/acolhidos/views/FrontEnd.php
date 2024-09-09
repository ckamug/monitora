<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Acolhidos</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item active">Acolhidos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
              <?php if($_SESSION["pf"]==3){ ?>
                <form action="cadastro-acolhido" method="post">
                  <p><button type="submit" class="btn btn-primary small"><i class="bi bi-file-earmark-plus me-1"></i> Cadastrar Acolhido</button></p>
                </form>
              <?php } ?>

              <div id="boxListaAcolhidos"></div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->