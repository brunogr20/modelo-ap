"use strict";
// ABAS RANKING
// var alertaPadrao = new Alert();
var pg={atual:1,total:1};
var localizacao = { latitude: '', longitude: '' };
document.addEventListener('DOMContentLoaded', function () {
  navigator.geolocation.getCurrentPosition(function (position) {
    localizacao.latitude = position.coords.latitude;
    localizacao.longitude = position.coords.longitude;
  });
});
var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
  dadosPergsResp = [],
  pUniq = ['FECHADA_RESPOSTA_UNICA','ESCALA_LIKERT_0_10', 'NET_PROMOTER_SCORE','AVALIACAO_SATISFACAO',],
  proximasPergs = 0;
function mascaraCep(e, src, mask) {
  var _TXT = "";
  if (window.event) {
    _TXT = e.keyCode;
  } else if (e.which) {
    _TXT = e.which;
  }
  if (_TXT == "") {
    return true;
  }
  if (_TXT > 47 && _TXT < 58) {
    var i = src.value.length;
    var saida = "#";//mask.substring(0,1);
    var texto = mask.substring(i);
    //alert(texto +" - "+ texto.substring(0,1))
    if (texto.substring(0, 1) != saida) {
      src.value += texto.substring(0, 1);
      if (texto.substring(0, 1) == ")") {
        src.value += " ";
      }
      if (src.value.length == 14 && mask.indexOf("(") > -1) { // Serve para a mÃ¡scara de numeros com 9 dÃ­gitos
        var trocaNumero, numeroTrocado;
        trocaNumero = src.value.substring(9, 11);
        numeroTrocado = trocaNumero[1] + trocaNumero[0];// Inverte
        src.value = src.value.replace(trocaNumero, numeroTrocado);
      }
    }
    return true;
  } else {
    if (_TXT != 8) {
      return false;
    } else {
      return true;
    }
  }
}

var nPosicaoTela=0;

function msg(obj, msg, dis) {
  if(msg){
    obj.style.display = '';
    var elemento=$(`<div class="alert alert-danger mgs-validacao" >`,{html:'<button type="button" class="close">&times;</button>'}).prependTo('#'+obj.getAttribute('id'));
    elemento.text(msg);
    setTimeout(function () {
      elemento.hide();
        }, 5000);
    elemento.click(function(){
      elemento.hide();
    });
  }
}
function enviarPesquisa() {
  var obj = document.querySelector('.bt-continuar');
  obj.disabled = true;
  obj.innerHTML = 'Enviando...';
  var form_data = new FormData(document.getElementsByName('form_pesquisa')[0]), ajax = new XMLHttpRequest();
  // form_data.append('tPesquisa', tPesquisa);
  form_data.append('latitude', localizacao.latitude);
  form_data.append('longitude', localizacao.longitude);
  form_data.append('idPesquisa', idPesquisa);
  form_data.append('tPesquisa', tPesquisa);
  form_data.append('tUsuario', tUsuario);
  ajax.onload = function (e) {
    var r = JSON.parse(this.responseText);
    if (r.status) {
      $('html,body').animate({ scrollTop: 0 + $('.barra-questionario-topo').offset().top }, 'slow');
      document.querySelector('.corpo-questionario').innerHTML='';
      document.querySelector('.corpo-questionario').style.display = 'none';
      document.querySelector('.encerramento-questionario').style.display = '';
      //   setTimeout(function () {
      //    location.href = location.href;
      //   }, 5000);
      // alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
    } else {
      alert(r.msg);
      obj.disabled = true;
    }
    obj.innerHTML = 'ENVIAR';
  };
  ajax.open('POST', baseUrl + 'questionario/salvar', true);//faz a requisicao dos dados, via post
  ajax.send(form_data);//envia o form
  return false;
}

function proximaPerg(posicaoTela) {
  var noValid = false, ancora = false;
  // var dadosTela=valid[posicaoTela];
  for (var indDados1 in valid) {
    if (parseInt(indDados1) <= parseInt(posicaoTela)) {
      if (valid[indDados1]['visivel']) {
        for (var indPerg in valid[indDados1]['perg']) {
          var ob = valid[indDados1]['perg'][indPerg];
          if (ob.visivel) {
            if (parseInt(ob.obrig)) {
              var retornoValid = validPesquisa(ob);
              //       if (noValid && !ancora) {
              if (!retornoValid.status) {
                msg(retornoValid.objMsg, retornoValid.msg, '');
                $('html,body').animate({ scrollTop: 0 + $(retornoValid.objMsg.parentElement).offset().top }, 'slow');
                return { status: false };
              } else {
                msg(retornoValid.objMsg, '', 'none');
              }

            } else {
              //return true;
            }
          }
          //**
        } /// for
      } // if
    } // if
  }//for
  // encadeamento
  nPosicaoTela = posicaoTela;
  encadearPerguntas();
  //// *********************************
  // console.log(" + + + ", proximasPergs)
  if (proximasPergs <= 0) {
    enviarPesquisa();
    return { status: false };
  } else {
    var breakWhile = false, proxTela = parseInt(posicaoTela);
    while (!breakWhile) {
      proxTela += 1;
      //   console.log('proxTela', proxTela, valid[proxTela])
      if (typeof valid[proxTela] != 'undefined' && valid[proxTela]['visivel']) {
        breakWhile = true;
        numPgVisisvel += 1;
        pg.atual=numPgVisisvel;
        $(".atual").html(numPgVisisvel);
        return { status: true, posicao: proxTela };
        //   } else if (proxTela >= totalPerguntas) {
        ////   console.log('totalPerguntas ', totalPerguntas)
        //    // validPesquisa(proxTela);
        //    breakWhile = true;
        //    return false;
      }
    }
  }
}// fim function 


