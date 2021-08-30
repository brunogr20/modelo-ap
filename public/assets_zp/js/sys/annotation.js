 //
 function loadNote(action,params={id:"",loadHomeNotes:false}){
  $.ajax({
    url: objPage.url.url_sys+'/anotation',
    type: 'POST',
    dataType: 'JSON',
    data: {action:action,id:params.id},
    success: function (r) {
      if(!params.loadHomeNotes)
        $("#annotation_menu").show();
      if (r.status) {
          $('[date-list=my-annotations]').html("");
          for(let item of r.list){
            let styleStar='star'+item.nivel;
             let ex={class:'fa-globe',type:'TODOS',title:'Mostrando em todas as páginas'}
             if(item.tipo_exibicao=='SECAO'){
              ex={class:'fas fa-dot-circle',type:'SECAO',title:'Mostrando só nesssa página'}
             }
            let html =`<div data-note="${item.id}">
            <div class="annotation closed ${styleStar}">
              <div class="box-annotation ">
                  <input readonly type="text" name='titulo' value="${(item.titulo == null) ? "Nota sem título":item.titulo}" placeholder="Digite um título">
                  <span class="note-created">${item.data}</span>
                  <textarea textarea-dynamic  class='textarea-dynamic' readonly placeholder="Escreva aqui sua anotação" name="texto" id="">${item.texto}</textarea>
                  <a onclick="noteClick(this,'expand')"  data-annotation='expand' aria-expanded="false"  class='btn-action toggle-icon '><span class="fa fa-angle-down ${styleStar}"></span></a> 
              </div>
              <div class="actions">
                  <a title="abrir edição" onclick="noteClick(this,'save')" data-save='edit' data-toggle="tooltip" data-placement="right" class='btn-action'><i class="fa fa-edit"></i></a>
                  <a title="Marque como importante" data-priority="1"  data-toggle="tooltip" data-placement="right"  class='btn-action' >
                    <i class="fa fa-star"></i>
                    <div class='stars-box'>
                      <a data-value='3' class='star3' onclick="selectionStar(this);"><input  class='hide' ${item.nivel==3?"checked='checked'":''} name='star${item.id}' value='3' type='radio' /><i class="fas fa-star"></i></a>
                      <a data-value='2' class='star2' onclick="selectionStar(this);"><input  class='hide' ${item.nivel==2?"checked='checked'":''} name='star${item.id}' value='2' type='radio' /><i class="fas fa-star"></i></a>
                      <a data-value='1' class='star1' onclick="selectionStar(this);"><input  class='hide' ${item.nivel==1?"checked='checked'":''} name='star${item.id}' value='1' type='radio' /><i class="fas fa-star"></i></a>
                    </div>
                  </a>`;
                 if(!['home','meu-perfil'].includes(objPage['url']['section_url'].split('/').pop())){
                   html +=` <a title="${ex.title}" onclick='toggleAllSection(this)' data-annotation='mark' data-exhibition='${ex.type}' data-toggle="tooltip" data-placement="right" class='btn-action' ><i class="fa ${ex.class}"></i></a>`;
                 }
                  html +=` <a title="Exclua essa nota" onclick="noteClick(this,'del')" data-toggle="tooltip" data-placement="right"  class='btn-action' ><i class="fa fa-times"></i></a>     
                      
              </div>
            </div>
            </div>`;


          $('[date-list=my-annotations]').append(html);
          $(`[data-note=${item.id}]`).bind("click",noteClick);
          $(".textarea-dynamic").unbind().bind("input", function() {
            // Forçar a altura para auto antes de aumentá-la
            $(this).css("height","100%");
            $(this).height(this.scrollHeight - 12);
          });

          // Efeito do botão de expandir nota
          $("div.box-annotation").hover(
              function(){
                let sh = $(this).find("textarea")[0].scrollHeight;
                if(sh >=63){
                  $(this).find("a.toggle-icon").addClass("show");
                }else{
                  $(this).find("a.toggle-icon").removeClass("show");
                }
              },
              function(){
                $(this).find("a.toggle-icon").removeClass("show");
              });
        }
        if(objPage.url.target == "home"){
          showHomeNotes(r);
        }
      }else{
        if(action!='filter'){
          noteClick(null,'add-new');
          showHomeNotes(r);
          $('[date-list=my-annotations]').html('');
        }
        let noNote = `<div class='no-note'>
                       <h4>Sem notas nesta seção</h4>`;
             if(!$('[data-note="new"]').length){
              noNote +=`<a data-annotation="add-new" onclick="noteClick(this,'add-new')">Adicone uma nota <i class="glyphicon glyphicon-plus"></i></a>`;
             }
            noNote +=`</div>`;
        $('[date-list=my-annotations]').html(noNote);


      }
      if(r.log){console.log(r.log)}
    },
    error:function(r){
     $.ajaxErros(r.responseJSON);
    },
  });
}

