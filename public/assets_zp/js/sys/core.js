$(function () {
  $.extend({
    toggleDarkMode: function(){
      let tema = (!objPage.isDark)?'DARK':'WHITE';
      $.ajax({
        url: objPage.url.url_sys+'/user-custom-tema',
        type: 'POST',
        dataType: 'JSON',
        data: {tema:tema},
        success: function (r) {
          if(r.status){
            objPage.isDark = (tema=='DARK')?true:false;
            if(objPage.url.section_url=='home'){
              updateColorChartHome();
            }
            if(objPage.isDark){
              let url = objPage.url.url_context+"/public/zp/css/dark.css";
              let lnk = document.createElement('link')
              lnk.setAttribute('type', "text/css" );
              lnk.setAttribute('rel', "stylesheet" );
              lnk.setAttribute('data-css', "tema" );
              lnk.setAttribute('href', url );
              document.getElementsByTagName("head").item(0).appendChild(lnk);
              $.setIconsTema(true);
            }else{
              document.getElementsByTagName("head").item(0).querySelector('link[data-css=tema]').remove();
              $.setIconsTema(false);     
            }
            location.reload();
          }else{
            $.alertMessage('error', r.msg);
          }
          if(r.log){console.log(r.log)}
        },
        error:function(r){
         $.ajaxErros(r.responseJSON);
        },
      });
    },
    setIconsTema:function(isDark){
       if(isDark){
        $("#change-mode-bottom").removeClass("fa-sun").addClass("fa-moon");
        $("#change-mode-bottom-up").removeClass("fa-toggle-off").addClass("fa-toggle-on");
       }else{
        $("#change-mode-bottom").removeClass("fa-moon").addClass("fa-sun");
        $("#change-mode-bottom-up").removeClass("fa-toggle-on").addClass("fa-toggle-off");
       }
    },
    ajaxErros(d){
      if(d.errors!== undefined){
        let errors=d.errors;
       for(let e in errors){
         $.alertMessage('error', errors[e]);
       }
      }else{
        $.alertMessage('error', 'Ocorreu uma falha nessa acão, tente novamente mais tarde!');
      }
    },
    toggleSwitchTheme: function (onload=true){
      if(objPage.isDark){
       let pai = $("#swTheme").prop("checked",true).click().click().parent();
       pai.attr("data-original-title","Habilitado");
      }
      if(!onload && !objPage.isDark){
        let pai = $("#swTheme").prop("checked",false).attr("checked",false).click().click().parent();
        pai.attr("data-original-title","Desabilitado");
      }

     }
  });
  if(typeof objPage !='undefined'){
    //$.toggleDarkMode();
    if(objPage.isDark){
    $.setIconsTema(true); 
    }else{
      $.setIconsTema(false);
    }
  }
  
  $('body').click(function (event) {
    if(event.target.id!='annotation_menu'&&event.target.className!='note-act-btn'&&$('#annotation_menu').css('display')!=='none'){
      $('#annotation_menu').hide();
    }
  });
 
  $('[role=logout]').click(function(){
    window.location.href = objPage.url.url_sys+'/logout'
  });
  
  /**Evitando o dropDown fechar quando clicar dentro dele. */
  $('.dropdown-menu').click(function(e) {
    e.stopPropagation();
  });

  $("[data-toggle='note']").click(function(){
    let s=$(this), boxAnnotation=$("#annotation_menu");
    if(boxAnnotation.is(":visible")){
    boxAnnotation.hide();
    }else{
      loadNote('all');
   }
  });


});
































