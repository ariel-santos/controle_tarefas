<div class="row black navbar-fixed">
    <div class="col s12 m4">
        <h4 class="blue-text darken-4"> Talk to me </h4>
    </div>
    <div class="col s12 m4" id="container-usuario">

    </div>
    <div class="col s12 m4">
        <p><a href="#modal-acesso" class="waves-effect waves-light btn blue darken-4 right">Acessar</a></p>
    </div>
</div>

    <!-- Modal de acesso -->
    <div id="modal-acesso" class="modal">
      <div class="row modal-content">
        <form class="col s12 m8 offset-m2">
            <h4 class="center">Talk to me</h4>
            <h5 class="center">Informe seus dados de acesso</h5>
            <p class="msg center"></p>
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="login" type="text" name="login">
                <label for="login">Login </label>
            </div>
            <div class="input-field col s12">
                <i class="material-icons prefix">lock</i>
                <input id="senha" type="password" name="senha">
                <label for="senha">Senha </label>
            </div>
            <div class="row center">
              <div class="input-field col s12 center">
                  <a href="#!" onclick="modal_acesso()" class="btn black center"> Acessar </a>
              </div>
            </div>

            <div class="input-field col s12 center">
              <p></p>
            </div>
        </form>
      </div>
    </div>