function showHomeNotes(data){
    let html="";
    if(!data.list.length){
        html +=`<div class="jumbotron">
            <h1 class="display-4">Nenhuma nota foi criada!</h1>
        <p class="lead">Quando você criar suas notas elas aparecerão aqui.</p>
        </div>`;
    }else {
        html += `<div  class="row">`;
        for(let item of data.list){
             html +=`
            <div  data-note="${item.id}" class="col-md-3 col-xs-6">
              <div class="note star${item.nivel}">
                <span onclick="noteClick(this,'del')" class="note-del-btn fa fa-times"></span>
                <span class="dobra"></span>
                <h2>${(item.titulo == null) ? "Nota sem título":item.titulo}</h2>
                <span class="note-created">${item.data}</span>
                <p>${item.texto}</p>
                <div class="end-note"></div>
                <div class="actions-note">
                 
                  <span  onclick="expandNote(${item.id})" class="note-act-btn"><i class="fa fa-edit"></i> Abrir nota</span>
                </div>
              </div>
            </div>
            `;
        }
        html += `</div>`;
    }
  $("#home-notes").html(html);

}

function expandNote(id){
    $("#annotation_menu").show();
    $(`[data-note=${id}]`).find(".actions > a[data-save=edit]").click();
    $(`[data-note=${id}]`).find(".box-annotation > textarea").focus();
    $(`[data-note=${id}]`).find(".box-annotation").addClass("focus");
}

function filterAnnotation(obj,id){
   let s =$(obj);
   s.find('i').removeClass("glyphicon-list glyphicon-filter");
   if(s.attr("role")=='filter'){
    s.find('i').addClass("glyphicon-list");
    s.attr('title','Ver todas as notas');      
    loadNote('filter',{id:id});
    s.attr("role",'all');
  }else if(s.attr("role")=='all'){
    s.find('i').addClass("glyphicon-filter");
    s.attr('title','Ver notas dessa seção');      
    loadNote('all');
    s.attr("role",'filter');
   }
}

