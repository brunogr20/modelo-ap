var tipo_resposta = [
// { text: 'Pergunta com duas opções', valor: 'DICOTOMICA', icon: 'add' },
{ text: 'NPS - Net Promoter Score', valor: 'NET_PROMOTER_SCORE', icon: 'add' },
{ text: 'Fechada com resposta múltipla (RM)', valor: 'FECHADA_RESPOSTA_MULTIPLA', icon: 'add' },
{ text: 'Fechada com resposta única (RU)', valor: 'FECHADA_RESPOSTA_UNICA', icon: 'add' },
{ text: 'Escala numérica', valor: 'ESCALA_LIKERT_0_10', icon: 'add', status: true },
{ text: 'Avaliação de satisfação', valor: 'AVALIACAO_SATISFACAO', icon: 'add' },
{ text: 'Aberta', valor: 'ABERTA', icon: 'add' },
{ text: 'Texto informativo', valor: 'MOSTRAR_TEXTO', icon: 'add' },
// { text: 'Escala de likert', valor: 'ESCALA_LIKERT', icon: 'add' },
// { text: 'Semi-aberta', valor: 'SEMI_ABERTA', icon: 'add' },
// { text: 'Matriz', valor: 'MATRIZ', icon: 'add' },
// { text: 'Ranking', valor: 'RANKING', icon: 'add' },
// { text: 'Fechada com imagem multipla (RM)', valor: 'MULTIPLA_ESCOLHA_IMAGEM', icon: 'add', status:true},
// { text: 'Fechada com imagem única (RU)', valor: 'UNICA_ESCOLHA_IMAGEM', icon: 'add', status:true},
// { text: 'Escala itemizada', valor: 'ESCALA_ITEMIZADA', icon: 'add' },
// { text: 'Escala de intenção de compra', valor: 'ESCALA_INTENCAO_COMPRA', icon: 'add' },
// { text: 'Cronômetro', valor: 'CRONOMETRO', icon: 'add' },
    // { text: 'Localização', valor: 'LOCALIZACAO', icon: 'add' },
    // { text: 'Bater foto', valor: 'FOTO', icon: 'add' },
    // { text: 'Scaner de QR code', valor: 'SCANER_QR_CODE', icon: 'add' },
    // { text: 'Scaner de código de barras', valor: 'SCANER_CODIGO_BARRAS', icon: 'add' },
], pergunta_atual = gUrl(),
    nova_pergunta = 0;
if (pergunta_atual > perguntasTotal) {
    pergunta_atual = perguntasTotal;
    setarUrl(pergunta_atual);
} else if (pergunta_atual < 1) {
    pergunta_atual = 1;
    setarUrl(pergunta_atual);
}
var arUlr= location.href.split('/');

function mtCamposDinamic(campo, numPergunta, numero, titulo, cod,imagem='') {
    if(campo=='imagem'){
//         var c = `<div role='resp_dinamic_${campo}${numPergunta}'  style='width:170px;float:left;margin-left:10px;'
//                      data-idresp="${campo}${numPergunta}${numero}" data-resp="${cod}"  data-numero="${numero}">
//         <div class="thumbnail">
//             <div class="box-del-imagem">
//                 <label> Imagem ${numero+1}</label>`;
//                 if (numero > 1) {
//                 c += `<span type="button"  onclick="deletarResposta('${campo}',${numPergunta},${numero})">X</span>`;
//                 }
//                 c += ` </div>
//             <img data-img="img-${numPergunta}${numero}" accept="image/png, image/jpeg" src="${(imagem)?imagem:base+'assets/imgs/frame1.png'}"
//                             alt="Imagem" style="height:130px;width:150px;margin:auto;width:100%" >
//             <span>160x130px</span>
//             <div class="perg-imagem-bt">
//             <input type="file" style="display:none"  id="file-${numPergunta}${numero}" onchange="changeFileImg(${numPergunta},${numero})"/>
//             <input type="hidden" data-resp="${cod}" value="${(imagem)?imagem:''}" name="${campo}${numero}" id="${campo}${numPergunta}${numero}" />
//             <span type="button" data-bt="addFile${numPergunta}${numero}" onclick="addFileImg(${numPergunta},${numero})" >${(imagem)?'Editar':'Adicionar'}</span>
//             </div>
//             <div class="caption">
//              <label for="perg1">Descrição:</label>
//              <input value="${(titulo)?titulo:''}" name="desc-img-${numPergunta}${numero}" type="text"  class="form-control">
//             </div>
//         </div>
//     </div>`;  
// return c;
    }else{
    var nomeCanpo = campo.toLowerCase().replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    // console.log('numero = ',`${nomeCanpo} ${numero + 1}`)
    var c = `<div role='resp_dinamic_${campo}${numPergunta}' data-numero="${numero}" data-idresp="${campo}${numPergunta}${numero}" >
    <label for="${campo}${numero}" > ${nomeCanpo} ${numero + 1}: </label>
     <input value="${titulo}" style="width:${(numero > 1) ? 93 : 100}%;" data-resp="${cod}"  name="${campo}${numero}" type="text" id="${campo}${numero}" class="form-control" />`;
    if (numero > 1) {
        c += `<button type="button" onclick="deletarResposta('${campo}',${numPergunta},${numero})" style="width:4%;float:right;margin-top:-34px;" class="btn btn-primary btn-danger" >X</button>`;
    }
    return c += `</div>`;
}
}

