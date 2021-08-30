"use strict";
// ABAS RANKING

var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
        dadosPergsResp = [],
        pUniq = ['FECHADA_RESPOSTA_UNICA', 'DICOTOMICA', 'NET_PROMOTER_SCORE', 'SEMI_ABERTA', 'ESCALA_LIKERT', 'ESCALA_ITEMIZADA', 'ESCALA_INTENCAO_COMPRA', 'AVALIACAO_SATISFACAO'],
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
                var saida = "#"; //mask.substring(0,1);
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
                                numeroTrocado = trocaNumero[1] + trocaNumero[0]; // Inverte
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

function msg(obj, msg, dis) {
        //  console.log(msg)
        obj.innerHTML = '';
        setTimeout(function () {
                obj.innerHTML = msg;
                obj.style.display = dis;
        }, 400);
}
/*function enviarPesquisa() {
 var obj = document.querySelector('.skiplink');
 obj.disabled = true;
 obj.value = 'Enviando...';
 var form_data = new FormData(document.getElementsByName('form_pesquisa')[0]), ajax = new XMLHttpRequest();
 form_data.append('acao', 'responder_pesquisa');
 // form_data.append('tPesquisa', tPesquisa);
 form_data.append('tUsuario', tUsuario);
 ajax.onload = function (e) {
 var r = JSON.parse(this.responseText);
 if (r.status) {
 $('html,body').animate({scrollTop: 0 + $('.cabecalho').offset().top}, 'slow');
 document.getElementById('box-pesquisa').style.display = 'none';
 document.getElementById('box-encerramento').style.display = '';
 //   setTimeout(function () {
 //    location.href = location.href;
 //   }, 5000);
 alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
 } else {
 alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
 obj.disabled = true;
 }
 obj.value = 'Enviar respostas';
 };
 ajax.open('POST', base + 'request/switchAjax.php', true);//faz a requisicao dos dados, via post
 ajax.send(form_data);//envia o form
 return false;
 }*/

function salvarContato(ident) {
        var ident = (ident) ? ident : '';
        var seletorForm = document.getElementsByName('form' + ident)[0];
        var ajax = new XMLHttpRequest(), form_data = new FormData(seletorForm);
        if (!form_data.get('nome')) {
                alertaPadrao.returnMensagem('Preecha o campo nome ' + ident, 'top', 'infor');
                return false;
        }

        if (!form_data.get('email')) {
                alertaPadrao.returnMensagem('Preecha o campo e-mail ' + ident, 'top', 'infor');
                return false;
        }
        if (!filter.test(form_data.get('email'))) {
                alertaPadrao.returnMensagem('É esperado um endereço de email válido. ', 'top', 'infor');
                return false;
        }

        form_data.append('id', seletorForm.getAttribute('data-contato'));
        form_data.append('lista', codLista);
        ajax.onload = function (e) {
                var r = JSON.parse(this.responseText);
                if (r.status) {
                        alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                        if (!seletorForm.getAttribute('data-contato')) {
                                setTimeout(function () {
                                        location.reload();
                                }, 2300);
                        }
                } else {
                        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                }
        };
        ajax.open('POST',baseUrl + 'lista/contato/salvar', true); //faz a requisicao dos dados, via post
        ajax.send(form_data); //envia o form
}

function deletarContato(cod) {
        $('<div></div>').appendTo('body')
                .html('<div><h6>Deseja deletar essa contato?</h6></div>')
                .dialog({
                        autoOpen: true,
                        'modal': true,
                        'title': 'Alerta',
                        'zIndex': 10000,
                        //  'autoOpen': true,
                        width: 400,
                        buttons: [
                                {
                                text: "Ok",
                                    click: function () {
                                        var ajax = new XMLHttpRequest(), form_data = new FormData();
                                        form_data.append('cod', cod);
                                        form_data.append('lista', codLista);
                                        form_data.append('acao', 'deletar_contato');
                                        ajax.onload = function (e) {
                                                var r = JSON.parse(this.responseText);
                                                if (r.status) {
                                                        alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                                        setTimeout(function () {
                                                                location.reload();
                                                        }, 3000);
                                                } else {
                                                        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                                                }
                                        };
                                        ajax.open('POST', baseUrl + 'lista/contaeditar', true); //faz a requisicao dos dados, via post
                                        ajax.send(form_data); //envia o form
                                        $(this).dialog("close");
                                   }
                                },
                                {
                                        text: "Cancel",
                                        click: function () {
                                                $(this).dialog("close");
                                        }
                                }
                        ]
                });
}