//$('[data-annotation=add-new]').bind("click",noteClick);
function noteClick(obj,action){  
  let sel = $(obj);
  let objNote = sel.parents("[data-note]"),
   pai = sel.parent().parent();
   let id=objNote.attr("data-note");
  switch(action){
    case "add-new": // Adiciona nova anotação no box
    if($('[data-note="new"]').length){
      $.alertMessage('info','Finalize a nota existente');
      return false;
    }else{
      $('.no-note').remove();
      minAnnotation();
      let newNote = `
      <li data-note='new'>
        <div class="annotation">
          <div class="box-annotation">
            <input maxlenght="36" type="text" value="" name='titulo' placeholder="Nota sem título">
            <textarea class='textarea-dynamic' rows="1" placeholder="Escreva aqui sua anotação" name="texto" id=""></textarea>
          </div>
          <div class="actions">
            <a title="salve esta nota"  onclick="noteClick(this,'save')" data-save='save' data-toggle="tooltip" data-placement="right" class='btn-action' ><i class="fa fa-save"></i></a>
            <a title="Marque como importante" data-priority='1' data-annotation='plus' data-toggle="tooltip" data-placement="right"  class='btn-action'>
              <i class="fa fa-star"></i>
              <div class='stars-box'>
                <a data-value='1' class='star1' onclick="selectionStar(this);"><input checked class='hide' name='starnew' value='1' type='radio' /><i class="fa fa-star"></i></a>
                <a data-value='2' class='star2' onclick="selectionStar(this);"><input class='hide' name='starnew' value='2' type='radio' /><i class="fa fa-star"></i></a>
                <a data-value='3' class='star3' onclick="selectionStar(this);"><input class='hide'  name='starnew' value='3' type='radio' /><i class="fa fa-star"></i></a>
              </div>
            </a>`;
            if(!['home','meu-perfil'].includes(objPage['url']['section_url'].split('/').pop())){
              newNote += `<a title="Mostrando só nesssa páginas" onclick='toggleAllSection(this)' data-annotation='mark' data-exhibition='SECAO' data-toggle="tooltip" data-placement="right" class='btn-action ef-link'  ><i class="fa fas fa-dot-circle"></i></a>`;
            }
            newNote += ` <a title="Exclua essa nota" onclick="noteClick(this,'del')"  data-toggle="tooltip" data-placement="right"  class='btn-action ef-link' ><i class="fa fa-times"></i></a>     
          </div>
        </div> 
      </li>`;
      $("#annotations-list").prepend(newNote);
      $(".textarea-dynamic").unbind().bind("input", function() {
        // Forçar a altura para auto antes de aumentá-la
        $(this).css("height","auto");
        $(this).height(this.scrollHeight - 12);
      });
    }
    break;
    case "del":
      if(id=='new'){
        objNote.remove();
      }else{
        deleteAnotation(objNote,id);
      }
    break;
    case "save":
    if(sel.attr('data-save')=='edit'){
      if($('[data-note="new"]').length){
        $.alertMessage('info','Finalize a nota existente');
        return false;
      }
      sel.attr("data-save",'save')
      minAnnotation();
      toggleBtSave(objNote,'edit'); 
    }else{
      let titulo=objNote.find('input[name=titulo]').val();
      let texto=objNote.find('textarea[name=texto]').val();
      let tipo_exibicao=(objNote.find('[data-exhibition]').attr('data-exhibition')=='SECAO')?'SECAO':'TODOS';
      let nivel=objNote.find(`input[name=star${id}]:checked`).val();
      nivel=(![1,2,3].includes(nivel))?nivel:1;
      if(!texto){
        $.alertMessage('warning','Digite algo na nota');
        return false;
      }
      $.ajax({
        url:objPage.url.url_sys+'/anotation/'+((id&&id!='new')?'update':'create'),
        type: 'POST',
        dataType: 'JSON',
        data: {id,titulo,texto,tipo_exibicao,nivel},
        success:function(r){
          if (r.status) {
            if(id=='new'){
              objNote.remove();
            }
            loadNote("all");
            $.alertMessage('success', r.msg);
            sel.attr("data-save",'edit')
            toggleBtSave(objNote,'save'); 
          }else{
            $.alertMessage('error', r.msg);
          }
        },
      });
    }
    break;
    case "expand":  
      let exp = (sel.attr("aria-expanded") == "true")?true:false;
      if(!exp){
        let ta =  pai.find("textarea");
        ta.trigger("input");

        sel.attr("aria-expanded",true);
        sel.find("span").removeClass("fa-angle-down").addClass("fa-angle-up");
      }else {
        let ta =  pai.find("textarea");
        ta.css({"height":"100%","overflow":"hidden"});
        sel.attr("aria-expanded",false);
        sel.find("span").removeClass("fa-angle-up").addClass("fa-angle-down");
      }
     // $('[data-annotation]').unbind().bind("click",noteClick);
    break;
  }
}
  $("textarea").unbind().bind("input", function() {
  // Forçar a altura para auto antes de aumentá-la
  $(this).css("height","auto");
  $(this).height(this.scrollHeight - 12);
});

