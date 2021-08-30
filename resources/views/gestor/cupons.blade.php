<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <title> Cupons - op Aqui</title>
    <meta name="description" content="">
    <script>
      var localizacao = {lat: -8.4571033, lng: -34.4795055};
        document.addEventListener('DOMContentLoaded', function () {
          navigator.geolocation.getCurrentPosition(function (position) {
            localizacao.lat = position.coords.latitude;
            localizacao.lng = position.coords.longitude;
          });
        });
    </script>
  </head>
  <body >
  
    <div >

    @include('gestor.templates.header')

        <main style="margin:auto" class="main-content p-0 ">
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <h4 class="pt-1 ml-1" ><i class="fas fa-chevron-right"></i> <b>Cupons</b></h4>
            <!-- Page Header -->
            <!-- <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Blog Overview</h3>
              </div>
            </div> -->
            <!-- End Page Header -->
            {{-- @include('site.templates.menu') --}}
            {{-- @include('site.templates.menu-pesquisa') --}}
            
            <div class="card mb-3 card-padrao " >  
              <div class="tab-content">
                <div class="d-flex justify-content-between bd-highlight col-12 col-sm-12">
                  <div><h5 class="pt-1" style="margin-left:-15px"  >Cupons criados:</h5></div>
                  <div> <button onclick='cadCupom()' style="color:#fff;margin-right:-13px!important"  class="btn btn-warning" ><b>Novo cupom</b></button></div>
                </div>
                 
                  <div class="list-group" style="margin-top: 15px" id="list-group-order">
                    <table class="table table-bordered ">
                      <thead>
                        <tr>
                          <th >Código</th>
                          <th  style="text-align: center;" >Plano</th>
                          <th  style="text-align: center;" width=90>Valor (R$)</th>
                          <th  style="text-align: center;" width=8>Utilizados</th>
                          <th  style="text-align: center;" width=8>Quantidade</th>
                          <th  style="text-align: center;" width=10 >Vencimento</th>
                          <th  style="text-align: center;" width=5>Ativo</th>
                          <th  style="text-align: center;" width=6>Deletar</th>
                          <th  style="text-align: center;" width=5>Editar</th>
                        </tr>
                      <tbody>          
                      @foreach($data['cupons'] as $key => $val)
                        <tr>
                          <td >{{$val['codigo']}}</td>
                          <td style="text-align: center;" >{{$val['plano']}}</td>
                          <td style="text-align: right;" >{{number_format($val['valor'], 2, ',', '.')}}</td>
                          <td style="text-align: center;" >{{$val['utilizados']}}</td>
                          <td style="text-align: center;" >{{$val['quantidade']}}</td>
                          <td align="center">{{retrieveDatetime($val['data_validade'])}}</td>
                          <td style="text-align: center;" >{{$val['status']}}</td>
                          <td align="center"><button type="button" class="btn btn-danger btn-sm" style="padding: 4px" onclick="deletarCupom('{{$val['id']}}')" ><i class="fa fa-trash-alt fa-fw" ></i></button></td>
                          <td align="center"><button type="button" class="btn btn-primary btn-sm" style="padding: 4px" onclick="modalFormCup('{{$val['id']}}')"  ><i class="fa fa-pencil-alt fa-fw" ></i> <span class="alterar"></span></button></td>
                        </tr>
                      @endforeach   
                    </table>               
                  </div>
              </div>

                            <!-- Modal -->
                            <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                  <h3 class="modal-title"  id="tituloModalLabel">Cadastro</h3>
                                  <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
                                </div>
                                <div class="modal-body">
                                  <form name="formEnt">
                                    <div class="form-group">
                                      <label for="FormControlNome">Código*:</label>
                                      <input name="codigo" onkeyup="this.value=this.value.toUpperCase();" type="text" class="form-control" id="FormControlNome" >
                                    </div>
                                    <div class="form-group">
                                      <label for="iPlano">Plano:</label>
                                      <select id='iPlano' name="id_plano"  class="form-control" >
                                        <option value="">Sem plano</option>
                                        @foreach($data['planos'] as $pln)
                                        @if($pln['tipo']!='GRATUITO')
                                          <option value="{{$pln['id']}}">{{$pln['titulo']}}</option>
                                          @endif
                                        @endforeach
                                      </select>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label for="FormControlEmail">quantidade*:</label>
                                    <input type="text" name="quantidade" onkeypress="return mascaraCep(event, this, '###########');" class="form-control" id="FormControlEmail" >
                                  </div>
                                  <div class="form-group">
                                    <label for="FormControlCPF">Valor*:</label>
                                    <input name="valor" type="text" placeholder="R$ 0,00"  class="form-control" id="money" >
                                    <script>
                                      $('#money').mask('000.000.000.000.000,00',{reverse: true});
                                    </script> 
                                  </div>
                                  <div class="form-group">
                                    <label for="FormControlTelefone">Data de vencimento*:</label>
                                    <input name="data_validade" type="text" placeholder="00/00/0000" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');"  class="form-control" id="FormControlTelefone" >
                                  </div>
                                  <div class="form-group">
                                    <label for="FormControlCargo">Ativo*:</label>
                                    <select id='iPlano' name="status"  class="form-control" >
                                      <option value="SIM">Sim</option>
                                      <option value="NÃO">Não</option>
                                    </select>
                                  </div>

                                  <!-- <button type="button"  class="btn btn-primary">Salvar</button> -->
                                  </form>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                  <button type="button" onclick="cadEditCupom();" class="btn btn-primary">Salvar</button>
                                </div>
                                </div>
                              </div>
                              </div>
                            
          
            </div>
        </main>
        @include('gestor.templates.rodape')
    </div>
    @include('gestor.templates.chamadas-down')
    <script type="text/javascript" src="{{asset('public/assets_gestor/js/questionario.js')}}"></script>

    <script>
      var cupom='';
      function  cadCupom() {
        cupom='';
        $("#tituloModalLabel").html("Cadastrar");
        // document.getElementById("boxSenha").style.display = '';
        document.querySelector('input[name=codigo]').disabled=false;
        $("#tituloModalLabel").html("Cadastro");
        document.querySelector('form[name=formEnt]').reset();
        $('#modalForm').modal();
      }

          ///////
    function cadEditCupom() {
     if (!$('input[name=codigo]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo código.', 'top', 'infor');
      return false;
     }
     if (!$('input[name=quantidade]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo quantidade.', 'top', 'infor');
      return false;
     }
     if (!$('input[name=valor]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo valor.', 'top', 'infor');
      return false;
     }

     if (!$('input[name=data_validade]').val()) {
      alertaPadrao.returnMensagem('Preecha o campo data de vencimento.', 'top', 'infor');
      return false;
     }


     var ajax = new XMLHttpRequest(), form_data = new FormData(document.querySelector('form[name=formEnt]'));
     form_data.append('id', cupom);

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
     ajax.open('POST',"{{env('APP_URL')}}cupons/salvar", true); //faz a requisicao dos dados, via post
     ajax.send(form_data);
     return false;
    }
    ///////
    function modalFormCup(cod) {
      colaborador=cod;
     $("#tituloModalLabel").html("Editar");
     var ajax = new XMLHttpRequest(), form_data = new FormData();
     form_data.append('id', cod);
     ajax.onload = function(e){
      var r = JSON.parse(this.responseText);
      if (r.status) {
        cupom = r.data.id;
       document.querySelector('input[name=codigo]').disabled=true;
       $('input[name=codigo]').val(r.data.codigo);
      //  $('input[name=cupom]').val(r.data.cupom);
       $('select[name=id_plano]').val(r.data.id_plano);
       $('input[name=valor]').val(r.data.valor);
       $('input[name=quantidade]').val(r.data.quantidade);
       $('input[name=data_validade]').val(r.data.data_validade);
       $('select[name=status]').val(r.data.status);
       $('#modalForm').modal();
      } else {
       alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
      }
     };
     ajax.open('POST',"{{ env('APP_URL') }}cupons/show", true); //faz a requisicao dos dados, via post
     ajax.send(form_data);
    }
    ///////


    /////
    // function delEntrevistador(cod) {
    //   if(totalMaster<=1){
    //     alertaPadrao.returnMensagem('Atenção: Esse é o último perfil MASTER e não é possível deletar.', 'top', 'erro');
    //     return false;
    //   }
    //  $('<div></div>').appendTo('body')
    //          .html('<div><h6>Deseja deletar este colaborador?</h6></div>')
    //          .dialog({
    //           autoOpen: true,
    //           'modal': true,
    //           'title': 'Alerta',
    //           'zIndex': 10000,
    //           //'autoOpen': true,
    //           width: 400,
    //           buttons: [
    //            {
    //             text: "Ok",
    //             click: function () {
    //              var ajax = new XMLHttpRequest(), form_data = new FormData();
    //              form_data.append('id', cod);
    //              ajax.onload = function (e) {
    //               var r = JSON.parse(this.responseText);
    //               if (r.status) {
    //                alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
    //                setTimeout(function () {
    //                 location.reload();
    //                }, 3000);
    //               } else {
    //                alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
    //               }
    //              };
    //              ajax.open('POST', '{{env('APP_URL')}}cupom/delete', true); //faz a requisicao dos dados, via post
    //              ajax.send(form_data); //envia o form
    //              $(this).dialog("close");

                 
    //              setTimeout(() => {
    //                 location.reload(); //atualizar a pagina para remover item deletado
    //               }, 1000); // atualizar após 1 seg, para delet nao ser cancelado no processo


    //             }
    //            },
    //            {
    //             text: "Cancel",
    //             click: function () {
    //              $(this).dialog("close");
    //             }
    //            }
    //           ]
    //          });
    // }

      function deletarCupom(cod) {
        $('<div></div>').appendTo('body')
          .html('<div><h6>Deseja deletar esse cupom?</h6></div>')
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
              ajax.open('POST', '{{env("APP_URL")}}cupons/delete', true);//faz a requisicao dos dados, via post
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
    </script>
  </body>
  </body>
</html>  