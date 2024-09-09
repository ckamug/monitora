<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dados do Acolhido - HUB</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
        <li class="breadcrumb-item"><a href="/coed/hub">Hub</a></li>
        <li class="breadcrumb-item active">Acolhido Hub</li>
      </ol>
    </nav>
  </div>

  <div class="card col-md-12">
    <div class="card-body">
      <h5 class="card-title">Formulário de Cadastro do Acolhido</h5>
      <input type="hidden" name="hidIdAcolhido" id="hidIdAcolhido" value="<?php echo $url[3] ?>" >

      <!-- INICIO BOX CADASTRO -->
      <div class="tab-content pt-2" id="borderedTabContent">

        <div class="tab-pane fade show active" id="tabForm" role="tabpanel" aria-labelledby="home-tab">

        <!-- Floating Labels Form -->
          <form class="row g-3 needs-validation mt-3" id="formAcolhido" name="formAcolhido" method="POST" autocomplete="off">

            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control" id="txtNomeCompleto" name="txtNomeCompleto" placeholder="Nome Completo">
                <div class="invalid-feedback">Informe o Nome Completo</div>
                <label for="txtNomeFantasia">Nome Completo</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-floating">
                <input type="date" class="form-control" id="txtDataNascimento" name="txtDataNascimento" placeholder="Data de Nascimento">
                <div class="invalid-feedback">Informe a Data de Nascimento</div>
                <label for="txtNumero">Data de Nascimento</label>
              </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-2">
              <div class="form-floating">
                <input type="date" class="form-control" id="txtDataEntrada" name="txtDataEntrada" placeholder="Data de Entrada">
                <div class="invalid-feedback">Data de Entrada</div>
                <label for="txtNumero">Data de Entrada</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-floating">
                <input type="date" class="form-control" id="txtDataSaida" name="txtDataSaida" placeholder="Data de Saída">
                <div class="invalid-feedback">Data de Saída</div>
                <label for="txtNumero">Data de Saída</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-floating">
                <select class="form-select" id="slcTipoDesligamento" name="slcTipoDesligamento" aria-label="Tipo de Desligamento">
                  <option selected disabled value="">Escolha</option>
                  <option value="Em Acolhimento">Em Acolhimento</option>
                  <option value="Alta Terapêutica">Alta Terapêutica</option>
                  <option value="Alta Solicitada">Alta Solicitada</option>
                  <option value="Alta Administrativa">Alta Administrativa</option>
                  <option value="Evasão">Evasão</option>
                  <option value="Reserva Finalizada">Reserva Finalizada</option>
                </select>
                <label for="slcIdentidadeGenero">Tipo de Desligamento</label>
              </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-12">
              
              <div class="col-md-4" style="position:relative; float:left;">
                <div class="form-floating">
                  <select class="form-select" id="slcAntesHub" name="slcAntesHub" onchange="trataLocalAntesHub(this.value)" aria-label="Local antes do HUB">
                    <option selected disabled value="">Escolha</option>
                    <option value="Casa Própria">Casa Própria</option>
                    <option value="Casa de Parente/Conhecido">Casa de Parente/Conhecido</option>
                    <option value="Situação de Rua">Situação de Rua</option>
                    <option value="Abrigo">Abrigo</option>
                  </select>
                  <label for="slcIdentidadeGenero">Onde estava antes de ser encaminhado pelo HUB?</label>
                </div>
              </div>

              <div class="col-md-3 d-none ms-2" id="boxLocalSituacaoRua" style="position:relative; float:left;">
                <div class="form-floating">
                  <select class="form-select" id="slcLocalSituacaoRua" name="slcLocalSituacaoRua" aria-label="Local quando em situação de rua">
                    <option selected disabled value="">Escolha</option>
                    <option value="Armênia">Armênia</option>
                    <option value="Brás">Brás</option>
                    <option value="CEAGESP">CEAGESP</option>
                    <option value="Centro">Centro</option>
                    <option value="Cidade Líder">Cidade Líder</option>
                    <option value="Glicério">Glicério</option>
                    <option value="Itaquera">Itaquera</option>
                    <option value="Lapa">Lapa</option>
                    <option value="Pátio do Colégio">Pátio do Colégio</option>
                    <option value="Região da Luz">Região da Luz</option>
                    <option value="Sé">Sé</option>
                    <option value="Outro">Outro</option>
                  </select>
                  <label for="slcIdentidadeGenero">Qual o local?</label>
                </div>
              </div>
            
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <select class="form-select" id="slcAposDesligamento" name="slcAposDesligamento" aria-label="Local após desligamento">
                  <option selected disabled value="">Escolha</option>
                  <option value="Alta Terapêutica">República</option>
                  <option value="Casa Própria">Casa Própria</option>
                  <option value="Casa de Parente/Conhecido">Casa de Parente/Conhecido</option>
                  <option value="Voltou a situação de rua">Voltou a situação de rua</option>
                </select>
                <label for="slcIdentidadeGenero">Para onde foi após o desligamento?</label>
              </div>
            </div>

            <div class="col-md-10">
              <legend class="col-form-label col-sm-12 pt-0">Tipo de droga que consumia</legend>
              <div class="col-sm-10">

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia1" name="chkSubstanciaConsumia[]" value="Álcool">
                  <label class="form-check-label" for="chkSubstanciaConsumia1">Álcool</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia2" name="chkSubstanciaConsumia[]" value="Maconha">
                  <label class="form-check-label" for="chkSubstanciaConsumia2">Maconha</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia3" name="chkSubstanciaConsumia[]" value="Cocaína">
                  <label class="form-check-label" for="chkSubstanciaConsumia3">Cocaína</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia4" name="chkSubstanciaConsumia[]" value="Crack">
                  <label class="form-check-label" for="chkSubstanciaConsumia4">Crack</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia5" name="chkSubstanciaConsumia[]" value="Êxtase">
                  <label class="form-check-label" for="chkSubstanciaConsumia5">Êxtase</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia6" name="chkSubstanciaConsumia[]" value="Anfetaminas">
                  <label class="form-check-label" for="chkSubstanciaConsumia6">Anfetaminas</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia7" name="chkSubstanciaConsumia[]" value="LSD">
                  <label class="form-check-label" for="chkSubstanciaConsumia7">LSD</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia8" name="chkSubstanciaConsumia[]" value="K2">
                  <label class="form-check-label" for="chkSubstanciaConsumia8">K2</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia9" name="chkSubstanciaConsumia[]" value="K4">
                  <label class="form-check-label" for="chkSubstanciaConsumia9">K4</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chkSubstanciaConsumia10" name="chkSubstanciaConsumia[]" value="K9">
                  <label class="form-check-label" for="chkSubstanciaConsumia10">K9</label>
                </div>

              </div>
            </div>

            <div class="text-center mt-5 col-md-11" id="boxBotoes">
              <button type="submit" class="btn btn-primary">Cadastrar informações</button>
            </div>
          </form>
        
        </div>

      </div>

    </div>

</main>

<div class="modal fade" id="confirmacaoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="tituloModal"></div>
      <div class="modal-body" id="corpoModal"></div>
      <div class="modal-footer" id="boxBotoesModal"></div>
    </div>
  </div>
</div>