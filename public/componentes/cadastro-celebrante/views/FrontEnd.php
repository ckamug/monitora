<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>OSC Celebrante</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item"><a href="/coed/celebrante">Celebrantes</a></li>
          <li class="breadcrumb-item active">Cadastro de Celebrante</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card col-md-10">
            <div class="card-body">
              <h5 class="card-title">Formulário de Cadastro de Celebrante</h5>
              <input type="hidden" name="hidIdCelebrante" id="hidIdCelebrante" value="<?php echo $url[3] ?>">
              <!-- Floating Labels Form -->
              <form class="row g-3 needs-validation" id="formCelebrante" name="formCelebrante" method="POST" autocomplete="off">
                <div class="col-md-10">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtNomeFantasia" name="txtNomeFantasia" placeholder="Nome Fantasia" required>
                    <div class="invalid-feedback">Informe o Nome Fantasia</div>
                    <label for="txtNomeFantasia">Nome Fantasia</label>
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtRazaoSocial" name="txtRazaoSocial" placeholder="Razão Social" required>
                    <div class="invalid-feedback">Informe a Razão Social</div>
                    <label for="txtRazaoSocial">Razão Social</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCnpj" name="txtCnpj" placeholder="CNPJ" required>
                    <div class="invalid-feedback">Informe o CNPJ</div>
                    <label for="txtCnpj">CNPJ</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCnae" name="txtCnae" placeholder="CNAE" required>
                    <div class="invalid-feedback">Informe o CNAE</div>
                    <label for="txtCnae">CNAE</label>
                  </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCep" name="txtCep" placeholder="CEP" required>
                    <div class="invalid-feedback">Informe o CEP</div>
                    <label for="txtCep">CEP</label>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" placeholder="Endereço" required>
                    <div class="invalid-feedback">Informe o Endereço</div>
                    <label for="txtEndereco">Endereço</label>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtNumero" name="txtNumero" placeholder="Nº" required>
                    <div class="invalid-feedback">Informe o Número</div>
                    <label for="txtNumero">Nº</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtComplemento" name="txtComplemento" placeholder="Complemento" required>
                    <div class="invalid-feedback">Informe o Complemento</div>
                    <label for="txtBairro">Complemento</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtBairro" name="txtBairro" placeholder="Bairro" required>
                    <div class="invalid-feedback">Informe o Bairro</div>
                    <label for="txtBairro">Bairro</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating" id="boxMunicipios"></div>
                </div>
                <div class="col-md-7">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="E-mail" required>
                    <div class="invalid-feedback">Informe o E-mail</div>
                    <label for="txtEmail">E-mail</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtTelefone" name="txtTelefone" placeholder="Telefone" required>
                    <div class="invalid-feedback">Informe o Telefone</div>
                    <label for="txtTelefone">Telefone</label>
                  </div>
                </div>

                <!-- CADASTRO DE REPASSES DE RUBRICAS -->

                <?php if(base64_decode($_SESSION["usr"])==1 OR base64_decode($_SESSION["usr"])==18 OR base64_decode($_SESSION["usr"])==22){ ?>

                <h5 class="card-title">Cadastro de Repasse de Rubrica - Financeiro</h5>
                    
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtRh" name="txtRh" placeholder="Recursos Humanos">
                    <label for="txtResponsavel">Recursos Humanos</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtCusteio" name="txtCusteio" placeholder="Custeio">
                    <label for="txtCpf">Custeio</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtServicosTerceiros" name="txtServicosTerceiros" placeholder="Serviços Terceiros">
                    <label for="txtCpf">Serviços Terceiros</label>
                  </div>
                </div>

                <?php } ?>

                <!-- FIM DO CADASTRO DE REPASSES DE RUBRICAS -->


                <div class="text-center mt-5 col-md-11" id="boxBotoes">
                  <button type="submit" class="btn btn-primary">Cadastrar Celebrante</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
        </div>
      </div>

  </main><!-- End #main -->