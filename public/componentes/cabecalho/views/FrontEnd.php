<!-- Carregamento da HEADER -->
<?php 
session_start();
  include_once 'header.php';
  include_once 'sidebar.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Cabeçalhos</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item active">Gerenciar Cabeçalhos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Relação de OSCs Executoras</h5>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-floating" id="boxSlcExecutoras"></div>
                </div>
                <div class="col-md-5 mt-3" id="boxCheckCelebrante">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="chkCelebrante" value="1">
                    <label class="form-check-label" for="chkCelebrante">
                      Cabeçalhos da Celebrante
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card" id="boxCadastraCabecalho" style="display:none;">
            <div class="card-body">
              <h5 class="card-title" style="margin-bottom:-13px;">Novo Cabeçalho</h5>
              <span id='tituloNovoCabecalho'></span>

              <form class="row g-3 mt-3" id="formCabecalho" name="formCabecalho" method="POST" autocomplete="off">
                  <div class="col-md-2">
                    <div class="form-floating" id="boxTipoRepasse"></div>
                  </div>
                  <div class="col-md-10"></div>
                  <div class="row col-md-6">
                    <div class="col-md-4">
                      <div class="form-floating" id="boxSlcTiposRepasse"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtMesReferencia" name="txtMesReferencia">
                        <div class="invalid-feedback">Informe o mês de referência</div>
                        <label for="txtNomeFantasia">Mês/Ano de referência</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtValorRepasse" name="txtValorRepasse" placeholder="Valor do Repasse">
                        <div class="invalid-feedback">Informe o Valor do Repasse</div>
                        <label for="txtNomeFantasia">Valor de Repasse</label>
                      </div>
                    </div>
                  </div>
                <div class="col-md-8"></div>

                <div class="row col-md-6">
                  <h3 class="card-title" style="font-size:14px;margin-top:-10px;margin-bottom:-10px;">Valores Previstos</h3>
                  <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="txtValorRecursosHumanos" name="txtValorRecursosHumanos" placeholder="Valor Recursos Humanos">
                      <div class="invalid-feedback">Informe o Valor Previsto para Recusros Humanos</div>
                      <label for="txtNomeFantasia">Recursos Humanos</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="txtValorCusteio" name="txtValorCusteio" placeholder="Valor Custeio">
                      <div class="invalid-feedback">Informe o Valor Previsto para Custeio</div>
                      <label for="txtNomeFantasia">Custeio</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="txtValorServicosTerceiros" name="txtValorServicosTerceiros" placeholder="Valor Serviços Terceiros">
                      <div class="invalid-feedback">Informe o Valor Previsto para Serviços Terceiros</div>
                      <label for="txtNomeFantasia">Serviços Terceiros</label>
                    </div>
                  </div>
                </div>

                <?php if($_SESSION["pf"]==1 OR $_SESSION["pf"]==2 OR $_SESSION["pf"]==8){ ?>
                <div class="row col-md-6 ms-3 d-none" style="margin-top: -80px;" id="boxSaldoPrestacao">
                  <h3 class="card-title" style="font-size:18px;margin-top:-10px;margin-bottom:-10px;">Cálculo da Rubrica Prevista para o mês de referência</h3>
                  <div class="col-md-3">
                    <div class="text-end"><strong>Recursos Humanos</strong></div>
                    <div class="text-end mt-1 pe-2" id="boxTotalRHPrevisto"></div>
                    <div class="text-end text-danger mt-1" id="boxSaldoRH"></div>
                    <div class="text-end text-danger mt-1 border-bottom" id="boxGlosaRH"></div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxCalcRH"></div>
                    <div class="text-end text-success border-bottom pe-2 mt-1" id="boxPrevistoFixoRH">0,00</div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxTotalRH">0,00</div>
                  </div>
                  <div class="col-md-3">
                    <div class="text-end"><strong>Custeio</strong></div>
                    <div class="text-end mt-1 pe-2" id="boxTotalCusteioPrevisto"></div>
                    <div class="text-end text-danger mt-1" id="boxSaldoCusteio"></div>
                    <div class="text-end text-danger mt-1 border-bottom" id="boxGlosaCusteio"></div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxCalcCusteio"></div>
                    <div class="text-end text-success border-bottom pe-2 mt-1" id="boxPrevistoFixoCusteio">0,00</div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxTotalCusteio">0,00</div>
                  </div>
                  <div class="col-md-3">
                    <div class="text-end"><strong>Serviços Terceiros</strong></div>
                    <div class="text-end mt-1 pe-2" id="boxTotalTerceirosPrevisto"></div>
                    <div class="text-end text-danger mt-1" id="boxSaldoTerceiros"></div>
                    <div class="text-end text-danger border-bottom mt-1"id="boxGlosaTerceiros"></div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxCalcTerceiros"></div>
                    <div class="text-end text-success border-bottom mt-1" id="boxPrevistoFixoTerceiros">0,00</div>
                    <div class="text-end text-primary pe-2 mt-1" id="boxTotalTerceiros">0,00</div>
                  </div>
                  <div class="col-md-3">
                    <div class="text-end"> &nbsp; </div>
                    <div class="text-end mt-1"> &nbsp; </div>
                    <div class="text-end mt-1"> &nbsp; </div>
                    <div class="text-end mt-1"> &nbsp; </div>
                    <div class="text-end mt-1"> &nbsp; </div>
                    <div class="text-end mt-1"> &nbsp; </div>
                    <div class="text-end mt-1" id="boxBotaoCalculo"></div>
                  </div>
  
                </div>
                <?php } ?>

                <div class="text-center mt-5 col-md-11" id="boxBotoes">
                  <button type="submit" class="btn btn-primary" id="btnRegistrarCabecalho">Registrar Cabeçalho</button>
                  <button type="button" class="btn btn-secondary" onclick="cancelaCadCabecalho()">Cancelar</button>
                </div>
              </form>

            </div>
          </div>

          <div class="card" id="boxCabecalhos" style="display:none;">
            <div class="card-body">
              <h5 class="card-title">Cabeçalhos</h5>
              <div class="col-md-2">
                  <button type="button" class="btn btn-primary" onclick="novoCabecalho()">Criar Cabeçalho</button>
              </div>
              <div class="row ms-3 mt-3" id="linhaCabecalhos"></div>
            </div>
          </div>


        </div>
      </div>
    </section>

    <div class="modal fade" id="avisoModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" id="tituloAvisoModal"></div>
          <div class="modal-body" id="corpoAvisoModal"></div>
          <div class="modal-footer" id="boxBotoesAvisoModal"></div>
        </div>
      </div>
    </div>

  </main>
