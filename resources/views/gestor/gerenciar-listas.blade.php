<?php
$perguntas = 1;
?>
<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <title>Gerenciar envios - op Aqui</title>
    <meta property="og:url"           content="{{$data['linkPesquisa']}}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="{{$data['pesquisa']['titulo']}}" />
    <meta property="og:description"   content="{{$data['pesquisa']['descricao']}}" />
    @if($data['pesquisa']['imagem_logo'])
      <meta property="og:image" content="{{$data['pesquisa']['imagem_logo']}}" />
    @endif
  </head>
  <body class="h-100">
    <div >

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
          @include('gestor.templates.menu')
          @include('gestor.templates.menu-pesquisa')
       
         <div class="card mb-3 card-padrao" >
          <ul class="nav nav-abas {{$data['pesquisa']['emAndamento']?'nav-abas-ativo':'nav-abas-inativo'}}">
              <li class="nav-item">
                  <a class="nav-link {{(!$data['aba']||$data['aba']=='compartilhamentos')?'active':''}}" href="#compartilhamentos" onclick="abasPesquisa('compartilhamentos')" data-toggle="tab">Compartilhamentos</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{($data['aba']=='historico')?'active':''}}" href="#historico" onclick="abasPesquisa('historico')" data-toggle="tab">Histórico</a>
              </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade in {{(!$data['aba']||$data['aba']=='compartilhamentos')?'active show':''}}" id="compartilhamentos">
              <h5 class="py-1" >Compartilhe sua pesquisa:</h5>

              LINK DA PESQUISA: <a href="{{$data['linkPesquisa']}}" target="_black" >{{$data['linkPesquisa']}} </a>
              <span class="sm-link" onclick="copia();" style="margin-top: -5px"><i  class="far fa-copy"></i></span>
      
              <hr/>
              
              <div class="row">
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-2 sm-link">
                  <div class="card-body" style="display: flex;flex-direction: column;">
                  <img  style="margin:auto;" src="{{asset('public/assets_gestor/img/layout.png')}}" />
                    <p align="center" style="margin-top: 24px;">Baixar o aplicativo</p>
                  </div>
                </div>
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-2 sm-link" onclick="downloadQRcode()">
                  <div class="card-body"  style="display: flex;flex-direction: column;">
                    <div>
                      <div style="width:170px;margin: auto;">
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(170)->generate($data['linkPesquisa'])) !!} ">
                      </div>
                    <p align="center" style="margin-top:10px;margin-bottom:0px;">Baixar o QR Code</p>
                  </div>
                  </div>
                </div>
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-3 sm-link" onclick="openEnviarLinkEmail()">
                  <div class="card-body"  style="display: flex;flex-direction: column;">
                    <img  style="margin:auto;" src="{{asset('public/assets_gestor/img/message.png')}}" />
                    <p align="center" style="margin-top: 24px;"> Enviar link para uma lista de  emails</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-2 " onclick="openRedesSociais()">
                  <div class="card-body"  style="display: flex;flex-direction: column;">
                    {{-- <img  style="margin:auto;" src="{{asset('public/assets_gestor/img/social-media.png')}}" /> --}}
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                    </script>
                  
                    <div type="button" style="
                    margin:auto;" class="
                    fb-share-button" data-size="large"></div>

                    <p align="center" style="margin-top: 25px;">Facebook</p>
                  </div>
                </div>
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-2 sm-link " onclick="openRedesSociais('whatsapp')">
                  <div class="card-body"  style="display: flex;flex-direction: column;">
                    <img  style="margin:auto;" src="{{asset('public/assets_gestor/img/whatsapp.png')}}" />
                    <p align="center" style="margin-top: 25px;">Whatsapp</p>
                  </div>
                </div>
                <div class="card m-3 col-lg col-md-3 col-sm-3 mb-2 sm-link " onclick="openRedesSociais('twitter')">
                  <div class="card-body"  style="display: flex;flex-direction: column;">
                    <img  style="margin:auto;" src="{{asset('public/assets_gestor/img/twitter.png')}}" />
                    <p align="center" style="margin-top: 25px;">Twitter</p>
                  </div>
                </div>
              </div>

              <!-- Modal -->
              @include('gestor.componentes.modal-enviar-email')

              <script>
                function openRedesSociais(r){
                 if(r=='twitter'){
                  window.open("https://twitter.com/intent/tweet?text=Participe da nossa pesquisa. {{$data['linkPesquisa']}}", '_blank');
                 }else if(r=='whatsapp'){
                  window.open("https://api.whatsapp.com/send?text=Participe da nossa pesquisa. {{$data['linkPesquisa']}}", '_blank');
                 }
                }
              </script>
      
            </div>
            
            <div class="tab-pane fade in {{($data['aba']=='historico')?'active show':''}}" id="historico">
              <h5 class="py-1" >Histório dos links enviados por email:</h5>
              @if(!count($data['lista']['dListas']))
                <h6 class="py-1" >No momento não existe histório de links enviados.</h6>
              @else
              <div style="min-height: 377px;">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                    <td>Respondidos: </td><td align="center">{{$data['lista']['dGeral']['concluidos']}}</td>
                    </tr>
                    <tr>
                      <td>Pendentes: </td><td align="center">{{$data['lista']['dGeral']['pendentes']}}</td>
                    </tr>
                    <tr>
                      <td>Total: </td><td align="center">{{$data['lista']['dGeral']['totalLista']}}</td>
                    </tr>
                  </tbody>
                </table>
                <div>     
                  <button type="button" onclick="listaContatos(this)"  style="float: right;margin-right: 20px;" class="btn btn-primary mb-2 font-weight-bold ">Enviar novo e-mail</button>
                  {{-- <button type="button" onclick="listaContatos(this)" style="margin-right: 20px;" class="btn btn-primary mb-2">Enviar novo e-mail</button>    --}}
                </div>
                <br/>
                <h4 class="pt-2" align="center">Situação dos links enviados</h4>
              <div class="mt-4"></div>
                @foreach($data['lista']['dListas'] as $lista)
                  {{-- <h6 class="font-weight-bold ">{{$lista['titulo']}}</h6> --}}
                  <h6> <b>{{$lista['titulo']}}</b></h6>
                  <table class="table">
                  <tbody>
                    <tr >
                      <td><strong>NOME</strong></td>
                      {{-- <td width="100" align="center" ><strong>ENVIO</strong></td> --}}
                      <td width="100" align="center" ><strong>STATUS</strong></td>
                    </tr>
                  @foreach($lista['contatos'] as $contato) 
                  <tr title="{{$contato['email']}}" style="background: {{$contato['status_resposta']?'#c3e6cb':'#f5c6cb'}}">
                      <td>{{$contato['nome']}} </td>
                      {{-- <td width="100" align="center">{{$contato['tipo_envio']}}</td> --}}
                      <td width="100" align="center">{{$contato['status_resposta']?'RESPONDIDO':'PENDENTE'}}</td>
                    </tr>
                  @endforeach
                  </tbody>
                  </table>
                @endforeach         
                @endif
                <!-- Modal -->
                @include('gestor.componentes.modal-reenviar-email')
              
              </div>
            </div>
          </div>



         </div>

        </div>
       
        <input id="link_pesquisa_input" value="{{$data['linkPesquisa']}}" style="display:non;width:5px;border: none;" type="text" />
        <script>
          function copia(){
            var cop=document.querySelector("#link_pesquisa_input");
            cop.select();
            document.execCommand('copy');
            alertaPadrao.returnMensagem("Link copiado com sucesso", 'top', 'sucesso');
          }
        </script>
      </main>
        @include('gestor.templates.rodape')
    </div>
    @include('gestor.templates.chamadas-down')
