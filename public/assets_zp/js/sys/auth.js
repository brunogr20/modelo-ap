$(function () {
  $('form[name=formData').submit(function(e){
    e.preventDefault();
    let url =$(this).attr('action')+'/auth',
    login = $(this).find('input[name=login]').val(),
    password = $(this).find('input[name=password]').val(),
    remember = $(this).find('input[name=remember]:checked').val()||false,
    bt=$(this).find('button[type=submit]');
    if(!login){
      $.alertMessage('warning','O campo e-mail é obrigatório.');
      return false;
    }
    if(!password){
      $.alertMessage('warning','O campo senha é obrigatório.');
      return false;
    }
    if(password.length<6){
      $.alertMessage('warning','O campo senha  deve ter no mínino 6 caracteres.');
      return false;
    }
    bt.attr('disabled',true);
      $.ajax({
      url: url,
      type: 'POST',
      dataType: 'JSON',
      data: {login,password,remember},
      success: function (r) {
        if(r.status){
          window.location.href=r.url; 
        }else{
          bt.html('Entrar');
          bt.attr('disabled',false);
          $.alertMessage('error', r.msg);
        }
        if(r.log){console.log(r.log)}
      },
      error:function(r){
        bt.attr('disabled',false);
        $.ajaxErros(r.responseJSON);
      },
    });
  });
  $('form[name=formDataForgotPass').submit(function(e){
    e.preventDefault();
    let url =$(this).attr('action')+'/forgot-pass',
    email = $(this).find('input[name=email]').val(),
    bt=$(this).find('button[type=submit]');
    if(!email){
      $.alertMessage('warning','O campo e-mail é obrigatório.');
      return false;
    }
    if(!(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(email))){
      $.alertMessage('warning','O digite um endereço de e-mail válido.');
      return false;
    }
    tmp=2;
    bt.html('.');
    let refreshText =()=>{
      if(tmp==1){
        bt.html('.');
      }else if(tmp==2){
        bt.html('..');
      }else if(tmp==3){
        bt.html('...');
      }else{
        bt.html('....');
        tmp=0
      }
      tmp++;
    }
    bt.attr('disabled',true);
    setInterval(refreshText,400);
    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'JSON',
      data: {email},
      success: function (r) {
        clearInterval(refreshText);
        bt.attr('disabled',false);
        bt.html('Enviar');
        if (r.status) {
         $('input[name=email]').val('');
         bt.attr('disabled',false);
         $('.reset_pass').click();
         $.alertMessage('success', r.msg,'top-center',9);
        }else{
          bt.html('Enviar');
          $.alertMessage('error', r.msg);
        }
        if(r.log){console.log(r.log)}
      },
      error:function(r){
        bt.html('Enviar');
        bt.attr('disabled',false);
        clearInterval(refreshText);
        $.ajaxErros(r.responseJSON);
    },
    });
    return false;
  });
  $('form[name=formDataNewPass').submit(function(e){
    e.preventDefault();
    let url =$(this).attr('action')+'/new-pass',
    password = $(this).find('input[name=password]').val(),
    repPassword = $(this).find('input[name=rep-password]').val(),
    token = $(this).find('input[name=token]').val(),
    bt=$(this).find('button[type=submit]');
    if(!password){
      $.alertMessage('warning','O campo senha é obrigatório.');
      return false;
    }
    if(password.length<6){
      $.alertMessage('warning','O campo senha  deve ter no mínino 6 caracteres.');
      return false;
    }
    if(!repPassword){
      $.alertMessage('warning','O campo confirmar senha é obrigatório.');
      return false;
    }
    if(password!=repPassword){
      $.alertMessage('warning','O campo confirmar senha deve ser igual ao da senha.');
      return false;
    }
    bt.attr('disabled',false);
    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'JSON',
      data: {password,token},
      success: function (r) {
        if (r.status) {
        window.location.href=r.url; 
        } else {
          bt.attr('disabled',false);
          $.alertMessage('error', r.msg);
        }
        if(r.log){console.log(r.log)}
      },
      error:function(r){
        bt.attr('disabled',false);
        $.ajaxErros(r.responseJSON);
    },
    });
    return false;
  });

  $('.reset_pass').click(function(){
   let role=$(this).attr('role'),
      login=$('[data-role=login]'),
      forgotPass=$('[data-role=forgot-pass]');
   if(role=='forgot-pass'){
    login.hide();
    forgotPass.slideDown("slow");
  }else if(role=='login'){
    login.slideDown("slow");
    forgotPass.hide();
   }
  });
});