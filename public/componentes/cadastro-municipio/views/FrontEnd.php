<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Municípios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item"><a href="/coed/municipio">Municípios</a></li>
          <li class="breadcrumb-item active">Cadastro de Município</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card col-md-10">
            <div class="card-body">
              <h5 class="card-title">Formulário de Cadastro de Município</h5>
              <input type="hidden" name="hidIdMunicipio" id="hidIdMunicipio" value="<?php echo $url[3] ?>" >
              <!-- Floating Labels Form -->
              <form class="row g-3 needs-validation" id="formMunicipio" name="formMunicipio" method="POST" autocomplete="off">
              
              <div class="col-md-8 d-none" id="boxStatus">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="chkStatus" name="chkStatus">
                  <label class="form-check-label" for="chkStatus" id="lblStatus">Ativo</label></div>
              </div>              
              
              <div class="col-md-8">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtOrgaoPublico" name="txtOrgaoPublico" value="" placeholder="Órgão Público" required>
                    <div class="invalid-feedback">Informe o Órgão Público</div>
                    <label for="txtOrgaoPublico">Órgão Público</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCnpj" name="txtCnpj" value="" placeholder="CNPJ" required>
                    <div class="invalid-feedback">Informe o CNPJ</div>
                    <label for="txtCnpj">CNPJ</label>
                  </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCep" name="txtCep" value="" placeholder="CEP" required>
                    <div class="invalid-feedback">Informe o CEP</div>
                    <label for="txtCep">CEP</label>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" value="" placeholder="Endereço" required>
                    <div class="invalid-feedback">Informe o Endereço</div>
                    <label for="txtEndereco">Endereço</label>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtNumero" name="txtNumero" value="" placeholder="Nº" required>
                    <div class="invalid-feedback">Informe o Número</div>
                    <label for="txtNumero">Nº</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtComplemento" name="txtComplemento" value="" placeholder="Complemento" required>
                    <div class="invalid-feedback">Informe o Complemento</div>
                    <label for="txtNumero">Complemento</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtBairro" name="txtBairro" value="" placeholder="Bairro" required>
                    <div class="invalid-feedback">Informe o Bairro</div>
                    <label for="txtBairro">Bairro</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating" id="boxMunicipios"></div>
                </div>
                <div class="col-md-10">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtTecnicoReferencia" name="txtTecnicoReferencia" value="" placeholder="Técnico de Referência" required>
                    <div class="invalid-feedback">Informe o nome do Técnico de Referência</div>
                    <label for="txtTecnicoReferencia">Técnico de Referência</label>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="" placeholder="E-mail institucional" required>
                    <div class="invalid-feedback">Informe o E-mail</div>
                    <label for="txtEmail">E-mail institucional</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtTelefone" name="txtTelefone" value="" placeholder="Telefone" required>
                    <div class="invalid-feedback">Informe o Telefone</div>
                    <label for="txtTelefone">Telefone</label>
                  </div>
                </div>
                <div class="text-center mt-5 col-md-11" id="boxBotoes">
                  <button type="submit" class="btn btn-primary">Cadastrar Município</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
        </div>
      </div>

  </main><!-- End #main -->