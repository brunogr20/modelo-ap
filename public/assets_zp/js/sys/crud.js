var gridTable = null;
$(function () {
  $.fn.extend({
    requireDados: function (id) {
      let seletor = $(this);
      $.ajax({
        url: objPage.url.url_page+'/load_form',
        type: 'POST',
        dataType: 'JSON',
        data: {id},
        success: function (r) {
          if (r.status) {
            objPage.dataForm = r.data;
            $.anchorForm();
            $('[data-action-title=form-action]').text('Edição');
            $(seletor).formDataLoad(r.data);
            objPage.form.loaded.callback(r);
          } else {
            $.alertMessage('error', r.msg);
          }
          if(r.log){console.log(r.log)}
        },
        error:function(r){
         $.ajaxErros(r.responseJSON);
        },
      });
    },
    formDataLoad: function (data) {
      var seletorForm = `form[name=${$(this).attr('name')}] `;
      $(this).clearForm();
      if(data.status !== ""){
       $.toggleSwitchStatus(data.status);
      }
        for(let key in data){
        let val = data[key];
        let input = $(`${seletorForm} input[name=${key}]`);
        
        if(input.attr('type') == 'radio') {
          if(!val) continue;
          for(let check of input) {
            if (check.value == val) {
              check.checked = true;
              $(check).parent().addClass("checked");
             }
          };
        } else if (input.attr('type') == 'checkbox') {
          if(!val) continue;
          input.parent().click();
        }else if (input.attr('type') == 'file') {
          if(val!== '' && val !== undefined && val !== null){
            input.attr('data-required-name',val);
            $.toggleOpBtFile(key,true)
            $.toggleBtsFile(key,true);
            $.loadFile(input.attr('data-type'),key,val);  
          }
        } else if (input.attr('type')=='text'&&input.attr('data-type')=='datetime') {
         let format= input.attr('data-format');
         input.val(moment(val).format(format))
        }else{
          $(seletorForm + 'select[name=' + key + ']').val(val);
          $(seletorForm + 'textarea[name=' + key + ']').val(val);
         input.val(val);
        }
      }
    },
    clearForm: function () {
      objPage.form.clear.init();
      var seletorForm = 'form[name=' + $(this).attr('name') + '] ';
      $(seletorForm + ' :input').not(`${seletorForm} :input[type=radio],${seletorForm} :input[type=checkbox]`).val('');
      $(seletorForm + ' :input[type=checkbox],' + seletorForm + ' :input[type=radio]').prop("checked", false).parent().removeClass("checked");
      $(seletorForm + ' :input[type=file]').each(function(_,e){
        $(e).clearFile();
        $.toggleBtsFile($(e).attr('name'),false);
        $(e).attr('data-required-name','');
        $.filesDefault($(e).attr('data-type'),$(e).attr('name'));
      });
      $.toggleSwitchStatus('SIM');
      $(seletorForm+' input[name=status]').prop("checked", true);
      objPage.form.clear.callback();
    },
    clearFile:function(){
      $(this).val();
      $(this).attr('data-required-name','');
      $(this).removeAttr("type");
      $(this).attr('type','file');
    },
    saveForm:function (valid){
      var seletor = $(this);
      if (!seletor.formValidation(valid)){
        return false;
      }
      /**Criando um formData para envio */
      data=seletor.createFormData();
      let URI=(seletor.find('input[name=id]').val()) ? 'update' : 'create';
      $.ajax({
        url: objPage.url.url_page+'/'+URI,
        type: 'POST',
        dataType: 'JSON',
        data: data,
        processData: false,
        contentType: false,
        beforeSend:function(){
          $.infoLoad('show');
        },
        success: function(r){
          if (r.status) {
            $.infoLoad('success');
            objPage.form[URI].callback(r);
            //$.alertMessage('success', r.msg);
            seletor.clearForm();
            gridTable.ajax.reload();
          }else{
           if(r.msg){
             $.infoLoad('error',{txtProcessing:"Processando...", txtConcluded:"Concluído!",txtError:r.msg});
           }else{
             $.infoLoad('error');
           }
            //$.alertMessage('error', r.msg);
          }
          if(r.log){console.log(r.log)}
        },
        error:function(r){
          $.infoLoad('error');
          //$.ajaxErros(r.responseJSON);
        },
      });
    },
    createFormData:function(){
      let s =$(this),fData =  new FormData(s[0]);
        $(this).find('[type=checkbox]').each(function (_, c) {
          if(!c.checked)
          if(c.name!==undefined){fData.set(c.name,'null');}
        });
        $(this).find('[data-type=datetime]').each(function (_, c) {
          let d=$(c).val(),f=$(c).attr('data-format');
          d=d.replace(/\//g, '-');
          if(d.length>=10){
            let short1 = d.substr(0, 10);
            let short2 = d.replace(short1,'');
            short1 = moment(short1,'DD-MM-YYYY').format('YYYY-MM-DD');
            d=short1+short2;
            fData.set(c.name,d);
          }else if(f=='MM/YYYY'){
            fData.set(c.name,moment(d,'MM-YYYY').format('YYYY-MM'));
          }
        });
      return fData;
    },
    updateStatus: function (status,cod) {
      if(!cod && !objPage.grid.idsGridSelected.length){
        $.alertMessage('erro', "Falha na ação!");
        return false;
      }
      let data = {status}, ids = ((cod)?[cod]:objPage.grid.idsGridSelected), self=this,
         rgStutus = {
          'SIM':{title:'Inativar item',role:"ativo",rClass:'fa-times-circle',addClass:'fa-check',checked:false},
          'NÃO':{title:'Ativar item',role:"inativo",rClass:'fa-check',addClass:'fa-times-circle',checked:false},
         }
      data['ids'] =  ids;
      $.ajax({
        url: objPage.url.url_page+'/update_status',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        success:function(r){
          if(r.status){
            if(['SIM','NÃO'].includes(status)){
              const idsGrid = objPage.grid.dsGridSelected;
              if($(self).hasClass('td-status')&&idsGrid.includes(cod)){
                rgStutus[status].checked =true;
              }else{
                objPage.grid.idsGridSelected =[];
              }
              for(let id of ids){
                let eTr = $(`tr[id=${id}]`);
                if(eTr){
                  eTr.find('.btn-status').attr('title', rgStutus[status].title).attr('role', rgStutus[status].role)
                  .find('i').removeClass(rgStutus[status].rClass).addClass(rgStutus[status].addClass);
                  eTr.find('.selecionar input').attr("checked",rgStutus[status].checked);
                }
              }
            }
            toggleDefaultBtns();
            $.alertMessage('success', r.msg);
           }else{
            $.alertMessage('error', r.msg);
            toggleDefaultBtns();
          }
        },
      });
    },
    delete:function(cod=''){
      if(!cod && !objPage.grid.idsGridSelected.length){
        $.alertMessage('erro', "Falha na ação!");
        return false;
      }
      let data = {},alertMsg='',self=this;
      if(cod){
        alertMsg = "Deseja deletar esse resgistro?";
        data['ids'] = [cod];
      }else{
        data['ids'] = objPage.grid.idsGridSelected;
        alertMsg = "Deseja deletar "+((data['ids'].length==1)?'o registros selecinado?':`os ${data.ids.length} registros selecinados?`);
      }
      alertify.confirm(
        "Confirme!", //Title
        alertMsg, //Message
        function(){
          $.ajax({
            url: objPage.url.url_page+'/delete',
            type: 'POST',
            dataType: 'JSON',
            data: data,
            success:function (r) {
              if(r.status){
                if(!$(self).hasClass('btn-deletar')){
                  objPage.grid.idsGridSelected =[];
                }  
                for(let id of data['ids']){
                  let eTr = $(`tr[id=${id}]`);
                  if(eTr){
                    gridTable.row(eTr).remove().draw();
                  }
                }
                $.alertMessage('success', r.msg);
                toggleDefaultBtns();
              }else{
                $.alertMessage('error', r.msg);
                toggleDefaultBtns();
              }
            },
            error:function(r){
              $.ajaxErros(r.responseJSON);
            },
          });
        },
        function(){console.log("cancel")
      });
    },
    reorderGrid: function () {
      let orderList =[];
      $(this).find('tr').each(function(){
        ($(this).attr('id') != undefined)? orderList.push($(this).attr('id')):"";
      });
      let displayStart = gridTable.context[0]._iDisplayStart+1;
      $.ajax({
        url: objPage.url.url_page+'/reorder_grid',
        type: 'POST',
        dataType: 'JSON',
        data: {displayStart:displayStart, orderList:orderList},
        success:function (r){
          if (r.status) {
            $.alertMessage('success', r.msg);
          } else {
            $.alertMessage('error', r.msg);
          }
        },
        error:function(r){
          $.ajaxErros(r.responseJSON);
        },
      });
    },
    selectFile: function(){
       let seletor = $(this);
      let inputName=seletor.attr('data-file-edit');
      let objFile = $('#file-'+inputName); 
      let inputType = objFile.attr('data-type');
      objFile.click().unbind(); //Limpando evento anterior
      objFile.on("change",function(ev){
        let fileAccept = objFile.attr('accept').replace(/(\.)?(\s)?/g,'').split(','),
        fileSizeParam = (objFile.attr('data-size'));
        file = ev.target.files[0];
        let mType = mineTypes(file.type)
        if(!mType){
          $.alertMessage('error', "Não foi possível reconhecer esse arquivo.");;
          objFile.clearFile();
          return false; 
        }
        let fileExt = file.type.split("/")[1];
        //obs: melhoras a verificação
       /* if(!mType.includes(fileExt)){
           $.alertMessage('error', "O tipo do arquivo não é permitido ("+(mType[mType])+")");;
           objFile.clearFile();
          return false;
        }*/
        console.log(fileExt)
        if(file.size>(fileSizeParam*1024)){
          let mds = ['Bytes','KB','MB','GB','TB'];
          let md = parseInt(Math.floor(Math.log(file.size) / Math.log(1024)));
          let strSize = (file.size/Math.pow(1024,md)).toFixed(1)+mds[md];
          objFile.clearFile();
          $.alertMessage('error',"Seu arquivo é muito grande ("+strSize+")!");
          return false;
        }
        //Setando o nome abreviado da imagem no box.
        $(`[file-title=${inputName}]`).html((file.name.length>10)? file.name.substring(0,10)+"(...)."+fileExt:file.name);
        //Alterando imagem a ser exibida na tag img,
        let reader = new FileReader();
        reader.onload = function(e){
          $.toggleBtsFile(inputName,true);
          $.toggleOpBtFile(inputName,false);
          objFile.attr('data-required-name',file.name);
          if(inputType=='file'){
            $(`[data-file-tag=${inputName}]`).attr('src', $.loadImgFile(mType));
          }else{
          $(`[data-lightbox=${inputName}]`).find('a').attr('href', e.target.result);
          $(`[data-file-tag=${inputName}]`).attr('src', e.target.result);
          }
        }
        reader.readAsDataURL(file);
          
      });
    },
    
    lightboxImg:function(){
      let name = $(this).attr("data-file-view");
      if($('#file-'+name).attr('data-type')=='image'){
        $(`[data-lightbox=${name}]`).find('a').click();
      }
    },
    deleteFile:function(){
      let name = $(this).attr("data-file-delete"),fun = $(this).find('i:visible').attr('role');
      let objFile = $('#file-'+name);
      let fType = objFile.attr('data-type');
        if(!(['image','file'].includes(fType))){
         $.alertMessage('erro', "Falha na ação!");
         return false; 
        }
        if(fun=='desfazer'){
         const dForm =objPage.dataForm;
         if(dForm[name]!==undefined&&dForm[name]){
          $.toggleOpBtFile(name,true);
          $.loadFile(fType,name,dForm[name]);
         }else{
          objFile.clearFile();
          $.toggleBtsFile(name,false);
          $.filesDefault(fType,name);
         }
        } else{
         let  data = {type:fType,id:$(this).parents('form').find('input[name=id]').val(),name},alertMsg='Deseja deletar essa imagem?',self=this;
         if(fType=='file'){alertMsg='Deseja deletar esse arquivo?';}
       
          objPage.form.deleteFile.init(data);
         
        alertify.confirm(
          "Confirme!", //Title
          alertMsg, //Message
          function(){
            $.ajax({
              url: objPage.url.url_page+'/delete_file',
              type: 'POST',
              dataType: 'JSON',
              data: data,
              success: function (r) {
                if (r.status) {
                  $.alertMessage('success', r.msg);
                  objFile.clearFile();
                  $.toggleBtsFile(name,false);
                  $.filesDefault(fType,name);
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
          function(){console.log("cancel")
        });
      }
    },
    downloadFile:function(){
      let name =  $(this).attr("data-file-download"),data=objPage.dataForm;
      if(data[name]!==undefined&&data[name]){
        document.location.href =objPage.url.url_page+'/download-f/'+data[name];
      }
    },
  });
  $.extend({
    infoLoad: function(concluded='show',op={txtProcessing:"Processando...", txtConcluded:"Concluído!",txtError:"Um erro ocorreu!"}){
      let info = $("#myinfo"),d=new Date().getTime();
      if(concluded=='success'){
        info.attr("aria-show",true);
        info.find("a.info-close").addClass('hide');
        let src = info.find("#myinfo-body").data("src-done");
        let html = `<img style="width:100px;" src="${src}?v=${d}" /><p>${op.txtConcluded}</p>`;
        info.find("#myinfo-body").html(html);
        setTimeout(()=>{
          info.attr("aria-show",false);
        },2500);
      }if(concluded=='error'){
        info.attr("aria-show",true);
        info.find("a.info-close").removeClass('hide');
        let src = info.find("#myinfo-body").data("src-error");
        let html = `<img style="width:100px;" src="${src}?v=${d}" /><p>${op.txtError}</p>`;
        info.find("#myinfo-body").html(html);
      }else if(concluded=='show') {
        info.attr("aria-show",true);
        info.find("a.info-close").addClass('hide');
        let src = info.find("#myinfo-body").data("src-before");
        let html = `<img style="width:100px;" src="${src}?v=${d}" /><p>${op.txtProcessing}</p>`;
        info.find("#myinfo-body").html(html);
      }else if(concluded=='hide'){
         info.attr("aria-show",false);
      }
    },
    openForm: function () {
      $('[data-action-title=form-action]').text('Inserção');
      $.anchorForm();
      objPage.dataForm=[];
      $('form[name=formData').clearForm();
      objPage.form.new.init();
    },
    closeForm: function() {
     ///close;
    },
    anchorForm:function(){
      if($(window).width()<=975){
        $('html,body').animate({ scrollTop: 0 + $('#container-form').offset().top }, 'slow');
       }
    },
    toggleBtsFile:function(name,st){
      if(st){
        $(`[data-file-view=${name}]`).show();
        $(`[data-file-download=${name}]`).show();
        $(`[data-file-delete=${name}]`).show();
      }else{
        $(`[data-file-view=${name}]`).hide();
        $(`[data-file-download=${name}]`).hide();
        $(`[data-file-delete=${name}]`).hide();       
      }
    },
    filesDefault:function(t,k){
      let def = {file:{title:'Sem arquivo',image:'no-doc.png'},image:{title:'Sem imagem',image:'no-image.png'}};
      $(`[data-file-tag=${k}]`).attr('src', objPage.url.url_context+"/public/assets_zp/img/"+def[t].image)
      .parent().attr("href","");
      $(`.mask p[file-title=${k}], div[data-file-infoname=${k}]`).html(def[t].title);
    },
    loadFile:function(t,k,val){
      if(t=='image'){
       $(`[data-file-tag=${k}]`).attr('src', objPage.filesPath+"/thumb_"+val)
       .parent().attr("href",objPage.filesPath+"/"+val);
      }else{
       let s = val.split('.');
       $(`[data-file-tag=${k}]`).attr('src', $.loadImgFile(s[s.length-1])); 
      }
      let name = val.split(".");
      $(`.mask p[file-title=${k}], div[data-file-infoname=${k}]`).html(name[0].substring(0,10)+"(...)."+name[name.length-1]);      
    },
    loadImgFile:function(ext){
      let img;
      if(["xlsx","xls"].includes(ext)){
        img = 'excel.png';
      }else if(["pdf",""].includes(ext)){
        img = 'pdf.png';
      }else if(["txt",""].includes(ext)){
        img = 'text.png';
      }else if(["doc","docx"].includes(ext)){
        img = 'word.png';
      }else if(["rar",""].includes(ext)){
        img = 'zip.png';
      }else if(["jpeg","jpg","jxr","hdp","wdp","png","svg","svgz","tif","tiff","wbmp","webp","jng"].includes(ext)){
        img = 'img.png';
      }else if(["mid","midi","kar","aac","f4a","f4b","m4a","mp3","oga","ogg","opus","ra","wav"].includes(ext)){
        img = 'music.png';
      }else if(["3gp","3gpp","f4p","f4v","m4v","mp4","mpeg","mpg","ogv","mov","webm","flv","mng","asf","asx","wmv","avi"].includes(ext)){
        img = 'video.png';
      }else{
        img = 'app.png';
      }
      return objPage.url.url_context+"/public/assets_zp/img/"+img;
    },
    toggleOpBtFile:function(k,showEx){
      if(showEx){
      $(`a[data-file-delete=${k}]`).find('i[role=desfazer]').hide();
      $(`a[data-file-delete=${k}]`).find('i[role=excluir]').show();
      $(`a[data-file-download=${k}]`).show();
    }else{
      $(`a[data-file-delete=${k}]`).find('i[role=desfazer]').show();
      $(`a[data-file-delete=${k}]`).find('i[role=excluir]').hide();
      $(`a[data-file-download=${k}]`).hide();
      }
    },
    toggleSwitchStatus: function (status){
       if(status=="SIM"){
        let pai = $("input[name=status][type=checkbox]").prop("checked",false).parent();
        pai.attr("data-original-title","Registro Inativo");
       }else{
       let pai=  $("input[name=status][type=checkbox]").prop("checked",true).parent();
       pai.attr("data-original-title","Registro Ativo");
       }
      },
  });
   
  $("[data-file-edit]").on("click",function(){
    $(this).selectFile();
  });
  $("[data-file-view]").on("click",function(){
   $(this).lightboxImg();
  });
  $("[data-file-delete]").on("click",function(){
   $(this).deleteFile();
  });
  $("[data-file-download]").on("click",function(){
   $(this).downloadFile();
  });
  // $("form[name=formData] input[name=status]").parent().on("click",function(e){
  //   let time = new Date().getTime();
  //   if((time-e.timeStamp)>3){
  //     let id =$(this).parents('form').find('input[name=id]').val();
  //     if(id!=undefined&&id){
  //       let status =($(this).find('[type=checkbox]').checked)?'SIM':'NÃO';
  //       //e.preventDefault();
  //    $(this).updateStatus(status,id);
  //   }
  //  }
  // });
  
  $('form[name=formData]').submit(function (e) {
    e.preventDefault();
    $(this).saveForm(objPage.form.save.validate);
    return false;
});

  $('.modal .close, button[role=btFechar]').on('click', function () {
    $.closeForm($(this));
  });

  $('[data-modal-get]').on('click',function(){
    var get = $(this).attr('data-modal-get'),
      modal = $('.modal');
    modal.addClass('show');
    removeClass('[data-modal-request]', 'ativo');
    modal.find('[data-modal-request="' + get + '"]').addClass('ativo');
  });

  function removeClass(obj, classe) {
    $(obj).removeClass(classe);
  }
  function removeEvent(obj, event) {
    $(obj).off(event);
  }

  $('[role=clearForm]').click(function () {
    $('form[name=formData').clearForm();
    $('[data-action-title=form-action]').text('Inserção');
  });
  //arquivos/imagens//
  // $('[role=addArquivo').click(function () {
  //   $(this).parent().find("input[type=file]").click();
  // });
  // $('[role=removerArquivo').click(function () {
  //   $(this).removerArquivo();
  // });
  // $("input[data-tipo=imagem],input[data-tipo=arquivo]").change(function () {
  //   $(this).addArquivo();
  // });

 /*
-----------------------------------------------------
\                                                   /
 ++++++++++++++++ FUNÇÕES ONLOAD +++++++++++++++++++
/                                                   \
-----------------------------------------------------
 */
$('[data-lightbox]').lightGallery({counter:false,hash:false});
if('B'!=objPage['level']&&'A'!=objPage['level']){
  let bt =$('[data-bt=save]');
  if(bt.length){
    bt.attr("disabled",true);
    bt.attr("title","Não permitido");
  }
}
});

function mineTypes (type) {
  let mType = {
    "application/atom+xml":"atom",
    "application/json":["json","map","topojson"],
    "application/ld+json":"jsonld",
    "application/rss+xml":"rss",
    "application/vnd.geo+json":"geojson",
    "application/xml":["rdf","xml"],
    "application/javascript":"js",
    "application/manifest+json":"webmanifest",
    "application/x-web-app-manifest+json":"webapp",
    "text/cache-manifest":"appcache",
    "audio/midi":["mid","midi","kar"],
    "audio/mp4":["aac","f4a","f4b","m4a"],
    "audio/mpeg":"mp3",
    "audio/ogg":["oga","ogg","opus"],
    "audio/x-realaudio":"ra",
    "audio/x-wav":"wav",
    "image/bmp":"bmp",
    "image/gif":"gif",
    "image/jpeg":["jpeg","jpg"],
    "image/jxr":["jxr","hdp","wdp"],
    "image/png":"png",
    "image/svg+xml":["svg","svgz"],
    "image/tiff":["tif","tiff"],
    "image/vnd.wap.wbmp":"wbmp",
    "image/webp":"webp",
    "image/x-jng":"jng",
    "video/3gpp":["3gp","3gpp"],
    "video/mp4":["f4p","f4v","m4v","mp4"],
    "video/mpeg":["mpeg","mpg"],
    "video/ogg":"ogv",
    "video/quicktime":"mov",
    "video/webm":"webm",
    "video/x-flv":"flv",
    "video/x-mng":"mng",
    "video/x-ms-asf":["asf","asx"],
    "video/x-ms-wmv":"wmv",
    "video/x-msvideo":"avi",
    "image/x-icon":["cur","ico"],
    "application/msword":"doc",
    "application/vnd.ms-excel":"xls",
    "application/vnd.ms-powerpoint":"ppt",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document":"docx",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":"xlsx",
    "application/vnd.openxmlformats-officedocument.presentationml.presentation":"pptx",
    "application/vnd.debian.binary-package": "deb",
    "application/font-woff":"woff",
    "application/font-woff2":"woff2",
    "application/vnd.ms-fontobject":"eot",
    "application/x-font-ttf":["ttc", "ttf"],
    "font/opentype":"otf",
    "application/java-archive":["ear","jar","war"],
    "application/mac-binhex40":"hqx",
    "application/octet-stream":["bin","deb","dll","dmg","img","iso","msi","msm","msp","safariextz"],
    "application/pdf":"pdf",
    "application/postscript":["ai","eps","ps"],
    "application/rtf":"rtf",
    "application/vnd.google-earth.kml+xml":"kml",
    "application/vnd.google-earth.kmz":"kmz",
    "application/vnd.wap.wmlc":"wmlc",
    "application/x-7z-compressed":"7z",
    "application/x-bb-appworld":"bbaw",
    "application/x-bittorrent":"torrent",
    "application/x-chrome-extension":"crx",
    "application/x-cocoa":"cco",
    "application/x-java-archive-diff":"jardiff",
    "application/x-java-jnlp-file":"jnlp",
    "application/x-makeself":"run",
    "application/x-cd-image": "iso",
    "application/x-opera-extension":"oex",
    "application/x-perl":["pl","pm"],
    "application/x-pilot":["pdb","prc"],
    "application/x-rar-compressed":"rar",
    "application/x-redhat-package-manager":"rpm",
    "application/x-sea":"sea",
    "application/x-shockwave-flash":"swf",
    "application/x-stuffit":"sit",
    "application/x-tcl":["tcl","tk"],
    "application/x-x509-ca-cert":["crt","der","pem"],
    "application/x-xpinstall":"xpi",
    "application/x-ms-dos-executable":"exe",
    "application/xhtml+xml":"xhtml",
    "application/xslt+xml":"xsl",
    "application/zip":"zip",
    "text/csv":"csv",
    "text/html":["htm","html","shtml"],
    "text/markdown":"md",
    "text/mathml":"mml",
    "text/plain":"txt",
    "text/vcard":["vcard","vcf"],
    "text/vnd.rim.location.xloc":"xloc",
    "text/vnd.sun.j2me.app-descriptor":"jad",
    "text/vnd.wap.wml":"wml",
    "text/vtt":"vtt",
    "text/x-component":"htc",
    "application/x-desktop": "desktop",
    "text/x-markdown": "md",
    "text/vnd.trolltech.linguist": "ts",
    "image/vnd.microsoft.icon": "ico",
    "application/x-java-archive": "jar",
    "application/x-sharedlib": "so"
  }
  if(mType[type]!=undefined){
    return mType[type];
  }
  return '';
}

$("[data-toggle='tooltip-zp']").hover(
  function(evHanderIn){
    $(this).context.setAttribute("aria-show",true);
},function(evHanderOut){
  $(this).context.setAttribute("aria-show",false);
});
let idSwStatus = document.getElementById("swStatus");
if(idSwStatus){
  idSwStatus.onclick = function(el){
    if(!el.target.checked){ // Quando sair do false para o true
      el.target.parentElement.setAttribute('data-original-title',"Registro Inativo");
    }else{
      el.target.parentElement.setAttribute('data-original-title',"Registro Ativo");
    }
  }
}