import  {templates,objSecao}  from "./gen_templates/index.js";
$(document).ready(function(){
    $(function () {
        $.extend({
            log(){
                return objSecao;
            },
            setHasOrder(el){
                objSecao.hasOrder = el.checked;
            },
            setHasStatus(el){
                objSecao.hasStatus = el.checked;
            },
            loadTemplate(select){
                if(select.value !== ""){
                    if(templates[select.value] !== undefined){
                        objSecao.fields = templates[select.value];
                        $.render(objSecao);
                    }else{
                        $.alertMessage('error', "Não existe nenhum template a ser carregado com a chave: "+select.value,"top-center");
                    }
                }
            },
            render(obj){
                // percorrendo o array de fields

                let fOrder=1;
                $('#fields').html("");
                for(field of obj.fields){
                    $('#fields').append($.newControl(field.fieldType,fOrder,field));
                    fOrder++;
                }
                $.registerMainEvents();
                $.registerSecondaryEvents();
            },
            newControl(type,fOrder,def={}){
                console.log(def);
                let isOnGrid = (def.html.isOnGrid) ? "checked":"";
                let notNull = (def.col.notNull) ? "checked":"";
                let v=`
                        <div data-field="${fOrder}" class="box-component">
                            <legend class="box-legend">${type.charAt(0).toUpperCase()}${type.slice(1)}</legend>
                            <div class="flex-extremos">
                                <div class="box-inner">
                                    <div class="main">
        
                                            <div class="fieldset-view">
                                            
                                                <div class="in-control">
                                                    <label>Name*</label>
                                                    <input type="text" data-field-param="name-${fOrder}"  value="${def.name}" placeholder="Name">
                                                </div>
                                                `;
                                                if(type!=="checkbox"){
                                                v+=`<div class="in-control xsm">
                                                        <label>Grid</label>
                                                        <input data-field-param="html.isOnGrid-${fOrder}" ${isOnGrid} type="checkbox" >
                                                    </div>`;
                                                }
                                                v+=`
                                                <div class="in-control sm">
                                                    <label>Tipo dado*</label>
                                                    <input type="text" data-field-param="col.type-${fOrder}" value="${def.col.type}" placeholder="VARCHAR">
                                                </div>
                                                <div class="in-control xsm">
                                                    <label>Tam</label>
                                                    <input type="text" data-field-param="col.length-${fOrder}" value="${def.col.length}" maxlength="3" class="number" placeholder="255">
                                                </div>
                                                <div class="in-control xsm">
                                                    <label>Not Null</label>
                                                    <input data-field-param="col.notNull-${fOrder}" ${notNull} type="checkbox" >
                                                </div>
                                                <div class="in-control">
                                                    <label>Valor Padrão</label>
                                                    <input data-field-param="col.defaultValue-${fOrder}" value="${def.col.defaultValue}" type="text" maxlength="3" class="number" placeholder="Default">
                                                </div>
                                                <div class="in-control lg">
                                                    <label>Comments</label>
                                                    <textarea rows="1"  data-field-param="col.comment-${fOrder}" style="width: 200px;" >${def.col.comment}</textarea>
                                                </div>
                                            </div>
                                            `;
                                            if(type !== "attribute"){
                                                v+=`
                                                <div class="fieldset-view">
                                                   
                                                <div class="in-control">
                                                    <label>Label*</label>
                                                    <input type="text" data-field-param="html.label-${fOrder}" value="${def.html.label}"  placeholder="Label">
                                                </div>
                                                
                                                <div class="in-control">
                                                    <label>Atributos</label>
                                                    <input type="text" data-field-param="html.attrs-${fOrder}" value="${def.html.attrs}" placeholder="id=seu_id, data-toggle=tooltip">
                                                </div>
                                               
                                                <div class="in-control">
                                                    <label>Validator</label>
                                                    <input type="text" data-field-param="html.validator-${fOrder}" value="${def.html.validator}" placeholder="required, email">
                                                </div>
                                                `;
                    }
                                            if(type==="select" || type==="radio"){
                                                v += `
                                                <div class="select-radio-box">
                                                    <div data-toggle-select="${fOrder}" class="toggle-select"> 
                                                        <span data-op="${fOrder}-op1" class="toggle-op selected">Entidade</span> 
                                                        <span data-op="${fOrder}-op2" class="toggle-op ">Estático</span> 
                                                    </div>
                                                    <div data-op-target="${fOrder}-op1" class="in-control xlg">
                                                        <div class="sel-entity">
                                                            <select onchange="$.getEntityFields(${fOrder},'value');" data-op-entity="${fOrder}"><option value="">Selecione uma Entidade</option></select>
                                                            <select onchange="$.getEntityFields(${fOrder},'label');" data-op-entity-f-value="${fOrder}"><option value="">Selecione um value</option></select>
                                                            <select data-op-entity-f-label="${fOrder}"><option value="">Selecione um label</option></select>
                                                        </div>
                                                    </div>
                                                    <div data-op-target="${fOrder}-op2" class="in-control xlg hide flex">
                                                        <span onclick="$.addStaticVal(${fOrder})">
                                                            <i class="icon-btn add fa fa-plus-circle"></i>
                                                        </span>
                                                        <g data-fOrder="${fOrder}"></g>
                                                    </div>
                                                </div>
                                            </div>
                                                `;
                                            }else if(type==="checkbox") {
                                                v += `
                                                <div class="select-radio-box">
                                                    <div data-toggle-select="${fOrder}" class="toggle-select"> 
                                                        <span data-op="${fOrder}-op1" class="toggle-op selected">Entidade</span> 
                                                        <span data-op="${fOrder}-op2" class="toggle-op ">Estático</span> 
                                                    </div>
                                                    <div data-op-target="${fOrder}-op1" class="in-control xlg">
                                                        <div class="sel-entity">
                                                            <select data-op-entity="${fOrder}"><option value="">Selecione uma Entidade</option></select>
                                                            <select data-op-entity-f-value="${fOrder}"><option value="">Selecione um Value</option></select>
                                                            <select data-op-entity-f-label="${fOrder}"><option value="">Selecione um Label</option></select>
                                                        </div>
                                                    </div>
                                                    <div data-op-target="${fOrder}-op2" class="in-control xlg hide flex">
                                                        <span onclick="$.addStaticCheckVal(${fOrder})">
                                                            <i class="icon-btn add fa fa-plus-circle"></i>
                                                        </span>
                                                        <g data-fOrder="${fOrder}"></g>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                                `;
                                            }else{
                                                v +=`</div>`;
                                            }
    
                                        v+=`
                               
                                    </div>
                                    <div class="box-actions">
                                        <a  data-action="${fOrder}-del" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
    
                        </div>
            `;
              if(type==="select" || type==="radio"){
                $.getEntities(fOrder);
              }
              
                return v;
            },
            dataValidate(obj){
                let {fields} = obj;
                let filterFields = fields.map(validate);
                let data = {...obj, fields: filterFields};
                console.log(data);
            },
            validate(el){
                if (el.fieldType === "checkbox"){
                    for(let ind in el.items){
                        delete el.items[ind].name;
                    }
                }
                return el;
            },
            addStaticVal(fOrder){
                console.log(objSecao);
                let index = objSecao.fields[fOrder-1].items.length+1;
                $(`g[data-fOrder=${fOrder}]`).append(`
                                            <div data-st-val="${index}" class="static-value-select">
                                                <input data-param-static-val="value-${fOrder}-${index}" type="text" placeholder="value">
                                                <input data-param-static-val="label-${fOrder}-${index}" type="text" placeholder="${index}º Label">
                                                <i onclick="$.removeStaticVal(${fOrder},${index})" class="icon-btn remove fa fa-minus-circle"></i>
                                            </div>`);
                // Adicionando novo item no objeto
                objSecao.fields[fOrder-1].items.push({value:"",label:""});
                $("[data-param-static-val]").unbind().bind("input",function(){
                    let p =$(this).data("param-static-val").split("-"),
                        chave=p[0],
                        fOrder = p[1],
                        index=p[2];
                    objSecao.fields[fOrder-1].items[index-1][chave]= $(this).val();
                });
    
            },
            addStaticCheckVal(fOrder){
                let index = $.fields[fOrder].lastIndex+=1;
    
                $(`g[data-fOrder=${fOrder}]`).append(`
                                            <div data-st-val="${index}" class="static-value-select">
                                                <input data-param-static-val="name-${fOrder}-${index}" type="text" placeholder="Name">
                                                <input data-param-static-val="value-${fOrder}-${index}" type="text" placeholder="${index}º Value">
                                                <input data-param-static-val="label-${fOrder}-${index}" type="text" placeholder="${index}º Label">
                                                <i onclick="$.removeStaticVal(${fOrder},${index})" class="icon-btn remove fa fa-minus-circle"></i>
                                            </div>`);
                // Adicionando novo item no objeto
                objSecao.fields[fOrder-1].items.push({name:"",value:"",label:""});
                $("[data-param-static-val]").unbind().bind("input",function(){
                    let p =$(this).data("param-static-val").split("-"),
                        chave=p[0],
                        fOrder = p[1],
                        index=p[2];
                    objSecao.fields[fOrder-1].items[index-1][chave]= $(this).val();
    
                });
    
            },
            removeStaticVal(fOrder,index){
                let i = index-1;
                $(`g[data-fOrder=${fOrder}] > [data-st-val=${index}]`).remove();
                let l = $(`g[data-fOrder=${fOrder}] div:last-child`).data("st-val");
                $.fields[fOrder].lastIndex= (l>=0)? l:0 ;
                console.log("Remover item"+i);
                objSecao.fields[fOrder-1].items.splice(i,1);
            },
            sendGenerate(){
                //$.dataValidate(objSecao);
                $.ajax({
                    url:objPage.url.url_page+"/create",
                    type: 'POST',
                    data:objSecao,
                    dataType:"JSON",
                    success:function(response){
                        if(response.status){
                            alert(response.msg);
                        }else{
                            alert(response.msg);
                        }
                    }
                });
            },
            loadDefValues(type){
              let def={};
              switch(type){
                  case "input":
                    def = { name:"titulo", 
                            html:{isOnGrid:true,label:"Título",attrs:"",validator:"required"},
                            col:{type:"VARCHAR",length:255,defaultValue:"",comment:""}
                          }
                  break;
                  case "select":
                    def = {name:"meu_select", 
                           html:{isOnGrid:true,label:"Meu Select",attrs:"",validator:"required"},
                           col:{type:"VARCHAR",length:255,defaultValue:"",comment:""},}
                  break;
                  case "radio":
                    def = {name:"meus_radios", html:{isOnGrid:true,label:"Meus Radios",attrs:"",validator:"required"},col:{type:"VARCHAR",length:255,defaultValue:"",comment:""}}
                  break;
                  case "checkbox":
                    def = {name:"meu_grupo_check", html:{isOnGrid:true,label:"Meu Grupo Checkbox",attrs:"",validator:"required"},col:{type:"VARCHAR",length:255,defaultValue:"",comment:""}}
                  break;
                  case "textarea":
                    def = {name:"texto", html:{isOnGrid:true,label:"Texto",attrs:"",validator:""},col:{type:"TEXT",length:"",defaultValue:"",comment:""}}
                  break;
                  case "date":
                    def = {name:"data", html:{isOnGrid:true,label:"Data",attrs:"",validator:""},col:{type:"date",length:"",defaultValue:"",comment:""}}
                  break;
                  case "time":
                    def = {name:"hora", html:{isOnGrid:true,label:"Hora",attrs:"",validator:""},col:{type:"time",length:"",defaultValue:"",comment:""}}
                  break;
                  case "image":
                    def = {name:"imagem", html:{isOnGrid:false,label:"Imagem",attrs:"",validator:""},col:{type:"VARCHAR",length:255,defaultValue:"",comment:""}}
                  break;
                  case "file":
                    def = {name:"arquivo", html:{isOnGrid:false,label:"Arquivo",attrs:"",validator:""},col:{type:"VARCHAR",length:255,defaultValue:"",comment:""}}
                  break;
              }
              return def;
            },
            getEntities(fOrder){
                $.ajax({
                    url:objPage.url.url_page+"/ajax",
                    type: 'POST',
                    data:{action:"load_entities"},
                    dataType:"JSON",
                    success:function(response){
                        if(response.status){
                            let op=`<option value="">Selecione uma entidade</option>`;
                            for(let o of response.data){op+=`<option value="${o.id_secao}">${o.id_group_file}</option>`;}
                            $(`[data-op-entity=${fOrder}]`).html(op);
                        }
                    }
                }); 
            },
            getEntityFields(fOrder,type){
                let idEntity = $(`[data-op-entity="${fOrder}"]`).val();
                if(idEntity !== ""){
                    $.ajax({
                        url:objPage.url.url_page+"/ajax",
                        type: 'POST',
                        data:{action:"load_entities_fields",idEntity},
                        dataType:"JSON",
                        success:function(response){
                            if(response.status){
                                let op=`<option value="">Selecione um ${type}</option>`;
                                for(let o of response.data){
                                    if(!(o.Field == "id" && type == "label" ))
                                    op+=`<option value="${o.Field}">${o.Field}</option>`;        
                                }
                                $(`[data-op-entity-f-${type}=${fOrder}]`).html(op);
                            }
                        }
                    }); 
                }else {
                    alert("Selecione uma entidade!");
                }
            },
            registerMainEvents: function(){ 
                $("button[data-add]").unbind('click').bind("click",function(e){
                    e.preventDefault();
                    let t=$(this).data("add");
                    if(t!=="periodo"){
                        let l = $(`#fields [data-field]:last-child`).data("field");
                         if(l === undefined || l === NaN){l=1;} else {l+=1;}
                        let def = $.loadDefValues(t);
                        $('#fields').append($.newControl(t,l,def));
                        //Atualizar o objeto aqui...(Adicionar Field)
                        let n = {
                            fieldType: t,
                            name: def.name,
                            html: { label: def.html.label, isOnGrid:def.html.isOnGrid,attrs:def.html.attrs,validator: def.html.validator,},
                            col: {type: def.col.type, length: def.col.length, notNull: false, defaultValue: def.col.defaultValue, comment: def.col.comment},
                            items: [],
                            entity:{type:"",id:"",fValue:"",fLabel:""}
                        }
                        objSecao.fields.push(n); // Field Vazio
                    }
                    $.registerSecondaryEvents();
                })
            },
            registerSecondaryEvents: function(){
                $("[data-toggle-select] [data-op]").unbind().bind("click",function(){
                    let op = $(this).data("op").split("-")[1],
                        fOrder = $(this).data("op").split("-")[0];
                    $(`[data-op^='${fOrder}-']`).removeClass('selected');
                    $(this).addClass('selected');
                    $(`[data-op-target^='${fOrder}-']`).addClass('hide');
                    $(`[data-op-target='${fOrder}-${op}']`).removeClass('hide');
                    if(op == "op2"){
                        $(`select[data-op-entity=${fOrder}],
                        select[data-op-entity-f-value=${fOrder}],
                        select[data-op-entity-f-label=${fOrder}]
                        `).val("");
                    objSecao.fields[fOrder -1].entity.type = "";
                    objSecao.fields[fOrder -1].entity.id = "";
                    objSecao.fields[fOrder -1].entity.fValue = "";
                    objSecao.fields[fOrder -1].entity.fLabel = "";
                    }else{
                        //Apagar dados dos campos estáticos.
                        $(`g[data-fOrder=${fOrder}]`).html("");
                        objSecao.fields[fOrder -1].items=[];
                    }
        
                });
                $("a[data-action]").unbind().bind("click",function(){
                    let a =$(this).data("action");
                    let fOrder = a.split("-")[0];
                    let action = a.split("-")[1];
                    switch(action){
                        case "del":
                            $(`[data-field=${fOrder}]`).remove();
                            //Atualizar o objeto aqui...(Remover Field)
                            objSecao.fields.splice(fOrder-1,1);
                            break;
                    }
                });
                $("[data-field-param]").unbind().bind("input",function(){
                    let p =$(this).data("field-param").split("-");
                    let fOrder = p[1];  // Indentificador de ordem de inserção (Field Id)
                        if(p[0].includes("."))
                        {
                            let parent = p[0].split(".")[0], // html, col etc...
                                child = p[0].split(".")[1]; // label,name, type etc...
                            if(child === "isOnGrid"){
                                objSecao.fields[fOrder-1].html.isOnGrid = $(this).prop("checked");
                            }else if(child === "notNull"){
                                objSecao.fields[fOrder-1].col.notNull = $(this).prop("checked");
                            }
                            else if(child === "type"){
                                objSecao.fields[fOrder-1].col.type = $(this).val().toUpperCase();
                                $(this).val(objSecao.fields[fOrder-1].col.type);
                            }else{
                                objSecao.fields[fOrder-1][parent][child] = $(this).val();
                            }
                        }
                        else
                        {
                            objSecao.fields[fOrder-1][p[0]] = $(this).val(); // Preenche campos no nó raiz do objeto
                        }
                });
                // EVENTOS PARA ENTIDADES
                $(".sel-entity select[data-op-entity]").unbind().bind("change",function(){
                    let fOrder = $(this).data("op-entity");
                    objSecao.fields[fOrder-1].entity.type="oneToMany";
                    objSecao.fields[fOrder-1].entity.id = $(this).val();
                });
                $(".sel-entity select[data-op-entity-f-value]").unbind().bind("change",function(){
                    let fOrder = $(this).data("op-entity-f-value");
                    objSecao.fields[fOrder-1].entity.fValue = $(this).val();
                });
                $(".sel-entity select[data-op-entity-f-label]").unbind().bind("change",function(){
                    let fOrder = $(this).data("op-entity-f-label");
                    objSecao.fields[fOrder-1].entity.fLabel = $(this).val();
                });
            }
        });
        // registrando eventos para os botões do modal.
        $.registerMainEvents();
    }); 
    $(function () {
        $.extend({
         loadCompSelect:function(data){
          $.ajax({
            url: objPage.url.url_page+'/ajax',
            type: 'POST',
            dataType: 'JSON',
            data:data,
            success: function (r) {
              if (r.status) {
                  let rt =r.data;
                  let couRt=Object.keys(rt).length;
                  let html =`<option value="">${(couRt)?'Escolha uma opção':'Nenhum resultado foi encontrado'}</option>`;
               if(data['action']=='load_menu'){
                   $('[data-form-group=menu]').removeClass('hide');
                for(let i in rt){     
                 html += `<option value="${i}">${rt[i]}</option>`;
                }
                $('select[name=menu]').html(html);
               }else if(data['action']=='load_submenu'){
                   if(couRt){
                    $('[data-form-group=submenu]').removeClass('hide');
                    for(let i in rt){     
                      html += `<option value="${i}">${rt[i]}</option>`;
                    }
                    $('select[name=submenu]').html(html);
                  }else{
                    $('[data-form-group=submenu]').addClass('hide');
                  }
               }
              } else {
                $.alertMessage('error', r.msg);
              }
              if(r.log){console.log(r.log)}
            },
            error:function(r){
             $.ajaxErros(r.responseJSON);
            },
          });
         }
        })
    });
    /*#################################### TRATAMENTO DOS EVENTOS ########################################### */
    $('select[name=tipo_secao]').change(function(){
      if($(this).val()){
          $.loadCompSelect({action:'load_menu'});
          objSecao.typeSection = $(this).val(); // Atribuindo o tipo de sessão ao objeto.
        }else{
          cMenu.val('').addClass('hide');
          cSubmenu.val('').addClass('hide');
      }
    });
    $('select[name=menu]').change(function(){
      let val= $(this).val();
      if(val){
          $.loadCompSelect({action:'load_submenu',id:val});
          objSecao.idMenu = val; // Atribuindo o ID do menu ao objeto.
      }
    });
    $('select[name=submenu]').change(function(){
      let val= $(this).val();
      if(val){
          objSecao.idSubmenu = val; // Atribuindo o ID do SUB MENU ao objeto.
      }
    });
    $('input[name=id_grupo]').on('input',function(){//Remove acentos e coloca sempre a primeira letra como maiúscula
      let val= $(this).val();
      val = val.charAt(0).toUpperCase()+val.slice(1);
      val = val.replace(/[áàãâª]/g,"a");
      val = val.replace(/[èéẽê]/g,"e");
      val = val.replace(/[íìĩî]/g,"i");
      val = val.replace(/[óòõôº]/g,"o");
      val = val.replace(/[úùûũ]/g,"u");
      val = val.replace(/[çÇ]/g,"c");
      val = val.replace(/\s/g,"");
      val = val.replace(/[^A-Za-z0-9]/g,"");
      val = val.replace(/_/g,"");
      if(val){
          objSecao.idGroup = val; // Atribuindo o ID do SUB MENU ao objeto.
      }
      //Criando nome da tabela
      $("[name=table_name]").val("api_"+camelCaseToUnderline(val)+"s");
      objSecao.tableName = "api_"+camelCaseToUnderline(val)+"s";
      
      $(this).val(val);
      $("[data-info-text]").html(val);
    });
    $('input[name=table_name]').on('input',function(){//Remove acentos e coloca espacos como underline
        let val= $(this).val();
        val = val.replace(/[áàãâª]/g,"a");
        val = val.replace(/[èéẽê]/g,"e");
        val = val.replace(/[íìĩî]/g,"i");
        val = val.replace(/[óòõôº]/g,"o");
        val = val.replace(/[úùûũ]/g,"u");
        val = val.replace(/[çÇ]/g,"c");
        val = val.replace(/\s/g,"_");
        val = val.replace(/[^A-Za-z\_0-9]/g,"");
        val = val.toLowerCase();
        if(val){
            objSecao.tableName = val; // Atribuindo o ID do SUB MENU ao objeto.
        }
        $(this).val(val);
      });
    $('input[name=nome_secao]').on('input',function(){
      let val= $(this).val();
          val = val.replace(/[\/\?\\]/g,"");
      if(val)
          objSecao.sectionName = val; // Atribuindo o ID do SUB MENU ao objeto.
    
        $(this).val(val);
    });
    $('[data-checkbox=group_type]').parent().parent().click(function(){
        objSecao.isEntity = false;
        objSecao.isSection = false;
        let arType=[],
        cTypeSec=$('[data-form-group=tipo_secao]'),
        cMenu=$('[data-form-group=menu]'),
        cSubmenu=$('[data-form-group=submenu]'),
        cNameSection=$('[data-form-group=nome_secao]');
        setTimeout(()=>{
            $('input[data-checkbox=group_type]:checked').each(function(_,e){
                if($(e).is(':checked'))
                arType.push($(e).val());
            });
    
             if(arType.includes('entity')){
                 objSecao.isEntity = true;
            }
             if(arType.includes('section')){
                 objSecao.isSection = true;
              cTypeSec.removeClass('hide');
              cNameSection.removeClass('hide');
             }else{
              cTypeSec.addClass('hide');
              cMenu.addClass('hide');
              cSubmenu.addClass('hide');
              cNameSection.addClass('hide');
             }
            if(arType.includes('entity')&&arType.includes('section')){
    
            }
        },10);
    });
    $('input[name=id_grupo]').on('focus',function(){ $("table.view-info").removeClass('hide');});
    $('input[name=id_grupo]').on('blur',function(){ $("table.view-info").addClass('hide');});
    function camelCaseToUnderline(str) {
        let matches = str.match(/[A-Z0-9]/g);
        let pieces = str.split(/[A-Z0-9]/);
        let r = "", c = 0;
        for (let p of pieces) {
            if (c != 0) {
                if (r == "") {
                    r += matches[c - 1].toLowerCase() + p;
                } else {
                    r += "_" + matches[c - 1].toLowerCase() + p;
                }
            }
            else {
                r += p;
            }
            c++;
        }
        return r;
    }
});


