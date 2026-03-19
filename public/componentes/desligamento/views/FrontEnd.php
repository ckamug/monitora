<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Prontuário Eletrônico - Desligamento</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
        <li class="breadcrumb-item"><a href="/coed/lista-desligamentos">Acolhidos</a></li>
        <li class="breadcrumb-item active">Desligamento</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <input type="hidden" name="hidEntrada" id="hidEntrada" value="<?php echo $url[3] ?>" >
  
  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h2 class="ms-2 mt-3">Desligamento</h2>
            <form class="row g-3" id="formDesligamento" name="formDesligamento" method="POST" enctype="multipart/form-data" autocomplete="off">

              <div class="col-5 mt-3">
                <div class="form-floating" id="boxTiposDesligamentos"></div>
              </div>

              <div class="row d-none" id="boxInfoDesligamentos">
                                                                    
                <div class="row mt-4 d-none" id="boxInfoDesligamentosAdministrativo">

                  <div class="col-md-12 mt-3">
                    <legend class="col-form-label col-sm-12 pt-0">Qual o motivo?</legend>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radMotivoDesligamentoAdm" id="radMotivoDesligamentoAdm1" value="Violências com outras pessoas acolhidas e/ou equipe da OSC">
                      <label class="form-check-label" for="radMotivoDesligamentoAdm1">Violências com outras pessoas acolhidas e/ou equipe da OSC</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radMotivoDesligamentoAdm" id="radMotivoDesligamentoAdm2" value="Reiterados descumprimentos das normas institucionais">
                      <label class="form-check-label" for="radMotivoDesligamentoAdm2">Reiterados descumprimentos das normas institucionais</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radMotivoDesligamentoAdm" id="radMotivoDesligamentoAdm3" value="Falta de comprometimento com o processo terapêutico">
                      <label class="form-check-label" for="radMotivoDesligamentoAdm3">Falta de comprometimento com o processo terapêutico</label>
                    </div>
                  </div>

                </div>

                <div class="row mt-4 d-none" id="boxInfoDesligamentosQualificado">
                  <legend class="col-form-label col-sm-12 pt-0">Quais os motivos?</legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado1" name="chkMotivosDesligamentoQualificado[]" value="Cumprimento do projeto terapêutico proposto para a execução dentro da instituição">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado1">Cumprimento do projeto terapêutico proposto para a execução dentro da instituição</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado2" name="chkMotivosDesligamentoQualificado[]" value="Melhora da qualidade de vida">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado2">Melhora da qualidade de vida</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado3" name="chkMotivosDesligamentoQualificado[]" value="Capacidade de autocuidado, auto-organização e organização de tempo">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado3">Capacidade de autocuidado, auto-organização e organização de tempo</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado4" name="chkMotivosDesligamentoQualificado[]" value="Consciência sobre a sua dependência química">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado4">Consciência sobre a sua dependência química</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado5" name="chkMotivosDesligamentoQualificado[]" value="Desenvolvimento de habilidades de prevenção a recaída">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado5">Desenvolvimento de habilidades de prevenção a recaída</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado6" name="chkMotivosDesligamentoQualificado[]" value="Inserção no mundo do trabalho">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado6">Inserção no mundo do trabalho</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado6" name="chkMotivosDesligamentoQualificado[]" value="Condições de autossustento">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado6">Condições de autossustento</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado6" name="chkMotivosDesligamentoQualificado[]" value="Condições de moradia">
                      <label class="form-check-label" for="chkMotivosDesligamentoQualificado6">Condições de moradia</label>
                    </div>

                  </div>
                  
                </div>

                <div class="row mt-4 d-none" id="boxInfoDesligamentosSolicitado">

                  <div class="col-md-12 mt-3">
                    <legend class="col-form-label col-sm-12 pt-0">Qual o motivo?</legend>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesligamentoSolicitado" id="radDesligamentoSolicitado1" value="Fim da licença para tratamento de saúde - liberada pelo INSS">
                      <label class="form-check-label" for="radDesligamentoSolicitado1">Fim da licença para tratamento de saúde - liberada pelo INSS</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesligamentoSolicitado" id="radDesligamentoSolicitado2" value="Cuidar dos filhos">
                      <label class="form-check-label" for="radDesligamentoSolicitado2">Cuidar dos filhos</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesligamentoSolicitado" id="radDesligamentoSolicitado3" value="Pressão de companheiro(a) e/ou familiares">
                      <label class="form-check-label" for="radDesligamentoSolicitado3">Pressão de companheiro(a) e/ou familiares</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesligamentoSolicitado" id="radDesligamentoSolicitado4" value="Necessidade de trabalhar para sustentar a família">
                      <label class="form-check-label" for="radDesligamentoSolicitado4">Necessidade de trabalhar para sustentar a família</label>
                    </div>
                  </div>

                </div>

                <div class="row mt-4 d-none" id="boxInfoDesligamentosDesistencia">

                  <div class="col-md-12 mt-3">
                    <legend class="col-form-label col-sm-12 pt-0">Qual o motivo?</legend>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesistencia" id="radDesistencia1" value="Não gostou da localização e/ou serviço prestado">
                      <label class="form-check-label" for="radDesistencia1">Não gostou da localização e/ou serviço prestado</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesistencia" id="radDesistencia2" value="Não se comprometeu com o processo terapêutico">
                      <label class="form-check-label" for="radDesistencia2">Não se comprometeu com o processo terapêutico</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesistencia" id="radDesistencia3" value="Vínculos afetivos transitórios e/ou tóxicos">
                      <label class="form-check-label" for="radDesistencia3">Vínculos afetivos transitórios e/ou tóxicos</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radDesistencia" id="radDesistencia4" value="Fim das pendências externas que levaram ao tratamento e/ou serviço terapêutico">
                      <label class="form-check-label" for="radDesistencia4">Fim das pendências externas que levaram ao tratamento e/ou serviço terapêutico</label>
                    </div>
                  </div>

                </div>

                <div class="row mt-4 d-none" id="boxInfoDesligamentosTransferencia">

                  <div class="col-md-12 mt-3" id="boxUnidadeHospitalar">
                    <legend class="col-form-label col-sm-12 pt-0">Qual o motivo?</legend>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radTransferencia" id="radTransferencia1" value="Transferência para serviço de saúde">
                      <label class="form-check-label" for="radTransferencia1">Transferência para serviço de saúde</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radTransferencia" id="radTransferencia2" value="Transferência para outro serviço da rede da Política Estadual sobre Drogas">
                      <label class="form-check-label" for="radTransferencia2">Transferência para outro serviço da rede da Política Estadual sobre Drogas</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radTransferencia" id="radTransferencia3" value="Sistema carcerário">
                      <label class="form-check-label" for="radTransferencia3">Sistema carcerário</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="radTransferencia" id="radTransferencia4" value="Óbito">
                      <label class="form-check-label" for="radTransferencia4">Óbito</label>
                    </div>
                  </div>

                </div>

                <div class="row mt-4">

                  <div class="col-10 mt-2">
                    <div class="form-floating">
                      <textarea class="form-control" placeholder="Sintese" id="txtSintese" name="txtSintese" style="height: 150px;"></textarea>
                      <label for="floatingTextarea">Síntese</label>
                    </div>
                  </div>

                  <hr class="col-10 mt-4"></hr>

                  <legend class="col-form-label col-sm-12 pt-0 mt-4"><strong>IMPACTOS</strong></legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos1" name="chkImpactos[]" value="Elevação de escolaridade e/ou capacitação (EJA ou ENCEJA)">
                      <label class="form-check-label" for="chkImpactos1">Elevação de escolaridade e/ou capacitação (EJA ou ENCEJA)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos2" name="chkImpactos[]" value="Inclusão ou retorno em curso superior">
                      <label class="form-check-label" for="chkImpactos2">Inclusão ou retorno em curso superior</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos3" name="chkImpactos[]" value="Realização de cursos de profissionalização">
                      <label class="form-check-label" for="chkImpactos3">Realização de cursos de profissionalização</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos4" name="chkImpactos[]" value="Fortalecimento de vínculos familiares e/ou construção de novos vínculos">
                      <label class="form-check-label" for="chkImpactos4">Fortalecimento de vínculos familiares e/ou construção de novos vínculos</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos5" name="chkImpactos[]" value="Construção ou fortalecimento da rede de apoio e protetivo">
                      <label class="form-check-label" for="chkImpactos5">Construção ou fortalecimento da rede de apoio e protetivo</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos6" name="chkImpactos[]" value="Saída da situação de rua">
                      <label class="form-check-label" for="chkImpactos6">Saída da situação de rua</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos7" name="chkImpactos[]" value="Participação em grupos de apoio e/ou de mútua ajuda">
                      <label class="form-check-label" for="chkImpactos7">Participação em grupos de apoio e/ou de mútua ajuda</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos8" name="chkImpactos[]" value="Referenciado e participando das atividades do CRAS">
                      <label class="form-check-label" for="chkImpactos8">Referenciado e participando das atividades do CRAS</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos9" name="chkImpactos[]" value="Referenciado e participando das atividades do CAPS">
                      <label class="form-check-label" for="chkImpactos9">Referenciado e participando das atividades do CAPS</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos10" name="chkImpactos[]" value="Referenciado e participando das atividades do Espaço Prevenir">
                      <label class="form-check-label" for="chkImpactos10">Referenciado e participando das atividades do Espaço Prevenir</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos11" name="chkImpactos[]" value="Inserido no mundo do trabalho">
                      <label class="form-check-label" for="chkImpactos11">Inserido no mundo do trabalho</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos12" name="chkImpactos[]" value="Capacidade e autossustento">
                      <label class="form-check-label" for="chkImpactos12">Capacidade e autossustento</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos13" name="chkImpactos[]" value="Bancarização">
                      <label class="form-check-label" for="chkImpactos13">Bancarização</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos14" name="chkImpactos[]" value="Não houve impactos">
                      <label class="form-check-label" for="chkImpactos14">Não houve impactos</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkImpactos15" name="chkImpactos[]" value="Capacidade de Autocuidado e Auto-organização">
                      <label class="form-check-label" for="chkImpactos15">Capacidade de Autocuidado e Auto-organização</label>
                    </div>

                  </div>
                
                </div>

              </div>

              <div class="row d-none" id="boxInfoEncaminhamento">
              
                <hr class="col-10 mt-4"></hr>

                <legend class="col-form-label col-sm-12 pt-0 mt-4"><strong>ENCAMINHAMENTO</strong></legend>
                <div class="col-sm-10">

                  <!-- pergunta 1 - hub -->
                  <div class="mb-3">
                    <label class="form-label">Foi encaminhado pelo HUB?</label>
                    <div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="acolhido_encaminhado_hub" id="desligamento_hub_sim" value="1">
                        <label class="form-check-label" for="desligamento_hub_sim">Sim</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="acolhido_encaminhado_hub" id="desligamento_hub_nao" value="0">
                        <label class="form-check-label" for="desligamento_hub_nao">Não</label>
                      </div>
                    </div>
                  </div>
                  <!-- fim pergunta 1 -->

                  <!-- pergunta 2 que depende da pergunta 1 com logica no index.js -->
                  <div class="row mt-3 d-none" id="boxRetornoSP">
                    <div class="col-md-12">
                      <legend class="col-form-label col-sm-12 pt-0">Como retornou para São Paulo?</legend>

                      <div id="boxRetornoSPOpcoes"></div>
                    </div>
                  </div>
                  <!-- fim pergunta 2 -->

                  <!-- pergunta sobre destino, é dinamica baseado no tipo de desligamento -->
                  <div class="row mt-4" id="boxDestinoResidente">
                    <div class="col-md-12">
                      <legend class="col-form-label col-sm-12 pt-0">Destino do(a) Residente:</legend>
                      <!-- As opções vão ser injetadas aqui via JS -->
                      <div id="boxDestinoResidenteOpcoes"></div>
                    </div>
                  </div>
                  <!-- fim pergunta sobre destino -->
                  
                  <!-- pergunta fixa: encaminhamento realizado no ato do desligamento -->
                  <div class="row mt-4" id="boxEncaminhamentoRealizado">
                    <div class="col-md-6">
                      <legend class="col-form-label col-sm-12 pt-0">Encaminhamento realizado no ato do desligamento:</legend>
                      <div id="boxEncaminhamentoRealizadoOpcoes"></div>
                    </div>
                  </div>
                  <!-- fim pergunta fixa -->
                  
                  <!-- encaminhamento realizado: outros -->
                  <div class="mt-2 d-none" id="boxOutroEquipSaude">
                    <input type="text" class="form-control" id="txtOutroEquipSaude" name="tipo_encaminhamento_realizado_outros_equipamentos" placeholder="Especifique outros equipamentos de saúde">
                  </div>

                  <div class="mt-2 d-none" id="boxOutroDestino">
                    <input type="text" class="form-control" id="txtOutroDestino" name="tipo_encaminhamento_realizado_outro" placeholder="Especifique outros encaminhamentos">
                  </div>
                  <!-- fim de outros -->
                  <div class="col-7 mt-5">
                    <button class="btn btn-primary" type="button" id="btnCadDesligamento" name="btnCadDesligamento" data-bs-toggle="collapse" data-bs-target="#colDesligamento" aria-expanded="false" aria-controls="colDesligamento" onclick="cadastraDesligamento()">
                      <i class="bi bi-check2-circle"></i> Registrar Desligamento
                    </button>
                  </div>

                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

</main><!-- End #main -->
