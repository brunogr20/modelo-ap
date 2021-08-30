export const templates = 
{
    institucional:[ /* Campos para seção Instituciona */
        {   
            fieldType: "input",
            name: "meu_input",
            html: {label: "Meu Input Renderizado",isOnGrid:true,attrs: "meu-id=22,data-whatever=1",validator: "required",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "...", comment: "Comentários Input aqui..."},
            items: [],
            entity:{type:"",id:"",fValue:"",fLabel:""}
        },
        {
            fieldType: "select",
            name: "meu_select_renderizado",
            html: {label: "Meu Select Renderizado",isOnGrid:true,attrs: "meu-id=23,data-whatever=2",validator: "required",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "25", comment: "Comentários Select aqui..."},
            items: [],
            entity:{type:"oneTwoMany",id:"14",fValue:"id",fLabel:"titulo"}
        },
    ],
    noticia:[ /* Campos para seção Notícia */
        {   
            fieldType: "input",
            name: "titulo",
            html: {label: "Título",isOnGrid:true,attrs: "",validator: "required",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "", comment: ""},
            items: [],
            entity:{type:"",id:"",fValue:"",fLabel:""}
        },
        {   
            fieldType: "input",
            name: "sub_titulo",
            html: {label: "Sub Título",isOnGrid:true,attrs: "",validator: "",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "", comment: ""},
            items: [],
            entity:{type:"",id:"",fValue:"",fLabel:""}
        },
        {   
            fieldType: "textarea",
            name: "texto",
            html: {label: "Conteúdo",isOnGrid:false,attrs: "",validator: "",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "", comment: ""},
            items: [],
            entity:{type:"",id:"",fValue:"",fLabel:""}
        },
        {   
            fieldType: "image",
            name: "imagem",
            html: {label: "Imagem",isOnGrid:false,attrs: "",validator: "",},
            col: {type: "VARCHAR", length: "255", notNull: false, defaultValue: "", comment: ""},
            items: [],
            entity:{type:"",id:"",fValue:"",fLabel:""}
        },
        {
            fieldType: "select",
            name: "meu_select_renderizado",
            html: {label: "Meu Select Renderizado",isOnGrid:true,attrs: "meu-id=23,data-whatever=2",validator: "required",},
            col: {type: "VARCHAR", length: "255", notNull: true, defaultValue: "25", comment: "Comentários Select aqui..."},
            items: [],
            entity:{type:"oneTwoMany",id:"14",fValue:"id",fLabel:"titulo"}
        },
    ],
}
export let objSecao = {// Inicialização do objeto que vai gerar a seção
    isEntity:false,//true
    isSection:false,
    idGroup:"",//Grupo arquivos ou nome entidade.
    tableName:"api_",//Nome da tabela que será criada para a entidade.
    sectionName:"", // Nome da seção que aparece no menu...
    typeSection:"",//Grid, Relatorio,
    sectionPosition:"",
    idMenu: "",
    idSubmenu: "",
    hasStatus:true,
    hasOrder:false,
    fields: []
};