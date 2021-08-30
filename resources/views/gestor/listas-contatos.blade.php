<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <script>
       var baseUrl = '{{env("APP_URL")}}';
       var codLista = '{{$data["lista"]["id"]}}';
    </script>
    <script src="{{ asset('public/assets_gestor/js/fnLista.js') }}" ></script>
    <title>Lista  Contatos - op Aqui</title>
    <meta name="description" content=" ">
    <script>
      function selectedSegmento(form,nome,valor){
        document.querySelector(`form[name=${form}] select[name=${nome}]`).value=valor;
      }      
     // gerencia abas
      function abasContatos(aba){
        let url = '{{env("APP_URL")}}lista/{{$data["lista"]["id"]}}/{{strUrl($data["lista"]["titulo"])}}/'+aba; 
        window.history.pushState("object or string", "Title",url);
        location.reload();
      }
    </script>
  </head>
  <body class="h-100">
    <div >

    @include('gestor.templates.header')
              
        <main style="margin:auto" class="main-content p-0 ">
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">

            @include('gestor.templates.menu')
            {{-- <hr style="margin-top:0px"/> --}}
            {{-- <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" style="margin:auto" class="mb-2 btn btn-sm btn-pill btn-outline-primary">
                <i class="material-icons  mr-1">person_add</i> NOVA
              </button>
            </div> --}}

            <h4 align="center" style="margin-bottom:10px;">{{$data['lista']['titulo']}}</h4>
            <div class="card mb-3 card-padrao" >
              <ul class="nav nav-abas nav-abas-inativo" >
                <li onclick="abasContatos('add');"  class=" nav-item ">
                <a class="nav-link {{(!$data['aba']||$data['aba']=='add')?'active':''}}" rule='graf' href="#add" data-toggle="tab"><b>Add</b></a>
                </li>
                @if(count($data['listaContatos']))
                <li onclick="abasContatos('contatos');" data-aba="contato" class="nav-item">
                <a class="nav-link {{($data['aba']=='contatos')?'active':''}}" href="#contatos" data-toggle="tab"><b>Contatos</b></a>
                </li>
                @endif
                <li onclick="abasContatos('segmentacao');" class="nav-item" >
                <a class="nav-link {{($data['aba']=='segmentacao')?'active':''}}" href="#segmentacao" data-toggle="tab"><b>Segmentação</b></a>
                </li>
              </ul>
                           
              <div class="tab-content">
                <div class="tab-pane fade in {{(!$data['aba']||$data['aba']=='add')?'active show':''}}" id="add">
                <br/>
                <div class="add-contatos-lista">
                  <button type="button" id="importarContatos" onclick="document.querySelector('[name=importar]').click()"  class="btn btn-primary btn-outline ">Importar contatos</button>
                  <input type="file" style="display:none" onchange="importarContatos(this)" name="importar"/>
                 
                  <ul class="list-group" style="width: 80%;"></ul>
                  <a class="badge badge-warning sm-link" style="margin-top:6px;" onclick="document.location.href = baseUrl + 'lista/modelo/importar/contatos'" >Baixar o modelo</a>
                  <h3>Adicionar novo contato</h3>
                </div>
                <form name='form'  data-contato="">
                  <div class="form-group">
                  <label for="exampleFormControlInput1">Nome*:</label>
                  <input type="text" name="nome" class="form-control" id="exampleFormControlInput1">
                  </div>
                  <div class="form-group">
                  <label for="exampleFormControlInput2">E-mail*:</label>
                  <input type="text" name="email" class="form-control" id="exampleFormControlInput2">
                  </div>
                  <div class="form-group">
                  <label for="exampleFormControlInput3">Telefone:</label>
                  <input type="text" name="telefone" maxlength="15" onkeypress="return mascaraCep(event, this, '(##) #####-####');" class="form-control" id="exampleFormControlInput3">
                  </div>
                  @foreach ($data['segmentacoes'] as $item)
                  <div class="form-group">
                    <label for="segmento{{'-'.$item['id']}}">{{$item['titulo']}}:</label>
                      <select name="segmento-{{$item['id']}}" class="form-control" id="segmento{{'-'.$item['id']}}">
                        <option value="" selected="">Escolha uma opção</option>
                          @foreach ($item['opcoes'] as $op)
                           <option value="{{$op['id']}}">{{$op['titulo']}}</option>       
                          @endforeach
                      </select>
                  </div>
                  @endforeach
                  <div class="modal-footer">
                    <button type="button" onclick="salvarContato('')" class="btn btn-primary">Salvar</button>
                  </div>
                </form>
                </div>
                <div class="tab-pane fade in  {{($data['aba']=='contatos')?' show active':''}}" id="contatos">
                  <h5 >Contatos da lista:</h5>
                  <div class="row"  style="width: 100%;margin: auto;">
                    <?php
                    foreach ($data['listaContatos'] as $key => $contato) {
                      $keyCont = $key + 1;
                      ?>
                      <div  class="col-md-6 bloco-card">
                        <div  class="card mb-3 card-padrao">
                          <form name='form{{$keyCont}}'  data-contato="{{$contato['id']}}">
                            <div class="form-group">
                            <label>Contato {{$keyCont}} </label>
                            <br>
                            <br>
                            <label for="exampleFormControlNome{{$keyCont}}">Nome :</label>
                            <input type="text" name="nome" value="{{$contato['nome']}}" class="form-control" id="exampleFormControlNome<?= $keyCont ?>">
                            </div>
                            <div class="form-group">
                            <label for="exampleFormControlEmail{{$keyCont}}">E-mail :</label>
                            <input type="text" name="email" value="{{$contato['email']}}" class="form-control" id="exampleFormControlEmail<?= $keyCont ?>">
                            </div>
                            <div class="form-group">
                            <label for="exampleFormControlTelefone{{$keyCont}}">Telefone :</label>
                            <input type="text" name="telefone" value="{{$contato['telefone']}}"  maxlength="15" onkeypress="return mascaraCep(event, this, '(##) #####-####');" class="form-control" id="exampleFormControlTelefone<?= $keyCont ?>">
                            </div>
                            @foreach ($data['segmentacoes'] as $item)
                            <div class="form-group">
                              <label for="segmento{{$key.'-'.$item['id']}}">{{$item['titulo']}}:</label>
                              <select name="segmento-{{$item['id']}}" class="form-control" id="segmento{{$key.'-'.$item['id']}}">
                                <option value="" selected="">Escolha uma opção</option>
                                  @foreach ($item['opcoes'] as $op)
                                  <option value="{{$op['id']}}">{{$op['titulo']}}</option>       
                                  @endforeach
                              </select>
                              @if(!empty($contato['segmento'][$item['id']]))
                              <script>selectedSegmento("form{{$keyCont}}","segmento-{{$item['id']}}","{{$contato['segmento'][$item['id']]}}")</script>
                              @endif
                            </div>
                            @endforeach
                            <div style="float:right;margin-right:20px;">
                            <button onclick="deletarContato({{$contato['id']}})" type="button" class="btn btn-danger">Deletar</button>
                            <button  onclick='salvarContato({{$keyCont}})' type="button" class="btn btn-primary">Editar</button>
                            </div>
                          </form>
                          <br/>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="tab-pane fade in <?= ($data['aba'] == 'segmentacao') ? 'active show' : ''; ?>" id="segmentacao">
                    <form name='segCadastro'  data-contato="">
                      <div class="form-group">
                      <label for="exampleFormControlSeg2">Criar segmento</label>
                      <input placeholder="Digite o nome do novo segmento" style="width:85%;" type="text" name="titulo" class="form-control" id="exampleFormControlSeg2">
                      <button type="button" style="float: right;width:15%;margin-top: -34px;" onclick="cadEditSegmentacao('segCadastro')" class="btn btn-primary"><b>Salvar</b></button>
                      </div>
                    </form>
                    <?php if (count($data['segmentacoes'])) { ?>
                      <h4>Lista de segmentações</h4>
                      <ul class="list-group">
                      <?php
                      foreach ($data['segmentacoes'] as $seg) {
                        ?>
                        <li class="list-group-item">
                        <b><?= $seg['titulo']; ?></b>
                        <?php
                        if (count($seg['opcoes'])) {
                          $arAux = array();
                          foreach ($seg['opcoes'] as $value) {
                          $arAux[] = $value['titulo'];
                          }
                          // $opSementacao = (array) $opSementacao;
                          echo '<br/><b>Opções:</b> ' . implode(', ', $arAux);
                        }else{
                          echo '<br/>Clique em editar para adicionar opções ';
                        }
                        ?>
                        <div style="float:right">
                          <a style="background:#d9534f;color:#fff" onclick="deletarSegmentacao(<?= $seg['id']; ?>)" class="badge badge-secondary sm-link">Deletar</a>
                          <a style="background:#337ab7;color:#fff" onclick="modalEditarSegmentacao(<?= $seg['id']; ?>, '<?= $seg['titulo']; ?>')" class="badge badge-primary sm-link">Editar</a>
                        </div>
                        </li>
                      <?php } ?>
                      </ul>
                    <?php } else { ?>
                      <p>A lista está vazia</p>
                    <?php } ?>

                      <!-- Modal -->
                      <div class="modal fade" id="modalFormSegmentacao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                          <h3 class="modal-title"  id="tituloModalLabel">Editar Segmentação</h3>
                          <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
                          </div>
                          <div class="modal-body">
                          <form name='segEditar'  data-contato="">
                            <div class="form-group">
                            <label for="exampleFormControlSeg3">Editar segmento</label>
                            <input type="hidden" role="codSeg" name="cod" >
                            <input placeholder="Digite o nome do novo segmento" style="width:89%;" type="text" role="mTituloSeg" name="titulo" class="form-control" id="exampleFormControlSeg3">
                            <button type="button" style="float: right;width: 10%;margin-top: -34px;" onclick="cadEditSegmentacao('segEditar')" class="btn btn-primary btn-circle"><b>Salvar</b></button>
                            </div>
                          </form>
                          <hr/>
                          <h4>Opções</h4>
                          <form name='segCadastro'  data-contato="">
                            <div class="form-group">
                            <label for="exampleFormControlSeg2">Nova opção</label>
                            <input placeholder="Digite o nome da nova opção" style="width:89%;" type="text" data-segmentacao="" name="tituloNovaOp" class="form-control" id="exampleFormControlSeg2">
                            <button type="button" style="float: right;width: 10%;margin-top: -34px;" onclick="cadEditSegOpcoes('tituloNovaOp')" class="btn btn-primary btn-circle"><b>Salvar</b></button>
                            </div>
                          </form>
                          <h5>Lista de opções</h5>
                          <div data-ajax="opcoes">
                  
                          </div>
                          </div>
                          <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                          <!--<button type="button" onclick="salvarLista()" class="btn btn-primary">Salvar</button>-->
                          </div>
                        </div>
                        </div>
                      </div>
                </div>
              </div>

            </div>  

          </div>    
        </main>
        @include('gestor.templates.rodape')
    </div>
    @include('gestor.templates.chamadas-down')

  </body>
</html>