<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  session_start();
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>OSC Executora</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item"><a href="/coed/executora">OSC Executora</a></li>
          <li class="breadcrumb-item active">Cadastro de OSC Executora</li>
        </ol>
      </nav>
    </div>

    <div class="card col-md-10">
            <div class="card-body">
              <h5 class="card-title">Formulário de Cadastro de OSC Executora</h5>
              <input type="hidden" name="hidIdExecutora" id="hidIdExecutora" value="<?php echo $url[3] ?>">
              <!-- Floating Labels Form -->
              <form class="row g-3 needs-validation" id="formExecutora" name="formExecutora" method="POST" autocomplete="off">
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
                    <input type="text" class="form-control" id="txtCnae" name="txtCnae" placeholder="CNAE">
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
                    <input type="text" class="form-control" id="txtComplemento" name="txtComplemento" placeholder="Complemento">
                    <div class="invalid-feedback">Informe o complemento</div>
                    <label for="txtNumero">Complemento</label>
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
                <div class="col-md-3 p-2">
                  <div class="form-floating" id="boxRegiaoAdministrativa"></div>
                </div>
                <div class="col-md-4 p-2">
                  <div class="form-floating" id="boxRegiaoMetropolitana"></div>
                </div>
                <div class="col-md-3 p-2">
                  <div class="form-floating" id="boxMacroregiao"></div>
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

                <!-- CADASTRO DE SERVIÇOS -->
                <h5 class="card-title">Serviços de Atendimento</h5>
                <div class="col-md-3">
                  <legend class="col-form-label col-sm-12 pt-0">Gêneros atendidos</legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkGenero1" name="chkGenero[]" value="Masculino">
                      <label class="form-check-label" for="chkGenero1">Masculino</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkGenero2" name="chkGenero[]" value="Feminino">
                      <label class="form-check-label" for="chkGenero2">Feminino</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkGenero3" name="chkGenero[]" value="LGBTQIA+">
                      <label class="form-check-label" for="chkGenero3">LGBTQIA+</label>
                    </div>
                    
                  </div>
                </div>

                <div class="col-md-9">
                  <legend class="col-form-label col-sm-12 pt-0">Serviços</legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico1" name="chkServico[]" value="1">
                      <label class="form-check-label" for="chkServico1">Serviço de atendimento terapêutico comunitário</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico2" name="chkServico[]" value="2">
                      <label class="form-check-label" for="chkServico2">Serviço de atendimento terapêutico residencial</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico3" name="chkServico[]" value="3">
                      <label class="form-check-label" for="chkServico3">Serviço de atendimento terapêutico híbrido</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico4" name="chkServico[]" value="4">
                      <label class="form-check-label" for="chkServico4">Serviço de atendimento em república</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico5" name="chkServico[]" value="5">
                      <label class="form-check-label" for="chkServico5">Serviço de atendimento institucional em Casa de Passagem</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="chkServico6" name="chkServico[]" value="6">
                      <label class="form-check-label" for="chkServico6">Serviço de apoio e suporte aos familiares e ex-acolhidos(as) da rede do Programa Recomeço</label>
                    </div>
                    
                  </div>
                </div>

                
                <!-- CADASTRO DE VAGAS -->

                <h5 class="card-title">Cadastro de Vagas Programa Recomeço</h5>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="txtVagas" name="txtVagas" placeholder="Nº de vagas" required>
                    <div class="invalid-feedback">Informe a quantidade de vagas do Programa Recomeço</div>
                    <label for="txtTelefone">Nº de vagas</label>
                  </div>
                </div>

                <!-- CADASTRO DE RESPONSÁVEIS -->

                    <h5 class="card-title">Cadastro de Responsáveis</h5>
                    
                      <div class="col-md-5">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="txtResponsavel" name="txtResponsavel" placeholder="Nome Completo">
                          <div class="invalid-feedback">Informe o nome do responsável</div>
                          <label for="txtResponsavel">Nome Completo</label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="txtCpfResponsavel" name="txtCpfResponsavel" placeholder="CPF">
                          <div class="invalid-feedback">Informe o CPF do responsável</div>
                          <label for="txtCpf">CPF</label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-floating" id="boxCargos"></div>
                      </div>
                      <div class="text-left col-md-1 mt-4">
                        <button type="button" class="btn btn-success" onclick="cadastraResponsavelExecutora()"><i class="bi bi-person-plus-fill"></i></button>
                      </div>
                    

                <!-- FIM DO CADASTRO DE RESPONSÁVEIS -->

                <div class="card col-md-10 mt-5">
                  <div class="card-body" id="boxListaResponsaveis"></div>
                </div>


                <!-- CADASTRO DE CASAS -->

                <h5 class="card-title">Cadastro de "Casas" da Executora</h5>
                    
                <div class="text-left col-md-3 mt-4">
                  <button type="button" class="btn btn-success" onclick="cadastraCasaExecutora()"><i class="bi bi-house-add"></i> ADICIONAR CASA</button>
                </div>

                <!-- FIM DO CADASTRO DE RESPONSÁVEIS -->

                <div class="card col-md-10 mt-2">
                  <div class="card-body mt-3" id="boxListaCasas"></div>
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
                  <button type="submit" class="btn btn-primary">Cadastrar OSC Executora</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

  </main>