<div class="modal fade" id="modalContatosEnviados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog" style="max-width:50%" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <h3 class="modal-title" id="tituloModalLabel">Enviar novo e-mail</h3>
     <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
    </div>
    <div class="modal-body">
     <form>
      <div class="form-group">
       <label for="exampleFormControlInput1">Assunto:</label>
       <input type="text" value="PESQUISA PARA TESTAR AS PERGUNTAS DISPONÍVEIS  (TÍTULO MUITO LONGO)" name="assunto" class="form-control" id="exampleFormControlInput1">
      </div>
      <div class="form-group">
       <label for="a1">Mensagem:</label>
       <textarea class="form-control" name="mensagem" id="a1" rows="2">Participe da nossa pesquisa</textarea>
      </div>
       <div class="form-group">
               <label for="txtAber">Imagem:</label>
               <div class="imagem_logo">
                 <div style="margin: auto;width: 310px;">
                   <img id="img_pd_email" style="width:300px" src="{{ asset('public/assets_gestor/img/img-padrao.png') }}">
                   <h6 align="center" style="margin-bottom:0px;">Dimensões: 550x250px</h6>
                   <div style="width:250px;margin:auto;">
                       <a onclick="abrirJanela('file_logo', 'imagem_logo')" style="color:#fff;width:116px;" class="sm-link btn btn-sm btn-success"><b>Adicionar</b></a>
                       <a onclick="limpar('file_logo', 'imagem_logo')" style="color:#fff;width:116px;" class="sm-link btn-sm btn btn-danger"><b>Limpar</b></a>
                   </div>
                 {{-- <div>
                   <span>Imagem:550x250px</span>
                   <a onclick="abrirJanela()" class="badge badge-success">Add</a>
                   <a onclick="limpar()" class="badge badge-success">limpar</a>
                 </div> --}}
                 </div>
               </div>
               <!--<input  type="hidden" name="imagem_logo"   class="form-control" />-->
               <input style="display:none" id="file_pd_email" type="file" onchange="imagem(this)" class="form-control">
       </div>
      <div class="form-group">
       <label for="a23">Assinatura</label>
       <textarea class="form-control" name="assinatura" id="a23" rows="2"></textarea>
      </div>
      {{-- <div class="bd-example">
       <div class="form-check">
        <input class="form-check-input" type="radio" name="tipo" checked="" id="r1" value="LINK">
        <label class="form-check-label" for="r1">
         Link
        </label>
       </div>
       <div class="form-check">
        <input class="form-check-input" type="radio" name="tipo" id="r2" value="QRCODE">
        <label class="form-check-label" for="r2">
         QR Code
        </label>
       </div>
      </div> --}}
       <div>
       <h4>Listas de contatos</h4>
       <hr>
         @foreach ($data['lista']['dListas'] as $lista)
           <p>{{$lista['titulo']}}</p>
           @foreach ($lista['contatos'] as $contato)
             <div class="form-check">
             <label title="">
             <input checked="" value="{{$contato['id_contato']}}" data-lista="{{$lista['id']}}" role="contatos" name="c_{{$contato['id_contato']}}" type="checkbox">
                {{$contato['nome']}}
             </label>
             </div>
           @endforeach
         @endforeach
       </div>
     </form>
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Fechar</b></button>
     <button type="button" onclick="enviarNovaPesquisa(this)" class="btn btn-primary "><b>Enviar</b></button>
    </div>
   </div>
  </div>
 </div> 