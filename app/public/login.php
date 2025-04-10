<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
      <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
          <div class="auth-form-transparent text-left p-3">
            <div class="brand-logo mb-4 text-center">
              <img src="../../../assets/images/logo.svg" alt="AutoSend Logo" style="max-width: 160px;">
            </div>
            <h4 class="text-center">Bem-vindo de volta 👋</h4>
            <h6 class="font-weight-light text-center mb-4">Acesse sua conta AutoSend</h6>

            <form class="pt-3" action="functions/auth-login.php" method="post">
              <div class="form-group">
                <label for="username">Usuário</label>
                <div class="input-group">
                  <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                      <i class="mdi mdi-account-outline text-primary"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control form-control-lg border-left-0" name="username" id="username" placeholder="Digite seu usuário" required>
                </div>
              </div>
              <div class="form-group">
                <label for="password">Senha</label>
                <div class="input-group">
                  <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                      <i class="mdi mdi-lock-outline text-primary"></i>
                    </span>
                  </div>
                  <input type="password" class="form-control form-control-lg border-left-0" name="password" id="password" placeholder="Digite sua senha" required>
                </div>
              </div>
              <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember"> Manter conectado
                  </label>
                </div>
                <a href="#" class="auth-link">Esqueceu a senha?</a>
              </div>
              <div class="my-3">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">ENTRAR</button>
              </div>
              <div class="text-center mt-4 font-weight-light">
                Ainda não tem uma conta?
                <a href="register-2.html" class="text-primary">Crie agora</a>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-6 login-half-bg d-flex flex-row">
          <p class="text-white font-weight-medium text-center flex-grow align-self-end">
            © 2025 AutoSend. Todos os direitos reservados.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
