@php
$dataMensuracao1='';
$dataMensuracao2='';
$pesquisaToken=substr($data['pesquisa']['id_token'], 0, 5);
$dataGraf=[];
$dashMensuracao = (count($data['dataFiltros']['mensuracao'])>1)?1:0;

if(!empty($data['dashboard']['totalEntrevistasDia'])){
  $dataMensuracao1='';//$data['dashboard']['totalEntrevistasDia'][0]['data'];
  $dataMensuracao2='';//$data['dashboard']['totalEntrevistasDia'][count($data['dashboard']['totalEntrevistasDia'])-1]['data'];
}
$mapEntrevistas =[];
//  dd($data['dataFiltros']['filtros']);
// dd($data);
@endphp

<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    {{-- <script src="{{asset('public/vendor/amcharts4/core.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/charts.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/dataviz.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/animated.js')}}"></script>
    --}}
    <script src="{{asset('public/vendor/amcharts3/amcharts.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts3/pie.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts3/serial.js')}}"></script>

    <script src="{{asset('public/assets_gestor/js/questionario.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/assets_gestor/css/dashboard.css') }}">
    <title>Dashboard - op Aqui</title>
    <meta name="description">
    <style>
      .contato-select,.grupo-radio{width: 50%;display: inline-block;vertical-align: top;}
      .grupo radio .radio:first-child {margin-top: 0;}
      </style>
    <script>
      var urlBase='{{env('APP_URL')}}',modMax='{{$data['zoom']}}',idPesquisa='{{$data['pesquisa']['id']}}',abaAtual='{{$data['aba']}}',
          dashMensuracao='{{$dashMensuracao}}',map=null;
          // console.log(.replace(/"/gi, '"'))
          // console.log(' teste " aa "    teste'.replace(/["]+/g, '*'))
          // var xx = `{!!json_encode($data['dashboard'])!!}`.replace(/['"]+/g, "\"");
          // console.log('Teste',xx.replace(/["]+/g, '&quot;'))
      var dataDashboard={
          dashPerguntas:JSON.parse(`{!!json_encode($data['dashPerguntas'])!!}`/*.replace(/&quot;/gi, "\"")*/),
          filtros:JSON.parse(`{!!json_encode($data['dataFiltros'])!!}`/*.replace(/&quot;/gi, "\"")*/),
          dashboard:JSON.parse(`{!!json_encode($data['dashboard'])!!}`/*.replace(/"/gi, "\"")*/),
      },
      dPesquisa ={
                 titulo:"{{$data['pesquisa']['titulo']}}",
                 descricao: "{{$data['pesquisa']['descricao']}}",
                 data_inicio:"{{retrieveDatetime($data['pesquisa']['data_inicio'])}}",  
                 data_fim:"{{retrieveDatetime($data['pesquisa']['data_fim'])}}",
                },
      downlaoadsDetalhes = {diaADia:false,localizacao:false}
      console.log('dataDashboard=',dataDashboard)
      function openFiltros(id,filtro){
        if(!filtro.value){
          return false;
        }
        document.getElementById(`fPerg${id}`).click();
        if(filtro.tipo_pergunta=='NUMERICO'){
          var aux = filtro.value.split('_'),input=document.querySelector(`[data-perg="${id}"]`);
          if(aux[0]){
            input.querySelector('input[name=min]').value =aux[0];
          }
          if(aux[1]){
            input.querySelector('input[name=max]').value =aux[1];
          }
        }else if(filtro.tipo_pergunta=='MONETARIO'){
          var aux = filtro.value.split('_'),input=document.querySelector(`[data-perg="${id}"]`);
          if(aux[0]){
            input.querySelector('input[name=min]').value=aux[0];
            // input.querySelector('input[name=min]').value=currencyFormat(, `.`, `,`, 2, event);
          }
          if(aux[1]){
            input.querySelector('input[name=max]').value=aux[1];
            // input.querySelector('input[name=max]').value=currencyFormat(input.querySelector('input[name=max]'), `.`, `,`, 2, event);
          }
        }else if(filtro.tipo_pergunta=='DATA'){
          var aux = filtro.value.split('_'),input=document.querySelector(`[data-perg="${id}"]`);
          // console.log(aux)
          if(aux[0]){
            input.querySelector('input[name=min]').value=aux[0].substr(8,2)+'/'+aux[0].substr(5,2)+'/'+aux[0].substr(0,4);
          }
          if(aux[1]){
            input.querySelector('input[name=max]').value=aux[1].substr(8,2)+'/'+aux[1].substr(5,2)+'/'+aux[1].substr(0,4);
          }
        }else{
          if(document.getElementById(`fPerg${id}`)){
            for(var f of filtro.value){
              if(document.getElementById(`checkResp${id}-${f}`)){
                document.getElementById(`checkResp${id}-${f}`).checked=true;
              }
            }
          }
        }
      }
      $(function(){
        $('.rel-aba-sub').click(function(){
         $('.rel-aba').removeClass('show').removeClass('active');
         $('.rel-aba').removeClass('show').removeClass('active');
        });
        $('.rel-aba').click(function(){
          console.log("ndxnxd")
         $('.rel-aba-sub').find('a').removeClass('show').removeClass('active');;
        });
      });
    </script>
  <script src="{{asset('public/assets_gestor/js/dashboard-graf.js')}}"></script>
  </head>
  <body class="h-100">
    
    <div >
      
      @include('gestor.templates.header')

      <main style="margin:auto" class="main-content p-0 ">

        <div class="main-content-container container-fluid px-4">

         @include('gestor.templates.menu')
          <div id="dashboard-max" class="{{$data['zoom']?'dashboard-max':''}}">
            <div class="wrapper card" style="overflow: hidden;flex-direction:row;">
                <!-- Sidebar Holder -->
                <nav id="sidebar" class="active">
                    <div class="sidebar-header" >
                        <h4 style="color: #ffffff">Filtros da pesquisa</h4>
                    </div>
                    <ul class="list-unstyled components">
                        <h5 class="ml-2">Mensurações</h5>
                        
                        @for($i=1;$i<=3;$i++)
                        <?php
                         $dataInicio =!empty($data['dataFiltros']['mensuracao'][$i]['inicio'])?$data['dataFiltros']['mensuracao'][$i]['inicio']:'';
                         $dataFim=!empty($data['dataFiltros']['mensuracao'][$i]['fim'])?$data['dataFiltros']['mensuracao'][$i]['fim']:'';
                        ?>
                          <li class="active mensuracao" data-mensuracao="data{{$i}}" style="margin-top:5px;display:{{($dataInicio)?'':(($i==1)?'':'none')}}">
                           <div style="display:flex;margin-left:2px;">
                              <div class="grupo-radio">
                                <div class="radio">
                                    <label style="margin-bottom:0px">
                                    M{{$i}} <input name="mensuracaoDataInicio{{$i}}" value="{{($dataInicio)?retrieveTypeDate($dataInicio):(($i==1)? $dataMensuracao1:'')}}" type="text" style="width:98px;display:inherit;padding:3px;font-size:15px;{{$i==1?';margin-left:4px':''}}" class="form-control" maxlength="10" placeholder="00/00/0000" onkeypress="return mascaraCep(event, this, '##/##/####');" />
                                    </label>
                                </div>
                              </div>
                              <div class="grupo-radio">
                                <div class="radio">
                                    <label style="margin-bottom:0px"> a <input name="mensuracaoDataFim{{$i}}"  value="{{($dataFim)?retrieveTypeDate($dataFim):($i==1? $dataMensuracao2:'')}}" type="text" style="width:105px;display:inherit;padding:3px;font-size: 15px;" class="form-control"  maxlength="10" placeholder="99/99/9999" onkeypress="return mascaraCep(event, this, '##/##/####');" /></label>
                                </div>
                              </div>
                            </div>
                            <div  data-bt="m{{$i}}" >
                              <div class="d-flex justify-content-end" >
                                <button onclick="rDataMensuracao('{{$i}}')" style="width:20px;height:20px;padding:2px;margin:3px;display:{{$i!=1?'':'none'}}" type="button" class="btn btn-danger btn-sm">
                                  <i style="font-size:14px;" class="fas fa-times"></i>
                                </button>
                                <button onclick="addDataMensuracao('{{$i}}')" style="width:20px;height:20px;padding:2px;margin-top:3px;margin-right:6px;color:#fff;display:{{$i!=3?'':'none'}}" type="button" class="btn btn-warning btn-sm">
                                  <i style="font-size:14px;" class="fas fa-plus"></i>
                                </button>
                              </div>
                            </div>
                          </li>
                          @endfor
                        <li>
                          <hr/>
                          <h5 class="ml-2">Filtros</h5>
                          @foreach($data['dashPerguntas'] as $pergunta)
                          <a href="#pageSubmenu{{$pergunta['id']}}" id="fPerg{{$pergunta['id']}}" data-toggle="collapse" aria-expanded="false" class="filtro-p dropdown-toggle">{{$pergunta['titulo']}}</a>
                            <ul class=" filtro-r  collapse list-unstyled" id="pageSubmenu{{$pergunta['id']}}">
                              @if($pergunta['tipo_pergunta']=='ABERTA')
                              @php
                               $mask ='';
                               $textMin ='Min';
                               $textMax ='Max';
                               if($pergunta['tipo_resp_entrada']=='MONETARIO'){
                                $mask = 'onkeypress="return currencyFormat(this, `.`, `,`, 2, event);"';
                               }else if($pergunta['tipo_resp_entrada']=='DATA'){
                                $textMin = 'Início';
                                $textMax = 'Fim';
                                $mask = 'onkeypress="return mascaraCep(event, this, `##/##/####`);"';
                               }else if($pergunta['tipo_resp_entrada']=='NUMERICO'){
                                $mask = 'onkeypress="return mascaraCep(event, this, `###############`);"';
                               }
                              @endphp
                              <li class="active mb-1" data-checkPerg="filtro" data-tipo="ABERTA"  data-tipo-entrada="{{$pergunta['tipo_resp_entrada']}}" data-perg="{{$pergunta['id']}}" style="margin-top:5px;">
                                <div style="display:flex;margin-left:2px;">
                                   <div class="grupo-radio">
                                     <div class="radio">
                                         <label style="margin-bottom:0px">
                                         {{$textMin}} <input name="min" value="" id="min{{$pergunta['id']}}" type="text" style="width:80px;display:inherit;padding:3px;font-size:15px;" class="form-control" {!!$mask!!} maxlength="10"  />
                                         </label>
                                     </div>
                                   </div>
                                   <div class="grupo-radio">
                                     <div class="radio">
                                         <label style="margin-bottom:0px"> 
                                          {{$textMax}} <input name="max"  value="" id="max{{$pergunta['id']}}" type="text"  style="width:89px;display:inherit;padding:3px;font-size: 15px;" class="form-control" {!!$mask!!} maxlength="10" />
                                        </label>
                                     </div>
                                   </div>
                                 </div>
                               </li>
                              @else
                              @foreach($pergunta['respostas'] as $val)
                                <li>
                                  <div class="form-check" style="margin-bottom:5px;margin-left:15px" id="divEmailEntrada13">
                                  <input type="checkbox" data-perg="{{$pergunta['id']}}" data-tipo="{{$pergunta['tipo_pergunta']}}"  data-checkPerg="filtro"  data-tipo="CHECKBOX"  class="form-check-input sm-link" value="{{$val['id']}}" id="checkResp{{$pergunta['id'].'-'.$val['id']}}" />
                                    <label class="form-check-label sm-link" for="checkResp{{$pergunta['id'].'-'.$val['id']}}">{{$val['titulo']}}</label>
                                  </div>
                                </li>
                                @endforeach
                              @endif
                            </ul>
                            @if(!empty($data['dataFiltros']['filtros'][$pergunta['id']]))
                                <script>openFiltros("{{$pergunta['id']}}",JSON.parse("{{json_encode($data['dataFiltros']['filtros'][$pergunta['id']])}}".replace(/&quot;/gi, "\"")));</script>
                            @endif
                            @endforeach
                        </li>
                        <li>
                          <hr/>
                          <div class="d-flex justify-content-end" >
                            <button onclick="limparFiltros()" style="padding:5px;" type="button" class="btn btn-secondary btn-sm">
                             <b>Limpar</b>
                            </button>
                            <button onclick="onFiltror(this)" style="padding:5px;margin-left:5px;margin-right:6px;color:#fff;" type="button" class="btn btn-warning btn-sm">
                              <b>Filtrar</b>
                            </button>
                          </div>
                        </li>
                    </ul>
                </nav>

                <div id="content">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="box-shadow:none;margin-bottom:5px;padding:0px;padding-top:10px;padding-bottom:10px">
                        <div class="container-fluid">
                            <button type="button" id="sidebarCollapse" data-filter="false" class="navbar-btn active">
                                <i data-icon="filter" class="fas fa-filter"></i>
                                <i style="display:none" data-icon="close" class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-align-justify"></i>
                            </button>
                                                                  
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                              <div class="dropdown nav navbar-nav ml-auto" style="margin:auto;width:95%">
                                <button class="btn btn-light dropdown-toggle btn-lg"  style="width:100%;background:#eee;padding: 7px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span>{{resum($data['pesquisa']['titulo'],100)}}</span>
                                </button>
                                <div class="dropdown-menu p-2" style="width: 100%" aria-labelledby="dropdownMenuButton">
                                  <form class="form-inline">
                                    <div class="form-group mb-2" style="width:50%">
                                      <label for="staticEmail2" class="sr-only">Email</label>
                                      <input type="text" name="bus-nome-pesq" style="width:100%" class="form-control" placeholder='Digite o nome da pesquisa'>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                      {{-- <label for="inputPassword2" class="sr-only">Password</label> --}}
                                     Início: <input type="text" name="bus-data-ini" style="width:100px;margin-left:5px" class="form-control" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');"  placeholder="00/00/0000">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                      {{-- <label for="inputPassword2" class="sr-only">Password</label> --}}
                                      Encerramento: <input type="text" name="bus-data-fim" style="width:100px;margin-left:5px" class="form-control" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');"  placeholder="99/99/9999">
                                    </div>
                                    <button type="button" onclick="buscaPesquisa();"  class="btn btn-primary mb-2"><i class="fas fa-search"></i></button>
                                  </form>
                                  <ul class="list-group list-group-flush" id="lista-busca">
                                    @foreach($data['selectPesquisas'] as $val)
                                     <li class="list-group-item iten-pesquisa sm-link" style='padding: 4px!important;{{$data['pesquisa']['id']==$val['id']?'background:#ccc':''}}' onclick="togglePesquisa('{{$val['id']}}')" {{$val['id']}}>{{resum($val['titulo'],100)}}</li>
                                    @endforeach
                                  </ul>
                                </div>
                              </div>
                                {{-- <ul class="nav navbar-nav ml-auto" style="margin:auto;width:95%">
                                    <li class="nav-item active" style="width:95%">
                                      <div class="form-group" style="margin: 0;">
                                        <select name="pesquisa" onchange="togglePesquisa(this.value)" class="form-control form-control">
                                          <option>Escolha uma pesquisa</option>
                                           @foreach($data['pesquisas'] as $val)
                                            <option value="{{$val['id']}}" >{{resum($val['titulo'],120)}}</option>
                                           @endforeach
                                        </select>
                                        @if(count($data['pesquisas']))
                                        <script>document.querySelector('[name=pesquisa]').value="{{$data['pesquisa']['id']}}";</script>
                                        @endif
                                      </div>
                                    </li>
                                </ul> --}}
                                {{-- <button type="button" id="sidebarCollapse" onclick=" toggleMax()" data-filter="false" class="navbar-btn active">
                                  <i data-icon="expand" style="display:{{$data['zoom']?'none':''}}" class="fas fa-expand"></i>
                                  <i data-icon="compress" style="display:{{!$data['zoom']?'none':''}}" class="fas fa-compress"></i>
                              </button> --}}
                            </div>
                        </div>
                    </nav>
          
                    <ul class="nav nav-abas {{$data['pesquisa']['emAndamento']?'nav-abas-ativo':'nav-abas-inativo'}}" id="pills-tab" role="tablist" style="margin-left: 4px">
                      <li class="nav-item">
                        {{-- <a class="nav-link rel-aba {{($data['aba']=='indicadores'|| !$data['aba'])?'active':''}}" onclick="abasPesquisa('indicadores');" id="indicadores-tab" data-toggle="pill" href="#indicadores" role="tab" aria-controls="indicadores" aria-selected="true">Indicadores</a> --}}
                        <a class="nav-link rel-aba {{($data['aba']=='indicadores'|| !$data['aba'])?'active':''}}" onclick="abasPesquisa('indicadores');" id="indicadores-tab" data-toggle="pill" href="" role="tab" aria-controls="indicadores" aria-selected="true">Indicadores</a>
                      </li>
                      @if(count($data['dashboard']['dadosNPS']))
                      <li class="nav-item">
                        <a class="nav-link rel-aba {{($data['aba']=='net-promoter-score')?'active':''}}" onclick="abasPesquisa('net-promoter-score');" id="net-promoter-score-tab" data-toggle="pill" href="" role="tab" aria-controls="net-promoter-score" aria-selected="false">Net Promoter Score</a>
                        {{-- <a class="nav-link rel-aba {{($data['aba']=='net-promoter-score')?'active':''}}" onclick="abasPesquisa('net-promoter-score');" id="net-promoter-score-tab" data-toggle="pill" href="#net-promoter-score" role="tab" aria-controls="net-promoter-score" aria-selected="false">Net Promoter Score</a> --}}
                      </li>
                      @endif
                      <li class="nav-item">
                        {{-- <a class="nav-link rel-aba {{($data['aba']=='relatorio')?'active':''}}" onclick="abasPesquisa('relatorio');" id="relatorio-tab" data-toggle="pill" href="#relatorio" role="tab" aria-controls="relatorio" aria-selected="false">Relatório</a> --}}
                        <a class="nav-link rel-aba {{($data['aba']=='relatorio')?'active':''}}" onclick="abasPesquisa('relatorio');" id="relatorio-tab" data-toggle="pill" href="" role="tab" aria-controls="relatorio" aria-selected="false">Relatório</a>
                      </li>
                      <li class="nav-item dropdown ">
                        {{-- <a class="nav-link {{($data['aba']=='entrevistas')?'active':''}}" onclick="abasPesquisa('entrevistas');" id="entrevistas-tab" data-toggle="pill" href="#entrevistas" role="tab" aria-controls="entrevistas" aria-selected="false">
                          Entrevistas
                        </a> --}}
                        {{-- <div class=""> --}}
                          <a type="button" class="rel-aba-sub {{(in_array($data['aba'],['geolocalizacao','entrevistas','entrevistas-realizadas']))?'active':''}} nav-link dropdown-toggle sm-link" style="padding:5px;" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            Entrevistas <span class="caret" style="margin-left: 0px;"></span>
                          </a>
            
                            <ul class="nav dropdown-menu" style="margin-top:2px;display:none">
                              <li class="nav-item" style="width:195px!important;margin-left:4px;">
                                {{-- <a class="sm-link {{($data['aba']=='entrevistas')?'active':''}}" style="text-transform:none;padding-right:13px;" onclick="abasPesquisa('entrevistas');addClassAba();" id="entrevistas-tab" data-toggle="pill" href="#entrevistas" role="tab" aria-controls="entrevistas" aria-selected="false">Número de entrevistas</a> --}}
                                <a class="sm-link {{($data['aba']=='entrevistas')?'active':''}}" style="text-transform:none;padding-right:13px;" onclick="abasPesquisa('entrevistas');addClassAba();" id="entrevistas-tab" data-toggle="pill" href="" role="tab" aria-controls="entrevistas" aria-selected="false">Número de entrevistas</a>
                              </li>
                              <li class="nav-item" style="width:195px!important;margin-left:4px;margin-top:5px">
                                {{-- <a class="sm-link {{($data['aba']=='geolocalizacao')?'active':''}}" style="text-transform:none;padding-right: 72px;" onclick="abasPesquisa('geolocalizacao');addClassAba();" id="geolocalizacao-tab" data-toggle="pill" href="#geolocalizacao" role="tab" aria-controls="geolocalizacao" aria-selected="false"> --}}
                                <a class="sm-link {{($data['aba']=='geolocalizacao')?'active':''}}" style="text-transform:none;padding-right: 72px;" onclick="abasPesquisa('geolocalizacao');addClassAba();" id="geolocalizacao-tab" data-toggle="pill" href="" role="tab" aria-controls="geolocalizacao" aria-selected="false">
                                 Geolocalização
                               </a>
                               </li> 
                              <li class="nav-item" style="width:195px!important;margin-left:4px;margin-top:5px">
                                {{-- <a class="sm-link {{($data['aba']=='entrevistas-realizadas')?'active':''}}" style="text-transform:none;padding-right: 23px;" onclick="abasPesquisa('entrevistas-realizadas');" id="entrevistas-realizadas-tab" data-toggle="pill" href="#entrevistas-realizadas" role="tab" aria-controls="entrevistas-realizadas" aria-selected="false">Entrevistas realizadas</a> --}}
                                <a class="sm-link {{($data['aba']=='entrevistas-realizadas')?'active':''}}" style="text-transform:none;padding-right: 23px;" onclick="abasPesquisa('entrevistas-realizadas');" id="entrevistas-realizadas-tab" data-toggle="pill" href="" role="tab" aria-controls="entrevistas-realizadas" aria-selected="false">Entrevistas realizadas</a>
                              </li>
                          
                            </ul>
                           <script>
                             function addClassAba(){
                                document.getElementById('dropdownMenuLink2').classList.add('active');
                             }
                           </script>
                      </li>
               
                      <ul class="navbar-nav mr-auto"></ul>
          
                      <li class="nav-item" style="background:none;color:#9B9B9B!important;box-shadow:none;">
                         {{-- right --}}
                      </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent" style="padding-left: 10px;padding-right: 12px;">
                          
                        <div class="tab-pane fade {{($data['aba']=='indicadores'||!$data['aba'])?'active show':''}}" id="indicadores" role="tabpanel" >
                          @if(!$data['totalEntrevistas'])
                           <h5 align='center' class="mt-5 pt-5">Nenhum registro foi encontrado</h5>
                          @else
                          <div class="row "  style="display:;">
                            <div id="box-indicador1" class="col-md-12 bloco-card" style="margin-top: 12px!important" ></div>
                            <div id="box-indicador2" class="col-md-12 bloco-card" style="margin-top: 12px!important" ></div>
                            <div id="box-indicador3" class="col-md-12 bloco-card" style="margin-top: 12px!important" ></div>
                            <div id="box-indicador4" class="col-md-12 bloco-card" style="margin-top: 12px!important" ></div>
                          </div>
                          @endif
                        </div>
                      @if(count($data['dashboard']['dadosNPS']))
                        <div class="tab-pane fade {{($data['aba']=='net-promoter-score')?'active show':''}}" id="net-promoter-score" role="tabpanel" aria-labelledby="net-promoter-score-tab">
                          <div style="margin-bottom:5px!important" class="d-flex justify-content-between bd-highlight mb-3 col-12 col-sm-12">
                            <div></div>
                            <div>
                              <ul class="nav ">
                                <li class="nav-item">
                                  <button type="button" class="btn btn-sm btn-danger" role='pdfNPS' onclick="gPDFNPS();" style="padding:5px">Baixar PDF <i class="fa fa-file-pdf fa-fw"></i></button>
                                </li>
                              </ul>
                            </div>
                          </div>
                          @if(!$data['totalEntrevistas'])
                          <h5 align='center' class="mt-5 pt-5">Nenhum registro foi encontrado</h5>
                          @else
                          <div id="cardNPS" class="box-responsivo card mb-3 card-padrao" >
                            <h4 class="mb-3">{{$data['dashboard']['dadosNPS']['titulo']}}</h4>
                            <div id="divNPS"><div class="loader" style="margin-top: 60px"></div></div>
                          </div>
                          @endif
                        </div>
                      @endif
                       
                      <div class="tab-pane fade {{($data['aba']=='relatorio')?'active show':''}}" id="relatorio" role="tabpanel" aria-labelledby="relatorio-tab">
                        @if(!$data['totalEntrevistas'])
                         <h5 align='center' class="mt-5 pt-5">Nenhum registro foi encontrado</h5>
                        @else
                        <div style="margin-bottom:5px!important" class="d-flex justify-content-between bd-highlight mb-3 col-12 col-sm-12">
                          <div></div>
                          <div>
                            <ul class="nav ">
                              <li class="nav-item">
                                <div class="dropdown">
                                  {{-- <a class="nav-link dropdown-toggle" style="width: 80px;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Excel
                                  </a> --}}
                                  <button type="button" class="btn btn-sm btn-secondary" style="padding:5px;" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                    Excel <i class="fa fa-file-excel fa-fw"></i></button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a style="padding: 5px!important;" class="dropdown-item sm-link" onclick="abrirModalDown('Excel','dados');">Baixar</a>
                                    <a style="padding: 5px!important;" class="dropdown-item sm-link" onclick="abrirModalDown('Excel','spss');" >Baixar com códigos</a>
                                  </div>
                                </div>
                              </li>
                              <li class="nav-item">
                                <button type="button" class="btn btn-sm btn-danger"  onclick="abrirModalDown('PDF','pdf');" role='pdf' style="padding:5px;margin-left:5px">Baixar PDF <i class="fa fa-file-pdf fa-fw"></i></button>
                              </li>
                            </ul>
                          </div>
                        </div>
      
                        <div id="box-relatorio"></div>
                        @endif
                      </div>
                   
                      <div class="tab-pane fade {{($data['aba']=='geolocalizacao')?'active show':''}}" id="geolocalizacao" role="tabpanel" aria-labelledby="geolocalizacao-tab">
                        <div style="margin-top: 5px">
                          <h5 class="py-1" >Geolocalização</h5>
                          <style> #map{height: 100%;}</style>
                          <div id="map" style="width:100%;height:600px;"></div>
                    
                        </div>
                       
                      </div>

                      <div class="tab-pane fade {{($data['aba']=='entrevistas')?'active show':''}}" id="entrevistas" role="tabpanel" aria-labelledby="entrevistas-tab">
                        <div style="margin-top: 5px">
                          <h5 class="py-1" >Números de entrevistas</h5>
                           <div id="list-entrevistas">
                           </div>
                        </div>
                      </div>
   
                      <div class="tab-pane fade {{($data['aba']=='entrevistas-realizadas')?'active show':''}}" id="entrevistas-realizadas" role="tabpanel" aria-labelledby="entrevistas-realizadas-tab">
                        <div style="margin-top: 5px">
                          <h5 class="py-1" >Entrevistas realizadas:</h5>
                          <div class="list-group" id="list-entrevistas-realizadas">
                               
                          </div>
                        </div>
                      </div>
                
        
                        {{-- <h5 class="py-1" >Localizações</h5>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th style="text-align: center;" >Longitude</th>
                              <th  style="text-align: center;">Latitude</th>
                            </tr>
                          <tbody>
                            @foreach($data['dashboard']['dadosLocalizacao'] as $item)
                            <tr>
                              <td align="center">{{$item['longitude']}}</td><td align="center">{{$item['latitude']}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table> --}}
                    </div>
                <br/>
                </div>
            </div>
          </div>

    </div>
    <div class="modal fade" id="modalMostraNoDownload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h3 class="modal-title" id="tituloMostrarDown" >Baixar Excel </h3>
          <button style="margin-top: -19px;" type="button" class="close" data-dismiss="modal" aria-label="Close"> X </button>
          </div>
          <div class="modal-body" style="padding:20px;padding-top:10px">
          <span class="my-5" data-encadear="titulo"></span>
          <div role="accordionEncadeamento" style="margin-top:10px">
            <form name="formEntrevistadores" id="">
              <label id="id-tb-dia-dia" style="display: none;"><input type="checkbox" name="tb-dia-dia" value="SIM">    Tabela dia a dia </label><br/>
              <label><input type="checkbox" name="tb-localizacao" value="SIM"> Incluir colunas de geolocalização</label>
           </form>
          </div>
          </div>
          <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
           <button type="button" onclick="fnAcaoDoModal();" class="btn btn-primary">Baixar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal entrevista-->
    @include('gestor.componentes.modal-ver-entrevista')
  </main>
  @include('gestor.templates.rodape')
</div>
    @include('gestor.templates.chamadas-down')
    <script src="{{asset('public/assets_gestor/js/dashboard.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfbZDkqzKw_aoInUOBgDogQFe_YJEHrDk&callback=initMap" async defer></script>
   
    <script src="{{asset('public/assets_gestor/js/dom-to-image.js')}}"></script>
    <script src="{{asset('public/assets_gestor/js/pdfmake.min.js')}}"></script>
    <script src="{{asset('public/assets_gestor/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('public/assets_gestor/js/pdfmain.js')}}"></script>
  <script>
    var dataGraf = JSON.parse("{{json_encode($dataGraf)}}".replace(/&quot;/gi, "\"")),
        tipoExcel='';
    // function openModalExcel(t){
    //   tipoExcel=t;
    //   $('#modalExcel').modal();
    // }

    var acaoDoModal='',downlaoadsDetalhes = {diaADia:false,localizacao:false},dadosLocalizacao=[];
    function abrirModalDown(valor,chave){
        acaoDoModal = chave;
        tipoExcel=chave;
        if(valor=='Excel'){
            document.querySelector('[id=id-tb-dia-dia]').style.display='none';
        }else{
            document.querySelector('[id=id-tb-dia-dia]').style.display='';
            document.querySelector('[name=tb-dia-dia]').checked = false;
        }
        document.querySelector('[name=tb-localizacao]').checked = false;
        document.getElementById('tituloMostrarDown').innerHTML = 'Mostrar no '+valor+':'
        $('#modalMostraNoDownload').modal();
    }
    function fnAcaoDoModal(){
        downlaoadsDetalhes = {
            diaADia:document.querySelector('[name=tb-dia-dia]').checked,
            localizacao:(document.querySelector('[name=tb-localizacao]'))?document.querySelector('[name=tb-localizacao]').checked:''
        };
        if(acaoDoModal=='dados'||acaoDoModal=='spss'){
            baixarExcel();
        // }else if(acaoDoModal=='spss'){
        //     baixarExcel('spss');
        }else if(acaoDoModal=='pdf'){
            baixarPDF();
        }else if(acaoDoModal=='word'){
            baixarWord();
        }
        $('#modalMostraNoDownload').modal('hide');
    }


    function onFiltror(obj){
      // var dM1=formtDataUS('{{ $dataMensuracao1}}').replace('-','').replace('-',''),
      //     dM2=formtDataUS('{{ $dataMensuracao2}}').replace('-','').replace('-',''),
        var  dados={pesquisa:"{{$data['pesquisa']['id']}}"},
          mensuracao=[],
          checkInitData=0
        
          for(var i=1;i<=3;i++){
            if(i>1){
              if(document.querySelector(`[data-mensuracao=data${i}]`).style.display=='none'){
                continue;
              }
            }
            var dmI=$(`input[name=mensuracaoDataInicio${i}]`).val(),dmF=$(`input[name=mensuracaoDataFim${i}]`).val();
            
            if(!dmI||!dmF){
              alertaPadrao.returnMensagem(`Preencha todos os campos M${i}!` , 'top', 'infor');
              return false;              
            }
            if(!validData(dmI)||!validData(dmF)){
              alertaPadrao.returnMensagem(`Data inválida nos campos M${i}!`, 'top', 'infor');
              return false;              
            }
            dmI = formtDataUS(dmI);
            dmF = formtDataUS(dmF);
            dmILim = parseInt(dmI.replace('-','').replace('-',''));
            dmFLim = parseInt(dmF.replace('-','').replace('-',''));

            if(dmILim>dmFLim){
              alertaPadrao.returnMensagem(`M${i}: A data de início não pode ser maior que a de encerramento!`, 'top', 'infor');
              return false;
            }
            
            if(i>1){
              var n=i-1;
              dmIAnt = parseInt(mensuracao[n]['inicio'].replace('-','').replace('-',''));
              dmFAnt = parseInt(mensuracao[n]['fim'].replace('-','').replace('-',''));
  
              if(dmILim<=dmFAnt||dmFLim<=dmFAnt){
                 alertaPadrao.returnMensagem(`Mensurações: M${i} não pode ser menor ou igual a data final da M${i-1}! `, 'top', 'infor');
                return false;
              }
            }
            // if(dmILim>dM2){
            //  alertaPadrao.returnMensagem(`M${i}: A mensuração inicia fora do `, 'top', 'infor');
            //  return false;
            // }

            mensuracao[i]={inicio:dmI,fim:dmF};
          }
          if(Object.keys(mensuracao).length>1){
            dashMensuracao=1;
          }else{
            dashMensuracao=0;
          }

          dados['mensuracao']=mensuracao;
  
        var filtros =  [''],fAux=[];
            for(var objInput of  $(`[data-checkPerg=filtro]`)){
              if($(objInput).attr('data-tipo')=='ABERTA'){
                var entrada = $(objInput).attr('data-tipo-entrada');
                if($(objInput).parent().hasClass("show")){
                  var min = $(objInput).find('input[name=min]').val(),max=$(objInput).find('input[name=max]').val();
                  if(entrada=='DATA'){
                    // console.log(min,max)
                    // if(!validData(min)||!validData(max)){
                    //   alertaPadrao.returnMensagem(`Filtors com datas inválidas!`, 'top', 'infor');
                    //   return false;              
                    // }
                    min = (min)?formtDataUS(min.replace('-','').replace('-','')):'';
                    max = (max)?formtDataUS(max.replace('-','').replace('-','')):'';
                  }else{
                    min = filterMoney(min);
                    max = filterMoney(max);
                  }
                  // if(min||max){
                    var idPerg = $(objInput).attr('data-perg');
                    fAux[idPerg]={
                      'tipo_pergunta':entrada,
                      'id_pergunta':idPerg,
                      'value':(min||max)?min+'_'+max:''
                    }
                  // }
                }
              }else{
                if(objInput.checked){
                  var idPerg = $(objInput).attr('data-perg');
                  if(!fAux[idPerg]){
                    fAux[idPerg]={
                      'tipo_pergunta':$(objInput).attr('data-tipo'),
                      'id_pergunta':idPerg,
                      'value':[]
                    }
                  }
                  fAux[parseInt(idPerg)]['value'].push(objInput.value);
                }
              }

            }
            for(var i in fAux){
              filtros.push(fAux[i]);
            }
               
            dados['filtros'] = filtros;

            for(var i=1;i<=4;i++){
              if(document.getElementById("dash"+i)){
                document.getElementById("dash"+i).innerHTML='<div class="loader" style="margin-top: 60px"></div>';
              }
            }
            if(document.getElementById("divNps")){
              document.getElementById("divNps").innerHTML='<div class="loader" style="margin-top: 60px;margin-bottom:60px"></div>';
            }

            obj.innerHTML = 'Filtrando...';
            obj.disabled = true;
              for(var i in dataDashboard.dashboard.dados){
                var ik =parseInt(i)+1;
                if(document.getElementById("rel"+ik)){
                  document.getElementById("rel"+ik).innerHTML='<div class="loader" style="margin-top: 60px"></div>';
                  document.getElementById("rel"+ik+'Descricao').innerHTML='';
                }
              }

        //  $('#peridoColet').html('');
        //  $('#entrevistados').html('');
        $.ajax({  // apiopaquivsproducao apiopaquiteste
        url: '{{env("APP_URL")}}dashboard/filtro',
                type: 'POST',
                dataType: 'JSON',
                data: dados,
                success: function (r) {
                  obj.innerHTML = 'Filtrar';
                  obj.disabled = false;
                  if(r.status){
                    dataDashboard['dashboard']=r.dashboard;
                    loadAbas();
                    initMap();
                    // var dRel =r.dashboard.dados,
                    //     dPerg=[],
                    //     num=0;
                    // for(var ob of dRel){
                    //   dPerg[ob['id']]=ob;
                    // }
                    // if(document.getElementById("divNps")){
                    //   gNps('divNps',r.dashboard.dadosNPS);
                    // }
                    // for(var i=1;i<=4;i++){
                    //   if(document.querySelector(`[name=sel-perg-${i}]`)){
                    //     gGraf('dash'+i,dPerg[document.querySelector(`[name=sel-perg-${i}]`).value]);
                    //   }
                    // }

                    // for(var i in dRel){
                    //   gGraf('rel'+i,dRel[i]);
                    // }
                  }
                }
        });

    }

    function baixarExcel(){
      // {{env('APP_URL')}}pesquisa/baixar/excel/{{'1'}}/codigo
      var ajax = new XMLHttpRequest(), form_data = new FormData();

      // downlaoadsDetalhes = {
      //   diaADia:document.querySelector('[name=tb-dia-dia]').checked,
      //   localizacao:(document.querySelector('[name=tb-localizacao]'))?document.querySelector('[name=tb-localizacao]').checked:''
      // };

      form_data.append('localizacao',(document.querySelector('[name=tb-localizacao]'))?document.querySelector('[name=tb-localizacao]').checked:'');
      form_data.append('tipoExcel',tipoExcel);
      form_data.append('pesquisa',"{{$data['pesquisa']['id']}}");
      ajax.onload = function (e) {
        var r = JSON.parse(this.responseText);
        if (r.status) {
          location.href= "{{env('APP_URL')}}download/excel/"+r.nome;
        } else {
          alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
        }
      };
      ajax.open('POST', '{{env("APP_URL")}}dashboard/baixar/excel', true); //faz a requisicao dos dados, via post
      ajax.send(form_data); //envia o form
      // $(this).dialog("close");
      $('#modalMostraNoDownload').modal('hide');
    }


    function togglePesquisa(id){
      var uri='';
      if(id){
        uri=`/${id}/${abaAtual}`;
        if(modMax){
          uri+='/zoom';
        }
      }
      location.href = urlBase+"dashboard"+uri;
    }
  </script>

  <script>
  function limparFiltros(){
  //  document.querySelector("[name=mensuracaoDataInicio1]").value ='{{$dataMensuracao1}}';
  //  document.querySelector("[name=mensuracaoDataFim1]").value ='{{$dataMensuracao2}}';
  //  document.querySelector("[data-mensuracao=data2]").style.display='none';
  //  document.querySelector("[data-mensuracao=data3]").style.display='none';
    for(var ob of document.querySelectorAll('[data-checkperg="filtro"]')){
    //  console.log(ob)
     if(ob.getAttribute('data-tipo')=='ABERTA'){
      ob.querySelector('input[name=min]').value='';
      ob.querySelector('input[name=max]').value='';
     }else{
      ob.checked=false;
     }
    }
  }
  function buscaPesquisa(){
   var data = {
     nome: $('[name="bus-nome-pesq"]').val(),
     dataIni: $('[name="bus-data-ini"]').val(),
     dataFim: $('[name="bus-data-fim"]').val(),
   };
   if(data.dataIni){
     if(!validData(data.dataIni)){
       alertaPadrao.returnMensagem(`Data de início inválida!`, 'top', 'infor');
       return false;              
     }
     data.dataIni = formtDataUS(data.dataIni.replace('-',''));
   }
   if(data.dataFim){
     if(!validData(data.dataFim)){
       alertaPadrao.returnMensagem(`Data de fim inválida!`, 'top', 'infor');
       return false;              
     }
     data.dataFim = formtDataUS(data.dataFim.replace('-',''));
   }

   var ajax = new XMLHttpRequest(), form_data = new FormData(),id="{{$data['pesquisa']['id']}}";
     form_data.append('nome', data.nome);
     form_data.append('dataIni', data.dataIni);
     form_data.append('dataFim', data.dataFim);
     ajax.onload = function (e) {
      var r = JSON.parse(this.responseText);
      if (r.status) {
        var html = '';
        for(var ob of r.data){
         html += `<li class="list-group-item iten-pesquisa sm-link" style='padding: 4px!important;${id==ob.id?'background:#ccc':''}' onclick="togglePesquisa(${ob.id})" >${ob.titulo}</li>`; 
        }
        if(!r.data.length){
          html = `<li class="list-group-item iten-pesquisa sm-link" >Nenhuma pesquisa foi encontrada.</li>`; 
        }
        document.getElementById('lista-busca').innerHTML=html;
      } else {
       alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
      }
     };
     ajax.open('POST', '{{env('APP_URL')}}dashboard/buscar/pesquisas', true); //faz a requisicao dos dados, via post
     ajax.send(form_data);
    return false;
  }
  </script>
  </body>
</html>