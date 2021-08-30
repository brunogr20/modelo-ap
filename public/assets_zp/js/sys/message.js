$(function () {
 $.extend({
   /**
    * 
    * @param {*} msg A mensagem em si
    * @param {*} type pode ser "warning","success","error",
    * @param {*} timeout Tempo de exibi��o do alert
    * @param {*} position pode ser 'bottom-right',bottom-left,'bottom-center',top-right,top-left, top-center
    */
   alertMessage: function (type, msg,position,timeout) {
     alertify.set('notifier', 'position', position);
     switch(type){
       case "warning":
         alertify.warning("<b style='color:#000'>"+msg+"</b>",timeout);
       break;
       case "success":
         alertify.success("<b style='color:#fff'>"+msg+"</b>",timeout);
       break;
       case "error":
         alertify.error("<b style='color:#fff'>"+msg+"</b>",timeout);
       break;
       case "info":
         alertify.notify("<b>"+msg+"</b>","custom",timeout);
       break;
       case "welcome":
         alertify.notify(""+msg+"","welcome",timeout);
       break;
       default:
         alertify.message(msg);
     }
    }
   });
  });