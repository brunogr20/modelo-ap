<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('public/assets_gestor/css/login.css') }}">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

@include('gestor.templates.chamadas-top')

<body>
  <div class="barra-login-topo" style="display:flex;flex-direction:column;align-items:center;padding-top:0px;">
    <img title="op Aqui" alt="op Aqui"  style="width:140px" src="{{url('/public/assets_gestor/img/logoBranca.png')}}"/>
  </div>
  {{-- bloco de recuperar senha --}}
  <div class="senha-container"> </div> 

  <div class="row" id="pwd-container"> 
   <div class="col-md-4"></div>
   <div class="col-md-4">  
     <section class="login-form">
      <h3 align="center">Login</h3>
      <form method="post" style="padding: 7px;" name="login" role="login">
      <input type="text"  name="login" placeholder="CPF"  class="cpfOuCnpj form-control input-lg"  />
      <!--<input type="text"  name="login" placeholder="CPF" name="login" maxlength="14" onkeypress="return mascaraCep(event, this, '###.###.###-##');" required class="form-control input-lg" />-->
      <input type="password" name="password" class="form-control input-lg" id="password"  placeholder="Senha" required="" />
      <div class="pwstrength_viewport_progress"></div>
      <button type="submit"  class="btn btn-lg btn-primary btn-block">Entrar</button>      
      <div>
       {{-- <a data-toggle="modal" data-target="#modalRecuperaSenha">Recuperar senha</a> --}}
       <a class="sm-link" onClick="MudarestadoLogin('pwd-container');">Recuperar senha</a>
      </div>
     </form>
    </section>  
   </div>
   <div class="col-md-4"></div>
  </div>
</body>

<script>
 function MudarestadoLogin(divid){
    document.getElementById(divid).style.display = "none";
    formRecSenha();
}

function formRecSenha(){
  blocoRecSenha = '';

  blocoRecSenha += `
  <div class="row" id="RecSenha-container"> 
   <div class="col-md-4"></div>
   <div class="col-md-4">  
     <section class="login-form">
     <h3 align="center">Recuperar Senha</h3>
     <form name="recSenha" style="padding: 7px;" role="login" id="recSenha">
      <input type="text" name="email" id="email" class="form-control input-lg" id="email"  placeholder="Email" required="" />
      <div class="pwstrength_viewport_progress"></div>
      <button onclick="recuperandoSenha();" class="btn btn-lg btn-primary btn-block">Enviar Dados</button>
      <div>
        <a class="sm-link" onClick="MudarestadoSenha('RecSenha-container');">Login</a>
      </div>
     </form>
    </section>  

  `;
  $( "div.senha-container" ).html(blocoRecSenha);

}

function MudarestadoSenha(divid){
    document.getElementById(divid).style.display = "none";
    document.getElementById('pwd-container').style.display = "inline";
    // $( "div.senha-container" ).html("");
}
  var options = {
    onKeyPress: function (cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
			$('.cpfOuCnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
		}
	}
		
	$('.cpfOuCnpj').length > 11 ? $('.cpfOuCnpj').mask('00.000.000/0000-00', options) : $('.cpfOuCnpj').mask('000.000.000-00#', options);

</script>

<script>

  function recuperandoSenha(){

    //funcao para evitar de atualizar pagina ou envie dados
    $("#recSenha").submit(function(event){
      event.preventDefault();
    });

    var dados = {'email':$( "#email" ).val()};
    // console.log(dados);
    $.ajax({
    url: "{{ env('APP_URL') }}rec-senha",
    type: 'POST',
    dataType: 'JSON',
    data: dados,
    success: function (r) {
     if (r.status) {
      alert(r.msg);
      setTimeout(function () {
         location.reload();
      }, 1000);
     } else {
      alert(r.msg);
     }
    }
   });
   return false;
  }


 $(function () {
  $('button[type=submit]').click(function () {
   if (!$('input[name=login]').val()) {
    alert('Preecha o campo CPF.');
    return false;
   }
   if (!$('input[name=password]').val()) {
    alert('Preecha o campo senha.');
    return false;
   }
 
   var dados = $('form[name=login]').serialize();
  
   $.ajax({
    url: "{{ env('APP_URL') }}auth",
    type: 'POST',
    dataType: 'JSON',
    data: dados,
    success: function (r) {
     if (r.status) {
      document.location.href = r.url;
     } else {
      alert(r.msg);
     }
    }
   });
   return false;
  });
 });
 
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