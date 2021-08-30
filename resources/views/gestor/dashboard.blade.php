<?php
$perguntas = 1;
?>
<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    @include('gestor.templates.chamadas-top')
    <script src="{{asset('public/vendor/amcharts4/core.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/charts.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/dataviz.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/animated.js')}}"></script>
    <title>Dashboard - op Aqui</title>
    <meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
  </head>
  <body class="h-100">

    <div >

    @include('gestor.templates.header')

        <main style="margin:auto" class="main-content p-0 ">
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <!-- <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Blog Overview</h3>
              </div>
            </div> -->
            <!-- End Page Header -->
            @include('gestor.templates.menu')
            <hr style="margin-top:0px"/>

            <div class="row">
            
            </div>
          
        </main>
        @include('gestor.templates.rodape')
    </div>
    @include('gestor.templates.chamadas-down')

  </body>
</html>