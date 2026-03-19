<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Prestação de Contas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item active">Prestação de Contas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <input type="hidden" name="hidIdOrigem" id="hidIdOrigem" value="<?php echo $url[3] ?>">
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="row">
            <div class="col-md-4 mb-1">
              <button type="submit" class="btn btn-primary" id="btnNovaPrestacao">Criar Nova Pretação de Contas</button>
            </div>
            <div class="card d-none" id="boxOscsExecutoras">
              <div class="card-body">
                <h5 class="card-title">Relação de OSCs Executoras</h5>
                <?php if($_SESSION["pf"]==1 or $_SESSION["pf"]==2 or $_SESSION["pf"]==8){ ?>
                  <div class="row mt-2 mb-1">
                    <div class="col-md-2">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radExecutoras" id="radExecutoras1" value="Todas" onclick="carregaExecutoras(this.value)" checked>
                        <label class="form-check-label" for="radExecutoras1">Todas</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radExecutoras" id="radExecutoras2" value="Disponibilizadas" onclick="carregaExecutoras(this.value)">
                        <label class="form-check-label" for="radExecutoras2">Disponibilizadas (pelas OSCs)</label>
                      </div>
                    </div>
                    <?php if($_SESSION["pf"]==1 or $_SESSION["pf"]==8){ ?>
                    <div class="col-md-3">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radExecutoras" id="radExecutoras4" value="Encerradas" onclick="carregaExecutoras(this.value)">
                        <label class="form-check-label" for="radExecutoras4">Encerradas (pela Celebrante)</label>
                      </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-2">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radExecutoras" id="radExecutoras3" value="Finalizadas" onclick="carregaExecutoras(this.value)">
                        <label class="form-check-label" for="radExecutoras3">Finalizadas</label>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-floating" id="boxSlcExecutoras"></div>
                  </div>
                  <div class="col-md-5 mt-3 d-none" id="boxCheckCelebrante">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="chkCelebrante" value="1">
                      <label class="form-check-label" for="chkCelebrante">Prestação de Contas da Celebrante</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="card d-none mt-1" id="boxNovaPrestacao">
            <div class="card-body">
              <h5 class="card-title" style="margin-bottom:-13px;">Nova Prestação de Contas</h5>
              <span id='tituloNovaPrestacao'></span>

              <form class="row g-3" id="formPrestacao" name="formPrestacao" method="POST" autocomplete="off">
                <div class="col-md-3">
                  <div class="form-floating" id="boxSlcTiposPrestacao"></div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtMesReferencia" name="txtMesReferencia">
                    <div class="invalid-feedback">Informe o mês de referência</div>
                    <label for="txtMesReferencia">Mês/Ano de referência</label>
                  </div>
                </div>

                <div class="text-left mt-3 col-md-12" id="boxBotoes">
                  <button type="submit" class="btn btn-primary">Registrar Nova Prestação de Contas</button>
                  <button type="button" class="btn btn-secondary" onclick="cancelaCadPrestacao()">Cancelar</button>
                </div>
              </form>

            </div>
          </div>

          <div class="card" id="boxPrestacoes" style="display:none;">
            <div class="card-body">
              <h5 class="card-title">Prestações de Contas<span id="txtTituloPrestacao"></span></h5>
              <div class="row ms-3 mt-5" id="boxListaPrestacoes"></div>
            </div>
          </div>

          <?php if(base64_decode($_SESSION['usr'])==1 or base64_decode($_SESSION['usr'])==139 or base64_decode($_SESSION['usr'])==189 or base64_decode($_SESSION['usr'])==217){ ?>
          <div class="card mt-1" id="boxFerramentasAdministrativas">
            <div class="card-body">
              <h5 class="card-title" style="margin-bottom:-13px;">Ferramentas administrativas</h5>


              <div class="col-sm-4 mt-3">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Documentos Complementares</h5>
                    <p class="card-text">Baixar documentos mensais de todas as OSCs</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="txtMesReferenciaDocComplementar" name="txtMesReferenciaDocComplementar" onchange="$('#boxArquivoGerado').addClass('d-none')">
                          <label for="txtMesReferenciaDocComplementar">Mês/Ano de referência</label>
                        </div>
                      </div>
                      <div class="col-md-8"></div>
                      <div class="col-md-6">
                        <button type="button" id="btnDocsComp" class="btn btn-warning my-2 ms-2"><i class="bi bi-file-zip"></i> Gerar Arquivo</button>
                      </div>
                      <div class="col-md-6 d-none text-center" id="boxArquivoGerado">
                        <div class="progress mt-4">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Gerando Arquivo...</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
          <?php } ?>


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



  <!-- Modal -->
  <div class="modal fade p-3" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">TERMO DE CIÊNCIA</h5>
        </div>
        <div class="modal-body">
          
          <h5>BEM VINDOS</h5>

          <p class="text-justify">Caros representantes, o primeiro trimestre de 2023 foi um período de implantação e adaptação ao novo sistema de prestação de contas e visando atender às dificuldades das organizações a Coordenadoria de Políticas sobre Drogas possibilitou a abertura do sistema para mais de uma correção, e com isso as organizações não foram penalizadas.</p>
          <p class="text-justify">Mediante a isto, estou ciente de que:</p>
          <ul style="list-style: upper-roman">
            <li>A partir da prestação de contas de abril de 2023, só será permitido um apontamento por documento.</li>
            <li>A não correção ou correções incorretas acarretarão em glosas no próximo repasse.</li>
            <li>Glosas são descontos, portanto, não acumulam ao saldo não executado.</li>
            <li>Perdas financeiras por má gestão ou incorreta apresentação da prestação de contas é de responsabilidade do financeiro da organização.</li>
            <li>Uma vez finalizada a prestação de contas (BOTÃO FINALIZAR PRESTAÇÃO), não será possível habilitar para inclusão de novos documentos.</li>
          </ul>
          <p class="text-justify">Declaro, por fim, que estou ciente e assumo a responsabilidade mediante ao que foi descrito acima.</p>

        </div>
        <div class="modal-footer" id="boxBotoesTermo"></div>
      </div>
    </div>
  </div>

  </main><!-- End #main -->