function importarContatos(obj) {
        var file = obj.files[0],
                reader = new FileReader();
                // console.log(file.name.split('.').slice(-1)[0])
        // if (file.type == 'application/vnd.ms-excel') {
        if (file.name.split('.').slice(-1)[0]== 'csv') {
                reader.readAsText(file, 'ISO-8859-1');
                reader.onload = function (resFile) {
                        var csv = resFile.target['result'];
                        // console.log(csv)
                        var csvLinhas = csv.split(/\r\n|\n/);
                        var headers = csvLinhas[0].split(','),
                                dadosContatos = [{}],
                                self = this,
                                dSeg = [],
                                statusSegmentacao = false;
                        var cab = headers[0].split(';');
                        ///
                        if (csvLinhas.length) {
                                if (cab[0] != 'NOME') {
                                        self.geral.alertToast({ msg: 'Lista fora do padão' });
                                        return false;
                                }
                                // if (cab.length > 12) {
                                //   self.geral.alertToast({ msg: 'Só é permitido até 13 colunas' });
                                //   return false;
                                // }
                                // let n = 0, forInicio = (cab[0] == 'NOME') ? 1 : 0;
                                var n = 0;
                                var totalColunas = cab.length;
                                if (totalColunas > 2) {
                                        statusSegmentacao = true;
                                        for (var iSeg = 3; iSeg < totalColunas; iSeg++) {
                                                if (cab[iSeg]) {
                                                        dSeg[iSeg] = cab[iSeg];
                                                }
                                        }
                                }
                                ///
                                for (var i = 1; i < csvLinhas.length; i++) {
                                        var linha = csvLinhas[i].split(',');
                                        if (linha.length) {
                                                var dados = linha[0].split(';');
                                                if (dados[0]) {
                                                        dadosContatos[n] = {
                                                                'contatos': { nome: (dados[0]), email: dados[1], telefone: dados[2], codigo_lista: this.codigo_lista },
                                                                'segmentacaoOp': []
                                                        };
                                                        if (statusSegmentacao) {
                                                                var _arAux = [];
                                                                for (var iSeg = 3; iSeg <= totalColunas; iSeg++) {
                                                                        if (dados[iSeg] && dSeg[iSeg]) {
                                                                                _arAux[dSeg[iSeg]] = dados[iSeg];
                                                                        }
                                                                        dadosContatos[n]['segmentacaoOp'] = _arAux;
                                                                }
                                                        }
                                                        n++;
                                                }
                                        }
                                }
                        }

                        var totalContatos = dadosContatos.length;
                        if (totalContatos) {
                                $('<div></div>').appendTo('body')
                                        .html('<div><h6>O arquivo selecionado foi aberto e contém ' + totalContatos + ' contato(s).\n Deseja realizar o  cadastro?</h6></div>')
                                        .dialog({
                                                autoOpen: true,
                                                modal: true, title: 'Importar contatos', zIndex: 10000,
                                                //autoOpen: true,
                                                width: 400,
                                                buttons: [
                                                        {
                                                                text: "Ok",
                                                                click: function () {
                                                                        enviarContato();
                                                                        document.querySelector('[name=importar]').value = '';
                                                                        $(this).dialog("close");
                                                                }
                                                        },
                                                        {
                                                                text: "Cancel",
                                                                click: function () {
                                                                        document.querySelector('[name=importar]').value = '';
                                                                        $(this).dialog("close");
                                                                }
                                                        }
                                                ]
                                        });
                        } else {
                                alertaPadrao.returnMensagem('O arquivo está vazio', 'top', 'infor');
                        }
                        async function enviarContato() {
                                //        self.loading = self.geral.LoadingCustom({ msg: 'Importando...', spinner: 'bubbles', tempo: 900000 });
                                var idsSementecao = [];
                                /////salvar segmentação
                                document.getElementById("importarContatos").disabled = true;
                                document.getElementById("importarContatos").innerHTML = 'Importando... 1/' + totalContatos;
                                if (statusSegmentacao) {
                                        var dados = { codigo: self.usuario_codigo, codigo_lista: self.codigo_lista, segmentacao: dSeg }
                                        var ajax = new XMLHttpRequest(), form_data = new FormData();

                                        form_data.append('lista', codLista);
                                        form_data.append('segmentacao', (dSeg));
                                        ajax.onload = function (e) {
                                                var r = JSON.parse(this.responseText);
                                                if (r.status) {
                                                        idsSementecao = r.ids;
                                                }
                                                lista();
                                        };
                                        ajax.open('POST', baseUrl+'lista/contato/importar/segmentacao', true); //faz a requisicao dos dados, via post
                                        ajax.send(form_data, true); //envia o form
                                } else {
                                        lista();
                                }

                                async function lista() {
                                        /// salvar contatos da lista 
                                        var totalEnviado = 1, dicionario = {'alteracao':"Alteração realizada",'cadastro':'Cadastro realizado'};
                                        for (var i = 0; i < parseInt(totalContatos); i++) {
                                                var dados = dadosContatos[i];
                                                if (statusSegmentacao) {
                                                    var _arAux = [], indAux = 0;
                                                    for (var iS in dados['segmentacaoOp']) {
                                                        if (dados['segmentacaoOp'][iS]) {
                                                            _arAux[indAux++] = { 'id_seg': idsSementecao[iS], op_titulo: dados['segmentacaoOp'][iS] };
                                                        }
                                                    }
                                                        dados['segmentacaoOp'] = _arAux;
                                                }
                                                dados['codigo_lista'] = self.codigo_lista;
                                                //  self.loading.data.content = `Importando...${totalEnviado++} / ${totalContatos}`;
                                                if (totalEnviado == totalContatos) {
                                                        dados['ultimoContato'] = true;
                                                } else {
                                                        dados['ultimoContato'] = false;
                                                }
                                                document.getElementById("importarContatos").innerHTML = 'Importando... ' + totalEnviado + '/' + totalContatos;
                                                await $.ajax({
                                                        url: baseUrl + 'lista/contato/importar/contato',
                                                        type: 'post',
                                                        async: true,
                                                        dataType: 'json',
                                                        data: { dados: dados, lista: codLista},
                                                        success: function (r) {
                                                                totalEnviado += 1;
                                                                if (dados['ultimoContato'] && r.status) {
                                                                        document.getElementById("importarContatos").disabled = false;
                                                                        document.getElementById("importarContatos").innerHTML = 'Impotar contatos';
                                                                   alertaPadrao.returnMensagem('Importação finalizada', 'top', 'sucesso');
                                                                   setTimeout(function () {
                                                                        location.reload();
                                                                   }, 9000);
                                                                }
                                                                if (r.status) {
                                                                        $('.list-group').html('<li class="list-group-item list-group-item-success">'+(i+1)+' - '+dicionario[r.tipo]+' com sucesso "' + dados['contatos']['nome'] + '" </li>'+$('.list-group').html());
                                                                } else {
                                                                        $('.list-group').html('<li class="list-group-item list-group-item-danger">'+(i+1)+' - Não foi possível importar "' + dados['contatos']['nome'] + '"</li>' + $('.list-group').html());
                                                                }
                                                        }

                                                })


                                        }
                                }
                        }
                }
        } else {
                alertaPadrao.returnMensagem('O arquivo selecionado não é do tipo "CSV"', 'top', 'infor');
                document.querySelector('[name=importar]').value = '';
                return false
        }

}

