<?php 
  session_start();
  session_destroy(); 
?>
<main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4" style="margin-left: -30px;">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="images/logo_politica_sobre_drogas_bg.png" alt="">
                  <span class="d-none d-lg-block"></span>
                </a>
              </div>

              <div class="card mb-3" id="boxCamposLogin">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Política Estadual Sobre Drogas</h5>
                    <p class="text-center small">Acesso à Plataforma</p>
                  </div>

                  <form class="row g-3" name="formLogin" id="formLogin" method="POST" autocomplete="off">

                    <div class="col-12">
                      <label for="txtLogin" class="form-label">CPF</label>
                      <div class="input-group has-validation">
                        <input type="text" name="txtLogin" class="form-control" id="txtLogin" required>
                        <div class="invalid-feedback">Por favor, informe o seu CPF</div>
                      </div>
                    </div>

                    <div class="col-md-12" id="boxInicial">
                    
                      <div class="col-md-12">
                          <label for="txtCriaSenha" class="form-label">Crie uma senha</label>
                          <input type="password" class="form-control" id="txtCriaSenha" name="txtCriaSenha" required>
                          <div class="invalid-feedback">Por favor, informe a nova senha</div>
                      </div>

                      <div class="col-md-12 mt-3">
                          <label for="txtRepeteSenha" class="form-label">Repita a senha</label>    
                          <input type="password" class="form-control" id="txtRepeteSenha" name="txtRepeteSenha" required>
                          <div class="invalid-feedback">Por favor, confirme a nova senha</div>
                      </div>

                    </div>

                    <div class="col-md-12" id="boxSenha">
                      <div class="col-12">
                        <label for="txtSenha" class="form-label">Senha</label>
                        <input type="password" name="txtSenha" class="form-control" id="txtSenha">
                        <div class="invalid-feedback">Por favor, informe sua senha</div>
                      </div>
                    </div>
                    
                    <div class="col-12 text-center" id="boxBotao">
                      <button class="btn btn-primary w-100 mt-3" id="btnStep">Continuar <i class="bi bi-arrow-bar-right"></i></button>
                    </div>

                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center" id="boxLocais"></div>

      </section>

    </div>
  </main>