function validPesquisa(ob) {
  var objMsg = document.getElementById(ob.t + ob.i);
  // console.log(' vpp ',objMsg)
  if (ob.t.substring(0, 6) == 'ABERTA' || ob.e == 'DATA') {///////////////////////////////////////////////////////////////
    var idText = (ob.t == 'DATA') ? 'DATA' + ob.i : 'ABERTA' + ob.i;
    objMsg = document.getElementById(idText);
    var aberto_value = document.getElementsByName('PER__' + ob.t + '__' + ob.i)[0].value;
    if (!aberto_value) {
      return { status: false, msg: 'Preecha esse campo', objMsg: objMsg };
    }
    if (ob.e == 'DATA') {
     var isData=true;
     for(var n of aberto_value.split('')){
      if(!['0','1','2','3','4','5','6','7','8','9','/'].includes(n)){
        isData=false;
        break;
      }
     }
     if(isData){
       var dia = parseInt(aberto_value.substr(0,2)),mes = parseInt(aberto_value.substr(3,2));
       isData=(aberto_value.length!=10)?false:true;
       isData=(dia<1||dia>31)?false:true;
       isData=(mes<1||mes>12)?false:true;
     }
     if(!isData){
      return { status: false, msg: 'É esperado uma data válida.', objMsg: objMsg };
     }
    }
    if (ob.e == 'NUMERICO') {
      var isNumerico=true;
      for(var n of aberto_value.split('')){
        if(!['0','1','2','3','4','5','6','7','8','9'].includes(n)){
         isNumerico=false;
         break;
        }
      } 
       if(!isNumerico){
         return { status: false, msg: 'É esperado um valor numérico válido.', objMsg: objMsg };
       }
    }
    if (ob.e == 'MONETARIO') {
     var tMoney='',isMoney=true;
     for(var n of aberto_value.split('')){
       if(!['0','1','2','3','4','5','6','7','8','9','.',','].includes(n)){
        isMoney=false;
        break;
       }
       tMoney+=n.replace('.','');
     } 
     tMoney=tMoney.replace(/,/g, ".");
      if(!isMoney){
        return { status: false, msg: 'É esperado um valor em reais válido.', objMsg: objMsg };
      }
    }else if (ob.e == 'EMAIL') {
      if (!filter.test(aberto_value)) {
        return { status: false, msg: 'É esperado um endereço de email válido.', objMsg: objMsg };
      }
    }
  } else if (pUniq.indexOf(ob.t) > -1) { //fechada///////////////////////////////////////////////////////////////////////////
    var checkRadio = vRadio('PER__' + ob.t + '__' + ob.i, ob.i);
    if (!checkRadio) {
      return { status: false, msg: 'Selecione uma opção acima', objMsg: objMsg };
    }
    if (ob.t == 'SEMI_ABERTA' && dadosPergsResp[ob.i] == 'outros') {///////////////////////////////////////////////////////////////////////////////////
      if (!document.getElementsByName('per_outros_' + ob.i)[0].value) {
        return { status: false, msg: 'Preencha o campo aberto', objMsg: objMsg };
      }
    }

    if (ob.t == 'NET_PROMOTER_SCORE' &&  ob.st_justificativa == 'sim') {
      if (document.getElementsByName('PER_JUSTIFICATIVA_' + ob.i)) {
        if ((ob.e != 0 && ob.e != null) && !document.getElementsByName('PER_JUSTIFICATIVA_' + ob.i)[0].value) {
          return { status: false, msg: 'Preencha o campo obrigatório', objMsg: objMsg };
        }
      }
    }
  } else if (ob.t == 'FECHADA_RESPOSTA_MULTIPLA' || ob.t =='MULTIPLA_ESCOLHA_IMAGEM') {/////////////////////////////////////////////////////////////////////////////////////////////
    var checkbox = [], dResp = [];
    dadosPergsResp[ob.i]='';
    [].forEach.call(document.querySelectorAll('[data-checkbox=ckeck' + ob.i + ']'), function (item, i) {
      if (item.checked) {
        dResp[i] = item.value;
        checkbox[i] = true;
      }
    });
    if (dResp.length) {
      dadosPergsResp[ob.i] = dResp.toString();
    }

    if (checkbox.length < 1) {
      //   msg(objMsg, 'Selecione uma ou mais opções acima', '');
      //   noValid = true;
      return { status: false, msg: 'Selecione uma ou mais opções acima', objMsg: objMsg };
    }
  } else if (ob.t == 'MATRIZ') {//////////////////////////////////////////////////////////////////////////////////////////////////////
    // if(ob.e=='UNICA'){
    [].forEach.call(ob.mat, function (item) {
      if (!vRadio(item.campo, '')) {
        //    msg(objMsg, 'Selecione nas opções acima', '');
        //    noValid = true;
        return { status: false, msg: 'Selecione nas opções acima', objMsg: objMsg };
      }
    });
    //   }
  } else if (ob.t == 'FECHADA_RESPOSTA_MULTIPLA') {/////////////////////////////////////////////////////////////////////////////////////////////
    var checkbox = [], dResp = [];
    [].forEach.call(document.querySelectorAll('[data-checkbox=ckeck' + ob.i + ']'), function (item, i) {
      if (item.checked) {
        dResp[i] = item.value;
        checkbox[i] = true;
      }
    });
    if (dResp.length) {
      dadosPergsResp[ob.i] = dResp.toString();
    }

    if (checkbox.length < 1) {
      return { status: false, msg: 'Selecione uma ou mais opções acima', objMsg: objMsg };
    }
  }
  return { status: true, msg: '', objMsg: objMsg };
}

