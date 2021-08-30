
@extends('zp.components.main')
@extends('zp.components.rel-grid')
@section('sectionReportFilter')
@php
$mes = date('m'); 
$anoInit=2019;  
$ano = date('Y');   
$dataAtual = date("d/m/Y");
@endphp
    <script>
        //Validar forlulário
        objPage.form.save.validate = {
         //
         }
        objPage.rel.init={
          columns:[
            {data:"dataInicio",title:"Cadastro",name:""},
            {data:"dataVenda",title:"Última venda",name:""},
            {data:"paroquiano",title:"Paroquiano",name:""},
            {data:"codigo",title:"Código",name:""},
            {data:"paroquia",title:"Paroquia",name:"",},
            {data:"estabelecimento",title:"Estabelecimento",name:"",},
            {data:"id",title:"Cupom",name:""},
            // {data:"transacoes",title:"Transações",name:""},
            {data:"valor",title:"Valor (R$)",name:"","className": "text-right"},
            {data:"status",title:"Status  Cupom",name:""},
            {data:"valor_comissao",title:"Contribuição (R$)","className": "text-right"},
            {data:"status_comissao",title:"Status Contribuição",render:function(data,type,row,meta){
              if(row.status=='FECHADO'){
                 return `<a class="btn-iteração" href="#" onclick="formComisao(this,${row.id},'${row.valor_comissao}')"  >${data} <i class="fas fa-edit"></i></a>`;  
              }else{
                 return '';  
              }
            }},
            {data:"id",title:"Mais",name:"",render:function(data,type,row,meta){
             return `<a class="btn-iteração" href="#" onclick="detalhesRelatorio(this,'${data}','${row.paroquiano}','${row.codigo}')" title="Detalhes" ><i class="fas fa-info-circle"></i></a>`;  
            }},
          ],
          buttons:[],
        }
    </script>
      <fieldset>
        <legend>Filtros</legend>
        {!! HTML::initFormRel(); !!}
            {!! HTML::input(["name"=>"codigo_paroquiano", "label"=>"Código"]);!!}
            {!! HTML::input(["name"=>"nome", "label"=>"Nome"]);!!}
            {!! HTML::input(["name"=>"email", "label"=>"E-Mail"]); !!}
            {!! HTML::input(["name"=>"celular", "label"=>"Celular / Whats","mask"=>["(99) 9 9999-9999"]]); !!}
            {!! HTML::select(["name"=>"id_paroquia", "label"=>"Paróquia"],$page['data']['paroquiasData']); !!}
            {!! HTML::select(["name"=>"id_estabelecimento", "label"=>"Estabelecimento"],$page['data']['estabelecimentoData']); !!}
            {!! HTML::datetimerange(["label"=>"Período","id"=>"name_identificador"],//end at
            ["name"=>"dataInicio"],//end p1
            ["name"=>"dataFim"]//end p2
            )
            !!}
            {!! HTML::select(["name"=>"status", "label"=>"Status"],['ABERTO'=>'Abertos','FECHADO'=>'Fechados'],['TODOS'=>'Todos']); !!}
        {!! HTML::endFormRel(); !!}
      </fieldset>

                 <!-- Modal  Alterar -->
  <div class="modal fade"  id="detalhesRelatorio" tabindex="-1" role="dialog" aria-labelledby="alterar-atendente" aria-hidden="true">
      <div class="modal-dialog"  role="document">
        <div class="modal-content" style="width:130%" >
          <div class="modal-header">
            <h5 class="modal-title" id="alterar-atendenteLabel">Detalhes do cupom</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <ul class="info">
                <li ><b>Nome:</b> <span data-detalhes="nome" ></span></li>
                <li ><b>Código:</b> <span data-detalhes="codigo" ></span></li>
                <li ><b>Número do Cupom:</b> <span data-detalhes="cupom"></span></li>
              </ul>
              <table  class="table table-striped">
                  <thead class="t-estabelecimento">
                      <tr>
                          <th class="indice" >#</th>
                          <th class="indice" >Data</th>
                          <th class="indice" >Valor</th>
                          <th class="indice" >Atendente</th>
                          <th class="indice" width="15%" >Controle</th>
                      </tr>
                  </thead>
                  <thead id="tbDetalhes"></thead>
               </table>
          </div>
          {{-- <div class="modal-footer">
              <button type="button" class="btn-padrao btn-excluir">Excluir</button>
              <button type="button" class="btn-padrao btn-vasado" data-dismiss="modal">cancelar</button>
              <button type="button" class="btn-padrao btn-registro">Registrar</button>
          </div> --}}
        </div>
      </div>
    </div><!--Fim modal detales--> 

  <!-- Modal  form -->
  <div class="modal fade"  id="formComissao" tabindex="-1" role="dialog" aria-labelledby="alterar-atendente" aria-hidden="true">
      <div class="modal-dialog"  role="document">
        <div class="modal-content" style="width:130%" >
          <div class="modal-header">
            <h5 class="modal-title" > Contribuição - <span id="vComissao"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form>
                <div class="form-group">
                  <label for="dataComissao">Data</label>
                  <input type="text" id="dataComissao" name="data" class="form-control" placeholder="Data">
                </div>
                  <div class="form-group">
                      <label for="exampleFormControlSelect1">Status de Pagamento</label>
                      <select name="status_comissao" class="form-control" id="exampleFormControlSelect1">
                        <option value="PENDENTE">Pendente</option>
                        <option value="PAGO">Pago</option>
                  
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Observação</label>
                        <textarea name="observacao" class="form-control" id="exampleFormControlTextarea1" rows="1"></textarea>
                      </div>
              </form>
              
               <div class="modal-footer">
                   <button type="button" class="btn-padrao " data-dismiss="modal">Cancelar</button>
                   <button type="button" class="btn-padrao " onclick="subFormComissao()">Salvar</button>
               </div>
          </div>
        </div>
      </div>
    </div><!--Fim modal detales--> 
      <script>
          function detalhesRelatorio(obj,cupom,paroquiano,codigo){
            if(cupom){
              $.ajax({
                url: objPage.url.url_page+'/ajax',
              type: 'POST',
              dataType: 'JSON',
              data: {cupom,action:'detalehes'},
              success: function (r) {
                if(r.status){
                  $("#detalhesRelatorio").modal();
                  $('[data-detalhes=nome]').html(paroquiano);
                  $('[data-detalhes=codigo]').html(codigo);
                  // $('[data-detalhes=nome]').html($(obj).parents('tr').find('.nomoParoc').text());
                  // $('[data-detalhes=codigo]').html($(obj).parents('tr').find('.codigoParoc').text());
                  $('[data-detalhes=cupom]').html(cupom);
                  let html='';
                  console.log(r.data)
                  for(let ob of r.data){
                    html += `
                      <tr>
                          <th class="indice" >${ob.n}</th>
                          <th class="indice" >${ob.data}</th>
                          <th class="indice" >${ob.valor}</th>
                          <th class="indice" >${ob.funcionario}</th>
                          <th class="indice" >${ob.controle?ob.controle:''}</th>
                      </tr>
                    `;
                  }
                 $('#tbDetalhes').html(html);  
                }else{

                }
              },
            });
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
       let idCupom;
        function formComisao(obj,cupom,valor){
            if(cupom){
              idCupom=cupom;
              $.ajax({
                url: objPage.url.url_page+'/ajax',
              type: 'POST',
              dataType: 'JSON',
              data: {cupom,action:'load-cupom'},
              success: function (r) {
                onClear();
                $("#vComissao").html('R$ '+valor);
                $("#formComissao").modal();
                if(r.status){
                 $('input[name=data]').val(r.data.data);
                 $('select[name=status_comissao]').val(r.data.status_comissao);
                 $('textarea[name=observacao]').val(r.data.observacao);
                }else{
                  $('#dataComissao').val('{{$dataAtual}}')
                }
              },
            });
            }
        }
        function onClear(){
            $('input[name=data]').val('');
            $('select[name=status_comissao]').val('');
            $('textarea[name=observacao]').val('');
        }
        function subFormComissao(){
          const data = {data:$('input[name=data]').val(),
              status_comissao:$('select[name=status_comissao]').val(),
              observacao:$('textarea[name=observacao]').val(),
              idCupom,
             };
               
               if(!data.idCupom){
                 alertify.alert('Atenção!','Não fui possível continuar na ação');
                 return false;
               } 
               if(!data.data){
                 alertify.alert('Atenção!','O campo data é obrigatório');
                 return false;
               } 
               if(!data.status_comissao){
                 alertify.alert('Atenção!','O campo status de pagamento é obrigatório');
                 return false;
               } 
              data['action'] ='submit-comissao';
              $.ajax({
              url: objPage.url.url_page+'/ajax',
              type: 'POST',
              dataType: 'JSON',
              data: data,
              success: function (r) {
                  if(r.status){
                    $("#formComissao").modal('hide');
                    tbGridRel.ajax.reload();  
                    alertify.alert(' ',r.msg);
                  }else{
                    alertify.alert(' ',r.msg);
                  }
                
              },
            });
            
        }
        $(function(){
              $('#dataComissao').mask('99/99/9999');
            });
      </script>
@endsection



