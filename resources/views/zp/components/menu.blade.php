
           
           <!-- 
             TRECHO BEM IDENTADO FAVOR MANTER ASSIM :) 
            "Perder tempo em aprender coisas que não interessam, priva-nos de descobrir coisas interessantes."
                                                                                  Carlos Drummond de Andrade.
           
            sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              @if(count($page['menus']))
                @foreach ($page['menus'] as $kName => $dMenu)
                  <div class="menu_section">
                    <h3>{{$kName}}</h3>
                    <ul class="nav side-menu">
                      @foreach ($dMenu as $menu)
                        @if (count($menu['secoes']))
                          <li>
                            <a><i class="{{$menu['icone']}}"></i> {{$menu['nome']}} <span class="fas fa-chevron-down pull-right"></span></a>
                            <ul class="nav child_menu">
                              @foreach ($menu['secoes'] as $sub)
                                @if(!empty($sub['url']))
                                  <li><a href="{{$sub['url']}}">{{$sub['nome']}}</a></li>
                                @else
                                  @foreach ($sub as $submenu) 
                                    @if(!empty($submenu['secoes']))
                                      <li>
                                        <a>{{$submenu['nome']}}<span class="fas fa-chevron-down pull-right"></span></a>
                                        <ul class="nav child_submenu fix-top">
                                        @foreach ($submenu['secoes'] as $secao) 
                                          <li class="sub_menu"><a href="{{$secao['url']}}">{{$secao['nome']}}</a></li>
                                        @endforeach
                                        </ul>
                                      </li> 
                                    @endif
                                  @endforeach
                                @endif
                              @endforeach
                            </ul>
                          </li>     
                        @endif             
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              @endif
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" href="{{$page['route_data']['url_sys'].'/meu-perfil'}}" data-placement="top" title="Meu Perfil">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" onclick="$.toggleDarkMode()" title="Alternar modo">
                <span id="change-mode-bottom"  class="fas fa-sun" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" href="{{$page['route_data']['url_sys'].'/home'}}" data-placement="top" title="Ir para home">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Sair"  role="logout" >
                <span class="fas fa-sign-out-alt" style="font-size: 21px;" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <script>

        
        $(document).ready(function(){
          $(".main_menu_side").height($(".container.body").height());
        });
        </script>


































