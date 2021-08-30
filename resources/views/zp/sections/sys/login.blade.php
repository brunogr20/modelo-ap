<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>adm cp</title>
   @include("zp.components.imports-head")
  </head>
  <body>
    <div class="form-login-container">
        <div class="half-screen">
            <div class="v-middle"> 
            @if($page['type']=='login')
                <div class="form-login" data-role='login'>
                   <h1 class='title blue'>LOGIN</h1>
                   <form class='' action="{{$page['url']}}" name="formData" >
                     <p>Por favor insira as informações da sua conta:</p>
                     <div class='form-group'>
                       <input type="text" name="login" class="form-control login" placeholder="E-mail"  />
                     </div>
                     <div class='form-group'>
                       <input type="password" name="password" class="form-control login" placeholder="Senha"  />
                     </div>
                     <div class='flex'>
                       <div class="form-control no-border">
                         <label>
                           <input class='fix' name='remember' value='true' type="checkbox">
                           Lembrar de mim
                         </label>
                       </div>
                       <div class="form-control no-border">
                         <label for="">
                           <a class="reset_pass ef-link" role="forgot-pass" >Esqueceu a senha?</a>
                         </label>
                       </div>
                     </div>
                     <div>
                       <button type='submit' class="btn btn-blue submit" >Entrar</button>               
                     </div>
                   </form>
                </div>
                <div class="form-login" style="display:none" data-role="forgot-pass">
                   <h1 class='title blue'>Recuperar senha</h1>
                   <form class='' action="{{$page['url']}}" name="formDataForgotPass" >
                     <p>Digite o e-mail que você cadastrou no sistema:</p>
                     <div class='form-group'>
                       <input type="text" name="email" class="form-control login" placeholder="E-mail"  />
                     </div>
                     <div class='flex'>
                       <div class="form-control no-border">
                         {{-- <label>
                           <input class='fix' name='remember' value='true' type="checkbox">
                           Lembrar de mim
                         </label> --}}
                       </div>
                       <div class="form-control no-border">
                         <label for="">
                           <a class="reset_pass ef-link" role="login" >Voltar ao login</a>
                         </label>
                       </div>
                     </div>
                     <div>
                       <button type='submit' class="btn btn-blue submit" >Enviar</button>               
                     </div>
                   </form>
                </div>
             </div>
             @elseif($page['type']=='new-password')
              <div class="form-login" data-role='new-password'>
                   <h1 class='title blue'>Alterar senha</h1>
                   <form class='' action="{{$page['url']}}" name="formDataNewPass" >
                     <p>Por favor preencha o formulário abaixo:</p>
                       <input type="hidden" name="token" value="{{$page['token']}}"  />
                     <div class='form-group'>
                       <input type="password" name="password" class="form-control login" placeholder="Senha"  />
                     </div>
                     <div class='form-group'>
                       <input type="password" name="rep-password" class="form-control login" placeholder="Confirmar senha"  />
                     </div>
                     <div>
                       <button type='submit' class="btn btn-blue submit" >Entrar</button>               
                     </div>
                   </form>
                </div>
             @endif
        </div>
        <div class="half-screen">
          <div class="col-md-6 bg-sky v-middle">
              <div class="presentation-login">
                  <div class="logo-login">
                  <img src="{{ asset('public/assets_zp/img/logo.svg') }}" alt="">
                  </div>
                  <h1 class='title green'>Bem-vindo ao adm cp</h1>
                  <h4 class='info white'>Gerenciador de conteúdo da adm Tecnologia</h4>
              </div>
              
            </div>
        </div>
        
    </div>
      
    <script src="{{asset('public/assets_zp/js/sys/auth.js')}}"></script>
  </body>
</html>