function montOpSegmentacao(idSeg,data){
       var html='';
        for(var d of data){
                html += `<div class="form-group">
                <input placeholder="Digite o nome da nova opção" data-segmentacao="${idSeg}" value="${d.titulo}" style="width:89%;" type="text" name="mTituloOp${d.id}" class="form-control" />
                <button title="Editar" type="button" style="float: right;width:6%;margin-top: -34px;" onclick="cadEditSegOpcoes('mTituloOp${d.id}',${d.id})" class="btn btn-primary btn-circle">
                <i class="fa fa-check"></i>
                </button>
                <button title="Deletar" type="button" style="float: right;width:6%;margin-top: -33px;margin-right: 25px;" onclick="deletarSegOpcao(${idSeg},${d.id})" class="btn btn-danger btn-circle">
                <i class="fa fa-times"></i>
                </button>
                </div>`;
        }
     return html;
}

function modalEditarSegmentacao(cod, titulo) {
        $('#modalFormSegmentacao').modal();
        var form_data = new FormData(),
                ajax = new XMLHttpRequest();
        form_data.append('lista', codLista);
        form_data.append('id', cod);
        ajax.onload = function (e) {
                var r = JSON.parse(this.responseText);
                document.querySelector('[role=codSeg]').value = cod;
                document.querySelector('[role=mTituloSeg]').value = r.dados.titulo;
                document.querySelector('[name=tituloNovaOp]').setAttribute('data-segmentacao', cod);
                $('[data-ajax=opcoes]').html(montOpSegmentacao(cod,r.dados.opcoes));
        };
        ajax.open('POST', baseUrl + 'lista/contato/segmentacao/busca', true); //faz a requisicao dos dados, via post
        ajax.send(form_data); //envia o form 
}

