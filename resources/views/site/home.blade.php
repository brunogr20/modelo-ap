<!DOCTYPE html>
<html lang="pt-br">
<head>
        @include('site.templates.template-chamada')
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets_gestor/img/favicon.png') }}">
</head>
<body>
    <div id="fullPage">
        <div class="section" data-anchor="slide1">
            <img class="back" src="{{asset('public/assets_site/imgs/Grupodemascara10.png')}}" alt="">
            <img id="bolhapequena" src="{{asset('public/assets_site/imgs/Elipse9.png')}}" alt="">
            <header>
            <div class="logoPrincipal" style="width:23%">
                    <img src="{{asset('public/assets_site/imgs/logoBranca.png')}}" alt="">
                </div>
                <button onclick="login();" class="entrar">Entrar</button>
                <div class="textoPrincipal">
                    <div class="texto">
                        <h1>Monitore a</h1>
                        <span class="textoDestaque">satisfação<h2 style="margin-left: 0.2em;">do</h2></span>
                        <span class="textoDestaque" style="font-size: 3.4rem;border-bottom: 4px solid #1887d4;margin-top: -10px;height:70px">seu cliente</span>
                        <h2>em tempo real</h2>
                        <p>Escute a voz do cliente de forma automatizada e sistemática com auxílio de profissionais especializados</p>
                        <button>Saiba mais</button>
                    </div>
                </div>
                <div class="imagemPrincipal">
                    <img src="{{asset('public/assets_site/imgs/Ativo1.png')}}" alt="">
                </div>
            </header>
        </div>
        
        

        <div class="section" data-anchor="slide2">
            <h1 class="titulo mx-5 px-5">planos</h1>
            <div class=" row" style="width: 80%;margin: auto;" >
                @foreach($data['planos'] as $key  => $item)
                    {{-- <div class="col-lg-2 mb-2"> --}}
                    @if($item['tipo']!='GRATUITO')
                        <div class="col-lg col-md-6 col-sm-6 mb-2">
                            <div class="card h-100  box-plano" style="overflow:hidden;border-radius: 15px">
                                <div class="plano-header">
                                <h3 align='center' style="font-size: 26px;line-height:1" class="card-title p-1 pt-2">{{$item['titulo']}}</h3>
                                    <div class="" style="display: flex;width: 130px; margin: auto;">
                                        <span style="margin-left: 3px;color: #fff;font-size:12px">
                                        @if ($item['tipo']!='GRATUITO')
                                        R$<br/>
                                        @endif
                                        @if($item['tipo']!='MENSAL' && $item['tipo']!='GRATUITO')
                                        {{$item['parcela']}}x 
                                        @endif
                                        </span>
                                        @if($item['valor']>0)
                                        <h2 align='center' style="color:#fff" class="font-weight-bold">{{number_format($item['valor']/$item['parcela'], 2, ',', '.')}}</h2>
                                        @else 
                                        <h2 align='center' style="color:#fff" class="font-weight-bold">Gratuito</h2>
                                        @endif
                                    </div>
                                </div>
                                <img style="width: 100%;margin-left: -2px;" src="{{url('/public/assets_gestor/img').'/ondaAzul.png'}}" />
                                {{-- <h4 align='center' class="card-title p-1 pt-2">{{$item['titulo']}}</h4> --}}
                                <div class="card-body bady-detalhes-compras" style="padding-top:0px;">
                                    <img style="width: 81px;margin: auto;" src="{{url('/public/assets_gestor/img/').'/'.str_replace(' ','-',strtolower($item['titulo'])).'.png'}}" />
                                    <p align='center' style="margin-bottom: 5px;" class="card-text p-1">{{$item['texto']}}</p>
                                    <button type="button" onclick="escolhendoPlano({{$item['id']}})" style="border-radius: 30px;background: #1565C0" class="btn btn-pill btn-info btn-lg font-weight-bold">Detalhes</button>
                                    {{-- <button type="button" onclick="iniciarTransacao({{$item['id']}})" class="btn btn-success btn-lg font-weight-bold">Detalhes</button> --}}
                                </div>
                            
                            </div>
                        </div>
                    @endif
                @endforeach
             </div>
             <div class="row" style="    margin-right: 0px;flex-direction:column;align-items:center">
                <div class="col-lg-12 col-md-12 mb-11" style="width: 81%;">
      
                  <div class="row  card h-100 plano-teste" style="border-radius: 30px;display: flex; flex-direction: inherit; background: #1565C0;                  ">
                    <div class="col-lg-4 col-md-4" style="display: flex;" >
                      <img style="width:60%;padding: 17px;margin: auto;" src="{{url('/public/assets_gestor/img/').'/robo-m-direita.png'}}" />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <br>
                      <h4 align='center' class="card-title p-1 pt-2">Teste grátis por 7 dias</h4>
                      <p align='center' class="card-text m-1">Teste por 7 dias e conheça nossas vantagens</p>
                      <p align='center' class="card-text m-1">Você terá direito a 15 entrevistas</p>
                      <div class="card-body bady-detalhes-compras" >
                          <button type="button" onclick="escolhendoPlano(5)" style="border-radius: 30px; background:#7AD634;color:#1565C0;width:200px;margin:auto" class=" btn-pill btn  btn-lg font-weight-bold">Detalhes</button>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                      <img style="width:60%;padding: 17px;margin-top: 74px;position: absolute;" src="{{url('/public/assets_gestor/img/').'/robo-m-esquerda.png'}}" />
                    </div>
                </div>
            </div>
            {{-- <div style="height: 400px; width: 100%;background: red"></div> --}}
        </div>
        
    </div>

        <img style="  margin-top: 320px" id="background" src="{{asset('public/assets_site/imgs/Grupodemascara9.png')}}" alt="">
        <div class="section position-relative" style="margin-top: 268px;" data-anchor="slide3">
            <div class="vantagens" style="margin-top: 631px">
                <div class="bonecoVantagens">
                    <h1 class="titulo" style="margin-bottom: 0.5em;color: white;">vantagens</h1>
                    <img style="width: 400px;" src="{{ asset('public/assets_site/imgs/Ativo2.png')}}" alt="">
                </div>  
                <div class="responsive" style="width:30%;z-index:5;margin-top: -15%;">
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                    <div class="carousel">
                        <img src="{{asset('public/assets_site/imgs/3.png')}}" alt="">
                        <p>coleta entrevistas ilimitadamente</p>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="section" data-anchor="slide4">
            <div class="whats" style="margin-top: 116px;">
                <div class="contato" >
                    <h1>entre em contato</h1> 
                    <h1>através do nosso</h1> 
                    <h1>whatsapp</h1>
                    <img src="{{asset('public/assets_site/imgs/Grupo227.png')}}" alt="">
                </div>
                <img id="novo" style="width: 28%;" src="{{asset('public/assets_site/imgs/Ativo3.png')}}" alt="">
            </div>

            <div class="formularioPrincipal" style="display: none;">
                <h1 class="titulo">contato</h1>
                <form action="">
                    <input class="caixaPequena" type="text" placeholder="Nome">
                    <input class="caixaPequena" type="text" placeholder="E-mail">
                    <input class="caixaPequena" type="text" placeholder="Telefone">
                    <input class="caixaPequena" type="text" placeholder="Empresa">
                    <input class="caixaGrande mensagem" type="text" placeholder="Mensagem">
                    <button>Enviar</button>
                </form>
            </div>
        </div>

        <div class="section" data-anchor="slide5">
            <footer>
                <div class="bolhas">
                    <img style="top: 0;left: 2%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-6.png')}}" alt="">
                    <img style="top: 2%;left: 35%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse42.png')}}" alt="">
                    <img style="top: 10%;left: 70%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse40.png')}}" alt="">
                    <img style="top: -6%;left: 60%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-9.png')}}" alt="">
                    <img style="top: 20%;left: 75%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-5.png')}}" alt="">
                    <img style="top: 10%;left: 78%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse43.png')}}" alt="">
                    <img style="top: 41%;left: -5%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse41.png')}}" alt="">
                    <img style="top: 50%;left: 25%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-6.png')}}" alt="">
                    <img style="top: 70%;left: 10%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-6.png')}}" alt="">
                    <img style="top: 70%;left: 64%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-6.png')}}" alt="">
                    <img style="top: 44%;left: 64%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse-3.png')}}" alt="">
                    <img style="top: 30%;left: 20%;position: absolute;" src="{{ asset('public/assets_site/imgs/Elipse47.png')}}" alt="">
                </div>
                <div class="info">
                    <img src="{{asset('public/assets_site/imgs/logoBranca.png')}}" alt="">
                    <p><span>Endereco:</span> Rua Ernesto de Paula, 1172 - SL 605, Boa Viagem, Recife-PE. CEP.51021-330</p>
                    <p><span>Fones:</span> 81 3325-0028 | 81 98500-0028</p>
                    <p><span>E-mail:</span> contato@dipestrategia.com.br</p>
                </div>
                <div class="adm" style="line-height: 0.5;">
                    <img src="{{asset('public/assets_site/imgs/')}}" alt="">
                    <p>Site desenvolvido pela <span>adm TECNOLOGIA</span></p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // new fullpage('#fullPage', {
        //     anchors:['slide1', 'slide2','slide3','slide4', 'slide5'],
        //     responsiveHeight: 330,
        //     responsiveWidth: 1100,
        // })
    </script>

    <script>
        $('.responsive').slick({
        dots: true,
        infinite: true,
        nav:false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                dots: true
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
        });
    </script>

    <script>
    function escolhendoPlano(idPlano){
    
        location.href=`{{env('APP_URL')}}plano-escolhido/${idPlano}`;
   
    }
    function login(idPlano){
    
        location.href=`{{env('APP_URL')}}login`;
   
    }
    </script>
</body>
</html>