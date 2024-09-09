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
    <input type="hidden" name="hidEntrada" id="hidEntrada" value="<?php echo $url[3] ?>" >
    
    <section class="section">
      <div class="row">
        
        <div class="col-lg-12">

          <div class="card">
            
            <div class="card-body">
              <h5 class="card-title">Acolhimento <span id="txtAcolhido"></span></h5>
              
              <div class="row mt-3" id="boxListaAcolhimentos">

                <div class="tab">
                  
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="equipeTecnica-tab" data-bs-toggle="tab" data-bs-target="#tabEquipeTecnica" type="button" role="tab" aria-controls="equipeTecnica" aria-selected="true" onclick="listaAcoes('Et')">Equipe Técnica</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="psicologia-tab" data-bs-toggle="tab" data-bs-target="#tabPsicologia" type="button" role="tab" aria-controls="psicologia" aria-selected="true" onclick="listaAcoes('Psi');carregaTiposAtendimentos();">Psicologia</button>
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
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delisgamento-tab" data-bs-toggle="tab" data-bs-target="#tabDesligamento" type="button" role="tab" aria-controls="desligamento" aria-selected="true">Desligamento</button>
                      </li>
                  </ul>
                  
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <!-- TAB EQUIPE TÉCNICA -->  
                    <div class="tab-pane fade show active" id="tabEquipeTecnica" role="tabpanel" aria-labelledby="equipeTecnica-tab">
                      <h3>Anotações da Equipe Técnica</h3>
                      
                      <form class="row g-3" id="formEquipeTecnica" name="formEquipeTecnica" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-2 mt-4" id="boxBotaoNovaAnotacaoEquipeTecnica">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesTecnicas" aria-expanded="false" aria-controls="colAnotacoesTecnicas">
                            <i class="bi bi-card-list"></i> Nova Anotação
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesTecnicas">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDataAnotacaoTecnica" name="txtDataAnotacaoTecnica" placeholder="Data da Anotação Técnica" readonly value="<?php echo date("d/m/Y") ?>">
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
                                <input type="text" class="form-control" id="txtDocumentoEquipeTecnica" name="txtDocumentoEquipeTecnica" placeholder="Número do CRP" readonly value="277.447.658-50">
                                <label for="floatingName">Nº CRP</label>
                              </div>                        
                            </div>
                            <div class="col-7 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição da Equipe Técnica" id="txtDescricaoAcaoEquipeTecnica" name="txtDescricaoAcaoEquipeTecnica" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Descrição da Ação</label>
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
                      <h3>Anotações da Psicologia</h3>
                      
                      <form class="row g-3" id="formPsicologia" name="formPsicologia" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-2 mt-4" id="boxBotaoNovaAnotacaoPsicologia">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesPsicologia" aria-expanded="false" aria-controls="colAnotacoesPsicologia">
                            <i class="bi bi-card-list"></i> Nova Anotação
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesPsicologia">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDataAnotacaoPsicologia" placeholder="Data da Anotação Técnica" readonly value="<?php echo date("d/m/Y") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnicoPsicologia" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating" id="boxTiposAtendimentosPsicologia"></div>
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoPsicologia" placeholder="Número do CRP" readonly value="277.447.658-50">
                                <label for="floatingName">Nº CRP</label>
                              </div>                        
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição da Psicologia" id="txtDescricaoAcaoPsicologia" name="txtDescricaoAcaoPsicologia" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Descrição da Ação</label>
                              </div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <input type="file" class="form-control" id="uplDocPsicologia">
                              </div>
                            </div>
                            <div class="col-7 mt-2">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesPsicologia" aria-expanded="false" aria-controls="colAnotacoesPsicologia" onclick="cadastraAcaoPsi()">
                                <i class="bi bi-check2-circle"></i> Registrar Ação
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>

                      <div class="row" id="boxListaAcoesPsi"></div>

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

                          </div>
                          
                        </div>

                        <div class="text-center mt-5 col-md-11" id="boxBotaoDadosSensiveis">
                          <button class="btn btn-success mt-5 mb-3 mx-0" onclick="cadastraDadosSensiveis()">Registrar dados sensíveis</button>
                        </div>
                      </form>

                    </div>
                    
                    <!-- TAB SERVIÇOS SOCIAIS -->
                    <div class="tab-pane fade" id="tabServicoSocial" role="tabpanel" aria-labelledby="servicoSocial-tab">
                      <h3>Anotações do Serviço Social</h3>
                      
                      <form class="row g-3" id="formServicoSocial" name="formServicoSocial" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="row">
                          <div class="col-2 mt-4" id="boxBotaoNovaAnotacaoPsicologia">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesServicoSocial" aria-expanded="false" aria-controls="colAnotacoesServicoSocial">
                            <i class="bi bi-card-list"></i> Nova Anotação
                            </button>
                          </div>
                          <div class="row collapse mt-4" id="colAnotacoesServicoSocial">

                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDataAnotacaoServicoSocial" placeholder="Data da Anotação Técnica" readonly value="<?php echo date("d/m/Y") ?>">
                                <label for="floatingName">Data</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtNomeTecnicoServicoSocial" placeholder="Nome do Técnico" readonly value="<?php echo $_SESSION["nm"] ?>">
                                <label for="floatingName">Nome do técnico</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-floating" id="boxTiposAtendimentosServicoSocial"></div>                   
                            </div>
                            <div class="col-2">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtDocumentoServicoSocial" placeholder="Número do CRP" readonly value="277.447.658-50">
                                <label for="floatingName">Nº CRP</label>
                              </div>                        
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <textarea class="form-control" placeholder="Descrição da Ação" id="txtDescricaoAcaoServicoSocial" name="txtDescricaoAcaoServicoSocial" style="height: 150px;"></textarea>
                                <label for="floatingTextarea">Descrição da Ação</label>
                              </div>
                            </div>
                            <div class="col-10 mt-2">
                              <div class="form-floating">
                                <input type="file" class="form-control" id="uplDocServicoSocial">
                              </div>
                            </div>
                            <div class="col-7 mt-2">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colAnotacoesServicoSocial" aria-expanded="false" aria-controls="colAnotacoesServicoSocial" onclick="cadastraAcaoSs()">
                                <i class="bi bi-check2-circle"></i> Registrar Ação
                              </button>
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
                        
                        <div class="col-3 mt-3">
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
                                <input class="form-check-input" type="checkbox" id="chkMotivosDesligamentoQualificado6" name="chkMotivosDesligamentoQualificado[]" value="Condições de autos sustento">
                                <label class="form-check-label" for="chkMotivosDesligamentoQualificado6">Condições de autos sustento</label>
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

                            </div>
                            
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

  </main><!-- End #main -->