function mtRespostas(d, idL) {
    var campos = '', tipo = d['tipo_pergunta'], idL;
      if (tipo == 'DICOTOMICA') {
        campos += `<div class="form-group" >
        <label for="selecEntrada${idL}" >Opções de entrada</label>
         <select  name="tipo_resp_entrada" class="form-control" id="selecEntrada${idL}" >
         <option value="">Escolha uma opção</option>    
         <option value='MASC_FEMIN' ${(d['tipo_resp_entrada'] == 'MASC_FEMIN') ? 'selected' : ''}>Masculino - Feminino</option>
         <option value='SIM_NAO' ${(d['tipo_resp_entrada'] == 'SIM_NAO') ? 'selected' : ''}>Sim - Não</option>
         <option value='VERD_FALSO' ${(d['tipo_resp_entrada'] == 'VERD_FALSO') ? 'selected' : ''}>Verdadeiro - Falso</option>
         </select>
         </div>`;
    } else if (tipo == 'ABERTA') {
        campos += `<div class="form-group form-row" >
            <div class="col" >
            <label for="selecEntrada${idL}" >Tipo de caracter*:</label>
             <select  onchange="mostrarQuadro(this.value,${idL})" name="tipo_resp_entrada" class="form-control" id="selecEntrada${idL}" >
             <option value='' >Escolha uma opção </option>
             <option value='DATA' ${(d['tipo_resp_entrada'] == 'DATA') ? 'selected' : ''}>Data</option>
             <option value='EMAIL' ${(d['tipo_resp_entrada'] == 'EMAIL') ? 'selected' : ''} >E-mail</option>
             <option value='MONETARIO' ${(d['tipo_resp_entrada'] == 'MONETARIO') ? 'selected' : ''} >Monetário</option>
             <option value='NUMERICO' ${(d['tipo_resp_entrada'] == 'NUMERICO') ? 'selected' : ''}>Numérico</option>
             <option value='TEXTO' ${(d['tipo_resp_entrada'] == 'TEXTO') ? 'selected' : ''} >Texto</option>
             </select>
             </div>`;
        var mostarDivQuadro = (d['tipo_resp_entrada'] == 'DATA' || d['tipo_resp_entrada'] == 'TEXTO' || d['tipo_resp_entrada'] == 'EMAIL') ? '' : 'none';
        campos += `<div class="col"  >
                    <div style='display:${mostarDivQuadro}' id='divQuadro${idL}'>
                        <label for="selecQuadro${idL}" >
                        Quadro*<spam  data-tooltip="quad${idL}" title="Os quadros são utilizados para agrupar as perguntas e possibilitar a exibição nos relatórios em formato de tabelas/quadros. Ex: quadro de informações pessoais"><i class="fas fa-question-circle"></i><spam>:
                          <script>
                          $('[data-tooltip=quad${idL}]').tooltip();
                          </script>
                        </label>
                        <select name="quadro" class="form-control" id="selecQuadro${idL}" >
                        <option value=''  ${(!d['quadro']) ? 'selected' : ''}>Padrão </option>
                        <option value='1' ${(d['quadro'] == '1') ? 'selected' : ''}>Quadro 1</option>
                        <option value='2' ${(d['quadro'] == '2') ? 'selected' : ''}>Quadro 2</option>
                        <option value='3' ${(d['quadro'] == '3') ? 'selected' : ''}>Quadro 3</option>
                        <option value='4' ${(d['quadro'] == '4') ? 'selected' : ''}>Quadro 4</option>
                        <option value='5' ${(d['quadro'] == '5') ? 'selected' : ''}>Quadro 5</option>
                        </select>
                    </div>
                  </div>
             </div>`;
             var mostarDivEmail = (d['tipo_resp_entrada'] == 'EMAIL')?'':'none';
             campos += `<div class="form-group form-row" >
                <div class="col" >
                    <div class="form-check" style="margin-bottom:5px;display:${mostarDivEmail};" id='divEmailEntrada${idL}'>
                        <input type="checkbox" name="entrada" ${d['entrada']=='sim'?'checked':''} class="form-check-input" value="sim" id="checkEntrada${idL}">
                        <label class="form-check-label" for="checkEntrada${idL}">Enviar e-mail de agradecimento</label>
                    </div>
                </div>
                <div class="col" >
                </div>
             </div>`;
    } else if (['FECHADA_RESPOSTA_MULTIPLA', 'FECHADA_RESPOSTA_UNICA', 'SEMI_ABERTA',
        'ESCALA_INTENCAO_COMPRA', 'ESCALA_LIKERT', 'ESCALA_ITEMIZADA'].indexOf(tipo) > -1) {
        var dados = (d['respostas'].length) ? d['respostas'] : [{ id: '', titulo: '',imagem:'' }, { id: '', titulo: '',imagem:'' }],
            arBoxDinamic = [];
            for (var i in dados) {
                arBoxDinamic[i] = mtCamposDinamic('resposta', idL, parseInt(i), dados[i]['titulo'], dados[i]['id']);
            }      
        campos += `<div role='box_dinamic_resposta${idL}' >
         ${fnHtmlExibicaoAleatoria(idL,d['resposta_randomica'])}
        ${fnMontRespostaMultEscol2Col(idL,arBoxDinamic)}
        </div>`;
        if (tipo == 'SEMI_ABERTA') {
            campos += `<div class="form-group" >
            <label for="semiAb${idL}" > Resposta ${dados.length}: </label>
              <input value="Outras" disabled type="text" id="semiAb${idL}" class="form-control" />
            </div>`;
        }
        campos += `<div style="display: flex;">
                      <button type="button" onclick="addResposta('resposta',${idL});"  class="btn btn-secondary font-weight-bold " style="margin:auto">Adicionar resposta</button>
                   </div> `;
    } else if (tipo == 'MATRIZ') {
    //     var linhas = (d['matriz']['LINHA'].length) ? d['matriz']['LINHA'] : [{ codigo: '', titulo: '' }, { codigo: '', titulo: '' }],
    //         colunas = (d['matriz']['COLUNA'].length) ? d['matriz']['COLUNA'] : [{ codigo: '', titulo: '' }, { codigo: '', titulo: '' }];
    //     campos += `<div class="panel-body">
    //     <ul class="nav nav-tabs">
    //         <li class="active"><a href="#linha${idL}" data-toggle="tab" aria-expanded="true">Linhas</a></li>
    //         <li class=""><a href="#coluna${idL}" data-toggle="tab" aria-expanded="false">colunas</a></li>
    //     </ul>
    //     <div class="tab-content">
    //         <div class="tab-pane fade active in" id="linha${idL}"><br/>
    //         <div role='box_dinamic_linha${idL}' >`;
    //     for (var i in linhas) {
    //         campos += mtCamposDinamic('linha', idL, parseInt(i), linhas[i]['titulo'], linhas[i]['codigo']);
    //     }
    //     campos += `</div>
    //     <button type="button" onclick="addResposta('linha',${idL});" style="width:30%;margin:auto" class="btn btn-secondary btn-sm btn-block">Adicionar resposta</button>
    //     </div>
    //     <!-- //  ---   ///////  ---  //-->
    //  <div class="tab-pane fade" id="coluna${idL}"><br/>
    //  <div role='box_dinamic_coluna${idL}' >`;
    //     for (var i in colunas) {
    //         campos += mtCamposDinamic('coluna', idL, parseInt(i), colunas[i]['titulo'], colunas[i]['codigo']);
    //     }
    //     campos += `</div>
    //     <button type="button" onclick="addResposta('coluna',${idL});" style="width:30%;margin:auto" class="btn btn-secondary btn-sm btn-block">Adicionar resposta</button>
    //     </div>
    //     </div>
    // </div>`;
    } else if (tipo == 'UNICA_ESCOLHA_IMAGEM' || tipo == 'MULTIPLA_ESCOLHA_IMAGEM') {
        // var dados = (d['respostas'].length) ? d['respostas'] : [{ codigo: '', titulo: '',imagem:'' }, { codigo: '', titulo: '',imagem:'' }];

        // var boxImg = `<div style="width:100%" class="tab-pane " id="imagem${idL}"><br/>
        //           <div role="box_dinamic_imagem${idL}" >`;
        //                 for (var i in dados) {
        //                     boxImg += mtCamposDinamic('imagem', idL, parseInt(i),dados[i]['titulo'],  dados[i]['codigo'],dados[i]['imagem']);
        //                 }
        //     boxImg +=`</div>
        //     <div style="width:100%;clear:both">
        //        <button type="button" onclick="addResposta('imagem',${idL});" style="width:40%;margin:auto" class="btn btn-secondary btn-sm btn-block">Adicionar resposta</button>
        //     <div>
        //  </div>`;
        //  campos += boxImg;//`<div role='box_dinamic_resposta${idL}' >${boxImg}</div>`;
    } else if (tipo == 'AVALIACAO_SATISFACAO') {
        campos += `<nav class="nav img-avaliacao-satisfacao" style="width:414px;margin:auto;">
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wMlQxNzoxOTozNy0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wMlQxNzoxOTozNy0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjMmQ3NmVmNC00OTU5LTI1NDAtYjM3Mi01OWIxZDQzNmMwOTgiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDpmNWRkZWMwZC1mODg0LTYwNGUtYTc1ZC1iOGVjNzQzMDM1MGEiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowZDlmYjc4Zi01N2EwLWIxNDQtOTJkNy1hMDM0ZjUwZDVhN2IiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjBkOWZiNzhmLTU3YTAtYjE0NC05MmQ3LWEwMzRmNTBkNWE3YiIgc3RFdnQ6d2hlbj0iMjAyMC0wNi0wMlQxNzoxNjo0My0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpjMmQ3NmVmNC00OTU5LTI1NDAtYjM3Mi01OWIxZDQzNmMwOTgiIHN0RXZ0OndoZW49IjIwMjAtMDYtMDJUMTc6MTk6MzctMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7AhIRXAAAhVUlEQVR4nO2deZhcR3mv36qz9jL7phlJljS2bGMLG5BsIGaLgRASJywmIZgQIIADiUngBnJzubkJgfuE65jAtQkECKsvOGAbQhIcY7P5OobgDVtehGRbsq1lZqRZerpnejtLVf6o7lHPImmmp2eRZ97naan7TJ/Tdc6v1q+++kporVlnbSFXOgHrLD/roq9B1kVfg6yLvgZZF30Nsi76GmRd9DXIuuhrkHXR1yDroq9B1kVfg6yLvgZZF30Nsi76GmRd9DXIuuhrELuek15yQ0+j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" />
         <!-- <img src="data:image/png;base64,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" />
     --->
          Otímo
          </a>
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
          <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wMlQxNzoyMDowNi0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wMlQxNzoyMDowNi0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjMzVmNGI3OC04OWNmLWVmNGYtYjA5YS1kYmZlNzEyMTdmYzkiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDphODYxZDQyNi02ODVkLWFlNDEtYTQxNi04YTIxNWE3NDBmMjQiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo5MjgyZjgxMS03MjdlLTA5NGQtYWUyMS1jZmI3Njg1MzgzNDkiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjkyODJmODExLTcyN2UtMDk0ZC1hZTIxLWNmYjc2ODUzODM0OSIgc3RFdnQ6d2hlbj0iMjAyMC0wNi0wMlQxNzoxNjo0My0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpjMzVmNGI3OC04OWNmLWVmNGYtYjA5YS1kYmZlNzEyMTdmYzkiIHN0RXZ0OndoZW49IjIwMjAtMDYtMDJUMTc6MjA6MDYtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6xiQwFAAAdYElEQVR4nO2deZAlR33nP1lZVe/u+5wZzWhGM2gGjYQQiMsCjAALMOIwCFgbLwavdlcIiGWxZW/gAIRxxEKYDbPCLNYiYcJr7mMlRIAWWUJgDhkkhEDoGo00M5rp+3p3HZm5f+R7rX49Z79+r4/p/kZ0dFW9V1m/V9/8Zf7yl7/8pTDGsImNBWe1BdjEymOT9A2ITdI3IDZJ34DYJH0DYpP0DYhN0jcgNknfgNgkfQNik/QNiE3SNyA2Sd+A2CR9A2KT9A2ITdI3IDZJ34Bwm7npHR95SavlaCWSwLOAncBWYC8wBAwAfu2at+ieEaAKjAOjwGHgidrfw7VraxJf+MgPl3xPU6SvMXQC+4FXA5cB5wC7llhG1yk+mwAOAfcD3wJ+g60U6xbrlfQs8Drg94CXAuee7gZtDItDw4QQOEKc7tb+2t9zgf8AFIC7gLuBb2ArxLrCeiP9+cAfAG8Dti/+UGlNrDRag0EATxPquxJPSiztBhBEcUxVaUTtCoAxBiEcpOPgSoEjDEI0VJYctsK9DrgeuA34MnBLi39r27BeSL8CuBa4cuFFYwxKG8JYEStDwkuQSabpyvh0ZlxyaUlXxiXlCxI++K6lV0qFA8RKUY0giBTVMCRfipkrRcyUYvKlCqXAEKsECBdfgpQGgWFB45DFVsC3YZv9TwNfAvIr9F6awlon/Qrgz4BX1C8IbFNdDWOMkaSTPlv7sgx3J+jv8hjsculICTwvxpUaRwgcUWvea/pfH7Q4QoIQGAPGpDDGYIwgiANmixUm5wImZiuMzpaZzBtKlQSaFMYIPFfhNLYA+4HPAtcBHwc+D0Qr8ZKWirVK+n7gw8Cb6xcEEGtNJdRIIRnq7mbXcIadQ0kGuhxyKQ1CoVQZpUFrQaAtoSfH4j7eXhMiwWB3hi29BmMCStUK47NzHJ2c48jEDDPFNPlKliD2cByD66iF2r8L+AfgvcCHsMbfmsJaI11i+8m/rB3XNBuKQYQwLru39HDhzhx7tvjk0gqtAyJlqIZYjV2G66FeQYzRVMMQAEe4eG4nO4d7OGcg4ILiOCMz4xwem2KykGG62E2+nEQIg+eqBVYE+4FvYkn/AHb4tyawlkh/KfA/gEvqF4SASqCJNewa6uLSPZ3s3uqS9BRhXKZUNRjjsNBgazW00WiliVWM40hymXNIp4cY6p5gZOoIE3NPMFXoZHS2h9lSAgDfVQuLeCPwu9hu6ua2CboErBXSPwh8rH4iBMQKilVFT8bhsgt7uXhXlnRSUaoEFKt1y7x9ZJ8IWitCrRBC0JndSjrVTyZ9mEzyIH25OWZK3RyZ7GK2nMR1NK7U9Vu7gZuwQ8z3YZ1Aq4bVJj2LtXZfu/BiORDEKuI5uxO84uJe+jp9itWQfMkOtVYbxhiCqIp0JNsH9tCZ6ePJkYdJeOMMdRc4MtnNk+NdVEKPpBcvHPK9FXgZdtj549WSfzV975cA91AjXADGCMqBIJMo8oYXJHjji/rJpT2mizFRXP/W2oHSimpYIZfu5PztF5PL7CCKQ3YOTPHc3UfZ2pMnUg6xchZKPgDcAfzpasm9WqS/HLgTeGb9QqQk1dCwrWeaq16c4QX7hggiSamq1hjVxyOIqriOyzPO2cdg9w4KFU02WeXinaPs2zaBdDSVsKFRTQKfw1r3K47VaN7fAnxl4YUwcnGciP07RrjsgiGGeneQL0doozm9l3T1IRDEOkYiOW/rXhCCp8YPk0pIdg7M0p2p8uCRfmZKKRJuvPA3XY918b53JeVdaU3/dywivBq5ZFMVnnXuIV64r5e+rnMpVS3h6w2xjolVxM7h3Qz1DFMNIyqhS0c64DnnHWNbb54gdlFaLGy93gN8ZiXlXElNfxPwxfqJMYJqJOnvKHP+1sOc099Df9cewjjGrEPCwWq80jFCeOzasodytUipWgQSeDJm//YxfFdxcKwb0LiOrruHrsF6iq5dCTlXStNfBHx94YUgkmzpKXLxuYfoySYY7tuHMQat1yfhT0MQqQjX9dm55Rm40kWbmEi5aC145rZx9m2dRGlB3Kjx7wb+aiUkXAnSdwG31k+MEVRDl+GeAhduH0EbTW/nTlJ+ikitSVf1kiEQhFFIV66HwZ6tRHEEGJR2CCKXXYPTnL9lCqUdYt1g2f818Cftlq/dpDtYV2Rv/UI1dBnqLnLRjjGUCsmk+unvGiCKwzaLstIwKBUz2LOFTDJLrGIAtBGEsbTEb50kUg5KN1irN2Pn7tuGdpP+JWzoEgJrtPV2lLloxxhCGIJYMtA9hOd669JwOx1iFZNOpOnrGkTVSIenid89OMOuwRlCJRdO/Qjg20BHu+RqJ+lXY4dnCCCIJblkyEU7RpGOphwYMqkMndnueS04G6GNpjvbi+8lG+wVbQRB7PCMLVPWqm8cxw9hp2bbgnaRvhP4+/pJpByEMOzbNk4mERHGEmM0Hekukl4SpdUpilrfiFVMJpUjk8qidGPljrUDBs7fMklXpkoQuQv79z+gTV67dpF+M7WIU2Mg1oK9WycZ6CxTjVyMMUhH0pHpRJ/lKc2MMTiOQzadY7GnSQChckh4djiXcGNbEZ7Gp7GBni1FO0h/J3YqEYAgdhnuLrKjf44glvNfksIhmUgdF6x4NsIYTTqRQQp5fHAmEEQuXZkqu4ZniLVY2L8nscS3FK0mPQX8bf0kUg7pRMQzhqcxxkazgO3nPDeBJ/1164hZCoyBpJdEnMKnHMQu2/tmGewsUW3s31+HnatoGVpN+seAHrDjca0ddg9Nk0sFhAu0HAyOI0/5Es42OEKe8nOtBQ6wZ3iKlB8TqwZqPsvxCzSal6VVBWFjz98Dtb4qdhjoLDHUVSSIGn+w7ecEAsHZ37hbmDP4pUEs6UgF7BiYXdy37wb+fatkaSXp12GXDaGMQDqGcwdmcKVGm0UGjHBQWtlZtBYKsJYhzvCXxsphe98cHemAMG6g57+1SpZWkb6NBcOLMJYMdhXpy5UXNesWAtC6VvM3SBOvOTPbJdIOCTdmR98si0LCzqNFQ7hWkf4+alpujMB3Y7b15DGcOARZCIdYBURxdMYasJ4hBARh9YxGKrZrlAx2FunKVBdr+/tpQfhQK0jPUKuBdvhhp0t7chWi+OTFa62pBKUNYcwJ4VAOSmijzuj3Ku2Q9BVbu/O1JVrzuAAbY7cstIL0N1Kz2JURSKkZ7i4ihDmuL69DCEGsFYVy/qwnXQiB0opiJb8kn0QYS/q7ynRkAmLV0EVevVyZWkH62+oHsXLoTlfpzlQIo1MPURwhyJdn56NKz1a40qVcLVIs55HOmcesKC1IejEDHeXFkTZXAn3LkWm5pA8Br6yfaCPo7yzjuWp+1djJ4EqPcqXIXHEGV652JHa7IBDCYaYwRRiHOM7SXrcx0N9RwpNqYauZYdFCzqViuaS/iZoBp42tmb25MuYkzfpiCCEYnxklUhGOOPsyoUhHUgnKTM6OLUnL64iVQ2c6oDtbXTwKWlXS57U8iiVdmSrZVECkzqxYV3rMFWeYnJvAc/1lirLGIASe6zI+fYxytdQU6doIXKnpzlQWr5F/OXbVTFNYDumd2HQfGANCGDrTVRvstwRNR8DRiUNUwwqe67F4Jel6hMGQcH1mCtOMTB3FlV7T7ohYOfTkKvhuQxPfAbywWfmWQ/pzqIVBGQSeq+nKVBf7jE8LT3pUgxIHjz1qBTqNj3o9wHU8wjjgyZHHUFoty1BVRpDyI9KJCN3omn3lye45HZZD+ovqB0YLUl5NsDPU8oVwXZ/JuXGOjB/Cc/113b9LR+I4gidGHidfmsNzvTPyu58MxggSUtGRCohVw7t9QbNlLsdsfnb9INYOuXSALxWqCdIFAk96HB49iHQczhnYSRgFaz5uTkqBlA5CWLey0Q5CSA4ee4Tx6WP4fmLZzzAGHGnIpQIQ9a4UsFHGOWzioyWhWZXysIvuAWtwZBIRrnvm/flxgjgOrpQ8OfI4h8efwPOsxhtjSCQknZ0+XV0JUqnVHd7V/Ss9vUlyOZ841lSrCsdx6OlzGZs7wNHxI3iu1zIXs9YOaT8m2divDwAXNVNes29wCBiGuoWpSPnRsm0wx7FRoYdGDhCrmL07d5LJpDl2rMDBg3lUrOkfSLFjRw5tDLMzIVF0Zq7NVkBrQzbrkevw+befjfGd257kV7+apFJWbNmSZd9FPuftL7F9Z4r8jCGKTEvmk5QWpPwQ34spBT6S+ZjCc2liyXOzpG/HNi22z3E1SU+h9PL74nqfWNGH+dkvS/z4Ds0d3z/E2FgFpTS9vUku2N/LVW/ZzRWvPodSKSY/F+I47SVea0Nvb5IgUFz/4Z/zjzf/tmEy6ZFHZrjrLsh1OLz1HT1c8bpOykVNpaxZromitcD3NL6rKFYbPtraTHnNkr5lXiADnlQk3LgpI64BBhwJvf0+d92e5ws3PkB+pvErIyNlRkbK3PH9I1z11t381w9czOBgmrGxctuI19rQ1ZUgDDXv/s9386MfHjvpdwt5zedumOTQEyHvencfCMcSv0zRHGFIeceFiu9pqqwmZeipHxgjkI7Gc9V8DFwzMAaEA739Hv/vO3lu+PjEcYQvxte+coBrr7mbkdES/f0pjG79GN8YSKc9pCu49ppTE74Q378tz42fmiCREiQSp8tydRoZav8977i1+kPNlNcs6Q3Niis1jrO8Fy4E9A143Pm9PJ/95MQZ33ffvRO844/uYGYmoLsn+XRwRovguQ69fUn++9/cy90/OLqke++6vcAXb5qms1sil+1+sNmrFmGgmZKaJb2hW/CPF2ZJ0Bq6eiQHHqryuf85ueT7DxyY4z3X3E0Yabq6UsvSKoFAOhLf9ZGOR29/gttuO8DNNz3UVHm3fnWWO79XoKfPXZ5cAnx53Htuir9m+/Rsk/edEImkbbT+96cnqFabG5vfc88YH/nQz/jk372AMEigVT0Y0Zz2Zdv+ViCEQGtNEFaohBW0KHPkoTk+/KHfnPC+TNahb8AlnXWII0OpoDn21PErbz//mUn2XZiku9clP6taGSHWlP+9WdJbturCGOjqltzylVkeeyho+Gxg2OXZl6bZsy9JV5eLlLZVmJ2JGTka8eD9FR57OCCOLavf+NpBdpwf8Jorh5idlCQTSXzPx5UenlwUQSxsVG4UR8QqJoyqBFGVIAyoRhUq1TJ9A5Jv/PMsY8ee1rBEQvC8yzJc+sIMW3f4dHdL0hmHODYUC5rJiZgDD1f56Q9LPPKgNbVLRc1Nn57kuuuH5n9DizDYzE3Nkt6SxWfGQCYreepQxDe/9LTVtnd/kt+7soMLLkrR0SnxfIGKDVpbY891BXFkuPJNnRx5MuLuOwrcdXuBODbceMNRegcjBoc9wlkQOAghTjqWN7WU4NroeQ+gIxyyWY+ZSbjjtvL8d19wWYY3vb2Hbds9PF9QrWjCwFDIW19BIinYfX6C85+Z5GVX5Pj1fRW+d2ue39xf4b57ytx+a54rruwgP6eW1dQvQFPVp1nSW5I9wHXB8+FbX56hkNc4Eq56ezdXvrmLTFYyMxVTyJ/4BQkBwhGcd36CCy9J8eLLs3z1n2b4zf0VfnZ3wDuvyTExHi1I/XnytyyE7cfd2usQAjJZl2/+8yQz04pEUvDH/7GP17yhg6BqTiKTQSkIA6sPUgpe9LtZnv28NN+9Nc//uXGKe39W4pW/34GUYr51Wiaa4qFZ0o8sPGm2i3I9h9lpxf2/KJPrdHj/B4d4zvPSzMwoxkaiU/Z9xoBRhrlZRSGv2Hthkg98aJAf31Vkz94kszMK2083IZcrKBUVBx8P6eqR/PmHh9j/rBRTkzFheGZeNqUM46MxiYTg9W/pYs/5CRJJO2ZvEeEATzVzU7Ok65OeLAFhoPE8wTUf6CeddtizN8noiHXnnilZojYJMT2h8HzBFa/rpFhQVCuGJUYnzSOKDI6Cd767D60MW7f5jByzlXAplUgICEPD1HjMnr1JoshQLumm5TpBVWmqm22W9IYk9kEkm/K7G2Nf8DMvtMOsmemaZduEdgoH4tgwPWm9Vs2+2DqUgp4eiSMFhcLyLG4hoJBXy5LLGEEYHUfXkmfYoHnSn/ZSCEOsJLF2cIRZ8iybENa6rR+vFdS1dK1E8hhsWPQiaZbmLaqhWX2YN7Ud7Hx6EMs1RdrZhPprXbwQFBhrprxmSZ+vYTZNt1NL9bk2tOJshNaC4PjmvSlDrlnSD2A3sMNxNNXIpRK5SGdtR7qsV0ipKYcegZKLFetAM+U1S/ocNW2vp+yuhl6rHA6bWIS6YoWRu3CzIAU81lR5y5DlkfqBdDTFikcUy8U7GG2iBZDCUAk8gsb3O0KTGwEuh/Q75oVyNIVqgjA+rvnZxDLhCEMYS/LlBLJRoX4BlE9y26nLXIY8P60fCGEty0IlsanpLYYQhjBymaskcBttprubLXM5pB8AHgRbG2PlMFNKLq6Nm1gmpGMoVH2qoYtobEXvarbM5ZCugB/VT4SAuXKSarxpxbcSjqOZLqWIVUN//gh2m8/mylymTPP9uudq8uUEc+UEcrNfbwlcR1MNPWYKqcW20g9ZxvT2ckn/DnZ/cQSGWDtM5tObnrkWQUrNbClFvpLAawyV+sZyyl0u6VVsem/AWvET+QylwMOVm9q+XGjtMDqbWTyf8RgLWthm0IqVgl+uH7jSUKz6TMyl8eTZm857JeC5inzFZ7KQXriTI9h9cJYVudQK0n8K3Ae2iXeEYWQmRzVcPYNuHS96BewwTTqG0dkc1dBb+B4NdvvOZaFVr+eT9QNPKubKScbmsnhyZUnXGjxP0NklSSRESwIQjYFshySVdloZ0HhKuI6hUPYZm80sjnX/OouilppBq0j/GjWXYD265PBkJ6GSK6rt6bTD0SMhX7xpmulpRW+/bHo+wBgbNtXbL3n0wQpPPh6QSKyMhSql5vBUJ6XAX+yQ+duT3bMUtIr0CPi7+oknFXOlJMemc/iuWrEwhHRWMD4ac8tXZ/nk9aOMHI0Y2uIhHJZEvjGQzTn09km+d2ue668b4dvfmKOnz237yMR3Y+bKSY5Ndyy22H8A/FsrntHK3u8fgEfrJ1JqnhjvplBJ4q9QM18qaJ59aZpzz0tw9EjER/98hJ/8oEhvr0tHp3Na4o2x3cPgsMfsjOKGT0xw0w12xc1znpcmCHRbZxJFLfLo4Gg3USxxG8fm17XqOa0kvQL8Zf3Ek5py1ePJiU6koxdnR2oLqlVDd6/kspfZBThTkzGf+MgoN35qgqmJmL4Bl87a4gTfF0gp8DxBKu3Q0SnpG3DxfMHt357jo9eN8C/ftaGAe/cnuezyLIW59u41k/BiRmdzjM7m8GRDC/lF4Oetek6r0zp8C7s99vMBfE/x1GQHPdkKW3vylEOvrel/hYC5Gc1ll2f5zrfmmJmyw8bbv53nR3cWeekrslzy/AyDWzxyOYdsziEMDcWCYnpK8etflvnJXSWOHGrcI+4P39WD5wmKhfbF8Xmuohz4HBjtARoWhCoWKFMr0I5cHv8JuBeQjjAo4fDYSC9dmSoJLyY6QSrwViIINH0DLq+/qot//OzTiyHLJc13b8nz3Vvy9PS5DA67dHRKKhXNxFjMyAnWoAG88rUdXPCsFJPjcdsIdxyDI+DRY72Uqj6JxnXoH6QFFnvD81pZWA2/Aj5eP/GlolDxeeRoHwLa75c3Ntz48lfl2HLOiRMSTk/GPPTrKvf8a4kH7q2clPD+IY8/fFcP5WJ7bZKEqzg00cnR6Y7FK4B/wYJ32Sq0y43xQeB+sN6EpKc4Op3j8bEefLd9GgOAgGrFkM46/
          NGfNp1UEYCr39tHZ5ecD9FuB5J+xPhcmoeP9uE22j4xduerlqOdvqurgBCsVeq7moOj3Tw11WGTErURjgOz0zHP/50sL391rqky3n51L5c8P83EeNwWD5/BGm7FSoLfPjWAMWKxM+t9LGP69FRoJ+kHsNtDA8zvFf7gkQHG5zIkj8+f0lLEMZTLmrdf3cuuPUvL53b5azp4w1u7yM8q2pHKzhjbpIexywOHhyhWfRKN/oxvAv+r9U+2aLeX+iZqThsD+FKjtMMvnxhmqpgmnWifxgsB5aImkXC47qNDbNtxZgmHX/X6Tq79QD+lgjrjxYpLgQESviLWDvc9Mcx0IUXSixcS/itsK9k2rMTUxPuB/wu1H+wqYuXwm0MDTOTbq/HCgfysortb8hcfHeLiS9Mn/W5Xt+Tq9/Xxzmt6KcwpKuU2Ee4qwljywKFBpvOpxV3dGHaP1bZajiuVfvEq4CfApbYvU5RDj3sPDnPJzhEGOktUQrfpbJOngnBgekrR2ye57iND/OhfCtx5e4HxkYggMPQPuey/KMXlr85x7nkJZqYUcWza0o8nvZgwdrnv8WGmiylSfkOFD4DXAwdb/+RGrBTpMfBS4HbgxcbY5ERhLLnv4Bb2bZtge98cYSwXbyDfEjgO5Oc0vi942atyvOQVOUZHIsKqJb2jQ1KtaibH44W5V1sGISzhs6Ukvz48yGwpuVjDDXZDnnta++QTYyUTrVawNfnbwO+Abeoi5fDgkX6qkcuuwWkcRxDGTss9d0LYZdHTkwopobfXxZEQVA2TE08PI1tJuDEgpSHhKkZnsvz2aD+VwFtM+CzwxywIKW83Vjq77gy2Rv+YWlPvSo3SgkeO9lKo+OzdOkkmEda23G5Dcy/svHulohuutQNJ32bRfHSkl8dHbL7FRUZbBLyWJvK7LgerEWMSYbfang/uk44h6cWMTHfw8wNbOTrdQcJVKx6E0SpIx5DyY/KVJPcdHOaRo704tayaCwh/FDtHsaKEw+qQDnY5zpuBT9QvCAEpP6ISejxwaIgHDg9SCT1Sfrxu4ugdYSuvMfD4aDf3HtjCxFy29hsa3M//CrwC+OVqyLnae2P9BTa68wYgaWB+O6rDE11MFdKc2z/L1p45kr6drGmHobdcOKKewlMwNpfl4Fg304UUrtSLJ08APgX8lxUXcgFWm3SAz2GD9/8eW/ttxmM/Iogkv32qn2PTObb25BnqLpLyIyIll7xXTDvgCIPv2pTnY7NZjk53MDGXmXexLrIVJoD3Al9ZDVkXYi2QDrZ/eyXwMWyEiAc2EMOTmkIlwUNH+zk2k2O4u0h/Z4m0HyGAWIuW5Jk/UzjC4Eo7MRJELsemc4zM5pgspFFK4LsnDBj5LtaX3lQSgVZjrZBex19hIz7/BnhN/WLdAMqXk0wXU+QmO+nNlenvKNOZrpLyI5R20EagjcCY5aXaXghHGIQw82RHSjJXTjKRTzORz5AvJ9BGkHAVrnec7XEYGwDxpeNLXj2sNdLBTsn+PvAW4M+AS8FmvPBchQdUQ5fDE108NdVBLhXSna7Sma3QkQrwXYUvFY5jUNpWAq3FaYd/hgUEOzbuHGMzOkWxpBj4zJaSdplROUEQS6QwePKEmj0JfAYbvdpU2q92Yi2SXsdXsaHVfwJci90HDrBj+/qqj0IlwVwpiTvdge/GZJMh2WREyg9J+grfjUl49eHfif3pxlDrKhyCWpqPauRSjSTFSoJi1SeIXCLl1PaHVyebM5jE2iY3Ame2G8AqYC2TDlYBPw98Aav57wZevPALnlQga8n1YslkPsPorN0nya+R7UqFdGzz7LsxLNB6UcuDF8YSbQSRcoiVJV/Xdq2w4UzWQj9Je/Ew8E/AzcBoO15EK7HWSa9DY9fMfRnrwr0SWwl21r8ghEEKkI7tAqCWhiuWNe/e6R8iauXYoI/TRvjMYF3Kt2BnEdeHM4H1Q/pC/Lj2dz3wEqwb87mcYEdCxzE4rV1qcbD27DuB27DN+brDeiS9jgp21u722vkFwD7gitrxMHZXqWa3ax7Fpk07iE31cT82fGnNGWZLxXomfTEerP19vXaexjb/W7EVIIPdTw4aE74KrDt6DDvjNV47Psg61eTTQZwq+f0mzk6svi9zEyuOTdI3IDZJ34DYJH0DYpP0DYhN0jcgNknfgNgkfQNik/QNiE3SNyA2Sd+A2CR9A2KT9A2ITdI3IDZJ34DYJH0D4v8DycXdVcoU4oQAAAAASUVORK5CYII=" />
        <!--<img src="data:image/png;base64,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" />
       -->
          Bom
          </a>
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wMlQxNzoyMjo0Ni0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wMlQxNzoyMjo0Ni0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozZjJlOGVmOS0zMTUzLWFkNDMtODQ4My1jZmVhZGZjOGE1MTgiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDpiOWRjZDJjYi03ZjYwLTMwNGQtODUyOC0wMTAwZTlmMTQzM2IiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowNjA3NmM4My0xNTdmLWUxNGUtOGJhYi01ZDQ2OWI3NjAwZTMiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjA2MDc2YzgzLTE1N2YtZTE0ZS04YmFiLTVkNDY5Yjc2MDBlMyIgc3RFdnQ6d2hlbj0iMjAyMC0wNi0wMlQxNzoxNjo0My0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDozZjJlOGVmOS0zMTUzLWFkNDMtODQ4My1jZmVhZGZjOGE1MTgiIHN0RXZ0OndoZW49IjIwMjAtMDYtMDJUMTc6MjI6NDYtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6zqztsAAAZ90lEQVR4nO2dfZCkR33fP939vMz7zr7c7WrvTtIdUgBFRhji2BCBITbGESY2tjFOJVQSUsQmgB07NuAioSDYqWBcZUAOhWWMUzblV4ydBFWBy3HAIkVZlh0QSBY6ibvT6U63t7u3r/PyvHR3/uiZ2dnZ3dvdedndu5lP1dztPDNPTz/9fbqfX//6193CWsuI4UIedgZGHDwj0YeQkehDyEj0IWQk+hAyEn0IGYk+hIxEH0JGog8hI9GHkJHoQ8hI9CFkJPoQMhJ9CBmJPoSMRB9CvG5O+rt3n+l3PvpJGXgBcCdwqvH/ceAYUABu6fi+BS4BNeAqcBk4D1wEzgJPAMsDz3WXvPBD39r3OV2JfsSYAe4CXge8HLgVmN1nGuPX+ewy8C3gK8AXgK8BC/vP5tHhRhV9CvinwGuB7wUmdjvBGuPqdDtSIIRwx8WOp842XvcCP49rDb4EfB74n9yAN8CNJvqrgR8B3oQTfhNWa6w2uLA/CQhEQ0zhe0hfYa11+gqBiROM1hvnWwvGglAIJRFKIOSWGMLjwBsbrwXgD4A/Bv5PX690gNwIogvg9cDPAa/Y9Im1WG0wqcZqi8pk8QoZ/GKIVwzx8iEqp1ChRPoS6QksFqkMILCpRicWm8boWoSuxiSrEclahK5E6LpB68DdBJ5ASBDCtrcKU8DbG6+HgF/B1f4jzVEX/Z8B7wFetOmoMeg4AeHh5XKEs0UyU3nCiQxBOUBlJEJppGqrpdZ2tOIWROAO2Nzm5KMa6XqNeKVCfG2NaLFKvKzRcYgxIViQnul8JLyi8XoI+Bjwmf4VQ385qqLfC7wf+J72g1ZrTKSRYYbsLZNkT5XJzuTwCwovA9gUayKsBpsKdLLbz5htjwqZwR/PE0xOwukYXauSLC1Sn1siWlgkXg1Iojw2USCsuwE2aIr/BeAXgS93VQID5KiJPg78Z+Ad7QetMeh6ivQD8memKd05SXYmiww1pDFGp+iIhqG2s0W2V6zR2LjxrBcKEZQJZ6cIp2voyhzR3CXqc3PEq1miSgFd9UFuEf+1jdev4VqrSs8Z6xNHSfQ3APcDJ9oP6noKCArPm6H8gikyMxmE1Ji4hq5e3+zuC9ZgU4NNE4RUyMJtZHOzBFNXSBbPEy8/R7xaor6UJ1n3EMoi1Cbj7x3AfTib5E8Gm9m9cRRE93Bi/2TriACbgI5iMsfzlF88S+FUyYkd1TFWbHzxALFGg0lBKLyxU8jcMUT2HF7mPOFYhWitSG0+R1L1kJ5pt/zPAJ8FfhX4D2ztPB4ohy36DK7L88qNQwJdM0DMxD2TjL/4JF7OI63GGOM+P1wEWINJ6gjlkZn9+yS5CaLnniDnLxKW69Tmc1TncphEIv1NTf7PAN8O/BgwfyjZ53B9768BHqVNcGsEuq4JyxGzr7mFqZfdhlCSZC3Bbm9zHSpWp5i4hjc2TfbMd0BwHGFrFE+uMf78JYJijI4V1my6UV+Fu+5XHUae4fBE/3Hgczh/OAAmldg0pXiywvSrT5K//SRpVWMSffiV+7pYbFJH+hmyz3sJqjhLUknxczHlO5YpnFgH3PW1MYPz6P3oIWT4UER/K/B7QNA8oBOF8uqMPW+JyZedxh8/QVqJ4QaaUWvTCCEl2du/Db887fIPFE+sU37eMirQ6ES1nxICf4QrjwPloEV/B/BA650FHSnCYo3xO68xdvcZVOEkpt7qf91ACGwaA4LMrXfjlSYxUYxOJOFYxPjfWyJTqqMj1XlpD9DRRR00Byn623BWusOCSSTZYzVKt84T3nICVTqNievceII3ccILzyNz6i5kkMWmKTr2UIFm7MwK2WM1TCI7L/F+4F8fVC4PSvR/Any89c66Jj07XWfs1nlUsYQ/dYfrDt1ATfq2CIFNYlRuDH/mDM49aNwzXcLY6RWy0zXX1G++1E/hjNuBcxCivwh4sPWuIXh+pkLp1BImhWDqDkSQwer0ALJzMJgkxp+YxSsdbzX7VrtX6dZV8jOV7YR/kM5xhgEwaNHHcF4oZ383BM9NVymeXMfEGlWYwSsdwybRgLNywFiNkAp/8gQISbPPaY0Tvnhyjdzxaqdx5+PKa2yQWRu06L+L80YB7hmeGa9TOrXqRr2MxJ+cBSlv/GZ9G2wSo0pTqOIEtm30xxoXuFE8tUamXEfHm4Q/gyu3gTFI0d+F8zkDoGOFX0wYO73ianyskbkiqjAObYEMNxcWIRXB+C10OhuMlghhKZ1eJSjEnTX+Ppz3biAMSvS7gf/SfGNTgQo0pVtXEcpitARjUPlxpJfBmpvnWb4Fa5C5MYQf0ulWNKlEeobSbauoQGP1phvjl3Hl2HcGJfp/B9yta8EgKZ5aw88nje6KBeWhskUsN2std1ijEUGIyhS2NVRNIvELCcUTaxi7qSvn4cqx7wxC9J8DXtp8oxNFbqpKdrK20YRZi5AeMszRGEW5eTEG6YWIMOuCM7dBx4rsVI3c1BbD7qXAO/udpX6LPg18oPnGpBI/n5Cfqbimq3EXWyxCSoQf3JQG3BYsSD+DkJJtHU8WrBbkb6m41nCzn/4Xgcl+Zqffor8fcAFn1r3yt1RQGe2e4y0sKB8hhmOCjW0JLbbVHJxhp0JNbrqKtZu+V8JFE/WNfpb6GeAnmm9MKslM1MmU6+453o61COWBkG0FMsIkkuxEjcx4vbO2v422rm+v9FP0/0ijX2KtQPqG3HTVfdKpq5DYuI61GnG0x00Plkb0V366gvSNq/EOgSvfvtAv0U8Bb26+sYkgLEcEhbijWW/H3vxGXAMhhOuWWkNr9sUOmFQSlGLCcoRNNn33zex/uta29Ev0f08j9MoagQwM2alay/PUiRDCRZ3Uq84btweshUwgyR/PkJ/Jks+qQ7EBrXW65Us++dkc+fEAJcXOeRECazUmru/tUdYw6rJTNWRg2qNuPPo0BNsP0UvAv2y+MakkLEfbWaEbCInVCSZaR8g9hOkZS2EioFLXfPr3z/PAbz7Fs3N1CpNhH7K/d6y1hKEkNxnyyFev8ZH7n+DPvzhHpuCRyyms2SqqkAob1zH1daTaW0ii0a7XE5ajzjL8N0Cx1+voR2Dk62h0KawVCGXJlOu7n2Uturbm+q5C7Nx1M5CfyfLYV6/xlvc9ysPfWAbg9hNZfueXvp17XzXN+uUqQg7WNrAWAl/hFzze9+HH+eXfepoodo+nt7zhFJ/84D1k8x61qt7cgguJjqrYqApCbZ/4lh9z/4VjEbWFbPsEy+O4Yeo/7OVa+lHTW7XcakFQiPGLyVaLvQPhBei1RUxtFaH8Hb+XLXmsLka87u1/3RIc4PylGq/5ib/iqceXKUyGA2/qPQHBVMivfeppPvjrZ1uCA3zqTy7y7z7wddR4iOd13nwCvTLvvHG7PM/bMYl7tgfFLXbRv+jpQuhd9NN0DPxnynWk3IOB1gg2SFau7lwYFmTB45fu/yYXnqtt+bgead77kSdAQrClsPtLpujzrb9b4d0ffWLbzz/xRxf4yhfnyIxt3MBCKUxUJV25CnKPtbwNKQ3h2JYh5+/B1fiu6VX072+l0ZjU5xeS9q7GLr8uSRYvYaKq67d3kMkprp6r8MBnn9kxiT/8s+d49G+XCAoDDOG3wHjAb/+Pi1RrO48VfPz3z4PfNkysAtLlK5iosu317fqzVhAUYzddaqMlywE/tO/E2uhV9FYtN6kbOPCy6XW6aZsRysdEFeL5Z5xB11Hj1UTAn31lnuX164/CfeYvrsCYP7AmPhMq0sU6Dz509brfe/DLV5k7XyGbUQgvRFdX3LV1ITg4g87LpviFLUbxP+4qwQa9iJ4DvrP1ToCfT9xUnn0UvvRCksWLJCtzSH/DGlcSiAyPPLayaxp//Y1lqGoCbzBuXVXwOPf0Oo8+uXbd7y2tJvzNY8vI8QxgiZ97CpvUYS89lO2wIKTFzyWdw/HfBeS7S7Q30e+m4SywViCVwc8lnWPCe8iBaw6jS99ERzU37ozF9yTJcsJjT12/oAHOXaqxfLlGkBmQL98TPHmxSpzsbqt880IVclniq+dIl+ca19M91gj8fIJUmzx0twHP7zbNXkrpZa2/DKhQ42XTzik8e0KoAF1fp37+a1idILwQJQXrtZRn5rYacJ1cnq9zdTl2z9NBYCyX5/cWw3dp0cClZ0ivnO26WW/HGoHKaGRgOqfT/8Nu0+yllFpj5s2MCWmxXfnSLdLPkK4vUj/3NWyaQBBSjzTz1+Jdz05SQzqgWAwhAGOZX9qb6PHyVXjmcWetd9ust9FsRbepUIci+gtbGTMCL5M21nLpHulnSVfnqZ9/FHSMVZlN/eGdUFLs1ZvbNddWdl3WAgBbW8bqGCEV/Zq0IZXBy2wR/Z6u0+vyvCJwEnDXJUEFjYmGPV6n8DMka/PUnnwYL1llajK36zmupttOY6cvWAsIwe2zu+cDQFtJXwcvLVjhxjOE2GQkz9ClMddt7k7TmHFqrUB5GukbjOnPxUo/Q1JZw1z+OuPZ3YMmJ8sB5YIHekB9NgmT5Z29hu2UchLhX2cAphsMrTJuM+amcAbdvulWpWM0Ax9xThnpbTE0esJIn2Koua28+zP9xPEM01Mhdg+Pgu4QnJrJ7Ombp6YkeG45un7RjE/oWNYkoEvPXC+itzKEAuH1t5alGvJFxT2ndzeG7jpTxJ/JUqsPyJqLNC+4fYyZ47s38S88KRtr4fQRS2stmw5v57GdTrke3Ypeav1lnI9YKtNVd20njAWbWr7rBbuJLrjvu09AbPtau8ANiwo/Q1QXTJ2w/KO7rt/nfv4JyXfcoVip9Dcj1giE11jAaHNj1lXAZLeib37ACQZiRC2tWV55l+Ke23cerJgak7z6boFdiZF+gPAaL+U7C9ot87jzjwgBQiKUh/D8TeebpE6ydJn1C49jzz7CD91z/SHjN77Mp1gU7MGHs2+EtM6Q28zeuhQddNuRPNnlefsiSWGiLHjfm0J+5EPVbb/zrh/0OJ48zuJjGUSuhAwLqGxhQ3jlgfQaXag2RGMtWK2xRmOSCJsm2LiGrq2ha6vYqOKie4DV0OON9wZ89MGYR57a+hg5VhK8/QdC6rUBrZayfePRuYz5nuhW9P2PE3aBELC0bPnhlwe883Up9z+42ai776U+P/36DCsrmqS+iqiuAgKkcBMM/BDhBSBVY8y+veTcKlFWJ6A1Jo2wSdSaVtxwfLvzhaBuIKfgk2/P8X3vX+fqymYVPvG2LDNTkrl5s59h814pd3NSt6If2GVFKaxVDb/yr7JMlyV/8OUEY+G+l3j8wo+GGAu1RCK9YNN5Nk0wSeSCEa11tbrzIkTrH3djNEXeBgEsLlvuOS353HvzvO/36jz+rOHMtOSnfiDkDd/pMb94oIJDl/2lbkXffeirT0gBlTpo3/LeN2V462sCjIWZKcnammF53W7vjZMS0Way9EMLKeHKkuUf3KH43H/Kc+Gq4cSEJAwFV5dMK2jyALnYzUndiv7cliMDvFgpXI2fv2bIhwKE+9vYPQfT9jUvV1csgQezE5JabFmuWGcPHnwI/x6CEbfSrehtMUEbqyv0ww27E82kK5HddOwwEAISDUmjPz5wsYWLP7RGdF50V3O8u60nc5syZAQ2Fdt1KUb0ASFsa72aDtG7Wmq0W9EXOzNkUnnEV3a8gRGNFTX1lorVlW3VregXgWp7hkwqt9vvZEQfENK2yritYq0B57pJr1vRL+F2LUIIi0kkJpGj5n1ACGHRsXIVa6OM54Bnu0mvW9Fj2u8ywXarHY/oE0ZLdKQ6K9X+d+Fr0EuH5/HmH0JadF1tZ12O6BEhrFsSPVKdan212zR7Ef0vm38IaUlq/qiJHwSN7lpa9zptpke6TbIX0Td+VLi5V2ltS8ZG9IhQlqSypULFwMPdptmL6BeBr8NGty1Z90ei9xkhLWnF6+yjfwPYea7XLvQiekL7VpMC4mqATtVI+D4hlLPak4rfaSt9iR58n716rr/QSsgzpFWPtDpq4vtFs5YnVb9zz7fP95Jur6L/BW6racAZHNFKOLLg+0i0GnZ2hZ/Dbe3ZNb2KXgf+tPVOQLwSoONRE98rQrqmPVoJO1X6DLD7XK/r0I+ByU+3EvMMSc0nXg36Hh07bEjPEK8EpDWvc+bQp3c6Z89p95oA8BXcPmOAs+Tr1zLYdOSo6RoBOlXUlzKdLebf0ENXrUm/QhA+0vxDKEuy5hOvBSj/5l7heVAoXxMvB8RrW1rMj/Uj/X6J/tvAFWj02a2gOpdzQ4EDfrY3I1akdAsZNP/3VI+vjvSk4ECiY4R0Po/qvJtYITZ6Zudw5dwz/VqoReM2j/0QgPQN8VpAfSlDdqrm/MY9FJa14HuQDQSBB0oJl3MLpG7iYpK6CRJSuNkxqe5eoGasm++5WmFxwqtmDLAnWr+dpFBPIEosevcFIXf5Yfcsr87nSNb9zn1aP77Tafuln6vz/DfgZ3HLf4OAypU8QSlGeHb/K1TgRAw8mJiUJFXLxQXDwqplperi0ip1y8UFi6/gmQVLNbIEPiyuWhZW7YZI+8RaJ/KJSUnoQZzCVEkwURD4HpyYkORCKOUEk0XB7Ljk2Li7EeaW3E3YjfbCcxZ79UquM4ELHFHRK8DP02iCpGdIKj7VuRyFU+tdiV7MCs5fNfzYh9d5+ophftUJfZRQEiYKgrG84L6XenzgxzNoA7qL4GShLJXLeZKa76Z+b/BumkErfaDfsaS/A/y/VuK+obqQI14JOpuqPWEb0a7HxwT5DGT2Nlv4QHGPAVfjy/md13PfDem7Llp1IdfpfXsEt9143xjE4ms/CfwVNMJ8Esn6pQLj+SWkMntebgxgrWaZHRf87i/kWV8xLK5ZVquW1RrMLbuY96srbpndS4uGKAVfwcUFS5RuLX1PClaqxk0wdBNhmCxKQn/7qcXHS64GRwmM5WG6LBHALeNO4JmyJJ9xfx8rCbxQML/oQrP382yXymC1YP1SAZuKzgrytr2ntDcGIfrDOKPuZ6BxB68HrD1bpHTbKsJsmW67I0pClMD8vCH04PiYZHbCiSWlak1OAdzBJjvdVxJ01bLSFqdeLokNw6yTxu4UG3833rhJMxjrmvFEw1rdkqzvPyRaCAsS1s4XideDzmb9V+lh3HwnBrXM4nuB1wN3gBO+Np/FzyVu18F4fxaWtc5CrifXazt3b1etdd0xvyGyBRaWLNp2tzxSP5C+oXo1R20h21nDz+LKsf+/OYhEcb7hf958I4RFSMvapSLRSogKD8dpI4SrmfXYUk8sUeLmtB+W4CrQRCsha5eK201FfhM9+th3YpCTgh4Gfrr5ptltWz1fIq14zlt3tAzxg8M6wZOqz8q5MefE2ux5+7e0GcT9ZtAzwT5Gc0M565oynSiWnh4nrXtuQbwhRAauO7v8VNntwuhvWvD3k8BvDPT3B5l4g7cC/xdoeZzSmsfK+TF0rIbOP68CTVrzWH66jI5U5wrPf0nbjleD4iBET4Hvoy1kWgXa3elPlp0j4pCe8QeNCl2Tvny2TBp5nYbb48Br6esaXdtzUBN9q8D3AudbP+wZ0rrH8lNlouXQ1fibdShWuEdbtByyfLaMTra0cN/AlU9XU4/3y0HO7n4O+EHapuJI36BjxfK3ylQXcihfd66VdsMjlEX5mtp81jXpseqs4U/gavjWOf8D4qD3vXwUeBHwWCsDjUUHV86PsXqh1DL4bgaaBtrqhdLma9u4rx/DrZl/ecdEBpGvg/yxBku4puyLzQNCWaRnqMzlWTo73vJM3ahxdkJaVKCJ1wKWzo5TmctvrAO3wZ/
        jymH1oPN3WDvcXsFtQNOK9xLCFVSy7gyd9WcLADeU+EJat4GeFaw9W2T56TJJxY2YdThefgu3FcqVw8jnYW5rbHBbSb6r/WCzaV+7XOTakxPUFrKtmnNkDT2xcXPWFrIsfXOc9cuFVhe1g3cBbzmEXLY4CntZfxh4JfC15oGmyGnV9eeXzo5TX8wghKtJR6XmN2u2EJb6Yoals+PO41j3tmuhHgFejrveQ2WA+1rti4dwBfIh2vYTbdaSeNUFCQalmOxEjaAUtwwia8SBzosX0o0jNCdtRgshtWtZ4tXgekboR4H3cEBdst04KqKD68u/E/hj4IPAvc0PmgLHKwHxcoCXTwnHIsJyhJdJUYFuid+x4XzviI0BIyEtRkuSik+0HBKthqQVz/XDPbPd4+ch3EhZTzNS+s1REr3JF4FX4IIxfha4E2g5OADSmpszV53P4ecTgnyMl0/dliKecZZyYzL/vm6EDoGtFa1FlNK6R1rxiCsBScVvxfXvMH7wJK4Z/2TvxdF/jqLoTT6Bi7f7KZzhc2fzg5ZxZBtN/4qLD1e+2zHKy6aoUDtnj++6g1JtWxM3sGCMRCcKmwh0okjrHrquSGueO94UWhmEv+1ddBYXIPoAAxoW7QdHWXRwTf5/xT0T34wbjHhJ61OxcQNY65boSOsedsHF20vf7TghVKP2NvwBnTSX62ougmhSt3CStWJjnFte12n0t8Cv42IEj6zYTY666E1quNrzAG5/19fj9hudbX5BCOt2mMC2rqq5Vkured+lpjeb9/ZHyXW4jJu8+b/ocerwQXOjiN7O5xuv9+AcPD8MvBj4ts4vNrtMon+W3ddxC/x8FvjfuLXcbjhuRNGbrOFq2p/i/A0vwm0QeC/uBpjBLYLfjS/C4AZAruAiWL6EGwl7lAMY+hw0N7Lo7RhcDfwq8JuNY2O4LaxO4m6AInCCzTGusLEZySXcjXQFNxJ4gQNc4vwgEdstfj/i5uYouGFHHDAj0YeQkehDyEj0IWQk+hAyEn0IGYk+hIxEH0JGog8hI9GHkJHoQ8hI9CFkJPoQMhJ9CBmJPoSMRB9CRqIPIf8f77loTsQ5NZoAAAAASUVORK5CYII=" />
        <!--<img src="data:image/png;base64,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" />
          -->
          Regular
          </a>
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wMlQxNzoyMToyOS0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wMlQxNzoyMToyOS0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2YmUyYjkyNy1hY2YyLWVh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" />
        <!-- <img src="data:image/png;base64,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" />
          -->
          Ruim
          </a>
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wMlQxNzoyMTo1Ny0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wMlQxNzoyMTo1Ny0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxZDJiNzJiOC01ZTE0LWNmNGMtYmRmNy02YzNjMjdkOGEwNGQiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDozOGVlYTI4OS01NzE4LTIyNGUtOThhOS0xMWNkYTBmM2E3YTkiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDplMDg3NWY1MC03ODdmLTM1NGMtYmEwZS1mMzU4N2Q5ZDk5NmUiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOmUwODc1ZjUwLTc4N2YtMzU0Yy1iYTBlLWYzNTg3ZDlkOTk2ZSIgc3RFdnQ6d2hlbj0iMjAyMC0wNi0wMlQxNzoxNjo0My0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDoxZDJiNzJiOC01ZTE0LWNmNGMtYmRmNy02YzNjMjdkOGEwNGQiIHN0RXZ0OndoZW49IjIwMjAtMDYtMDJUMTc6MjE6NTctMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7nvtnwAAAcz0lEQVR4nO2deZRkV33fP79731JVvW8z0zPdnhktCKGNRVhIEUIyO8YEsyY+4IMPBHJOInKIJZz4EHyO5YCxg4MlEx8wsY2dYDZD7ASwCcgGI5BQQCAQ2jWrpns03T3dXdW1vOXe/HGruqvX6a6qXma6vkc9qvdevVv33e/73fu7v/v7/a5Ya2ljd0FtdwXa2Hq0Sd+FaJO+C9EmfReiTfouRJv0XYg26bsQbdJ3Idqk70K0Sd+FaJO+C9EmfReiTfouRJv0XYg26bsQbdJ3IbxG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" />  
        <!--<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAADrlJREFUeNrsXWtsHNUVPokd27ETex2HpLSGOIYSBBRvfhG1KrgFQVGVkEgtEJEKBwWk8lASCX6UAMGooZVAwhGoUgPCTksFtJXsEqkNETSGoipQqbEDDRjxCCQtIW3ijYlf8avzjWec2bt3Zudx57Hr80lXnl3v7jy+e173nnMvEYPBYDAYDAaDwWAwGAwGg8FgMBgMBoPBYDAYDBFzivz+0lpLWf7mQ0ZrPZa/THoBENystSbjOK3gN3uM1qu17mLuCIUCSG6L1jq11q+1qQhav3G+Fpeag6EIa40HP5WA1mlcD6v3kKR6i9Y2u5WwmqpSamqspCuXV1FKO75wSTktW1pu+/nPvhylz0+MUmZwnA59Oki9nwzRae3YJeAD7NRam3HMpAdAg9a2G+rUESD36m9V09VXVOt/QXpQgPQ33x2gN98b0P+iM7hAh9ZatXaESfcn2dudPrR61SJafdUineRlS8pDvyh0glcO9NOet0/RngOn8n28NamSn0TSIdVP2alxkHvvmvN1wqMg2tYcaKYAxD/zyhf6sYPa32pIP5Nuo8rbjbArB5DmDd9fQj+59rzE9dLfvv5feuGvJ3QTYAOEexuTovKTQrqpylMyW/3EnQ26rU46YPsfePaIne3PWFT+rCY9ZUj3Wpn3/cSmhkRKthvJf+C5I3bef5ch9ZnZSHraiHMbxH/AZj+0vl6JBx4XQPjPXzym23wJoObXUUwjfHGRvtaQ8JQo3b/ftqIgVLkXlX/zjj6Z1GcMie+K+ppKYvLOX9RahRh+7Xv8MlpRP5+KCYgw7rxxKfX9e4Q+PDZs/Rfu/1YEAlFLfEkMhLeLbz60/gJ6+u5GqiibS8UI3NePv7tYU6tzdMmXaL1IiS+Jk3Coc5ANGz4bMD2IVKETPzo2GRvxc+IkHOocIdlsA0K66x88LLPzsPEdxUC6OSvGhLsjfl3Yzl3YpCMs22/10qsrS+jVHZdT+qLZS3ge4uHVfy9MVR8m6SD6oDUOB+F/evgSWnU55x/kIR5x/EoKaQAnTEcOYdkq6xs7NnyDbvrOEpo7dw6zbWBpbZneMHMnCMylWnu5kEjfYrRzb6xZSvesPp/mzy9lpgU0NVbJwjmQflprBwqB9AZx8OX6dLUu5QsXllNJyVxm2SacO/TpkDiAs8qQdqVqPgwG2kXH7cmN9VRaOpfKykqYXQc8u/kicb4hRZLBrKSRjni82frGrruX6cRXVpYxq3lgzj0IaCYX6WJxqXf0yr9Y1fod1y2m265ZNH1DNeU0Zw47cPmAsfrTgxP0Tt8Zkfhfa20kaZK+RVTrW1Yv0Y/Ly0uZcA+QTCunRMc4CZIO561TDM9WNlbqx1VVZTRvHjtwboEJGkkYB2nfrcKpU8VEVtbqZRdU0I++XXvuJirYgfMKZAxJhqm3J0W9p0RH45Fbvj5zDK+dVbs/IDdQ4iinkkB6lq1ZtaJKb+eknAdjfMfuRuGG0/OOi/TN1hdWtW5KOsM/kPYt4Pa4SV9rVTf1dWU5pPOATHDbLhR1NFDAosmgpGf1ujuuq8v6J2x5UHuOWSiMSXsoJowNuE6X9W6eIMksCiTtQRiBhPdb33jrlyt0abdKeW3tfF9EI3UY+eNWwJtFDIskyqQApU1IdRbJhoRuW3+BktIrlE1duumf4tu1fsO3ILr3VquawaTKbddkSzoGZdC8AETf9Oj7+uSDiC8zY/SHv53US4qTQPxdOz+ih3/zuX5duR13SO+4yInDLFqg8KiqVDYZ00c+Ey2CqPebrC+uX5mbq+513hzqEQ/STcdABUmcwPlFTWTXMSQZsJ6B6lyn5x8V6c1ZpKdlpHv7+bva5ISnUilqb2+n/fv3U0vL9JAApEjFw/Rru20qVyidTlNnZ6fecOx0X57Ct9zQrdnvb/kNorNWa8IIHMbac2xHiXtJh220K/kF0SbZzc3N1NPTo7cXXj8RSzUMzmsHdE6TbPxdvnz5TFlzEJME3wA+jcV3MFfN6olK0puzB2QWBH6QMhtulXTZa4e68FCBpUncXKv12On+opZ2v6Q3ZZMebmZrR0cHHTkybcO7urqou7t7Ws2+G496dwrLWltbKZOZdqq3bt2q9LwSrdYUtXo/R/ol4ZIOwqEmRcSVNy+o2ZwOihYGJJKejlLSZ04GWy6z594fZKV3ddNYGQvpfs7r5/5EYI5dmGePjPSsE8GJUwE4OV7r0TdcuyQW0r2eF/elalxB0uHSUZCeyiZdXWkxEgO9dJK46thxXi8kerkvN6bFiY9IJF2FarcSibJlNzeu8kH67aBufIpdmy9WOnqYytWG0Ut6fd082w+OjU14/vFt6+vp1ccvlz5QxKroFG/vvDL2pUlwflzHQzbj67h+3IfqNXMuzD2XZ0kP/OSsEywq1SceKOJwMxZPVZUkssoVnRQN3nxmcGKmc4a1xp3TUqeRke6EsezCe+83GOLDCyOMKxT4Ue81bj84MTFJjOIg3TXGx5n0YiH9NBPPku6Is2cn+CnPNtJZ0ouQ9GMnzzr+f2RknJ+yQmAHijhIz2STPub44ampKZZ2hfg8N4cgEwXpWZkaA0P5bfbw8BizpQiZ3FTwnsgl/fDR4bxfGB1lZ04VJPP40Uv64aP56+QxSMMqXg0kqVqeJd3vMCxOlDbVO1q+2bahoTGqro5uSBUVMb2fDtGhTwb1lR3efO/c8ELmzIQuMfr2XQvOXffVV9RQDcb4G6uoaXll4tabxzyEUOnjK+89MOnAgQ8HpSnQohe/cGFZqGXLyDj1sp2W+Bkx58667VcSiivQgYNKeRDSe60vDvTlJx1e/PDwOFVWzlP6IEAyUpKxdZbqejd0CrPEClK/ZlWtnjUTV/KGJM+/N0rSu7NJP+PqS1DxqkhHdcmOF49GlgaNDoVzomHmD3VqUe8vI8n+7fbzO0F0LYoXZybwxeJFO1RXVwRaNdIt2ZBMqOYmTUUjKbFmQamjhOqVsWfG9fz0XlTKvpu/UjZK8iVFjPDaa6MmPWtzWSwQKNamy4AVI+vq5nu27XqdW9tHjmTDBoMAkK1ifvuQQT46mpOPAPJ3bbk4VLWPaxDq/LA8+LqoSW8hy2qGsOm77lnm6osLFpTpK065BYoF7WrHINEgGjXcYSZcoLOZ5dN2GgDXgG3FwsDNj/eJW3363hAg1Pp025NqUl5XV+mq1g0PWlahCrLv0x7yvWu+FmloBcKfeeU4Pa1dl4x8kK56exLV9elBJlwyJOxAsO+guzIjePJffTXq8iHnjuYhGbHvuZV6bpobwjEwBCdyYGCU+vuHcxrex//dDCDhfDgvzi/L3JVdr4pQVEAXBVhPLmjQnLVVB6Qc0u5aVaQq8i5agF5+w4P/0v/CViOl2K0aHxw8q4eJXtK24HPA0XRrfnBdeg26ZvtxXciAVW1mIOWCLxNoyw8VIyVZXrxbh86rmvcKSO3Jk/4rRXFdSVgZS+LAwdYtD/KbKu5qp/XFH//e7/qLUPMDAyOhPKzJyalYv68K2K1ZwO6gv6lCxHIcupfub/RUvowBG2wAoBLoUJnMiJ6uBZVdXl6iL3xUWlqSpVkmJjDfP6F/DrOBMAX4HExP3CtdIkyFaVPlwKkknYzQrcV8gaLGPz/yTU8/EHTQphhx1eZD4vhAhxGqBYIqo9VqfYHpVi9qHoCa5+nXbFsuGRBqVfHbqqoPM4bWaDbfwMwbFvgv97DkN2bi4M3P9t2cEP+vefQDcWvOVlK0SZ9K97TNamswx96254RnO3zq1PCsl3gsRijZoK9N1e+rJB0XlrXIyvOv/U+fdmXivTlvkiHnraRwx6Yw9Oh+q5pHRs1bv1jhuY4dnvOiRfNn1SrSkO4Vmw6KUt5N09twUhIl3cRGUc3f337MV8iFwRWMqM0W3LnzY5la36j6PGGsy40LxZjhD8w3Pj4+CtH1tfTY6Oi43gG8rjFbaNih2fHn9n4pvv0zre0tBNJ1552mc+gunXlDs+31i8t8rVGDOnesalFWVpyevc1at12ij5Rkm25CuqsyRuv8rkgFO4/93YpJ6ottV2UMqr9B00uE6yyPjk3Rnn+cpmuuWEjn1fgjDrE8PPt580oKXuod9k+/0SCeCo104DhNr0t+64yNVkA8xsdNBw/7vRXiblA9Hw/SDdsOyxIx1pPPhMekkA58oLXPyJJPp4J4AJMkIyMTOumFstkfJnjeef80/XD7B7I6QHjqL4V9DVHtqtNjRzxID7IAITx7ePhmSXRS94FDBx0cHKPn935BP/3VZ3aEd0RxLVFupSQlfl/PgO9wTiQfD3ZoaFyXJkynxm3zcU3QRJhMQjrWk53H6bGXv9DvOy7CoyZdSrwZzmFmDuq+XIGahqOH8mhIP+x/lB3AJBqpWsi9gxbKnBmn+3Ydpd+9cUr2lUgJDztkcwJIbydhtUOEdNhvPYz146HysRsk1P90MsVcZSRDw6CjmZGF2KHvkqtzc7StK+qHH6f+w+BNpzWON4F917ENt8p1Z2UA8dACphOIMDCfEzY5OamnUoFcDBqBdBnMWUZMOkmAcGwd+SxALGTSzQGcdpLsKAjCH7nlfNdJlkkCEkhgu21W6egiYX5itpFuApvGbifJ4rYYvcMuzWFvGaICUOWPvfwfu4UaQDISIdrivs4kxTYNhtQ3y/4J0iH1SZR8SDaaQ+5AtyHdR5JwvUkcymrR2lNks6Q1Ciqwpys2/wtjBWq3wFJqqOh5/rWTTsuqmYklHUl6wEkdv0xZVL4tUDQJ8qEFougAIBfSDLL18QVnmKo8k7SHm/RB6waD+JZ8H4Ttx/5w6AA4VtEJQDLsM4jGwgtuFlUypLo1Kaq8EEkXJf92WYgnA7x/kI8hXhxjBwqnjgCCsRAiPG4skwaC3ayRZwnBdidVsosBZtHkVAJaJwXcwJ7hXfpbjAffHxHJ/cb5WsjH3ims3tUjbYR7TcZxWsFv9hit1wi7eorhQRV7KYm5+3PapWRmDGIzxUIwg8FgMBgMBoPBYDAYDAaDwWAwGAwGg8FgMBgMd/i/AAMAh2pt+CDmwWkAAAAASUVORK5CYII=" />
        -->
          Péssimo
          </a>
        <a class="nav-link" style="display: flex;flex-direction: column;align-items: center;" >
          <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAACFCAYAAAB2QfdRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAGvmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA2LTAyVDE3OjE2OjQzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNi0wNVQxNjowNzozMC0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNi0wNVQxNjowNzozMC0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3YjA5MmQ3Mi04OWVjLTBhNDItYjI1MC0wOGJjNjlkZTdlMGIiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDoyNDc5ZjlmZC05YTBmLWZlNGItOTYwNi04NjNmMzM3ZmM3MjYiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpiYzE5OTBkMy1lZmQ3LTA2NGQtYjNhYS1mYTcwNGNmZTRkZTkiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOmJjMTk5MGQzLWVmZDctMDY0ZC1iM2FhLWZhNzA0Y2ZlNGRlOSIgc3RFdnQ6d2hlbj0iMjAyMC0wNi0wMlQxNzoxNjo0My0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpiOTA2MmJiYi02ZGYzLWQ0NGQtODZhZi1hZGZmOTc3YmU3YWEiIHN0RXZ0OndoZW49IjIwMjAtMDYtMDJUMTc6MTk6MjItMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6N2IwOTJkNzItODllYy0wYTQyLWIyNTAtMDhiYzY5ZGU3ZTBiIiBzdEV2dDp3aGVuPSIyMDIwLTA2LTA1VDE2OjA3OjMwLTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+KwfpyQAAGaFJREFU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"/>
          Não se aplica       
          </a>
      </nav>`;
    } else if (tipo == 'MOSTRAR_TEXTO') {
        campos += `<div class="form-group form-row" >
                 <div class="col" >
                   <label for="text${idL}" > Texto: </label>
                   <textarea  name="texto" class="form-control" id="text${idL}" rows="3" >${(d['texto']) ? d['texto'] : ''}</textarea>
                </div>
                <div class="col">
                </div>
                </div>`;
    } else if (tipo == 'ESCALA_LIKERT_0_10'){
        campos += `<div class="form-group form-row">
        <div class="col ">
        <label >Intervalo da escala:</label>
            <div class="d-flex justify-content-between bd-highlight">
                <div  class="form-group row" style="width:278px">
                    <label style="margin-left:15px;margin-right:5px;" for="selecDe${idL}">De</label>
                    <select name="escalaDe" style="width: 230px;" class="form-control" id="selecDe${idL}">`;
                    for (var i = 0; i <= 10; i++) {
                    campos += `<option value="${i}">${i}</option>`;
                    }
                    campos += `
                    </select>
                </div>
                <div class="form-group row" style="width:275px">
                    <label style="margin-left:15px;margin-right:8px;" for="selecA${idL}">a</label>
                    <select name="escalaA" style="width: 230px;" class="form-control" id="selecA${idL}">`;
                       for (var i = 0; i <= 10; i++) {
                          campos += `<option value="${i}">${i}</option>`;
                        }
                        campos += `
                    </select>
                </div>
            </div>
        </div>
        <div class="col"></div>
        </div>`;
        if(!d['id']){
            campos += `<script>document.querySelector('form[name=form${idL}]')
                               .querySelector('select[name=escalaA]').value=10</script>`;  
         }else{
            var aux = d['tipo_resp_entrada'].split('-'); 
          //  if(){
            campos += `<script>
            document.querySelector('form[name=form${idL}]').querySelector('select[name=escalaDe]').value=${aux[0]}
            document.querySelector('form[name=form${idL}]').querySelector('select[name=escalaA]').value=${aux[1]}
                </script>`;  
          //  }
         }
    } else if (tipo == 'NET_PROMOTER_SCORE') {
        var com_justificativa = { sim: '', nao: 'checked' };
        if (d['st_justificativa']=='sim') {
            com_justificativa = { sim: 'checked', nao: '' };
        }
        var rEmailCheck = 'checked';
        if (d['id']&&d['quadro']!='sim') {
            rEmailCheck = '';
        }
 
        campos += `<div class="form-group form-row">
        <div class="col" style="margin-top:0px">
          <label>Resposta com campo para justificativa?</label><br/>
          <label class="radio-inline" onclick="toggleBoxJust('${idL}','sim')"><input  ${com_justificativa['sim']} name="st_justificativa" value='sim' type="radio">Sim</label>
          <label class="radio-inline" onclick="toggleBoxJust('${idL}','não')"><input ${com_justificativa['nao']} name="st_justificativa" value='não' type="radio">Não</label>
        </div>
        <div class="col">
            <div class="form-check" style="margin-bottom: 5px;" id="divDretatEntrada${idL}">
                <input type="checkbox" onclick="checkNPSEmail()" name="quadro" ${rEmailCheck} class="form-check-input" value="sim" id="checkEntradaDetrator">
                <label class="form-check-label" for="checkEntradaDetrator">Receber um e-mail quando for escolhida uma opção de detrator (0 a 6)</label>
            </div>
        <input  name="entrada" placeholder='E-mail destinatário' class="form-control ${rEmailCheck?'':'d-none'}" id="NPSEmail" value="${d['entrada']?d['entrada']:''}" >
        </div>
        <script>
          function checkNPSEmail(){
              var input =  document.getElementById('NPSEmail');
              //if(!document.getElementById('checkEntradaDetrator').checked){
              //  input.value='';
              //}
              input.classList.toggle('d-none');
          }
        </script>
        </div>`;
        var jTipo_resp_entrada = { sim: 'checked', nao: '' };
        if (!d['tipo_resp_entrada'] || d['tipo_resp_entrada'] == '0') {
            jTipo_resp_entrada = { sim: '', nao: 'checked' };
        }
        campos += `<div class="form-group form-row" role='npsBoxJust${idL}' style="display:${(com_justificativa['sim']) ? '' : 'none'}">
        <div class="col" style="margin-top:0px">  
           <label for="just${idL}" > Pergunta da justificativa*: </label>
           <input  name="texto" class="form-control" id="just${idL}" value="${d['texto'] ? d['texto'] : ''}" >
        </div>
        <div class="col" style="margin-top:0px">  
          <label>Será obrigatório responder a justificativa:</label></br>
           <label class="radio-inline"><input ${jTipo_resp_entrada['sim']} name="tipo_resp_entrada" value='sim'type="radio">Sim</label>
           <label class="radio-inline"><input ${jTipo_resp_entrada['nao']} name="tipo_resp_entrada" value='não' type="radio">Não</label>
          </div>
        </div>
          `;
    }
    if(tipo == 'NET_PROMOTER_SCORE'){
        for (var i = 0; i < 10; i++) {
            if(i%2==0){
              campos += `<div class="form-group form-row" >`;
            }
            campos += `<div class="col" >
            <label for="resp${i}" > Resposta ${i + 1}: </label>
            <input   value="${i}" disabled type="text" id="resp${i}" class="form-control"/>
            </div>
            `;
            if(i%2!=0){
             campos += `</div>`;
            }
        }
                campos += `<div class="form-group form-row" >
            <div class="col" >
                <label for="resp10" > Resposta 11: </label>
                <input  value="10" disabled type="text" id="resp10" class="form-control"/>
            </div>
            <div class="col"></div>
        </div>
    `;
            
    }
    // campos += `<br/>
    // <div class="modal-footer">
    //   <input type="button" onclick="salvarPergunta(${idL},{'novaPergunta':true});" style="float: right;" class = "btn btn-primary font-weight-bold ${emAndamento?'bt-pesq-ativa':''}" value="Salvar e criar nova pergunta"/>
    //   <input type="button" onclick="salvarPergunta(${idL});" style="float: right;margin-right:0px;" class = "btn btn-primary font-weight-bold ${emAndamento?'bt-pesq-ativa':''}" value="Salvar"/>
    // </div>
    //   `;
    return campos;
}
function fnHtmlExibicaoAleatoria(n,check){
return `<div class="form-check" style="margin-bottom:5px;">
<input type="checkbox" name="resposta_randomica" ${check=='sim'?'checked':''} class="form-check-input" value="sim" id="checkAbert${n}">
<label class="form-check-label" for="checkAbert${n}">Exibição aleatória das respostas</label>
</div>`;
}

