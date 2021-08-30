
var showFilter = false,widthCard=0;
function getWidthCard(){
    var url = location.href.split('/');
    console.log(url,url.includes('relatorio'))
    if(url.includes('relatorio')){
        widthCard = $('#numCard1').width()+45;
    }else if(url.includes('net-promoter-score')){
        widthCard = $('#cardNPS').width()+45;;
    }else{
        widthCard = $('.box-responsivo').first().width()-5;
        console.log("kkfc",widthCard)
    }
}
$(document).ready(function () {
 getWidthCard();
 console.log(widthCard)

 $('#sidebarCollapse').on('click', function () {
     $('#sidebar').toggleClass('active');
     if($(this).attr("data-filter")=='true'){
       $(this).find("[data-icon=filter]").show();
       $(this).find("[data-icon=close]").hide();
       $(this).attr("data-filter",'false');
       widthCard+=250;
       showFilter=false;
       $('.box-responsivo').animate({width: widthCard}, 900); 
     }else{
       widthCard-=250;
       showFilter=true;
       $('.box-responsivo').animate({width: widthCard}, 900); 
       $(this).find("[data-icon=filter]").hide();
       $(this).find("[data-icon=close]").show();
       $(this).attr("data-filter",'true');
     }
    //  $('.box-responsivo').css('width','99.50%');
    //  $('.box-responsivo').animate({width: '99.50%'}, 00); 
 });
});
function toggleMax(){
    $('#dashboard-max').toggleClass('dashboard-max');
    $('.box-responsivo').css('width','auto');
    if(!modMax){
      $('[data-icon=compress]').show();
      $('[data-icon=expand]').hide();
      modMax='SIM';
    }else{
      $('[data-icon=expand]').show();
      $('[data-icon=compress]').hide();
      modMax='';
    }
    // $('.box-responsivo').css('width','99.50%');
    widthCard = $('.box-responsivo').width();
    console.log('widthCard',widthCard)
    if(showFilter){
        // widthCard-=250;
    }else{

    }
    abasPesquisa(abaAtual);
  }

function formtDataUS(d){
  return d.substr(6, 4)+'-'+d.substr(3, 2)+'-'+d.substr(0, 2);
}

function filterMoney(d){
    var tMoney='',isMoney=true;
    for(var n of d.split('')){
      if(!['0','1','2','3','4','5','6','7','8','9','.',','].includes(n)){
       isMoney=false;
       break;
      }
      tMoney+=n.replace('.','');
    } 
    tMoney=tMoney.replace(/,/g, ".");
    return tMoney;
}

function validData(d){
  var isData=true;
  for(var n of d.split('')){
   if(!['0','1','2','3','4','5','6','7','8','9','/'].includes(n)){
     isData=false;
     break;
   }
  }
  if(isData){
    var dia = parseInt(d.substr(0,2)),mes = parseInt(d.substr(3,2));
    isData=(d.length!=10)?false:true;
    isData=(dia<1||dia>31)?false:true;
    isData=(mes<1||mes>12)?false:true;
  }
  return isData;
}

function rDataMensuracao(m){
  document.querySelector(`[name=mensuracaoDataInicio${m}]`).value='';
  document.querySelector(`[name=mensuracaoDataFim${m}]`).value='';
if(m==2){
 document.querySelector(`[data-bt=m1]`).style.display='';
 document.querySelector(`[data-mensuracao=data2]`).style.display='none';
}else if(m==3){
 document.querySelector(`[data-bt=m2]`).style.display='';
 document.querySelector(`[data-mensuracao=data3]`).style.display='none';
}
}
function addDataMensuracao(m){
if(m==1){
 document.querySelector(`[data-bt=m1]`).style.display='none';
 document.querySelector(`[data-mensuracao=data2]`).style.display='';
}else if(m==2){
 document.querySelector(`[data-bt=m2]`).style.display='none';
 document.querySelector(`[data-mensuracao=data3]`).style.display='';
}
}

//////////////////////////////////////////////////////// graficos  /////////////////////////////////////////////////////////////
var boxGrafNum=1;
function toggleGraf(i,idPerg){
  var item = dataDashboard.dashboard.dados.filter((item)=>{
    if(item.id==idPerg){
      return item;
    }
  });
  if(parseInt(dashMensuracao)){
    gGrafMenuracao('dash'+i,item[0]);
  }else{
    gGraf('dash'+i,item[0]);
  }
  $.ajax({
    url: urlBase+'dashboard/alterar/indicadores',
    type: 'POST',
    dataType: 'JSON',
    data: {pesquisa:idPesquisa,num_indicador:i,id:idPerg},
    success: function(r){
    }
  });
}
var styleColorColumns = ["#11971a", "#e62a2b", "#0083d1", "#fffe01"];

