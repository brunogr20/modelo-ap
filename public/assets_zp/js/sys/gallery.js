$(function(){
  $("#principal-galeria").sortable();
  $('#selecionarImagem').click(function(){
   if($(this).val()!='selecionar'){
    $(this).val('selecionar');
    $('#marcarImagem').hide();
    $('#btDeletMult').hide();
    if($('input[type=checkbox]').is(':checked')){
     $('input[type=checkbox]').attr("checked",false).hide().closest('.galeria-bor').removeClass('check');
    }
   }else{
    $(this).val('ocutar');
    $('#marcarImagem').show().text('Marcar');
    $('#btDeletMult').show();
    $('input[type=checkbox]').show();
   }
  });
 $('#marcarImagem').click(function(){
  let input=$('input[type=checkbox]');
  if(input.length){
   if(input.prop("checked")==true){
    $(this).val('marcar');
    input.prop("checked",false);
    $('.galeria-bor').removeClass('check');
   }else{
    $(this).val('desmarcar');
    input.prop("checked",true);
    $('.galeria-bor').addClass('check');
   }
  }else{
   alert('Não foi possível realizar está ação.');
  }
 });
});
var urlAjax='adm_popup_galeria_paginas_retorno.php';
function FileFrame(fileArea){
 var self=this;
 this.fileArea=fileArea;
 this.fileTitle='';
 this.elemtMsg=document.getElementById('drop-message');
 this.init=function(){
  // Registrando eventos de drag and drop
  self.fileArea.addEventListener("dragleave",self.dragHover,false);
  self.fileArea.addEventListener("dragover",self.dragHover,false);
  self.fileArea.addEventListener("drop",self.drop,false);
 };
 this.dragHover=function(e){
  // Impede possíveis tratamentos dos arquivos
  // arrastados pelo navegador, por exemplo, exibir
  // o conteudo do mesmo.
  e.stopPropagation();
  e.preventDefault();
  // Quando o arquivo está sobre área alteramos o seu estilo
  self.fileArea.className=(e.type=="dragover"?"hover":"");
 };
 this.drop=function(e){
  self.dragHover(e);
  // Volta um array com os arquivos arratados,
  // porém neste exemplo iremos tratar apenas
  // o primeiro arquivo
  self.file=e.dataTransfer.files;
  self.fileTitle.innerHTML=self.file.name;
  // self.read(self.file);
  self.sendFile(self.file);
 };
 // Esse método irá ler o arquivo na memória,
 // depois iremos mostrá-lo no nosso frame
 this.read=function(file){
  // Iremos ler apenas imagens nesse exemplo
  // e iremos exibi-lo no frame
  if(file.type.match('image.*')){
   var reader=new FileReader();
   // Callback que será executado após a leitura do arquivo
   reader.onload=function(f){
    self.fileArea.innerHTML="";
    self.elemtMsg.innerHTML='Carregando...';
    self.fileArea.setAttribute("style","padding: 0px !important;");
    var img=document.createElement("img");
    img.setAttribute("src",f.target.result);
    img.setAttribute("height","350");
    self.fileArea.appendChild(img);
   }
   // Irá ler o arquivo para ser acessado através de uma url
   reader.readAsDataURL(file);
  }else{
   alert('O arquivo selocionado não é um tipo de imagem');
  }
 }
 // Essa função pode ser utilizada como
 this.sendFile=function(file){
  // Criaremos um formulário
  let totalImagens=file.length,imagensAdd=0,msgRetornto='';
  for(let i=0;i<totalImagens;i++){
   self.elemtMsg.innerHTML='Carregando...';
   let statusUpload=true;
   if(!file[i].type.match('image.*')){
    msgRetornto=msgRetornto+'<div class="barraTituloForm">"'+file[i].name+'" não é um tipo de imagem.  Imagens </div>';
    statusUpload=false;
   }
   if(parseInt(file[i].size)>2000000){
    msgRetornto=msgRetornto+'<div class="barraTituloForm">"O tamanho da imagem "'+file[i].name+'" é maior do que o esperado.(2MB) </div>';
    statusUpload=false;
   }
   if(statusUpload){
    var ultimaImagem=i;
    var f=new FormData();
    // Passando o arquivo para o formulário
    f.append("acao",'upload');
    f.append("pastaIMG",document.getElementById('pastaIMG').value);
    f.append("codPublicacao",document.getElementById('codPublicacao').value);
    f.append("tb",document.getElementById('tb').value);
    f.append("arquivo"+i,file[i]);
    f.append("numImagem",i);
    var request=new XMLHttpRequest();
    request.open("POST",urlAjax,true);
    request.send(f);
    request.onload=function(e){
     var dados=JSON.parse(this.responseText);
     if(dados.status){
      imagensAdd++;
      self.elemtMsg.innerHTML='Carregando...<br/> '+imagensAdd+' de '+totalImagens;
      document.getElementById('principal-galeria').innerHTML=document.getElementById('principal-galeria').innerHTML+
              stringBoxImagem(dados.codigo,dados.imagem,document.getElementById('pastaIMG').value,++dados.quant);
     }else{
      msgRetornto=msgRetornto+'<div class="barraTituloForm">'+dados.mensagem+'</div>';
     }

     if(parseInt(dados.num)+1==totalImagens){
      if(imagensAdd==1){
       alert(imagensAdd+" imagem  carregada com sucesso!");
      }else{
       alert(imagensAdd+" imagens  carregadas com sucesso!");
      }
      self.elemtMsg.innerHTML='Arraste uma<br/> ou mais imagens aqui';
      if(msgRetornto){
       document.getElementById('msgErros').innerHTML='<div class="imgFalha">Imagens que falharam no carregamento</div>'+msgRetornto;
      }
     }

    };
    request.onreadystatechange=function(){
     // Término do envio do formulário
     if(request.readyState==4){
     }
    }
   }else if(parseInt(i)+1==totalImagens){
    if(msgRetornto){
     document.getElementById('msgErros').innerHTML='<div class="imgFalha">Imagens que falharam no carregamento</div>'+msgRetornto;
    }
   }

  }
 }

}