function mostrarQuadro(valor, n) {
    if (!valor || valor == 'EMAIL' || valor == 'TEXTO' || valor == 'DATA') {
        document.getElementById('divQuadro' + n).style.display = '';
    } else {
        document.getElementById('divQuadro' + n).style.display = 'none';
        document.getElementById('selecQuadro' + n).value = '';
    }
    if(valor == 'EMAIL' ){
        document.getElementById('divEmailEntrada' + n).style.display = '';
    }else{
        document.getElementById('divEmailEntrada' + n).style.display = 'none';
    }
}

function fnMontRespostaMultEscol2Col(num,data,foiDeletadoItem=false){
    var html = '';
    if(foiDeletadoItem){
       html += fnHtmlExibicaoAleatoria(num,document.querySelector('#checkAbert'+num).checked?'sim':'não');
    }
    for (var i in data) {
        if(i%2==0){
            html +=`<div class="form-group form-row" >
            <div class="col">${data[i]}</div>
            <div class="col new-resp${num}-lin${i}-col2" ><!--resp-col2-lin${i}--></div>
            </div>`;
        }else{
            html = html.replace(`<!--resp-col2-lin${i-1}-->`,data[i]);
        }
    }
    return html;
}

function pergunta(n, d, novaPergunta = false) {
    if(typeof d=='string'){
      d = JSON.parse(d);
    }

    var slTam = d['tam_fonte'] ? `` : `selected`;
    
    var respostas = '', displayForm = '', opsTipo = '<option value="">Escolha uma opção</option>',
        campos = '';
    for (var o of tipo_resposta) {
        opsTipo += `<option value="${o.valor}" ${(d['tipo_pergunta'] == o.valor) ? 'selected' : ''}>${o.text}</option>`;
    }

    var htmlPrgMesmaTela='';
    if (n > 1) {
        var exibir_mesma_tela = { sim: '', nao: 'checked' };
        if (d['exibir_mesma_tela'] && d['exibir_mesma_tela'] == 'sim') {
            exibir_mesma_tela = { sim: 'checked', nao: '' };
        }
 
        // campos += `<div class="form-group" role="respObrig${n}">
        htmlPrgMesmaTela = `
         <label style="margin-left:5px;">Mostar na mesma tela da pergunta anterior?</label> 
         <label class="radio-inline"><input  name="exibir_mesma_tela"  ${exibir_mesma_tela['sim']} value='sim' type="radio">Sim</label>
         <label class="radio-inline"><input   name="exibir_mesma_tela"  ${exibir_mesma_tela['nao']} value='não' type="radio">Não</label>
         `;
    }
    
    campos += ` <div class="d-flex justify-content-between box-pergunta-paginacao" style="height: 33px;" >
                    <div style="margin-top:-5px" ><h5 class="py-1" >Pergunta ${n}</h5></div>
                     ${(!novaPergunta)?createPagination(n):''}
                </div>`
                campos += `<div class="form-group" style="margin-bottom:0px;margin-left:-5px;" >
                ${htmlPrgMesmaTela}
                </div>`;
                var resposta_obrig = { sim: 'checked', nao: '' };
                if (d['resposta_obrig'] != 'sim'&&d['resposta_obrig']) {
                    resposta_obrig = { sim: '', nao: 'checked' };
                }
                campos += `<div class="form-group form-row" >
                <div class="col">
                 <label for="selecTipo${n}" > Tipo de pergunta*: </label>
                 <select name="tipo_pergunta" onchange="openModalAvisoTipo(this.value,${n})" class="form-control" id="selecTipo${n}" >${opsTipo}</select>
                </div>
                <div class="col" role="respObrig${n}" >
                <label>Será obrigatório responder essa pergunta: </label> <br/>
                <label class="radio-inline"><input ${resposta_obrig['sim']}  checked name="resposta_obrig" value='sim' type="radio">Sim</label>
                <label class="radio-inline"><input ${resposta_obrig['nao']} name="resposta_obrig" value='não' type="radio">Não</label>
               </div>
               </div>`;
    campos += `<input type="hidden" name="pesquisa" value="${codPesquisa}" />
    <input type="hidden" name="ordem" value="${n}" />`;
    campos += `<div class="form-group" id="perg-titulo-${n}" style="display:${d['tipo_pergunta']=='MOSTRAR_TEXTO'?'none':''}" >
    <label for="perg${n}" >Escreva aqui sua pergunta*: </label>
      <input value="${d['titulo']}"  name="titulo" type="text" id="perg${n}" class="form-control" >
    </div>`;

    campos += `<div class="form-group form-row">
        <div class="col" style="margin-top:0px" >
          <label for="desc${n}" > Descrição: </label>
          <input  name="descricao" class="form-control" value="${d['descricao'] ? d['descricao'] : ''}" id="desc${n}" />
          </div>
        <div class="col" >
          <label for="selecTamFonte${n}" > Tamanho da fonte: </label>
          <select name="tam_fonte" class="form-control" id="selecTamFonte${n}">
          <option value="large" ${(d['tam_fonte'] == 'large') ? 'selected' : ''} >Grande</option>
          <option value="medium" ${(d['tam_fonte'] == 'medium') ? 'selected' : ''} ${slTam}>Médio</option>
          <option value="small" ${(d['tam_fonte'] == 'small') ? 'selected' : ''}>Pequena</option>
          </select>
        </div>
     </div>
     `;
    
    if (!novaPergunta) {
        respostas = mtRespostas(d, n);
        if (n != pergunta_atual) {
            displayForm = 'none';
        }
    }

    campos += `<div data-resposta="resp${n}" >${respostas}</div>`;
    // <input type="button" onclick="salvarPergunta(this,${n},{'novaPergunta':true});" style="float: right;" class = "btn btn-primary font-weight-bold bt-salvar ${emAndamento?'bt-pesq-ativa':''}" value="Salvar e criar nova pergunta"/>
    campos += `<br/>
    <div class="modal-footer" style="justify-content:center;padding-bottom:3px;">
      <input type="button" ${modPesquisa=='PRODUCAO'?'disabled':''} onclick="salvarPergunta(this,${n},{'novaPergunta':true});" style="float: right;" class = "btn btn-primary font-weight-bold bt-salvar " value="Salvar e criar nova pergunta"/>
      <input type="button" ${modPesquisa=='PRODUCAO'?'disabled':''} onclick="salvarPergunta(this,${n});" style="float: right;margin-right:0px;" class = "btn btn-primary font-weight-bold bt-salvar " value="Salvar"/>
    </div>
      `;
   // campos += `<input type="button" onclick="salvarPergunta(${n});" style="float: right;margin-right: 40px;" class = "btn btn-primary" value="Salvar"/>  `;

    if (n == pergunta_atual && d['status_encadeamento']) {
        document.querySelector('[data-bt=encadear] ').getElementsByTagName('span')[0].style.display = '';
    }
    var exibeVGreal = arUlr.includes('visao-geral');
    if(exibeVGreal){
        displayForm='';
    }
    
    var form = `<form   name="form${n}" onsubmit="ruturn false;" data-encadeamento="${d['status_encadeamento']}" data-pergunta="${d['id']?d['id']:''}"
                role="form_perguntas" style="display:${displayForm}" >${campos}</form>`;

    if(exibeVGreal){ 
      return form;
    }else{
        $(form).appendTo("[data-perg=formulario]");
    }

    if (d['tipo_pergunta'] == 'MOSTRAR_TEXTO') {
        document.querySelector(`[role=respObrig${n}]`).style.display = 'none';
    }
}
var avisoTipo=null,avisoNum=null;
function openModalAvisoTipo(tipo, n){
    if (dadosPerguntas.length) {
        if(!checkOcorrenciaNPS(tipo, n)){
            return false;
        }
    }
    if(tipo&&document.querySelector(`form[name=form${n}]`).getAttribute('data-pergunta')){
        avisoTipo=tipo,avisoNum=n;
        $('#modalAvisoChangeTipo').modal('show');
    }else{
        tipoResposta(tipo, n);
    }
}
function actionModalAviso(){
    tipoResposta(avisoTipo, avisoNum);
    $('#modalAvisoChangeTipo').modal('hide');
}

