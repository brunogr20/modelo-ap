@php
 $valid = [];
  $satisfacao = [
      ['id' => '5', 'nome' => 'Ótimo', 'img' => 'happy.png'],
      ['id' => '4', 'nome' => 'Bom', 'img' => 'amost_happy.png'],
      ['id' => '3', 'nome' => 'Regular', 'img' => 'indiferent.png'],
      ['id' => '2', 'nome' => 'Ruim', 'img' => 'sad.png'],
      ['id' => '1', 'nome' => 'Péssimo', 'img' => 'angry.png'],
      ['id' => '0', 'nome' => 'Não se aplica', 'img' => 'n-opinar.png'],
];
// dd($data['questionario']['paleta']);
@endphp
 <!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/assets_gestor/css/questionario.css') }}">
    <link rel="shortcut icon" href="{{ asset('public/assets_gestor/img/favicon.ico') }}" />
    <script src="{{ asset('public/assets_gestor/js/jquery-3.4.1.min.js') }}" ></script>
    <script src="{{ asset('public/assets_gestor/alertZ/alertZ.js') }}" ></script>
    {{-- <script type="text/javascript" src="{{asset('public/assets_gestor/js/compress-img.js')}}"></script> --}}
  <title>PESQUISA - {{$data['questionario']['titulo']}}</title>
    <meta name="description" content="">
  </head>
  <body class="h-100" style="background-color:{{$data['questionario']['paleta']['cor_bg_fundo']}}">
    @if($data['questionario']['modo_pesquisa']=='TESTE')
    <div class="col d-flex justify-content-center" style="background: red">
      <span style="color: #fff"><b>Formulário em fase de teste</b></span>
    </div>
    @endif
      <div class="col d-flex justify-content-center barra-questionario-topo" style="background-color:{{$data['questionario']['paleta']['cor_bg_titulo']}}">
      <h3 class="text-center" style="color:{{$data['questionario']['paleta']['cor_ft_titulo']}}">
        {{$data['questionario']['titulo']}}
      </h3>
    </div>
        <main style="margin:auto" class="main-content main-box p-0 "> 
          @if($data['questionario']['exibir_texto_abertura'])
          <div class="abertura-questionario">
            <div class="row  py-3 p-5">
              <div class="col-12 col-sm-12" style="display:grid;">
                <h1 class="text-center"  style="color:{{$data['questionario']['paleta']['cor_ft_fundo']}}">
                  {!!formatText($data['questionario']['texto_abertura'])!!}
                </h1>
                <br/>
                @if($data['questionario']['url_imagem_abertura'])
                  <img style="max-width:400px;max-height:200px;margin:auto" src="{{$data['questionario']['url_imagem_abertura']}}" />
                  <br/>
                @endif
              </div>
              <div class="d-flex justify-content-center bd-highlight mb-3 col-12 col-sm-12">
                <button type="button" class="btn btn-primary btn-lg" onclick="iniciarQuestionario();" style="background-color:{{$data['questionario']['paleta']['cor_bg_botao_avancar']}};color:{{$data['questionario']['paleta']['cor_ft_botao_avancar']}}">
                  Iniciar
                </button>
              </div>
            </div>
          </div>
          @endif
          <div style="display:none" class="encerramento-questionario" >
            <div class="row  py-3 p-5">
              <div class="col-12 col-sm-12" style="display:grid;">
                <h1 class="text-center"  style="color:{{$data['questionario']['paleta']['cor_ft_fundo']}}">
                  {!!formatText($data['questionario']['texto_encerramento'])!!}
                </h1>
                <br/>
                @if($data['questionario']['url_imagem_encerramento'])
                  <img style="max-width:400px;max-height:200px;margin:auto" src="{{$data['questionario']['url_imagem_encerramento']}}" />
                  <br/>
                @endif
              </div>
            </div>
          </div>
          <div style="display:none" class="corpo-questionario" id='top-pagina'>
            @if($data['questionario']['descricao'])
              <h4 class="text-center questionario-descricao" style="color:{{$data['questionario']['paleta']['cor_ft_fundo']}}" >{{$data['questionario']['descricao']}}</h4>
            @else
             <br/>
            @endif
            <div class="main-content-container container-fluid px-4"> 
              <form  name="form_pesquisa" method="post" >
                  @foreach ($data['questionario']['grupoPerguntas'] as $keyGrp => $grupoPerg)
                    @php
                      $valid[$keyGrp]=['visivel'=>true];
                    @endphp
                    <div style="display:none;" data-id-tela="idTela<?= $keyGrp ?>" onclick="encadearPerguntas()">
                      <p class="col-12 font-weight-bold" align="right" style="color:{{$data['questionario']['paleta']['cor_ft_fundo']}}"><span class="atual">{{$keyGrp+1}}</span>/<span data-total="perg">{{count($data['questionario']['grupoPerguntas'])}}</span></button>
                        @foreach ($grupoPerg as $keyPerg => $pergunta)
                          @php
                            $valid[$keyGrp]['perg'][$keyPerg]=['visivel'=>true];
                            $valid[$keyGrp]['perg'][$keyPerg]['encadeamento'] = $pergunta['encadeamento'];
                            $valid[$keyGrp]['perg'][$keyPerg]['i'] = $pergunta['id'];
                            $valid[$keyGrp]['perg'][$keyPerg]['e'] = $pergunta['tipo_resp_entrada'];
                            $valid[$keyGrp]['perg'][$keyPerg]['st_justificativa'] = $pergunta['st_justificativa'];
                            $valid[$keyGrp]['perg'][$keyPerg]['obrig'] = ($pergunta['resposta_obrig'] == 'sim') ? 1:0;
                            if ($pergunta['tipo_pergunta'] == 'ABERTA') {
                              $valid[$keyGrp]['perg'][$keyPerg]['t'] = $pergunta['tipo_pergunta'] . '_' . $pergunta['tipo_resp_entrada'];
                              $nomePer = 'PER__' . $pergunta['tipo_pergunta'] . '_' . $pergunta['tipo_resp_entrada'] . '__' . $pergunta['id'];
                            } else {
                              $valid[$keyGrp]['perg'][$keyPerg]['t'] = $pergunta['tipo_pergunta'];
                              $nomePer = 'PER__' . $pergunta['tipo_pergunta'] . '__' . $pergunta['id'];
                            }
                          @endphp
                          @if($pergunta['tipo_pergunta']=='MOSTRAR_TEXTO')
                          <div class="card card-small card-msg " style="background-color:{{$data['questionario']['paleta']['cor_bg_caixa']}}"  data-id-perg="idPerg{{$keyGrp . $keyPerg}}"  data-pergunta="{{$pergunta['tipo_pergunta']}}" >
                            <div class="row  py-3 p-5">
                              <div class="col-12 col-sm-12">
                              <h3 class="text-center titulo-font-{{$pergunta['tam_fonte']}}" style="color:{{$data['questionario']['paleta']['cor_ft_pergunta']}}">{!!$pergunta['texto']!!}</h3>
                              </div>
                            </div>
                          </div>
                          @else
                        <div class="card card-small" style="background-color:{{$data['questionario']['paleta']['cor_bg_caixa']}};border: 1px solid {{$data['questionario']['paleta']['cor_bd_caixa']}}" data-id-perg="idPerg{{$keyGrp . $keyPerg}}"  data-pergunta="{{$pergunta['tipo_pergunta']}}">
                                <div class="row  py-3 p-5">
                                  <div class="col-12 col-sm-12">
                                      <h3 class="text-left titulo-font-{{$pergunta['tam_fonte']}}" style="color:{{$data['questionario']['paleta']['cor_ft_pergunta']}}" >
                                        {{$pergunta['titulo']}}
                                      </h3>
                                      {{-- @if($pergunta['descricao']) --}}
                                      <p class="descricao-font-{{$pergunta['tam_fonte']}}" style="margin-left:5px;margin-bottom:10px;color:{{$data['questionario']['paleta']['cor_ft_descricao']}}">{{$pergunta['descricao']}}</p>
                                      {{-- @endif --}}
                                      <div>
                                        @if($pergunta['tipo_pergunta']=='ABERTA')
                                          <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12"  align="left">
                                            @if($pergunta['tipo_resp_entrada']=='DATA')
                                              <input name="{{$nomePer}}" placeholder="00/00/0000" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');" class="form-control form-control-lg" type="tel" />
                                            @elseif($pergunta['tipo_resp_entrada']=='MONETARIO')
                                             <input name="{{$nomePer}}" placeholder="R$ 0,00" onkeypress="return currencyFormat(this, '.', ',', 2, event);" class="form-control form-control-lg" type="tel" />
                                            @elseif($pergunta['tipo_resp_entrada']=='NUMERICO')
                                             <input name="{{$nomePer}}" onkeypress="return mascaraCep(event, this, '###########');" class="form-control form-control-lg" type="tel" />
                                            @elseif($pergunta['tipo_resp_entrada']=='EMAIL')
                                             <input name="{{$nomePer}}" class="form-control form-control-lg" type="email" />
                                            @else 
                                             <input name="{{$nomePer}}" class="form-control form-control-lg" type="text" />
                                            @endif
                                          </div>
                                        @elseif(in_array($pergunta['tipo_pergunta'],['NET_PROMOTER_SCORE','ESCALA_LIKERT_0_10']))
                                        @php
                                        $initI =0;
                                        $endI =10;
                                        if($pergunta['tipo_pergunta']=='ESCALA_LIKERT_0_10'){
                                         $escalaAux =  explode('-',$pergunta['tipo_resp_entrada']);
                                         $initI =$escalaAux[0];
                                         $endI =$escalaAux[1];
                                        }
                                        @endphp
                                          <div class="d-flex  nps-box-bts">
                                            <div class="d-flex nps-bloco-1">
                                              @for($i=$initI;$i<=$endI;$i++)
                                              @if($i==6)
                                              </div>
                                              <div class="d-flex nps-bloco-2 ">
                                             @endif
                                                <div>
                                                  <button data-bt="check" type="button" for="r{{$i}}" class="btn btn-nps resp-nps-tmp-{{$pergunta['tam_fonte']}}  {{$pergunta['tipo_pergunta']=='NET_PROMOTER_SCORE'?'nps-cor-'.$i:'likert-1-10'}}">{{$i}}</button>
                                                  <input name="{{$nomePer}}"  class="d-none" type="radio" value="{{$i}}">
                                                </div>
                                             @endfor
                                            </div>
                                          </div>
                                            @if($pergunta['st_justificativa']=='sim')
                                            <div style="margin-top:15px" class=" col-xs-12 col-sm-12 col-md-12 col-lg-12"  align="left">
                                              <h5 class="text-left" style="margin-bottom:6px;color:{{$data['questionario']['paleta']['cor_ft_descricao']}}">
                                                {{$pergunta['texto']}}
                                              </h5>
                                              <input name="PER_JUSTIFICATIVA_{{$pergunta['id']}}" class="form-control form-control-lg" type="text" />
                                            </div>
                                            @endif
                                        @elseif(in_array($pergunta['tipo_pergunta'],['FECHADA_RESPOSTA_UNICA','FECHADA_RESPOSTA_MULTIPLA']))
                                          <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8"  align="left">
                                            @php
                                            $dataRespostas = [];
                                            if($pergunta['resposta_randomica']=='sim'){
                                              $tRespDin = count($pergunta['respostas'])-1;
                                              $arBlock=[];
                                              foreach($pergunta['respostas'] as $valDinamic) {
                                                $iRand=0;
                                                while (true) {
                                                 $iRand=rand (0,$tRespDin);
                                                 if(!in_array($iRand,$arBlock)){
                                                  $arBlock[]=$iRand;
                                                  break;
                                                 }
                                                }
                                                $dataRespostas[$iRand] = $valDinamic;
                                              }
                                              ksort($dataRespostas);
                                            }else{
                                              $dataRespostas = $pergunta['respostas'];
                                            }
                                            @endphp
                                            @foreach ($dataRespostas as $keyResp => $resposta)
                                              <div class="form-check ">
                                                @if($pergunta['tipo_pergunta']=='FECHADA_RESPOSTA_MULTIPLA')
                                              <input name="{{$nomePer.'__'.$resposta['id']}}" class="form-check-input" data-checkbox="ckeck{{$pergunta['id']}}" type="checkbox" value="{{$resposta['id']}}" id="{{$nomePer.'__'.$resposta['id'].'_'.$keyResp}}">
                                                <label class="form-check-label resposta-check texto-resp-font-{{$pergunta['tam_fonte']}}" for="{{$nomePer.'__'.$resposta['id'].'_'.$keyResp}}">
                                                  <strong>{{$resposta['titulo']}}</strong>
                                                </label>
                                                @else
                                              <input name="{{$nomePer}}" class="form-check-input" type="radio" value="{{$resposta['id']}}" id="{{$nomePer.'_'.$keyResp}}">
                                                <label class="form-check-label resposta-check texto-resp-font-{{$pergunta['tam_fonte']}}" for="{{$nomePer.'_'.$keyResp}}">
                                                  <strong>{{$resposta['titulo']}}</strong>
                                                </label>
                                                @endif
                                              </div>
                                            @endforeach
                                          </div>
                                          @elseif($pergunta['tipo_pergunta']=='AVALIACAO_SATISFACAO')
                                          <div class="d-flex justify-content-between bd-highlight mb-3 col-12 col-sm-12">
                                            @foreach($satisfacao as $s)
                                            @if($s['id']=='0')
                                              <div style="width:108px;display:flex;flex-direction: column;align-items: center; margin-top: 7px;" >
                                                <img class="img-satisfacao" style='width:90px' data-bt="check" src="{{url('public/assets_gestor/img/' . $s['img'])}}" >
                                                <input  name='{{$nomePer}}'  class="d-none" type="radio" value="{{$s['id']}}">
                                                <p align="center" style="color:#bfbfbf;margin-top: 13px;" class="texto-resp-font-{{$pergunta['tam_fonte']}}" ><b>{{$s['nome']}}</b></p>
                                              </div>
                                              @else
                                                <div >
                                                  <img class="img-satisfacao" data-bt="check" src="{{url('public/assets_gestor/img/' . $s['img'])}}" >
                                                  <input  name='{{$nomePer}}'  class="d-none" type="radio" value="{{$s['id']}}">
                                                  <p align="center" class="texto-resp-font-{{$pergunta['tam_fonte']}}" ><b>{{$s['nome']}}</b></p>
                                                </div>
                                              @endif
                                            @endforeach
                                          </div>
                                        @endif
                                      </div>
                                  </div>
                                  <div style="width:100%;padding-left: 150px;padding-right: 150px;" id="{{$pergunta['tipo_pergunta'] . $pergunta['id']}}" style="display:none;"  ></div>
                                </div>
                              </div>
                          @endif
                          <br/>
                        @endforeach
                    </div>
                  @endforeach
                  <div class="d-flex justify-content-between bd-highlight mb-3 col-12 col-sm-12" style="margin-bottom:80px!important;">
                    <div>
                      <button type="button" class="btn btn-primary btn-lg bt-voltar" style="display:none;background-color:{{$data['questionario']['paleta']['cor_bg_botao_voltar']}};color:{{$data['questionario']['paleta']['cor_ft_botao_voltar']}}" >
                       <i class="fa fa-arrow-left fa-fw" ></i> VOLTAR
                      </button>
                    </div>
                    <div>
                      <button type="button" class="btn btn-primary btn-lg bt-continuar" style="background-color:{{$data['questionario']['paleta']['cor_bg_botao_avancar']}};color:{{$data['questionario']['paleta']['cor_ft_botao_avancar']}}">
                         CONTINUAR <i class="fa fa-arrow-right fa-fw" ></i>
                      </button>
                    </div>
                  </div>
              </form>
          </div>
        </main>
        <script>
          var valid = JSON.parse('{{json_encode($valid)}}'.replace(/&quot;/gi, "\"")),
          totalPerguntas = parseInt('{{count($valid)}}'),
          idPesquisa = "{{$data['questionario']['id']}}",
          tPesquisa = '{{$data["idToken"]}}',
          tUsuario = '{{$data["idTokenContato"]}}',
          baseUrl = '{{env("APP_URL")}}',
          numPgVisisvel = 1,
          numPg = 0;
        </script>
        <script type="text/javascript" src="{{asset('public/assets_gestor/js/fnPesquisa.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/assets_gestor/js/questionario.js')}}"></script>
        @if(!$data['questionario']['exibir_texto_abertura'])
          <script>
           iniciarQuestionario();
          </script>
        @endif
  </body>
</html>
