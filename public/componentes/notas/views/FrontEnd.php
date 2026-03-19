<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Prestação de Contas - Notas Fiscais</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item" id="boxBreadCrumbs"><a href="/coed/prestacoes">Prestação de Contas</a></li>
          <li class="breadcrumb-item active">Notas Fiscais</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card d-none" id="boxCabecalhoInfoOsc">
            <div class="card-body">
              <h5 class="card-title"><span class="col-lg-12 fs-5" id="infoOsc"></span></h5>
            </div>
          </div>
        
          <div class="card d-none" id="boxCabecalho">
            
            <div class="card-body">
              
              <h5 class="card-title">Cabeçalho OSC - <span class="col-lg-12 fs-5" id="nomeOsc"></span></h5>
              
              <div class="row p-2">
                <div style="width: 350px;"><strong>Tipo de Repasse:</strong> <span id="boxTipoRepasse"></span></div>
                <div style="width: 350px;"><strong>Mês de referência:</strong> <span id="boxMesReferencia"></span></div>
                <div style="width: 350px;"><strong>Vagas disponibilizadas:</strong> <span id="boxVagasDisponibilizadas"></span></div>
                <div style="width: 350px;" id="boxProvisao">
                    <div class="form-floating" style="width: 280px; margin-top:-20px;">
                      <input type="text" class="form-control d-none" id="txtValorProvisao" name="txtValorProvisao" placeholder="Informe o valor da Provisão">
                      <label for="txtDataNota">Informe aqui o valor da Provisão</label>
                    </div>
                </div>
              </div>
              <div class="row p-2">
                <div style="width: 350px;"><strong>Valor de Repasse:</strong> R$ <span id="boxValorRepasse"></span></div>
                <div style="width: 350px;"><strong>Valor Executado:</strong> R$ <span id="boxValorExecutado"></span></div>
                <div style="width: 350px;"><strong>Valor Glosado:</strong> R$ <span id="boxValorGlosado"></span></div>
                <div style="width: 350px;"><strong>Valor Não Executado:</strong> R$ <span id="boxValorNaoExecutado"></span></div>
              </div>

              <hr></hr>

              <div class="row p-2">
                <div style="width: 300px;"><strong>Rúbrica</strong></div>
                <div style="width: 250px;"><strong>Previsto</strong></div>
                <div style="width: 600px;"><strong>Executado</strong></div>
              </div>
              <div class="row p-1 ps-2">
                <div style="width: 300px;">Recursos Humanos</div>
                <div style="width: 250px;">R$ <span id="boxPrevistoRH"></span><input type="text" id="txtPrevistoRh" name="txtPrevistoRh" hidden></div>
                <div style="width: 600px;">R$ <span id="boxExecutadoRH"></span><input type="text" id="txtExecutadoRh" name="txtExecutadoRh" hidden></div>
              </div>
              <div class="row p-1 ps-2">
                <div style="width: 300px;">Custeio</div>
                <div style="width: 250px;">R$ <span id="boxPrevistoCusteio"></span><input type="text" id="txtPrevistoCusteio" name="txtPrevistoCusteio" hidden></div>
                <div style="width: 600px;">R$ <span id="boxExecutadoCusteio"></span><input type="text" id="txtExecutadoCusteio" name="txtExecutadoCusteio" hidden></div>
              </div>
              <div class="row p-1 ps-2">
                <div style="width: 300px;">Serviços Terceiros</div>
                <div style="width: 250px;">R$ <span id="boxPrevistoTerceiros"></span><input type="text" id="txtPrevistoTerceiros" name="txtPrevistoTerceiros" hidden></div>
                <div style="width: 600px;">R$ <span id="boxExecutadoTerceiros"></span><input type="text" id="txtExecutadoTerceiros" name="txtExecutadoTerceiros" hidden></div>
              </div>

              <div class="row col-12" id="boxAlteraPrevisto"></div>

            </div>

          </div>
        
          <div class="card d-none" id="boxRegistrarNota">
            <div class="card-body">
              <h5 class="card-title" id="tituloNf">Registrar Nota Fiscal</h5>
              
              <form class="row g-3" id="formNotaFiscal" name="formNotaFiscal" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="hidIdPrestacao" id="hidIdPrestacao" value="<?php echo $url[3] ?>" >
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="txtDataNota" name="txtDataNota" placeholder="Data da Nota Fiscal">
                    <div class="invalid-feedback">Informe a data da Nota Fiscal</div>
                    <label for="txtDataNota">Data da NF</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtNumeroNotaFiscal" name="txtNumeroNotaFiscal" placeholder="Detalhes do documento">
                    <div class="invalid-feedback">Informe os detalhes do documento</div>
                    <label for="txtNumeroNotaFiscal">Detalhes do documento</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating" id="boxCategorias"></div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating" id="boxSubcategorias">
                    <select class="form-select" id="slcSubCategorias" name="slcSubCategorias" aria-label="Subcategorias" disabled></select>
                    <label for="slcSubcategorias">Subcategorias</label>
                  </div>
                </div>
                <div class="col-md-2 pt-3" id="boxAnaliseCoed"></div>
                <div class="col-md-2">
                  <input type="hidden" id="txtValorNotaEdicao" name="txtValorNotaEdicao" value="0"> <!-- CAMPO UTILIZADO PARA VALIDAÇÃO DA RUBRICA QUANDO NOTA EDITADA ( FUNÇÃO VALIDARUBRICA() ) -->
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtValorNotaFiscal" name="txtValorNotaFiscal" placeholder="Valor da Nota Fiscal">
                    <div class="invalid-feedback">Informe o Valor da Nota Fiscal</div>
                    <label for="txtValorNotaFiscal">Valor da Nota Fiscal</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="txtDataPagamento" name="txtDataPagamento" placeholder="Data de pagamento da NF">
                    <div class="invalid-feedback">Informe a data de pagamento da Nota Fiscal</div>
                    <label for="txtDataNota">Data de Pagamento</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div id="boxEnvio">
                    <input class="form-control p-3" type="file" id="uplNf" name="uplNf">
                  </div>
                </div>

            
          <div class="col-md-12">

              <div class="col-md-3">
                <div class="form-floating" id="boxStatusNotas"></div>
              </div>
              
              <div class="col-md-11 mt-2 d-none" id="boxMotivoGlosa">
                    
                <div class="col-md-8" id="boxCampoMotivoGlosa">
                  <div class="form-floating mt-3">
                    <textarea class="form-control" placeholder="Escreva o motivo da glosa" id="txtMotivoGlosa" name="txtMotivoGlosa" style="height: 100px"></textarea>
                    <label for="txtMotivoGlosa">Informe o motivo da glosa</label>
                  </div>
                </div>

                <div class="col-md-2 d-none mt-1" id="boxValorGlosa">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtValorGlosa" name="txtValorGlosa" placeholder="Valor da Glosa">
                    <div class="invalid-feedback">Informe o Valor da Glosa</div>
                    <label for="txtValorNotaFiscal">Valor da Glosa</label>
                  </div>
                </div>

              </div>

              <div class="col-md-8 mt-2 d-none" id="boxRessalva">
                <h5 class="card-title ms-2" id="tituloRessalva">Ressalva</h5>    
                <div class="col-md-8" id="boxCampoRessalva">
                  <div class="form-floating mt-3">
                    <textarea class="form-control" placeholder="Escreva a ressalva" id="txtRessalva" name="txtRessalva" style="height: 100px"></textarea>
                    <label for="txtRessalva">Informe a ressalva</label>
                  </div>
                </div>
                <div class="col-md-8 ms-2 mt-2 d-none" id="boxTextoRessalva">
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <p id="textoRessalva"></p>
                    <hr>
                  </div>
                </div>

              </div>
              <div id="boxTeste" class="mt-2 mb-5 ms-2 col-md-8"></div>

              <div class="col-md-8 ms-2 mt-2 d-none" id="boxTextoMotivoGlosa1">

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <h4 class="alert-heading">Motivo da glosa</h4>
                  <p id="textoMotivoGlosa"></p>
                  <hr>
                  <p class="mb-0" id="dataMotivoGlosa">data</p> 
                </div>

              </div>

              <div class="col-md-8 mt-2 d-none mt-2 ms-2" id="boxItensGlosa"></div>
          </div>  


                <div class="text-center mt-5 col-md-11" id="boxBotoes">
                  <button type="submit" id="btnRegistrar" class="btn btn-primary">Registrar NF</button>
                  <button type="button" class="btn btn-secondary" onclick="cancelaCadNota(0)">Cancelar</button>
                </div>
              </form>


            </div>
          </div>

          <div class="card">
            <div class="card-body">
            <h5 class="card-title">Notas Fiscais Registradas</h5>
              <div id="boxListaNotasFiscais"></div>
            </div>
          </div>

          <div class="card d-none" id="boxDisponibilizaPrestacao">
            <div class="card-body text-end p-2">
              <button type="button" class="btn btn-success" id="btnDisponibilizaPrestacao"><i class="bi bi-upload me-1"></i> Finalizar Prestação</button>
            </div>
          </div>

          <div class="card d-none" id="boxLiberaPrestacao">
            <div class="card-body text-start p-2">
              <button type="button" class="btn btn-warning" id="btnLiberaPrestacao"><i class="bi bi-key me-1"></i> Liberar prestação</button>
            </div>
          </div>

          <div class="card" id="boxDocsComplementares">
            <div class="card-body">
              <h5 class="card-title" id="tituloNf">Documentos Complementares</h5>

              <form class="row g-3" id="formDocComplementar" name="formDocComplementar" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="hidIdPrestacaoDoc" id="hidIdPrestacaoDoc" value="<?php echo $url[3] ?>" >
                <div class="col-md-4">
                  <div id="boxEnvioDocComplementar">
                    <input class="form-control p-3" type="file" id="uplDoc" name="uplDoc">
                  </div>
                </div>
              </form>

              <hr class="mt-2"></hr>
              <p class="h5">Apontamentos e Justificativas dos documentos complementares</p>
              <div class="form-floating col-md-6" id="boxTxtMensagem">
                <textarea class="form-control" name="txtMensagem" id="txtMensagem" placeholder="Mensagem" id="floatingTextarea" style="height: 100px;"></textarea>
                <label for="floatingTextarea">Informe aqui o apontamento ou justificativa dos documentos complementares</label>
              </div>
              <div class="mt-1 col-md-5">
                <button type="button" id="btnEnviarMensagem" class="btn btn-success" onclick="registraApontamentoDocComp('<?php echo $url[3] ?>')">Enviar mensagem</button>
              </div>
              <div class="mt-2 col-md-11" id="boxListaApontamentosDocComp"></div>

            </div>
          </div>

        </div>
      </div>
    </section>

    <div class="modal fade" id="confirmacaoModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-gradient" id="tituloModal"></div>
          <div class="modal-body" id="corpoModal"></div>
          <div class="modal-footer" id="boxBotoesModal"></div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->