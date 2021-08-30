<!DOCTYPE html>
<html lang="pt-br">
<head>
        @include('site.templates.template-chamada') 
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets_gestor/img/favicon.png') }}">
</head>
<body>
    <section class="comprar" id="informacoes">
        <div class="passo">
            <div class="logo">
                <img src="{{asset('public/assets_site/imgs/logoBranca.png')}}" alt="">
            </div>
            <div class="formulario" style="margin-top: 25%;margin-left: 40%;">
                @foreach($data['planos'] as $key  => $item)

                <h1 class="tituloPlano" style="font-size: 3rem">Plano <span>escolhido</span></h1>
                <p class="subtituloPlano" style="font-size: 6rem; margin: 0px 0 0 0;"><span style="font-size: 3rem;">{{$item['titulo']}}</span></p>
                <p class="precoPlano" style="font-size: 3.0rem;">
                @if($item['tipo']!='GRATUITO')    
                R$ {{number_format($item['valor']/$item['parcela'], 2, ',', '.')}}
                @if($item['tipo']!='GRATUITO' && $item['tipo']!= 'MENSAL')
                    x   {{$item['parcela']}}
                @endif
                </p>
                @endif

                <p class="textoPlano">{{$item['texto']}}</p>
                <div class="botoes"  style="margin-bottom: 1em;">
                    {{-- <button href="cadastro">Avançar</button> --}}
                    <button onclick = "avançar({{$item['id']}})">Avançar</button>
                    {{-- <button onclick = "Mudarestado(id)">Avançar</button> --}}
                </div>
                @endforeach

            </div>
        </div>
        <div class="boneco sumir">
            <div class="imagem">
                <img src="{{asset('public/assets_site/imgs/Ativo3.png')}}" alt="">
            </div>
        </div>
    </section>

    <script>
        function avançar($idPlano){
            // location.href.cadastro;
            location.href=`{{env('APP_URL')}}cadastro/${$idPlano}`;

            // open('cadastro'); //faz a requisicao dos dados, via post

        }
        var contador  = 1;   
            function Mudarestado(el) {
                console.log(el);
                var display = document.getElementById(el).style.display;    
                var idRelacionado = el
            
                if(contador ==1)
                {
                    
                    anterior  = el;
                }else{
                   
                    document.getElementById(anterior).style.display = 'none';
                    anterior = el;
                }
                
                document.getElementById(el).style.display = 'flex';
                contador++;
            }
    </script>