function tipoResposta(tipo, n) {
    htmlRes = '';
    if(tipo=='MOSTRAR_TEXTO'){
        document.querySelector(`#perg-titulo-${n}`).querySelector(`input`).value = '';
        document.querySelector(`#perg-titulo-${n}`).style.display = 'none';
    }else{
        document.querySelector(`#perg-titulo-${n}`).style.display = '';
    }
    if (dadosPerguntas.length) {
        if(!checkOcorrenciaNPS(tipo, n)){
            return false;
        }
    }
    if (['ESCALA_LIKERT', 'ESCALA_INTENCAO_COMPRA', 'ESCALA_ITEMIZADA'].indexOf(tipo) > -1) {
        for (var i in respostaFixas[tipo]) {
            htmlRes += `<div class="form-group" > 
            <label for="perg${i}" > Resposta ${i}: </label>
            <input   name="resposta${i + 1}" type="text" id="perg${i}" value='${respostaFixas[tipo][i].r}' class="form-control" />
            </div>`;
        }
        htmlRes = `<div role='box_dinamic_resposta${n}' >${htmlRes}</div>`;
        htmlRes += `<div class="modal-footer">
                      <input type="button" onclick="salvarPergunta(${n});" style="float: right;margin-right: 40px;" class = "btn btn-primary font-weight-bold" value="Salvar"/> 
                    </div>`;
    } else {
        htmlRes = mtRespostas({ tipo_pergunta: tipo, respostas: [], matriz: { LINHA: [], COLUNA: [] } }, n);
    }
    $(`[data-resposta=resp${n}]`).html(htmlRes);
    if(tipo=="NET_PROMOTER_SCORE"){
        if(!$('#perg'+n).val()){
            $('#perg'+n).val('De 0 a 10, quanto recomendaria a EMPRESA/MARCA para am e/ou parentes?');
        }
    }
}

