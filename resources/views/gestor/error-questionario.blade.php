<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    <title>Infor - op Aqui</title>
    <meta name="description" content="">
    <link rel="shortcut icon" href="{{ asset('public/assets_gestor/img/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets_gestor/boot/css/bootstrap.css') }}" crossorigin="anonymous">
  </head>
  <body class="h-100">

    <main style="margin:auto;max-width:1000px" class="main-content p-0 ">
      <!-- / .main-navbar -->
      <div class="main-content-container mt-5 px-5">
        <div class="jumbotron">
          @if($data['status']=='EntrevistaRealizada')
            <h1 class="display-4">Entrevista realizada!</h1>
            <p class="lead">Obrigado por ter participado da nossa pesquisa.</p>
          @elseif($data['status']=='EntrevistaNaoVinculada')
          <h1 class="display-4">Pesquisa não vinculada!</h1>
          <p class="lead">Você não está vinculado a essa pesquisa.</p>
          @elseif($data['status']=='limiteAtingido')
          <h1 class="display-4">Coleta bloqueada!</h1>
          <p class="lead">Essa pesquisa já atingiu o total de entrevistas esperadas.</p>
          @elseif($data['status']=='planoInativo')
          <h1 class="display-4">Expirado</h1>
          <p class="lead">Não foi possível acessar essa pesquisa.</p>
          @else
          <h1 class="display-4">Pesquisa não encontrada!</h1>
          <p class="lead">Não foi possível encontrar essa pesquisa.</p>
          @endif
            <hr class="my-4">
            <p>Para mais informações acesse o nosso site</p>
          <p class="lead">
            <a class="btn btn-primary btn-lg "  href="https://appop.com" role="button">Ir para o site</a>
          </p>
        </div>
      </div>
    </main>

  </body>
</html>