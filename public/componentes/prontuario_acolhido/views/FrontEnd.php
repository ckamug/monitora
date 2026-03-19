<!-- Carregamento da HEADER -->
<?php 
  include_once "../../../../configuracoes.php";
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
          <li class="breadcrumb-item active"><a href="/coed/acolhidos">Prontuário Eletrônico</a></li>
          <li class="breadcrumb-item active">Acolhimento</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <input type="hidden" name="hidEntrada" id="hidEntrada" value="<?php echo $url[3] ?>" >
    
    <section class="section">
      <div class="row">
        
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body pt-4">  
              <button type="button" class="btn btn-success btn-lg btn-ativo" id="btnAcolhimento">ACOLHIMENTO</button>
              <button type="button" class="btn btn-light btn-lg" id="btnAnamnese">ANAMNESE</button>
            </div>
          </div>

          
<!-- INICIO DO CARD ACOLHIMENTO -->
          <div class="card" id="cardAcolhimento">
            
            <div class="card-body">
              
              <div class="row mt-3" id="boxListaAcolhimentos">

                <h2 class="ms-2">Acolhimento <span id="txtAcolhido"></span></h2>

                <div class="tab mt-4">
                  
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="equipeTecnica-tab" data-bs-toggle="tab" data-bs-target="#tabEquipeTecnica" type="button" role="tab" aria-controls="equipeTecnica" aria-selected="true" onclick="listaAcoes('Et')">Equipe Técnica</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="psicologia-tab" data-bs-toggle="tab" data-bs-target="#tabPsicologia" type="button" role="tab" aria-controls="psicologia" aria-selected="true" onclick="listaAcoes('Psi');carregaTiposAtendimentos();">Psicologia</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="atividades-tab" data-bs-toggle="tab" data-bs-target="#tabAtividades" type="button" role="tab" aria-controls="atividades" aria-selected="true" onclick="listaAcoes('Atv');carregaTiposAtendimentos();">Atividades</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dadosSensiveis-tab" data-bs-toggle="tab" data-bs-target="#tabDadosSensiveis" type="button" role="tab" aria-controls="dadosSensiveis" aria-selected="true">Dados Sensíveis</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="servicoSocial-tab" data-bs-toggle="tab" data-bs-target="#tabServicoSocial" type="button" role="tab" aria-controls="servicoSocial" aria-selected="true" onclick="listaAcoes('Ss');carregaTiposAtendimentos();">Serviço Social</button>
                      </li>
                      <li class="nav-item d-none" role="presentation">
                        <button class="nav-link" id="projetoVida-tab" data-bs-toggle="tab" data-bs-target="#tabProjetoVida" type="button" role="tab" aria-controls="projetoVida" aria-selected="true">Projeto de Vida</button>
                      </li>
                      <li class="nav-item d-none" role="presentation" id="aba-desligamento">
                        <button class="nav-link" id="desligamento-tab" data-bs-toggle="tab" data-bs-target="#tabDesligamento" type="button" role="tab" aria-controls="desligamento" aria-selected="true">Desligamento</button>
                      </li>
                  </ul>
                  
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <!-- TAB EQUIPE TÉCNICA -->  
                    <div class="tab-pane fade show active" id="tabEquipeTecnica" role="tabpanel" aria-labelledby="equipeTecnica-tab">
                      <h3>Evolução do Prontuário - Equipe</h3>
                      
                      <form class="row g-3" id="formEquipeTecnica" name="formEquipeTecnica" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-3 mt-4" id="boxBotaoNovaAnotacaoEquipeTecnica">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesTecnicas" aria-expanded="false" aria-controls="colAnotacoesTecnicas">
                            <i class="bi bi-card-list"></i> Registro de Atendimento
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesTecnicas">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="date" class="form-control" id="txtDataAnotacaoTecnica" name="txtDataAnotacaoTecnica" value="<?php echo date("Y-m-d") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnico" name="txtNomeTecnico" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoEquipeTecnica" name="txtDocumentoEquipeTecnica" placeholder="Registro Profissional" readonly value="277.447.658-50">
                                <label for="floatingName">Nº Registro Profissional</label>
                              </div>                        
                            </div>
                            <div class="col-7 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição da Equipe Técnica" id="txtDescricaoAcaoEquipeTecnica" name="txtDescricaoAcaoEquipeTecnica" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Registro de Atendimento</label>
                              </div>
                            </div>
                            <div class="col-7 mt-3">
                              <h4>Anexar arquivo</h4>
                              <div class="form-floating">
                                <input type="file" class="form-control" id="uplDocEquipeTecnica" name="uplDocEquipeTecnica">
                              </div>
                            </div>
                            <div class="col-7 mt-3 mb-4">
                              <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesTecnicas" aria-expanded="false" aria-controls="colAnotacoesTecnicas" onclick="cadastraAcaoEt()">
                                <i class="bi bi-check2-circle"></i> Registrar Ação
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>

                      <div class="row" id="boxListaAcoesEt"></div>

                    </div>

                    <!-- TAB PSICOLOGIA -->
                    <div class="tab-pane fade" id="tabPsicologia" role="tabpanel" aria-labelledby="psicologia-tab">
                      <h3>Evolução do Prontuário - Psicologia</h3>
                      
                      <form class="row g-3" id="formPsicologia" name="formPsicologia" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-3 mt-4" id="boxBotaoNovaAnotacaoPsicologia">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesPsicologia" aria-expanded="false" aria-controls="colAnotacoesPsicologia">
                            <i class="bi bi-card-list"></i> Registro de Atendimento
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesPsicologia">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="date" class="form-control" id="txtDataAnotacaoPsicologia" name="txtDataAnotacaoPsicologia" value="<?php echo date("Y-m-d") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnicoPsicologia" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoPsicologia" placeholder="Registro Profissional" readonly value="277.447.658-50">
                                <label for="floatingName">Nº Registro Profissional</label>
                              </div>                        
                            </div>
                            <div class="col-4"></div>
                            <div class="col-3 mt-2">
                              <div class="form-floating" id="boxTiposAtendimentosPsicologia"></div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição da Psicologia" id="txtDescricaoAcaoPsicologia" name="txtDescricaoAcaoPsicologia" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Registro de Atendimento</label>
                              </div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <input type="file" class="form-control" id="uplDocPsicologia">
                              </div>
                            </div>
                            
                            <div class="col-7 mt-2 d-flex align-items-center">
                                <button class="btn btn-primary me-3" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesPsicologia" aria-expanded="false" aria-controls="colAnotacoesPsicologia" onclick="cadastraAcaoPsi()">
                                    <i class="bi bi-check2-circle"></i> Registrar Ação
                                </button>
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="chkSigilosoPsi" id="chkSigilosoPsi" value="1" style="transform: scale(1.5);">
                                    <label class="form-check-label ms-2" for="chkSigilosoPsi">
                                        Informações Sigilosas
                                    </label>
                                </div>
                            </div>


                          </div>
                        </div>
                      </form>

                      <div class="row" id="boxListaAcoesPsi"></div>

                    </div>

                    <!-- TAB ATIVIDADES -->
                    <div class="tab-pane fade" id="tabAtividades" role="tabpanel" aria-labelledby="atividades-tab">
                      <h3>Evolução do Prontuário - Atividades</h3>
                      
                      <form class="row g-3" id="formAtividades" name="formAtividades" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-3 mt-4" id="boxBotaoNovaAnotacaoAtividades">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesAtividades" aria-expanded="false" aria-controls="colAnotacoesAtividades">
                            <i class="bi bi-card-list"></i> Registro de Atendimento
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesAtividades">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="date" class="form-control" id="txtDataAnotacaoAtividades" name="txtDataAnotacaoAtividades" value="<?php echo date("Y-m-d") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnicoAtividades" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoAtividades" placeholder="Registro Profissional" readonly value="277.447.658-50">
                                <label for="floatingName">Nº Registro Profissional</label>
                              </div>                        
                            </div>
                            <div class="col-4"></div>
                            <div class="col-3 mt-2">
                              <div class="form-floating" id="boxTiposAtendimentosAtividades"></div>
                            </div>
                            <div class="col-4 mt-2">
                              <div class="form-floating" id="boxSubTiposAtendimentosAtividades"></div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição das Atividades" id="txtDescricaoAcaoAtividades" name="txtDescricaoAcaoAtividades" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Registro de Atendimento</label>
                              </div>
                            </div>
                            
                            <div class="col-7 mt-2 d-flex align-items-center">
                                <button class="btn btn-primary me-3" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesAtividades" aria-expanded="false" aria-controls="colAnotacoesAtividades" onclick="cadastraAcaoAtv()">
                                    <i class="bi bi-check2-circle"></i> Registrar Ação
                                </button>
                            </div>


                          </div>
                        </div>
                      </form>

                      <div class="row" id="boxListaAcoesAtv"></div>

                    </div>

                    <!-- TAB DADOS SENSÍVEIS -->
                    <div class="tab-pane fade" id="tabDadosSensiveis" role="tabpanel" aria-labelledby="dadosSensiveis-tab">
                      <h3>Dados Sensíveis</h3>
                      <form class="row g-3" id="formDadosSensiveis" name="formDadosSensiveis" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">A. Sofreu negligência/abandono dos cuidadores/responsáveis na infância/adolescência?</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia1" name="radNegligencia" value="Sim, com acolhimento institucional">
                              <label class="form-check-label" for="radNegligencia1">Sim, com acolhimento institucional</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia2" name="radNegligencia" value="Sim, com destituição do poder familiar">
                              <label class="form-check-label" for="radNegligencia2">Sim, com destituição do poder familiar</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia3" name="radNegligencia" value="Sim, sem acompanhamento da rede protetiva">
                              <label class="form-check-label" for="radNegligencia3">Sim, sem acompanhamento da rede protetiva</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia4" name="radNegligencia" value="Não">
                              <label class="form-check-label" for="radNegligencia4">Não</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia5" name="radNegligencia" value="Não foi possível coletar esta informação durante o processo terapêutico">
                              <label class="form-check-label" for="radNegligencia5">Não foi possível coletar esta informação durante o processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radNegligencia6" name="radNegligencia" value="Há indícios, porém, sem confirmação no processo terapêutico">
                              <label class="form-check-label" for="radNegligencia6">Há indícios, porém, sem confirmação no processo terapêutico</label>
                            </div>

                            <div class="col-md-2 mt-2 ms-4 d-none" id="boxIdadeNegligencia">
                              <div class="form-floating">
                                <input type="number" class="form-control" id="txtIdadeNegligencia" name="txtIdadeNegligencia" min="0" max="120" placeholder="Qual idade?">
                                <label for="txtIdadeNegligencia">Qual idade?</label>
                              </div>
                            </div>

                          </div>
                          
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">B. Sofreu violência física dos cuidadores/responsáveis na infância/adolescência?</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica1" name="radViolenciaFisica" value="Sim, com acolhimento institucional">
                              <label class="form-check-label" for="radViolenciaFisica1">Sim, com acolhimento institucional</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica2" name="radViolenciaFisica" value="Sim, com destituição do poder familiar">
                              <label class="form-check-label" for="radViolenciaFisica2">Sim, com destituição do poder familiar</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica3" name="radViolenciaFisica" value="Sim, sem acompanhamento da rede protetiva">
                              <label class="form-check-label" for="radViolenciaFisica3">Sim, sem acompanhamento da rede protetiva</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica4" name="radViolenciaFisica" value="Não">
                              <label class="form-check-label" for="radViolenciaFisica4">Não</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica5" name="radViolenciaFisica" value="Não foi possível coletar esta informação durante o processo terapêutico">
                              <label class="form-check-label" for="radViolenciaFisica5">Não foi possível coletar esta informação durante o processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaFisica6" name="radViolenciaFisica" value="Há indícios, porém, sem confirmação no processo terapêutico">
                              <label class="form-check-label" for="radViolenciaFisica6">Há indícios, porém, sem confirmação no processo terapêutico</label>
                            </div>

                            <div class="col-md-2 mt-2 ms-4 d-none" id="boxIdadeViolenciaFisica">
                              <div class="form-floating">
                                <input type="number" class="form-control" id="txtIdadeViolenciaFisica" name="txtIdadeViolenciaFisica" min="0" max="120" placeholder="Qual idade?">
                                <label for="txtIdadeViolenciaFisica">Qual idade?</label>
                              </div>
                            </div>

                          </div>
                          
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">C. Sofreu violência sexual na infância/adolescência?</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaSexual1" name="radViolenciaSexual" value="Sim">
                              <label class="form-check-label" for="radViolenciaSexual1">Sim</label>
                            </div>
                            
                            <div class="col-md-2 mb-2 d-none" id="boxIdade">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtQualIdade" name="txtQualIdade" placeholder="Qual idade?">
                                <label for="txtQualIdade">Qual idade?</label>
                              </div>
                            </div>
                            
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaSexual2" name="radViolenciaSexual" value="Não">
                              <label class="form-check-label" for="radViolenciaSexual2">Não</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaSexual3" name="radViolenciaSexual" value="Não foi possível coletar esta informação durante o processo terapêutico">
                              <label class="form-check-label" for="radViolenciaSexual3">Não foi possível coletar esta informação durante o processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaSexual4" name="radViolenciaSexual" value="Há indícios, porém, sem confirmação no processo terapêutico">
                              <label class="form-check-label" for="radViolenciaSexual4">Há indícios, porém, sem confirmação no processo terapêutico</label>
                            </div>

                          </div>
                          <div class="col-6 mt-2">
                            <div class="form-floating">
                              <textarea class="form-control" placeholder="Observações" id="txtObservacoesViolenciaSexual" name="txtObservacoesViolenciaSexual" style="height: 100px;"></textarea>
                              <label for="floatingTextarea">Observações</label>
                            </div>
                          </div>
                          
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">D. Sobre o agressor:</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor1" name="chkAgressor[]" value="Pai">
                              <label class="form-check-label" for="chkAgressor1">Pai</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor2" name="chkAgressor[]" value="Mãe">
                              <label class="form-check-label" for="chkAgressor2">Mãe</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor3" name="chkAgressor[]" value="Padrasto">
                              <label class="form-check-label" for="chkAgressor3">Padrasto</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor4" name="chkAgressor[]" value="Madrasta">
                              <label class="form-check-label" for="chkAgressor4">Madrasta</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor5" name="chkAgressor[]" value="Tios/primos/sobrinhos">
                              <label class="form-check-label" for="chkAgressor5">Tios/primos/sobrinhos</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor6" name="chkAgressor[]" value="Irmãos">
                              <label class="form-check-label" for="chkAgressor6">Irmãos</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor7" name="chkAgressor[]" value="Pessoas de confiança da família">
                              <label class="form-check-label" for="chkAgressor7">Pessoas de confiança da família</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor8" name="chkAgressor[]" value="Desconhecidos">
                              <label class="form-check-label" for="chkAgressor8">Desconhecidos</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="chkAgressor9" name="chkAgressor[]" value="Não se aplica">
                              <label class="form-check-label" for="chkAgressor9">Não se aplica</label>
                            </div>

                          </div>
                          
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">E. Sofreu violência praticada por parceiros?</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaParceiros1" name="radViolenciaParceiros" value="Não">
                              <label class="form-check-label" for="radViolenciaParceiros1">Não</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaParceiros2" name="radViolenciaParceiros" value="Não foi possível coletar esta informação durante o processo terapêutico">
                              <label class="form-check-label" for="radViolenciaParceiros2">Não foi possível coletar esta informação durante o processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaParceiros3" name="radViolenciaParceiros" value="Há indícios, porém, sem confirmação no processo terapêutico">
                              <label class="form-check-label" for="radViolenciaParceiros3">Há indícios, porém, sem confirmação no processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radViolenciaParceiros4" name="radViolenciaParceiros" value="Sim">
                              <label class="form-check-label" for="radViolenciaParceiros4">Sim</label>
                            </div>

                            <div class="col-md-2 mt-2 ms-4 d-none" id="boxIdadeViolenciaParceiros">
                              <div class="form-floating">
                                <input type="number" class="form-control" id="txtIdadeViolenciaParceiros" name="txtIdadeViolenciaParceiros" min="0" max="120" placeholder="Qual idade?">
                                <label for="txtIdadeViolenciaParceiros">Qual idade?</label>
                              </div>
                            </div>

                            <div class="col-sm-10 ms-4 d-none" id="boxViolenciaParceiros">

                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolenciaParceiro1" name="chkTipoViolenciaParceiro[]" value="Patrimonial">
                                <label class="form-check-label" for="chkTipoViolenciaParceiro1">Patrimonial</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolenciaParceiro2" name="chkTipoViolenciaParceiro[]" value="Física">
                                <label class="form-check-label" for="chkTipoViolenciaParceiro2">Física</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolenciaParceiro3" name="chkTipoViolenciaParceiro[]" value="Psicológica">
                                <label class="form-check-label" for="chkTipoViolenciaParceiro3">Psicológica</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolenciaParceiro4" name="chkTipoViolenciaParceiro[]" value="Sexual">
                                <label class="form-check-label" for="chkTipoViolenciaParceiro4">Sexual</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolenciaParceiro5" name="chkTipoViolenciaParceiro[]" value="Moral">
                                <label class="form-check-label" for="chkTipoViolenciaParceiro5">Moral</label>
                              </div>

                              <div class="row mt-2">
                                <legend class="col-form-label col-sm-12 pt-0 fw-bold">Teve suporte da rede protetiva? (DDM, CREAS, Hospitais, etc.)</legend>
                                <div class="col-sm-10">

                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radSuporte1" name="radSuporte" value="Sim">
                                    <label class="form-check-label" for="radSuporte1">Sim</label>
                                  </div>

                                  <div class="col-md-8 mb-2 d-none" id="boxSuporte">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtQualSuporte" name="txtQualSuporte" placeholder="Qual?">
                                      <label for="txtQualSuporte">Qual?</label>
                                    </div>
                                  </div>
                                  
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="radSuporte2" name="radSuporte" value="Não">
                                    <label class="form-check-label" for="radSuporte2">Não</label>
                                  </div>

                                </div>
                              </div>

                            </div>

                          </div>
                          
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0 fw-bold">F. Foi autor (a) de violência?</legend>
                          <div class="col-sm-10">

                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radAutorViolencia1" name="radAutorViolencia" value="Não">
                              <label class="form-check-label" for="radAutorViolencia1">Não</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radAutorViolencia2" name="radAutorViolencia" value="Não foi possível coletar esta informação durante o processo terapêutico">
                              <label class="form-check-label" for="radAutorViolencia2">Não foi possível coletar esta informação durante o processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radAutorViolencia3" name="radAutorViolencia" value="Há indícios, porém, sem confirmação no processo terapêutico">
                              <label class="form-check-label" for="radAutorViolencia3">Há indícios, porém, sem confirmação no processo terapêutico</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="radAutorViolencia4" name="radAutorViolencia" value="Sim">
                              <label class="form-check-label" for="radAutorViolencia4">Sim</label>
                            </div>

                            <div class="col-sm-10 ms-4 d-none" id="boxAutorViolencia">

                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolencia1" name="chkTipoViolencia[]" value="Patrimonial">
                                <label class="form-check-label" for="chkTipoViolencia1">Patrimonial</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolencia2" name="chkTipoViolencia[]" value="Física">
                                <label class="form-check-label" for="chkTipoViolencia2">Física</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolencia3" name="chkTipoViolencia[]" value="Psicológica">
                                <label class="form-check-label" for="chkTipoViolencia3">Psicológica</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolencia4" name="chkTipoViolencia[]" value="Sexual">
                                <label class="form-check-label" for="chkTipoViolencia4">Sexual</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkTipoViolencia5" name="chkTipoViolencia[]" value="Moral">
                                <label class="form-check-label" for="chkTipoViolencia5">Moral</label>
                              </div>

                              <div class="row mt-2">
                                <div class="col-sm-10">
                                  <legend class="col-form-label col-sm-12 pt-0 fw-bold">Foi responsabilizado criminalmente?</legend>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radResponsabilizado1" name="radResponsabilizado" value="Não foi responsabilizado criminalmente">
                                    <label class="form-check-label" for="radResponsabilizado1">Não foi responsabilizado criminalmente</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radResponsabilizado2" name="radResponsabilizado" value="Foi responsabilizado criminalmente">
                                    <label class="form-check-label" for="radResponsabilizado2">Foi responsabilizado criminalmente</label>
                                  </div>

                                </div>

                                <div class="col-sm-10 ms-4 mt-2 d-none" id="boxPenaAplicada">
                                  <legend class="col-form-label col-sm-12 pt-0 fw-bold">Qual a pena que lhe foi aplicada?</legend>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radPenaAplicada1" name="radPenaAplicada" value="Pena alternativa">
                                    <label class="form-check-label" for="radPenaAplicada1">Pena alternativa</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radPenaAplicada2" name="radPenaAplicada" value="Audiência de custódia">
                                    <label class="form-check-label" for="radPenaAplicada2">Audiência de custódia</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radPenaAplicada3" name="radPenaAplicada" value="Sentença em regime semiaberto">
                                    <label class="form-check-label" for="radPenaAplicada3">Sentença em regime semiaberto</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radPenaAplicada4" name="radPenaAplicada" value="Sentença em regime fechado">
                                    <label class="form-check-label" for="radPenaAplicada4">Sentença em regime fechado</label>
                                  </div>

                                  <div class="col-md-5 mb-2 d-none" id="boxTempoPenaAplicada">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtTempoPenaAplicada" name="txtTempoPenaAplicada" placeholder="Qual o tempo da pena concedida? ">
                                      <label for="txtTempoPenaAplicada">Qual o tempo da pena concedida? </label>
                                    </div>
                                  </div>

                                </div>
                              </div>

                            </div>

                            <div class="row mt-4">
                              <legend class="col-form-label col-sm-12 pt-0 fw-bold">G. É egresso do sistema prisional?</legend>
                              <div class="col-sm-10">

                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="radEgresso1" name="radEgresso" value="Sim">
                                  <label class="form-check-label" for="radEgresso1">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="radEgresso2" name="radEgresso" value="Não">
                                  <label class="form-check-label" for="radEgresso2">Não</label>
                                </div>

                                <div class="col-sm-12 mt-2 d-none" id="boxEgresso">

                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radEgressoPena1" name="radEgressoPena" value="Pena alternativa">
                                    <label class="form-check-label" for="radEgressoPena1">Pena alternativa</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radEgressoPena2" name="radEgressoPena" value="Audiência de custódia">
                                    <label class="form-check-label" for="radEgressoPena2">Audiência de custódia</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radEgressoPena3" name="radEgressoPena" value="Sentença em regime semiaberto">
                                    <label class="form-check-label" for="radEgressoPena3">Sentença em regime semiaberto</label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" id="radEgressoPena4" name="radEgressoPena" value="Sentença em regime fechado">
                                    <label class="form-check-label" for="radEgressoPena4">Sentença em regime fechado</label>
                                  </div>

                                  <div class="col-sm-10 ms-2 d-none" id="boxSentenca">

                                    <div class="col-md-5 mt-2">
                                      <div class="form-floating">
                                        <input type="text" class="form-control" id="txtTempoPenaEgresso" name="txtTempoPenaEgresso" placeholder="Qual o tempo da pena concedida?">
                                        <label for="txtTempoPenaEgresso">Qual o tempo da pena concedida?</label>
                                      </div>
                                    </div>

                                    <div class="col-sm-10 mt-2">
                                      <legend class="col-form-label col-sm-12 pt-0 fw-bold">Cumpriu toda a pena?</legend>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radCumpriuPena1" name="radCumpriuPena" value="Sim">
                                        <label class="form-check-label" for="radCumpriuPena1">Sim</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radCumpriuPena2" name="radCumpriuPena" value="Não">
                                        <label class="form-check-label" for="radCumpriuPena2">Não</label>
                                      </div>
                                    </div>
                                    <div class="col-sm-10 mt-2">
                                      <legend class="col-form-label col-sm-12 pt-0 fw-bold">Está foragido?</legend>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radForagido1" name="radForagido" value="Sim">
                                        <label class="form-check-label" for="radForagido1">Sim</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radForagido2" name="radForagido" value="Não">
                                        <label class="form-check-label" for="radForagido2">Não</label>
                                      </div>
                                    </div>
                                    <div class="col-sm-10 mt-2">
                                      <legend class="col-form-label col-sm-12 pt-0 fw-bold">Liberdade provisória?</legend>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radLiberdade1" name="radLiberdade" value="Sim">
                                        <label class="form-check-label" for="radLiberdade1">Sim</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="radLiberdade2" name="radLiberdade" value="Não">
                                        <label class="form-check-label" for="radLiberdade2">Não</label>
                                      </div>
                                    </div>

                                  </div>

                                </div>

                              </div>
                              
                            </div>

                            <div class="row mt-4">
                              <legend class="col-form-label col-sm-12 pt-0 fw-bold">H.Está com pendência judicial?</legend>
                              <div class="col-sm-10">

                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="radPendenciaJudicial1" name="radPendenciaJudicial" value="Sim">
                                  <label class="form-check-label" for="radPendenciaJudicial1">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" id="radPendenciaJudicial2" name="radPendenciaJudicial" value="Não">
                                  <label class="form-check-label" for="radPendenciaJudicial2">Não</label>
                                </div>

                                <div class="col-sm-12 mt-2 d-none" id="boxPendencia">
                                  <div class="col-md-5 mt-2">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtMotivoPendencia" name="txtMotivoPendencia" placeholder="Qual o motivo?">
                                      <label for="txtMotivoPendencia">Qual o motivo?</label>
                                    </div>
                                  </div>
                                </div>

                              </div>
                              
                            </div>

                          </div>
                          
                        </div>

                        <div class="text-center mt-5 col-md-11" id="boxBotaoDadosSensiveis">
                          <button class="btn btn-success mt-5 mb-3 mx-0" onclick="cadastraDadosSensiveis()">Registrar dados sensíveis</button>
                        </div>
                      </form>

                    </div>
                    
                    <!-- TAB SERVIÇOS SOCIAIS -->
                    <div class="tab-pane fade" id="tabServicoSocial" role="tabpanel" aria-labelledby="servicoSocial-tab">
                      <h3>Evolução do Prontuário - Serviço Social</h3>
                      
                      <form class="row g-3" id="formServicoSocial" name="formServicoSocial" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-3 mt-4" id="boxBotaoNovaAnotacaoPsicologia">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesServicoSocial" aria-expanded="false" aria-controls="colAnotacoesServicoSocial">
                            <i class="bi bi-card-list"></i> Registro de Atendimento
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesServicoSocial">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="date" class="form-control" id="txtDataAnotacaoServicoSocial" name="txtDataAnotacaoServicoSocial" value="<?php echo date("Y-m-d") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnicoServicoSocial" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoServicoSocial" placeholder="Registro Profissional" readonly value="277.447.658-50">
                                <label for="floatingName">Nº Registro Profissional</label>
                              </div>                        
                            </div>                            
                            <div class="col-4"></div>
                            <div class="col-3 mt-2">
                              <div class="form-floating" id="boxTiposAtendimentosServicoSocial"></div>                   
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Registro de Atendimento" id="txtDescricaoAcaoServicoSocial" name="txtDescricaoAcaoServicoSocial" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Registro de Atendimento</label>
                              </div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <input type="file" class="form-control" id="uplDocServicoSocial">
                              </div>
                            </div>
                            <div class="col-7 mt-2 d-flex align-items-center">
                                <button class="btn btn-primary me-3" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesServicoSocial" aria-expanded="false" aria-controls="colAnotacoesServicoSocial" onclick="cadastraAcaoSs()">
                                    <i class="bi bi-check2-circle"></i> Registrar Ação
                                </button>
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="chkSigilosoSs" id="chkSigilosoSs" value="1" style="transform: scale(1.5);">
                                    <label class="form-check-label ms-2" for="chkSigilosoSs">
                                        Informações Sigilosas
                                    </label>
                                </div>
                            </div>
                          </div>
                        </div>
                      </form>

                      <div class="row" id="boxListaAcoesSs"></div>

                    </div>
                    
                    <!-- TAB PROJETO DE VIDA -->
                    <div class="tab-pane fade" id="tabProjetoVida" role="tabpanel" aria-labelledby="projetoVida-tab">
                      <h3>Projeto de Vida</h3>
                      <p>Projeto de Vida</p>
                    </div>
                    
                    <!-- TAB DESLIGAMENTO -->
                    <div class="tab-pane fade" id="tabDesligamento" role="tabpanel" aria-labelledby="desligamento-tab">
                      <h3>Desligamento</h3>
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
                                <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado7" name="chkMotivosDesligamentoQualificado[]" value="Condições de autossustento">
                                <label class="form-check-label" for="chkMotivosDesligamentoQualificado7">Condições de autossustento</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado8" name="chkMotivosDesligamentoQualificado[]" value="Condições de moradia">
                                <label class="form-check-label" for="chkMotivosDesligamentoQualificado8">Condições de moradia</label>
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
                          </div>
                        </div>
                      </form>
                    </div>
                  
                  </div>
                
                </div>

              </div>

            </div>

          </div>
