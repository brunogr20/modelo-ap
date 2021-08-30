<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <title>Perfil - op Aqui</title>
    <meta name="description" content="">
  </head>
  <body class="h-100">

    @include('gestor.templates.header')
        <main style="margin:auto" class="main-content p-0 ">
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <!-- <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Blog Overview</h3>
              </div>
            </div> -->
            <!-- End Page Header -->
            {{-- @include('gestor.templates.menu') --}}
  
            <!--   - - - - - - - - -   abas ----- - - - - -    -->
            <h4 class="pt-1 ml-1" ><i class="fas fa-chevron-right"></i> <b>Perfil</b></h4>
            <div class="card mb-3 card-padrao"  >
              <div class="d-flex justify-content-between bd-highlight col-12 col-sm-12">
                <div><h5 class="pt-1" style="margin-left:-15px"  >Altere os dados da sua conta:</h5></div>
                <div><button type="button" class="btn btn-link" onclick="openModal()" ><b>Alterar Senha</b></button></div>
              </div>

              <form name="formPerfil">

                <div class="form-group form-row">
                  <div class="col">
                    <div class="form-group" >
                      <label for="ttlPesq" >Nome*: </label>
                      <input onkeyup="maiusc(this);" value="{{$data['usuario']['nome']}}"    name="nome" type="text" id="ttlPesq" class="form-control" />
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group" >
                      <label for="email" >E-mail*: </label>
                      <input  value="{{$data['usuario']['email']}}"    name="email" type="text" id="email" class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="form-group form-row">
                  <div class="col">
                    <div class="form-group" >
                      <label for="CPF" >CPF*: </label>
                      <input  value="{{$data['usuario']['cpf']}}" name="cpf" maxlength="14" onkeypress="return mascaraCep(event, this, '###.###.###-##');" type="text" id="CPF" class="form-control" />
                   </div>
                  </div>
                  <div class="col">
                    <div class="form-group" >
                      <label for="telefone" >Telefone*: </label>
                      <input  value="{{$data['usuario']['telefone']}}" maxlength="15" onkeypress="return mascaraCep(event, this, '(##) #####-####');"   name="telefone" type="text" id="telefone" class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="form-group form-row">
                  <div class="col">
                    <div class="form-group" >
                      <label for="cargo" >Cargo: </label>
                      <input value="{{$data['usuario']['cargo']}}"  name="cargo" type="text" id="cargo" class="form-control" />
                    </div>
                  </div>
                  <div class="col">
                   
                  </div>
                </div>
                
                

                <div class="modal-footer">
                  <button type="button" onclick="editPerfil()" class="btn btn-primary">Salvar</button>
                </div>
              </form>
            </div>

              <!-- Modal -->
              <div class="modal fade" id="modalFormPerfildorSenha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" >Alterar Senha</h3>
                    <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
                  </div>
                  <div class="modal-body">
                    <p>Para alterar a senha preencha o formulário</p>
                    <form name="formEntSenha">
                    <div class="form-group">
                      <label for="FormControlSenha2">Senha*:</label>
                      <input type="password" name="mSenha" class="form-control" id="FormControlSenha2" >
                    </div>
                    <div class="form-group">
                      <label for="FormControlConSenha2">Confirmar senha*:</label>
                      <input type="password" name="mConf_senha" class="form-control" id="FormControlConSenha2" >
                    </div>
                    <!-- <button type="button" onclick="editarSenha();" class="btn btn-primary">Salvar</button> -->
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" onclick="editarSenha();" class="btn btn-primary">Salvar</button>
                  </div>
                  </div>
                </div>
                </div>
         
            </div>
       
        </main>
        @include('gestor.templates.rodape')

    @include('gestor.templates.chamadas-down')
    <script>
      function openModal(){
        $('#modalFormPerfildorSenha').modal();
      }

      function modalSenha(cod) {
      colaborador = cod;
     document.querySelector('form[name=formEntSenha]').reset();
     $('#modalFormEntrevistadorSenha').modal();
    }
    function editarSenha() {
     if (!$('input[name=mSenha]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo senha.', 'top', 'infor');
      return false;
     }
     if ($('input[name=mSenha]').val().length <= 3) {
      alertaPadrao.returnMensagem('O campo senha deve ter no mínimo 4 caracteres.', 'top', 'infor');
      return false;
     }
     if ($('input[name=mSenha]').val() != $('input[name=mConf_senha]').val()) {
      alertaPadrao.returnMensagem('O campo confirmar senha está diferente da senha.', 'top', 'infor');
      return false;
     }
     var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('form[name=formEntSenha]'));
     form_data.append('id', "{{$data['usuario']['id']}}");
     ajax.onload = function (e) {
      var r = JSON.parse(this.responseText);
      if (r.status) {
       alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
       setTimeout(function () {
                    location.reload();
       }, 3000);
      } else {
       alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
      }
     };
     ajax.open('POST', '{{env('APP_URL')}}colaborador/salvar-senha', true); //faz a requisicao dos dados, via post
     ajax.send(form_data);
     return false;
    }

    function VerificaCPF(strCpf) {
        var soma;
        var resto;
        soma = 0;
      if (strCpf == "00000000000") {
          return false;
      }

      for (i = 1; i <= 9; i++) {
          soma = soma + parseInt(strCpf.substring(i - 1, i)) * (11 - i);
      }

      resto = soma % 11;

      if (resto == 10 || resto == 11 || resto < 2) {
          resto = 0;
      } else {
          resto = 11 - resto;
      }

      if (resto != parseInt(strCpf.substring(9, 10))) {
          return false;
      }

      soma = 0;

      for (i = 1; i <= 10; i++) {
          soma = soma + parseInt(strCpf.substring(i - 1, i)) * (12 - i);
      }
      resto = soma % 11;

      if (resto == 10 || resto == 11 || resto < 2) {
          resto = 0;
      } else {
          resto = 11 - resto;
      }

      if (resto != parseInt(strCpf.substring(10, 11))) {
          return false;
      }

      return true;
    }
       ///////
    function editPerfil() {
     if (!$('input[name=nome]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo nome.', 'top', 'infor');
      return false;
     }
     if (!$('input[name=email]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo e-mail.', 'top', 'infor');
      return false;
     }
     if (!(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i).test($('input[name=email]').val())) {
      alertaPadrao.returnMensagem('E-mail inválido.', 'top', 'infor');
      return false;
     }
     if (!$('input[name=cpf]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo CPF.', 'top', 'infor');
      return false;
     }
     if (!$('input[name=telefone]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo nome.', 'top', 'infor');
      return false;
     }

     var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('form[name=formPerfil]'));

     ajax.onload = function (e) {
      var r = JSON.parse(this.responseText);
      if (r.status) {
       alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
       setTimeout(function () {
        location.reload();
       }, 3000);
      } else {
       alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
      }
     };
     ajax.open('POST','{{env("APP_URL")}}perfil/salvar', true); //faz a requisicao dos dados, via post
     ajax.send(form_data);
     return false;
    }

    function mascaraCep(e, src, mask) {
      var _TXT = "";
      if (window.event) {
      _TXT = e.keyCode;
      } else if (e.which) {
      _TXT = e.which;
      }
      if (_TXT == "") {
      return true;
      }
      if (_TXT > 47 && _TXT < 58) {
      var i = src.value.length;
      var saida = "#"; //mask.substring(0,1);
      var texto = mask.substring(i);
      //alert(texto +" - "+ texto.substring(0,1))
      if (texto.substring(0, 1) != saida) {
        src.value += texto.substring(0, 1);
        if (texto.substring(0, 1) == ")") {
        src.value += " ";
        }
        if (src.value.length == 14 && mask.indexOf("(") > -1) { // Serve para a mÃ¡scara de numeros com 9 dÃ­gitos
        var trocaNumero, numeroTrocado;
        trocaNumero = src.value.substring(9, 11);
        numeroTrocado = trocaNumero[1] + trocaNumero[0]; // Inverte
        src.value = src.value.replace(trocaNumero, numeroTrocado);
        }
      }
      return true;
      } else {
      if (_TXT != 8) {
        return false;
      } else {
        return true;
      }
      }
    }
    </script>
  </body>
</html>