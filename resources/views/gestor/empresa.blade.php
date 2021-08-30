<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <title>Empresa - op Aqui</title>
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
           <h4 class="pt-1 ml-1" ><i class="fas fa-chevron-right"></i> <b>Empresa</b></h4>
            <div class="card mb-3 card-padrao"  >
              <div class="d-flex justify-content-between bd-highlight col-12 col-sm-12">
                <div><h5 class="pt-1" style="margin-left:-15px"  >Altere os dados da empresa:</h5></div>
              </div>
            <form name="formEmpresa">

              <div class="form-group" >

                <div class="form-group form-row">
                  <div class="col">
                    <div>
                      <label for="tpEntrevista" >Pessoa*:</label>
                      {{-- <select name="pessoa" onchange="checkTypeInterview(this.value)" class="form-control" id="tpEntrevista" > --}}
                        <select name="pessoa" onchange="maskCpfCnpj(this.value)" class="form-control" id="tpEntrevista" >
                        <option value='FISICA' >Física</option>
                      <option value='JURIDICA' >Júridica</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group" >
                      <label for="ttlPesq" >Nome*: </label>
                      <input value="{{$data['empresa']['nome']}}" name="nome" type="text" id="ttlPesq" class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="form-group form-row">
                  <div class="col">
                    <div class="form-group" >
                      <label for="cpf_cnpj" >CPF/CNPJ*: </label>
                      <input value="{{$data['empresa']['cpf_cnpj']}}" name="cpf_cnpj" type="text" id="cpf_cnpj" maxlength="18"  onkeypress='mascaraMutuario(this,cpfCnpj)' onblur='clearTimeout()' class="form-control" />
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group" >
                      <label for="telefone" >Telefone*: </label>
                      <input value="{{$data['empresa']['telefone']}}" name="telefone" type="text" maxlength="15" onkeypress="return mascaraCep(event, this, '(##) #####-####');" id="telefone" class="form-control" />
                    </div>
                  </div>
                </div>

              
                <div class="form-group form-row">
                  <div class="col">
                    {{-- <div class="form-group" >
                      <label for="telefone" >Responsável*: </label>
                      <input value="{{$data['empresa']['responsavel']}}" name="responsavel" type="text" id="responsavel" class="form-control" />
                    </div> --}}
                    <label for="tpEntrevista" >UF*:</label>
                    <select name="uf" onchange="getCidades(this.value)" class="form-control" id="tpEntrevista" >
                      @foreach(ufData() as $uf)
                      <option value='{{$uf}}'>{{$uf}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col">
                    <div class="form-group" >
                      <label for="tpEntrevista" >Cidade*:</label>
                      <select name="cidade" class="form-control" >
                        <option value="" selected >Escolha uma opção</ion-option>
                        @foreach($data['cidades'] as $cidade)
                        <option value="{{$cidade}}" >{{$cidade}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              
                <div class="form-group form-row">
                    <div class="col">
                      <div class="col">
                        <label for="txtAber" >Imagem Logo:</label>
                      <div class="imagem_logo">
                          <div style="margin: auto;width: 200px;">
                          <?php $img =($data['empresa']['imagem_logo_empresa'])? $data['empresa']['imagem_logo_empresa'] : asset('public/assets_gestor/img/img-padrao.png')  ?>
                          <img  class="img-fluid" id="imagem_logo_empresa" src="<?= $img; ?>">
                          <h6 align="center" style="margin-bottom:0px;">Dimensões: 200x200px</h6>
                          <div>
                              <a onclick="abrirJanela('file_imagem_logo_empresa', 'imagem_logo_empresa')" style="color:#fff;width:97px;" class="sm-link btn btn-sm btn-success"><b>Adicionar</b></a>
                              <a onclick="limpar('file_imagem_logo_empresa', 'imagem_logo_empresa')" style="color:#fff;width:98px;" class="sm-link btn-sm btn btn-danger"><b>Limpar</b></a>
                          </div>
                      </div>
                      </div>
                      <!--<input  type="hidden" name="imagem_logo"   class="form-control" />-->
                      <input style="display:none" id="file_imagem_logo_empresa" name="imagem_logo_empresa" type="file" onchange="imagem(this, 'imagem_logo_empresa')"   class="form-control" />
                    </div>
                    </div>
                    <div class="col">
            
                    </div>   
                </div>
                   
            
               

              <div class="modal-footer">
                <button type="button" onclick="editEmpresa()" class="btn btn-primary">Salvar</button>
              </div>
            </form>
            </div>
          </div>
        </main>

      @include('gestor.templates.rodape')
  
      @include('gestor.templates.chamadas-down')
    <script>
      function abrirJanela(idFile) {
        document.getElementById(''+idFile+'').click();
      }
      function limpar(idFile, idImg) {
        document.getElementById(''+idFile+'').setAttribute("data-img", '');
        document.getElementById(''+idImg+'').setAttribute('src', '{{ asset('public/assets_gestor/img/img-padrao.png') }}');
        document.getElementById(''+idFile+'').value = '';
      }
      function imagem(obj, idImg) {
        var file = obj.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(){
          document.getElementById(''+idImg+'').setAttribute('src', reader.result);
          obj.setAttribute("data-img", reader.result);
          setImagem = reader.result;
        }
        reader.onerror = function (error) {
          console.log('Error: ', error);
        };
      }



      //script cpf_cnpj
      function maskCpfCnpj(v) {
            if(v=='JURIDICA'){
              $('input[name=cpf_cnpj]').attr('maxlength',18).mask('99.999.999/9999-99');
            }else if(v=='FISICA'){
              $('input[name=cpf_cnpj]').attr('maxlength',14).mask('###.###.###-##');
            }
      }

      function mascaraMutuario(o,f){
        v_obj=o
        v_fun=f
        setTimeout('execmascara()',1)
      }

      function execmascara(){
          v_obj.value=v_fun(v_obj.value)
      }

      function cpfCnpj(v){
          v=v.replace(/\D/g,"")

          if (v.length <= 14) { 
              v=v.replace(/(\d{3})(\d)/,"$1.$2")
              v=v.replace(/(\d{3})(\d)/,"$1.$2")
              v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
          } else {  
              v=v.replace(/^(\d{2})(\d)/,"$1.$2")        
              v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
              v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
              v=v.replace(/(\d{4})(\d)/,"$1-$2")
          }
          return v
      }
      
      function pCampo(nome, valor) {
            var obj = document.querySelector('[name=' + nome + ']');
            if (obj) {
              obj.value = valor;
            }
      }
      pCampo('pessoa', '{{$data["empresa"]["pessoa"]}}');
      pCampo('uf', '{{$data["empresa"]["uf"]}}');
      pCampo('cidade', '{{$data["empresa"]["cidade"]}}');

      function editEmpresa() {
        if (!$('select[name=pessoa]').val()) {
          alertaPadrao.returnMensagem('Preecha o campo pessoa.', 'top', 'infor');
          return false;
        }
        if (!$('input[name=nome]').val()) {
          alertaPadrao.returnMensagem('Preecha o campo nome.', 'top', 'infor');
          return false;
        }
        if (!$('input[name=cpf_cnpj]').val()) {
         alertaPadrao.returnMensagem('Preecha o campo CPF/CNPJ.', 'top', 'infor');
         return false;
        }      
        if (!$('input[name=telefone]').val()) {
          alertaPadrao.returnMensagem('Preecha o campo telefone.', 'top', 'infor');
          return false;
        }
        if (!$('select[name=uf]').val()) {
          alertaPadrao.returnMensagem('Preecha o campo UF.', 'top', 'infor');
          return false;
        }
        if (!$('select[name=cidade]').val()) {
          alertaPadrao.returnMensagem('Preecha o campo cidade.', 'top', 'infor');
          return false;
        }

        var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('form[name=formEmpresa]'));

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
        ajax.open('POST','{{env("APP_URL")}}empresa/salvar', true); //faz a requisicao dos dados, via post
        ajax.send(form_data);
        return false;
    }

    function getCidades(uf){
      var ajax = new XMLHttpRequest(), form_data = new FormData();
      form_data.append('uf',uf);

      ajax.onload = function (e) {
        var r = JSON.parse(this.responseText);
        if (r.status) {
         var html ='';
         for(ob of r.cidades){
          html += ` <option value="${ob}" >${ob}</option>`;
         }
         document.querySelector('[name=cidade]').innerHTML =html;
        } else {
        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
        }
      };
      ajax.open('POST','{{env("APP_URL")}}empresa/cidades', true); //faz a requisicao dos dados, via post
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