function cadEditSegmentacao(nome) {
        var form_data = new FormData(document.querySelector('[name=' + nome + ']')),
                ajax = new XMLHttpRequest();
        if (!form_data.get('titulo')) {
                alertaPadrao.returnMensagem('Preencha o nome do segmento', 'top', 'infor');
                return false;
        }
        form_data.append('lista', codLista);
        ajax.onload = function (e) {
                var r = JSON.parse(this.responseText);
                if (r.status) {
                        alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                        if (!form_data.get('cod')) {
                                setTimeout(function () {
                                        location.reload();
                                },3000);
                        }
                } else {
                        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                }
        };
        ajax.open('POST', baseUrl + 'lista/contato/segmentacao/salvar', true); //faz a requisicao dos dados, via post
        ajax.send(form_data); //envia o form
}

function deletarSegmentacao(cod) {
        $('<div></div>').appendTo('body')
                .html('<div><h6>Deseja deletar esse segmento?</h6></div>')
                .dialog({
                        autoOpen: true,
                        'modal': true,
                        'title': 'Alerta',
                        'zIndex': 10000,
                        width: 400,
                        buttons: [
                                {
                                        text: "Ok",
                                        click: function () {
                                                var ajax = new XMLHttpRequest(), form_data = new FormData();
                                                form_data.append('id', cod);
                                                form_data.append('lista', codLista);
                                                ajax.onload = function (e) {
                                                        var r = JSON.parse(this.responseText);
                                                        if (r.status) {
                                                                alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                                                setTimeout(function () {
                                                                        location.reload();
                                                                }, 3000);
                                                        } else {
                                                                alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                                                        }
                                                };
                                                ajax.open('POST', baseUrl + 'lista/contato/segmentacao/delete', true); //faz a requisicao dos dados, via post
                                                ajax.send(form_data); //envia o form
                                                $(this).dialog("close");
                                        }
                                },
                                {
                                        text: "Cancel",
                                        click: function () {
                                                $(this).dialog("close");
                                        }
                                }
                        ]
                });
}



