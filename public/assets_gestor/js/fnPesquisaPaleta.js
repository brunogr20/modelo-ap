
var temas={
    'SERENIDADE':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#002171',
        cor_bg_titulo:'#0D47A1',cor_ft_titulo:'#FFFFFF',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#5472D3',cor_ft_pergunta:'#002171',cor_ft_descricao:'#0D47A1',
        cor_bg_botao_voltar:'#5472D3',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#002171',cor_ft_botao_avancar:'#FFFFFF',
    },
    'ESPERANÇA':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#2E7D32',
        cor_bg_titulo:'#2E7D32',cor_ft_titulo:'#FFFFFF',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#60AD5E',cor_ft_pergunta:'#005005',cor_ft_descricao:'#2E7D32',
        cor_bg_botao_voltar:'#60AD5E',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#005005',cor_ft_botao_avancar:'#FFFFFF',
    },
    'PACIÊNCIA':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#00695C',
        cor_bg_titulo:'#00695C',cor_ft_titulo:'#FFFFFF',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#439889',cor_ft_pergunta:'#003D33',cor_ft_descricao:'#00695C',
        cor_bg_botao_voltar:'#439889',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#003D33',cor_ft_botao_avancar:'#FFFFFF',
    },
    'CONFIANÇA':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#D50000',
        cor_bg_titulo:'#D50000',cor_ft_titulo:'#FFFFFF',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#FF5131',cor_ft_pergunta:'#9B0000',cor_ft_descricao:'#D50000',
        cor_bg_botao_voltar:'#FF5131',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#9B0000',cor_ft_botao_avancar:'#FFFFFF',
    },
    'RESPEITO':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#616161',
        cor_bg_titulo:'#616161',cor_ft_titulo:'#FFFFFF',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#8E8E8E',cor_ft_pergunta:'#373737',cor_ft_descricao:'#616161',
        cor_bg_botao_voltar:'#8E8E8E',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#373737',cor_ft_botao_avancar:'#FFFFFF',
    },
    'ALEGRIA':{
        cor_bg_fundo:'#FFFFFF', cor_ft_fundo:'#FFB300',
        cor_bg_titulo:'#FFB300',cor_ft_titulo:'#C68400',
        cor_bg_caixa:'#FFFFFF',cor_bd_caixa:'#FFE54C',cor_ft_pergunta:'#C68400',cor_ft_descricao:'#FFB300',
        cor_bg_botao_voltar:'#FFE54C',cor_ft_botao_voltar:'#FFFFFF',
        cor_bg_botao_avancar:'#C68400',cor_ft_botao_avancar:'#FFFFFF',
    },
}

function setTema(t){
    var paleta = (t)?temas[t]:dadosPaleta;
    document.querySelector(`[data-tema=titulo]`).innerHTML=(t)?t:nomeTema;
    document.querySelector(`[name=tema]`).value=(t)?t:nomeTema;
    if(!Object.keys(paleta).length){
        paleta=temas['SERENIDADE'];
        document.querySelector(`[data-tema=titulo]`).innerHTML='SERENIDADE';
        document.querySelector(`[name=tema]`).value='SERENIDADE';
    }
    for(var k in paleta){   
        if(['cor_bg_fundo','cor_bg_titulo','cor_bg_caixa','cor_bg_botao_voltar','cor_bg_botao_avancar'].includes(k)){
            document.querySelector(`[data-color=${k}`).style.background= paleta[k];
        }else if(k=='cor_bd_caixa'){
           document.querySelector(`[data-color=${k}]`).style.border="1px solid "+paleta[k];
        }else{
            document.querySelector(`[data-color=${k}]`).style.color= paleta[k];
        }  
        document.querySelector(`[name=${k}]`).value=paleta[k];  
    }
}

function openPaleta(obj){
  obj.querySelector('input[type=color]').click();
}

function aplicarCor(obj){
    var e='',v='';
    if(obj.getAttribute('name')=='cor_bd_caixa'){
     e='border';
     v="1px solid "+obj.value;
    }else{
     e = obj.getAttribute('data-element');
     v=obj.value;
    }
    var o =  document.querySelector(`[data-color=${obj.getAttribute('name')}]`);
    if(o){
       o.style[e]=v;
     }
}
    