<script>
  function downloadQRcode(){
    location.href ="{{env('APP_URL')}}pesquisa/download/qrcode/{{$data['pesquisa']['id']}}"
  }
  function abasPesquisa(aba){
    let url = '{{env("APP_URL")}}pesquisa/gerenciar-listas/{{$data["pesquisa"]["id"]}}/{{strUrl($data["pesquisa"]["titulo"])}}/'+aba; 
    window.history.pushState("object or string", "Title",url);
  }

  function listaAtiva(obj) {
    var e = obj.getElementsByTagName('input')[0];
        e.checked = !e.checked;
  
    for (var ob of document.querySelectorAll('[role=lista-sub-clientes]')){
      ob.style.display = 'none';
    }
    if(e.checked){
      document.querySelector('[data-box=contatos' + obj.getAttribute('data-lista') + ']').style.display = '';
    }
  }

  function segmentarContatos(lista) {
        var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('[name=seg-lista-' + lista + ']'));
        form_data.append('lista', lista);
        ajax.onload = function (e) {
          var r = JSON.parse(this.responseText);
          if (r.status) {
            var contatos = document.querySelector('[data-box=contatos' + lista + ']');
            for (var ob of contatos.getElementsByTagName('input')){
              if (r.contatos.includes(parseInt(ob.value))){
                ob.checked = true;
              } else{
                ob.checked = false;
              }
            }
          } else {
            alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
          }
        };
        ajax.open('POST','{{env("APP_url")}}lista/contato/segmentar', true); //faz a requisicao dos dados, via post
        ajax.send(form_data); //envia o form
  }

  function listaContatos() {
    $('#modalContatosEnviados').modal();
  }

  function enviarNovaPesquisa(obj) {
     var campos = {
     mensagem: $('textarea[name=mensagem]').val(),
    // tipo: $('input[name=tipo]:checked').val(),
     assunto: $('input[name=assunto]').val(),
     assinatura: $('textarea[name=assinatura]').val(),
    };
    if (!campos.assunto) {
     alertaPadrao.returnMensagem('Preecha o campo assunto.', 'top', 'infor');
     return false;
    }
    if (!campos.mensagem) {
     alertaPadrao.returnMensagem('Preecha o campo mensagem.', 'top', 'infor');
     return false;
    }
    // if (!campos.tipo) {
    //  alertaPadrao.returnMensagem('Escolha o tipo de envio.', 'top', 'infor');
    //  return false;
    // }
    ////// - - - - - - - 
    var arAux = [], n = 0;
    for (var ob of document.querySelectorAll('input[role=contatos]')){
     if (ob.checked&&ob.value) {
      // var lista = ob.getAttribute('data-lista');
      // if (arAux[lista]) {
      //  arAux[lista].push(ob.value);
      if(ob.value) {
       arAux.push(ob.value);
      }
    //  } else {
    //  arAux[lista] = [ob.value]
    //  }
     }
    }
    if (!arAux.length) {
     alertaPadrao.returnMensagem('Escolha pelo menos um contato.', 'top', 'infor');
     return false;
    }
    
    campos['contatos'] = JSON.stringify(arAux);
    campos['pesquisa'] = "{{$data['pesquisa']['id']}}";
    let fData =new FormData();
    for(let c in campos){
      fData.append(c, campos[c]);
    }
    if(document.getElementById('file_pd_email').files[0]){
      fData.append('imagem', document.getElementById('file_pd_email').files[0]);
    }
    //
    obj.disabled = true;
    obj.innerHTML = 'Enviando...';
    var ajax=new XMLHttpRequest(); // instancia um objto ajax;
    ajax.onload=function(e){
      var r=JSON.parse(this.responseText);
      obj.disabled = false;
      obj.innerHTML = 'Enviar';
      if(r.status){
        alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
        setTimeout(function(){
        location.reload();
        },2000);
      }else{
        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
      }
    };
    ajax.open('POST','{{env("APP_URL")}}gerenciar-listas/envio/novo/email',true); //faz a requisicao dos dados, via post
    ajax.send(fData); //envia o form
   return false;
    // $.ajax({
    //  url: base + 'request/switchAjaxListas.php',
    //  method: 'post',
    //  data: fData,
    //  dataType: "json",
    //  success: function (r) {
    //   obj.disabled = false;
    //   obj.innerHTML = 'Enviar';
    //   if (r.status) {
    //    alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
    //   } else {
    //    alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
    //   }
    //  }
    // });

  }
  function limpar() {
         document.getElementById('img_pd_email').setAttribute('src', '{{ asset('public/assets_gestor/img/img-padrao.png') }}');
         document.getElementById('file_pd_email').value = '';
  }
  function imagem(obj) {
    var file = obj.files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
      document.getElementById('img_pd_email').setAttribute('src', reader.result);
    //document.getElementById('img_pd_email').setAttribute("data-img", reader.result);
    // setImagem = reader.result;
      //arrImages[idImg] = reader.result;
    };
    reader.onerror = function (error) {
    console.log('Error: ', error);
    };
  }
  function abrirJanela(idFile) {
  document.getElementById('file_pd_email').click();
  }
</script>
 
  </body>
</html>