function cadEditSegOpcoes(selector, cod = '') {
        var form_data = new FormData(),
                ajax = new XMLHttpRequest();
        var objOp = document.querySelector('[name=' + selector + ']');
        if (!objOp.value) {
                alertaPadrao.returnMensagem('Preencha o nome da opção.', 'top', 'infor');
                return false;
        }
        form_data.append('lista', codLista);
        form_data.append('id', cod);
        form_data.append('segmentacao', objOp.getAttribute('data-segmentacao'));
        form_data.append('titulo', objOp.value);
        ajax.onload = function (e) {
                var r = JSON.parse(this.responseText);
                if (r.status) {
                        if (!cod) {
                                objOp.value = '';
                                $('[data-ajax=opcoes]').html(montOpSegmentacao(objOp.getAttribute('data-segmentacao'),r.dados.opcoes));
                        }
                        alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                } else {
                        alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                }
        };
        ajax.open('POST',baseUrl + 'lista/contato/segmentacao/opcao/salvar', true); //faz a requisicao dos dados, via post
        ajax.send(form_data); //envia o form 
}

function deletarSegOpcao(seg, cod) {
        /*$('<div></div>').appendTo('body')
         .html('<div><h6>Deseja deletar esse opção?</h6></div>')
         .dialog({
         autoOpen: true, 'modal': true, 'title': 'Alerta', 'zIndex': 99000, width: 400,
         buttons: [
         {
         text: "Ok",
         click: function () {*/
        var conf = confirm("Deseja deletar esse opção?");
        if (conf) {
                var ajax = new XMLHttpRequest(), form_data = new FormData();
                form_data.append('cod', cod);
                form_data.append('seg', seg);
                form_data.append('lista', codLista);
                ajax.onload = function (e) {
                        var r = JSON.parse(this.responseText);
                        if (r.status) {
                                alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                $('[data-ajax=opcoes]').html(montOpSegmentacao(seg,r.dados.opcoes));
                        } else {
                                alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                        }
                };
                ajax.open('POST', baseUrl + 'lista/contato/segmentacao/opcao/delete', true); //faz a requisicao dos dados, via post
                ajax.send(form_data); //envia o form
        }
        /*     $(this).dialog("close");
         }
         },
         {
         text: "Cancel",
         click: function () {
         $(this).dialog("close");
         }
         }
         ]
         });*/
}


function abasContatos(aba) {
        var arUrl = window.location.href.split('/'), novaUrl = [];
        for (var i in arUrl) {
                if (i < arUrl.length - 1) {
                        novaUrl[i] = arUrl[i];
                }
        }
        window.history.pushState("object or string", "Title", novaUrl.join('/') + '/' + aba);
}

