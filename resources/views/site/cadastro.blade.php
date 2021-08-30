<!DOCTYPE html>
<html lang="pt-br">
    <head>
        @include('site.templates.template-chamada') 
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets_gestor/img/favicon.png') }}">
        <link href="{{ asset('public/assets_gestor/alertZ/alert.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('public/assets_gestor/alertZ/classNotification.js') }}" ></script>
        <script>
            var alertaPadrao = new Alert();
        </script>
</head>
<body>
       <section class="comprar" id="dados">
        <div class="boneco esquerda">
            <img class="marca esquerda" src="{{asset('public/assets_site/imgs/logoBranca.png')}}" alt="">
            <div class="imagem">
                <img class="esquerda" src="{{asset('public/assets_site/imgs/Ativo2.png')}}" alt="">
            </div>
        </div>
        <form name="formDadosPessoais" class="passo direita">
            <div class="formulario" style="margin: 0em auto 0 auto;">
                @foreach($data['planos'] as $key  => $item)
                <h1 class="tituloCompra" style="font-size: 2.0rem;">{{$item['titulo']}}</h1>
                <h2 class="subtituloCompra" style="font-size: 1.5rem;">informe seus dados</h2>
                <select class="caixaGrande caixa" style="padding: 0.2rem;" name="tipo_pessoa" onchange="maskCpfCnpj(this.value)" id="tipo_pessoa">
                    <option value="FISICA">Pessoa Física</option>
                    <option value="JURIDICA">Pessoa Jurídica</option>
                </select>
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="nome_empresa" type="text" placeholder="Empresa*">
                <input class="caixaPequena caixa" style="padding: 0.2rem;" name="telefone_empresa" type="text" placeholder="Telefone Empresa*" maxlength="15" onkeypress="return mascara(event, this, '(##) #####-####');">
                <input class="caixaPequena caixa" style="padding: 0.2rem;" name="cpf_cnpj_empresa" maxlength="14"  onkeypress='mascaraMutuario(this,cpfCnpj)' onblur='clearTimeout()' type="text" placeholder="CPF/CNPJ Empresa*">
                <select class="caixaGrande caixa" style="padding: 0.2rem;" name="uf" id="" onchange="getCidades(this.value)">
                    <option value="">UF</option>
                    @foreach(ufData() as $uf)
                    <option value='{{$uf}}'>{{$uf}}</option>
                    @endforeach
                </select>
                <select class="caixaGrande caixa" style="padding: 0.2rem;" name="cidade" id="" onchange="getCidades(this.value)">
                    <option value="" selected>Cidade Empresa*</option>
                </select>
              
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="nome" type="text" placeholder="Nome do contato*">
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="email" type="text" placeholder="E-mail*">
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="confirma_email" type="text" placeholder="Confirmação do E-mail*">
                <input class="caixaPequena caixa" style="padding: 0.2rem;" name="telefone" type="text" placeholder="Telefone Contato*" maxlength="15" onkeypress="return mascara(event, this, '(##) #####-####');">
                <input class="caixaPequena caixa" style="padding: 0.2rem;" name="cpf_cnpj" maxlength="14"  onkeypress='mascaraMutuario(this,cpfCnpj)' onblur='clearTimeout()' type="text" placeholder="CPF*">
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="password" type="password" placeholder="Senha*">
                <input class="caixaGrande caixa" style="padding: 0.2rem;" name="confirma_password" type="password" placeholder="Confirmação de senha*">                    
                <input class="caixaGrande caixa" style="padding: 0.2rem;" value="{{$item['id']}}" type="hidden" name="id_plano" placeholder="">                    
                <div class="botoes" style="margin-bottom: 2em;">
                    <button type="button" onclick="goBack()" >voltar</button>
                    <button type="button" onclick="infromarDados({{$item['id']}})" >avançar</button>
                </div>
                @endforeach

            </div>
        </form>
        
    </section>



    <script>
    function goBack() {
        window.location.replace("{{env("APP_URL")}}#slide2");
    }
        function infromarDados() {
            // setTimeout(function () {
            //     // location.reload();
            //     location.reload();
            //     location.href=`{{env('APP_URL')}}confirma-dados`;
            // }, 0);
            //funcao para evitar de atualizar pagina ou envie dados

            if (!$('input[name=nome_empresa]').val()) {
                alertaPadrao.returnMensagem('Preencha o campo Empresa.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=telefone_empresa]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Telefone.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=cpf_cnpj_empresa]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo CPF/CNPJ.', 'top', 'erro');
            return false;
            }      
            if (!$('select[name=cidade]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Cidade.', 'top', 'erro');
            return false;
            }      
            if (!$('input[name=nome]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Nome do Contato.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=email]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo E-mail.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=confirma_email]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Confirmação do E-mail.', 'top', 'erro');
            return false;
            }
            if ($('input[name=confirma_email]').val() != $('input[name=email]').val() ) {
                alertaPadrao.returnMensagem('Os campos E-mail e Confirmação do E-mail devem ser iguais.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=telefone]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Contato.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=cpf_cnpj]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo CPF.', 'top', 'erro');
            return false;
            } 
            if (!$('input[name=password]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Senha.', 'top', 'erro');
            return false;
            }
            if (!$('input[name=confirma_password]').val()) {
                alertaPadrao.returnMensagem('Preecha o campo Confirmação de Senha.', 'top', 'erro');
            return false;
            }
            if ($('input[name=password]').val() != $('input[name=confirma_password]').val() ) {
                alertaPadrao.returnMensagem('Os campos Senha e Confirmação de senha devem ser iguais.', 'top', 'erro');
            return false;
            }
            
            var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('form[name=formDadosPessoais]'));

            ajax.onload = function (e) {
            var r = JSON.parse(this.responseText);
            if (r.status) {
                // alert('Dados enviados com sucesso!');
            //   alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
            setTimeout(function () {
                document.location.href = r.url;
            }, 0);
            } else {
                // alert('Algo ocorreu de forma inesperada.');
              alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
            }
            };
            ajax.open('POST','{{env("APP_URL")}}cadastro/salvar', true); //faz a requisicao dos dados, via post
            ajax.send(form_data);
            return false;
        }

        function maskCpfCnpj(v) {
            if(v=='JURIDICA'){
                $('input[name=cpf_cnpj_empresa]').attr('maxlength',18).mask('99.999.999/9999-99');
            }else if(v=='FISICA'){
                $('input[name=cpf_cnpj_empresa]').attr('maxlength',14).mask('###.###.###-##');
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
        

        function mascara(e, src, mask) {
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

        
        function getCidades(uf){
      if(uf){
        var ajax = new XMLHttpRequest(), form_data = new FormData();
        form_data.append('uf',uf);
          ajax.onload = function (e) {
          var r = JSON.parse(this.responseText);
          if (r.status) {
            var html=' <option value="" selected >Escolha uma opção</ion-option>';
          for(var i in r.cidades){
            html += `   <option value="${r.cidades[i]}" >${r.cidades[i]}</option>`;
          }
            document.querySelector('[name=cidade]').innerHTML=html;
          } else {
            alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
          }
          };
          ajax.open('POST','{{env("APP_URL")}}novo-usuario/cidades', true); //faz a requisicao dos dados, via post
          ajax.send(form_data);
        }else{
          document.querySelector('[name=cidade]').innerHTML= ' <option value="" selected >Escolha uma opção</ion-option>';
        }
      return false;

    }
    </script>
</body>
</html>