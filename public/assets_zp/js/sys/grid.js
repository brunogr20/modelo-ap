if(objPage.grid !== undefined){
  if (Object.keys(objPage.grid.init).length){
  let levelPerm= objPage['level'];
  let classPerm1=(levelPerm=='B'||levelPerm=='A')?'':'not-allowed',
  classPerm2=('A'==levelPerm)?'':'not-allowed';
    let gGrid = objPage.grid.init;
    $(function(){
        let checkAll = `
        <div id='btn-check-all'>
           <label>
             <div data-toggle='tooltip' onclick='markAll(this)' data-placement='right' title='Marcar/Desmarcar Todos' class='checkbox' style='position: relative;'>
                 <input  type='checkbox' class='flat'  style='position: absolute; opacity: 0;'>
                 <ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;'>
                 </ins>
             </div>
           </label>
        </div>
        `;
        gGrid.columns.unshift({
            name:"",orderable:false,className:"selecionar", data:null,defaultContent:'',
            title:(gGrid.buttons.multChecked) ? checkAll: ""
        });
    if(gGrid.buttons.status){
      let t='Não permitido';
    gGrid.columns.push({name:"status",className:"td-status ", width:"5%", data:"status",title:"",render:function(data,type,row,meta){
       if(data=='SIM'){
        var btStatus={ role:'ativo',title:(classPerm1)?t:'Inativar item',msg:'Inativar',icon:"fa-check"};
       }else{
        var btStatus={ role:'inativo',title:(classPerm1)?t:'Ativar item',msg:'Ativar',icon:"fa-times-circle"};
       }
       return '<span data-toggle="tooltip" data-placement="left" class="btn-status cursor-pointer '+classPerm1+'" title="'+btStatus.title+'"  role="'+btStatus.role+'"><i class="fa '+btStatus.icon+'"></i></span>'; // DEsativar
      }});
    }
      if(gGrid.buttons.delete){
       gGrid.columns.push({name:"Deletar",className:"btDelete "+classPerm2,width:"5%", title:'',data:null,orderable:false,defaultContent:`<span data-toggle="tooltip" data-placement="left"  title="${(classPerm2)?'Não permitido':'Deletar item'}"  class="btn-deletar cursor-pointer" role="excluir"><i class="fa fa-trash"></i></span>`});
      }
     var gridButtons = [];
       if(gGrid.buttons.create){
        gridButtons.push({
          className:'btn btn-default btn-add '+classPerm1,
          text:`<i class="fa fa-plus">  <b title="${classPerm1?'Não permitido':''}">Criar Novo</b></i>`,
          bom:true,
          action:function(){
           if(!classPerm1)
           $.openForm();
          }
        });
        }
        if(gGrid.buttons.refresh){
         gridButtons.push({
         className:'btn btn-default btn-refresh',
         text: '<i class="fas fa-sync-alt"> <b>Atualizar</b></i>',
         bom:true,
         action:function(){
          objPage.grid.refreshing = true;
          $(".btn-refresh").html("<i class='fas fa-sync-alt'> <b>Atualizando</b></i>");
          gridTable.ajax.reload();
         }
        });
        }
        let existOrder=false;
        for(let ob of objPage.grid.init.columns){
          if(ob['data']=='ordem'){
            existOrder=true;
          }
        }
        if(gGrid.buttons.order&&existOrder){
         gridButtons.push({
          className:'btn btn-default btn-sort '+classPerm1,
          text:`<i class="fa fa-sort"> <b title="${classPerm1?'Não permitido':''}">Ordenar</b></i>`,
          bom:true,
          action:function(){
            if(!classPerm1)
            toggleOrdering();
          }
        });
        }
        if(gGrid.buttons.multStatus){
         gridButtons.push({
          className:'btn hide btn-default btn-enable '+classPerm1,
          text: '<i class="fa fa-check"> <b>Ativar Selecionados</b></i>',
          bom: true,
          action:function(){
            if(!classPerm1){
             $(this).updateStatus('SIM'); // ativar múltiplos
            }
          }
         },
         {
          className:'btn hide btn-default btn-disable'+classPerm1,
          text: '<i class="fa fa-times-circle"> <b>Desativar Sel.</b></i>',
          bom    : true,
          action:function(){
            if(!classPerm1){
             $(this).updateStatus('NÃO');// inativar múltiplos
            }
          }
         });
        }
        if(gGrid.buttons.multDelete){
         gridButtons.push({
          className:'btn hide btn-default btn-trash '+classPerm2,
          text: '<i class="fa fa-trash"> <b>Excluir selecionados</b></i>',
          bom    : true,
          action:function(){
            if(!levelPerm){
             $(this).delete();// deletar múltiplos
            }
          }
         });
        }
        objPage.grid.lang.sLengthMenu="_MENU_  por página";
      let tb={
      "responsive":true,
      "paging":true,
      "pagingType":"full_numbers",
      "ordering":true,
      "info":true,
      "order":[[1,"ASC"]],
      "scrollY":"350px",
      "scrollCollapse":true,
      "lengthMenu":[[50,100,300,500,-1],[50,100,300,500,"Todos"]],
      "serverSide":true,
      "processing":true,
      "autoWidth":false,
      ajax:{
       url:objPage.url.url_page+"/load_grid",
       type:'POST',
       complete:function(){
        if (!gGrid.buttons.multChecked) {
            $("#tb-grade-principal tbody tr td:first-child, th.selecionar").hide();
        }
        objPage.grid.ajax.callback();
        objPage.grid.refreshing = false;
        fnShowTextBtn();

        firstColumnToOrdering(objPage.grid.orderingTable);
        $(".btn-refresh").html("<i class='fas fa-sync-alt'> <b>Atualizar</b></i>").removeClass("expanded");

       },
      },
      rowReorder:{
        selector: "td:nth-child(1)",
        enable:false
      },
      columns:gGrid.columns,
      language: objPage.grid.lang,
      rowId:'id',
      //obs: cada letra (frtlip) é referente a um elemento da grid
       dom:'<"top"B>l'+((gGrid.buttons.search)?'f':'')+'tip',
        buttons:gridButtons,
     }
     for(let r in gGrid){
      if(!['buttons'].includes(r)){
       if(!tb[r]!==undefined){
         tb[r]=gGrid[r];
       }
      }
    }
     //DataTable: função que gera a grid.
     //obs: a grid e setada em uma variavel global para poder ser acessada de todo o projeto
     gridTable=$('#tb-grade-principal').DataTable(tb);

     //selecionar linha e carregar dados no form
    let timeBack=1000;
      $('#tb-grade-principal tbody').on('click', 'td', function (ev) {
           //console.log($(this).children()[0].checked, $(this).parent());
           if($(this).children()[0] != undefined)
           ($(this).children()[0].checked)?$(this).parent().addClass('selected-mult') : $(this).parent().removeClass('selected selected-mult');
           if ($(this).children().hasClass('btn-status') && (new Date().getTime() - timeBack) < 400){
            if($(this).find('span').attr('data-toggle')=='tooltip'){
            let nStatus = ($(this).find('span.btn-status').attr('role') == 'ativo') ? 'NÃO':'SIM';
             $(this).updateStatus(nStatus,$(this).parent().attr('id'));
            }
          } else if ($(this).children().hasClass('btn-deletar') && (new Date().getTime() - timeBack) < 400) {
            if(!classPerm2){
             $(this).delete($(this).parent().attr('id'));
            }
          } else {
              timeBack = new Date().getTime();
          }

          $("tr.selected").removeClass("selected");

            if(ev.currentTarget.classList.contains("selecionar")){
                toggleActBtn(); // Faz controle da exibição dos botões de acão.
                toggleDefaultBtns();
            }
            if($(this).children().length < 1){
              $('form[name=formData').requireDados(gridTable.row($(this).parent()).id());
              //gridZoomToggle(false);
            }

          if(!$(this).parent().hasClass("selected-mult")){
            $(this).parent().addClass("selected");
          }
      });

      gridTable.on( 'row-reorder', function ( e, diff, edit ) {
       if(diff.length){
         $(this).reorderGrid();
       }
      });

      //pesquisa de dados na grid
      gridTable.columns().every(function(){
      var that=this;
      $('input[type=search]',this.footer()).on('change',function(){
        that.search(this.value).draw();
      });
      });

    });

    function fnShowTextBtn(){
      $( "a.btn-add, a.btn-sort,a.btn-refresh,a.btn-trash, a.btn-disable, a.btn-enable" ).hover(
        function() {
          $(this).addClass("expanded");
        },
        function() { // ao sair
          if(objPage.grid.orderingTable && $(this).hasClass("btn-sort")) {
            $(this).addClass("fixed");
            return;
          }
          if(objPage.grid.refreshing && $(this).hasClass("btn-refresh")) {
            $(this).addClass("fixed").html("<i class='fas fa-sync-alt'> <b>Atualizando</b></i>");
            return;
          }
          $(this).removeClass("expanded");
        }
      );
    }

    function toggleOrdering(){
        objPage.grid.orderingTable = !objPage.grid.orderingTable;
        if(!objPage.grid.orderingTable){
          // $.alertMessage("warning","Ordenação Desativada!","top-center");
          gridTable.rowReorder.disable();
          firstColumnToOrdering(objPage.grid.orderingTable);
        }else{
          // $.alertMessage("info","Ordenação Ativada!","top-center");
          gridTable.context[0].oInit;
          gridTable.context[0].oInit.rowReorder = {
              selector: "td:nth-child(1)"
           };
          gridTable.rowReorder.enable();
          firstColumnToOrdering(objPage.grid.orderingTable);
        }
    }

    function firstColumnToOrdering(ordering){
      if(ordering){
        $("tr[role='row'] td:first-child").html("<button class='reorder btn btn-success fa fa-sort'></button>");
        $('#btn-check-all').hide();
      }
      else{
        $("tr[role='row'] td:first-child").html("<input type='checkbox'>").addClass("selecionar");
        $('#btn-check-all').show();
      }
    }

    function markAll(wrapInput)
    {
      let c = !wrapInput.firstElementChild.classList.contains('checked');
      $("#tb-grade-principal tbody tr td:first-child input[type='checkbox']").each(function(){
        $(this).context.checked = c;
        if(c)
          $(this).parent().parent().addClass('selected-mult');
        else
          $(this).parent().parent().removeClass('selected-mult');
      });
        toggleDefaultBtns();
        toggleActBtn();
    }
    /**
     *
     * @internal Faz Controle da exibição dos botões de ação da grid.
     * @author José Henrique <henriquegreg45@gmail.com>
     */
    function toggleActBtn()
      {
        let active=false,inactive=false;
        // Percorre todos os checkboxes visíveis na grid
        // $("#tb-grade-principal tbody tr td.td-status button.btn-status").each(function(){
        let gridSelected = [];
        $("#tb-grade-principal tbody tr").each(function(){
          let eTr =$(this);
          if(eTr.children().first().children()[0].checked){
            let status=eTr.find(".btn-status").attr('role');
            if(status=="ativo"){
              active=true;
            }
            if(status=="inativo"){
              inactive=true;
            }
            //alert(eTr.children().first().children()[0].checked);
            //eTr.addClass('selected');
            gridSelected.push(eTr.attr('id'));
          }
          objPage.grid.idsGridSelected =gridSelected;
        });

        if ( active){  //Apresenta o botão de Ativar
          $(".btn-disable,.btn-trash").removeClass('hide');
        }else{
          $(".btn-disable").addClass('hide');
        }
        if (inactive){//Apresenta o botão Desativar
          $(".btn-enable,.btn-trash").removeClass('hide');
        }else{
          $(".btn-enable").addClass('hide');
        }
    }

    function toggleDefaultBtns(){
      let atLeastOnChecked=false;
      // Percorre todos os checkboxes visíveis na grid
      $("#tb-grade-principal tbody tr").each(function(){
        let eTr =$(this);
        if(eTr.children().first().children()[0].checked){
          atLeastOnChecked = true;
        }
       });
        //  td:last-child,td:nth-last-child(2),
        //  th:last-child,th:nth-last-child(2)
       if (atLeastOnChecked){
        $(`.btn-sort,.btn-refresh,.btn-add, 
           #tb-grade-principal_length,
           #tb-grade-principal_filter,
           #tb-grade-principal_paginate
         
           `).addClass('hide');
        $(".dataTables_wrapper").addClass('focus');
        $("th").css('pointer-events','none');
        $("th:first-child").css('pointer-events','all');
       }else {
        $(`.btn-sort,.btn-refresh,.btn-add, 
            #tb-grade-principal_length,
            #tb-grade-principal_filter,
            #tb-grade-principal_paginate
        `).removeClass('hide');
        $(".dataTables_wrapper").removeClass('focus');
        $("th").css('pointer-events','all');
       }
    }

    function gridZoomToggle(show){
      if(show){
        let g = $("#main-grid").removeClass('col-md-6').addClass('col-md-12 expanding');
        gridTable.draw();
         setTimeout(() => {
            g.removeClass('expanding');
         }, 500);
        $("#container-form").fadeOut();
      }else {
        let g = $("#main-grid").removeClass('col-md-12').addClass('col-md-6 expanding');
        gridTable.draw();
        setTimeout(() => {
          g.removeClass('expanding');
        }, 500);
        $("#container-form").fadeIn();
      }

      $.toggleDarkMode();
     }
    }

}