<!-- FIM DO CARD ACOLHIMENTO -->


<!-- INICIO DO CARD ANAMNESE -->

          <div class="card d-none" id="cardAnamnese">
            
            <div class="card-body">
              
              <div class="row mt-3" id="boxListaAcolhimentos">

                <h2 class="ms-2">Anamnese <span id="txtAcolhido"></span></h2>

                <div class="tab mt-4">
        
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="identificacao-tab" data-bs-toggle="tab" data-bs-target="#tabIdentificacao" type="button" role="tab" aria-controls="identificacao" aria-selected="true" onclick="listaAcoes('Et')">1 - Identificação</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="historicoSocial-tab" data-bs-toggle="tab" data-bs-target="#tabHistoricoSocial" type="button" role="tab" aria-controls="historicoSocial" aria-selected="true" onclick="listaAcoes('Psi');carregaTiposAtendimentos();">2 - Histórico Social</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="saudeGeral-tab" data-bs-toggle="tab" data-bs-target="#tabSaudeGeral" type="button" role="tab" aria-controls="saudeGeral" aria-selected="true">3 - Saúde Geral</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="avaliacaoPsicossocial-tab" data-bs-toggle="tab" data-bs-target="#tabAvaliacaoPsicossocial" type="button" role="tab" aria-controls="avaliacaoPsicossocial" aria-selected="true" onclick="listaAcoes('Ss');carregaTiposAtendimentos();">4 - Avaliação Psicossocial</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sobreUso-tab" data-bs-toggle="tab" data-bs-target="#tabSobreUso" type="button" role="tab" aria-controls="sobreUso" aria-selected="true">5 - Sobre o uso</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="medicacao-tab" data-bs-toggle="tab" data-bs-target="#tabMedicacao" type="button" role="tab" aria-controls="medicacao" aria-selected="true">6 - Medicação</button>
                      </li>
                  </ul>
                  
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <!-- TAB IDENTIFICAÇÃO -->  
                    <div class="tab-pane fade show active" id="tabIdentificacao" role="tabpanel" aria-labelledby="identificacao-tab">
                      
                      <form class="row g-3" id="formIdentificacao" name="formIdentificacao" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row mt-4">

                          <div class="col-4">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNacionalidade" name="txtNacionalidade" placeholder="Nome do Técnico">
                              <label for="txtNacionalidade">Nacionalidade</label>
                            </div>
                          </div>

                          <div class="col-4">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNaturalidade" name="txtNaturalidade" placeholder="Nome do Técnico">
                              <label for="txtNaturalidade">Naturalidade</label>
                            </div>
                          </div>
                          
                          <div class="col-2">
                            <div class="form-floating">
                              <select class="form-select" id="slcEtnia" name="slcEtnia" aria-label="Etnia">
                                <option value="0" selected></option>
                                <option value="Branca">Branca</option>
                                <option value="Preta">Preta</option>
                                <option value="Parda">Parda</option>
                                <option value="Indígena">Indígena</option>
                                <option value="Amarela">Amarela</option>
                              </select>
                              <label for="slcEtnia">Etnia</label>
                            </div>
                          </div>

                          <div class="col-md-12 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>1.1 O nascimento (nome) foi registrado em Cartório de Registro Civil?</strong></legend>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="radRegistroCartorio" id="radRegistroCartorio1" value="Sim e tem Certidão de Nascimento e/ou de Casamento">
                              <label class="form-check-label" for="radRegistroCartorio1">Sim e tem Certidão de Nascimento e/ou de Casamento</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="radRegistroCartorio" id="radRegistroCartorio2" value="Sim, mas não tem Certidão de Nascimento nem de Casamento">
                              <label class="form-check-label" for="radRegistroCartorio2">Sim, mas não tem Certidão de Nascimento nem de Casamento</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="radRegistroCartorio" id="radRegistroCartorio3" value="Não (Se tem RANI, passe ao 1.3.2, opção C)">
                              <label class="form-check-label" for="radRegistroCartorio3">Não (Se tem RANI, passe ao 1.3.2, opção C)</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="radRegistroCartorio" id="radRegistroCartorio4" value="Não sabe">
                              <label class="form-check-label" for="radRegistroCartorio4">Não sabe</label>
                            </div>
                          </div>

                          <div class="col-4 mt-4">
                            <div class="form-floating">
                              <select class="form-select" id="slcTipoCertidao" name="slcTipoCertidao" aria-label="Tipos de Certidão">
                                <option value="0" selected></option>
                                <option value="Nascimento">Nascimento</option>
                                <option value="Casamento">Casamento</option>
                                <option value="Certidão Administrativa de Nascimento do Indígena (RANI)">Certidão Administrativa de Nascimento do Indígena (RANI)</option>
                              </select>
                              <label for="floatingSelect">1.2 Tipo da Certidão</label>
                            </div>
                          </div>
                          <div class="col-8 mt-4"></div>
                          <div class="col-8 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNomeCartorio" name="txtNomeCartorio" placeholder="Nome do Técnico">
                              <label for="floatingName">Nome do Cartório</label>
                            </div>
                          </div>
                          <div class="col-4 mt-2"></div>
                          <div class="col-2 mt-2">
                            <div class="form-floating">
                              <input type="date" class="form-control" id="txtDataRegistro" name="txtDataRegistro" placeholder="Nome do Técnico">
                              <label for="floatingName">Data do Registro</label>
                            </div>
                          </div>
                          <div class="col-2 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNLivro" name="txtNLivro" placeholder="Nome do Técnico">
                              <label for="floatingName">Nº do Livro</label>
                            </div>
                          </div>
                          <div class="col-2 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNFolha" name="txtNFolha" placeholder="Nome do Técnico">
                              <label for="floatingName">Nº da Folha</label>
                            </div>
                          </div>
                          <div class="col-2 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNTermo" name="txtNTermo" placeholder="Nome do Técnico">
                              <label for="floatingName">Nº do Termo/RANI</label>
                            </div>
                          </div>
                          <div class="col-4 mt-2"></div>
                          <div class="col-4 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtMatricula" name="txtMatricula" placeholder="Nome do Técnico">
                              <label for="floatingName">Matrícula</label>
                            </div>
                          </div>
                          <div class="col-8 mt-2"></div>
                          <div class="col-2 mt-2">
                            <div class="form-floating">
                              <select class="form-select" id="slcUfRegistro" name="slcUfRegistro" aria-label="Tipos de Certidão">
                                <option value="0" selected></option>
                              </select>
                              <label for="floatingSelect">UF do Registro</label>
                            </div>
                          </div>
                          <div class="col-4 mt-2">
                            <div class="form-floating">
                              <select class="form-select" id="slcMunicipioCertidao" name="slcMunicipioCertidao" aria-label="Tipos de Certidão">
                                <option value="0" selected></option>
                              </select>
                              <label for="floatingSelect">Município do Registro</label>
                            </div>
                          </div>
                          <div class="col-12 mt-5">
                            <button class="btn btn-primary" type="button" id="btnCadIdentificacao" name="btnCadIdentificacao">
                              <i class="bi bi-check2-circle"></i> Registrar Identificação
                            </button>
                          </div>

                        </div>
                      </form>

                    </div>

                    <!-- TAB HISTÓRICO SOCIAL -->
                    <div class="tab-pane fade" id="tabHistoricoSocial" role="tabpanel" aria-labelledby="historicoSocial-tab">

                      <form class="row g-3" id="formHistoricoSocial" name="formHistoricoSocial" method="POST" enctype="multipart/form-data" autocomplete="off">
                        
                        <div class="row mt-4">

                          <div class="col-md-12 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>2.1 ESCOLARIDADE</strong></legend>
                          </div>

                          <div class="col-md-3 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Sabe ler e escrever?</strong></legend>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="radSabeLer" id="radSabeLer1" value="Sim">
                              <label class="form-check-label" for="radSabeLer1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="radSabeLer" id="radSabeLer2" value="Não">
                              <label class="form-check-label" for="radSabeLer2">Não</label>
                            </div>
                          </div>

                          <div class="col-md-6 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Frequentou escola?</strong></legend>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxEscola',1,0)" name="radFrequentouEscola" id="radFrequentouEscola1" value="Sim">
                              <label class="form-check-label" for="radFrequentouEscola1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxEscola',0,0)" name="radFrequentouEscola" id="radFrequentouEscola2" value="Não">
                              <label class="form-check-label" for="radFrequentouEscola2">Não</label>
                            </div>
                          </div>

                          <div class="row d-none" id="boxEscola">
                            <div class="col-4 mt-4">
                              <div class="form-floating">
                                <select class="form-select" id="slcGrauEscolaridade" name="slcGrauEscolaridade" aria-label="Ano/Série">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcEtnia">Grau de Escolaridade</label>
                              </div>
                            </div>

                            <div class="col-2 mt-4" id="boxEscolaridade">
                              <div class="form-floating">
                                <select class="form-select" id="slcAnoSerie" name="slcAnoSerie" aria-label="Grau de Escolaridade">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcEtnia">Ano/Série</label>
                              </div>
                            </div>
                            <div class="col-6"></div>
                            <div class="col-6 mt-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeEscola" name="txtNomeEscola" placeholder="Nome do Técnico">
                                <label for="txtNacionalidade">Nome da Escola</label>
                              </div>
                            </div>
                            <div class="col-6"></div>
                            <div class="col-2 mt-2" id="boxEscolaridade">
                              <div class="form-floating">
                                <select class="form-select" id="slcUfEscola" name="slcUfEscola" aria-label="Grau de Escolaridade">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcEtnia">UF</label>
                              </div>
                            </div>

                            <div class="col-4 mt-2">
                              <div class="form-floating">
                                <select class="form-select" id="slcMunicipioEscola" name="slcMunicipioEscola" aria-label="Etnia">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcEtnia">Cidade</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>2.2 MORADIA</strong></legend>
                          </div>

                          <div class="col-md-6 mt-4 ms-2" id="boxOndeCostumaDormirOpcoes">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>a. Onde costuma dormir? (nos últimos 6 meses)?</strong></legend>
                            <div id="boxOndeCostumaDormirLista"></div>
                          </div>
                          <div class="col-5"></div>
                          <div class="row d-none" id="boxTempoMoradia">
                            <div class="col-3 mt-4">
                              <div class="form-floating">
                                <select class="form-select" id="slcTempoMoradia" name="slcTempoMoradia" aria-label="Etnia">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcTempoMoradia">Por quanto tempo?</label>
                              </div>
                            </div>

                            <div class="col-md-12 mt-4 ms-2" id="boxRotinaDiurnaOpcoes">
                              <legend class="col-form-label col-sm-12 pt-0"><strong>Durante o dia, qual era a sua rotina?</strong></legend>
                              <div id="boxRotinaDiurnaLista"></div>
                            </div>
                          </div>

                          <div class="row d-none" id="boxSituacaoRua">
                          <div class="col-md-12 mt-4 ms-2" id="boxMotivosRuaOpcoes">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>2.3 SITUAÇÃO DE RUA</strong></legend>
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Quais os principais motivos pelos quais passou a morar na rua?</strong></legend>
                            <div class="row" id="boxMotivosRuaLista"></div>
                          </div>

                          <div class="col-4 mt-2">
                            <div class="form-floating">
                              <select class="form-select"  id="slcTempoSituacaoRua" name="slcTempoSituacaoRua" aria-label="Etnia">
                                <option value="0" selected></option>
                              </select>
                              <label for="slcTempoSituacaoRua">Há quanto tempo está em situação de rua?</label>
                            </div>
                          </div>
                          <div class="col-6 mt-4"></div>
                          <div class="col-12 mt-4">
                            <div class="col-7 form-floating">
                              <input type="text" class="form-control" id="txtEstavaSituacaoRua" name="txtEstavaSituacaoRua" placeholder="Nome do Técnico">
                              <label for="txtEstavaSituacaoRua">Já estava em situação de rua quando iniciou uso de drogas ou foi para a rua em decorrência do uso?</label>
                            </div>
                          </div>

                          </div>

                          <div class="col-md-12 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>No último mês, antes do acolhimento terapêutico exerceu alguma atividade remunerada?</strong></legend>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxAtividadeRemunerada',1,0)" name="radAtividadeRemunerada" id="radAtividadeRemunerada1" value="Sim">
                              <label class="form-check-label" for="radAtividadeRemunerada1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxAtividadeRemunerada',0,0)" name="radAtividadeRemunerada" id="radAtividadeRemunerada2" value="Não">
                              <label class="form-check-label" for="radAtividadeRemunerada2">Não</label>
                            </div>
                          </div>

                          <div class="row d-none" id="boxAtividadeRemunerada">
                            <div class="col-5 mt-2">
                              <div class="form-floating">
                                <select class="form-select" onchange="boxControl('boxOutraAtividade',1,this.id)" id="slcTrabalhoPrincipal" name="slcTrabalhoPrincipal" aria-label="Etnia">
                                  <option value="0" selected></option>
                                </select>
                                <label for="slcEtnia">Nesse trabalho principal era</label>
                              </div>
                            </div>

                            <div class="col-md-7 mt-2 d-none" id="boxOutraAtividade">
                              <div class="col-10 form-floating">
                                <input type="text" class="form-control" id="txtOutroTrabalhoPrincipal" name="txtOutroTrabalhoPrincipal" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">Especifique</label>
                              </div>
                            </div>
                          </div>

                          <div class="col-5 mt-2"></div>
                          
                          <div class="col-md-12 mt-4 ms-2">
                            
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Quanto recebe, normalmente, por mês de:</strong></legend>
                            
                            <div class="col-3 mt-4">1. Ajuda/doação regular de não morador</div>
                            <div class="col-md-2 m-1">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtAjudaDoacao" name="txtAjudaDoacao" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">R$</label>
                              </div>
                            </div>
                            
                            <div class="col-4 mt-4">2. Aposentadoria, aposentadoria rural, pensão ou BPC/LOAS</div>
                            <div class="col-md-2 m-1">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtAposentadoria" name="txtAposentadoria" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">R$</label>
                              </div>
                            </div>
                            
                            <div class="col-3 mt-4">3. Seguro desemprego</div>
                            <div class="col-md-2 m-1">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtSeguroDesemprego" name="txtSeguroDesemprego" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">R$</label>
                              </div>
                            </div>
                            
                            <div class="col-3 mt-4">4. Pensão alimentícia</div>
                            <div class="col-md-2 m-1">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtPensao" name="txtPensao" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">R$</label>
                              </div>
                            </div>
                            
                            <div class="col-8 mt-4">5. Outras fontes de remuneração (Bolsa Família, POT, Transcidadania, Frente de Trabalho, entre outras bolsas municipais)</div>
                            <div class="col-md-2 m-1">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtOutrasdFontes" name="txtOutrasdFontes" placeholder="Outro trabalho principal">
                                <label for="txtNacionalidade">R$</label>
                              </div>
                            </div>
                            
                          </div>

                          <div class="col-md-12 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Precisa de qualificações para a inserção no Mercado de Trabalho?</strong></legend>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxQualificacao',1,0)" name="radPrecisaQualificacao" id="radPrecisaQualificacao1" value="Sim">
                              <label class="form-check-label" for="radPrecisaQualificacao1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxQualificacao',0,0)" name="radPrecisaQualificacao" id="radPrecisaQualificacao2" value="Não">
                              <label class="form-check-label" for="radPrecisaQualificacao2">Não</label>
                            </div>
                          </div>

                          <div class="col-md-7 mt-2 d-none" id="boxQualificacao">
                            <div class="col-12 form-floating">
                              <input type="text" class="form-control" id="txtQualQualificacao" name="txtQualQualificacao" placeholder="Outro trabalho principal">
                              <label for="txtQualQualificacao">Especifique</label>
                            </div>
                          </div>

                          <div class="col-md-12 mt-4 ms-2" id="boxReferenciadaOpcoes">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>2.4	VINCULAÇÃO A PROGRAMAS E SERVIÇOS SOCIOASSISTENCIAIS</strong></legend>
                            <legend class="col-form-label col-sm-12 pt-0"><strong>É referenciada ou já foi atendida no:</strong></legend>
                            <div class="row" id="boxReferenciadaLista"></div>
                          </div>

                          <div class="col-12 mt-5">
                            <button class="btn btn-primary" type="button" id="btnCadIdentificacao" name="btnCadIdentificacao">
                              <i class="bi bi-check2-circle"></i> Registrar Saúde Geral
                            </button>
                          </div>

                        </div>
                      
                      </form>

                      <div class="row" id="boxListaAcoesPsi"></div>

                    </div>

                    <!-- TAB SAÚDE GERAL -->
                    <div class="tab-pane fade" id="tabSaudeGeral" role="tabpanel" aria-labelledby="saudeGeral-tab">
                      <form class="row g-3" id="formSaudeGeral" name="formSaudeGeral" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row mt-4">
                          
                          <div class="col-md-12 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>3.1	SAÚDE</strong></legend>
                          </div>

                          <div class="col-md-12 ms-2 mt-4" id="boxDoencasOpcoes">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Possui alguma das doenças listadas abaixo?</strong></legend>
                            <div class="row" id="boxDoencasLista"></div>
                          </div>

                          <div class="col-md-6 mt-2" id="boxOutraDoenca">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutraDoencaSaudeGeral" name="txtOutraDoencaSaudeGeral" placeholder="Qual outra doença?">
                              <label for="txtOutraDoencaSaudeGeral">Qual outra doença?</label>
                            </div>
                          </div>

                          <div class="col-md-12 ms-2 mt-4">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>3.2. Realiza ou realizou tratamento médico e ambulatorial de algumas das doenças listadas?</strong></legend>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxOndeTratamentoMedicoAmbulatorial',1,0)" name="radTratamentoMedicoAmbulatorial" id="radTratamentoMedicoAmbulatorial1" value="Sim">
                              <label class="form-check-label" for="radTratamentoMedicoAmbulatorial1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" onclick="boxControl('boxOndeTratamentoMedicoAmbulatorial',0,0);$('#txtOndeTratamentoMedicoAmbulatorial').val('')" name="radTratamentoMedicoAmbulatorial" id="radTratamentoMedicoAmbulatorial2" value="Não">
                              <label class="form-check-label" for="radTratamentoMedicoAmbulatorial2">Não</label>
                            </div>
                          </div>

                          <div class="col-md-6 mt-2 d-none" id="boxOndeTratamentoMedicoAmbulatorial">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOndeTratamentoMedicoAmbulatorial" name="txtOndeTratamentoMedicoAmbulatorial" placeholder="ONDE?">
                              <label for="txtOndeTratamentoMedicoAmbulatorial">ONDE?</label>
                            </div>
                          </div>

                          <div class="col-12 mt-5">
                            <button class="btn btn-primary" type="button" id="btnCadIdentificacao" name="btnCadIdentificacao">
                              <i class="bi bi-check2-circle"></i> Registrar Histórico Social
                            </button>
                          </div>

                        </div>
                      </form>

                    </div>
                    
                    <!-- TAB AVALIAÇÃO PSICOSSOCIAL -->
                    <div class="tab-pane fade" id="tabAvaliacaoPsicossocial" role="tabpanel" aria-labelledby="avaliacaoPsicossocial-tab">

                      <form class="row g-3" id="formAvaliacaoPsicossocial" name="formAvaliacaoPsicossocial" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row mt-4">

                          <div class="col-md-12 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>4.1	TRANSTORNOS MENTAIS</strong></legend>
                          </div>

                          <div class="col-md-12 mt-4 ms-2">
                            <legend class="col-form-label col-sm-12 pt-0"><strong>Já foi diagnosticada com alguma das especificidades listadas abaixo?</strong></legend>
                            <div class="row" id="boxEspecificidadesLista"></div>
                          </div>

                          <div class="col-md-6 form-floating mt-2 d-none" id="boxOutroTranstorno">
                            <input type="text" class="form-control" id="txtOutroTranstornoPsicossocial" name="txtOutroTranstornoPsicossocial" placeholder="Qual outro transtorno?">
                            <label for="txtOutroTranstornoPsicossocial">Qual outro transtorno?</label>
                          </div>

                          <div class="col-md-12 mt-2 ms-2">
                            <div class="col-form-label col-sm-12 pt-0"><strong>Atualmente faz/fazia algum tipo de acompanhamento?</strong></div>
                            <div id="boxTiposAcompanhamentoLista"></div>
                          </div>

                          <div class="col-md-6 mt-2 d-none" id="boxOndeAcompanhamento">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOndeAcompanhamento" name="txtOndeAcompanhamento" placeholder="ONDE?">
                              <label for="txtOndeAcompanhamento">ONDE?</label>
                            </div>
                          </div>

                          <div class="col-12 mt-5">
                            <button class="btn btn-primary" type="button" id="btnCadIdentificacao" name="btnCadIdentificacao">
                              <i class="bi bi-check2-circle"></i> Registrar Avaliação Psicossocial
                            </button>
                          </div>

                        </div>
                      </form>

                    </div>

                  <!-- TAB SOBRE O USO -->
                  <div class="tab-pane fade" id="tabSobreUso" role="tabpanel" aria-labelledby="sobreUso-tab">
                    <form class="row g-3" id="formSobreUso" name="formSobreUso" method="POST" enctype="multipart/form-data" autocomplete="off">
                      <div class="row mt-4">
                        <div class="col-md-12 ms-2">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>5.1. Qual tipo de substância psicoativa de sua preferência?</strong></legend>
                        </div>
                        
                        <div class="card">
                          <div class="card-body">
                            <div class="row mt-3">
                              <div class="col-3">
                                <div class="form-floating">
                                  <select class="form-select" onchange="boxControl('boxOutraSubstancia',1,this.id)" id="slcDrogas" name="slcDrogas" aria-label="Substâncias">
                                    <option value="" selected></option>
                                    <option value="Álcool">Álcool</option>
                                    <option value="Maconha">Maconha</option>
                                    <option value="Crack">Crack</option>
                                    <option value="Cocaína">Cocaína</option>
                                    <option value="K, Spice">K, Spice</option>
                                    <option value="Solventes">Solventes</option>
                                    <option value="Heroína">Heroína</option>
                                    <option value="LSD">LSD</option>
                                    <option value="Cigarro">Cigarro</option>
                                    <option value="Êxtase">Êxtase</option>
                                    <option value="Metanfetamina">Metanfetamina</option>
                                    <option value="Anfetamina">Anfetamina</option>
                                    <option value="Medicação psicotrópica">Medicação psicotrópica</option>
                                    <option value="Outro(a)">Outro(a)</option>
                                  </select>
                                  <label for="slcDrogas">Substância</label>
                                </div>
                              </div>
                              <div class="col-2">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="txtIdadeInicio" name="txtIdadeInicio" placeholder="Data da Medicação">
                                  <label for="txtIdadeInicio">Idade de início do uso</label>
                                </div>
                              </div>
                              <div class="col-3">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="txtUltimoUso" name="txtUltimoUso" placeholder="Data da Medicação">
                                  <label for="txtUltimoUso">Último uso</label>
                                </div>
                              </div>
                              <div class="col-3">
                                <div class="form-floating">
                                  <select class="form-select" id="slcContinuaUso" name="slcContinuaUso" aria-label="Substâncias">
                                    <option value="" selected></option>
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                  </select>
                                  <label for="slcContinuaUso">Continua o uso?</label>
                                </div>
                              </div>
                              <div class="col-1 mt-2">
                                <button class="btn btn-success" type="button" id="btnAddSubstancias" name="btnAddSubstancias">
                                  <i class="bi bi-plus-circle-dotted"></i>
                                </button>
                              </div>
                              <div class="col-3 form-floating d-none mt-2" id="boxOutraSubstancia">
                                <input type="text" class="form-control" id="txtOutraSubstancia" name="txtOutraSubstancia" placeholder="Outra substância">
                                <label for="txtOutraSubstancia">Qual outra substância?</label>
                              </div>
                            </div>
                            <div class="row mt-2" id="listaSubstanciasProblema"></div>
                          </div>
                        </div>

                        <div class="col-md-12 ms-2 mt-3">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>5.2	HISTÓRICO PASSADO</strong></legend>
                        </div>

                        <div class="row mt-2">
                          <div class="col-3">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxHistoricoFamilia',1,this.id)" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                                <option value="Não sabe">Não sabe</option>
                              </select>
                              <label for="slcDrogas">Há histórico de uso de drogas na família?</label>
                            </div>
                          </div>
                          <div class="row d-none" id="boxHistoricoFamilia">
                            <div class="col-md-12 mt-4 ms-2">
                              <legend class="col-form-label col-sm-12 pt-0"><strong>Quem?</strong></legend>
                              <div class="row">
                                <div class="col-6 mt-3">
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" id="chkFamiliarDrogas1" name="chkFamiliarDrogas[]" value="Pai">
                                    <label class="form-check-label" for="chkFamiliarDrogas1">Pai</label>
                                  </div>
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" id="chkFamiliarDrogas2" name="chkFamiliarDrogas[]" value="Mãe">
                                    <label class="form-check-label" for="chkFamiliarDrogas2">Mãe</label>
                                  </div>
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" id="chkFamiliarDrogas3" name="chkFamiliarDrogas[]" value="Avós">
                                    <label class="form-check-label" for="chkFamiliarDrogas3">Avós</label>
                                  </div>
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" id="chkFamiliarDrogas4" name="chkFamiliarDrogas[]" value="Padrasto/Madrasta">
                                    <label class="form-check-label" for="chkFamiliarDrogas4">Padrasto/Madrasta</label>
                                  </div>
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" id="chkFamiliarDrogas5" name="chkFamiliarDrogas[]" value="Tios">
                                    <label class="form-check-label" for="chkFamiliarDrogas5">Tios</label>
                                  </div>
                                  <div class="form-check form-check-inline ms-3">
                                    <input class="form-check-input" type="checkbox" onclick="boxControl('boxOutroMembroFamilia',1,0)" id="chkFamiliarDrogas6" name="chkFamiliarDrogas[]" value="Outros">
                                    <label class="form-check-label" for="chkFamiliarDrogas6">Outros</label>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="col-12 form-floating" id="boxOutroMembroFamilia">
                                    <input type="text" class="form-control" id="txtOutroFamilia" name="txtOutroFamilia" placeholder="Especifique">
                                    <label for="txtOutroFamilia">Especifique</label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-4 mt-4">
                                <div class="form-floating">
                                  <select class="form-select" id="slcPresenciouFamiliar" name="slcPresenciouFamiliar" aria-label="Substâncias">
                                    <option value="" selected></option>
                                    <option value="Pai">Sim</option>
                                    <option value="Mãe">Não</option>
                                  </select>
                                  <label for="slcPresenciouFamiliar">Já presenciou o uso no ambiente familiar?</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 ms-2 mt-5">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>5.3	SOBRE O PRIMEIRO USO (Não confundir com a dependência química)</strong></legend>
                        </div>

                        <div class="row mt-2">
                          <div class="col-4">
                            <div class="form-floating">
                              <select class="form-select" id="slcPrimeiraExperimentacao" name="slcPrimeiraExperimentacao" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="De 0 a 6 anos">De 0 a 6 anos</option>
                                <option value="De 7 a 10 anos">De 7 a 10 anos</option>
                                <option value="Dos 11 aos 12 anos">Dos 11 aos 12 anos</option>
                                <option value="Dos 13 aos 15 anos">Dos 13 aos 15 anos</option>
                                <option value="Dos 16 aos 17 anos">Dos 16 aos 17 anos</option>
                                <option value="Dos 18 aos 21 anos">Dos 18 aos 21 anos</option>
                                <option value="Dos 22 aos 25 anos">Dos 22 aos 25 anos</option>
                                <option value="Dos 26 aos 35 anos">Dos 26 aos 35 anos</option>
                                <option value="Dos 36 aos 45 anos">Dos 36 aos 45 anos</option>
                                <option value="Acima dos 46 anos">Acima dos 46 anos</option>
                              </select>
                              <label for="slcPrimeiraExperimentacao">Com que idade fez a primeira experimentação?</label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-floating">
                              <select class="form-select" id="slcPrimeiraDroga" name="slcPrimeiraDroga" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Comprou">Comprou</option>
                                <option value="Ganhou de familiares">Ganhou de familiares</option>
                                <option value="Ganhou de amigos">Ganhou de amigos</option>
                                <option value="Acesso sem autorização (pegou)">Acesso sem autorização (pegou)</option>
                              </select>
                              <label for="slcPrimeiraDroga">Como conseguiu a primeira droga?</label>
                            </div>
                          </div>
                          <div class="col-4"></div>
                          <div class="row">
                            <div class="col-4 mt-2">
                              <div class="form-floating">
                                <select class="form-select" onchange="boxControl('boxOutroOfertou',1,this.id)" id="slcQuemHistoricoFamiliar" name="slcQuemHistoricoFamiliar" aria-label="Substâncias">
                                  <option value="" selected></option>
                                  <option value="Pai/Mãe">Pai/Mãe</option>
                                  <option value="Avós">Avós</option>
                                  <option value="Padrasto/Madrasta">Padrasto/Madrasta</option>
                                  <option value="Tios/Primos">Tios/Primos</option>
                                  <option value="Amigos">Amigos</option>
                                  <option value="Pessoas de confiança da família">Pessoas de confiança da família</option>
                                  <option value="Outros">Outros</option>
                                </select>
                                <label for="slcQuemHistoricoFamiliar">Quem ofertou no primeiro uso?</label>
                              </div>
                            </div>
                            <div class="col-4 mt-2 d-none" id="boxOutroOfertou">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtOutroOfertou" name="txtOutroOfertou" placeholder="Especifique">
                                <label for="txtOutroFamilia">Especifique</label>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-8 mt-2">
                              <div class="form-floating">
                                <select class="form-select" onchange="boxControl('boxOutroOfertouExperiencia',1,this.id)" id="slcExperiencia" name="slcExperiencia" aria-label="Substâncias">
                                  <option value="" selected></option>
                                  <option value="Oferta de familiares/figuras de referências">Oferta de familiares/figuras de referências</option>
                                  <option value="Curiosidade">Curiosidade</option>
                                  <option value="Influência do ambiente social">Influência do ambiente social</option>
                                  <option value="Influência de amigos">Influência de amigos</option>
                                  <option value="Problemas familiares">Problemas familiares</option>
                                  <option value="Luto">Luto</option>
                                  <option value="Dificuldades emocionais">Dificuldades emocionais</option>
                                  <option value="Outros">Outros</option>
                                </select>
                                <label for="slcExperiencia">Em relação ao seu primeiro contato com substâncias psicoativas, qual opção melhor representa sua experiência?</label>
                              </div>
                            </div>
                            <div class="col-4 mt-2 d-none" id="boxOutroOfertouExperiencia">
                              <div class="col-12 form-floating">
                                <input type="text" class="form-control" id="txtOutroOfertouExperiencia" name="txtOutroOfertouExperiencia" placeholder="Especifique">
                                <label for="txtOutroOfertouExperiencia">Especifique</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="card mt-3">
                          <div class="card-body">
                            <div class="row mt-2">
                              <legend class="col-form-label col-12 ms-2"><strong>Qual foi a trajetória de consumo? Informe em ordem numérica de experimentação as drogas que você já consumiu: </strong></legend>
                              <div class="col-3 mt-2">
                                <div class="form-floating">
                                  <select class="form-select" id="slcTrajetoria" name="slcTrajetoria" aria-label="Substâncias">
                                    <option value="" selected></option>
                                    <option value="Álcool">Álcool</option>
                                    <option value="LSD">LSD</option>
                                    <option value="Maconha">Maconha</option>
                                    <option value="Tabaco">Tabaco</option>
                                    <option value="Crack">Crack</option>
                                    <option value="Êxtase">Êxtase</option>
                                    <option value="Cocaína">Cocaína</option>
                                    <option value="Metanfetamina">Metanfetamina</option>
                                    <option value="K, Spice">K, Spice</option>
                                    <option value="Anfetamina">Anfetamina</option>
                                    <option value="Solventes">Solventes</option>
                                    <option value="Medicação psicotrópica">Medicação psicotrópica</option>
                                    <option value="Heroína">Heroína</option>
                                    <option value="Cigarro Eletrônico">Cigarro Eletrônico</option>
                                    <option value="Outro(a)">Outro(a)</option>
                                  </select>
                                  <label for="slcTrajetoria">Substância</label>
                                </div>
                              </div>
                              <div class="col-2 mt-2">
                                <div class="form-floating">
                                  <select class="form-select" id="slcRankeamento" name="slcRankeamento" aria-label="Substâncias">
                                    <option value="" selected></option>
                                    <option value="1">1º</option>
                                    <option value="2">2º</option>
                                    <option value="3">3º</option>
                                    <option value="4">4º</option>
                                    <option value="5">5º</option>
                                    <option value="6">6º</option>
                                    <option value="7">7º</option>
                                    <option value="8">8º</option>
                                    <option value="9">9º</option>
                                    <option value="10">10º</option>
                                    <option value="11">11º</option>
                                    <option value="12">12º</option>
                                    <option value="13">13º</option>
                                    <option value="14">14º</option>
                                  </select>
                                  <label for="slcRankeamento">Ranking</label>
                                </div>
                              </div>
                              <div class="col-1 mt-3">
                                <button class="btn btn-success" type="button" id="btnAddRanking" name="btnAddRanking">
                                  <i class="bi bi-plus-circle-dotted"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="row mt-2" id="bosListaRanking"></div> 
                        </div>

                        <div class="row mt-2">
                          <div class="col-5">
                            <div class="form-floating">
                              <select class="form-select" id="slcLocalExperimentacao" name="slcLocalExperimentacao" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Rua, praça, parque">Rua, praça, parque</option>
                                <option value="Casa de familiares">Casa de familiares</option>
                                <option value="Casa de amigos">Casa de amigos</option>
                                <option value="Festas, eventos, baladas, shows">Festas, eventos, baladas, shows</option>
                                <option value="Escola (banheiro, sala de aula, pátio, quadras, etc)">Escola (banheiro, sala de aula, pátio, quadras, etc)</option>
                                <option value="Restaurantes, bares">Restaurantes, bares</option>
                                <option value="Outros">Outros</option>
                              </select>
                              <label for="slcLocalExperimentacao">Qual foi o local da experimentação?</label>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 ms-2 mt-5">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>5.4	SOBRE A DEPENDÊNCIA</strong></legend>
                        </div>

                        <div class="row mt-2">
                          <div class="col-7">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxIdadeIniciou',1,this.id)" id="slcIdadeIniciou" name="slcIdadeIniciou" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="De 0 a 6 anos">De 0 a 6 anos</option>
                                <option value="De 7 a 10 anos">De 7 a 10 anos</option>
                                <option value="De 11 aos 12 anos">De 11 aos 12 anos</option>
                                <option value="De 13 aos 15 anos">De 13 aos 15 anos</option>
                                <option value="De 16 aos 17 anos">De 16 aos 17 anos</option>
                                <option value="De 18 aos 21 anos">De 18 aos 21 anos</option>
                                <option value="De 22 aos 25 anos">De 22 aos 25 anos</option>
                                <option value="De 26 aos 35 anos">De 26 aos 35 anos</option>
                                <option value="De 36 aos 45 anos">De 36 aos 45 anos</option>
                                <option value="Acima dos 46">Acima dos 46</option>
                              </select>
                              <label for="slcIdadeIniciou">Com que idade o uso se tornou cotidiano, iniciando a dependência?</label>
                            </div>
                          </div>
                          <div class="col-5 d-none" id="boxIdadeIniciou">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtDataMedicacao" name="txtDataMedicacao" placeholder="Data da Medicação">
                              <label for="txtDataMedicacao">Especifique</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-7">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxTraumasIndividuais',1,this.id)" id="slcAcontecimentos" name="slcAcontecimentos" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Separação/Divórcio de pais ou responsáveis">Separação/Divórcio de pais ou responsáveis</option>
                                <option value="Separação/Divórcio do próprio indivíduo">Separação/Divórcio do próprio indivíduo</option>
                                <option value="Luto">Luto</option>
                                <option value="Desemprego / Ausência de renda / Renda insuficiente">Desemprego / Ausência de renda / Renda insuficiente</option>
                                <option value="Desde o primeiro uso me tornei dependente">Desde o primeiro uso me tornei dependente</option>
                                <option value="Trabalho (uso de drogas durante a execução do trabalho)">Trabalho (uso de drogas durante a execução do trabalho)</option>
                                <option value="Morar em território com presença de tráfico e uso de drogas">Morar em território com presença de tráfico e uso de drogas</option>
                                <option value="Para perda de peso">Para perda de peso</option>
                                <option value="Nenhum evento específico ou não sei relacionar">Nenhum evento específico ou não sei relacionar</option>
                                <option value="Traumas individuais">Traumas individuais</option>
                              </select>
                              <label for="slcAcontecimentos">O que estava acontecendo na sua vida que te levou a dependência química?</label>
                            </div>
                          </div>

                          <div class="col-5"></div>

                          <div class="col-4 mt-2 d-none" id="boxTraumasIndividuais">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxOutroTrauma',1,this.id)" id="slcAcontecimentosTrauma" name="slcAcontecimentosTrauma" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Violência física">Violência física</option>
                                <option value="Violência sexual">Violência sexual</option>
                                <option value="Violência psicológica">Violência psicológica</option>
                                <option value="Violência praticada por parceiros afetivos">Violência praticada por parceiros afetivos</option>
                                <option value="Bullying">Bullying</option>
                                <option value="Conflitos amorosos">Conflitos amorosos</option>
                                <option value="Conflitos profissionais">Conflitos profissionais</option>
                                <option value="Questões de identidade/preconceito">Questões de identidade/preconceito</option>
                                <option value="Outros">Outros</option>
                              </select>
                              <label for="slcAcontecimentosTrauma">Qual trauma?</label>
                            </div>
                          </div>
                          <div class="col-5 mt-2 d-none" id="boxOutroTrauma">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutroTraumaAcontecimentos" name="txtOutroTraumaAcontecimentos" placeholder="Data da Medicação">
                              <label for="txtDataMedicacao">Especificar</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-7">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxRelacao',1,this.id)" id="slcRelacaoTraumas" name="slcRelacaoTraumas" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcRelacaoTraumas">Existe alguma relação entre traumas passados e o uso de drogas na sua vida?</label>
                            </div>
                          </div>
                          <div class="col-5 d-none" id="boxRelacao">
                            <div class="form-floating">
                              <select class="form-select" id="slcQualRelacaoTrauma" name="slcQualRelacaoTrauma" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Forte relação, com necessidade de intervenção imediata">Forte relação, com necessidade de intervenção imediata</option>
                                <option value="Relação moderada, com poucos impactos">Relação moderada, com poucos impactos</option>
                                <option value="Outros">Outros</option>
                              </select>
                              <label for="slcQualRelacaoTrauma">Qual relação?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-7">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxTipoAjuda',1,this.id)" id="slcBuscouAjuda" name="slcBuscouAjuda" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcBuscouAjuda">Antes do uso regular de drogas, você buscou ajuda profissional para lidar com questões pessoais?</label>
                            </div>
                          </div>
                          <div class="col-5 d-none" id="boxTipoAjuda">
                            <div class="form-floating">
                              <select class="form-select" id="slcResultadoAjuda" name="slcResultadoAjuda" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim, obtive ajuda, porém sem resultados específicos">Sim, obtive ajuda, porém sem resultados específicos</option>
                                <option value="Sim, obtive ajuda e com bons resultados">Sim, obtive ajuda e com bons resultados</option>
                                <option value="Sim, mas não obtive ajuda">Sim, mas não obtive ajuda</option>
                              </select>
                              <label for="slcResultadoAjuda">Qual o resultado da ajuda?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-4">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxFrequentouCenas',1,this.id)" id="slcFrequentouCenasAbertas" name="slcFrequentouCenasAbertas" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcDrogas">Frequentou cenas abertas de uso de drogas?</label>
                            </div>
                          </div>
                          <div class="row mt-2 d-none" id="boxFrequentouCenas">
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtQuandoFrequentouCenasAbertas" name="txtQuandoFrequentouCenasAbertas" placeholder="Data da Medicação">
                                <label for="txtQuandoFrequentouCenasAbertas">Quando?</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtQuantoTempo" name="txtQuantoTempo" placeholder="Data da Medicação">
                                <label for="txtQuantoTempo">Por quanto tempo?</label>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtCenasLocalizacao" name="txtCenasLocalizacao" placeholder="Data da Medicação">
                                <label for="txtCenasLocalizacao">Localização</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-5">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxRelacaoAjuda',1,this.id)" id="slcServicosEmergencia" name="slcServicosEmergencia" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcServicosEmergencia">Já precisou de serviço de emergência pelo uso de substâncias?</label>
                            </div>
                          </div>
                          <div class="col-6"></div>
                          
                          <div class="row d-none" id="boxRelacaoAjuda">

                            <div class="card mt-3">
                              <div class="card-body">
                                <div class="row mt-2">
                                  <legend class="col-form-label col-12 ms-2"><strong>Informações sobre os serviços de emergência</strong></legend>
                                  <div class="col-2 mt-2">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtPeriodoEmergencia" name="txtPeriodoEmergencia" placeholder="Data da Medicação">
                                      <label for="txtDataMedicacao">Período</label>
                                    </div>
                                  </div>
                                  <div class="col-2 mt-2">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtLocalEmergencia" name="txtLocalEmergencia" placeholder="Data da Medicação">
                                      <label for="txtLocalEmergencia">Local</label>
                                    </div>
                                  </div>
                                  <div class="col-3 mt-2">
                                    <div class="form-floating">
                                      <select class="form-select" id="slcAbstinencia" name="slcAbstinencia" aria-label="Substâncias">
                                        <option value="" selected></option>
                                        <option value="Sim">Sim</option>
                                        <option value="Não">Não</option>
                                      </select>
                                      <label for="slcDrogas">Abstinência pós-tratamento?</label>
                                    </div>
                                  </div>
                                  <div class="col-4 mt-2">
                                    <div class="form-floating">
                                      <input type="text" class="form-control" id="txtMotivoSaida" name="txtMotivoSaida" placeholder="Data da Medicação">
                                      <label for="txtMotivoSaida">Motivo da Saída</label>
                                    </div>
                                  </div>
                                  <div class="col-1 mt-3">
                                    <button class="btn btn-success" type="button" id="btnAddRanking" name="btnAddRanking">
                                      <i class="bi bi-plus-circle-dotted"></i>
                                    </button>
                                  </div>

                                  <div class="row mt-2" id="boxListaAjuda"></div>
                                </div>

                              </div>
                            </div>
                            
                          </div>

                        </div>

                        <div class="row mt-2">
                          <div class="col-4">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxTipoTratamento',1,this.id)" id="slcFezTratamento" name="slcFezTratamento" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcFezTratamento">Já fez tratamento para dependência química?</label>
                            </div>
                          </div>
                          <div class="col-6"></div>
                          <div class="row d-none" id="boxTipoTratamento">
                            <div class="col-7 mt-4">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkTratamentoDependencia1" name="chkTratamentoDependencia[]" value="Ambulatorial">
                                <label class="form-check-label" for="chkTratamentoDependencia1">Ambulatorial</label>
                              </div>
                              <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="checkbox" id="chkTratamentoDependencia2" name="chkTratamentoDependencia[]" value="Acolhimento terapêutico">
                                <label class="form-check-label" for="chkTratamentoDependencia2">Acolhimento terapêutico</label>
                              </div>
                              <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="checkbox" id="chkTratamentoDependencia3" name="chkTratamentoDependencia[]" value="CAPS/CAPS AD">
                                <label class="form-check-label" for="chkTratamentoDependencia3">CAPS/CAPS AD</label>
                              </div>
                              <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="checkbox" id="chkTratamentoDependencia4" name="chkTratamentoDependencia[]" value="Internação Hospitalar">
                                <label class="form-check-label" for="chkTratamentoDependencia4">Internação Hospitalar</label>
                              </div>
                            </div>
                            <div class="col-2 mt-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtVezesTratamento" name="txtVezesTratamento" placeholder="Data da Medicação">
                                <label for="txtVezesTratamento">Quantas vezes?</label>
                              </div>
                            </div>
                            <div class="col-3 mt-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtTempoTratamento" name="txtTempoTratamento" placeholder="Data da Medicação">
                                <label for="txtTempoTratamento">Por quanto tempo?</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-4">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxInternacao',1,this.id)" id="slcInternacaoCompulsoria" name="slcInternacaoCompulsoria" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcInternacaoCompulsoria">Já teve internação involuntária ou compulsória?</label>
                            </div>
                          </div>
                          <div class="row mt-2 d-none" id="boxInternacao">
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtVezesInternacaoCompulsoria" name="txtVezesInternacaoCompulsoria" placeholder="Data da Medicação">
                                <label for="txtVezesInternacaoCompulsoria">Quantas vezes?</label>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtLocalInternacaoCompulsoria" name="txtLocalInternacaoCompulsoria" placeholder="Data da Medicação">
                                <label for="txtLocalInternacaoCompulsoria">Local</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-6">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxQtdRecaidaUsoDrogas',1,this.id)" id="slcRecaidaUsoDrogas" name="slcRecaidaUsoDrogas" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcRecaidaUsoDrogas">Você já passou por processos de recaída ao uso de drogas? Se sim, quantas vezes?</label>
                            </div>
                          </div>
                          <div class="col-3 mt-2 d-none" id="boxQtdRecaidaUsoDrogas">
                            <div class="form-floating">
                              <input type="number" class="form-control" id="txtQtdRecaidaUsoDrogas" name="txtQtdRecaidaUsoDrogas" min="0" placeholder="Quantas vezes?">
                              <label for="txtQtdRecaidaUsoDrogas">Quantas vezes?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-7">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxTraumaRecaida',1,this.id)" id="slcRecaida" name="slcRecaida" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Separação/Divórcio do próprio indivíduo">Separações/Divórcios</option>
                                <option value="Luto">Luto</option>
                                <option value="Desemprego / Ausência de renda / Renda insuficiente">Desemprego / Ausência de renda / Renda insuficiente</option>
                                <option value="Desde o primeiro uso me tornei dependente">Desde o primeiro uso me tornei dependente</option>
                                <option value="Trabalho (uso de drogas durante a execução do trabalho)">Trabalho (uso de drogas durante a execução do trabalho)</option>
                                <option value="Morar em território com presença de tráfico e uso de drogas">Território de zona de conflito</option>
                                <option value="Para perda de peso">Para perda de peso</option>
                                <option value="Nenhum evento específico ou não sei relacionar">Nenhum evento específico ou não sei relacionar</option>
                                <option value="Traumas individuais">Traumas individuais</option>
                              </select>
                              <label for="slcRecaida">Que evento aconteceu na sua vida quando teve recaída?</label>
                            </div>
                          </div>

                          <div class="row mt-2 d-none" id="boxTraumaRecaida">
                            <div class="col-5">
                              <div class="form-floating">
                                <select class="form-select" onchange="boxControl('boxOutroTraumaRecaida',1,this.id)" id="slcTraumaRecaida" name="slcTraumaRecaida" aria-label="Substâncias">
                                  <option value="" selected></option>
                                  <option value="Violência física">Violência física</option>
                                  <option value="Violência sexual">Violência sexual</option>
                                  <option value="Violência psicológica">Violência psicológica</option>
                                  <option value="Violência praticada por parceiros afetivos">Violência praticada por parceiros afetivos</option>
                                  <option value="Bullying">Bullying</option>
                                  <option value="Conflitos amorosos">Conflitos amorosos</option>
                                  <option value="Conflitos profissionais">Conflitos profissionais</option>
                                  <option value="Questões de identidade/preconceito">Questões de identidade/preconceito</option>
                                  <option value="Outros">Outros</option>
                                </select>
                                <label for="slcTraumaRecaida">Qual trauma?</label>
                              </div>
                            </div>
                            <div class="col-5 d-none" id="boxOutroTraumaRecaida">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtOutroTraumaRecaida" name="txtOutroTraumaRecaida" placeholder="Outro Trauma">
                                <label for="txtOutroTraumaRecaida">Especificar</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-6">
                            <div class="form-floating">
                              <select class="form-select" onchange="boxControl('boxQtdInternacaoDesintoxicacao',1,this.id)" id="slcInternacaoDesintoxicacao" name="slcInternacaoDesintoxicacao" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                              </select>
                              <label for="slcInternacaoDesintoxicacao">Já precisou de internação hospitalar para desintoxicação? Se sim, quantas vezes?</label>
                            </div>
                          </div>
                          <div class="col-3 mt-2 d-none" id="boxQtdInternacaoDesintoxicacao">
                            <div class="form-floating">
                              <input type="number" class="form-control" id="txtQtdInternacaoDesintoxicacao" name="txtQtdInternacaoDesintoxicacao" min="0" placeholder="Quantas vezes?">
                              <label for="txtQtdInternacaoDesintoxicacao">Quantas vezes?</label>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 ms-2 mt-5">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>5.5	HISTÓRICO PRESENTE E FUTURO</strong></legend>
                        </div>

                        <div class="col-md-12 mt-4 ms-2">
                          <legend class="col-form-label col-sm-12 pt-0"><strong>Quais as consequências da dependência química em sua vida?</strong></legend>
                          <div class="row">
                            <div class="col-5">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias1" name="chkConsequencias[]" value="Vivência em situação de rua">
                                <label class="form-check-label" for="chkAgressor1">Vivência em situação de rua</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias2" name="chkConsequencias[]" value="Interrupção dos estudos/evasão escolar">
                                <label class="form-check-label" for="chkAgressor2">Interrupção dos estudos/evasão escolar</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias3" name="chkConsequencias[]" value="Sem qualificação profissional">
                                <label class="form-check-label" for="chkAgressor3">Sem qualificação profissional</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias4" name="chkConsequencias[]" value="Desemprego (demissão ou abandono)">
                                <label class="form-check-label" for="chkAgressor4">Desemprego (demissão ou abandono)</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias5" name="chkConsequencias[]" value="Isolamento social">
                                <label class="form-check-label" for="chkAgressor5">Isolamento social</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias6" name="chkConsequencias[]" value="Envolvimento em atos infracionais ou crimes">
                                <label class="form-check-label" for="chkAgressor5">Envolvimento em atos infracionais ou crimes</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias7" name="chkConsequencias[]" value="Perda/prejuízo da capacidade de autocuidado">
                                <label class="form-check-label" for="chkAgressor5">Perda/prejuízo da capacidade de autocuidado</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias8" name="chkConsequencias[]" value="Desencadeamento de transtornos mentais">
                                <label class="form-check-label" for="chkAgressor5">Desencadeamento de transtornos mentais</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias9" name="chkConsequencias[]" value="Prejuízo no desenvolvimento psicoemocional ou relacional">
                                <label class="form-check-label" for="chkAgressor5">Prejuízo no desenvolvimento psicoemocional ou relacional</label>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias10" name="chkConsequencias[]" value="Contágio por IST">
                                <label class="form-check-label" for="chkAgressor6">Contágio por IST</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias11" name="chkConsequencias[]" value="Surgimento/agravamento de outros problemas de saúde/comorbidades">
                                <label class="form-check-label" for="chkAgressor7">Surgimento/agravamento de outros problemas de saúde/comorbidades</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias12" name="chkConsequencias[]" value="Perda/venda de bens para manutenção da dependência química">
                                <label class="form-check-label" for="chkAgressor8">Perda/venda de bens para manutenção da dependência química</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias13" name="chkConsequencias[]" value="Lesões ou deficiência física em decorrência do uso">
                                <label class="form-check-label" for="chkAgressor9">Lesões ou deficiência física em decorrência do uso</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias14" name="chkConsequencias[]" value="Perda da guarda dos filhos/acolhimento institucional dos filhos">
                                <label class="form-check-label" for="chkAgressor9">Perda da guarda dos filhos/acolhimento institucional dos filhos</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias15" name="chkConsequencias[]" value="Envolvimento em prostituição">
                                <label class="form-check-label" for="chkAgressor9">Envolvimento em prostituição</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkConsequencias16" name="chkConsequencias[]" value="Gravidez indesejada">
                                <label class="form-check-label" for="chkAgressor9">Gravidez indesejada</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" onclick="boxControl('boxRompimentoVinculos',1,0)" id="chkConsequencias17" name="chkConsequencias[]" value="Rompimento dos vínculos familiares">
                                <label class="form-check-label" for="chkAgressor9">Rompimento dos vínculos familiares</label>
                              </div>

                              <div class="row" id="boxRompimentoVinculos">
                                <div class="col-md-12 mt-2 ms-4">
                                  <div class="form-check form-check">
                                    <input class="form-check-input" type="radio" name="radRompimentoVinculos" id="radRompimentoVinculos1" value="Abandono dos filhos">
                                    <label class="form-check-label" for="radRegistroCartorio1">Abandono dos filhos</label>
                                  </div>
                                  <div class="form-check form-check">
                                    <input class="form-check-input" type="radio" name="radRompimentoVinculos" id="radRompimentoVinculos2" value="Abandono dos pais/irmãos">
                                    <label class="form-check-label" for="radRegistroCartorio2">Abandono dos pais/irmãos</label>
                                  </div>
                                  <div class="form-check form-check">
                                    <input class="form-check-input" type="radio" name="radRompimentoVinculos" id="radRompimentoVinculos3" value="Abandono do (a) companheiro (a)">
                                    <label class="form-check-label" for="radRegistroCartorio2">Abandono do (a) companheiro (a)</label>
                                  </div>
                                </div>

                                <div class="col-10 mt-2 ms-4">
                                  <div class="form-floating">
                                    <textarea class="form-control" placeholder="Informações Compartilhadas entre a equipe" id="txtInformacoesCompartilhadas" name="txtInformacoesCompartilhadas" style="height: 150px;"></textarea>
                                    <label for="txtPrescricaoMedica">Informações Compartilhadas entre a equipe</label>
                                  </div>
                                </div>
                              </div>

                            </div>

                          </div>

                        </div>
                        
                        <div class="row mt-4">
                          <div class="col-5">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Tem moradia fixa e condições de autossustento">Tem moradia fixa e condições de autossustento</option>
                                <option value="Sem moradia mas com condições de autossustento">Sem moradia mas com condições de autossustento</option>
                                <option value="Tem moradia mas sem condições de autossustento">Tem moradia mas sem condições de autossustento</option>
                                <option value="Sem moradia e sem condições de autossustento">Sem moradia e sem condições de autossustento</option>
                                <option value="Estava em situação de rua e não tem condições de autossustento">Estava em situação de rua e não tem condições de autossustento</option>
                              </select>
                              <label for="slcDrogas">Como é sua situação atual em termos de moradia e estabilidade?</label>
                            </div>
                          </div>
                          <div class="col-5">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Boa saúde física e mental">Boa saúde física e mental</option>
                                <option value="Algumas preocupações, mas estável">Algumas preocupações, mas estável</option>
                                <option value="Preocupações sérias com a saúde">Preocupações sérias com a saúde</option>
                                <option value="Saúde física e mental muito comprometidas">Saúde física e mental muito comprometidas</option>
                              </select>
                              <label for="slcDrogas">Em termos de saúde física e mental, como você se sente atualmente?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          
                          <div class="col-10">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Influência positiva">Influência positiva</option>
                                <option value="Influência neutra">Influência neutra</option>
                                <option value="Influência negativa moderada">Influência negativa moderada</option>
                                <option value="Influência negativa significativa">Influência negativa significativa</option>
                              </select>
                              <label for="slcDrogas">Como você percebe a influência do ambiente ao seu redor (amigos, família, locais frequentados) em relação ao uso de drogas?</label>
                            </div>
                          </div>
                        
                        </div>

                        <div class="row mt-2">
                          <div class="col-10">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Não impactou">Não impactou</option>
                                <option value="Deixou de tomar banho e manter os cuidados de higiene pessoal (corte de unhas, cuidados com cabelo, barba, higiene bucal, etc.)">Deixou de tomar banho e manter os cuidados de higiene pessoal (corte de unhas, cuidados com cabelo, barba, higiene bucal, etc.)</option>
                                <option value="Deixou de cuidar da alimentação e perdeu muito peso">Deixou de cuidar da alimentação e perdeu muito peso</option>
                                <option value="Deixou de cuidar da saúde física, inclusive a bucal">Deixou de cuidar da saúde física, inclusive a bucal</option>
                                <option value="Total descuido a ponto de não reconhecer a própria imagem">Total descuido a ponto de não reconhecer a própria imagem</option>
                              </select>
                              <label for="slcDrogas">Como o uso de drogas impactou a sua capacidade de autocuidado?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-10">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Não impactou, consegue organizar e cumprir uma rotina">Não impactou, consegue organizar e cumprir uma rotina</option>
                                <option value="Impactou, consegue organizar uma rotina mas não consegue cumpri-la">Impactou, consegue organizar uma rotina mas não consegue cumpri-la</option>
                                <option value="Impactou, não consegue organizar uma rotina ou mesmo cumpri-la">Impactou, não consegue organizar uma rotina ou mesmo cumpri-la</option>
                                <option value="Impactou a ponto de não reconhecer o tempo/espaço">Impactou a ponto de não reconhecer o tempo/espaço</option>
                                <option value="Impactou a ponto de não conseguir cumprir compromissos pré-estabelecidos (emissão de documentos, agendamentos clínicos, etc)">Impactou a ponto de não conseguir cumprir compromissos pré-estabelecidos (emissão de documentos, agendamentos clínicos, etc)</option>
                              </select>
                              <label for="slcDrogas">Como o uso de drogas impactou a sua capacidade de auto-organização?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-10">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Não impactou, consegue manter um relacionamento saudável">Não impactou, consegue manter um relacionamento saudável</option>
                                <option value="Impactou, mantém contato esporádico mas o relacionamento é conflituoso">Impactou, mantém contato esporádico mas o relacionamento é conflituoso</option>
                                <option value="Impactou, houve rompimento do contato com a maior parte da família">Impactou, houve rompimento do contato com a maior parte da família</option>
                                <option value="Impactou, não se importa com a família e não quer manter contato">Impactou, não se importa com a família e não quer manter contato</option>
                              </select>
                              <label for="slcDrogas">Como o uso de drogas impactou as suas relações familiares?</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-2">
                          <div class="col-10">
                            <div class="form-floating">
                              <select class="form-select" id="slcHistoricoFamiliar" name="slcHistoricoFamiliar" aria-label="Substâncias">
                                <option value="" selected></option>
                                <option value="Estou otimista e buscando mudanças">Estou otimista e buscando mudanças</option>
                                <option value="Estou indeciso(a) sobre o futuro">Estou indeciso(a) sobre o futuro</option>
                                <option value="Sinto-me desafiado(a), mas disposto(a) a mudar">Sinto-me desafiado(a), mas disposto(a) a mudar</option>
                                <option value="Tenho dificuldade em visualizar uma melhoria">Tenho dificuldade em visualizar uma melhoria</option>
                              </select>
                              <label for="slcDrogas">Qual é a sua perspectiva atual em relação à superação do uso de drogas e melhoria da situação de vida?</label>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 mt-5">
                          <button class="btn btn-primary" type="button" id="btnCadIdentificacao" name="btnCadIdentificacao">
                            <i class="bi bi-check2-circle"></i> Registrar Dados Sobre o Uso
                          </button>
                        </div>

                      </div>

                    </form>
                  </div>

                  <!-- TAB MEDICAÇÃO -->
                  <div class="tab-pane fade" id="tabMedicacao" role="tabpanel" aria-labelledby="medicacao-tab">

                    <form class="row g-3" id="formMedicacao" name="formMedicacao" method="POST" enctype="multipart/form-data" autocomplete="off">
                      
                      <div class="row mt-4">
                        
                        <div class="col-2 mt-4" id="boxBotaoNovaMedicacao">
                          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colMedicacao" aria-expanded="false" aria-controls="colMedicacao">
                          <i class="bi bi-card-list"></i> Nova Medicação
                          </button>
                        </div>
                        <div class="row collapse mt-4" id="colMedicacao">

                          <div class="col-2">
                            <div class="form-floating">
                              <input type="date" class="form-control" id="txtDataMedicacaoRegistro" name="txtDataMedicacaoRegistro" placeholder="Data da Medicação">
                              <label for="txtDataMedicacaoRegistro">Data</label>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtNomeMedicacao" name="txtNomeMedicacao" placeholder="Nome da Medicação">
                              <label for="txtNomeMedicacao">Nome da medicação</label>
                            </div>
                          </div>
                          <div class="col-4"></div>
                          <div class="col-6 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtDosagemMedicacao" name="txtDosagemMedicacao" placeholder="Dosagem">
                              <label for="txtDosagemMedicacao">Dosagem</label>
                            </div>
                          </div>
                          <div class="col-4"></div>
                          <div class="col-8 mt-2">
                            <div class="form-floating">
                              <textarea class="form-control" placeholder="Prescrição" id="txtPrescricaoMedicacao" name="txtPrescricaoMedicacao" style="height: 150px;"></textarea>
                              <label for="txtPrescricaoMedicacao">Prescrição</label>
                            </div>
                          </div>
                          <div class="col-4"></div>
                          <div class="col-6 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtTempoUsoMedicacao" name="txtTempoUsoMedicacao" placeholder="Tempo de uso">
                              <label for="txtTempoUsoMedicacao">Tempo de uso</label>
                            </div>
                          </div>
                          <div class="col-7"></div>
                          <div class="col-6 mt-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtUnidadeSaudeMedicacao" name="txtUnidadeSaudeMedicacao" placeholder="Unidade de saúde que prescreveu">
                              <label for="txtUnidadeSaudeMedicacao">Unidade de saúde que prescreveu</label>
                            </div>
                          </div>
                          <div class="col-8 mt-2">
                            <div class="form-floating">
                              <textarea class="form-control" placeholder="Observações" id="txtObservacoesMedicacao" name="txtObservacoesMedicacao" style="height: 150px;"></textarea>
                              <label for="txtObservacoesMedicacao">Observações</label>
                            </div>
                          </div>
                         
                          <div class="col-12 mt-3">
                            <button class="btn btn-primary" type="button" id="btnCadMedicacao" name="btnCadMedicacao">
                              <i class="bi bi-plus-circle-dotted"></i> Registrar Medicação
                            </button>
                          </div>

                        </div>

                      </div>

                      <div class="row mt-3" id="boxListaMedicacoes"></div>

                    </form>

                    </div>
                  
                  </div>
                
                </div>          

              </div>

            </div>

          </div>

<!-- FIM DO CARD ANAMNESE -->









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

  </main><!-- End #main -->