//function proximaPerg(posicaoTela) {
// var noValid = false, ancora = false;
//// var dadosTela=valid[posicaoTela];
// encadearPerguntas();
//// console.log('valid=', valid);
// for (var indDados1 in valid) {
//  if (parseInt(indDados1) <= parseInt(posicaoTela)) {
//   if (valid[indDados1]['visivel']) {
//    //   console.log(parseInt(indDados1), parseInt(posicaoTela))
//    for (var indPerg in valid[indDados1]['perg']) {
//     var ob = valid[indDados1]['perg'][indPerg];
//     //  console.log('indDados1=', indDados1)
//     if (ob.visivel) {
//      if (parseInt(ob.obrig)) {
////       console.log('posicaoTela =', ob)
//       var retornoValid = validPesquisa(ob);
////       console.log(retornoValid)
////       if (noValid && !ancora) {
//       if (!retornoValid.status) {
//        msg(retornoValid.objMsg, retornoValid.msg, '');
//        $('html,body').animate({scrollTop: 0 + $(retornoValid.objMsg.parentElement).offset().top}, 'slow');
//        return {status: false};
//       } else {
//        msg(retornoValid.objMsg, '', 'none');
//       }
//
//      } else {
////return true;
//      }
//     }
////**
//    } /// for
//   } // if
//  } // if
// }//for
//
////// *********************************
//// console.log(" + + + ", proximasPergs)
// if (proximasPergs <= 0) {
//  enviarPesquisa();
//  return {status: false};
// } else {
//  var breakWhile = false, proxTela = parseInt(posicaoTela);
//  while (!breakWhile) {
//   proxTela += 1;
////   console.log('proxTela', proxTela, valid[proxTela])
//   if (typeof valid[proxTela] != 'undefined' && valid[proxTela]['visivel']) {
//    breakWhile = true;
//    numPgVisisvel += 1;
//    $(".atual").html(numPgVisisvel);
//    return {status: true, posicao: proxTela};
////   } else if (proxTela >= totalPerguntas) {
//////   console.log('totalPerguntas ', totalPerguntas)
////    // validPesquisa(proxTela);
////    breakWhile = true;
////    return false;
//   }
//  }
// }
//}// fim function 
//
//
//function validPesquisa(ob) {
// var objMsg = document.getElementById(ob.t + ob.i);
// //console.log(' vpp ',ob)
// if (ob.t.substring(0, 6) == 'ABERTA' || ob.e == 'DATA') {///////////////////////////////////////////////////////////////
//  var idText = (ob.t == 'DATA') ? 'DATA' + ob.i : 'ABERTA' + ob.i;
//  objMsg = document.getElementById(idText);
//  var aberto_value = document.getElementsByName('per__' + ob.t + '__' + ob.i)[0].value;
//  if (!aberto_value) {
//   // msg(objMsg, 'Preecha esse campo', '');
////   noValid = true;
//   return {status: false, msg: 'Preecha esse campo', objMsg: objMsg};
//  }
//  if (ob.e == 'EMAIL') {
//   if (!filter.test(aberto_value)) {
////    msg(objMsg, 'É esperado um endereço de email válido.', '');
////    noValid = true;
//    return {status: false, msg: 'É esperado um endereço de email válido.', objMsg: objMsg};
//   }
//  }
// } else if (pUniq.indexOf(ob.t) > -1) { //fechada///////////////////////////////////////////////////////////////////////////
//  var checkRadio = vRadio('per__' + ob.t + '__' + ob.i, ob.i);
//  if (!checkRadio) {
////   msg(objMsg, 'Selecione uma opção acima', '');
////   noValid = true;
//   return {status: false, msg: 'Selecione uma opção acima', objMsg: objMsg};
//  }
//  if (ob.t == 'SEMI_ABERTA' && dadosPergsResp[ob.i] == 'outros') {///////////////////////////////////////////////////////////////////////////////////
////         console.log("dadosPergsResp[ob.i]", document.getElementsByName('per_outros_' + ob.i)[0])
//   if (!document.getElementsByName('per_outros_' + ob.i)[0].value) {
////    msg(objMsg, 'Preencha o campo aberto', '');
////    return {status: false};
//    return {status: false, msg: 'Preencha o campo aberto', objMsg: objMsg};
//   }
//  }
//  if (ob.t == 'NET_PROMOTER_SCORE' && ob.st_justificativa) {
//   if ((ob.e != 0 && ob.e != null) && !document.getElementsByName('per_justificativa_' + ob.i)[0].value) {
////    msg(objMsg, 'Preencha o campo obrigatório', '');
////    return {status: false};
//    return {status: false, msg: 'Preencha o campo obrigatório', objMsg: objMsg};
//   }
//  }
// } else if (ob.t == 'MATRIZ') {//////////////////////////////////////////////////////////////////////////////////////////////////////
//// if(ob.e=='UNICA'){
//  [].forEach.call(ob.mat, function (item) {
//   if (!vRadio(item.campo, '')) {
////    msg(objMsg, 'Selecione nas opções acima', '');
////    noValid = true;
//    return {status: false, msg: 'Selecione nas opções acima', objMsg: objMsg};
//   }
//  });
////   }
// } else if (ob.t == 'FECHADA_RESPOSTA_MULTIPLA') {/////////////////////////////////////////////////////////////////////////////////////////////
//  var checkbox = [], dResp = [];
//  [].forEach.call(document.querySelectorAll('[data-checkbox=ckeck' + ob.i + ']'), function (item, i) {
//   if (item.checked) {
//    dResp[i] = item.value;
//    checkbox[i] = true;
//   }
//  });
//  if (dResp.length) {
//   dadosPergsResp[ob.i] = dResp.toString();
//  }
//
//  if (checkbox.length < 1) {
////   msg(objMsg, 'Selecione uma ou mais opções acima', '');
////   noValid = true;
//   return {status: false, msg: 'Selecione uma ou mais opções acima', objMsg: objMsg};
//  }
// }
// return {status: true, msg: '', objMsg: objMsg};
//}
//
//function encadearPerguntas() {
// var totalPs = 0, pergsDisp = true;
// proximasPergs = 0;
// for (var indDados in valid) {
//  var pergsDisp = false;
//  for (var indPerg in valid[indDados]['perg']) {
//   var perg = valid[indDados]['perg'][indPerg];
//   validPesquisa(perg)
//   var encadeamento = perg['encadeamento'];
//   if (encadeamento['codigo_resposta'] && encadeamento['codigo_pergunta_relacao']) {
//    var arAux = [];
////    console.log(perg, dadosPergsResp)
//    if (typeof dadosPergsResp[encadeamento['codigo_pergunta_relacao']] != 'undefined') {
//     var verifResp = dadosPergsResp[encadeamento['codigo_pergunta_relacao']].split(","),
//             _arEncad = encadeamento['codigo_resposta'];
//     _arEncad = _arEncad.split(',')
////     console.log('_arEncad = ', _arEncad)
////     console.log('verifResp = ', verifResp)
//     for (var i in _arEncad) {
//      if (verifResp.indexOf(_arEncad[i]) >= 0) {
//       arAux.push(_arEncad[i]);
//      }
//     }
//    }
////    console.log('4 = ', arAux.length)
//    ////
//    if (arAux.length >= 1) {
//     document.querySelector('li[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = '';
//     pergsDisp = true;
//     valid[indDados]['perg'][indPerg]['visivel'] = true;
//    } else {
//     valid[indDados]['perg'][indPerg]['visivel'] = false;
//     document.querySelector('li[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = 'none';
//    }
//   } else {
//    valid[indDados]['perg'][indPerg]['visivel'] = true;
//    document.querySelector('li[data-id-perg=idPerg' + indDados + indPerg + ']').style.display = '';
//    pergsDisp = true;
//   }
//  }//
//  // verificar ultima resp
//  valid[indDados]['visivel'] = pergsDisp;
////  console.log(pergsDisp)
//  if (pergsDisp) {
//   totalPs += 1;
//  }
//  if (parseInt(indDados) > parseInt(numPg)) {
//   if (valid[indDados]['visivel']) {
//    proximasPergs += 1;
//   }
//  }
//
// }
// if (proximasPergs > 0) {
//  document.querySelector('.skiplink').value = 'CONTINUAR'
// } else {
//  document.querySelector('.skiplink').value = 'ENVIAR'
// }
//// console.log('valid ', valid)
//// console.log('proximasPergs ', proximasPergs)
//// console.log('aaaaa', document.querySelector("p[data-total=perg]"))
// totalPerguntas = totalPs;
// $("p[data-total=perg]").html(totalPs);
//}
//
//
//function vRadio(campo, cod) {
// var checkRadio = false;
// [].forEach.call(document.querySelectorAll('[name=' + campo + ']'), function (item) {
//  if (item.checked) {
//   if (cod) {
//    dadosPergsResp[cod] = item.value;
//   }
//   return  checkRadio = true;
//  }
// });
// return checkRadio;
//}
//function vCheckbox(obj, num) {
// var checkbox = [], n = 0;
// [].forEach.call(document.querySelectorAll('[data-checkbox=' + obj.getAttribute('data-checkbox') + ']'), function (item, i) {
//  if (item.checked)
//   checkbox[n++] = true;
// });
// if (checkbox.length > num) {
//  alertaPadrao.returnMensagem("Só é possível marcar até " + num + " opções", 'top', 'info', 3000);
//  obj.checked = false;
// }
//}