var area=document.getElementById("image-area");
var fileFrameArea=new FileFrame(area);
fileFrameArea.init();
var btDeletMult=document.querySelector('#btDeletMult');
btDeletMult.addEventListener('click',function(e){
 var msg=document.getElementById('msg2');
 var form_data=new FormData();
 var num=0;
 var ids=[]
 var valida=false;
 //cria array de dados para serem ordenados
 $('#principal-galeria .galeria-bor input[type=checkbox]').each(function(e){
  if($(this).prop("checked")==true){
   ids[num++]=$(this).attr('data');
   valida=true;
  }
 });
 if(valida){
  form_data.append('acao','deletarMult');
  form_data.append('tb',document.getElementById('tb').value);
  form_data.append("pastaIMG",document.getElementById('pastaIMG').value);
  form_data.append("codPublicacao",document.getElementById('codPublicacao').value);
  form_data.append('ids',ids);
  var r=confirm("Deseja deletar os ("+num+") itens selecionados?");
  if(r==true){
   var ajax=new XMLHttpRequest();// instancia um objto ajax;
   ajax.onload=function(e){
    var dados=JSON.parse(this.responseText);
    carregaGaleria();
    //window.opener.exibirTotalFotos(document.getElementById('codGaleria').value,document.getElemntById('tb').value);
    // alert(dados.msg);
   };
   ajax.open('POST',urlAjax,true);//faz a requisicao dos dados, via post
   ajax.send(form_data);//envia o form
  }
 }else{
  alert('Selecione um item da galeria.')
 }
});
var btOrdencao=document.querySelector('#ordenarImagem');
btOrdencao.addEventListener('click',function(e){
 var msg=document.getElementById('msg2');
 var form_data=new FormData();
 var ids=[];
 var num=0;
 //cria array de dados para serem ordenados
 $('#principal-galeria .galeria-bor input[type=hidden]').each(function(e){
  ids[num++]=$(this).val();
 });
 if(!ids.length){
  alert('Não foi possível realizar está ação.');
  return false;
 }
 form_data.append('acao','ordernarItens');
 form_data.append('tb',document.getElementById('tb').value);
 form_data.append("pastaIMG",document.getElementById('pastaIMG').value);
 form_data.append('ids',ids);
 var ajax=new XMLHttpRequest();// instancia um objto ajax;
 ajax.onload=function(e){
  var dados=JSON.parse(this.responseText);
  if(dados.status){
   alert('Os itens foram ordenados com sucesso.');
   carregaGaleria()
  }else{
   alert('Não foi possível ordenar os dados.')
   console.log('erro ondenacao.');
  }
 };
 ajax.open('POST',urlAjax,true);//faz a requisicao dos dados, via post
 ajax.send(form_data);//envia o form
});
function fileUpload(file){
 fileFrameArea.sendFile(file.files);
 // var fileArq=document.querySelector('input[name=imagem]').files[0];
 /*  var fileArq=file.files[0];
  var form_data=new FormData();
  form_data.append("acao",'upload');
  form_data.append("pastaIMG",document.getElementById('pastaIMG').value);
  form_data.append("codPublicacao",document.getElementById('codPublicacao').value);
  form_data.append("tb",document.getElementById('tb').value);
  form_data.append("arquivo",fileArq);
  if(!check_multifile_logo(fileArq['name'])){
  alert('O tipo do arquivo é inválido');
  }
  var ajax;// cria var ajax;
  ajax=new XMLHttpRequest();// instancia um objto ajax;
  ajax.onload=function(e){
  var dados=JSON.parse(this.responseText);
  if(dados.status){
  carregaGaleria();
  alert('1 imagem carregada com sucesso!');
  }else{
  alert(dados.mensagem)
  }
  };
  ajax.open('post',urlAjax,true);//faz a requisicao dos dados, via post
  ajax.send(form_data);//envia o form
  return false;*/
}