function checkOcorrenciaNPS(tipo,n){
    var numNPS = 0;
    for (let ob of dadosPerguntas) {
        if (ob.tipo_pergunta == 'NET_PROMOTER_SCORE') {
            numNPS += 1;
        }
    }
    
    if (numNPS >= 1 && tipo == 'NET_PROMOTER_SCORE') {
        alertaPadrao.returnMensagem('Só é possível escolher uma pergunta do tipo net promoter score por pesquisa.', 'top', 'info');
        $('#selecTipo' + n).val('');
        return false;
    }
    return true;
}

function irPage(p){
    pergunta_atual=p;
    mostraPergunta(p,false);
    checkPergEncadeada(p);
    if(p<=1){
      exibirItensPrimeiraTela(false);
    }else{
      exibirItensPrimeiraTela(true);
    }

    if(p>=dadosPerguntas.length){
        esconderItensUltimaTela(true);
    }else{
        esconderItensUltimaTela(false);
    }
}
function createPagination(num){
     function getTitle(n){
        return (dadosPerguntas[n]['tipo_pergunta']=='MOSTRAR_TEXTO')?dadosPerguntas[n]['texto']:dadosPerguntas[n]['titulo'];
     }
   let total=dadosPerguntas.length,html='';
   if(total){
        html += `        
        <nav role='pagination' aria-label="Navegação">
        <ul class="pagination">`;
        if(num>1){
             html += `<li class="page-item">
                <a class="page-link sm-link" title="${getTitle(0)}" onclick="irPage(1)" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
            html += `<li class="page-item">
            <a class="page-link sm-link" title="${getTitle(num-2)}" onclick="irPage(${num-1})" aria-label="Anterior">
                <span aria-hidden="true" style="font-size:12px">&lt;</span>
            </a>
            </li>`;
        }
        if(num>1){
            if(num==2){
             html += `<li class="page-item sm-link" title="${getTitle(0)}" onclick="irPage(1)"><a class="page-link">1</a></li>`;
            }else{
                for(var i=num-2;i<num;i++){
                    html += `<li class="page-item sm-link" title="${getTitle(i-1)}" onclick="irPage(${i})"><a class="page-link" >${i}</a></li>`;
                }
            }
        }
        // html += `<li class="page-item active  ${emAndamento?'pagination-ativa':''}""><a class="page-link">${num}</a></li>`;
        html += `<li class="page-item active"><a class="page-link">${num}</a></li>`;

        if(num+2<=total){
            for(var i=num+1;i<num+3;i++){
                html += `<li class="page-item sm-link" title="${getTitle(num)}"  onclick="irPage(${i})"><a class="page-link" >${i}</a></li>`;
            }
        }else if(num+1==total){
            html += `<li class="page-item sm-link" title="${getTitle(num)}" ><a onclick="irPage(${num+1})" class="page-link">${num+1}</a></li>`;
        }
        
        if(num<total){
        html += `
            <li class="page-item" title="${getTitle(num)}" >
                <a class="page-link sm-link" onclick="irPage(${num+1})" aria-label="Próximo">
                    <span aria-hidden="true" style="font-size:12px">&gt;</span>
                </a>
            </li>`;
        html += `
            <li class="page-item" title="${getTitle(total-1)}" >
            <a class="page-link sm-link" onclick="irPage(${total})" aria-label="Próximo">
                <span aria-hidden="true">&raquo;</span>
            </a>
            </li>`;
        }
        html += `</ul>
        </nav>`;
   }
 return html;
}

function gUrl() {
    var base = window.location.href.split('/'), p = 1;
    if (base.length) {
        base = base[base.length - 1];
        if (base.substring(0, 2) == 'p.') {
            p = base.replace(/[^0-9]/g, '');
        }
    }
    return parseInt(p);
}

function navBoxs(tipo) {
    if(['ordenar','novaPergunta','visaoGeral','principal'].includes(tipo)){
        var nomeClass =emAndamento?"nav-menu-perguntas-ativa-a":'nav-menu-perguntas-inativa-a';
        for(var ob of document.querySelectorAll('[aba=resposta]')){
         ob.classList.remove("nav-menu-perguntas-ativa-a");
         ob.classList.remove("nav-menu-perguntas-inativa-a");
        }
        document.querySelector(`[data-bt=${tipo}]`).classList.add(nomeClass);
    }
    if (tipo == 'principal'){
        location.reload();
        return;
    }
    // document.querySelector('[data-box=perguntas]').style.display = 'none';
    // document.querySelector('[data-box=visao]').style.display = 'none';
    // document.querySelector('[data-box=lista]').style.display = 'none';
    if (tipo == 'ordenar') {
        document.querySelector('[data-bt=encadear]').style.display = 'none';
        document.querySelector('[data-bt=duplicar]').style.display = 'none';
        document.querySelector('[data-bt=excluir]').style.display = 'none';
        location.reload();
        return;
        /*document.querySelector('[data-box=lista]').style.display = '';*/
    } else if (tipo == 'visaoPergunta'){
        document.querySelector('[data-box=perguntas]').style.display = '';
    } else if (tipo == 'visaoGeral'){
        document.querySelector('[data-bt=duplicar]').style.display = 'none';
        document.querySelector('[data-bt=encadear]').style.display = 'none';
        document.querySelector('[data-bt=excluir]').style.display = 'none';
        location.reload();
        return;
       /* document.querySelector('[data-box=visao]').style.display = '';*/
    } else if (tipo == 'novaPergunta'){
         location.reload();
        // document.querySelector('[data-box=perguntas]').style.display = 'none';
        document.querySelector('[data-box=visao]').style.display = 'none';
        document.querySelector('[data-box=lista]').style.display = 'none';

        document.querySelector('[data-bt=encadear]').style.display = 'none';
        // document.querySelector('[data-bt=excluir]').style.display = 'none';
        // document.querySelector('.bt-anterior').style.display = 'none';
        document.querySelector('[data-box=perguntas]').style.display = '';
        addPergunta();
    }
}

function esconderItensUltimaTela(esconder){
    if(esconder){
        document.querySelector('.bt-proximo').style.display = 'none';
    }else{
        document.querySelector('.bt-proximo').style.display = '';
    }
}

function exibirItensPrimeiraTela(exibir){
   if(exibir){
    document.querySelector('.bt-anterior').style.display = '';
    document.querySelector('[data-bt=encadear]').style.display = '';
    document.querySelector('[data-bt=duplicar]').style.display = '';
    //document.querySelector('[data-bt=excluir]').style.display = '';
   }else{
    document.querySelector('.bt-anterior').style.display = 'none';
    document.querySelector('[data-bt=duplicar]').style.display = 'none';
    document.querySelector('[data-bt=encadear]').style.display = 'none';
   // document.querySelector('[data-bt=excluir]').style.display = 'none';
   }
}

function navPerguntas(op) {
    dadosEncadaemento = [];
    document.querySelector('form[name=form' + pergunta_atual + ']').style.display = 'none';
    if (op == 'ANTERIOR') {
        pergunta_atual -= 1
        if (pergunta_atual <= 1) {
            exibirItensPrimeiraTela(false);
        }
        if (nova_pergunta) {
            // document.querySelector('[data-bt=FinalizarNovaPergunta]').style.display = '';
            // document.querySelector('[role=novaPergunta]').style.display = '';
            
        }
        abasPesquisa('principal');
        // document.querySelector('[role=novaPergunta]').style.display = 'none';
        document.querySelector('[data-bt=duplicar]').style.display = '';
        document.querySelector('[data-bt=encadear]').style.display = '';
        // document.querySelector('.abas-perguntas').style.display = '';
        esconderItensUltimaTela(false);
    } else if (op == 'PROXIMO') {
        pergunta_atual += 1;
        if (pergunta_atual + 1 > parseInt(perguntasTotal)) {
            esconderItensUltimaTela(true);
        }
        exibirItensPrimeiraTela(true);
        if (nova_pergunta == pergunta_atual) {
            abasPesquisa('nova-pergunta');
            // document.querySelector('[data-bt=FinalizarNovaPergunta]').style.display = 'none';
            // document.querySelector('[role=novaPergunta]').style.display = '';
            document.querySelector('[data-bt=duplicar]').style.display = 'none';
            document.querySelector('[data-bt=encadear]').style.display = 'none';
        }
    }
    checkPergEncadeada(pergunta_atual);

    document.querySelector('form[name=form' + pergunta_atual + ']').style.display = '';
    document.querySelector('[role=pAtual]').innerHTML = pergunta_atual;
    setarUrl(pergunta_atual);
    $('html,body').animate({ scrollTop: 0 + $('#id-topo-p').offset().top }, 'slow');
}

function checkPergEncadeada(numPerg){
    if (document.querySelector('form[name=form' + numPerg+ ']').getAttribute('data-encadeamento') == 'true') {
        document.querySelector('[data-bt=encadear] ').getElementsByTagName('span')[0].style.display = '';
    } else {
        document.querySelector('[data-bt=encadear] ').getElementsByTagName('span')[0].style.display = 'none';
    }
}

function setarUrl(n) {
    var arUrl = window.location.href.split('/'), novaUrl = [];
    for (var i in arUrl) {
        if (i < arUrl.length - 1) {
            novaUrl[i] = arUrl[i];
        }
    }
    window.history.pushState("object or string", "Title", novaUrl.join('/') + '/p.' + n);
}

function addResposta(tipo, n) {
    boxPai = document.querySelectorAll(`[role=resp_dinamic_${tipo}${n}]`);
    var num = parseInt(boxPai[boxPai.length - 1].getAttribute('data-numero')) + 1;
    var resp = mtCamposDinamic(tipo, n, boxPai.length, '', '');
    if(boxPai.length%2==0){
        var html =`<div class="form-group form-row" >
        <div class="col">${resp}</div>
        <div class="col new-resp${n}-lin${num}-col2" ></div>
        </div>`;
        $(html).appendTo(`[role=box_dinamic_${tipo}${n}]`);
    }else{
        if(boxPai[boxPai.length-1].parentNode.parentNode.children[1]){
            boxPai[boxPai.length-1].parentNode.parentNode.children[1].innerHTML=resp;
        }else{
            document.querySelector(`.new-resp${n}-lin${num-1}-col2`).innerHTML=resp;
        }    
    }
}

function addPergunta() {
    var numero = nova_pergunta;
    if (!numero) {
        numero = parseInt(document.querySelectorAll(`[role=form_perguntas]`).length) + 1;
        var dados = { pergunta: '', pergunta: '', titulo: '', tipo_pergunta: '', descricao: '', resposta_obrig: '', st_justificativa: "", tipo_resp_entrada: '' };
        pergunta(numero, dados, true);
        nova_pergunta = numero;
        document.querySelector('[role=pTotal]').innerHTML = perguntasTotal += 1;
    }
    // document.querySelector('[data-bt=novaPergunta]').style.display = 'none';
    // document.querySelector('[role=novaPergunta]').style.display = '';
    // document.querySelector('.abas-perguntas').style.display = 'none';
    document.querySelector('[data-bt=duplicar]').style.display = 'none';
    document.querySelector('.bt-proximo').style.display = 'none';
    mostraPergunta(numero);
    forms=document.querySelectorAll('form[role=form_perguntas]');
    for (var i in forms) {
        if(typeof forms[i] == 'object'){
            for(let bt of forms[i].querySelectorAll('button,input,select,textarea')){
                bt.removeAttribute('disabled');
            }
        }
    }
}

function deletarResposta(tipo, idL, n) {
    var codPergunta = document.getElementsByName('form' + idL)[0].getAttribute('data-pergunta');
    if(tipo=='imagem'){
        // var cod = document.querySelector(`[role='resp_dinamic_${tipo}${idL}']`).getAttribute('data-resp');
    }else{
        var obj = document.querySelector(`[data-idresp=${tipo}${idL}${n}]`), numero = 1;
        var cod = obj.getElementsByTagName('input')[0].getAttribute('data-resp');
    }
  
    function dOrdem(obj) {
        obj.parentNode.removeChild(obj, tipo, numero, idL);
        var data=[],dValues=[];
        for (var obj of document.querySelectorAll(`[role=resp_dinamic_${tipo}${idL}]`)) {
            obj.children[0].innerHTML = ` ${tipo}  ${numero++}:`;
            data.push(obj.parentNode.innerHTML);
            dValues.push({key:obj.parentNode.querySelector('[data-idresp]').getAttribute('data-idresp'),val:obj.parentNode.querySelector('input').value});
        }
       document.querySelector(`[role=box_dinamic_resposta${idL}]`).innerHTML= fnMontRespostaMultEscol2Col(idL,data,true);
       for(var ob of dValues){
         document.querySelector(`[data-idresp=${ob.key}]`).querySelector(`input`).value=ob.val;
       }
    }
    // function dOrdemImg(obj) {
    //     document.querySelector(`[data-idresp="${tipo}${idL}${n}"]`).remove();
    //     for (var obj of document.querySelectorAll(`[role=resp_dinamic_${tipo}${idL}]`)) {
    //         obj.getElementsByTagName('label').innerHTML = ` ${tipo}  ${numero++}:`;
    //     }
    // }
    if (cod) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>Deseja excluir essa resposta?</h6></div>')
            .dialog({
                autoOpen: true,
                modal: true, title: 'Alerta', zIndex: 10000, autoOpen: true,
                width: 400,
                buttons: [
                    {
                        text: "Ok",
                        click: function () {
                            var ajax = new XMLHttpRequest(), form_data = new FormData();
                            form_data.append('id', cod);
                            form_data.append('pergunta', codPergunta);
                            form_data.append('pesquisa', codPesquisa);
                            ajax.onload = function (e) {
                                var r = JSON.parse(this.responseText);
                                if (r.status) {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                    if(tipo=='imagem'){
                                        dOrdemImg();
                                    }else{
                                        dOrdem(obj, tipo, numero, idL);
                                        //dOrdem(document.querySelector(`[data-idresp=${tipo}${idL}${n}]`));
                                    }
                                } else {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                                }
                            };
                            ajax.open('POST', baseUrl + 'perguntas/resposta/delete', true);//faz a requisicao dos dados, via post
                            ajax.send(form_data);//envia o form
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
    } else {
        if(tipo=='imagem'){
            dOrdemImg();
        }else{
        dOrdem(obj, tipo, numero, idL);
        }
    }
}


function duplicarPergunta() {
    if(modPesquisa=='PRODUCAO'){
        alertaPadrao.returnMensagem('No modo de produção não é possível duplicar as perguntas.', 'top', 'info');
        return false;
    }
    var cod = document.querySelector('form[name=form' + pergunta_atual + ']').getAttribute ('data-pergunta');
    if (cod) {
        // if(document.getElementById('selecTipo'+pergunta_atual).value=='NET_PROMOTER_SCORE'){
        //     alertaPadrao.returnMensagem('Só é possível ter um tipo de pergunta net promoter score por pesquisa.', 'top', 'info');
        //     return false;
        // }
        $('<div></div>').appendTo('body')
            .html('<div><h6>Deseja duplicar essa pergunta?</h6></div>')
            .dialog({
                autoOpen: true,
                modal: true, title: 'Alerta', zIndex: 10000, autoOpen: true,
                width: 400,
                buttons: [
                    {
                        text: "Ok",
                        click: function (obj) {
                            var ajax = new XMLHttpRequest(), form_data = new FormData();
                            form_data.append('id', cod);
                            form_data.append('pesquisa', codPesquisa);
                            ajax.onload = function (e) {
                                var r = JSON.parse(this.responseText);
                                if (r.status) {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                    setarUrl(pergunta_atual + 1);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 3000);
                                } else {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                                }
                            };
                            ajax.open('POST', baseUrl + 'perguntas/duplicar', true);//faz a requisicao dos dados, via post
                            ajax.send(form_data);//envia o form
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
}

 function criarNps(){
    $('#modalAvisoNps').modal('hide');
    abasPesquisa('nova-pergunta');
    navBoxs('novaPergunta');
    document.getElementById('selecTipo'+nova_pergunta).value='NET_PROMOTER_SCORE';
     tipoResposta('NET_PROMOTER_SCORE',nova_pergunta);
}

function mostraPergunta(numero, relaod = false) {
    for(var ob of document.querySelectorAll('form[role="form_perguntas"]')){
      ob.style.display = 'none';
    }
    // document.querySelector('form[name=form' + pergunta_atual + ']').style.display = 'none';
    pergunta_atual = numero;
    document.querySelector('form[name=form' + pergunta_atual + ']').style.display = '';
    document.querySelector('[role=pAtual]').innerHTML=pergunta_atual;
    setarUrl(pergunta_atual);
    if (relaod) {
        location.reload();
    }
}

function salvarPergunta(obj,n,data={}) {
    var seletorForm = document.getElementsByName('form' + n)[0];
    var form_data = new FormData(seletorForm);
    var tipoPergunta = form_data.get('tipo_pergunta');
    form_data.append('id', seletorForm.getAttribute('data-pergunta'));
    if (!tipoPergunta) {
        alertaPadrao.returnMensagem('Preecha o campo tipo de pergunta', 'top', 'infor');
        return false;
    }
    if (!form_data.get('titulo') && tipoPergunta != 'MOSTRAR_TEXTO') {
        alertaPadrao.returnMensagem('Preecha o campo escreva aqui sua pergunta', 'top', 'infor');
        return false;
    }
    if (!form_data.get('texto') && tipoPergunta == 'MOSTRAR_TEXTO') {
        alertaPadrao.returnMensagem('Preecha o campo texto ', 'top', 'infor');
        return false;
    }
    ////////  - - - - - - - - - - - 
    if (tipoPergunta == 'NET_PROMOTER_SCORE') {
        if (form_data.get('st_justificativa') == 'sim' && form_data.get('tipo_resp_entrada') == 'sim' && !form_data.get('texto')) {
            alertaPadrao.returnMensagem('Preecha o campo pergunta da justificativa', 'top', 'infor');
            return false;
        }else if(form_data.get('st_justificativa') != 'sim'){
            form_data.set('texto','');
        }

        if(document.getElementById('checkEntradaDetrator').checked){
            if(!form_data.get('entrada')){
                alertaPadrao.returnMensagem('Preecha o campo e-mail destinatário', 'top', 'infor');
                return false;   
            }
            if (!/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                .test(form_data.get('entrada'))) {
                    alertaPadrao.returnMensagem('O e-mail destinatário não é válido', 'top', 'infor');
                    return false;
            }
            form_data.append('quadro','sim');
        }else{
            form_data.append('quadro','não');
        }
        // form_data.append('quadro',(document.querySelector('form[name=form' + n + ']')
        //     .querySelector('input[name=entrada]').checked)?'sim':'não');
    } else if (tipoPergunta == 'ESCALA_LIKERT_0_10') {
        var de = form_data.get('escalaDe');
       var a = form_data.get('escalaA');
        if(de>=a){
            alertaPadrao.returnMensagem('O número inicial da escala não pode ser maior ou igual ao número de encerramento.', 'top', 'infor');
      return false;
        }

        form_data.append('tipo_resp_entrada',de+'-'+a)
        form_data.delete('escalaA');
        form_data.delete('escalaDe');
    } else if (tipoPergunta == 'DICOTOMICA') {
        if (!form_data.get('tipo_resp_entrada')) {
            alertaPadrao.returnMensagem('Preecha o campo opção de entrda', 'top', 'infor');
            return false;
        }
    } else if (tipoPergunta == 'ABERTA') {
        if (!form_data.get('tipo_resp_entrada')) {
            alertaPadrao.returnMensagem('Preecha o campo tipo de caracter', 'top', 'infor');
            return false;
        }
        if(form_data.get('tipo_resp_entrada')=='EMAIL'){
            form_data.append('entrada',(document.querySelector('form[name=form' + n + ']')
            .querySelector('input[name=entrada]').checked)?'sim':'não');
        }
    }

    if (['ESCALA_LIKERT', 'ESCALA_INTENCAO_COMPRA', 'ESCALA_ITEMIZADA', 'FECHADA_RESPOSTA_MULTIPLA',
        'FECHADA_RESPOSTA_UNICA', 'SEMI_ABERTA'].indexOf(tipoPergunta) > -1) {
        var objResposta = document.querySelector('[role=box_dinamic_resposta' + n + ']').querySelectorAll('input[type=text]'),
            dadosResposta = [], numResp = 0;
        for (var input of objResposta) {
            // var obInput = ob.getElementsByTagName('input')[0];
            if (!input.value) {
                alertaPadrao.returnMensagem('Preecha o campo resposta ' + (numResp + 1), 'top', 'infor');
                return false;
            }
        
            form_data.delete(input.getAttribute('name'));
            var cod = (input.getAttribute('data-resp')) ? input.getAttribute('data-resp') : '';
            dadosResposta[numResp] = { cod: cod, titulo: input.value };
            numResp++;
        }
        form_data.append('resposta_randomica',(document.querySelector('[role=box_dinamic_resposta' + n + ']')
                             .querySelector('input[name=resposta_randomica]').checked)?'sim':'não');
        form_data.append('respostas', JSON.stringify(dadosResposta));
    } else if (tipoPergunta == 'MATRIZ') {
        var objLinhas = document.querySelectorAll('[role=box_dinamic_linha' + n + ']')[0].children,
            objColunas = document.querySelectorAll('[role=box_dinamic_coluna' + n + ']')[0].children,
            dadosMatriz = { LINHA: [], COLUNA: [] }, numResp = 0;
        for (var ob of objLinhas) {
            var obInput = ob.getElementsByTagName('input')[0];
            if (!obInput.value) {
                alertaPadrao.returnMensagem('Preecha o campo linha ' + (numResp + 1), 'top', 'infor');
                return false;
            }
            form_data.delete(obInput.getAttribute('name'));
            var cod = (obInput.getAttribute('data-resp')) ? obInput.getAttribute('data-resp') : '';
            dadosMatriz['LINHA'][numResp] = { cod: cod, titulo: obInput.value };
            numResp++;
        }
        numResp = 0;
        for (var ob of objColunas) {
            var obInput = ob.getElementsByTagName('input')[0];
            if (!obInput.value) {
                alertaPadrao.returnMensagem('Preecha o campo coluna ' + (numResp + 1), 'top', 'infor');
                return false;
            }
            form_data.delete(obInput.getAttribute('name'));
            var cod = (obInput.getAttribute('data-resp')) ? obInput.getAttribute('data-resp') : '';
            dadosMatriz['COLUNA'][numResp] = { cod: cod, titulo: obInput.value };
            numResp++;
        }
        form_data.append('matriz', JSON.stringify(dadosMatriz));
    }else if(tipoPergunta == 'UNICA_ESCOLHA_IMAGEM' || tipoPergunta == 'MULTIPLA_ESCOLHA_IMAGEM'){
        var objImage = document.querySelectorAll('[role=box_dinamic_imagem' + n + ']')[0].children,
        numResp = 0,dadosResposta = [];
        for (var ob of objImage) {
            var objImg = ob.getElementsByTagName('input')[1];
            if(!objImg.value){
                alertaPadrao.returnMensagem('Preecha a imagem ' + (numResp + 1), 'top', 'infor');
                return false;  
            }
         
            var cod = (objImg.getAttribute('data-resp')) ? objImg.getAttribute('data-resp') : '';
            dadosResposta[numResp] = { cod: cod, titulo:document.querySelector('[name=desc-img-'+n+numResp+']').value,imagem:objImg.value };
            form_data.delete(objImg.getAttribute('name'));
            numResp++;
        }
        form_data.append('resposta', JSON.stringify(dadosResposta));
           }
           form_data.append('encadeamento', dadosEncadaemento);
           var clickSalvar =(obj.value=='Salvar')?true:false;
           obj.value = "Salvando...";
           $('.bt-salvar').attr('disabled',true);
           
    ajax = new XMLHttpRequest();
    ajax.onload = function (e) {
        var r = JSON.parse(this.responseText);
        if (r.status) {
            alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
            $('html,body').animate({ scrollTop: 0 + $('#id-topo-p').offset().top }, 'slow');
            if(data['novaPergunta']){
                obj.value = "Salvar e criar nova pergunta";
                nova_pergunta=0;
                addPergunta();
                $('[data-bt=principal]').removeClass('active');
                $('[data-bt=principal]').parent().css('display','');
            }else{
                obj.value = "Salvar";
                setTimeout(function () {
                    location.reload();
                }, 3000);
            }
        } else {
            if(clickSalvar){
                obj.value = "Salvar";
            }else{
                obj.value = "Salvar  e criar nova pergunta";
            }
            $('.bt-salvar').removeAttr('disabled');
            alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
        }
    };
    ajax.open('POST', baseUrl + 'perguntas/salvar', true);//faz a requisicao dos dados, via post
    ajax.send(form_data);//envia o form
    return false;
}

function encadearPergunta() {
    if (pergunta_atual > 1) {
        var form=document.querySelector('form[name=form' + pergunta_atual + ']');
        var cod = form.getAttribute('data-pergunta');
        var tituloPergunta = form.querySelector('[name=titulo]').value;
        var form_data = new FormData();
        form_data.append('pesquisa', codPesquisa);
        form_data.append('posicao', pergunta_atual);
        form_data.append('id', cod);
        ajax = new XMLHttpRequest();
        ajax.onload = function (e) {
            var r = JSON.parse(this.responseText),htmlRelp='',html='';
            if (r.status) {
                 for(let p of r.perguntas){
                    if (p.tipo_pergunta == 'NET_PROMOTER_SCORE') {
                        htmlRelp = '';
                        for (i = 0; i <= 10; i++) {
                            htmlRelp += `<div class='form-group' role=''>
                                      <label onclick='checkEncad(${p.id})' class='radio-inline'>
                                      <input role='resposta${p.id}'  name='' value='${i}' type='checkbox'> ${i}</label> 
                                     </div>`;
                        }
                       } else if (p.tipo_pergunta == 'AVALIACAO_SATISFACAO') {
                        htmlRelp = '';
                        htmlRelp += `
                                <div class='form-group' role=''>
                                      <label onclick='checkEncad(${p.id})' class='radio-inline'>
                                       <input  role='resposta${p.id}' value='5' type='checkbox'> Ótimo
                                      </label> 
                                     </div>
                                <div class='form-group' role=''>
                                      <labelonclick='checkEncad(${p.id})' class='radio-inline'>
                                      <input  role='resposta${p.id}' value='4' type='checkbox'> Bom</label> 
                                     </div>
                                <div class='form-group' role=''>
                                      <label onclick='checkEncad(${p.id})' class='radio-inline'>
                                      <input  role='resposta${p.id}' value='3' type='checkbox'> Regular</label> 
                                     </div>
                                <div class='form-group' role=''>
                                      <label onclick='checkEncad(${p.id})' class='radio-inline'>
                                      <input  role='resposta${p.id}' value='2' type='checkbox'> Ruim</label> 
                                     </div>
                                <div class='form-group' role=''>
                                      <label onclick='checkEncad(${p.id})' class='radio-inline'>
                                      <input  role='resposta${p.id}' value='1' type='checkbox'> Péssimo</label> 
                                     </div>
                                     `;        
                       } else if (['FECHADA_RESPOSTA_MULTIPLA','FECHADA_RESPOSTA_UNICA','ESCALA_LIKERT_0_10'].includes(p.tipo_pergunta)) {
                        htmlRelp = '';
                        for(let resp of p.respostas){
                            htmlRelp += `<div class='form-group' role=''>
                            <label onclick='checkEncad(${p.id})' class='radio-inline'>
                            <input name='resposta${p.id}' role='resposta${p.id}' value='${resp.id}' type='checkbox'> ${resp.titulo}</label> 
                           </div>`;
                        }
                       }
                       if (htmlRelp) {
                        html += `<h3>${p.titulo}</h3>
                                 <div data-encadeamento='p-encad' data-pergunta='${p.id}'>${htmlRelp}</div>`;
                       }
                }
                document.querySelector('[role=accordionEncadeamento]')
                    .innerHTML = `<div id="accordionEncadeamento" ><h3 onclick="semEncadeamento()">Sem encadeamento</h3>
                    <div data-encadeamento='p-encad' data-pergunta='sem_encadeamento' >
                                <input  onclick='checkEncad("sem_encadeamento")' name='respostasem_encadeamento' 
                                     value='sem_encadeamento' type='radio'> Pergunta sem encademento
                                </div>
                    ${html}
                    </div>`;

                var active = 9999;
                if(Object.keys(r.encadeamento).length){
                    if (r.encadeamento.posicao !== undefined) {
                        active = r.encadeamento.posicao + 1;
                    } else {
                        active = 0;
                    }
                }
                 document.querySelector('[data-encadear=titulo]').innerHTML = tituloPergunta;
                 document.querySelector('[data-encadear=num]').innerHTML = pergunta_atual;
                $('#modalEncadeamento').modal();
                $("#accordionEncadeamento").accordion({heightStyle: '', active: active,});
               
                if (Object.keys(r.encadeamento).length) {         
                    // if (['NET_PROMOTER_SCORE', 'FECHADA_RESPOSTA_MULTIPLA', 'FECHADA_RESPOSTA_UNICA'].indexOf(r.encadeamento.tipo) > -1) {
                        for (var ob of document.querySelectorAll('input[role=resposta' + r.encadeamento.id + ']')) {
                            if (r.encadeamento.respostas.indexOf(ob.value) > -1) {
                                ob.checked = true;
                            }
                        }
                    // } else {
                    //     for (var ob of document.querySelectorAll('[name=resposta' + r.encadeamento.id + ']')) {
                    //         if (ob.value == r.encadeamento.respostas[0]) {
                    //             ob.checked = true;
                    //         }
                    //     }
                    // }
                } else {
                    document.querySelector('[name=respostasem_encadeamento]').checked = true;
                }
            } else {
                alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
            }
        };
        ajax.open('POST', baseUrl + 'perguntas/encadeada-listar', true);//faz a requisicao dos dados, via post
        ajax.send(form_data);//envia o form
        return false;

    }
}

function enviarEncadeameneto() {
    dadosEncadaemento = [];
    var p = document.querySelectorAll('[data-encadeamento=p-encad]'), status = false;
    if (p[0].getElementsByTagName('input')[0].checked) {
        dadosEncadaemento = {cod: 'sem_encadeamento',resp:['sem_encadeamento']}
        status = true;
    } else {
        for (var ob of p) {
            var resp = [];
            for (var obInut of ob.getElementsByTagName('input')) {
                if (obInut.checked) {
                    resp.push(obInut.value);
                }
            }
            if (resp.length) {
                status = true;
                dadosEncadaemento = {
                    cod: ob.getAttribute('data-pergunta'),
                    resp: resp
                }
            }
        }
    }

    if (!status) {
        alertaPadrao.returnMensagem('Selecione uma opção', 'top', 'infor');
        return false
    }

    var cod = document.querySelector('form[name=form' + pergunta_atual + ']').getAttribute('data-pergunta');
    var ajax = new XMLHttpRequest(), form_data = new FormData();
    form_data.append('encad_perg', dadosEncadaemento['cod']);
    form_data.append('encad_resp', (dadosEncadaemento['resp']));
    form_data.append('id', cod);
    form_data.append('pesquisa', codPesquisa);
    ajax.onload = function (e) {
        var r = JSON.parse(this.responseText);
        if (r.status) {
            alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
            setTimeout(function () {
                location.reload();
            }, 2300);
        } else {
            alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
        }
    };
    ajax.open('POST', baseUrl + 'perguntas/salvar-encadeamento', true);//faz a requisicao dos dados, via post
    ajax.send(form_data);//envia o form
}

function visaoIrPergunta(n){
    abasPesquisa('principal');
    mostraPergunta(n, true);
    //   for (var f of document.querySelectorAll('form[role=form_perguntas]')) {
    //     for(let bt of f.querySelectorAll('button,input,select,textarea')){
    //         bt.removeAttribute('disabled');
    //     }
    //   }
    // navBoxs('visaoPergunta')
}

function visaoGeral(){
 var html='',boxClose=false,aux='',cal=1,forms=document.querySelectorAll('form[role=form_perguntas]');
                for (var i in forms) {
                   if(forms[i].innerHTML){
                    forms[i].querySelector("nav[role=pagination]").style.display='none';
                    for(let bt of forms[i].querySelectorAll('button,input,select,textarea')){
                        bt.setAttribute('disabled',true);
                    }

                //     if(!boxClose){
                //   aux += `<div class="d-flex  bd-highlight mb-3 col-12 col-sm-12" style="">
                //         <i onclick="location.reload()" style="font-size:40px;margin-top:-10px!important;margin: auto;" class="sm-link fas fa-check-circle"></i>
                //       </div>`;
                //       boxClose=true;
                //     }
                    aux += `<div class="card mb-3 col-sm card-padrao card-visao-geral" style="margin:5px" title="Ir para a pergunta" onclick='visaoIrPergunta(${parseInt(i)+1})'>
                    <div style="text-align: end;">${parseInt(i)+1}/${forms.length}</div>
                             ${forms[i].innerHTML}
                    </div>`;

                    if(cal>=2){
                      cal=0; 
                      html +=`<div class='row'>${aux}</div>`;
                      aux ='';
                    }
                    cal++;
                    if(parseInt(i)+1>=forms.length&&aux){
                      html +=`<div class='row'>${aux}</div>`;
                    }
                  }
                }
                
    $('[role=accordionView]').html(html); 
    //navBoxs('visaoGeral');
}

function semEncadeamento() {
    // document.querySelector('[name=respostasem_encadeamento]').checked = true;
    // checkEncad('sem_encadeamento');
}
function checkEncad(cod) {
    for (var ob of document.querySelectorAll('[data-encadeamento=p-encad]')) {
        if (ob.getAttribute('data-pergunta') != cod) {
            for (var obInut of ob.getElementsByTagName('input')) {
                obInut.checked = false;
            }
        }
    }
}

// function cadEditEncadeamento() {
//     var form_data = new FormData(),
//         ajax = new XMLHttpRequest();
//     ajax.onload = function (e) {
//         var r = JSON.parse(this.responseText);
//         if (r.status) {
//             alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
//             setTimeout(function () {
//                 location.reload();
//             }, 3000);
//         } else {
//             alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
//         }
//     };
//     ajax.open('POST', baseUrl + 'request/switchAjaxPesquisa.php', true);//faz a requisicao dos dados, via post
//     ajax.send(form_data);//envia o form
// }

function deletarPergunta() {
    if(pergunta_atual==nova_pergunta){
        abasPesquisa('principal');
        location.reload();
        return false
    }
    if(modPesquisa=='PRODUCAO'){
        alertaPadrao.returnMensagem('No modo de produção não é possível deletar as perguntas', 'top', 'info');
        return false;
    }
    var cod = document.querySelector('form[name=form' + pergunta_atual + ']').getAttribute('data-pergunta');

    if (cod) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>Deseja deletar essa pergunta?</h6></div>')
            .dialog({
                autoOpen: true,
                modal: true, title: 'Alerta', zIndex: 10000, autoOpen: true,
                width: 400,
                buttons: [
                    {
                        text: "Ok",
                        click: function (obj) {
                            var ajax = new XMLHttpRequest(), form_data = new FormData();
                            form_data.append('cod', cod);
                            form_data.append('pesquisa', codPesquisa);
                            ajax.onload = function (e) {
                                var r = JSON.parse(this.responseText);
                                if (r.status) {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'sucesso');
                                    setarUrl(pergunta_atual - 1)
                                    setTimeout(function () {
                                        location.reload();
                                    }, 3000);
                                } else {
                                    alertaPadrao.returnMensagem(r.msg, 'top', 'erro');
                                }
                            };
                            ajax.open('POST', baseUrl + 'perguntas/delete', true);//faz a requisicao dos dados, via post
                            ajax.send(form_data);//envia o form
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
}

function toggleBoxJust(idL, value) {
    let display = 'none';
    if (value == 'sim') {
        display = '';
    }
    for(var ob of document.querySelectorAll('[role=npsBoxJust'+idL+']')){
        ob.style.display = display;
    }
}

function changeFileImg(numPergunta,numero){
    let objFile = document.getElementById('file-'+numPergunta+numero);
    document.querySelector(`[ data-bt=addFile${numPergunta}${numero}]`).innerHTML = 'Editar'
    // const reader  = new FileReader();
    // reader.onloadend = function () {
        //     $(`[data-img=img-${numPergunta}${numero}]`).attr('src',reader.result)
        //     document.getElementById('imagem'+numPergunta+numero).value = reader.result;
        // }
        if (objFile) {
            if(!['image/png','image/jpeg'].includes(objFile.files[0]['type'])){
               alertaPadrao.returnMensagem("Essa imagem não  é de um tipo válido. Ex: .png ou .jpg", 'top', 'infor');
               objFile.value ='';
               return false;
            }
            const compressor = new Compress()
            compressor.compress([...objFile.files], {
                size: 2,
                quality: .35
            }).then((results) => {
                const output = results[0]
                $(`[data-img=img-${numPergunta}${numero}]`).attr('src',output.prefix + output.data)
                document.getElementById('imagem'+numPergunta+numero).value = output.prefix + output.data;
                // const file = Compress.convertBase64ToFile(output.data, output.ext)
               // preview.src = output.prefix + output.data
            })
        }
       /*
        if(objFile.files[0].size>(100*1024)){
            alertaPadrao.returnMensagem("O tamanho da imagem deve ser menor que 100kb", 'top', 'infor');
            objFile.value ='';
            return false;
        }*/
      // reader.readAsDataURL(objFile.files[0]);
}

function addFileImg(numPergunta,numero){
  document.getElementById('file-'+numPergunta+numero).click();
}