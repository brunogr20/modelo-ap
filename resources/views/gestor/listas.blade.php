<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <title>Listas - op Aqui</title>
    <meta name="description" content="">
  </head>
  <body class="h-100">

    <div >

    @include('gestor.templates.header')
              
        <main style="margin:auto" class="main-contents p-0 ">
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
            {{-- <hr style="margin-top:0px"/> --}}

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <button onclick="abrirModal('', '');" type="button" style="margin:auto" class="mb-2 btn btn-pill btn-outline-primary btn-lg">
                <i class="material-icons  mr-1">person_add</i> <b>CLIQUE AQUI E CRIE UMA LISTA</b>
              </button>
            </div>
         
            <div class="row">
              <?php foreach ($data['listas'] as $key => $value){?>
              <!-- Users Stats -->
                {{-- <div class="col-lg-12 col-md-12 col-sm-12 mb-4"> --}}
                  <div class="col-md-6 bloco-card"  >
                  <div class="card card-small card-pesquisa-inativa">
                    <div class="card-header border-bottom" style="display: flex;">
                    <h6 class="m-0 mr-auto">{{$value['titulo']}}</h6>
                    <span class="">
                      <img class="sm-link" style="width:19px" title="Deletar" onclick="deletarLista({{$value['id']}})"  src="{{url('/public/assets_gestor/img/delete-inativo.png')}}"/>
                      </span>
                    </div>
                    <div class="card-body pt-0">
                      <div class="row  py-2 bg-light">
                        <div class="col-12 col-sm-8">
                        <span class="text-left">Criada em </span>{{$value['dataCadastro']}}<br/>
                        <span class="text-left">Total de contatos: </span>{{$value['contatos']}}<br/>
                        </div>
                        <div class="col-12 col-sm-4 d-flex mb-2 mb-sm-0">
                          {{-- <a class=" ml-auto mr-auto ml-sm-auto mr-sm-0 mt-3 mt-sm-0" href="{{env('APP_URL')}}pesquisas"> --}}
                            <div class="btn-group btn-group-sm">
                            <a href="{{env('APP_URL')}}lista/{{$value['id']}}/{{strUrl($value['titulo'])}}">
                             <button type="button" class="btn btn-white"><b>Contatos</b></button>
                            </a>
                              {{-- <button onclick="deletarLista({{$value['id']}})" type="button" class="btn btn-white">
                                <span class="text-danger">
                                  <i class="material-icons">clear</i>
                                </span> Deletar Lista </button> --}}
                              <a>
                                <button onclick="abrirModal({{$value['id']}}, '{{$value['titulo']}}');" type="button" class="btn btn-white">
                                
                                    <i class="material-icons">more_vert</i>
                                    <b>Editar</b>
                                 
                                </button>
                              </a>
                            </div>
                          {{-- </a>  --}}
                        </div>
                      </div>
                  
                    </div>
                  </div>
                </div>
              <?php } ?>
             </div>

            </div>
          </div>
         
          <!-- Modal -->
          <div class="modal fade" id="modalFormLista" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <h3 class="modal-title"  id="tituloModalLabel">Lista</h3>
              <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
              </div>
              <div class="modal-body">
              <div class="form-group">
                <label for="exampleFormControlInput1">Título:</label>
                <input type="text" name="titulo" class="form-control" id="exampleFormControlInput1">
              </div>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="salvarLista()" class="btn btn-primary">Salvar</button>
              </div>
            </div>
            </div>
          </div>
        </main>
        @include('gestor.templates.rodape')
      </div>
  
    @include('gestor.templates.chamadas-down')
    <script>
      var codListaAt = '';
      function abrirModal(cod, titulo) {
       if (cod) {
        codListaAt = cod;
        document.getElementById("tituloModalLabel").innerHTML = 'Editar lista';
        document.getElementsByName('titulo')[0].value = titulo;
       } else {
        codListaAt = '';
        document.getElementsByName('titulo')[0].value = '';
        document.getElementById("tituloModalLabel").innerHTML = 'Criar lista';
       }
       $('#modalFormLista').modal();
      }
  
      function deletarLista(cod) {
       $('<div></div>').appendTo('body')
               .html('<div><h6>Deseja deletar esta lista?</h6></div>')
               .dialog({
                autoOpen: true,
                modal: true, title: 'Alerta', zIndex: 10000, autoOpen: true, width: 400,
                buttons: [
                 {
                  text: "Ok",
                  click: function (obj) {
                   var ajax = new XMLHttpRequest(), form_data = new FormData();
                   form_data.append('id', cod);
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
                   ajax.open('POST', '{{env("APP_URL")}}lista/delete', true);//faz a requisicao dos dados, via post
                   ajax.send(form_data);//envia o form
                   $(this).dialog("close");

                  setTimeout(() => {
                    location.reload(); //atualizar a pagina para remover item deletado
                  }, 1000); // atualizar após 1 seg, para delet nao ser cancelado no processo

                  }
                 },
                 {
                  text: "Cancel",
                  click: function () {
                   $(this).dialog("close");
                  }
                 }
                ]
               });
      }
      function salvarLista() {
       var ajax = new XMLHttpRequest(), form_data = new FormData();
       if (!document.getElementsByName('titulo')[0].value) {
        alertaPadrao.returnMensagem('Preecha o campo título.', 'top', 'infor');
        return  false;
       }
       form_data.append('id', codListaAt);
       form_data.append('titulo', document.getElementsByName('titulo')[0].value);
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
       ajax.open('POST','{{env("APP_URL")}}lista/salvar', true);//faz a requisicao dos dados, via post
       ajax.send(form_data);//envia o form
      }
      </script>
  </body>
</html>