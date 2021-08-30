@inject('config', 'App\ZP\Config')
<!DOCTYPE html>
<html lang="en">
  <head>
    <script>

    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>ADM cp | Gerenciador de Conteúdos</title>
    <link rel="shortcut icon" href="{{asset('public/assets_zp/img/favicon.png')}}" type="image/x-icon">
     <script>
      let objPage = {url:JSON.parse(`<?= json_encode($page['route_data']) ?>`),
                     filesPath:`{{ asset("public/storage/".files_path()) }}`,
                     level:`<?= !empty($page['section_active']['level'])?$page['section_active']['level']:'' ?>`,
                    };       
    </script>
     {{-- Importa Todos os Scripts por sessão --}}
   
     @include("zp.components.imports-head")
     
 
   </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container pa">
        <div class="col-md-3 left_col h-100">
          <div class="left_col">
           {{-- <div class="navbar nav_title" style="border: 0;">
              <a title="adm Tecnologia" target="_blank" href="http://adm.com.br" class="site_title">
                {{-- <img width='35px' src="{{asset('public/assets_zp/img/adm.svg')}}">
                <img  src=""> --}
                <object style="" width='30px' data="{{asset('public/assets_zp/img/adm.svg')}}" type="image/svg+xml"></object>
                <span>  adm cp</span>
              </a>
              {{-- <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>adm cp</span></a> --}
            </div>--}}

            <!-- menu profile quick info -->
            
            <div class="section_prof"> <!-- clearfix-->
                <a title="{{$config->getClientName()}}" href='{{$page['route_data']['url_sys'].'/home'}}'>
                  <img src="{{$config->getClientImage()}}" alt="{{$config->getClientName()}}" class="img-circle profile_img">
                  <h2>{{$config->getClientName()}}</h2>
                </a>
            </div>
          
            <!-- /menu profile quick info -->
           
            {{-- Importando Menus --}}
            @include("zp.components.menu")
          
            <!-- top navigation -->
            <div class="top_nav">
              <div class="nav_menu">
                <nav class="flex-extremos">
                  <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                  </div>
                  <ul class="nav navbar-nav navbar-right flex-row-reverse">
                    <li class="">
                      <a data-user="logado" class="user-profile dropdown-toggle ef-link" data-toggle="dropdown" aria-expanded="false">
                          @if(!file_exists(public_path('storage/arqConteudo/arqSysUsuario/'.$page['user_logged']['imagem'])) || $page['user_logged']['imagem'] == "" || empty($page['user_logged']['imagem']))
                            <img  src="{{asset('public/assets_zp/img/user.png')}}" alt="">
                          @else
                            <img  src="{{url('public/storage/arqConteudo/arqSysUsuario/'.$page['user_logged']['imagem'])}}" alt="">
                          @endif 
                        <g>{{$page['user_logged']['nome']}}</g>
                        <span class=" fa fa-angle-down"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{$page['route_data']['url_sys'].'/meu-perfil'}}"><i class="fa fa-user pull-right"></i> Meu Perfil</a></li>
                        <li>
                          <a class="ef-link" 
                              style="
                              display: flex;
                              flex-direction: row-reverse;
                              justify-content: space-between;"
                          >
                          <div class="">
                            <i style="font-size: 20px;" onclick="$.toggleDarkMode()" id="change-mode-bottom-up" class="fas fa-toggle-on"></i>
                          </div>
                          Modo Dark</a>
                        </li>
                        <li><a class="ef-link" role="logout" ><i class="fa fas fa-sign-out-alt pull-right"></i> Sair</a></li>
                      </ul>
                    </li>
                    {{-- Anotações --}}
                    <li role="presentation" data-toggle='note' class="dropdown">
                      <a  class="dropdown-toggle info-number ef-link" aria-expanded="false">
                        {{-- <i class="fa fa-sticky-note"></i> --}}
                        <span class="fas fa-sticky-note gray" aria-hidden="true"></span>
                        
                        {{-- <span class="badge bg-green">1</span> --}}
                      </a>
                      <ul id="annotation_menu" class="dropdown-menu list-unstyled msg_list large" role="menu">
                        <li class='actions-annotation-area'>
                          <div>
                            <a class='btn-action' onclick="noteClick(this,'add-new')" data-annotation="add-new" title="Adicionar nova nota"  data-toggle="tooltip" data-placement="top"  href="#"><i class="glyphicon glyphicon-plus"></i></a>
                            @if(!empty($page['section_active']))
                              <a class='btn-action' title="Ver notas dessa seção" onclick="filterAnnotation(this,{{$page['section_active']['id']}})" role='filter'    ><i class="glyphicon glyphicon-filter"></i></a>
                            @else
                              {{-- <a class='btn-action' title="Ver todas notas"  data-toggle="tooltip" data-placement="top" ><i class="glyphicon glyphicon-list"></i></a> --}}
                            @endif 
                          </div>
                          <h3>Minhas anotações</h3>
                        </li>
                      <g id='annotations-list'></g>
                          <li date-list='my-annotations'>
                          </li> 
                      </ul>
                    </li>
                    {{-- Notificações --}}
                    <li role="presentation" class="dropdown">
                      <a  class="dropdown-toggle info-number ef-link" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fas fa-bell gray"></i>
                      </a>
                      <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                          <a>
                            <span class="image"></span>
                            <span class="message">
                              Nenhuma notificação
                            </span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul> <!-- ./navbar-nav navbar-right -->
                </nav> <!-- ./nav -->
              </div>
            </div>
          </div><!-- ./left_col -->

          <!-- page content -->
          <div class="right_col" role="main">    
            @yield('sectionContent')      
          </div>
        <!-- /page content -->
        {{-- RODAPÉ --}}
        {!! HTML::modal('sysModalTemaCustom',['title'=>'Temas'])!!}
        {{-- <p>Escolha as cores de sua preferência</p>
        <div class="x_panel">
                  <div class="x_content">
                    <button type="button" class="btn btn-round btn-default">Default</button>
                    <button type="button" class="btn btn-round btn-primary">Primary</button>
                    <button type="button" class="btn btn-round btn-success">Success</button>
                    <button type="button" class="btn btn-round btn-info">Info</button>
                    <button type="button" class="btn btn-round btn-warning">Warning</button>
                    <button type="button" class="btn btn-round btn-danger">Danger</button>
                  </div>
                </div> --}}
        {!! HTML::endModal()!!}
        <footer class="footer_fixed flex">
        <div class="clearfix"></div>
        <div class='rights'>
            <span style='text-align:center'> &copy Copyright 2019 - adm Tecnologia&reg - Todos os direitos reservados</span>
          </div>
          @if (count($page['last_acess']))
          <div class='last-access' title="Versão: {{$config->getVersion()}}">
            Último acesso: <a>{{$page['last_acess']['data']}} às {{$page['last_acess']['hora']}}</a>
          </div>
          @endif
          <div class="clearfix"></div>
        </footer>
        
      </div>
    </div>

    {{-- Importa Todos os Scripts por sessão --}}
    @include("zp.components.imports-footer")
  </body>
</html>
