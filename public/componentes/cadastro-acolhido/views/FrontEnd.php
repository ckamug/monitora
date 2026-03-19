<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
  $contatoReferenciaTempId = empty($url[3]) ? '-' . random_int(100000000, 2147483647) : '';
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Dados do Acolhido</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
          <li class="breadcrumb-item"><a href="/coed/acolhidos">Acolhidos</a></li>
          <li class="breadcrumb-item active">Dados do Acolhido</li>
        </ol>
      </nav>
    </div>
    
    <section class="section">
    
      <div class="card col-md-12">
            <div class="card-body">
              <h5 class="card-title">Formulário de Cadastro do Acolhido</h5>
              <input type="hidden" name="hidIdAcolhido" id="hidIdAcolhido" value="<?php echo $url[3] ?>" >
              <input type="hidden" name="hidContatoReferenciaTempId" id="hidContatoReferenciaTempId" value="<?php echo $contatoReferenciaTempId ?>" >

              <div class="tab">
              
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="btnForm" data-bs-toggle="tab" data-bs-target="#tabForm" type="button" role="tab" aria-controls="Form" aria-selected="true">Dados e Informações do Acolhido</button>
                  </li>
                  <li class="nav-item d-none" role="presentation" id="abaDocumentos">
                    <button class="nav-link" id="btnArquivos" data-bs-toggle="tab" data-bs-target="#tabArquivos" type="button" role="tab" aria-controls="Arquivos" aria-selected="true">Documentos, Avaliações e Exames</button>
                  </li>
                  <li class="nav-item d-none" role="presentation" id="abaStatus">
                    <button class="nav-link" id="btnStatus" data-bs-toggle="tab" data-bs-target="#tabStatus" type="button" role="tab" aria-controls="Status" aria-selected="true">Status de Solicitações de Vagas</button>
                  </li>
                  <li class="nav-item d-none" role="presentation" id="abaAcolhimento">
                    <button class="nav-link" id="btnAcolhimento" data-bs-toggle="tab" data-bs-target="#tabAcolhimento" type="button" role="tab" aria-controls="Acolhimento" aria-selected="true">Acolhimento</button>
                  </li>
                </ul>              
          

                <!-- INICIO BOX CADASTRO -->
                <div class="tab-content pt-2" id="borderedTabContent">
                  
                  <div class="tab-pane fade show active" id="tabForm" role="tabpanel" aria-labelledby="home-tab">

                  <!-- Floating Labels Form -->
                  <form class="row g-3 needs-validation mt-3" id="formAcolhido" name="formAcolhido" method="POST" autocomplete="off">
                    <div class="col-md-12" id="boxSolicitarVaga"></div>

                    <div class="col-md-7">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtNomeCompleto" name="txtNomeCompleto" placeholder="Nome Completo" required>
                        <div class="invalid-feedback">Informe o Nome Completo</div>
                        <label for="txtNomeFantasia">Nome Completo</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtDataNascimento" name="txtDataNascimento" placeholder="Data de Nascimento" required>
                        <div class="invalid-feedback">Informe a Data de Nascimento</div>
                        <label for="txtNumero">Data de Nascimento</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <select class="form-select" id="slcSexo" name="slcSexo" aria-label="Sexo conforme registro">
                          <option selected value="0">Escolha</option>
                          <option value="Masculino">Masculino</option>
                          <option value="Feminino">Feminino</option>
                        </select>
                        <label for="slcIdentidadeGenero">Sexo conforme registro</label>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtNomeSocial" name="txtNomeSocial" placeholder="Nome Social">
                        <div class="invalid-feedback">Informe o Nome Social</div>
                        <label for="txtNomeFantasia">Nome Social</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <select class="form-select" id="slcIdentidadeGenero" name="slcIdentidadeGenero" aria-label="Identidade de Gênero">
                          <option selected value="0">Escolha</option>
                          <option value="Masculino">Masculino</option>
                          <option value="Feminino">Feminino</option>
                          <option value="Travesti">Travesti</option>
                          <option value="Transgênero">Transgênero</option>
                          <option value="Gênero neutro">Gênero neutro</option>
                          <option value="Não-binário">Não-binário</option>
                          <option value="Agênero">Agênero</option>
                          <option value="Pangênero">Pangênero</option>
                        </select>
                        <label for="slcIdentidadeGenero">Identidade de Gênero</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <select class="form-select" id="slcOrientacaoSexual" name="slcOrientacaoSexual" aria-label="Orientação Sexual">
                          <option selected value="0">Escolha</option>
                          <option value="Assexual">Assexual</option>
                          <option value="Bissexual">Bissexual</option>
                          <option value="Gay">Gay</option>
                          <option value="Heterossexual">Heterossexual</option>
                          <option value="Lésbica">Lésbica</option>
                          <option value="Pansexual">Pansexual</option>
                          <option value="Queer">Queer</option>
                          <option value="Nenhuma">Nenhuma das anteriores</option>
                        </select>
                        <label for="slcIdentidadeGenero">Orientação Sexual</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtFiliacao1" name="txtFiliacao1" placeholder="Filiação">
                        <label for="txtNomeMae">Filiação (1)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtFiliacao2" name="txtFiliacao2" placeholder="Filiação">
                        <label for="txtNomeMae">Filiação (2)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtFiliacao3" name="txtFiliacao3" placeholder="Filiação">
                        <label for="txtNomeMae">Filiação (3)</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <select class="form-select" id="slcEstadoCivil" name="slcEstadoCivil" aria-label="Estado Civil">
                          <option selected value="0">Escolha</option>
                          <option value="Solteiro">Solteiro</option>
                          <option value="Casado">Casado</option>
                          <option value="Divorciado">Divorciado</option>
                          <option value="União Estável">União Estável</option>
                          <option value="Viúvo">Viúvo</option>
                        </select>
                        <label for="slcEstadoCivil">Estado Civil</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtNis" name="txtNis" placeholder="NIS" inputmode="numeric" minlength="11" maxlength="11" pattern="[0-9]{11}">
                        <div class="invalid-feedback">Informe o NIS com 11 digitos</div>
                        <label for="txtNumero">NIS</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtCpf" name="txtCpf" placeholder="CPF">
                        <div class="invalid-feedback">Informe o CPF</div>
                        <label for="txtNumero">CPF</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtRg" name="txtRg" placeholder="RG">
                        <div class="invalid-feedback">Informe o RG</div>
                        <label for="txtNumero">RG</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-12 pt-0">É a primeira vez em serviço de acolhimento terapêutico?</legend>
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radAcolhimento" id="radAcolhimento1" value="SIM" onclick="abreBox('boxQuantasVezes',0)">
                            <label class="form-check-label" for="radAcolhimento1">Sim</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radAcolhimento" id="radAcolhimento2" value="NAO" onclick="abreBox('boxQuantasVezes',1)">
                            <label class="form-check-label" for="radAcolhimento2">Não</label>
                          </div>
                        </div>
                      </fieldset>
                      <div class="col-md-12" id="boxQuantasVezes" style="display: none;">
                        <div class="row mb-3">
                          <div class="col-md-2">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtReincidencia" name="txtReincidencia" placeholder="Reincidência">
                              <div class="invalid-feedback">Quantas vezes houve reincidência?</div>
                              <label for="txtReincidencia">Quantas vezes?</label>
                            </div>
                          </div>
                          <div class="col-md-10"></div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3"></div>
                    <div class="col-md-10 mt-5 fs-5">DADOS DE CONTATO</div>
                    <hr class="mt-2">

                    <div class="col-md-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtTelefonePessoal" name="txtTelefonePessoal" placeholder="Contato Telefônico Pessoal">
                        <div class="invalid-feedback">Informe o Contato Telefônico Pessoal</div>
                        <label for="txtNumero">Contato Telefônico Pessoal</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtTelefoneResidencial" name="txtTelefoneResidencial" placeholder="Contato Telefônico Residencial">
                        <div class="invalid-feedback">Informe o Contato Telefônico Residencial</div>
                        <label for="txtNumero">Contato Telefônico Residencial</label>
                      </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-10 mt-5 fs-6">CONTATOS DE REFERÊNCIA</div>
                    <hr class="mt-2">
                    <div class="col-md-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtNomeReferencia" name="txtNomeReferencia" placeholder="Nome do Contato de Referência">
                        <div class="invalid-feedback">Informe o nome do Contato de Referência</div>
                        <label for="txtNomeReferencia">Nome do Contato de Referência</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="txtTelefoneReferencia" name="txtTelefoneReferencia" placeholder="Contato Telefônico de Referência">
                        <div class="invalid-feedback">Informe o Contato Telefônico de Referência</div>
                        <label for="txtTelefoneReferencia">Contato Telefônico</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                      <select class="form-select" id="slcGrauParentesco" name="slcGrauParentesco" aria-label="Grau de Parentesco">
                          <option selected value="0">Escolha</option>
                          <option value="Pai">Pai</option>
                          <option value="Mãe">Mãe</option>
                          <option value="Irmãos">Irmãos</option>
                          <option value="Tio e/ou Tia">Tio e/ou Tia</option>
                          <option value="Primo e/ou Prima">Primo e/ou Prima</option>
                          <option value="Vizinhos">Vizinhos</option>
                          <option value="Conhecidos">Conhecidos</option>
                          <option value="Algum serviço público de Referência">Algum serviço público de Referência</option>
                        </select>
                        <label for="slcGrauParentesco">Tipo de Vínculo</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-floating">
                      <select class="form-select" id="slcTipoServico" name="slcTipoServico" aria-label="Tipo de Serviço Público">
                          <option selected value="0">Escolha</option>
                          <option value="Centro POP">Centro POP</option>
                          <option value="Consultório na rua">Consultório na rua</option>
                          <option value="Serviço de acolhimento institucional">Serviço de acolhimento institucional</option>
                          <option value="Outro">Outro</option>
                        </select>
                        <label for="slcGrauParentesco">Tipo de Serviço</label>
                      </div>
                    </div>
                    <div class="col-md-1 mt-4">
                      <div class="form-floating">
                      <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="Adicionar contato de referência" onclick="cadastraContatoReferencia()"><i class="bi bi-plus"></i></button>
                      </div>
                    </div>

                    <div id="boxContatosReferencia"></div>

                    <div class="col-md-10 mt-5 fs-5">ENDEREÇO</div>
                    <hr class="mt-2">

                    <div class="col-md-12">
                      <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-12 pt-0">Tem endereço fixo?</legend>
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radEndereco" id="radEndereco1" value="SIM" onclick="abreBox('boxEndereco',1)">
                            <label class="form-check-label" for="radEndereco1">Sim</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radEndereco" id="radEndereco2" value="NAO" onclick="abreBox('boxSituacaoRua',1)">
                            <label class="form-check-label" for="radEndereco2">Não, estou em situação de rua</label>
                          </div>
                        </div>
                      </fieldset>
                    </div>

                    <div class="col-md-12" id="boxEndereco" style="display: none;">
                      <div class="row mb-3">
                        <div class="col-md-2">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtCep" name="txtCep" placeholder="CEP">
                            <div class="invalid-feedback">Informe o CEP</div>
                            <label for="txtCep">CEP</label>
                          </div>
                        </div>
                        <div class="col-md-10"></div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-8">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" placeholder="Endereço">
                            <div class="invalid-feedback">Informe o Endereço</div>
                            <label for="txtEndereco">Endereço</label>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtNumero" name="txtNumero" placeholder="Nº">
                            <div class="invalid-feedback">Informe o Número</div>
                            <label for="txtNumero">Nº</label>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-3">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtComplemento" name="txtComplemento" placeholder="Complemento">
                            <div class="invalid-feedback">Informe o Complemento</div>
                            <label for="txtBairro">Complemento</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtBairro" name="txtBairro" placeholder="Bairro">
                            <div class="invalid-feedback">Informe o Bairro</div>
                            <label for="txtBairro">Bairro</label>
                          </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-floating" id="boxMunicipios"></div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12" id="boxSituacaoRua" style="display:none;">
                      <div class="col-md-3">
                        <div class="form-floating">
                          <select class="form-select" id="slcTempoSituacaoRua" name="slcTempoSituacaoRua" aria-label="Tempo em situação de rua">
                            <option selected value="0">Escolha</option>
                            <option value="Até 6 meses">Até 6 meses</option>
                            <option value="De 6 meses a 1 ano">Entre 6 meses e 1 ano</option>
                            <option value="De 1 ano a 2 anos">Entre 1 e 2 anos</option>
                            <option value=">De 2 anos a 4 anos">Entre 2 e 5 anos</option>
                            <option value="Acima de 5 anos">Entre 5 e 10 anos</option>
                          </select>
                          <label for="slcTempoSituacaoRua">Há quanto tempo?</label>
                        </div>
                      </div>  
                      
                      <div class="col-md-6 mt-3 d-none">
                        <legend class="col-form-label col-sm-12 pt-0">Quais os motivos que levaram a situação de rua?</legend>
                        <div class="col-sm-10">

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua1" name="chkMotivosSituacaoRua[]" value="Desemprego">
                            <label class="form-check-label" for="chkMotivosSituacaoRua1">Desemprego</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua2" name="chkMotivosSituacaoRua[]" value="Uso de substancia psicoativa">
                            <label class="form-check-label" for="chkMotivosSituacaoRua2">Uso de substancia psicoativa</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua3" name="chkMotivosSituacaoRua[]" value="Falta de moradia">
                            <label class="form-check-label" for="chkMotivosSituacaoRua3">Falta de moradia</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua4" name="chkMotivosSituacaoRua[]" value="Problemas psiquiátricos">
                            <label class="form-check-label" for="chkMotivosSituacaoRua4">Problemas psiquiátricos</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua5" name="chkMotivosSituacaoRua[]" value="Relacionamento afetivo">
                            <label class="form-check-label" for="chkMotivosSituacaoRua5">Relacionamento afetivo</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkMotivosSituacaoRua6" name="chkMotivosSituacaoRua[]" value="Relacionamento familiar">
                            <label class="form-check-label" for="chkMotivosSituacaoRua6">Relacionamento familiar</label>
                          </div>

                        </div>
                      </div>

                    </div>
                    
                    <div class="col-md-10 mt-5 fs-5">SAÚDE</div>
                    <hr class="mt-2">

                    <div class="col-md-6">
                      <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-12 pt-0">Tem alguma das seguintes comorbidades?</legend>
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade1" name="chkComorbidade[]" value="Pressão Alta">
                            <label class="form-check-label" for="chkComorbidade1">Pressão Alta</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade2" name="chkComorbidade[]" value="Colesterol">
                            <label class="form-check-label" for="chkComorbidade2">Colesterol</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade3" name="chkComorbidade[]" value="Tuberculose">
                            <label class="form-check-label" for="chkComorbidade3">Tuberculose</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade4" name="chkComorbidade[]" value="Sífilis">
                            <label class="form-check-label" for="chkComorbidade4">Sífilis</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade5" name="chkComorbidade[]" value="Doenças Cardiovasculares">
                            <label class="form-check-label" for="chkComorbidade5">Doenças Cardiovasculares</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade6" name="chkComorbidade[]" value="Epilepsia">
                            <label class="form-check-label" for="chkComorbidade6">Epilepsia</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade7" name="chkComorbidade[]" value="Diabetes">
                            <label class="form-check-label" for="chkComorbidade7">Diabetes</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade8" name="chkComorbidade[]" value="Hepatite (B/C)">
                            <label class="form-check-label" for="chkComorbidade8">Hepatite (B/C)</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade9" name="chkComorbidade[]" value="HIV">
                            <label class="form-check-label" for="chkComorbidade9">HIV</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade10" name="chkComorbidade[]" value="Cirrose">
                            <label class="form-check-label" for="chkComorbidade10">Cirrose</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade11" name="chkComorbidade[]" value="Outra">
                            <label class="form-check-label" for="chkComorbidade11">Outra</label>
                          </div>
                          <div class="col-md-4" id="boxTipoAcompanhamento" style="display:none;">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutraComorbidade" name="txtOutraComorbidade" placeholder="Qual?">
                              <label for="txtOutraComorbidade">Qual?</label>
                            </div>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkComorbidade12" name="chkComorbidade[]" value="Não">
                            <label class="form-check-label" for="chkComorbidade12">Não</label>
                          </div>
                        </div>
                      </fieldset>
                    </div>

                    <div class="col-md-6">

                      <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-12 pt-0">Possui alguma deficiência que limite as habilidades habituais? (Trabalhar, estudos, etc.)</legend>
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radDeficiencia" id="radDeficiencia1" value="SIM" onclick="abreBox('boxDeficiencia',1)">
                            <label class="form-check-label" for="radDeficiencia1">
                              Sim
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radDeficiencia" id="radDeficiencia2" value="NAO" onclick="abreBox('boxDeficiencia',0)">
                            <label class="form-check-label" for="radDeficiencia2">
                              Não
                            </label>
                          </div>
                        </div>
                        
                        <div class="col-md-12 mt-3" id="boxDeficiencia" style="display:none;">
                        
                        <h5>Qual o tipo de deficiência?</h5>
                                                  
                        <div class="row mt-2">
                          <div class="col-6 mt-2">
                            <div class="col-sm-10">
                              
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia1" name="chkDeficiencia[]" value="Cegueira">
                                <label class="form-check-label" for="chkDeficiencia1"> Cegueira</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia2" name="chkDeficiencia[]" value="Baixa visão">
                                <label class="form-check-label" for="chkDeficiencia2"> Baixa visão</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia3" name="chkDeficiencia[]" value="Surdez Severa/profunda">
                                <label class="form-check-label" for="chkDeficiencia3"> Surdez Severa/profunda</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia4" name="chkDeficiencia[]" value="Surdez leve/moderada">
                                <label class="form-check-label" for="chkDeficiencia4"> Surdez leve/moderada</label>
                              </div>

                            </div>
                          </div>
                              
                          <div class="col-6 mt-2">
                            <div class="col-sm-10">    
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia5" name="chkDeficiencia[]" value="Deficiência Física">
                                <label class="form-check-label" for="chkDeficiencia5"> Deficiência Física</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia6" name="chkDeficiencia[]" value="Síndrome de Down">
                                <label class="form-check-label" for="chkDeficiencia6"> Síndrome de Down</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia7" name="chkDeficiencia[]" value="Transtorno/doença mental">
                                <label class="form-check-label" for="chkDeficiencia7"> Transtorno/doença mental</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkDeficiencia8" name="chkDeficiencia[]" value="Deficiência Mental ou intelectual">
                                <label class="form-check-label" for="chkDeficiencia8"> Deficiência Mental ou intelectual</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4">
                          <legend class="col-form-label col-sm-12 pt-0">- Em função dessa deficiência recebe cuidados permanentes de terceiros?</legend>
                          <div class="col-10 mt-2">
                            <div class="col-sm-12">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros1" name="chkCuidadosTerceiros[]" value="Não">
                                <label class="form-check-label" for="chkCuidadosTerceiros1"> Não</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros2" name="chkCuidadosTerceiros[]" value="Sim, de alguém da família">
                                <label class="form-check-label" for="chkCuidadosTerceiros2"> Sim, de alguém da família</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros3" name="chkCuidadosTerceiros[]" value="Sim, de cuidador especializado">
                                <label class="form-check-label" for="chkCuidadosTerceiros3"> Sim, de cuidador especializado</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros4" name="chkCuidadosTerceiros[]" value="Sim, de vizinho">
                                <label class="form-check-label" for="chkCuidadosTerceiros4"> Sim, de vizinho</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros5" name="chkCuidadosTerceiros[]" value="Sim, de instituição da rede">
                                <label class="form-check-label" for="chkCuidadosTerceiros5"> Sim, de instituição da rede</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkCuidadosTerceiros6" name="chkCuidadosTerceiros[]" value="Sim, de outra forma">
                                <label class="form-check-label" for="chkCuidadosTerceiros6"> Sim, de outra forma</label>
                              </div>
                            </div>
                          </div>  
                        </div>
                      </fieldset>

                    </div>
                    
                    <div class="row col-md-12">
                      <legend class="col-form-label col-sm-12 pt-0">Qual o tipo de substancia da sua preferência?</legend>
                      <div class="col-2 mt-2">
                        <div class="col-sm-10">

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia1" name="chkSubstanciaPreferencia[]" value="Álcool">
                            <label class="form-check-label" for="chkSubstanciaPreferencia1">Álcool</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia2" name="chkSubstanciaPreferencia[]" value="Maconha">
                            <label class="form-check-label" for="chkSubstanciaPreferencia2">Maconha</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia3" name="chkSubstanciaPreferencia[]" value="Cocaína">
                            <label class="form-check-label" for="chkSubstanciaPreferencia3">Cocaína</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia4" name="chkSubstanciaPreferencia[]" value="Crack">
                            <label class="form-check-label" for="chkSubstanciaPreferencia4">Crack</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia5" name="chkSubstanciaPreferencia[]" value="Êxtase">
                            <label class="form-check-label" for="chkSubstanciaPreferencia5">Êxtase</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia6" name="chkSubstanciaPreferencia[]" value="Anfetaminas">
                            <label class="form-check-label" for="chkSubstanciaPreferencia6">Anfetaminas</label>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-5 mt-2">
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia7" name="chkSubstanciaPreferencia[]" value="LSD">
                            <label class="form-check-label" for="chkSubstanciaPreferencia7">LSD</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia8" name="chkSubstanciaPreferencia[]" value="Substâncias K;Spice">
                            <label class="form-check-label" for="chkSubstanciaPreferencia8">Substâncias K, Spice</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia9" name="chkSubstanciaPreferencia[]" value="Heroína">
                            <label class="form-check-label" for="chkSubstanciaPreferencia9">Heroína</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia10" name="chkSubstanciaPreferencia[]" value="Metanfetamina">
                            <label class="form-check-label" for="chkSubstanciaPreferencia10">Metanfetamina</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia11" name="chkSubstanciaPreferencia[]" value="Medicação Psicotrópica">
                            <label class="form-check-label" for="chkSubstanciaPreferencia11">Medicação Psicotrópica</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkSubstanciaPreferencia12" name="chkSubstanciaPreferencia[]" value="Outra">
                            <label class="form-check-label" for="chkSubstanciaPreferencia12">Outra</label>
                          </div>
                          <div class="col-md-8 mt-2" id="boxOutraSubstanciaPreferencia" style="display:none;">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutraSubstanciaPreferencia" name="txtOutraSubstanciaPreferencia" placeholder="Qual?">
                              <label for="txtOutraSubstanciaPreferencia">Qual?</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-floating">
                        <select class="form-select" id="slcTempoUtilizaSubstancia" name="slcTempoUtilizaSubstancia" aria-label="A quanto tempo utiliza alguma substância">
                          <option selected value="0">Escolha</option>
                          <option value="Até 6 meses">Até 6 meses</option>
                          <option value="De 6 meses a 1 ano">De 6 meses a 1 ano</option>
                          <option value="De 1 ano a 2 anos">De 1 ano a 2 anos</option>
                          <option value=">De 2 anos a 4 anos">De 2 anos a 4 anos</option>
                          <option value="Acima de 5 anos">Acima de 5 anos</option>
                        </select>
                        <label for="slcTempoUtilizaSubstancia">Há quanto tempo utiliza substâncias?</label>
                      </div>
                    </div>

                    <div class="col-md-12">

                      <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-12 pt-0">Estava em alguma unidade hospitalar para desintoxicação?</legend>
                        <div class="col-sm-10">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radUnidadeHospitalar" id="radUnidadeHospitalar1" value="SIM" onclick="abreBox('boxUnidadeHospitalar',1)">
                            <label class="form-check-label" for="radUnidadeHospitalar1">
                              Sim
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="radUnidadeHospitalar" id="radUnidadeHospitalar2" value="NAO" onclick="abreBox('boxUnidadeHospitalar',0)">
                            <label class="form-check-label" for="radUnidadeHospitalar2">
                              Não
                            </label>
                          </div>
                        </div>

                        <div class="col-md-3 mt-3" id="boxUnidadeHospitalar" style="display:none;">
                          <div class="form-floating">
                            <select class="form-select" id="slcUnidadeHospitalar" name="slcUnidadeHospitalar" onchange="trataUnidadeHospitalar(this.value)" aria-label="Qual unidade hospitalar">
                              <option selected value="0">Escolha</option>
                              <option value="Instituto Bairral de Psiquiatria">Instituto Bairral de Psiquiatria</option>
                              <option value="Hospital Lacan">Hospital Lacan</option>
                              <option value="Instituto Perdizes HCFMUSP">Instituto Perdizes HCFMUSP</option>
                              <option value="Intituto Bezerra de Menezes">Intituto Bezerra de Menezes</option>
                              <option value="CAISM Philippe Pinel">CAISM Philippe Pinel</option>
                              <option value="Unidade Recomeço Helvétia">Unidade Recomeço Helvétia</option>
                              <option value="Centro de Reabilitação de Casa Branca">Centro de Reabilitação de Casa Branca</option>
                              <option value="CAISM Água Funda">CAISM Água Funda</option>
                              <option value="Hospital Estadual Santa Rita do Passa Quatro">Hospital Estadual Santa Rita do Passa Quatro</option>
                              <option value="Hospital Dr. Adolfo Bezerra de Menezes (SJRP)">Hospital Dr. Adolfo Bezerra de Menezes (SJRP)</option>
                              <option value="Outra">Outra</option>
                            </select>
                            <label for="slcUnidadeHospitalar">Qual unidade hospitalar?</label>
                          </div>
                        </div>

                        <div class="col-md-4 mt-3 d-none" id="boxOutraUnidadeHospitalar">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="txtOutraUnidadeHospitalar" name="txtOutraUnidadeHospitalar" placeholder="Outra Unidade Hospitalar">
                            <label for="txtOutraUnidadeHospitalar">Qual outra unidade hospitalar?</label>
                          </div>
                        </div>
                        
                      </fieldset>

                    </div>

                    <div class="col-md-10 mt-5 fs-5">HISTÓRICO</div>
                    <hr class="mt-2">
                    <div class="col-md-8">
                      <div class="form-floating">
                        <textarea class="form-control" placeholder="Breve histórico" name="txtHistorico" id="txtHistorico" style="height: 100px;"></textarea>  
                        <div class="invalid-feedback">Informe o Contato Telefônico Pessoal</div>
                        <label for="txtHistorico">Breve relato do histórico da pessoa a ser acolhida</label>
                      </div>
                    </div>
                    

                    <div class="text-center mt-5 col-md-11" id="boxBotoes">
                      <?php if(empty($url[3])){ ?>
                      <button type="submit" class="btn btn-primary">Cadastrar Acolhido</button>
                      <?php } ?>
                    </div>
                  </form>
                </div>

              
                <!-- FIM BOX CADASTRO -->
                
                <!-- INICIO BOX ARQUIVOS -->
                <div class="tab-pane fade" id="tabArquivos" role="tabpanel" aria-labelledby="tabArquivos">

                  <div class="row mt-5">
                  
                  
                    <div class="card col-4 p-0 ms-3" style="width: 32%;">
                      <div class="card-header bg-primary text-dark bg-opacity-10">DOCUMENTOS</div>
                      <div class="card-body">
                        <h5 class="card-title">Anexar documentos</h5>
                          
                          <form id="formDocumentos" method="post" enctype="multipart/form-data">
                          
                            <div class="col-12 mt-5">
                              <input class="form-control" type="file" id="documentos" name="documentos">
                            </div>

                          </form>

                          <div class="col-12 mt-3" id="visualizar_documentos"></div>
                      
                        </div>
                      <div class="card-footer"></div>
                    </div>
                    
                    <div class="card col-4 p-0 ms-3" style="width: 32%;">
                      <div class="card-header bg-primary text-dark bg-opacity-10">AVALIAÇÕES</div>
                      <div class="card-body">
                        <h5 class="card-title">Anexar avaliações</h5>
                          
                          <form id="formAvaliacoes" method="post" enctype="multipart/form-data">
                          
                            <div class="col-23 mt-5">
                              <input class="form-control" type="file" id="avaliacoes" name="avaliacoes">
                            </div>

                          </form>

                          <div class="col-12 mt-3" id="visualizar_avaliacoes"></div>
                      
                        </div>
                      <div class="card-footer"></div>
                    </div>

                    <div class="card col-4 p-0 ms-3" style="width: 32%;">
                      <div class="card-header bg-primary text-dark bg-opacity-10">EXAMES</div>
                      <div class="card-body">
                        <h5 class="card-title">Anexar exames</h5>
                          
                          <form id="formExames" method="post" enctype="multipart/form-data">
                          
                            <div class="col-12 mt-5">
                              <input class="form-control" type="file" id="exames" name="exames">
                            </div>

                          </form>

                          <div class="col-12 mt-3" id="visualizar_exames"></div>
                      
                        </div>
                      <div class="card-footer"></div>
                    </div>
                  
                  </div> 

                </div>
                <!-- FIM BOX ARQUIVOS -->

                <!-- INICIO BOX STATUS -->
                <div class="tab-pane fade" id="tabStatus" role="tabpanel" aria-labelledby="tabStatus">
                  <div class="col-md-12 mt-3" id="boxStatusVaga"></div>
                </div>
                <!-- FIM BOX STATUS -->

                <!-- INICIO BOX ACOLHIMENTO -->
                <div class="tab-pane fade" id="tabAcolhimento" role="tabpanel" aria-labelledby="tabAcolhimento">

                  <h4 class="w-100 text-center mt-4 card-title" id="hTipoServico"></h4>

                    <div class="row w-100">
                    
                      <div class="col-md-2 text-center d-none">
                        <button type="button" class="btn btn-outline-success rounded">
                          <i class="bi bi-journal-medical display-3" data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='Prontuário'></i>
                        </button>
                      </div>
                      
                      <div class="col-md-10 mt-5 fs-5">DOCUMENTAÇÃO</div>
                      <hr class="mt-2">
                      
                      <div class="col-md-6 mt-3">
                        <legend class="col-form-label col-sm-12 pt-0"><strong>Documentação que possuo</strong></legend>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo1" name="chkDocPossuo" value="RG">
                            <label class="form-check-label" for="chkDocPossuo1">RG</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo2" name="chkDocPossuo" value="CPF">
                            <label class="form-check-label" for="chkDocPossuo2">CPF</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo3" name="chkDocPossuo" value="CNH">
                            <label class="form-check-label" for="chkDocPossuo3">CNH</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo4" name="chkDocPossuo" value="CTPS">
                            <label class="form-check-label" for="chkDocPossuo4">CTPS</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo5" name="chkDocPossuo" value="Certidão de Nascimento">
                            <label class="form-check-label" for="chkDocPossuo5">Certidão de Nascimento</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo6" name="chkDocPossuo" value="Título de Eleitor">
                            <label class="form-check-label" for="chkDocPossuo6">Título de Eleitor</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo7" name="chkDocPossuo" value="Cartão SUS">
                            <label class="form-check-label" for="chkDocPossuo7">Cartão SUS</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo8" name="chkDocPossuo" value="Reservista">
                            <label class="form-check-label" for="chkDocPossuo8">Reservista</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo9" name="chkDocPossuo" value="CadUnico">
                            <label class="form-check-label" for="chkDocPossuo9">CadUnico</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocPossuo10" name="chkDocPossuo" value="Outros">
                            <label class="form-check-label" for="chkDocPossuo10">Outros</label>
                          </div>

                          <div class="col-md-8 mt-2 d-none" id="boxOutrosDocPossuo">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutroDocPossuo" name="txtOutroDocPossuo" placeholder="Outros Documentos">
                              <div class="invalid-feedback">Informe qual outro documento</div>
                              <label for="txtOutroDocPossuo">Quais outros documentos?</label>
                            </div>
                          </div>

                      </div>
                    
                      <div class="col-md-6 mt-3">
                        <legend class="col-form-label col-sm-12 pt-0"><strong>Documentação necessária para ser providenciada</strong></legend>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria1" name="chkDocNecessaria" value="RG">
                            <label class="form-check-label" for="chkDocNecessaria1">RG</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria2" name="chkDocNecessaria" value="CPF">
                            <label class="form-check-label" for="chkDocNecessaria2">CPF</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria3" name="chkDocNecessaria" value="CNH">
                            <label class="form-check-label" for="chkDocNecessaria3">CNH</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria4" name="chkDocNecessaria" value="CTPS">
                            <label class="form-check-label" for="chkDocNecessaria4">CTPS</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria5" name="chkDocNecessaria" value="Certidão de Nascimento">
                            <label class="form-check-label" for="chkDocNecessaria5">Certidão de Nascimento</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria6" name="chkDocNecessaria" value="Título de Eleitor">
                            <label class="form-check-label" for="chkDocNecessaria6">Título de Eleitor</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria7" name="chkDocNecessaria" value="Cartão SUS">
                            <label class="form-check-label" for="chkDocNecessaria7">Cartão SUS</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria8" name="chkDocNecessaria" value="Reservista">
                            <label class="form-check-label" for="chkDocNecessaria8">Reservista</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria9" name="chkDocNecessaria" value="CadUnico">
                            <label class="form-check-label" for="chkDocNecessaria9">CadUnico</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chkDocNecessaria10" name="chkDocNecessaria" value="Outros">
                            <label class="form-check-label" for="chkDocNecessaria10">Outros</label>
                          </div>

                          <div class="col-md-8 mt-2 d-none" id="boxOutrosDocNecessaria">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="txtOutroDocNecessaria" name="txtOutroDocNecessaria" placeholder="Outros Documentos">
                              <div class="invalid-feedback">Informe qual outro documento</div>
                              <label for="txtOutroDocNecessaria">Quais outros documentos?</label>
                            </div>
                          </div>

                      </div>

                    </div>

                    <div class="row w-100">

                      <div class="col-md-10 mt-5 fs-5">OUTRAS INFORMAÇÕES</div>
                      <hr class="mt-2">


                      <div class="col-md-3 mt-3">
                        <div class="form-floating">
                          <select class="form-select" id="slcEscolaridade" name="slcEscolaridade" aria-label="Escolaridade">
                            <option selected value="0">Escolha</option>
                            <option value="Sem escolaridade">Sem escolaridade</option>
                            <option value="Ensino Fundamental Completo">Ensino Fundamental Completo</option>
                            <option value="Ensino Fundamental Incompleto">Ensino Fundamental Incompleto</option>
                            <option value="Ensino Médio Completo">Ensino Médio Completo</option>
                            <option value="Ensino Médio Incompleto">Ensino Médio Incompleto</option>
                            <option value="Ensino Técnico Completo">Ensino Técnico Completo</option>
                            <option value="Ensino Técnico Incompleto">Ensino Técnico Incompleto</option>
                            <option value="Ensino Superior Completo">Ensino Superior Completo</option>
                            <option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
                          </select>
                          <label for="slcEscolaridade">Escolaridade</label>
                        </div>
                      </div> 

                      <div class="col-md-3"></div>

                      <div class="col-md-6 mt-3">
                        <legend class="col-form-label col-sm-12 pt-0"><strong>Possui benefício de transferência de renda?</strong></legend>

                          <div class="form-check form-check-inline">
                            <input class="form-radio-input" type="radio" id="chkBeneficio1" name="chkBeneficio" value="SIM">
                            <label class="form-radio-label" for="chkBeneficio1">SIM</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-radio-input" type="radio" id="chkBeneficio2" name="chkBeneficio" value="NÃO">
                            <label class="form-radio-label" for="chkBeneficio2">NÃO</label>
                          </div>

                          <div class="d-none" id="boxTipoBeneficio">

                            <div class="col-md-6 mt-3 ms-4">
                              <legend class="col-form-label col-sm-12 pt-0"><strong>Quais benefícios</strong></legend>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio1" name="chkTipoBeneficio" value="BPC">
                                <label class="form-check-label" for="chkTipoBeneficio1">BPC</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio2" name="chkTipoBeneficio" value="Ação Jovem">
                                <label class="form-check-label" for="chkTipoBeneficio2">Ação Jovem</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio3" name="chkTipoBeneficio" value="Renda Cidadã">
                                <label class="form-check-label" for="chkTipoBeneficio3">Renda Cidadã</label>
                              </div>
                              <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio5" name="chkTipoBeneficio" value="Bolsa Família">
                                <label class="form-check-label" for="chkTipoBeneficio5">Bolsa Família</label>
                              </div>
                              <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio6" name="chkTipoBeneficio" value="PETI">
                                <label class="form-check-label" for="chkTipoBeneficio6">PETI</label>
                              </div>
                              <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio7" name="chkTipoBeneficio" value="POT - Programa Operação Trabalho">
                                <label class="form-check-label" for="chkTipoBeneficio7">POT - Programa Operação Trabalho</label>
                              </div>
                              <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="chkTipoBeneficio4" name="chkTipoBeneficio" value="Outros">
                                <label class="form-check-label" for="chkTipoBeneficio4">Outros</label>
                              </div>
                            </div>

                            <div class="col-md-10 mt-2 ms-4">
                              <div class="form-floating d-none" id="boxOutroTipoBeneficio">
                                  <input type="text" class="form-control" id="txtOutroTipoBeneficio" name="txtOutroTipoBeneficio" placeholder="Outro beneficio">
                                  <label for="txtOutroTipoBeneficio">Qual outro beneficio?</label>
                              </div>
                            </div>


                            <div class="col-md-7 mt-2 ms-4">
                              <div class="form-floating">
                                <input type="text" class="form-control" id="txtValorRecebido" name="txtValorRecebido" placeholder="Valor recebido">
                                <div class="invalid-feedback">Informe o valor recebido</div>
                                <label for="txtValorRecebido">Valor Recebido (todos os benefícios)</label>
                              </div>
                            </div>

                          </div>

                      </div>                    

                    </div>

                    <div class="text-center mt-5 col-md-11" id="boxBotaoAcolhimento">
                      <button class="btn btn-success mt-5 mb-3 mx-0" onclick="cadastraAcolhimento()">Confirmar Acolhimento</button>
                    </div>

                </div>
                <!-- FIM BOX ACOLHIMENTO -->

                </div>
            
            </div>

            </div>
          </div>
        </div>
      </div>

    </section>

  </main>

<div class="modal fade" id="mdlSolicitarVaga" tabindex="-1" aria-labelledby="mdlSolicitarVagaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #dbebfb;">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-house"></i> Solicitar Vaga</h5>
      </div>
      <div class="modal-body">
        
        <div class="col-md-12">
          <div class="form-floating" id="boxServicos"></div>
        </div>
        <div class="col-md-12 mt-2">
          <div class="form-floating d-none" id="boxGenero">
            <select class="form-select" id="slcGenero" name="slcGenero" aria-label="Gênero">
              <option selected="" disabled="" value="0">Escolha</option>
              <option value="Masculino">Masculino</option>
              <option value="Feminino">Feminino</option>
            </select>
            <label for='slcGenero'>Gênero</label>
          </div>
        </div>

      </div>
      <div class="modal-footer" id="boxBtnSolicitacao">
        <button type="button" class="btn btn-primary" id="btnConfirmaSolicitacaoVaga">Enviar Solicitação</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmacaoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="tituloModal"></div>
      <div class="modal-body" id="corpoModal"></div>
      <div class="modal-footer" id="boxBotoesModal"></div>
    </div>
  </div>
</div>