function  stringBoxImagem(codigo,imagem,pastaIMG,numero){
 return "<div  class='galeria-bor'> <div class='galeria-imagem'><img class='imgG' src='"+pastaIMG+'/'+imagem+"'  /></div><input type='hidden' name='ordena"+codigo+"' value='"+codigo+"'/><div class='box-control'><input class='intBox' nome=''  value='"+codigo+"' data='"+codigo+"' type='checkbox' /><span class='num'><b>"+numero+"</b></span><span onclick='deletarImg(\""+codigo+"\")' class='elim'></span></div></div>";
}

function carregaGaleria(){
 var codPublicacao=document.getElementById('codPublicacao').value
 var pastaIMG=document.getElementById('pastaIMG').value
 if(codPublicacao){
  var form_data=new FormData();
  form_data.append('codPublicacao',codPublicacao);
  form_data.append('acao','listar');
  form_data.append('tb',document.getElementById('tb').value);
  var ajax=new XMLHttpRequest();// instancia um objto ajax;
  ajax.onload=function(e){
   var dados=JSON.parse(this.responseText);
   if(dados.status){
    var galeria=dados.galeria;
    var resposta='';
    for(var i=0;i<galeria.length;i++){
     var numero=parseInt(i)+1;
     resposta+=stringBoxImagem(galeria[i].codigo,galeria[i].imagem,pastaIMG,numero);
     //      resposta+="<div  class='galeria-bor'> <div class='galeria-imagem'><img class='imgG' src='"+pastaIMG+'/'+galeria[i].imagem+"'  /></div><input type='hidden' name='ordena"+galeria[i].codigo+"' value='"+galeria[i].codigo+"'/><div class='box-control'><input class='intBox' nome=''  value='"+galeria[i].codigo+"' data='"+galeria[i].codigo+"' type='checkbox' /><span class='num'><b>"+numero+"</b></span><span onclick='deletarImg(\""+galeria[i].codigo+"\")' class='elim'></span></div></div>";
//<a href='"+pastaIMG+'/'+galeria[i].imagem+"' data-group='galeria' class='html5lightbox' >
    }
    document.getElementById('principal-galeria').innerHTML='';
    setTimeout(function(){
     document.getElementById('principal-galeria').innerHTML=resposta;
     jQuery(".html5lightbox").html5lightbox();
     var check=document.querySelectorAll('.intBox');
     [].forEach.call(check,function(obj){
      obj.addEventListener('click',function(){
       this.closest('.galeria-bor').classList.toggle('check');
       if($('input[type=checkbox]').is(':checked')){
        $('#marcarImagem').show().text('Marcar');
        $('#btDeletMult').show();
        $('input[type=checkbox]').show();
        $(this).val('Ocutar');
       }else{
        $('#marcarImagem').hide();
        $('#btDeletMult').hide();
       }

      });
     });
    },70);
   }else{
    console.log('erro');
   }
  };
  ajax.open('POST',urlAjax,true);//faz a requisicao dos dados, via post
  ajax.send(form_data);//envia o form
 }
}

carregaGaleria();
function deletarImg(id){
 if(id,id!='undefined'){
  var msg=document.getElementById('msg2');
  msg.innerHTML='Carregando...'
  var r=confirm("Deseja deletar essa imagem?");
  if(r==true){
   var form_data=new FormData();
   form_data.append('acao','deletarItem');
   form_data.append(' tb',document.getElementById('tb').value);
   form_data.append("pastaIMG",document.getElementById('pastaIMG').value);
   form_data.append("codPublicacao",document.getElementById('codPublicacao').value);
   form_data.append('codigoItem',id);
   var ajax=new XMLHttpRequest();// instancia um objto ajax;
   ajax.onload=function(e){
    var dados=JSON.parse(this.responseText);
    if(dados.status){
     //msg.innerHTML='Imagem  deletada  com sucesso!';
     alert('Imagem  deletada  com sucesso.');
     carregaGaleria()
    }else{
     alert(dados.msg);
    }
    msg.innerHTML='';
    /* setTimeout(function(){
     msg.innerHTML='';
     },3000)*/
   };
   ajax.open('POST',urlAjax,true);//faz a requisicao dos dados, via post
   ajax.send(form_data);//envia o form
  }
 }else{
  alert('Erro escontrado');
 }
}

function check_multifile_logo(file){
 var extension=file.substr((file.lastIndexOf('.')+1))
 extension='.'+extension;
 if(extension=='.jpg'||extension=='.png'||extension=='.gif'){
  return true;
 }else{
  return false;
 }
}