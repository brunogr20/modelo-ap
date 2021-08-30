@extends('zp.components.main')
@section('sectionContent')
    <script>
      objPage.grid.init={
           columns:[
           {title: "Perfil",data: "nome",name:""},
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:false,refresh:true,order:false,search:true,delete:false,status:false,multChecked:false,multStatus:false,multDelete:false,}
      }; 
        //Validar forlulário
         objPage.form.save.validate ={id:['required|Escolha um perfil da grid']}; 
         objPage.form.loaded.callback = ()=>{ 
            let data=objPage.dataForm;
            // $(".item-p0,.item-pA,.item-pB,.item-pC").removeClass("item-p0 item-pA item-pB item-pC");
            if(Object.keys(data).length>1){
              for(let d in data){
                objPage.form.save.validate[d]=['required'];
              };
              for(let type of ['submenu-','menu-']){
                let kName='';
                $(`[name^='${type}']`).each(function(){
                  let s=$(this);
                  if(kName!=s.attr('name')){
                  let aSplit=s.attr('name').split('-');
                  let check={0:0,C:0,B:0,A:0};
                    group = (aSplit[0]=='menu')?$(`[data-group-menu=${aSplit[1]}]`):$(`[data-group-submenu=${aSplit[1]}-${aSplit[2]}]`);
                    tGroup=group.length/4;
                    group.each(function(){
                      if(this.checked){
                      check[$(this).val()]++;
                      }
                    });
                    let isGroupEquals=false;
                    for(let i in check){
                      if(check[i]==tGroup){// verifica se todos os elementos estão marcados iguais;
                        isGroupEquals=true;
                        let iChecked =$(`[name=${s.attr('name')}][value=${i}]`);
                        iChecked.prop("checked",true);
                        iChecked.parents('.panel').removeClass("item-p0 item-pA item-pB item-pC").addClass("item-p"+i);
                        iChecked.parents(".item-accordion-link")
                        .removeClass("item-p0 item-pA item-pB item-pC")
                        .addClass("item-p"+i); 
                      }
                    }
                    if(!isGroupEquals){
                      $(`[name=${s.attr('name')}][value=0]`).parents('.panel').removeClass("item-p0 item-pA item-pB item-pC");
                    }
                  }
                  kName=s.attr('name');
               });
              }
            }else{
             $(`input[type=radio][value=0]`).prop("checked",true)
                 .parents(".item-accordion-link.item")
                 .removeClass("item-p0 item-pA item-pB item-pC")
                 .addClass("item-p0");
            } 
            $("[data-group-menu],[data-group-submenu]").trigger("change");
          }    
    </script>
    @include('zp.components.grid')
   
      {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}

          {!! HTML::initTab('geral',true); !!}
          <div class="box-info">
              <ul>
                  <li class="perm0"><b>0</b><br> Sem acesso</li>
                  <li class="permC"><b>C</b><br> Ler</li>
                  <li class="permB"><b>B</b><br> Ler, Editar, Criar</li>
                  <li class="permA"><b>A</b><br> Ler, Editar, Criar, Excluir</li>
              </ul>
          </div>
          {!! HTML::inputHidden(['name'=>'id']); !!}
          <div class="accordion" id="accordion_sessions" role="tablist" aria-multiselectable="true">
            @if(count($page['data']['menus_permissions']))
                  @foreach ($page['data']['menus_permissions'] as $kname => $dMenu)
                    @foreach ($dMenu as $menu)               
                      <div class="panel item-p0">
                        <a class="panel-heading collapsed" role="tab" id="heading{{ $menu['id'] }}" data-toggle="collapse" data-parent="#accordion_sessions" href="#sessao{{ $menu['id'] }}" aria-expanded="false" aria-controls="collapseOne">          
                            <h4 class="panel-title"> {{ $menu['nome'] }} </h4>
                        </a>
                        <div class="action-radio-horizontal parent">
                            @foreach (['0','C','B','A'] as $level)
                             <label>{{$level}}<input value='{{$level}}' name="menu-{{$menu['id']}}"  type="radio" {{(!$level)?'checked':''}}></label>
                            @endforeach
                        </div>
                        <div id="sessao{{ $menu['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $menu['id'] }}" aria-expanded="false" style="height: 0px;">
                          <div class="panel-body">                            
                              @foreach ($menu['secoes'] as $secao)
                                @if(!empty($secao['url']))
                                  <div class="item-accordion-link item item-p0">
                                      <a class='title' href="#">{{$secao['nome']}}</a>
                                      <div class="options">
                                        @foreach (['0','C','B','A'] as $level)
                                         <label>{{$level}}<input value='{{$level}}' data-group-menu='{{$menu['id']}}' name="perm-{{$menu['id'].'-'.$secao['id']}}" type="radio" {{(!$level)?'checked':''}}></label>
                                        @endforeach
                                      </div>
                                  </div>
                                @else
                                  {{-- Tem Sub Menu --}}
                                  {{-- SUB MENU --}}
                                  <div class="accordion item" id="comu-group{{ $menu['id'] }}" role="tablist" aria-multiselectable="true">
                                    @foreach ($secao as $submenu) 
                                        <div class="panel inner">
                                          <a class="panel-heading collapsed" role="tab" id="heading{{ $submenu['id'] }}" data-toggle="collapse" data-parent="#comu-group{{ $menu['id'] }}" href="#collapseComu"
                                            aria-expanded="false" aria-controls="collapseOne">          
                                            <p class='title'>{{$submenu['nome']}}</p>
                                          </a>
                                          <div class="action-radio-horizontal inner">
                                            @foreach (['0','C','B','A'] as $level)
                                             <label>{{$level}}<input value='{{$level}}' data-group-menu='{{$menu['id']}}' name="submenu-{{ $menu['id'].'-'.$submenu['id'] }}" type="radio" {{(!$level)?'checked':''}}></label>
                                            @endforeach                                           
                                          </div>
                                        </div>
                                        <div id="collapseComu" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $submenu['id'] }}" aria-expanded="false" style="height: 0px;">
                                          <div class="panel-body inner">                                        
                                            {{-- Sessoes do sub menu --}}
                                            @foreach ($submenu['secoes'] as $subs)            
                                              <div class="item-accordion-link item item-p0">
                                                <a class='title' href="#">{{$subs['nome']}}</a>
                                                <div class="options">
                                                  @foreach (['0','C','B','A'] as $level)
                                                    <label>{{$level}}<input value='{{$level}}' data-group-menu='{{$menu['id']}}' data-group-submenu='{{$menu['id'].'-'.$submenu['id']}}'  name="perm-{{$menu['id'].'-'.$submenu['id'].'-'.$subs['id'] }}" type="radio" {{(!$level)?'checked':''}}></label>
                                                  @endforeach
                                                </div>
                                              </div>
                                            @endforeach
                                        </div>
                                      </div>
                                    @endforeach
                                  </div>
                                @endif                         
                              @endforeach
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @endforeach       
            @endif
          </div> <!-- FIM ACCORDION PRINCIP -->
          <script>
           $("[name^='menu-'],[name^='submenu-']").on('click',function(){
             let s=$(this),group=[];
             let aSplit=s.attr('name').split('-'),val=s.val();
             if(aSplit[0]=='menu'){
              group=$(`[data-group-menu=${aSplit[1]}]`)
             }else if(aSplit[0]=='submenu'){
              group=$(`[data-group-submenu=${aSplit[1]}-${aSplit[2]}]`)
              $(`[name=menu-${aSplit[1]}]`).prop("checked",false).removeAttr("checked");
             }
             let check={0:0,C:0,B:0,A:0};
             tGroup=group.length/4;
             group.each(function(e){
              if($(this).val()==val){
               check[$(this).val()]++;
               $(this).prop("checked",true);
               $(this).parents('.panel, .panel-body.inner').removeClass("item-p0 item-pA item-pB item-pC")
               .addClass("item-p"+val);
               $(this).parents(".item-accordion-link")
                      .removeClass("item-p0 item-pA item-pB item-pC")
                      .addClass("item-p"+val);
              }
             });
             $(this).parents(".panel-body").find(".panel.inner").removeClass("item-p0 item-pA item-pB item-pC");
             $(`[name=${$(this).attr('name')}][value=0]`).parents('.panel').removeClass("item-p0 item-pA item-pB item-pC");
             for(let i in check){
              if(check[i]==tGroup){
                  $(`[name=submenu-${$(this).attr('data-group-submenu')}][value=${i}]`).prop("checked",true);
                  $(this).parents(".panel-body").find(".panel.inner").addClass("item-p"+i);
                  if(aSplit[0]=='menu'){
                    $(`[name=${$(this).attr('name')}][value=0]`).parents('.panel').addClass("item-p"+i);
                  }
              }
             }
            
           });
            $("[data-group-menu],[data-group-submenu]").on('change',function(){
              if($(this).prop("checked")){        
               $(this).parents(".item-accordion-link.item").removeClass("item-p0 item-pA item-pB item-pC").addClass("item-p"+$(this).val());
               ///
               let idGroupMenu=$(this).attr('data-group-menu'),idGroupSubMenu=$(this).attr('data-group-submenu');
               if(idGroupSubMenu!==undefined&&idGroupSubMenu!==false){  
                  let groupSub = $(`[data-group-submenu=${idGroupSubMenu}]`),
                  check={0:0,C:0,B:0,A:0};
                  tGroupSub=groupSub.length/4;
                  groupSub.each(function(){
                      if(this.checked){check[$(this).val()]++;}
                    });
                    let iChecked = $(`[name=submenu-${idGroupSubMenu}]:checked`);
                        iChecked.prop("checked",false);
                        iChecked.parents(".panel-body").find(".panel.inner").removeClass("item-p0 item-pA item-pB item-pC")
                  for(let i in check){
                    if(check[i]==tGroupSub){
                        let iChecked = $(`[name=submenu-${idGroupSubMenu}][value=${i}]`);
                        iChecked.prop("checked",true);
                        iChecked.parents(".panel-body").find(".panel.inner").addClass("item-p"+i);
                    }
                  }
               }
               let groupMenu = $(`[data-group-menu=${idGroupMenu}]`),check={0:0,C:0,B:0,A:0};
               tGroupMenu=groupMenu.length/4;
               groupMenu.each(function(){
                  if(this.checked){check[$(this).val()]++;}
               });
               let iChecked = $(`[name=menu-${idGroupMenu}]:checked`);
                   iChecked.prop("checked",false); 
                   iChecked.parents('.panel').removeClass("item-p0 item-pA item-pB item-pC")
               for(let i in check){
                 if(check[i]==tGroupMenu){
                  let iChecked = $(`[name=menu-${idGroupMenu}][value=${i}]`);
                     iChecked.prop("checked",true); 
                     iChecked.parents('.panel').removeClass("item-p0 item-pA item-pB item-pC").addClass("item-p"+i);
                 }
               }
              }
            });
           
          </script>

        {!! HTML::endTab(); !!}
                
        {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"]]);!!}

    {!!  HTML::endForm(); !!}



@endsection
