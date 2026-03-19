<!-- Carregamento da HEADER -->
<?php 
  include_once 'header.php';
  include_once 'sidebar.php';
  $url = explode('/' , $_SERVER["REQUEST_URI"]);
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Usuários</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/coed/area-restrita">Início</a></li>
        <li class="breadcrumb-item"><a href="/coed/usuarios">Usuários</a></li>
        <li class="breadcrumb-item active">Cadastro de Usuários</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="card col-md-10">
    <div class="card-body">
      <h5 class="card-title">Formulário de Cadastro de Usuários</h5>
      <input type="hidden" name="hidIdUsuario" id="hidIdUsuario" value="<?php echo $url[3] ?>" >
      <!-- Floating Labels Form -->
      <form class="row g-3 needs-validation" id="formUsuario" name="formUsuario" method="POST" autocomplete="off">
        
        <div class="col-md-12">
          <div class="form-floating">
            <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome Completo" required>
            <div class="invalid-feedback">Informe o nome completo</div>
            <label for="txtNomeFantasia">Nome Completo</label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" id="txtCpf" name="txtCpf" placeholder="Razão Social" required>
            <div class="invalid-feedback">Informe o CPF</div>
            <label for="txtCpf">CPF</label>
          </div>
        </div>
        <div class="col-md-10">
          <div class="form-floating">
            <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="E-Mail" required>
            <div class="invalid-feedback">Informe o E-mail</div>
            <label for="txtEmail">E-mail</label>
          </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating" id="boxTiposRegistros"></div>
        </div>
        <div class="col-md-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="txtNumeroRegistro" name="txtNumeroRegistro" placeholder="Número do Registro">
            <label for="txtNumeroRegistro">Nº do Registro</label>
          </div>
        </div>
        <span> Somente para cadastro de psicólogo, médico, etc.</span>
        <div class="text-center mt-5 col-md-11" id="boxBotoes">
          <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
        </div>
      </form><!-- End floating Labels Form -->
    </div>

  </div>

  <div class="card col-md-10 d-none" id="boxVinculoUsuario">

    <div class="card-body">
      <h5 class="card-title">Vinculos do usuário</h5>
      
      <div class="row">
        <div class="col-md-2">
            <div class="form-floating" id="boxPerfis"></div>
        </div>
        <div class="col-md-6 d-none" id="boxVinculo">
          <div class="form-floating" id="boxVinculoPerfil"></div>
          <div class="form-floating" id="boxVinculoCasas"></div>
        </div>
        <div class="col-md-2 mt-2">
        <button type="button" class="btn btn-success d-none" id="btnVincular" onclick="cadastraVinculo()">Vincular</button>
        </div>
      </div>
    </div>
    <div class="col-md-12 mt-2 mb-2">
      <div class="col-md-11 ms-2" id="boxListaVinculos"></div>
    </div>

  </div>

  <div class="modal fade" id="confirmacaoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning bg-gradient" id="tituloModal"></div>
        <div class="modal-body" id="corpoModal"></div>
        <div class="modal-footer" id="boxBotoesModal"></div>
      </div>
    </div>
  </div>

</main><!-- End #main -->