function toggleAllSection(obj){
  let sel = $(obj);
  let id=sel.parents("[data-note]").attr("data-note"),tipo=(sel.attr('data-exhibition')!='SECAO')?'SECAO':'TODOS';
  function toggle(st){
    sel.find('i').removeClass('fas fa-dot-circle');
    sel.find('i').removeClass('fa-globe');
    if (st){
      sel.find('i').addClass('fas fa-dot-circle');
      sel.attr('title','Mostrando só nesssa páginas');
      sel.attr('data-exhibition','SECAO');
    }else{
      sel.find('i').addClass('fa-globe');
      sel.attr('title','Mostrando em todas as páginas');
      sel.attr('data-exhibition','TODOS');
    }
  }
  if(id=='new'){
    toggle(tipo=='SECAO');
  }else{
    $.ajax({
      url:objPage.url.url_sys+'/anotation/update',
      type: 'POST',
      dataType: 'JSON',
      data: {id,tipo_exibicao:tipo},
      success:function(r){
        if (r.status) {
          toggle(tipo=='SECAO');
          $.alertMessage('success', r.msg);
        }else{
          $.alertMessage('error', r.msg);
        }
      },
    });
  }
}

function selectionStar(obj){
  let sel=$(obj);
  let box=sel.parents("[data-note]");
  let id=box.attr("data-note");
  let input=sel.find(`input[name=star${id}]`);
  input.prop('checked',true);
  let nivel=input.val();
  nivel=(![1,2,3].includes(nivel))?nivel:1;
  if(id!='new'){
    $.ajax({
      url:objPage.url.url_sys+'/anotation/update',
      type: 'POST',
      dataType: 'JSON',
      data: {id,nivel},
      success:function(r){
        if (r.status) {

           $("input[type=radio].hide").each(function(){

             if($(this).prop("checked"))
               $(this).attr("checked","checked");
             else
               $(this).removeAttr("checked");                

           });



          box.find('.annotation').removeClass("star1 star2 star3").addClass("star"+nivel);
          box.find("[data-annotation=expand]").find('span')
             .removeClass("star1 star2 star3").addClass("star"+nivel);
          $.alertMessage('success', r.msg);
        }else{
          $.alertMessage('error', r.msg);
        }
      },
    });
  }
}

function minAnnotation(idNot=''){
  for(let ob of $('[data-note]')){
    if(idNot&&$(ob).attr('data-note')==idNot){continue;}
    toggleBtSave($(ob),'save');
    let ex = ob.querySelector('[data-annotation="expand"]');
    $(ex).attr('aria-expanded',true)
    noteClick(ex,'expand');
  };
}

function toggleBtSave(obj,t) {
pai = obj.find('.box-annotation');
btSave= obj.find('[data-save]');
if(t!='save'){
  btSave.removeAttr("data-annotation")
  .attr({"data-annotation":"save","data-original-title":"Salvar essa nota"})
  .find("i.fa").removeClass("fa-edit").addClass("fa-save");
  pai.find("a.btn-action").click().hide();
  //pai.after().hide();
  pai.parent().removeClass("closed");
  pai.find("input").removeAttr("readonly");
  let t = pai.find("textarea").removeAttr("readonly");
  t.trigger("input");
}else{
  btSave.removeAttr("data-annotation")
     .attr({"data-annotation":"edit","data-original-title":"Edite essa nota"})
     .find("i.fa").removeClass("fa-save").addClass("fa-edit");
     pai.parent().addClass("closed").css("height","auto");
     pai.removeClass("focus");
     pai.find("input").removeAttr("readonly");
     pai.find("textarea").removeAttr("readonly");
     pai.find("a.btn-action").click().show();
     //pai.find("textarea").focus();
}
}

function deleteAnotation(objNote,id) {
let self=this;
alertify.confirm(
  "Confirme!", //Title
  "Deseja deletar essa nota?", //Message
  function(){
    $.ajax({
      url:objPage.url.url_sys+'/anotation/delete',
      type: 'POST',
      dataType: 'JSON',
      data: {ids:[id]},
      success:function(r){
        if (r.status) {

          if(objPage.url.target == "home"){
            loadNote("all");
          }else{
            objNote.remove();
          }
          $.alertMessage('success', r.msg);
        }else{
          $.alertMessage('error', r.msg);
        }
      },
    });
  },
  function(){console.log("cancel")}
  );
}