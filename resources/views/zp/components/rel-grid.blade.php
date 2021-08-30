@section("sectionContent")
<div id='' class='col-md-12'>
    <div class='x_panel'>
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
        <div class='x_content'>
            <div class="filtros-rel">
                @yield("sectionReportFilter")
                <div class="separator"></div>
                
            </div>
            <table id="tb-grade-relatorio" class="display table-rel table-striped" style="width:100%">
            </table>
        </div>
    </div>
</div>
<script >
    let tbGridRel=null;
    $(function(){
          $('form[name=formDataRel]').submit(function(e){
           tbGridRel.ajax.reload();  
           return false
          });
          $.extend({
           relValid:function(){
              return (!$('form[name=formDataRel]').formValidation(objPage.form.save.validate))?false:true;
            },
           tAjaxParams:function(){
           fData={};
           for(let ob of $('form[name=formDataRel] :input')){
            let s = $(ob);
            let name=s.attr('name');
            if(!s.length){continue;}
            if(s.attr('type')=='radio'){
             let vCheck=$(`[name=${name}]:checked`).val();
             fData[name]=(vCheck!==undefined)?vCheck:'';
            }else if(s.attr('type')=='checkbox'){
             if(s[0].checked){
              fData[name] =s.val();
             }
            }else{
             fData[name] = s.val();
            }
           }
           return {filter:fData};
           },
           getExcelAjax:function(){
            if(!$.relValid()){return false;}
             let data=$.tAjaxParams(),cols=objPage.rel.init.columns;
             data['columns']=[];
             for(let i in cols){
               data['columns'].push({title:cols[i].title,data:cols[i].data,name:cols[i].name});
             }
             data['search']={value:$('#tb-grade-relatorio_filter label input').val()};
             data['rel_type']='EXCEL';
             $.ajax({
                url: objPage.url.url_page+'/filter',
                type: 'POST',
                dataType: 'JSON',
                data:data,
                success:function(r){
                if (r.status) {
                  location.href=objPage.url.url_page+'/download-r/'+r.nameFile;
                }else{
                    $.alertMessage('error', r.msg);
                }
                if(r.log){console.log(r.log)}
                },
                error:function(r){
                $.ajaxErros(r.responseJSON);
                },
            });
           }
          });
          let objRel=objPage.rel.init;
   
        let relBtts=[{
            className:'btn btn-default',
            text: '<i class="fa fa-search">  <b>Buscar</b></i>',
            bom:true,
            action:function(){
             if($.relValid()){
              tbGridRel.ajax.reload();      
             }
            }
        },{
            className:'btn btn-default',
            text: '<i class="fa fa-file-excel-o">  <b>Excel</b></i>',
            bom:true,
            action:function(){
                $.getExcelAjax();
            }
        }];

        if(objRel.buttons!= undefined&&objRel.buttons.length){
         relBtts.concat(objRel.buttons);
        }

        let tb ={
            "searching": true,
            "paging":true,
            "pagingType":"full_numbers",
            "ordering":false,
            "responsive":true,
            "fixedHeader":true,
            "language":objPage.grid.lang,
            "lengthMenu":[[100,500,1000,3000,5000,-1],[100,500,1000,3000,5000,"Todos"]],
            "iDisplayLength":500,
            "columns":[],
            "dom":'<"top"<"filters"fl><"btns"B>><"#rel-title">tip',
            "serverSide":true,
            processing:true,
            "ajax":{
            "url":objPage.url.url_page+"/filter",
            "data":function(d){
                d['rel_type']='VIEW';
                return Object.assign(d,$.tAjaxParams());
            },
            type:'POST',
            },
            initComplete:function(json){
                $("#rel-title").html(`<h2>${json.jqXHR.responseJSON.title}</h2>`);
                $(".main_menu_side").height($(".container.body").height());
            },
            "buttons":relBtts,
        }
        for(let r in objRel){//substitui  dados dataTable
          if(!['buttons'].includes(r)){
           if(!tb[r]!==undefined){
             tb[r]=objRel[r];
           }
          }
        }
         tbGridRel=$('#tb-grade-relatorio').DataTable(tb);

        $(`table#tb-grade-relatorio > thead > tr > th`).click(function(){
         let index = $(this).toggleClass("selected").context.cellIndex+1;
         $("table#tb-grade-relatorio tbody tr td:nth-child("+index+")").toggleClass("selected");
        });
        $("table#tb-grade-relatorio > thead > tr > th").hover(
         function(){
          let i = $(this).addClass("before-select").context.cellIndex+1;
          $("table#tb-grade-relatorio  tbody  tr  td:nth-child("+i+")").addClass("before-select");
         },
         function(){
          let i = $(this).removeClass("before-select").context.cellIndex+1;
          $("table#tb-grade-relatorio  tbody  tr  td:nth-child("+i+")").removeClass("before-select");
         }
        );
        $('table#tb-grade-relatorio tbody').on( 'click', 'tr',function(){
         if($(this).hasClass('selected')) {
          $(this).removeClass('selected');
         }else{
          tbGridRel.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
         }
        });
    });

  </script>
  <script>
    let seletorForm=$('#form-relatorio')
    let fg =seletorForm.find('.form-group');
    for(let i=1; i<=fg.length; i+=2){        
        let lastHeight= fg[i-1].clientHeight;
        if(fg[i] == undefined){       
        seletorForm.append(`<div style="height:${lastHeight}px" class="form-group"></div>`);
        }else if(fg[i].clientHeight < lastHeight){
        seletorForm.find('.form-group:nth-child('+(i+1)+')').height(lastHeight-6);
        }else if(fg[i].clientHeight > lastHeight){
        seletorForm.find('.form-group:nth-child('+(i)+')').height(fg[i].clientHeight-6);
        }    
    }
    seletorForm.append("<button class='hide' type='submit'></button>");
  </script>
@endsection