function encadearPerguntas() {
  var totalPs = 0, pergsDisp = true;
  proximasPergs = 0;
  for (var indDados in valid) {
    var pergsDisp = false;
    for (var indPerg in valid[indDados]['perg']) {
      var perg = valid[indDados]['perg'][indPerg];
      validPesquisa(perg)
      var encadeamento = perg['encadeamento'];
      if (encadeamento['id_resposta'] && encadeamento['id_pergunta_relacao']) {
        var arAux = [];
        if (typeof dadosPergsResp[encadeamento['id_pergunta_relacao']] != 'undefined') {
          var verifResp = dadosPergsResp[encadeamento['id_pergunta_relacao']].split(","),
          _arEncad = encadeamento['id_resposta'];
          _arEncad = _arEncad.split(',');
          for (var i in _arEncad) {
            if (verifResp.indexOf(_arEncad[i]) >= 0) {
              arAux.push(_arEncad[i]);
            }
          }
        }
            ////
            if(arAux.length >= 1){
              document.querySelector('div[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = '';
              pergsDisp = true;
              valid[indDados]['perg'][indPerg]['visivel'] = true;
            }else{
              valid[indDados]['perg'][indPerg]['visivel'] = false;
              document.querySelector('div[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = 'none';
        }
      } else {
        valid[indDados]['perg'][indPerg]['visivel'] = true;
        document.querySelector('div[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = '';
        pergsDisp = true;
      }
    }//
    // verificar ultima resp
    valid[indDados]['visivel'] = pergsDisp;
    if (pergsDisp) {
      totalPs += 1;
    }
    if (parseInt(indDados) > parseInt(numPg)) {// numPg  está no arquivo questionario.js
      if (valid[indDados]['visivel']){
        proximasPergs += 1;
      }
    }

  }
 
  // if (proximasPergs<=0||totalPs<=nPosicaoTela+2) {
  //   document.querySelector('.bt-continuar').innerHTML = 'ENVIAR';
  // } else {
  //   document.querySelector('.bt-continuar').innerHTML  = 'CONTINUAR <i class="fa fa-arrow-right fa-fw" ></i>';
  // }

  totalPerguntas = totalPs;
  pg.total=totalPs;
  // console.log('encad ',pg);
  textBtEnviar();
  $("[data-total=perg]").html(totalPs);
}

function textBtEnviar(){
  if (pg.atual>=pg.total) {
    document.querySelector('.bt-continuar').innerHTML = 'ENVIAR';
  } else {
    document.querySelector('.bt-continuar').innerHTML  = 'CONTINUAR <i class="fa fa-arrow-right fa-fw" ></i>';
  }
}

function vRadio(campo, cod) {
  var checkRadio = false;
  [].forEach.call(document.querySelectorAll('[name=' + campo + ']'), function (item) {
    if (item.checked) {
      if (cod) {
        dadosPergsResp[cod] = item.value;
      }
      return checkRadio = true;
    }
  });
  return checkRadio;
}
function vCheckbox(obj, num) {
  var checkbox = [], n = 0;
  [].forEach.call(document.querySelectorAll('[data-checkbox=' + obj.getAttribute('data-checkbox') + ']'), function (item, i) {
    if (item.checked)
      checkbox[n++] = true;
  });
  if (checkbox.length > num) {
    alertaPadrao.returnMensagem("Só é possível marcar até " + num + " opções", 'top', 'info', 3000);
    obj.checked = false;
  }
}
