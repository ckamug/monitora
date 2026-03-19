<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
?>	

<main id="main" class="main">
  <input type="hidden" id="hidPerfilLogado" value="<?php echo $_SESSION["pf"] ?>">

  <div class="pagetitle">
    <h1>Dashboard | <span class="fs-6" id="boxVinculoAtivo"></span></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <?php if($_SESSION["pf"]==1){ ?>
        <div class="row">
          
          <div class="col-md-3">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Vagas Ofertadas</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-door-open"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalVagas"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="col-md-3">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Vagas Ocupadas</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-door-closed"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalVagasOcupadas"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <?php if($_SESSION["pf"]!=4){ ?>
          <div class="col-md-3">
            <div class="card info-card revenue-card">

              <div class="card-body">
                <h5 class="card-title">Acolhidos</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalAcolhidos"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          
          <div class="col-md-3">

            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">OSCs Cadastradas</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-house"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalOscs"></h6>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <div class="col-md-3">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Portas de Entrada (Municipios)</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-house-up"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalPortasDeEntrada"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <?php } ?>
         
          <div class="col-md-3">
            <div class="card info-card prestacoes-disponiveis-card" style="cursor: pointer;" onclick="abrePrestacoes()">

              <div class="card-body">
                <h5 class="card-title">Prestações de Contas Disponíveis</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-journal"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalPrestacoesDisponibilizadas"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card info-card prestacoes-finalizadas-card" style="cursor: pointer;" onclick="abrePrestacoes()">

              <div class="card-body">
                <h5 class="card-title">Prestações de Contas Finalizadas</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-journal-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="totalPrestacoesFinalizadas"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <?php } ?>

        <div class="row" id="boxTabelaSolicitacoesVagas">
          <div class="col-12">
            <div class="card top-selling overflow-auto">

              <div class="card-body pb-0">
                <h5 class="card-title">Solicitações de vagas</h5>
                <div id="boxSolicitacoesVagas"></div>
              </div>

            </div>
          </div>

        </div>

        <div class="row">
          <?php if($_SESSION["qtdvinculos"]>1){ ?>
          <div class="col-md-12">
            
            <div class="accordion" id="boxLocaisTrabalhos">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-geo-alt"></i></div>
                    <h5 class="card-title">Vínculos do usuário<span> | Clique e acesse seus outros locais de trabalho</span></h5>
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#boxLocaisTrabalhos">
                  <div class="accordion-body">
                    <div class="row align-items-center" id="boxVinculos"></div>
                  </div>
                </div>
              </div>
            </div>
          
          </div>
          <?php } ?>
        </div>

      </div><!-- End Left side columns -->

      <?php if($_SESSION["pf"]==3){ ?>

        <div class="accordion mt-3" id="boxOscs">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-house-heart"></i></div>
                    <h5 class="card-title">Relação de OSCs<span> | Organizações que atendem ao seu município</span></h5>
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#boxOscs">
                  <div class="accordion-body">
                    <div class="col-md-10 mx-auto" id="boxRelacaoOscs"></div>
                  </div>
                </div>
              </div>
            </div>

      <?php } ?>
      


    </div>
  </section>

  <div class="modal fade" id="confirmacaoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-danger" id="tituloModal">ATENÇÃO</div>
        <div class="modal-body" id="corpoModal"></div>
        <div class="modal-footer" id="boxBotoesModal"></div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="encaminhamentoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-send"></i> Encaminhar para OSC</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" id="hidSolicitacaoEncaminhamento">
          <div class="form-floating" id="boxOscsEncaminhamento"></div>
          <div class="form-floating mt-3 ms-1" id="boxDetalhesOscEncaminhamento"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnConfirmaEncaminhamento">Encaminhar Solicitacao</button>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL JUSTIFICATIVA -->
  <div class="modal fade" id="justificativaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="exampleModalLabel"><i class="fa-solid fa-circle-exclamation" style="color: #e20808;"></i> Justificativa</h5>
        </div>
        <div class="modal-body">

            <div class="mb-3">
              <div class="form-floating">
                <textarea class="form-control" style="height: 150px;" id="txtJustificativa" name="txtJustificativa" placeholder="Justificativa da vaga negada"></textarea>
                <label for="txtNomeFantasia">Justifique a vaga negada</label>
              </div>
            </div>

        </div>
        <div class="modal-footer" id="boxBotoesModalJustificativa"></div>
      </div>
    </div>
  </div>
  <!-- FIM MODAL JUSTIFICATIVA -->

</main><!-- End #main -->