function gGraf(k,d){
  if(!d){
   return;
  }
  if(typeof d =='string'){
    d = JSON.parse(d);
  }
  document.getElementById(k).innerHTML='';
  var dChart = {
            "theme": "light",
            "dataProvider": [],
            "valueField": "dValue",
            "titleField": "dLabel",
            'fontSize':12,
            'marginTop': 30,
            'marginRight': 30,
            "export": {
            "enabled": true
            }
    }
    dChart['dataProvider'] = d['data'];
    dChart['angle'] = 30;
  if(["FECHADA_RESPOSTA_UNICA",'ESCALA_LIKERT_0_10',].includes(d['tipo_pergunta'])){
    var checkZeros=[];
    checkZeros= d.data.filter((it)=>{
      return it.dValue=='0.0'?true:false;
    });
    if (d.data.length <= 4 && d.data.length!=checkZeros.length) {
      dChart['angle'] = 23;
      // dChart['colors'] = (graf.tipo_resp_entrada == 'MASC_FEMIN') ? styleColor : styleColor2;
      dChart['colors'] =  styleColorColumns;
      dChart['type'] = 'pie';
      dChart['labelFunction'] = function (info) {
        var data = info.dataContext;
        // console.log(info)
        return data.dLabel + ": " + data.dValue.replace(".", ",") + "% ";
      };
      dChart['balloonFunction'] = function (graphDataItem, graph) {
        var data = graphDataItem.dataContext;
        return data.dLabel + ': ' + data.dValue.replace(".", ",") + "% ("+graphDataItem.dataContext.total+")";
      };
    }else {
      dChart['type'] = 'serial';
      dChart['colors'] = ['rgb(103, 183, 220)'],
      dChart['categoryField'] = 'dLabel';
      dChart['categoryAxis'] = {
        "gridPosition": "start",
        "labelRotation": 45
      };
      dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 100, minHorizontalGap: 100, maximum: 100 }];
      dChart['graphs'] = [{
        "balloonText": "[[category]]:[[value]]% ([[value]])",
        "customBulletField": "img",
        "bulletOffset": 10,
        "bulletSize": 52,
        "fillAlphas": 1,
        "lineAlpha": 0.2,
        "labelText": "[[value]]%",
        'labelPosition':'right',
        'labelOffset': 0,
        "title": "dValue",
        "type": "column",
        "valueField": "dValue",
        "balloonFunction":function (graphDataItem, graph) {
          var valor = graphDataItem.values.value;
          if(valor<100){
            valor = valor.toFixed(1);
          }
          valor = valor.toString();
          return '<b>'+graphDataItem.category + ': ' + valor.replace(".", ",") + "% ("+graphDataItem.dataContext.total+")";
        },
        labelFunction: function (info) {
          var data = info.dataContext.dValue;
          return data.replace(".", ",") + "%";
        }
      }]
      if (d.data.length > 6) {
        dChart['graphs'][0]['labelOffset'] = 0;
        dChart['rotate'] = true;
      }
    }
    if(d.tipo_pergunta == 'ESCALA_LIKERT_0_10') {
      dChart['rotate'] = false;
      dChart['categoryAxis']['labelRotation'] =0;
    }
    chart = AmCharts.makeChart(k, dChart);
  }else if(["AVALIACAO_SATISFACAO",].includes(d['tipo_pergunta'])){
    dChart['type'] = 'serial';
    dChart['categoryField'] = 'dLabel';
    dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 100, maximum: 100 }];
    dChart['colors'] = ['rgb(103, 183, 220)'],
    dChart['graphs'] = [{
      "balloonText": "[[category]]:[[value]]% ([[value]])",
      "customBulletField": "img",
     "bulletOffset": 10,
      "bulletSize": 52,
      "fillAlphas": 1,
      "lineAlpha": 0.2,
      "labelText": "[[value]]%",
      'labelPosition':'right',
      'labelOffset': 5,
      "title": "dValue",
      "type": "column",
      "valueField": "dValue",
      "balloonFunction": function (graphDataItem, graph) {
        var valor = graphDataItem.values.value.toString();
        return graphDataItem.category + ': ' + valor.replace(".", ",") + "% ("+graphDataItem.values.total+")";
      },
      labelFunction: function (info) {
      var percent = info.dataContext.dValue;
      return percent.replace(".", ",") + "%";
      }
    }];
    chart = AmCharts.makeChart(k, dChart);
        
  }else if(["FECHADA_RESPOSTA_MULTIPLA",].includes(d['tipo_pergunta'])){
    dChart['type'] = 'serial';
    dChart['rotate'] = true;
    dChart['categoryField'] = 'dLabel';
    dChart['colors'] = ['rgb(103, 183, 220)'],
    dChart['graphs'] = [{
      "balloonText": "[[category]]:[[value]]% ([[value]])",
      "fillAlphas": 1,
      "lineAlpha": 0.2,
      "title": "dValue",
      "labelText": "[[value]]%",
      'labelPosition':'top',
      'labelOffset': 0,
      "type": "column",
      "valueField": "dValue",
      "balloonFunction": function (graphDataItem, graph) {
          console.log(graphDataItem)
        var valor= graphDataItem.values.value;
        if(valor<100){
          valor = valor.toFixed(1);
        }
        valor = valor.toString();
        return graphDataItem.category + ': ' + valor.replace(".", ",") + "% ("+graphDataItem.dataContext.total+")";
      },
      labelFunction: function (info) {
        var data = info.dataContext.dValue;
        return data.replace(".", ",") + "%";
      }
    }]
    dChart['valueAxes'] = [{ "minimum": 0, minHorizontalGap: 100, maximum: 100 }];
    dChart['categoryAxes'] = {
      "gridPosition": "start",
      "fillAlpha": 0.05,
      "position": "left", 
    }
  chart = AmCharts.makeChart(k, dChart);
  }else if (d.tipo_pergunta == 'ABERTA'){

    var hmtlAberta='';
    if(d.tipo_resp_entrada=='MONETARIO'||d.tipo_resp_entrada=='NUMERICO'){
      hmtlAberta = '<table class="table table-striped mt-2"><tbody>';
      for(var item of d.data){
        hmtlAberta += `<tr><th scope="row">${item.label}</th><td align="right"> ${item.valor}</td></tr>`;
      }
      hmtlAberta += '</tbody></table>';
    }else{
      if(d.quadro){
        hmtlAberta = '<table class="table table-striped"><tbody><tr>';
        for(var item of d.data.cab){
                hmtlAberta += `<th style='background:#337ab7;color:#fff'  scope="row"><p  style="margin-bottom:0px" align='center'>${item.nome}</p></th>`;
            }
            hmtlAberta += '</tr>';
            for (let iL in d.data.linhas) {
                hmtlAberta += '<tr>';
            for (let c of d.data.linhas[iL].colunas) {
                hmtlAberta += `<th scope="row"><p  style="margin-bottom:0px" align='center'>${c.nome}</p></td>`;
            }
            hmtlAberta += '</tr>';
            }
        hmtlAberta += '</tbody></table>';
      }else{
        hmtlAberta = '<table class="table table-striped"><tbody>';
        for(var item of d.data){
          hmtlAberta += `<tr><th scope="row">${item.nome} (${item.total})</td></tr>`;
        }
        hmtlAberta += '</tbody></table>';
      }
    }
    document.getElementById(k).innerHTML=hmtlAberta;
  }else if(d.tipo_pergunta=='NET_PROMOTER_SCORE'){
       var graf=d;
       if(!Object.keys(graf).length){
        return;
       }
       htmlNps = `
               <div >
                  <div class="nps_todo">
                   <div id="NPS${boxGrafNum++}">
                      <div class="grafico-nps">
                          <div class="graf_top net_border_red net_red">
                              <span style="font-size:15px">Detratores</span><br>
                              <span>${graf.data.detratores.porcentagem}%</span>
                          </div>
                          <!-- <p class="nps-titulo">Detratores</p> -->
                          <div class="grafico-nps-novo">`;
                         for(var dt of graf.data.detratores.lista){
                          htmlNps +=`  <div class="detretores" >
                                  <span class="circulos-k net_red">${dt.total}</span>
                                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARAAAAESCAYAAAA1wooeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAE8xJREFUeNrsnU9sFmUex6f0n2BbcTm2B9mwh/bEZhPKSSGxnIwxwMmY7HoSTJS9qAckxqgH14tlE8XL6ibEvQgxxhM1oXpqSUw40cOSxQMc2cW2gtAWdr7Tmaa288yfd+admWeezyd5U4Ig7zx/PvP7/eZ5nvE8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADvo6fY/cHvvvqf8H4f8zzP+Z3/4AYDucDX8fO9/ZvfcuP6TlQLxxSFpnPI/L9CnALXxtf+Z9kUya4VAQnG8E0YdANAMJJB3yxZJaQLxxbE7FMdf6SuAxvJxKJI7jRFIKI/LHvUNABtQjeRwGRIpLBBfHvtDeeymXwCs4U4okau1CSSMPG4gDwBrJbK3SCSyo4S0BXkA2Ekwh8O5XK1AvPWCKTUPALvZH87l6lKY8FHtZdoeoDUc7uQRb6cRyDu0N0Cr6GhO5xZIGH0cor0BWsWhcG53PQI5RVsDtJLccztXDSTcGHeDdgZoLXvzbMDLG4GQugC0PJXpZgrzDO0L0GpyzfG8AmHdB0C7yTXHEQgAVCYQAAAEAgAIBAAQCAAgEABAIAAACAQAEAgAIBAAQCAAAAgEABAIACAQAEAgAIBAAAAQCAAgEABAIACAQAAAEAgAIBAAQCAAgEAAAIEAACAQAEAgAIBAAACBAAAgEABAIACAQAAAgQCAC/TRBA7dLcZGvd6xMa93fNzrGRnxeifGvR0jw17P8Pqv87B2bcF7tLToPVxcWv/14qK3trDgrd286T28eYvGdoSePH/49t59j2gySzrWF0T/5AFfDBNe/8EDG9KogkgmK3NXfLlc81bmrwS/B3aw58b1HgTiqDD6Dk56A1NTQbTRJBSVPJiZ8Vbn5hEKAoGmSGNg6lmv/8hU8NMmHsx8561cmgl+IhMEAhUyeOyoldJIk8n9CxfpXAQC3UApyWMv/yWQR5m1jKheEaQZYUE0iajwGvy65LqKvosk8uvnX1CIRSBQBv0HJ71BXxxFow2JYdUXhSbm6vy8L4vFVFlkZV0qI17f5GQguj5fLHmf6sRFJfd9kazMzTMIEAh0Io6dp14LJmUnSBJ6CrL+c762a9D311OgItdxb/rviASBQDfFofB/c2GyiWwu+OZNfRAJAoGUGsfQRx/mEocN0ihbJhLJ8htvUSNBIBB0gD95VBxV1JFnEt3/6mJrnlqoMDx4/GgueSoaUbGVR8AIxFl099115u3Mi76iJxRlFT+bhgqv0ZOmLCgKufve+9ZFXwgECkcdj/vpStYnKxLHvemzzoTtEurOU69nFokE8ouf1hCNIJDWoyLp0LlPMuX9romjiEgkj+UTr1JkRSDtZdeZ00GInqXGQaHwtyLJWmBWinf3vQ9oNATSrgkwfO7T1MVV5PTJZK0ZqUa0dOIkAq5AIBwoVEHK8sS336TKQ3fOn597Hnmk1DrURmqrJNTWanO1PXQXIpAuotxdxdK0u+Xym2+19slKt5AkFNWlRSMqrrJJjwjEOlTvSJOH7qSLL76EPDpAbZYlGlEfqC+ACMQaNGiTnhzoiYHujKQr5aDaiNo86cmWohC1ORCBNNfG4fqOJHnozqmoA3mUh9oyLZLLkk5CfhBIifIY+fJ8ojyyDHToPKVJE7P6RsXVqs6GRSCQGckj6UmLQujlV06yWrKLBIvJ/DZOKpqqj9RXgEAag0LjJHko9yb/ro609lZfkc4gkMbIIylt4TFiPaQVTamJIJDa0eNB5GG3RHjEi0BqQYMvaV8L8rBDInmODwAEUgpaIp0U/iIPuySivmTZOwKppsG0M/TcJ8ijZRJRnzbtbX4IpIVo/4VpHYEGKfJotkRM/aM+Vd8CAukaKriZHtdGp2JBs0naQqC+paiKQLqCcmRT0VSrIJGHXRIxrQZWH1MPQSClovDWVPcIVj++yXmcNpHWZ1mPnAQEkomknZ5JdzNoLklRY7QpEhBIYbRV3HR6us6iYFetvajvTOeJJPU7IJDMqYvO4IxD523q5UZgN+pD09mp6ntSGQTSMSqomdYG6NBe6h72oz5UX8ZODr/vs5yij0AgdvCYXjepuxZ1j/agvjSlMhoDLDBDILkZMhTRFO6mncMJ7UplhiioIpA8aB2A6SVGem8LqUs7Uxn1bRwaC6wNQSCZMaUuemMcT13ai/pWfZxnTCAQyBx9LLPatPWY+pgoBIEUij60CYtXJbYf9bFpwx1RCALpOPq4N32WBnIEU18ThSCQRAYNz/yJPohC0sYIAnG9IcZGjUuXiT6IQiI0RlgXgkC28RjRB2SMQlidikC2h6aGg3VZNOYupr7nEGYEsm1AxG2a0poAlqy7i/o+bl2IxgoSQSAb9B+Zik9fvuJ8U9cxjQHTmHGNPtcbQHeTuOKpljbXcUCy7mwanL2joxvnr+pOuHbrlrdyaab1hzY37fq1OnWXPxa2RqgaM/o917c1OB+BmJ68VL1kXd9j9w+zwUlY+vXmw5v1a/2e/pv+TBsPusl6/U9e/bHS65cgTGOBA4cQiDEU1d2uKjQxhj77NNPjweC9NP6fbdORe3muPziftuLrN40F0hjHBZKUvlQVgaS9nDsp1G+DRGy4fo2FuFQlSmMQiKvRx+SBWtOXou9l1d+1OYzWdy96/VWtyTCNCdMYQiAO0GfY11BF+qI7Vxmbs5JOjG969FdGBKE2rOL6TWOiz/G9MU4LZGBqqrYIxLT2pKw0zIboo6zrr2JNhrmQ6nYdxFmBaODFFe1MB8qUPoGOlDfpbSzmlfmdy2zLJOLGhsaQy3UQZwViyl1X5q5Ukz5NTnb9Wmxs/7rbMjGNMYwNl+sgzgqkd2Ii813GhmiK71xPBJI0lhBImyOQg6YIZN4DyDM2TGMJgbQ5Ahkf3/Z7bJyDNOLGSNxYQiBtvmhD4Wt1oTqBlJkq2Zh22Xr9cWNEY8nVQqqTAukdG4v9/SoPDnpw6btG/r+4/mRMY6Rvws0oxE2BGELOKu9kD2bKWaxW167houg7l7WTtay2LBLtuJrGOCkQU7j5sMKt2bqT6XWKRfn1839auaVc31nfvShJr6TsSr8Z2poUxqUIxBBuVl1E1cG9Rf5N/V2bD3y28fpN37eXFMahix4Zjr0j1sHiiy91NIn0d/R3bcfG648bK3FjCoG0NYUZ3h5uri0s1DYYf37u+VyHNyts199pw2lY0fXnSefUVnVef9xYiRtTLuDkkYZNDDfvvvdBMDG0PV0btLbu09Edd2V+PvgzbXzNhFKR+xcuBNffPzm5rY90zSqWNvX6XU1hnD8TdWOALi7V/x38iSGR6BOEh6FEXHkvTXT9G+FxQ6+/CWOFFKZhNHEVqiaOyy+1aur1s2IZgQAAAgEABFIhrha7ABAI+SsAAgEABAIACAQAAIEUguIqMFYQSOcN4ehmKGCsIJCc8CQGGFMIpGMeLW3fxenywbiQM4WJGStxYwqBtJS4zVCuv2UdshM3VlzdYEcKs/nOQnEM0qKPhpxmh0DqTGEMB9HsIAqBtAljGCNtONwJgWSNQAynj1X1jlWwF9MYqetEOwRSh0Bu3oxvjC2ngAFkHSOrjqYwTp5IpkNqFHJuLYb1OfIkRtetFyHtGB31J8RYkNdHaxvSorDovSgqGirvf+jL+OGtW8EEciGMjxsjum5XUxhnjzRUyLl1srS1iKrr0jmjfQcngwlQJNL6TZtNPbtNzHr14+rcfHB+axsLi3FjxNX0xWmBrMxdib3b9vuTzPQWdpsY8Cd3/5GpQBxVpWb6dwb0CcUioUgkK5dmvAcz31nfphobprGEQFyLQK5dM95hbRWIBvjAsaPBBG7CuhYJZXDsqDfofyeF+JLIgwsXrW1fYwHVMJYQSJsjkPkrhkl4wLs3bVc9QxNUr0NochE4+p76KDLR6xnKfD9uNYI+kGssuUBPnj98e+++R226+N0/zMZOuv/+/g9WiEPSeOzlP5cSbWgib87loyJplPdv3kCmpdxl/Zt6P65kYoNIfveff2/7PcnwztOHWiWFPTeuZ/aC0++F0YuKNAnj6gdNzdmLiiMShfJ2hd5rt251XOyUWHpHR/2fE8HdOa9Y9Gd3nnotuJami2RgS8F48xhyGacFoqcFXoxAVHxsokAkDk24vOLQtXTjyYj+X8H/z///R2nf5ic+pkmXJBK94jLPaz4rS1/8MWEcQw7jdAqjgfvk1R9j79L/2/+nBuXek96ut0/neswsaURPP+q6q6t9o6dBWWUSienu+x80qtiqcRInbo2Ttq0ByZPCOH2gUPRkwDTwm8CuM6e94S/PZ5KH8nHdwZWTL79ysvYipf5tfQd9F30nfbcsb5rTteqade1NSV/i5FGnnJuC8yeS6S6dJ2StWh5xNZo4cfzyxlvhJD3byNdBrsvtbPAd9V2zfEddexMkYhoLprFDCuNICtP0NMYUNm+dlLrL24ge6e489Xri4+e6+0Htv/uHy86kL6QwJaYxGuBN/c56i73u5rbKQ+i76xp0LU1++kL6gkA6SmMGjx+tfYLFDdw7Tx9u5JOKTtG16JriRF63IE1jgPSFFCZTuvDzc8/XuilMIX60AvLXf3zRij0laXf8gePHgoVrWquiFK0uVMx94ttvGpneNiWFcXodyNY7XVzBUr+nol9daALZtLS+KMF+mYZI0lTAtjltJIXpYhgdG8IeO8pBQy5ODG0ENNTA2pQ+IpCS0BMN051PaQS4hanPNUaa+JgcgTQhjSEKgZTo4z7RBwIxoaXT0ZF9RCFEH1vR2FhxfO8LAklBy62JQog+8owNBAKZopChjz6kgVqOqY+JPhBI4ShER9o1ZZMdlI/61nRsIdEHAiklCtl15m3eo9tC1KfqW6IPBFIKy4bFY8qRdfgNtAv1qanGtVzjQkIEYinR2RpxaIUiL+JuDzqwybTqNOsZJggEtqEVh6bBM3zuU1KZlqQuj//tQ+NNhFWnCKRj1rfNv08q42jqor5nyz4CKUTS5i6FvTyVsRf1nSl1adKmvkZHcHn+cJu386eFuaZTqXSHWnzxpVa+B7bNqIY18uV5Y5/qfBJXow9OJOtCKrN84lWjXIb8HJp6iF03hKQ+U1+TuiCQUtE6AFNBTXezx1mlag3qK9NTNPUxaz4QSFfQ2Z2mVEX5NBKxQx6mulXwPhq/jwGBdI2lEyeN4W308mhoJkn9oz5V3wIC6SpaG2Cqh0R3OCTSTHkkRYgqhLNgDIFUgnLkpHNSkYhd8lBf8hQNgVSKDtZNWqWIROyQh/qQQ5IRSC2o4JY0+JBIs+WhvqNoikBqReEvErFTHr+wy7YwrEQtCb2AKGmHLgO2OtKkrXqHXhgG8bAStQbSlrNrQA99xg7ert4NtcLUb+M0eaivgBSmUWgdge5qSemMFjBp/wVniZRPtLclaXOj+kZ9xDJ1BNJY0moiWQY65COLmEkhEYhVEkl6xBuF2rvOnCalKZiyqA3TUkP1BfLoUh/k+cMUUfOR9iRAaPWjllCzkCl/yqJT4dLe1ZMWEcJ28hRREUiX0XmbQ+c+SY00dJfU+Zvk5+lRh04RMx0EFME5LdUIhBSmy2jZuwp3aQNZE0KPgqmNmFHbqI3S5BE9pkUe3QeBVIDSFA3otAN6FY4rnx/513leo7mlXdQmapu0dlEbq63ZGFdRREgK08yURih3vzd91tnJsH5w9euZVvJGp8ZxGFC1KQwCqSmPTzrYxnWR5BGH0OHHKpZSP0IgzuX0ep1i1nQl2gHc1txeT1ZU38gqDglVr17g9HQE4nQ0okmT5x0zelfr/a8uBhPH9ruurl8iHTx+1Phi6zj0xEoyJepAIBCG7UN+WpNnEmnySCIrl2asuwtLGv1HpoKfeRbTSZ56Vy1FUgQCMajIqmgkj0hskUmn0ojEoaiDIikCgS6KZPOEW5m7Ev6cr+0a9P37Dx4odB2IA4FAgUk4WMIrNFV4XV1YCEJ/TcqHfsRSVjFWxc8dfkQhSSgV6xsfL7zjWFHUfd7RgkCgvBpJ9ISizA14Sn3WFtZF8nBxKVUq67IYXv+1L4qyv0v0pIkaBwKBLiGJRLWENhDVbtj0hkCgQqJHoDbKZHPBl0exCAQaIJP+yQNe38FJXyZTjdtLo5TkwcyMtzo3763MX0EaCARsEErvxETwFKTsekVaLUN1FT0FWrt2DWEgEATSFqn0TYxvyCQqiPYMj+R+aqJi66OlxY3CaySN1fDXgEAQCAACSYTzQACgYxAIACAQAEAgAIBAAACBAAAgEABAIACAQAAAgQAAAgEAQCAAgEAAAIEAAAIBAEAgAIBAAACBAAACAQAEAgCAQAAAgQAAAgEABAIAgEAAAIEAAAIBAAQCAAgEAACBAAACAQAEAgAIZIOrNBlAq8k1xxEIAFQmkO9pX4BWk2uO5xXILO0L0GpyzfFcAtlz4/pP/o+vaWOAVvJ1OMe7FoGIadoZoJXkntu5BeIbapZUBqB9qUs4t7srkJB3aW+AVtHRnO5IIKGpPqbNAVrBx51EH0UikMhYrAsBsJurRTKKniL/8u29+3b7P274n930A4B13PE/e/3o406n/4NCe2HCf/hw+EUAwC55HC4ij8IRyJZI5LL/2U+/AFiRthSWR+EIJCYSobAK0GxUMP1jGfIoLQLZEo0c8n+8438O0VcAjWHW/7zb6dOWygSyRSSn/M8L9B1AbWjryXTZ4ui6QDaJ5KkwGnnGW6+RUCcB6B5Xw4921c7m3dsCAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA0Fb+L8AAFR0R758qsHYAAAAASUVORK5CYII="
                                  />
                                  <p>${dt.nome}</p>
                              </div>`;
                         }
                              htmlNps +=`   </div>
                      </div>
                      <div class="grafico-nps">
                          <div class="graf_top net_border_yellow net_yellow">
                           <span style="font-size:15px">Neutros</span><br>
                           <span>${graf.data.neutros.porcentagem}%</span>
                          </div>
                          <!-- <p class="nps-titulo" >Neutros</p> -->
                          <div class="grafico-nps-novo">`;
                          for(var nt of graf.data.neutros.lista){
                          htmlNps +=`    <div class="neutros" >
                                  <span class="circulos-k net_yellow">${nt.total}</span>
                                  <img src="data:image/png;base64,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"
                                  />
                                  <p>${nt.nome}</p>
                              </div>`;
                          }
                              htmlNps +=`         </div>
                      </div>
                      <div class="grafico-nps">
                          <div class="graf_top net_border_green net_green">
                           <span style="font-size:15px">Promotores</span><br>
                           <span>${graf.data.promotores.porcentagem}%</span>
                          </div>
                          <!-- <p class="nps-titulo">Promotores</p> -->
                          <div class="grafico-nps-novo">`;
                          for(var pr of graf.data.promotores.lista){
                          htmlNps +=`          <div class="promotores" >
                                  <span class="circulos-k net_green">${pr.total}</span>
                                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARAAAAESCAYAAAA1wooeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAFFtJREFUeNrsnT1oXFcahsdrs25WYEggWLDgsCDBEoFhYBe7kdy4GpuAKynNVlaTkCxSlRSWqzQ2JKVSbZNRZQiRmgiCJVg21YDBbiRYomKRYInBoMBiQ8jOezVHGcv3nHvu3P97nsdcZmxLM/f8vff7vvOdczodAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgGZwrugv6K53rwxfFobX/PC6OroAoBiejK7d4bUzWB4cNFJAhsIh0fh4eL1PmwJUxjfD68uhkOw0QkBGwnFvZHUAQD2QgNzPW0hyE5ChcFwaCccntBVAbfliJCQvaiMgI/F43CG+AdAEFCO5kYeIZBaQoXhcHYnHJdoFoDG8GInIk8oEZGR5/Ih4ADRWRN7NYon8Lge3BfEAaCbRGB6N5XIFpHMSMCXmAdBsro7GcnkuzGiq9jF1D9AabkwyxTupBXKP+gZoFRON6dQCMrI+FqhvgFaxMBrbhVsgH1PXAK0k9dhOFQMZLYz7kXoGaC3vplmAl9YCwXUBaLkrU6QLM0/9ArSaVGM8rYCQ9wHQblKNcQQEAEoTEAAABAQAEBAAQEAAAAEBAAQEAAABAQAEBAAQEABAQAAAEBAAQEAAAAEBAAQEABAQAAAEBAAQEABAQAAAAQEAQEAAAAEBAAQEABAQAEBAAAAQEABAQAAAAQEABAQAAAEBAAQEABAQAEBAACAELlAF4TA9Nd25PHW5M/PWTGfq91MnrxenTt+nYf/5fuf41XHn+OXx6Xu9Hh0fdQ6PD6lsBASajEShO92NhMG86t/yYlxwFq4svPZ/RkwGh4PTV/0bICBQc8HoXu5GA1qWRqX3cvnkXgyyTHYOdjqDowGC0iLOpfnh7nr3V6qsXqIhsZi/Mv+GFVB3JCa7B7vRK2JSLwbLA29dQEAayK2ZW40UjSQx2dzfpHERECgCBUAX31vs9GZ7ucYyTLwiev/yt/c2TOD19H3O97K1t9XZeLZBIBYBgTxQXEPCkdXakDDo0sBUHMJHLHwxoqKYh4ROf087qxNnlUhIFC8BBAQmEI673buvBSJTdYJRsNK8VlWGKJg63c1Ujq8GXyEkCAgUKRwy/01QUlcdkRVlgr5pXR+EBAGBhBjHvYV7qYSjCaKRt5hISO7v3CdGgoCA0OBZnFuMrI40g0jBxrZMgWpWScHhNOIpa2Tj6QZTwAhIuOgJvHJtxTvpa2t/q9N/2s8t+Fk3FHhdmlvq9GZ6Xj+v5LSHPzxsnPWFgEBmq0Puiu/MioRDT9xQzHa5c7LIfIVEAiK3BmsEAWk9CpI+uPnAy+8PTTiyCInEY3V7lSArAtJe5K4o3uET4yBQ+LqQ+AaYFReRWwMISKsGgKyOpOQqfHo3vjEjxYhkjSDAxQsIGwqV4LJ8fefrRPGQq7L0aAnxSIh1qI5kZbhQXavOVfdQLAhIgWh6cr237ox3yOrQoJCAEARMRnUkK0115pqNUp2r7tUGUBznU5nit6bXqDL/eMeHf/0w0V//9PtPO0c/H1FhKXn+v+ed7X9vdy6ev9iZe2fO6fZITH74zw9UmidHW0f3EZAKWVtY69z58x3nU/Sz7z+LFou9+uUVFTYhqjsJgyyRa3+8FolJHBIYxaFwD/MXEFyYAsTDNe2ozr68uUxnzhHVperU5dKoTdQ2kC8ISE7ITO7f6TvFw6ejw2T4CLPaRm2U5x4mCAjkIh7rt9adMy1KCtPUIoHS4jDJZKprG2ojtRUgILVh5fqKUzyUFLa2g/lcmhs5rGvVuUtEcGcQkHp01oSYhzoye32Wj+rcJSLERBCQ6i2PayuIR8NFRG0ICEjpKEHJta4F8WiGiKgNSTZDQEpFKdJa3IV4tENE1JZZN4FGQMALszAO8WiXiGhmRm0LCEihuPby0PQh4lFvEbFN8apNXQ8GQEAyo4CbzdRVAhNTtfVHbWRLNlPbElRFQAohOuDJEjRVFqTLPIZ6obayZQOrjdkGAAHJFZd5q+xHPdXIMG0OSW3mu+UkICBeKEpv61CupxnUF5fVaDa9BgQkM+YApDi0nweraptLdP6uZXczV7sDAuLtutiCanqCaRcxaDZqQ+0KF4faHlcGAZkYBdRsG/gS92gHasOV7fiHhNreZxd9BATewJxJYnNdiHu0B7WlzZVRHyDBDAFJjS2IJnMX1yUsV4aAKgKSCuUB2A4x0o7guC7tdGVsB1KpL5AbgoB4Y3NddGIcsy7tRW2rNk7TJxAQ8LY+yDZtP7Y2xgpBQDJZH1qExVGJ7UdtbFtwhxWCgExsfRA4DQdbW2OFICBOFt9bxPoApxVi6yMISOBort+Wuoz1gRViUB8hLwQBwfoArBAEJD96s/G7q/ef9qmcQLG1va2vICCBol254xZNKSeAlPVwUdvH5YWor7CTOwJyyvyV+Xj3ZW+LygkcWx+w9RkEJDD0NIkLniq1mQ2SQdmpcUsX1GdY6o+AWGdedg92GT0QiYetL7DhEAJiNUVZ8wJJfQE3JnABcbkvCAjgxiAgTmxpybgv4NsnQk9tD1tALOtesD7At0/Y+hACEgC2IBgCAr59IvRA6oVQCy7fNW7DZNuGMmWgNRYKzOmppvdxx2gquUnX3vO9yKxuepq9KfPsW7NReW1lVjnVNlWWWd9/1uJQH1JfCnWXumAFxOa7Dg7LFxBlNSo92sccNoOsN/yjYwfUqZXs1LSclUnKrKd9lWVW34i7X/WlUK3WYAXEdkh2mRaIOt7a/Jr16Aivz7h8soeJjh/Qnp5VCGDaMrsOKa9zmW19Q2UJVUCCjYFUbYFoEK331jOJx9lOrM+r8+nypsxZxKPKMtv6RsgzMcEKiM3XLiP20r/TL+zAIn2uPr9O+QltKnNcH8lLEHFhGoICd3GdrWgB0Xeu3/J7AisoFwVMf9qP3ut3Z94+iQUkDZToyTz8nuXN5cqDe20rs+7vbFl0b6EGUoMUEJvbUGR032cgqQOa4KBLzPQZS3NL0eyFbWCZAbX0aKnSuvYps2ZWtPdGUplN4DWpzEWKiK2PSOjqHn/ChSnQfYl83AIDqCvX3YFD7X51u387CgomWUL6f53Nq5+37ZplylllTCQpWGrKrLL4lFl141Xm68WV2RVIJQYSCLYn2PHLYp5amn7szfSsT+DV7dWJDuvWz+v3dJaJ7XcVH6giyKfvtMU8dK+65yxlVp3Zfld1XVSCl62PhLomBgukhBiIzQrQAJC5nXUKUC6Py2zXVHHZ2L7TlDlrDofqzFXmoiwvWx/BAgnJArk4Fduxy4656Cmcl2jpc2ynquU1VUyZ7X0lrk8hIAG5MEXOwMSd+r7xdCP35CN9nj7X5/uLps1ljusruDC4MIWh4N/4U0sdsKizZvS54x3cdeo8ZW5un6oLwaayv2GWvizOhdFTcv/R/qlZXeR0nwaPpm5N4NTkVJRNlWWW9VHklHyRfQUBaShFJ5GpQ5e5irQOOQltLbP6CvuhBuzCAAACAgAICAAgIACAgAAAICAAgIAAAAJSe0LeVQroKwhIRkJdDAX0FQQkJWXsfQr0KQSkpcStDcEshSwuTKgHS4UpIDGLoUI/ZR1SuDAxfSXUBXa4MFghkNH6wIXBhTl5shAcgyTrw9JHcGGwQLzOaYWwsfURLJCAsG13pwOnAFzY+og2bgqRIDcU0iY35uQzH/+2SMxhSRI1bc1X5gY8TR3Ad7t3o53ObPuhFoltBiZUFybYHclkcp41R8sWEB09cHp2yuVOdNKcjiogT8U+eHXynBF+td/0H6ZL3f+0qjOVcWFqRh1OWj978FKac2RDFw9bHRaJrW+EeKRl8AJS10CqOclerg385uapTqrO1SGAioDUygJxncV7b+Fe5OuHjupAdTFJHWKBICCFoaBX3GxMmRbI6nerzqeXBs+Dmw+CzJJVmVV2l4iq7lSHVVog6kOhBlCDFhBhOyWtrC37zTmxLhHRvch8r+KA7MpchWFZVWZXO6jOXGfj5o3tXvI+aQ8BaZIbYzF/yzzzw4iIyxTXlOV6bz0Il0ZlVFldZ9uqrsoUD1efKNOFQkAaEgfRdGrZ7pQGxNb+VuLg2lzcbKU1ojKpbEkiqToqWzxcfSLk+EfwAqJOGGeCyv+u4uSxtZ0162nzZ60RxQfakDmrMqgsSVaHUN2ojspGfSEuDqW+E3L8I3gBEbsHu5W7MeNs7m9G57wmnS6v+/t28dvO2sJaI4VE96x7VxmS6lp1oTpR3VSB7f5sfQcBCQhbEKxsN2YcBQg1YHwCdL2ZXqOEZFw4dO8+7aO6qCrXQpaHrS+EHkBFQBLcmCqTuXRfq9ur0eVjJhshUbZmHQ9+1j3p3nyFI235cV+q4UIHIlM0btBpkVtVZvN4Rx30B1Fw0SdtW7kKutS5VS79flVPStWpLj3B0+SyaIGcFhbWYYCqD+C+2DmX5oe7691f21oRj//2OLaTV2k+n0XrQVaur6ROdtNA1GyBphz1WlR5dH+aTYlEbPiaNgFO9/fwXw9rVd/KR4mrzxv/uNFaURgsD7x1AQtkxNbeVuwTfmluqZLIvy02oilMDU5ZJL5CYmaVxq0sDVbtYXH482H0udrT03fgamBpZy69ajXszNszmTJ4dS+yOOo2Jaq2t/UVwAJ5DQX35J/HcXvjdi336UgrJGmtFiMokWAUkE5fV+Foan/AAqkQdQjFCuJiIRqkdbFCXmvo4cBbPlyOBrielmljDUlWS1HCpPhB/2m/1qtYbQlt6iNs+oSAxLLxbCM+mDrTq/VuYRqIEjjjqkhI6jYTo4Fngrp1n72Q9WGbKVIfAQTE+kSXWR335K2rFXL26a5ZI11GTOTm5GmZpLU0VKdNm/K0WR8mCA3EQJxxBaVVt833lZsz+9ZsVD6ljOftnmhwKWNUA2zv+V5jN9lxxT6Wt5aDEBBiIAVZIdrYRrMgTUQDWtd4XosGi8RkPEiatFDPDCATZJVotCkmYNu8COsDAfFG8Y44K0SiIregLSnMGvi6XhsYAY+RyOWzWGbqE/Amv6MK7FZIHNpJnXN024faVG2L9YGA5IJtWb1MfvYqbR/mrJk0fQEQEKd5bzNblbHK0QvtQW1pW2fEYV8IyMRoUZdtX46HNx/iyrTEdVFbxqG2L/vkOwSkRWimwXbqGa5M+10XtT1L9hGQTLiWw8vsrePeG+CH2s7mulS5DQIC0jIURLM9iZQ3QDykeajNbDkfamsCpwhIrq6Mdsay+dDaoo94SHNIarOqd0FDQFqI8gBsATXX0wzqh8tqVBuT84GAFIKCarY1HvKn9VSDeqM2ssWt1La2oDkgILngMm+1BLzKjZjBjdrGtkzf5aYCApIbSipydTSZx4hIPcXD5WZqkSQJYwhIKchHdkXpEZFmiYfasqnbDyAgDUXL4l1ZiohIM8RDbVj10R0ISKAo4OY6EBsRqbd4qO0ImiIglaJtDhGRZopH3beoREACEhGXD62OzBRvie0xrGuXeGh/D8QDAakViuK7RETThw9uPiBjtUBUt6pj19m7aqPV75iuRUBqhvIIdAymy50xB0yzdiZ/VKdJB4urbdRGpKkjILV2Z1wi4tPRIR0+wkzMoxjOp/nh6VvTtIAHWgYuc3runbnY/794/mLn5p9uRj/z7L/POq9+eUWlTeiyfPSXj6IDx1WnNjRV+/k/P6fCPDnaOvJeisy5MAWSNBMQNdbxUWdle4VEppTo+Im1+TXrZkAGJYmR55GONOfC4MIUiDquDiNy+dwaAP07fXZ7T2F1qK507IZLPExMCvEoFgSkaDU/HHQ+ePRBooWhnbEkJMRG3LEO1ZFtFzGD6tqnziE7uDAloidnUuePROfoZK0Ni7tO0Al6cgV9juNUvIPs0vJcGASkAt/dNx9EMwchHysg4dCmx668jnGXRauk2QwIAQnCj9cT1dddCU1I0giH0KyXa99aQEBa69PLrUmaSRgXkv7Tfmt9e+VxLM0teQuHZrDkrrB7OgIStDWiuEiaM2YUI9na24oGThueutFOYbM9rxiHQRaZ4h1YHQgIdNIFCsf9/t2D3UaeYSLrS9f8lflU09cEmBEQcNXxdDeyRtIISVPEZFLRMMIhq4MgKQICBQrJ+IDTYDOvVZVB929eJy0HwoGAQIZBuPhe9iM0FXjVJdNfg/L45XFuwVgFP6cuTkUiIVdMf8+64lhW1MYzzmhBQCC3GImERMHGPFPe5foYIfERFSMWp+9zvhcFhyUcxDgQECgIzVoojtCWtHdZG4rfsG6leQJygepqHhpouvT0N0HJpomJEY22TEWHChZIS5CYmGClxMQ3Oa0slPQlsTBBXUQDFwYaICiKU5jXsrYLMHEVCYV5RTBwYaBBaMCe5oQMfhOVmbdnTsXEBETN+zRIGPQdJvBqRGP/p33EIiAQkMBEJcoNYWoUcoINhQAAAQEABAQAEBAAQEAAABAQAEBAAAABAQAEBAAQEAAABAQAEBAAQEAAAAEBAEBAAAABAQAEBAAQEABAQAAAEBAAQEAAAAEBAAQEAAABAQAEBAAQEABAQAAAAQEAQEAAAAEBAAQEABAQAIDsAvKEKgNoNanGOAICAKUJyC71C9BqUo3xtAKyQ/0CtJpUYzyVgAyWBwfDl2+oY4BW8s1ojBdmgYgvqWeAVpJ6bKcWkKFC7eDKALTPdRmN7WIFZMR96hugVUw0picSkJFSfUGdA7SCLyaxPrJYIEaxyAsBaDZPsngU57J8c3e9e2n48uPwukQ7ADSOF8Pr3aH18WLSD8i0Fmb0xTdGNwIAzRKPG1nEI7MFcsYSeTy8rtIuAI1wWzKLR24CMiYi94bXJ7QPQG1RwPTveX3YubzvbigkCyMhWaCtAGrDzvC6P+lsS2kCckZIPh5e79N2AJWhpSdf5i0chQvImJBcGVkj852TGAlxEoDieDK6tKp2J+3aFgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIC28n8BBgD6KC2oYqAhZgAAAABJRU5ErkJggg=="
                                  />
                                  <p>${pr.nome}</p>
                              </div>`;
                          }
                              htmlNps +=`  </div>
                      </div>      
       `;
     
       var classSel1 = '',classSel2='',classSel3 ='',classSel4 ='',colorNPS='',exBallon='';
       if(graf.net_scope>=76){
          //  classSel1 = 'selectScope';
              colorNPS='#339933';
              exBallon ='A';
          }else if(graf.net_scope>=51&&graf.net_scope<=75){
            //  classSel2 = 'selectScope';
              exBallon ='B';
              colorNPS='#ffc10c';
          }else if(graf.net_scope>=1&&graf.net_scope<=50){
              exBallon ='C';
             // classSel3 = 'selectScope';
             colorNPS='#ff8c00';
          }else if(graf.net_scope<=0){
              exBallon ='D';
             // classSel4 = 'selectScope';
              colorNPS='#ed2124';
       }
                    var resScoreB = graf.data.promotores.porcentagem-graf.data.detratores.porcentagem;
                    htmlNps += ` <div  class="detalhes-nsp">
                      <div class="nsp_score" style='background:${colorNPS}!important' >
                          <div class="block" >
                              <h3>${resScoreB.toFixed(1)}</h3>
                              <p>NPS Score</p>
                          </div>
                      </div>
     
                      <ul>
                          <li>
                              <div class="detalhes_int ${classSel1}" >
                                  <div class="img_dado" ${exBallon=='A'?"style='background:#339933'":''}>
                                      <p ${(exBallon=='A')?'class="h"':''}><b>Zona de excelência (76 a 100)</b>`;
                                      if(exBallon=='A'){
                                       htmlNps +='<img src="data:image/png;base64,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" />';
                                      }
                                      htmlNps +=`</p></div>
                                  <!-- <span class="net_green">${graf.data.promotores.porcentagem}%</span> -->
                              </div>
                          </li>
                          <li>
                              <div class="detalhes_int ${classSel2}" >
                              <div class="img_dado" ${exBallon=='B'?"style='background:#ffc10c'":''}>
                              <!--   <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0NzMuOTM1IDQ3My45MzUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ3My45MzUgNDczLjkzNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGQzEwRTsiIGN4PSIyMzYuOTY3IiBjeT0iMjM2Ljk2NyIgcj0iMjM2Ljk2NyIvPgo8Zz4KCTxjaXJjbGUgc3R5bGU9ImZpbGw6IzMzMzMzMzsiIGN4PSIxNjQuOTM4IiBjeT0iMTU1LjIzMiIgcj0iMzcuMjE2Ii8+Cgk8Y2lyY2xlIHN0eWxlPSJmaWxsOiMzMzMzMzM7IiBjeD0iMzA1LjY2NyIgY3k9IjE1NS4yMzIiIHI9IjM3LjIxNiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6IzMzMzMzMzsiIGQ9Ik0zNDMuMjU3LDMxNi44NjJjLTU5LjI4MSw2MC4zMjktMTU0LjY2Myw1OS44NTQtMjEzLjQ1LTAuODk4ICAgYy04LjQtOC42ODUtMjEuNjE2LDQuNTYxLTEzLjIyNywxMy4yMjdjNjUuNzY5LDY3Ljk3LDE3My42NDUsNjguMzQsMjM5LjkwNSwwLjg5OCAgIEMzNjQuOTQ0LDMyMS40NzksMzUxLjcyMSwzMDguMjQ1LDM0My4yNTcsMzE2Ljg2MkwzNDMuMjU3LDMxNi44NjJ6Ii8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg=="
                                      />-->
                                      <!--<p>Entre 51 e 75 = Zona de Qualidade</p>-->
                                      <p><b>Zona de qualidade (51 a 75)</b>`;
                                      if(exBallon=='B'){
                                       htmlNps +='<img src="data:image/png;base64,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" />';
                                      }
                                      htmlNps +=`</p></div>
                                  <!-- <span class="net_yellow">${graf.data.neutros.porcentagem}%</span> -->
                              </div>
                          </li>
                          <li>
                              <div class="detalhes_int ${classSel3}" >
                                  <div class="img_dado" ${exBallon=='C'?"style='background:#ff8c00'":''}>
                                  <!--  <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0NzMuOTM1IDQ3My45MzUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ3My45MzUgNDczLjkzNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGQzEwRTsiIGN4PSIyMzYuOTY3IiBjeT0iMjM2Ljk2NyIgcj0iMjM2Ljk2NyIvPgo8Zz4KCTxwYXRoIHN0eWxlPSJmaWxsOiMzMzMzMzM7IiBkPSJNMzU2LjY3MSwzNTQuMWMtNjYuMjI2LTY3LjYxOC0xNzQuMjU1LTY3LjMzNy0yNDAuMDk2LDAuNzAzICAgYy04LjM4OSw4LjY2Niw0LjgyNywyMS45MTIsMTMuMjI3LDEzLjIyN2M1OC44Ny02MC44MywxNTQuMzg2LTYxLjIwNCwyMTMuNjQxLTAuNzAzQzM1MS44OTYsMzc1Ljk2LDM2NS4xMTYsMzYyLjcyMSwzNTYuNjcxLDM1NC4xICAgTDM1Ni42NzEsMzU0LjF6Ii8+Cgk8Y2lyY2xlIHN0eWxlPSJmaWxsOiMzMzMzMzM7IiBjeD0iMTY0LjkzOCIgY3k9IjE1NS4yMzIiIHI9IjM3LjIxNiIvPgoJPGNpcmNsZSBzdHlsZT0iZmlsbDojMzMzMzMzOyIgY3g9IjMwNS42NjciIGN5PSIxNTUuMjMyIiByPSIzNy4yMTYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K"
                                      />-->
                                      <!--<p>Entre 1 e 50 = Zona de Aperfeiçoamento</p>-->
                                      <p><b>Zona de aperfeiçoamento (1 a 50)</b>`;
                                      if(exBallon=='C'){
                                       htmlNps +='<img src="data:image/png;base64,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" />';
                                      }
                                      htmlNps +=`</p></div>
                                  <!-- <span class="net_yellow">${graf.data.neutros.porcentagem}%</span> -->
                              </div>
                          </li>
                          <li>
                              <div class="detalhes_int ${classSel4}" >
                                  <div class="img_dado" ${exBallon=='D'?"style='background:#ed2124'":''}>
                                     <!-- <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ1Ljg3NCA0NS44NzQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ1Ljg3NCA0NS44NzQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDEuMzg0LDM0Ljc2NGMtMS41OTctMC42OTctMy40NTcsMC4wMzUtNC4xNTIsMS42MzVjLTAuMDkxLDAuMjA3LTAuMTU3LDAuNDIzLTAuMiwwLjYzOGwtOC44NDEtMy44NDdsOC44NC0zLjg0NiAgICBjMC4wNDMsMC4yMTUsMC4xMDksMC40MywwLjIsMC42MzdjMC42OTUsMS41OTksMi41NTYsMi4zMyw0LjE1MiwxLjYzNWMxLjU5OC0wLjY5MywyLjMzLTIuNTUzLDEuNjM1LTQuMTUgICAgYy0wLjQ3NC0xLjA5LTEuNDg5LTEuNzc1LTIuNTkzLTEuODgzYzAuNjc0LTAuODgsMC44NjUtMi4wOTEsMC4zOTEtMy4xODFjLTAuNjk0LTEuNTk4LTIuNTU0LTIuMzI4LTQuMTUxLTEuNjM1ICAgIGMtMS41OTksMC42OTUtMi4zMjksMi41NTYtMS42MzUsNC4xNTNjMC4wOTIsMC4yMDksMC4yMDMsMC40MDMsMC4zMywwLjU4MmwtMTIuNDIxLDUuNDAybC0xMi40MjMtNS40MDIgICAgYzAuMTI4LTAuMTc5LDAuMjQtMC4zNzMsMC4zMzEtMC41ODJjMC42OTUtMS41OTgtMC4wMzctMy40NTgtMS42MzQtNC4xNTNjLTEuNTk5LTAuNjkzLTMuNDU4LDAuMDM3LTQuMTUyLDEuNjM1ICAgIGMtMC40NzQsMS4wOS0wLjI4NCwyLjMwMSwwLjM5MSwzLjE4MWMtMS4xMDQsMC4xMDctMi4xMiwwLjc5My0yLjU5MywxLjg4M2MtMC42OTUsMS41OTgsMC4wMzcsMy40NTcsMS42MzQsNC4xNSAgICBjMS41OTcsMC42OTUsMy40NTctMC4wMzYsNC4xNTItMS42MzVjMC4wOTEtMC4yMDcsMC4xNTctMC40MjIsMC4yLTAuNjM3bDguODQsMy44NDZsLTguODQyLDMuODQ2ICAgIGMtMC4wNDMtMC4yMTUtMC4xMDktMC40My0wLjItMC42MzljLTAuNjk1LTEuNTk5LTIuNTU1LTIuMzMtNC4xNTItMS42MzVjLTEuNTk3LDAuNjk0LTIuMzMsMi41NTUtMS42MzQsNC4xNTEgICAgYzAuNDczLDEuMDksMS40ODksMS43NzQsMi41OTMsMS44ODNjLTAuNjc1LDAuODgtMC44NjUsMi4wOTEtMC4zOTEsMy4xODFjMC42OTQsMS41OTksMi41NTQsMi4zMjgsNC4xNTIsMS42MzYgICAgYzEuNTk4LTAuNjk3LDIuMzMtMi41NTYsMS42MzUtNC4xNTNjLTAuMDkxLTAuMjEtMC4yMDItMC40MDItMC4zMy0wLjU4MWwxMi40MjItNS40MDNsMTIuNDIyLDUuNDAzICAgIGMtMC4xMjcsMC4xNzktMC4yMzgsMC4zNzEtMC4zMywwLjU4MWMtMC42OTQsMS41OTgsMC4wMzcsMy40NTYsMS42MzUsNC4xNTNjMS41OTgsMC42OTIsMy40NTgtMC4wMzcsNC4xNTEtMS42MzYgICAgYzAuNDc1LTEuMDksMC4yODQtMi4zMDEtMC4zOTEtMy4xODFjMS4xMDQtMC4xMDYsMi4xMi0wLjc5MywyLjU5My0xLjg4M0M0My43MTQsMzcuMzE2LDQyLjk4MSwzNS40NTcsNDEuMzg0LDM0Ljc2NHoiIGZpbGw9IiMwMDAwMDAiLz4KCQk8cGF0aCBkPSJNMTMuNDU1LDE4Ljc0NWgwLjkyNXYzLjcwOGMwLDEuNjIxLDEuMzM0LDIuOTQ3LDIuOTU1LDIuOTQ3aDUuNTU0aDAuMWg1LjU1NWMxLjYyLDAsMi44OTMtMS4zMjYsMi44OTMtMi45NDd2LTMuNzA4ICAgIGgwLjk4NWMyLjYyMywwLDQuNzQzLTIuMTI2LDQuNzQzLTQuNzQ5YzAtNC4wODQtMS43Ni03Ljk3MS00LjgyMS0xMC42NzJDMzEuMDQyLDIuMTc2LDI3LjQ3NSwwLDIyLjk0MSwwICAgIGMtNC43MzIsMC04LjEwOSwyLjE3NC05LjQxMSwzLjMyNGMtMy4wNjIsMi43MDEtNC44Miw2LjU4OC00LjgyLDEwLjY3MkM4LjcxMSwxNi42MTksMTAuODMzLDE4Ljc0NSwxMy40NTUsMTguNzQ1eiBNMjYuMTY4LDguMTA3ICAgIGg1LjM5NmMwLjMyOCwwLjQ3NiwwLjUyLDEuMDk0LDAuNTIsMS43MzljMCwxLjc3Ni0xLjQ0LDMuMjA0LTMuMjE4LDMuMjA0Yy0xLjc3NiwwLTMuMjE4LTEuNDI4LTMuMjE4LTMuMjA0ICAgIEMyNS42NDgsOS4yMDEsMjUuODQsOC41ODMsMjYuMTY4LDguMTA3eiBNMjAuNjAzLDE1LjM0OWwxLjc0My0yLjk4YzAuMTIzLTAuMjExLDAuMzQ5LTAuMzM5LDAuNTkyLTAuMzQxICAgIGMwLjI0NCwwLjAwMiwwLjQ3LDAuMTMsMC41OTIsMC4zNDFsMS43NDMsMi45OGMwLjEyNSwwLjIxNCwwLjEyNSwwLjQ3MiwwLjAwMiwwLjY4NWMtMC4xMjMsMC4yMTUtMC4zNTEsMC4zMzUtMC41OTgsMC4zMzVoLTEuNjg5ICAgIGgtMC4xaC0xLjY4OWMtMC4yNDgsMC0wLjQ3Ni0wLjEyLTAuNTk5LTAuMzM1QzIwLjQ3NywxNS44MiwyMC40NzksMTUuNTYyLDIwLjYwMywxNS4zNDl6IE0xNC4zMTIsOC4xMDdoNS4zOTcgICAgYzAuMzI4LDAuNDc2LDAuNTE5LDEuMDk0LDAuNTE5LDEuNzM5YzAsMS43NzYtMS40NDEsMy4yMDQtMy4yMTcsMy4yMDRjLTEuNzc3LDAtMy4yMTctMS40MjgtMy4yMTctMy4yMDQgICAgQzEzLjc5Myw5LjIwMSwxMy45ODQsOC41ODMsMTQuMzEyLDguMTA3eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo="
                                      />-->
                                      <!-- <p>Entre -100 e 0 = Zona Crítica</p>-->
                                      <p><b>Zona crítica (-100 a 0)</b>`;
                                      if(exBallon=='D'){
                                       htmlNps +='<img src="data:image/png;base64,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" />';
                                      }
                                      htmlNps +=`</p></div>
                                  <!-- <span class="net_red">${graf.data.detratores.porcentagem}%</span> -->
                              </div>
                          </li>
                      </ul>
                      </div>
                      </div>`;
                     
                     if(graf.justificativa.promotores.length||graf.justificativa.neutros.length||graf.justificativa.detratores.length){
                      htmlNps += `<h3  class="just">Justificativa</h3>`;
                     }
                     if(graf.justificativa.promotores.length){
                       htmlNps += ` <table class="table table-striped" style="margin-bottom:0px">
                      <thead>
                      <tr>
                      <th scope="col">
                      <div class="detalhes-cab">
                      <img src="${urlBase}public/assets_gestor/img/green_box.png" />
                      </div>
                      <p class="t-ti-tb net_green"><strong>Promotores</strong></p>
                      </th>
                      </thead> 
                      <tbody>     
                      `;
                      for(var item of graf.justificativa.promotores){  
                      htmlNps += `  <tr><th>
                                  <strong>${item.label}</strong> (${item.total})
                                  </th></tr>`;
                      }
                          htmlNps += ` 
                          </tbody>
                      </table>`;
                    }
                    if(graf.justificativa.neutros.length){
                      htmlNps += `<table class="table table-striped" style="margin-bottom:0px"><thead>
                      <tr>
                      <th scope="col">
                      <div class="detalhes-cab">
                      <img src="${urlBase}public/assets_gestor/img/yellow_box.png" />
                      </div>
                      <p class="t-ti-tb net_yellow"><strong>Neutros</strong></p>
                      </th>
                      </thead> 
                      <tbody>     
                     `;
                      for(var item of graf.justificativa.neutros){  
                              htmlNps += `  <tr><th>
                                  <strong>${item.label}</strong> (${item.total})
                                  </th></tr>`;
                      }
                          htmlNps += `   
                          </tbody>
                      </table>`;
                    }
                    if(graf.justificativa.detratores.length){
                      htmlNps += `<table class="table table-striped" style="margin-bottom:0px"><thead>
                      <tr>
                      <th scope="col">
                      <div class="detalhes-cab">
                      <img src="${urlBase}public/assets_gestor/img/red_box.png" />
                      </div>
                      <p class="t-ti-tb net_red"><strong>Detratores</strong></p>
                      </th>
                      </thead> 
                      <tbody>`;
                      for(var item of graf.justificativa.detratores){  
                         htmlNps += `  <tr><th>
                           <strong>${item.label}</strong> (${item.total})
                         </th></tr>`;
                      }
                      htmlNps += `</tbody>
                      </table>`;
                    }
     
              document.getElementById(k).innerHTML = htmlNps;
  }

  if(document.getElementById(`${k}Descricao`)){
    document.getElementById(`${k}Descricao`).innerHTML=`<p style="margin: 0px;">Base: ${d.totalResp}</p>`
  }

}

