
            <div class="modal fade" id="modalVerEntrevista" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" style="max-width:80%" role="document">
                  <div class="modal-content" >
                  <div class="modal-header">
                    <div>
                      <h4 class="modal-title"  id="tituloModalLabel">{{$data['pesquisa']['titulo']}}</h4>
                      <h6 style="margin:0px" id="detalheEntrevista"></h6>
                      <p style="margin:0px" id="detalheLocalizacao"></p>
                    </div>
                    <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
                  </div>
                  <div class="modal-body" style="padding:5px">
                    <iframe src="" id="modalIframe" style="zoom:0.60" width="100%" height="750" frameborder="0"></iframe>
                  </div>
                </div>
              </div>
            </div>
<script>
     function verEntrevista(id,detalhes,lon,lat){
        var dt ='';
        $('#detalheEntrevista').html(`<b>Detalhes: </b>${detalhes}`);
        if(lon&&lat){
          dt =`<span class='sm-link' title='Longitude'><b>Lon: </b>${lon}</span> <span class='sm-link' title='Latitude'><b>Lat: </b>${lat}</span>`;
        }
        $('#detalheLocalizacao').html(dt);

      $('#modalIframe').attr('src','{{env("APP_URL")}}pesquisa/entrevistas/dtlh/{{$data["pesquisa"]["id_token"]}}/'+
       id+"{{$data['grupoLink']=='pesquisa'?'/sim':''}}");
        $('#modalVerEntrevista').modal();
      }
      function del(id){
      $('<div></div>').appendTo('body')
              .html('<div><h6>Deseja deletar essa entrevista?</h6></div>')
              .dialog({
              autoOpen: true,
                      'modal': true,
                      'title': 'Alerta',
                      'zIndex': 10000,
                      //  'autoOpen': true,
                      width: 400,
                      buttons: [
                      {
                      text: "Ok",
                              click: function () {
                              var ajax = new XMLHttpRequest(), form_data = new FormData();
                                      form_data.append('entrevista',id);
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
                                      ajax.open('POST', '{{env("APP_URL")}}entrevista/delete', true); //faz a requisicao dos dados, via post
                                      ajax.send(form_data); //envia o form
                                      $(this).dialog("close");
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