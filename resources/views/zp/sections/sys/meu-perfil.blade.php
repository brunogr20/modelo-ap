@inject('config', 'App\ZP\Config')
@extends('zp.components.main')
@section('sectionContent')
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @if(count($page['section_active']))
                        <h2>Você está em
                            <small>
                                <i>{{$page['section_active']['menu']}}</i> >
                                @if(!empty($page['section_active']['submenu']))
                                    <i>{{$page['section_active']['submenu']}}</i> >
                                @endif
                                <b><i><a href='{{$page['section_active']['url']}}'>{{$page['section_active']['secao']}}</a></i></b>
                            </small>
                        </h2>
                        <div class="clearfix"></div>
                    @endif
                </div>
                <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_center">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <form name="form-img">
                                  
                                @if(!file_exists(public_path('storage/'.files_path().$page['user_logged']['imagem'])) || $page['user_logged']['imagem'] == "" || empty($page['user_logged']['imagem']))
                                  <div id="filter">
                                    <span>
                                      <label class="fa fa-camera ef-link">
                                        <input name="imagem" id="imagem" type="file" class='hide'>
                                      </label>
                                    </span>
                                    <small>Adicionar foto</small>
                                  </div>
                                  <img class="img-responsive avatar-view" id="img" src="{{asset('public/assets_zp/img/user.png')}}" alt="">
                                @else
                                  <div id="filter"><span><label class="fa fa-camera ef-link"><input name="imagem" id="imagem" type="file" class='hide'></label></span><small>Alterar foto</small>
                                    <span class="btn-delete-avatar"><i onclick="deleteImg()" class="fas fa-times"></i></span>
                                  </div>
                                  <img class="img-responsive avatar-view" id="img" src="{{url('public/storage/arqConteudo/arqSysUsuario/'.$page['user_logged']['imagem'])}}" >
                                  
                                @endif 
                                  
                                  
                                </form>
                                
                              </div>
                            <script>
                              var input = document.getElementById('imagem');
                              var preview = document.getElementById('img');
                              input.addEventListener('change', function(e) {
                                  showThumbnail(this.files);
                              });
                              function showThumbnail(files) {
                                  if (files && files[0]) {
                                  var reader = new FileReader();
                                  reader.onload = function (e) {
                                    preview.src = e.target.result;
                                  }
                                    reader.readAsDataURL(files[0]);
                                  }
                                    var formdata = new FormData($("form[name='form-img']")[0]);
                                    formdata.append("status","SIM");
                                    $.ajax({
                                      url: objPage.url.url_page+'/user_update',
                                      type: 'POST',
                                      dataType: 'JSON',
                                      data: formdata,
                                      processData: false,
                                      contentType: false,
                                      success: function (r) {
                                        console.log(r);
                                        if(r.status){
                                            $.alertMessage('success',r.msg);
                                            setTimeout(()=>window.location.reload(),1000);
                                          } else {
                                            $.alertMessage('error', r.msg);
                                        }                                      },
                                      error:function(r){
                                        $.ajaxErros(r.responseJSON);
                                      },
                                    }); 
                              }
                              function deleteImg(){
                                alertify.confirm(
                                  "Confirme!", //Title
                                  "Você realmente quer remover sua foto de perfil?", //Message
                                  function(){
                                    $.ajax({
                                      url: objPage.url.url_page+'/user_delete_image',
                                      type: 'POST',
                                      dataType: 'JSON',
                                      data: {name:"imagem"},
                                      success: function (r) {
                                        if (r.status) {
                                           $.alertMessage('success', r.msg);
                                           setTimeout(()=>window.location.reload(),1000);
                                           $("#img").attr("src","{{asset('public/assets_zp/img/user.png')}}").target.onload;
                                           
                                        }else{
                                          console.log(r.type);
                                          $.alertMessage('error', r.msg);
                                        }
                                      },
                                      error:function(r){
                                        $.ajaxErros(r.responseJSON);
                                      },
                                    });

                                  },
                                  function(){console.log("cancel")}
                                );
                              }
                              

                            </script>
                        </div>
                        <h3>{{$page['user_logged']['nome']}}</h3>
                        <ul class="list-unstyled user_data">
                          <li>
                           <i class="fa fa-map-marker user-profile-icon"></i>
                            {{$config->getClientName()}}
                          </li>
                          <li>
                           <i class="fa fa-briefcase user-profile-icon"></i> 
                           {{$page['perfil']['nome']}}
                          </li>
                          <li class="m-top-xs">
                           <i class="fa fa-envelope-o"></i>
                             <a href="mailto:{{$page['user_logged']['email']}}" >{{$page['user_logged']['email']}}</a>
                          </li>
                        </ul>
                        {{--<a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>--}}
                        <br>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                     <div class="profile_title">
                       <div class="col-md-8" style="border: 1px solid #e6e6e6;padding: 30px 30px;">
                         <h2><i class="fa fa-user"></i> Meus dados</h2>
                         <form name="form-perfil">
                          {!! HTML::initTab('geral',true); !!}
                            <div class="form-group"></div>
                            {!! HTML::input(["readonly"=>"true", "value"=>$page['user_logged']['nome'],"name"=>"titulo", "label"=>"Nome"]); !!}
                            {!! HTML::input(["class"=>"no-back form-control","name"=>"senha",'type'=>'password', "label"=>"Senha*","placeholder"=>"******"]); !!}
                            {!! HTML::input(["name"=>"conf_senha", "label"=>"Confirmar senha*",'type'=>'password',"placeholder"=>"******"]); !!}
                          {!! HTML::endTab() !!}
                          <div class="ln_solid"></div>
                          <div class="form-group">
                           <div class=" btn-form-group col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success ">Salvar</button>
                           </div>
                          </div>
                          </form>
                       </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
   <script>
      $(function(){
          $('form[name=form-perfil]').submit(function(){
            let pass=$('input[name=senha]').val(),
                pesPass=$('input[name=conf_senha]').val(); 
                if(!pass){
                 $.alertMessage('info','O campo senha é obrigatório.');
                 return false;
                } 
                if(pass.length<6){
                 $.alertMessage('info','O campo senha  deve ter no mínino 6 caracteres.');
                 return false;
                } 
                if(pass!=pesPass){
                 $.alertMessage('info','O campo confirmar senha deve ser igual ao da senha.');
                 return false;
                }
                $.ajax({
                  url: objPage.url.url_page+'/user_update',
                  type: 'POST',
                  dataType: 'JSON',
                  data: {pass},
                  success: function (r) {
                    if (r.status) {
                      $('input[name=senha]').val('')
                      $('input[name=conf_senha]').val(''); 
                      $.alertMessage('success',r.msg);
                    } else {
                      $.alertMessage('error', r.msg);
                    }
                    if(r.log){console.log(r.log)}
                  },
                  error:function(r){
                    $.ajaxErros(r.responseJSON);
                  },
                }); 
            return false;
        });
      });
  </script>
@endsection
