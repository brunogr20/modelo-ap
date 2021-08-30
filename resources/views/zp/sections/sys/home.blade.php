@extends('zp.components.main') 
@section('sectionContent') {{-- LINHA 1 --}}
   {{-- Topo para o admin geral --}}
  @if(!empty($page['data']['home_box']))
  <div class="row top_tiles">
    @foreach($page['data']['home_box'] as $box)
     <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
      <div class="icon"><i class="{{$box['icon']}}"></i></div>
        <div class="count">{{$box['count']}}</div>
        <h3>{{$box['title']}}</h3>
        <p>{{$box['text']}}</p>
      </div>
    </div>
    @endforeach
  </div> 
  @endif
  
  {{-- Coluna Esquerda --}}
  <div class='col-md-8'>
    <div class="row">
      {{-- Meus acessos --}}
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-bar-chart"></i> Meu acessos</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
          </div>
          <div class="x_content">
            <div class="col-md-12">
              <div style='margin-top: 17px;padding-left:7px;'  class="col-md-4">
                  <ul class="list-unstyled timeline">
                      <li>
                        <p>Mais Acessados</p>
                        @if(count($page['data']['accesses']['more_accesses']))
                          @foreach ($page['data']['accesses']['more_accesses'] as $moreAc) 
                          <div class="block_content">
                          <h2 class="title">
                            {{ $moreAc['menu']}} >
                            @if(!empty($moreAc['submenu']))
                            {{$moreAc['submenu']}} >
                            @endif
                            <a href="{{$moreAc['url']}}"><i>{{$moreAc['secao']}}</i></a>
                          </h2>
                          <div class="byline">
                            {{-- <span>há 2 horas</span> --}}
                          </div>
                        </div>
                        @endforeach
                        @endif
                      </li>
                      <li>
                        <p>Acessos recentes</p> 
                        @if(count($page['data']['accesses']['recents']))
                          @foreach ($page['data']['accesses']['recents'] as $recent) 
                          <div class="block_content">
                          <h2 class="title">
                            {{ $recent['menu']}} >
                            @if(!empty($recent['submenu']))
                            {{$recent['submenu']}} >
                            @endif
                            <a href="{{$recent['url']}}"><i>{{$recent['secao']}}</i></a>
                          </h2>
                          <div class="byline">
                            <span>{{$recent['time']}}</span>
                          </div>
                        </div>
                        @endforeach
                        @endif
                      </li>
                  </ul>
              </div>
              <div style='margin-top: -17px' class="col-md-8">
                <div id="chartAccesses" style="width:100%;height: 250px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>   
    </div>
    <div class="row">
      {{-- Minhas Notas --}}
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-edit"></i> Minhas Anotações</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
          </div>
          <div class="x_content">
            <div id="home-notes" class="col-md-12">
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
  {{-- Coluna Direita --}}
  <div class="col-md-4">
    {{-- Minhas Atividades --}}
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-laptop"></i> Minhas Atividades</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
      
        </div>
        <div class="x_content"> 
          <div class="scroll middle">
              @foreach($page['data']['last_actions'] as $action)
                    <article class="media event">
                      <a href="{{$action['url']}}" class="pull-left date">
                        <p class="month">{{$action['mes']}}</p>
                        <p class="day">{{$action['dia']}}</p>
                      </a>
                      <div class="media-body">
                        <a class="title"  href="{{$action['url']}}">{{$action['secao']}}</a>
                        @php
                            switch($action['acao']):
                                case "UPDATE":
                                  $t= "Você <strong>atualizou</strong> um registro.";
                                break;
                                case "CREATE":
                                  $t= "Você <strong>criou</strong> um registro.";
                                break;
                                case "DELETE":
                                  $t= "Você <strong>apagou</strong> um registro.";
                                break;
                                default:
                                  $t="Você visitou essa sessão";
                                break;
                            endswitch;
                        @endphp
                        <p>{!! $t !!} <br>às {{$action['hora']}}</p>
                      </div>
                    </article>
              @endforeach  
          </div>
        </div>
      </div>
    </div>
    {{-- Resumo atividades --}}
    <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
              <h2><i class="fa fa-circle-o-notch"></i> Resumo atividades</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
        
          </div>
          <div class="x_content"> 
            <div class="col-md-12">
                <div style="width:100%;height: 165px;" id="charsAction"></div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div> <!-- End Row -->
  @if(Session::get('message_welcome'))
  @php
    Session::forget('message_welcome')
  @endphp
   <script>
    $(function(){
     $.alertMessage('welcome','Olá <?=Auth::guard("zp")->user()->nome;?>,<br/> Seja bem-vindo!','bottom-rigth',10);
    });
   </script>
  @endif
<script>
 var dataAccesses=JSON.parse('<?=json_encode($page['data']['chart_accesses']);?>');
 var dataActions=JSON.parse('<?=json_encode($page['data']['chart_actions']);?>');
 $(document).ready(function(){
    loadNote('all',{loadHomeNotes: true});
 });
</script> 
<script src="{{asset('public/assets_zp/js/sys/home.js')}}"></script>

@endsection

