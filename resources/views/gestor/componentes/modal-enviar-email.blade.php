<div class="modal fade" id="modalEnviarLinkEmal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog" style="max-width:50%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="tituloModalLabel">Enviar novo e-mail</h3>
        <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
      </div>
      <div class="modal-body" style="padding:15px">
        <h5 class="py-1" style="margin-top: -15px" >Selecione uma lista para enviar os emails</h5>
      {{-- <button type="button" id="qrcode_pesquisa" onclick="baixarQRcode(this)" data-pesquisa="" class="btn btn-link"><b>Baixar QR Code</b></button>
        <a role="comp-twitter" target="_blank" href="">
        <button type="button" id="qrcode_pesquisa" onclick="baixarQRcode(this)" data-pesquisa="" class="btn btn-link"><b>Twitter</b></button>
        </a>    --}}
      {{-- <td><div style="width: 70px; height: 10px!important;margin-top: -6px;"><iframe src="" id="linkFacebbok" style="zoom:0.60" width="100%" height="750" frameborder="0"></iframe></div></td> --}}
        <form>
          <!-- <div class="form-group">
          <label for="a1">Mensagem</label>
          <textarea class="form-control" name="mensagem" id="a1" rows="2" placeholder='Participe da nossa pesquisa'></textarea>
          </div> -->
          {{-- <div class="bd-example">
            <div class="form-check">
            <button type="button" style="padding-left:25px;padding-right:41px;" class="btn btn-secondary">
              <input style="margin-top:1px" class="form-check-input" type="radio" name="tipo" checked="" id="r1" value="LINK" >
              <label class="form-check-label" for="r1"><b>Enviar Link</b></label>
            </button>
            </div>
            <div class="form-check">
              <button type="button" style="padding-left:25px;margin-top:5px" class="btn btn-outline-secondary">
                <input style="margin-top:1px" class="form-check-input" type="radio" name="tipo" id="r2" value="QRCODE">
                <label class="form-check-label" for="r2"><b>Enviar QR Code</b></label>
              </button>
          </div>
          </div> --}}
          <button type="button" onclick="enviarPesquisa(this)"  style="float: right;margin-right: 20px;" class="btn btn-primary mb-2 font-weight-bold ">Enviar emails</button>
        </form>
        
        <h3 class="mt-4">Listas de contatos</h3>
        <div  class="list-group">
          <?php  foreach ($data['listasContatos'] as $lista) {  ?>
            <button onclick="listaAtiva(this)" data-lista="<?= $lista['id']; ?>" type="button" style="background: #e6e6fa;margin:0px;border: 0px;border-bottom: solid 1px #c1e5e5!important;" class="sm-link list-group-item d-flex justify-content-between align-items-center">
              <b><input type="radio" class="d-none" name="lista_envio" value="<?= $lista['id']; ?>" /> <span style="color:#868e96!important"><?= $lista['titulo']; ?></span></b><span class="pull-right text-muted small"><em><?= count($lista['contatos']) ?> Contatos</em> </span>
            </button>
            <div style="display:none;border:1px solid #ccc" role="lista-sub-clientes" data-box="contatos<?= $lista['id']; ?>">
              <div style="width:93%;margin:auto">
                <?php if (count($lista['segmentacoes'])) { ?>
                  <h5 class="py-1">Segmentações</h5>
                  {{-- <hr/> --}}
                  <form name="seg-lista-<?=  $lista['id'] ?>">
                    <?php
                      foreach ($lista['segmentacoes'] as $segmento) {
                        if (count($segmento['opcoes'])) {
                          ?>
                          <div class="form-group ">
                          <label for="forSelect<?= $segmento['id']; ?>"><?= $segmento['titulo']; ?></label>
                          <select name="seg_<?= $segmento['id']; ?>" onchange="segmentarContatos(<?= $lista['id'] ?>)" id="forSelect<?= $segmento['id']; ?>" class="form-control">
                            <option value="">Escolha uma opção</option>
                            <?php foreach ($segmento['opcoes'] as $op) { ?>
                            <option value="<?= $op['id']; ?>"><?= $op['titulo']; ?></option>
                            <?php } ?>
                          </select>
                          </div>
                          <?php
                        }
                      }
                    ?>
                  </form>
                  {{-- </div> --}}
                <?php } ?>
                {{-- <div style="width:93%;margin:auto"> --}}
                  <h5 class="py-1">Contatos</h5>
                {{-- </div> --}}
                <ul style="border:0px;padding-left: 0;" class="list-group list-group-flush">
                  <?php foreach ($lista['contatos'] as $contato) { ?>
                  <li class="list-group-item sm-link ">
                    <label title="<?= $contato['email']; ?>"><input checked=""  value="<?= $contato['id']; ?>" name="c_<?= $contato['id']; ?>" type="checkbox" /> <?= $contato['nome']; ?></label>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
          <?php } ?> 
        </div>


      </div>
    </div>
  </div>
</div>
<script>
  function openEnviarLinkEmail(){
    $('#modalEnviarLinkEmal').modal();
  }
  function enviarPesquisa(obj) {
      var campos = {
            //mensagem:$('textarea[name=mensagem]').val(),
            //tipo:$('input[name=tipo]:checked').val(),
            lista:$('input[name=lista_envio]:checked').val(),
      };
      //    if (!campos.mensagem){
      //      alertaPadrao.returnMensagem('Preecha o campo mensagem.', 'top', 'infor');
      //      return false;
      //    }
      // if (!campos.tipo){
      //   alertaPadrao.returnMensagem('Escolha o tipo de envio.', 'top', 'infor');
      //   return false;
      // }
      if (!campos.lista){
        alertaPadrao.returnMensagem('Escolha uma lista.', 'top', 'infor');
        return false;
      }
      ////// - - - - - - - 
      var arAux = [], n = 0;
      for (var ob of document.querySelector('[data-box=contatos' + campos.lista + ']').getElementsByTagName('input')){
        if (ob.checked){
          arAux[n++] = ob.value;
        }
      }
      if (!arAux.length){
        alertaPadrao.returnMensagem('Escolha pelo menos um contato.', 'top', 'infor');
        return false;
      }
      campos['contatos'] = arAux;
      campos['pesquisa'] = "{{$data['pesquisa']['id']}}";
                    
                    obj.disabled = true;
                    obj.innerHTML = 'Enviando...';
                    $.ajax({
                      url:'{{env("APP_URL")}}gerenciar-listas/envio/email',
                      method:'post',
                      data:campos,
                      dataType : "json",
                      success: function(r) {
                        obj.disabled = false;
                        obj.innerHTML = 'Enviar emails';
                        if(r.status){
                          alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                          setTimeout(function(){
                            location.reload();
                          },3000);
                        }else{
                          alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                        }
                      }
                    });
  }
</script>