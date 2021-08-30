<?php
// function perEntrevistas($total,$resp){
//   return ($total/100*$resp);
// }
//dd($data['planosEmAberto']);

foreach ($data['pesquisas'] as $key => $value){
  // $percent=perEntrevistas($value['quant_entrevistas_esperadas'],$value['entrevistasRealizadas']);
  if($value['entrevistasRealizadas']>=15 && $value['modo_pesquisa']=='TESTE'){
    $limiteEntrevista  = 'background:red !important;';             
  }else{
    $limiteEntrevista='';    
  }
  ?>
  <!-- Users Stats -->
    {{-- <div class="col-lg-12 col-md-12 col-sm-12 mb-4 "> --}}
    <div class="col-md-6 bloco-card"  >
      <div class="card card-small {{$value['emAndamento']?'card-pesquisa-ativa':'card-pesquisa-inativa'}}">
        <div class="card-header border-bottom" style="display: flex;{{$limiteEntrevista}}">
          <h6 class="m-0 mr-auto">{{resum($value['titulo'],110)}}</h6>

          <span style="width: 43px;">
            @php
            //$nomeIcon =$value['emAndamento']?'ativo':'inativo'
            $nomeIcon ='inativo'
            @endphp
            @if($value['status_plano'])
              
                @if($data['planosEmAberto']>0)        
                  <img class="sm-link" style="width:19px" title="Duplicar" onclick="duplicarPesquisa({{$value['id']}})" src="{{url('/public/assets_gestor/img/replicate-'.$nomeIcon.'.png')}}"/>
                @endif
              <img class="sm-link" style="width:19px" title="Compartilhar" onclick="compartilharPesquisa({{$value['id']}},'{{strUrl($value['titulo'])}}')" src="{{url('/public/assets_gestor/img/share-'.$nomeIcon.'.png')}}"/>
             {{-- <img class="sm-link" style="width:19px" title="Deletar" onclick="deletarPesquisa({{$value['id']}});"  src="{{url('/public/assets_gestor/img/delete-'.$nomeIcon.'.png')}}"/> --}}
            @endif  
        </span>
        </div>
        <div class="card-body pt-0">
          <div class="row  py-2 bg-light">
            <div class="col-12 col-sm-12">
              @if(count($value['planosAtivos']))
              <span class="text-left">Assinatuta:
                @foreach ($value['planosAtivos'] as $pln)
                {{$pln['titulo']}} de {{retrieveDatetime($pln['data_inicio'])}} a {{retrieveDatetime($pln['data_fim'])}}
                @endforeach
              </span>
              @else
              <span class="text-left">Assinatuta: Nenhum plano ativo</span>
              @endif
              <br/>
              <?php
                if($value['modo_pesquisa']=='TESTE'){
                  $mod  = 'PRÉ-TESTE DO FORMULÁRIO';
                }else{
                  $mod  = 'FORMULÁRIO VALIDADO';
                }
              ?>
                          
              
              <span class="text-left">Status do plano: {{$value['status_plano']?'Ativo':'Inativo'}}</span><br/>
              <span class="text-left">Modo: {{$mod}}</span><br/>
              <span class="text-left">Período de coleta: {{retrieveDatetime($value['data_inicio'])}} a {{retrieveDatetime($value['data_fim'])}}</span><br/>
            </div>
            <div class="col-12 col-sm-7">
            <span class="text-left">Total de perguntas: {{$value['perguntasTotal']}}</span><br/>
            <span class="text-left">Entrevistas realizadas: {{$value['entrevistasRealizadas']}}</span><br/>            
            {{-- <span class="text-left">Andamento:</span> --}}
            </div>
            <div class="col-12 col-sm-5 d-flex mb-2 mb-sm-0" style="margin-top:10px">
              <a class=" ml-auto mr-auto ml-sm-auto mr-sm-0 mt-3 mt-sm-0" href="{{env('APP_URL')}}pesquisa/{{$value['id']}}/{{strUrl($value['titulo'])}}">
               @if($value['status_plano'])
                <button type="button" class="btn btn-sm btn-white ml-auto mr-auto ml-sm-auto mr-sm-0 mt-3 mt-sm-0"><b>Ir para a pesquisa <i class="fas fa-arrow-right"></i></b></button>
               @endif
              </a> 
            </div>
          </div>
          {{-- <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: {{$percent}}%;" aria-valuenow="{{$percent}}" aria-valuemin="0" aria-valuemax="100">{{$percent}}%</div>
          </div> --}}
        </div>
      </div>
    </div>
<?php } ?>