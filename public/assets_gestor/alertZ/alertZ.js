$(function(){
 $.extend({
  alertMessage:function(data={type:'',msg:''}){
   var style = "position:fixed;z-index:99999;margin-left:40%";
   var elemento=$(`<div aling="center" style="${style}">`,{id:'mensagemAlerta',html:'<button type="button" class="close">&times;</button>'}).prependTo("body");
   elemento.prepend('<strong></strong><span></span> ');
   //  var elemento=$('#mensagemAlerta');
   var time,b,classe;
   if(data.type=='success'){
    classe="center alert alert-success alert-dismissable";
    b='Sucesso! ';
    time=30000;
   }else if(data.type=='infor'){
    classe="alert alert-info alert-dismissable";
    b='Informação! ';
    time=3050;
   }else if(data.type=='atencao'){
    classe="alert alert-warning alert-dismissable";
    b='Atenção! ';
    time=3050;
   }else if(data.type=='erro'){
    classe="alert alert-danger alert-dismissable";
    b='Erro! ';
   }
   elemento.addClass(classe).show()
           .animate({marginTop:"13%"},100)
           .children('strong').text(b);
   elemento.children('span').text(data.msg);
   console.log(elemento)
   if(time){
    setTimeout(function(){
     elemento.hide();
    },time);
   }
   elemento.children('button').click(function(){
    setTimeout(function(){
     elemento.hide();
    },200);
   });
  }
 });
});
