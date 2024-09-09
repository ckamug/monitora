<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Prontuário Eletrônico</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item"><a href="/coed/acolhidos">Acolhidos</a></li>
          <li class="breadcrumb-item active">Prontuário Eletrônico</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <input type="hidden" name="hidIdOrigem" id="hidIdOrigem" value="<?php echo $url[3] ?>" >
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title" id="txtAcolhido"></h5>
              <div class="row ms-3 mt-5" id="boxListaEntradas"></div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </main><!-- End #main -->