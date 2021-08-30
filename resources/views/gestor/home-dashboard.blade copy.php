
<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <script src="{{asset('public/vendor/amcharts4/core.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/charts.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/dataviz.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/animated.js')}}"></script>
    <script src="{{asset('public/assets_gestor/js/questionario.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/assets_gestor/css/dashboard.css') }}">
    <title>Dashboard - op Aqui</title>
    <meta name="description">
    <style>
      .contato-select,.grupo-radio{width: 50%;display: inline-block;vertical-align: top;}
      .grupo radio .radio:first-child {margin-top: 0;}
    </style>
    <script>
      function getGraphHome(num,tEntrevistas,total,status){
        total = parseInt(total);
        tEntrevistas = parseInt(tEntrevistas);
        var pendentes = total-tEntrevistas;
            // Themes begin
            var realizados = parseFloat((tEntrevistas*100)/total).toFixed();
           
            restestantes =  parseFloat(((total-tEntrevistas)*100)/total).toFixed();

            if(['0.0','100.0'].includes(realizados)){
              realizados = realizados.split('.')[0];
            }
            if(['0.0','100.0'].includes(restestantes)){
              restestantes = restestantes.split('.')[0];
            }
            
            // Add data
            am4core.useTheme(am4themes_animated);    
            var chart = am4core.create("charsNum"+num, am4charts.PieChart);
            chart.data = [{"title":"Realizadas","num": realizados},{"title":"Restantes","num":restestantes}];
            
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "num";
            pieSeries.dataFields.category = "title";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;
      
      }
          </script>
  </head>
  <body class="h-100">
    <div >

      @include('gestor.templates.header')

      <main style="margin:auto" class="main-content p-0 ">

        <div class="main-content-container container-fluid px-4">

         @include('gestor.templates.menu')
         
            <div class="wrapper card" style="overflow: hidden;flex-direction:row;">
                <!-- Sidebar Holder -->
                <nav id="sidebar">
                    <div class="sidebar-header">
                        <h4 style="color: #ffffff">Filtro</h4>
                    </div>

                    <ul class="list-unstyled components">
                        <h5 class="ml-2">Mensuração</h5>
                        @for($i=1;$i<=3;$i++)
                          <li class="active" style="display:flex;margin-left:2px;">
                            <div class="grupo-radio">
                              <div class="radio">
                                  <label>De <input type="text" style="width:100px;" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');" ></label>
                              </div>
                            </div>
                            <div class="grupo-radio">
                              <div class="radio">
                                  <label> a <input type="text" style="width:105px;" maxlength="10" onkeypress="return mascaraCep(event, this, '##/##/####');"></label>
                              </div>
                            </div>
                          </li>
                        @endfor

                        <li>
                          <hr/>
                          <h5 class="ml-2">Filtros</h5>
                          @foreach($data['perguntas'] as $pergunta)
                          <a href="#pageSubmenu{{$pergunta['id']}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">{{$pergunta['titulo']}}</a>
                            <ul class="collapse list-unstyled" id="pageSubmenu{{$pergunta['id']}}">
                              @foreach($pergunta['respostas'] as $val)
                                <li>
                                  <div class="form-check" style="margin-bottom:5px;margin-left:15px" id="divEmailEntrada13">
                                  <input type="checkbox" name="entrada" class="form-check-input sm-link" value="sim" id="checkResp{{$pergunta['id'].'-'.$val['id']}}">
                                    <label class="form-check-label sm-link" for="checkResp{{$pergunta['id'].'-'.$val['id']}}">{{$val['titulo']}}</label>
                                  </div>
                                </li>
                              @endforeach
                            </ul>
                            @endforeach
                        </li>
                    </ul>
                </nav>

                <div id="content">

                    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="box-shadow:none;margin-bottom:15px;padding:0px;padding-top:10px;padding-bottom:10px">
                        <div class="container-fluid">
                            <button type="button" id="sidebarCollapse" data-filter="true" class="navbar-btn active">
                                <i  style="display:none" data-icon="filter" class="fas fa-filter"></i>
                                <i data-icon="close" class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-align-justify"></i>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="nav navbar-nav ml-auto" style="margin:auto;width:95%">
                                    <li class="nav-item active" style="width:95%">
                                      <div class="form-group" style="margin: 0;">
                                        {{-- <label>Pesquisa:</label> --}}
                                        <select class="form-control form-control">
                                          <option>Escolha uma opção</option>
                                           @foreach($data['perguntas'] as $pergunta)
                                           <option value="{{$val['id']}}" >{{$val['titulo']}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <ul class="nav nav-pills " id="pills-tab" role="tablist" style="margin-left: 4px">
                      <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Relatório</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Detalhes</a>
                      </li>
                      <li class="nav-item">
                        <div class="dropdown">
                          <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Excel
                          </a>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a style="margin-left: 5px;" class="dropdown-item" href="{{env('APP_URL')}}pesquisa/baixar/excel/{{'1'}}">Baixar</a>
                            <a style="margin-left: 5px;" class="dropdown-item" href="{{env('APP_URL')}}pesquisa/baixar/excel/{{'1'}}/codigo">Baixar com códigos</a>
                          </div>
                        </div>
                      </li>
                      <ul class="navbar-nav mr-auto"></ul>
          
                      <li class="nav-item" style="background:none;color:#9B9B9B!important;box-shadow:none;">
                         {{-- right --}}
                      </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent" style="padding-left: 10px;padding-right: 12px;">
                      <div class="tab-pane fade show active row" id="pills-home" role="tabpanel" style="display:flex;" >
                       
                        <?php foreach ($data['pesquisas'] as $key => $value){?>
                          <div class="col-md-6 bloco-card"  >
                          <div class="card card-small {{$value['emAndamento']?'card-pesquisa-ativa':'card-pesquisa-inativa'}}">
                              <div class="card-header border-bottom">
                                <select class="form-control form-control">
                                  <option>Escolha uma opção</option>
                                  @foreach($data['perguntas'] as $val)
                                   <option value="{{$val['id']}}" >{{$val['titulo']}}</option>
                                  @endforeach
                                </select>  
                              </div>
                              <div class="card-body pt-0">
                                <div class="row  py-2 bg-light">
                                  <div class="col-12 col-sm-12">
                                    <span class="text-left">Período de coleta: {{retrieveDatetime($value['data_inicio'])}} a {{retrieveDatetime($value['data_fim'])}}</span><br/>
                                  </div>
                                  <div class="col-12 col-sm-9">
                                  <span class="text-left">Entrevistas realizadas: {{$value['entrevistasRealizadas']}}</span><br/>
                                  <span class="text-left">Quantidade esperada: {{$value['quant_entrevistas_esperadas']}}</span><br/>
                                  <br/>  
                             
                                </div>
                                  {{-- <div class="col-12 col-sm-1 d-flex mb-2 mb-sm-0">
                                    <div class=" ml-auto mr-auto ml-sm-auto mr-sm-0 mt-3 mt-sm-0" href="{{env('APP_URL')}}pesquisa/{{$value['id']}}/{{strUrl($value['titulo'])}}">
                                      <button type="button" class="btn btn-link" style="padding-top:4px;padding-bottom:3px;font-size:14px"><b>Relatório</b></button>
                                      <button type="button" class="btn btn-link"  style="padding-top:4px;padding-bottom:3px;font-size:14px"><b>Comparativo</b></button>                  
                                    </div>
                                  </div> --}}
                                
                                  <div style="width:100%;height: 165px;" id="charsNum{{$key}}"></div>
                                  <script>getGraphHome('{{$key}}',"{{$value['entrevistasRealizadas']}}","{{$value['quant_entrevistas_esperadas']}}","{{$value['emAndamento']}}");</script>
                                </div>
                            
                              </div>
                            </div>
                          </div>
                      <?php } ?>

                      </div>
                      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                    </div>
          <br/>
                </div>
            </div>


    </div>

  </main>
  @include('gestor.templates.rodape')
</div>
    @include('gestor.templates.chamadas-down')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script type="text/javascript">
          $(document).ready(function () {
              $('#sidebarCollapse').on('click', function () {
                  $('#sidebar').toggleClass('active');
                  if($(this).attr("data-filter")=='true'){
                    $(this).find("[data-icon=filter]").show();
                    $(this).find("[data-icon=close]").hide();
                    $(this).attr("data-filter",'false');
                  }else{
                    $(this).find("[data-icon=filter]").hide();
                    $(this).find("[data-icon=close]").show();
                    $(this).attr("data-filter",'true');
                  }
              });
          });
      </script>
  </body>
</html>