// function gNps(keyId,graf){
  
//          if(document.getElementById(`${keyId}Descricao`)){
//           document.getElementById(`${keyId}Descricao`).innerHTML=`<p style="margin: 0px;">Base: ${graf.totalResp}</p>`
//         }
// }

function gGrafMenuracao(k,graf){
  if(typeof graf =='string'){
    graf = JSON.parse(graf);
  }

  var pergRespUnica = ['AVALIACAO_SATISFACAO', 'FECHADA_RESPOSTA_UNICA'];
  let styleColor1= ['#0070c0', '#f00', '#00b050', '#b3a2c7', '#ffc000']
  if (graf.tipo_resp_entrada == 'MONETARIO'
      || graf.tipo_resp_entrada == 'NUMERICO'
      // || graf.tipo_pergunta == 'FECHADA_RESPOSTA_UNICA'
      // || graf.tipo_pergunta == 'SEMI_ABERTA'
      ) {
      let dChart = {
          "type": "serial",
          "theme": "light",
          // 'angle': 30,
          'fontSize':12,
          'marginBottom': 150,
          'marginTop': 30,
          "legend": {
              "position": 'top',
              // "useGraphSettings": true
          },
          "dataProvider": graf.data,
          "colors": ['#f00', '#0070c0', '#00b050', '#b3a2c7', '#ffc000', '#000000'],

          "valueAxes": [{
              "gridColor": "#cccccc",
              //'gridAlpha':1,
              "integersOnly": true,
              "reversed": false,
              "axisAlpha": 0,
              "dashLength": 5,
              "gridCount": 10,
              "position": "left",
          }],
          "gridAboveGraphs": true,
          "startDuration": 0.5,
          "chartCursor": {
              "cursorAlpha": 0.5,
              "zoomable": false
          },
          "categoryField": "fase",
          "categoryAxis": {
              /* "gridThickness":0,
                 "gridPosition": "start",
                 "axisAlpha": 0,
                 "fillAlpha": 0.05,*/
              //   "gridAlpha": 0.1,
              //"fillColor": "#000000",
              //"position": "bottom",
              //"tickPosition": "start",
              //"tickLength": 20
          },
          "export": { "enabled": true, "position": "bottom-right" },
      }
      if (graf.tipo_resp_entrada != 'MONETARIO' && graf.tipo_resp_entrada != 'NUMERICO') {
          dChart['valueAxes'][0]['maximum'] = 100;
          dChart['valueAxes'][0]['minimum'] = 0;
          dChart['valueAxes'][0]['minVerticalGap'] = 100;
      } else {
          dChart['valueAxes'][0]['minimum'] = 0;
      }

      let arGraphs = [], indG = 0;
      //  let arLabelPosition1 = ['top','right','left','inside','middle','bottom'];
      let arLabelPosition = ['middle', 'right', 'top', 'bottom'];
      for (let iG in graf.fases) {

          arGraphs[indG] = {};
          arGraphs[indG]['balloonText'] = "[[category]]: <b>[[value]]%</b>";
          arGraphs[indG]['fillAlphas'] = 0;
          arGraphs[indG]["bullet"] = "round";
          if (graf.tipo_resp_entrada == 'MONETARIO') {
              arGraphs[indG]["labelText"] = "R$[[value]]";
          } else if (graf.tipo_resp_entrada == 'NUMERICO') {
              arGraphs[indG]["labelText"] = "[[value]]";
          } else {
              arGraphs[indG]["labelText"] = "[[value]]%";
          }
          let labelPosition = 'middle';

          if (graf.fases[iG]['valueField'] == 'max') {
              labelPosition = 'top';
          } else if (graf.fases[iG]['valueField'] == 'min') {
              labelPosition = 'left'; 
          } else if (graf.fases[iG]['valueField'] == 'med') {
              labelPosition = 'right';
          } else {
              labelPosition = arLabelPosition[Math.floor(Math.random() * 3)];
              // console.log(Math.floor(Math.random() * 4),'labelPosition ',labelPosition)
          }
          arGraphs[indG]['labelPosition'] = labelPosition;

          arGraphs[indG]['bulletSize'] = (graf.fases[iG]['bulletSize']) ? graf.fases[iG]['bulletSize'] : 9;
          arGraphs[indG]['customBulletField'] = 'customBullet';
          arGraphs[indG]['customBullet'] = graf.fases[iG]['customBullet'];
          arGraphs[indG]["title"] = graf.fases[iG]['titulo'],
              arGraphs[indG]["valueField"] = graf.fases[iG]['valueField'];
          arGraphs[indG]["labelFunction"] = function (item) {
              let percent = item.values.value;

              if (graf.tipo_resp_entrada == 'MONETARIO') {
                  percent = percent.toFixed(2);
                  return 'R$' + percent.toString().replace(".", ",");
              } else if (graf.tipo_resp_entrada == 'NUMERICO') {
                  if (!percent || percent == 'NaN' || percent == '0') {
                      percent = '0,0';
                  } else {
                      percent = percent.toFixed(1);
                  }
                  return percent.toString().replace(".", ",");
              } else {
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  if (!percent || percent == 'NaN' || percent == '0') {
                      percent = '0.0';
                  }
                  percent = percent.toString();
              }
              return percent.replace(".", ",") + "%";
          },
              arGraphs[indG]["balloonFunction"] = function (graphDataItem, graph) {
                  let valor = graphDataItem.values.value;

                  if (graf.tipo_resp_entrada == 'MONETARIO') {
                      valor = valor.toFixed(2);
                      return graph.title + ': R$' + valor.toString().replace(".", ",");
                  } else if (graf.tipo_resp_entrada == 'NUMERICO') {
                      valor = valor.toFixed(1);
                      return graph.title + ': ' + valor.toString().replace(".", ",");
                  } else {
                      if (valor < 100) {
                          valor = valor.toFixed(1);
                      }
                      return graph.title + ': ' + valor.toString().replace(".", ",") + "%";
                  }
              },
              indG++;
      }
      dChart["graphs"] = arGraphs;
     //$('<div id="idGraf' + graf.codigo + '"></div>').attr("class", "dimCharts").appendTo("[data-chart=relatorio]");
      AmCharts.makeChart(k, dChart);

      /////////
  } else if (graf.tipo_pergunta == 'NET_PROMOTER_SCORE') {
    //  console.log(graf)

      let dChart = {
          "type": "serial", "theme": "light", 'marginTop': 30,
          // 'angle': 30,
          "legend": {
              position: 'top',
              //    "useGraphSettings": true
          },
          "colors": ['#f00', '#ffc000', '#00b050'],
          //  'fontSize':16,
          "dataProvider": graf.data.classificacao,
          "valueAxes": [{
              "gridColor": "#cccccc",
              "integersOnly": true,
              "maximum": 100,
              "minimum": 1,
              "minVerticalGap": 100,
              "reversed": false,
              "axisAlpha": 0,
              "dashLength": 5,
              "gridCount": 10,
              "position": "left",
              "axisTitleOffset": 20,
           
          }],
          "startDuration": 0.5,
          "chartCursor": {
              "cursorAlpha": 0,
              "zoomable": false
          },
          "categoryField": "fase",
          "categoryAxis": {
              "axisAlpha": 0,
             // stackType:"100%"
             // position:'top'
              //offset:5,
              // "gridPosition": "start",
              //"axisAlpha": 0,
              //"fillAlpha": 0.05,
              //"fillColor": "#000000",
              // "gridAlpha": 0,
              // "position": "bottom"
          },
          "export": { "enabled": true, "position": "bottom-right" }
      }
      let arGraphs = [], indG = 0;
      let arLabelPosition = ['left', 'right', 'top'];
      for (let iG in graf.fases.classificacao) {
          arGraphs[indG] = {};
          arGraphs[indG]['balloonText'] = "[[category]]: <b>[[value]]%</b>";
          arGraphs[indG]["bullet"] = "round";
          arGraphs[indG]["labelText"] = "[[value]]%";
          arGraphs[indG]['labelPosition'] = arLabelPosition[parseInt(iG)];
          arGraphs[indG]["title"] = graf.fases.classificacao[iG]['titulo'],
              arGraphs[indG]["valueField"] = graf.fases.classificacao[iG]['valueField'];
          arGraphs[indG]['bulletSize'] = (graf.fases.classificacao[iG]['bulletSize']) ? graf.fases.classificacao[iG]['bulletSize'] : 9;
          arGraphs[indG]['customBulletField'] = 'customBullet';
          arGraphs[indG]['customBullet'] = graf.fases.classificacao[iG]['customBullet'];
          arGraphs[indG]["balloonFunction"] = function (graphDataItem, graph) {
              let valor = graphDataItem.values.value;
              if (valor < 100) {
                  valor = valor.toFixed(1);
              }
              valor = valor.toString();
              return graph.title + ': ' + valor.replace(".", ",") + "%";
          },
              arGraphs[indG]["labelFunction"] = function (item) {
                  let percent = item.values.value;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  if (!percent || percent == 'NaN' || percent == '0') {
                      percent = '0,0';
                  }
                  return percent.toString().replace(".", ",") + "%";
              },
              indG++;
      }
      dChart["graphs"] = arGraphs;
    //  $('<div id="idGrafClassificacao' + graf.codigo + '"></div>').attr("class", "dimCharts").appendTo("[data-chart=relatorio]");
      AmCharts.makeChart(k+`2`, dChart);
      ////// * * * * * ** * * * * * * *  * * * 
    
      let graphs = [], valueAxes = { 
          minimum: -0, minVerticalGap: 100, maximum: 0 ,
          "gridColor": "#cccccc",
          // "axisAlpha": 0,
          //     "dashLength": 5,
          //     "gridCount": 15,
          // "integersOnly": true,
          // "integersOnly": true,
          //     "reversed": false,
          //     "axisAlpha": 0,
          //     "dashLength": 1,
          //     "gridCount": 5,
              // "position": "left",
      };
      if (graf.data.NSPScoreStatus.negativo) {
          valueAxes['minimum'] = -100;
          graphs.push({
              "fillAlphas": 0.8, "lineAlpha": 0.2, "type": "column", "valueField": "dValueNeg",
              "title": "Male", "labelText": "[[value]]%", 'labelPosition': 'right',
              "clustered": false,
              "lineColorField": "color",
              "fillColorsField": "color",
              "labelFunction": function (item, b) {
                  let percent = item.values.value;
                  if (!percent || percent == 'NaN' || percent == '0') {
                      return;
                  }
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  if (percent <= -100) {
                      percent=-100;
                  }
                  return percent.toString().replace(".", ",") + "";
              },
              "balloonFunction": function (item) {
                  let data = item.values.value;
                  if (!data || data == 'NaN' || data == '0') {
                      return;
                  }
                  if (data < 100) {
                      data = data.toFixed(1);
                  }
                  return item.category + ': ' + data.toString().replace(".", ",") + "";
              }
          });
      }
      if (graf.data.NSPScoreStatus.positivo) {
          valueAxes['maximum'] = 100;
          graphs.push({
              "fillAlphas": 0.8, "lineAlpha": 0.2, "type": "column", "valueField": "dValuePos",
              "title": "Female", "labelText": "[[value]]%", 'labelPosition': 'right',
              "clustered": false,
              "lineColorField": "color",
              "fillColorsField": "color",
              "labelFunction": function (item) {
                  let percent = item.values.value.toString();
                  if (!percent || percent == 'NaN' || percent == '0') {
                      return;
                  }
                  return percent.replace(".", ",") + "";
              },
              "balloonFunction": function (item) {
                  let data = item.values.value;
                  if (data < 100) {
                      data = data.toFixed(1);
                  }
                  if (!data || data == 'NaN' || data == '0') {
                      return;
                  }
                  return item.category + ': ' + data.toString().replace(".", ",") + "";
              }
          });
      }
      graf.data.NSPScore.forEach(nps => {
          if(!nps.dValuePos){
              nps.color='#c81508';
          }else{
              if(nps.dValuePos>=76){
                  nps.color='#019c1f';
              }else if(nps.dValuePos>=51&&nps.dValuePos<=75){
                  nps.color='#ffba00';
              }else if(nps.dValuePos>=1&&nps.dValuePos<=50){
                  nps.color='#ff8c00';
              }
          }
      });
      dChart = {
          "type": "serial",
          "theme": "light",
          // 'angle': 30,
          'marginTop': 30,
          //'fontSize':16,
          "marginBottom": 50,
          "dataProvider": graf.data.NSPScore,
          "startDuration": 1,
         // "colors": ['#0070c0', '#0070c0'],
          "graphs": graphs,
          "categoryField": "fase",
          "categoryAxis": {
              "gridPosition": "start",
              "gridAlpha": 0.2,
              "axisAlpha": 0
          },
          "valueAxes": [valueAxes],
          "balloon": {
              "fixedPosition": true
          },
          "export": {
              "enabled": true
          }
      };


      //$('<div id="idGrafNSPScore' + graf.codigo + '"></div>').attr("class", "dimCharts").appendTo("[data-chart=relatorio]");
      chart = AmCharts.makeChart(k, dChart);
  } else if (graf.tipo_pergunta == 'AVALIACAO_SATISFACAO') {
              //    console.log(graf.tipo_pergunta)
      let dFase = graf.fases;
      let dChart = {
          "theme": "light",
          // 'fontSize':16,
          "dataProvider": [],
          "valueField": "dValue",
          "titleField": "dLabel",
          // "columnSpacing":0,
          "export": { "enabled": true },
          colors: styleColor1,
      }
      let colorsMat;

          dChart['marginRight'] = 30;
          dChart['rotate'] = true;
          colorsMat = ['#77e119','#ffef0a','#ffa200','#e73129','#f00'];
  
      dChart['dataProvider'] = dFase.data;

      // dChart['depth3D'] = 20;
      // dChart['angle'] = 30;
      let graphs = [], dGraphs = dFase.graphs;

      for (let iG in dGraphs) {
          graphs[iG] = {
              "balloonText": `${dGraphs[iG]['titulo']}:[[value]]% ([[value]])`,
              "fillAlphas": 0.7,
              "id": "AmGraph-" + (parseInt(iG) + 1),
              "lineAlpha": 0.2,
              customBullet:dGraphs[iG]['img'],
             // customMarker:dGraphs[iG]['img'],
              "customBulletField": "img",
              "bulletOffset": 0,
              "bulletSize": 25,
              'columnWidth': 0.7,
              "fillAlphas": 0.8,
        //     "lineAlpha": 0.2,
              "title": dGraphs[iG]['titulo'],
              "labelText": "[[value]]%",
              'labelPosition': 'bottom',
              
              // "fillAlphas": 1,
              //  "lineAlpha": 0.2,
              "labelOffset": -6,
              // "labelOffset": 0,
            //  "color": colorsMat[iG],
              "type": "column",
              "valueField": dGraphs[iG]['valueField'],
              'balloonFunction': function (graphDataItem, graph) {
                //   console.log(graphDataItem,graph)
                  let percent = graphDataItem.values.value;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  percent = percent.toString();
                  if (percent == 'NaN' || percent == '0.0') {
                      return '';
                  }
                  return graph.title + ': ' + percent.replace(".", ",") + "%";
              },
              'labelFunction': function (info) {
                  let percent = info.values.percents;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  if (percent<=0 || percent == 'NaN' || percent == '0.0') {
                      return '';
                  }
                  percent =  percent.toString().replace(".", ",") + "%";
                  return (percent=='NaN%')?'':percent;
              }
          }
      }
      // dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 10, maximum: 100 }];
      dChart['valueAxes'] = [{ "minimum": 0, minHorizontalGap: 100, minVerticalGap: 10, maximum: 100 }];
      dChart['graphs'] = graphs;
      dChart['type'] = 'serial';
      dChart['colors'] = colorsMat;
      dChart['categoryField'] = 'dLabel';
      dChart['marginBottom'] = 50;
      dChart['categoryAxis'] = {
          "gridPosition": "start",
          "fillAlpha": 0.05,
          //   "position": "right",
          //  "position": "bottom",
      };
      dChart['legend'] = {
          "horizontalGap": 10,
          "useGraphSettings": false,
          "markerSize": 8,
          position: 'top',
      };
      this.chart = this.AmCharts.makeChart(k, dChart);
  } else if (graf.tipo_pergunta == 'ESCALA_LIKERT_0_10'
            //  || graf.tipo_pergunta == 'DICOTOMICA'
             || graf.tipo_pergunta == 'FECHADA_RESPOSTA_UNICA'
            //  || graf.tipo_pergunta == 'SEMI_ABERTA'
             ) {
              //    console.log(graf.tipo_pergunta)
      let dFase = graf.fases;
      let dChart = {
          "theme": "light",
          // 'fontSize':16,
          "dataProvider": [],
          "valueField": "dValue",
          "titleField": "dLabel",
          // "columnSpacing":0,
          "export": { "enabled": true },
          colors: styleColor1,
      }
      let colorsMat;
      if (graf.tipo_pergunta == 'DICOTOMICA') {
          dChart['rotate'] = false
          dChart['marginTop'] = 30;
           colorsMat = ['#0083d1', '#e62a2b', '#11971a', '#fffe01', '#670167', '#ed7d31'];
      } else {
          dChart['marginRight'] = 30;
          dChart['rotate'] = true;
          colorsMat = ['#f00', '#ffc000', '#00b050','#0000FF','#FF34B3','#6A5ACD','#EE7621','#006400','#CD853F','#00FF00','#8B7355','#FFFACD'];
      }
      dChart['dataProvider'] = dFase.data;

      // dChart['depth3D'] = 20;
      // dChart['angle'] = 30;
      let graphs = [], dGraphs = dFase.graphs;

      for (let iG in dGraphs) {
          graphs[iG] = {
              "balloonText": `${dGraphs[iG]['titulo']}:[[value]]% ([[value]])`,
              "fillAlphas": 0.7,
              "id": "AmGraph-" + (parseInt(iG) + 1),
              "lineAlpha": 0.2,
              //   "columnSpacing":0,
              'columnWidth': 0.7,
              "title": dGraphs[iG]['titulo'],
              "labelText": "[[value]]%",
              'labelPosition': 'bottom',
              // "fillAlphas": 1,
              //  "lineAlpha": 0.2,
              "labelOffset": 1,
              // "labelOffset": 0,
            //  "color": colorsMat[iG],
              "type": "column",
              "valueField": dGraphs[iG]['valueField'],
              'balloonFunction': function (graphDataItem, graph) {
                  let percent = graphDataItem.values.value;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  percent = percent.toString();
                  if (percent == 'NaN' || percent == '0.0') {
                      return '';
                  }
                  return graph.title + ': ' + percent.replace(".", ",") + "%";
              },
              'labelFunction': function (info) {
                  let percent = info.values.percents;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  if (percent<=0 || percent == 'NaN' || percent == '0.0') {
                      return '';
                  }
                  percent =  percent.toString().replace(".", ",") + "%";
                  return (percent=='NaN%')?'':percent;
              }
          }
      }
      // dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 10, maximum: 100 }];
      dChart['valueAxes'] = [{ "minimum": 0, minHorizontalGap: 100, minVerticalGap: 100, maximum: 100 }];
      dChart['graphs'] = graphs;
      dChart['type'] = 'serial';
      dChart['colors'] = colorsMat;
      dChart['categoryField'] = 'dLabel';
      dChart['marginBottom'] = 50;
      dChart['categoryAxis'] = {
          "gridPosition": "start",
          "fillAlpha": 0.05,
          //   "position": "right",
          //  "position": "bottom",
      };
      dChart['legend'] = {
          "horizontalGap": 10,
          "useGraphSettings": false,
          "markerSize": 8,
          position: 'top',
      };
      this.chart = this.AmCharts.makeChart(k, dChart);

  } else if (graf.tipo_pergunta == 'MATRIZ') {
      let dChart = {
          "type": "serial",
          "theme": "light",
          "colors": styleColor1,
          // 'angle': 10,
          //'fontSize':16,
          "legend": {
              "horizontalGap": 10,
              "maxColumns": 8,
              "useGraphSettings": true,
              "markerSize": 6,
              position: 'top',
          },
          "dataProvider":graf.data ,
          "valueAxes": [{
            //  "gridColor": "#aaaaaa",
              "stackType": "regular",
              "axisAlpha": 0.5,
              "gridAlpha": 0,
              "integersOnly": true,
              "minimum": 0, minVerticalGap: 100, minHorizontalGap: 100, maximum: 100
          }],
          "gridAboveGraphs": true,
          "rotate": true,
          "columnWidth": 0,
          "categoryField": "dLabel",
          "categoryAxis": {
          "gridPosition":"start",
          //"gridThickness":0,
          "axisAlpha": 0.4,
          "gridAlpha": 0.03,
          //"position": "bottom"ottom"
          "guides": [],
          minorTickLength:5
          //"gridThickness":1,
          //"widthField":300,
          },
          "export": { "enabled": true },
      }
      let guides =[];
      for (let iL in graf.label) {
          let num = parseInt(iL)+1;
          guides[iL] ={
              "category": `M${num} `+graf.label[iL]['inicio'],
              "toCategory": `M${num} `+graf.label[iL]['fim'],
              "LineAlpha": 0.5,
              "expand": true,
              color:"#000000",
              "label": graf.label[iL]['titulo'],
              "position": "left",
              "tickLength": 120,
              "toValue": 10,
              //dashLength:100,
              }
      }
      dChart["categoryAxis"]['guides'] = guides;

      let arGraphs=[],indG=0; //, sCors = ['#0070c0', '#f00', '#00b050', '#b3a2c7', '#ffc000'];
      // let fases = [
      //   {titulo:'AQui 1',valueField:'aqui1'},
      //   {titulo:'AQui 2',valueField:'aqui2'},
      // ]
      for (let iG in graf.fases) {
          arGraphs[indG] = {};
          arGraphs[indG]['balloonText'] = "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]%</b></span>";
          arGraphs[indG]['fillAlphas'] = 0.7;
          arGraphs[indG]["labelText"] = "[[value]]%";
          arGraphs[indG]["columnWidth"] = 0.4;
          arGraphs[indG]["columnSpacing"] = 0.1;
          // arGraphs[indG]["columnSpacing"] = 0.1;
          arGraphs[indG]["lineAlpha"] = 0.3;
          arGraphs[indG]["type"] = "column";
          arGraphs[indG]["title"] = graf.fases[iG]['titulo'],
          arGraphs[indG]["valueField"] = graf.fases[iG]['valueField'];
          arGraphs[indG]["labelFunction"] = function (item) {
              let percent = item.values.value;
              if (percent < 100) {
                  percent = percent.toFixed(1);
              }
              if (!percent || percent == 'NaN' || percent == '0.0') {
                  return '';
              }
              return percent.toString().replace(".", ",") + "%";
          },
              arGraphs[indG]["balloonFunction"] = function (graphDataItem, graph) {
                  let data = graphDataItem.values.value;
                  if (data < 100) {
                      data = data.toFixed(1);
                  }
                  if (!data || data == 'NaN' || data == '0.0') {
                      return '';
                  }
                  return graph.title + ': ' + data.toString().replace(".", ",") + "%";
              },
              indG++;
      }
      
      dChart["graphs"] = arGraphs;
      this.AmCharts.makeChart(k, dChart);

    /* for (let iFase in graf.fases) {
          let dFase = graf.fases[iFase];
          let dChart = {
              "theme": "light",
              "dataProvider": [],
              "valueField": "dValue",
              "titleField": "dLabel",
              "export": { "enabled": true },
              colors: styleColor1,
          }
          dChart['dataProvider'] = dFase.data;

          // dChart['depth3D'] = 20;
          // dChart['angle'] = 30;
          let graphs = [], dGraphs = dFase.graphs;
          let colorsMat = ['#0083d1', '#e62a2b', '#11971a', '#fffe01', '#670167', '#ed7d31'];
          dChart['rotate'] = true;
          for (let iG in dGraphs) {
              graphs[iG] = {
                  "balloonText": `${dGraphs[iG]['titulo']}:[[value]]% ([[value]])`,
                  "fillAlphas": 0.6,
                  "id": "AmGraph-" + (parseInt(iG) + 1),
                  "lineAlpha": 0.2,
                  "title": dGraphs[iG]['titulo'],
                  "labelText": "[[value]]%",
                  'labelPosition': 'top',
                  "labelOffset": 0,
                  //  "color": colorsMat[iG],
                  "type": "column",
                  "valueField": dGraphs[iG]['valueField'],
                  'balloonFunction': function(graphDataItem, graph) {
                      let percent = graphDataItem.values.value;
                      if (percent < 100) {
                          percent = percent.toFixed(1);
                      }
                      percent = percent.toString();
                      if (percent == '0') {
                          percent = '0,0';
                      }
                      return graph.title + ': ' + percent.replace(".", ",") + "%";
                  },
                  labelFunction: function(info) {
                      let percent = info.values.percents.toFixed(1).toString();
                      if (percent == 'NaN' || percent == '0') {
                          percent = '0,0';
                      }
                      return percent.replace(".", ",") + "%";
                  }
              }
          }
          // dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 10, maximum: 100 }];
          dChart['valueAxes'] = [{ "minimum": 0, minHorizontalGap: 10, maximum: 100 }];
          dChart['graphs'] = graphs;
          dChart['type'] = 'serial';
          dChart['colors'] = colorsMat;
          dChart['categoryField'] = 'dLabel';
          dChart['marginBottom'] = 50;
          dChart['categoryAxis'] = {
              "gridPosition": "start",
              "fillAlpha": 0.05,
              "position": "left",
          };
          dChart['legend'] = {
              position: 'top',
              "horizontalGap": 10,
              "useGraphSettings": false,
              "markerSize": 8,
          };
          chart = AmCharts.makeChart(`idGraf${graf.codigo}${iFase}`, dChart);
      }*/

  } else if (graf.tipo_pergunta == 'FECHADA_RESPOSTA_MULTIPLA') {
      // let colorsMat = ['#ffc000', '#f00', '#00b050', '#b3a2c7', '#ffc000', '#ed7d31'];
      let colorsMat = ['#0083d8', '#e62a2b', '#11971a', '#fffe01', '#070767', '#ed7d31','#f00e01', '#070ff67', '#ea7d24'];
      let dChart = {
          "theme": "light",
          "dataProvider": [],
          "valueField": "dValue",
          "titleField": "dLabel",
          "export": { "enabled": true },
          // colors: ['#0083d8', '#e62a2b', '#11971a', '#fffe01', '#070767', '#ed7d31'],
      }
      dChart['dataProvider'] = graf.data;
      // dChart['depth3D'] = 20;
      // dChart['angle'] = 30;
      let graphs = [], dGraphs = graf.fases;
      dChart['rotate'] = true;
      for (let iG in dGraphs) {
          graphs[iG] = {
              "balloonText": `${dGraphs[iG]['titulo']}:[[value]]% ([[value]])`,
              "fillAlphas": 0.6,
              "id": "AmGraph-" + (parseInt(iG) + 1),
              "lineAlpha": 0.6,
              "title": dGraphs[iG]['titulo'],
              "labelText": "[[value]]%",
              'labelPosition': 'top',
              "labelOffset": 0,
              // "color": colorsMat[iG],
              "type": "column",
              "valueField": dGraphs[iG]['valueField'],
              'balloonFunction': function(graphDataItem, graph) {
                  let percent = graphDataItem.values.value;
                  if (percent < 100) {
                      percent = percent.toFixed(1);
                  }
                  percent = percent.toString();
                  if (percent == '0') {
                      percent = '0,0';
                  }
                  return graph.title + ': ' + percent.replace(".", ",") + "%";
              },
              labelFunction: function(info) {
                  let percent = info.values.value.toFixed(1).toString();
                  if (!percent || percent == 'NaN' || percent == '0.0') {
                      return '';
                  }
                  return percent.replace(".", ",") + "%";
              }
          }
      }
      // dChart['valueAxes'] = [{ "minimum": 0, minVerticalGap: 10, maximum: 100 }];
      dChart['valueAxes'] = [{ "minimum": 0, minHorizontalGap: 100, maximum: 100 }];
      dChart['graphs'] = graphs;
      dChart['type'] = 'serial';
      dChart['colors'] = colorsMat;
      // dChart['dataProvider'] = graf.data['data'];
      dChart['categoryField'] = 'dLabel';
      dChart['marginBottom'] = 50;
      dChart['categoryAxis'] = { "gridPosition": "start", "fillAlpha": 0.05, "position": "left" };
      dChart['legend'] = {position: 'top', "horizontalGap": 100, "useGraphSettings": false, "markerSize": 8 };
      $('<div id="idGraf' + graf.codigo + '" ></div>').attr("class", "dimCharts").appendTo("[data-chart=relatorio]");
     chart = AmCharts.makeChart(k, dChart);

  } else if (pergRespUnica.includes(graf.tipo_pergunta)) {
      // colors: ['#0083d1', '#e62a2b', '#11971a', '#fffe01', '#670167', '#ed7d31'],
      let dChart = {
          "type": "serial",
          "theme": "light",
          "colors": styleColor1,
          "legend": {
              position: 'top',
              "horizontalGap": 10,
              "maxColumns": 3,
              "useGraphSettings": true,
              "markerSize": 10
          },
          "dataProvider": graf.data,
          "valueAxes": [{
              "stackType": "regular",
              "axisAlpha": 0.5,
              "gridAlpha": 0,
              "minimum": 0, minVerticalGap: 10, minHorizontalGap: 100, maximum: 100
          }],

          "rotate": true,
          "categoryField": "fase",
          "categoryAxis": {
              "gridPosition": "start",
              "axisAlpha": 0,
              "gridAlpha": 0,
              //"position": "bottom"ottom"
          },
          "export": { "enabled": true }
      }
      let arGraphs = [], indG = 0; //, sCors = ['#0070c0', '#f00', '#00b050', '#b3a2c7', '#ffc000'];
      for (let iG in graf.fases) {
          arGraphs[indG] = {};
          arGraphs[indG]['balloonText'] = "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]%</b></span>";
          arGraphs[indG]['fillAlphas'] = 0.8;
          arGraphs[indG]["labelText"] = "[[value]]";
          arGraphs[indG]["lineAlpha"] = 0.3;
          arGraphs[indG]["type"] = "column";
          //  arGraphs[indG]["color"] = sCors[iG];
          arGraphs[indG]["title"] = graf.fases[iG]['titulo'],
              arGraphs[indG]["valueField"] = graf.fases[iG]['valueField'];
          arGraphs[indG]["labelFunction"] = function(item) {
              let percent = item.values.value.toString();
              if (!percent || percent == 'NaN' || percent == '0') {
                  percent = '0,0';
              }
              return percent.replace(".", ",") + "%";
          }
          indG++;
      }
       dChart["graphs"] = arGraphs;
      // $('<div id="idGraf' + graf.codigo + '"></div>').attr("class", "dimCharts").appendTo("[data-chart=relatorio]");
      AmCharts.makeChart(k, dChart);
  }
  if(document.getElementById(`${k}Descricao`)){
    document.getElementById(`${k}Descricao`).innerHTML=`<p style="margin: 0px;">Base: ${graf.totalResp}</p>`
  }
}
