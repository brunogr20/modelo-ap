$(function(){
			$('[data-bt=check]').click(function(){
			let pai=$(this).parent('div');
			if($(this).prop("tagName")=='BUTTON'){
				pai.parent('div').parent('div').find('[data-bt=check]').removeClass('checked-border');
			}else{
				pai.parent('div').find('[data-bt=check]').removeClass('checked-border');
			}
			$(this).addClass('checked-border');
			for(var ob of $(`[name=${pai.find('input').attr('name')}]`)){
			 $(ob).removeAttr('checked');
			}
   pai.find('input').attr('checked',true);
  });

  $('.bt-continuar').on('click', function (e) {
  e.preventDefault;
  var verificar = proximaPerg(numPg);
  if (verificar.status) {	
    document.querySelector('div[data-id-tela=idTela' + numPg + ']').style.display = 'none';
    numPg = verificar.posicao;
    document.querySelector('div[data-id-tela=idTela' + numPg + ']').style.display = '';
    $('html,body').animate({scrollTop: $('#top-pagina').offset().top}, 'slow');
				toggleVoltar(numPg);
				textBtEnviar();
  }
  });

  $('.bt-voltar').on('click', function (e) {
  for (var i = (numPg - 1); i >= 0; i--) {
    if (valid[i]['visivel']) {
    document.querySelector('div[data-id-tela=idTela' + numPg + ']').style.display = 'none';
    numPg = i;
				document.querySelector('div[data-id-tela=idTela' + i + ']').style.display = '';
				var nAtual=numPgVisisvel -= 1;
				pg.atual = nAtual
				textBtEnviar();
	   $(".atual").html(nAtual);
	   document.querySelector('.bt-continuar').innerHTML  = 'CONTINUAR <i class="fa fa-arrow-right fa-fw" ></i>';
    toggleVoltar(i);
    $('html,body').animate({scrollTop: $('#top-pagina').offset().top}, 'slow');
    return false;
    }
  }
  });
});

function toggleVoltar(n) {
  if (n > 0) {
  $('.bt-voltar').show();
  } else {
  $('.bt-voltar').hide();
  }
}

function iniciarQuestionario() {
	encadearPerguntas();
	if(document.querySelector('.abertura-questionario')){
		document.querySelector('.abertura-questionario').style.display = 'none';
	}
	$('html,body').animate({scrollTop: $('#top-pagina').offset().top}, 'slow');
	document.querySelector('.corpo-questionario').style.display = '';
	document.querySelector('div[data-id-tela=idTela0]').style.display = '';
}

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

function calculaZeros(qtd){
	zeros = "";
	for (z=0; z<qtd; z++)
		zeros += "0";
	return zeros;
}

function currencyFormat(fld, milSep, decSep, decQtd, e) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.event) ? e.keyCode : e.which;
	if (whichCode == 13) return true;  // Enter
	if (whichCode == 8) return true;  // Delete (Bug fixed)
	if (whichCode == "") return true;
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
		if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) fld.value = '';
	if (len > 0 && len <= decQtd){
		zeros = calculaZeros(decQtd - len);
		fld.value = '0' + decSep + zeros + aux;
	}
	if (len > decQtd) {
		aux2 = '';
		for (j = 0, i = len - (decQtd + 1); i >= 0; i--) {
			if (j == 3) {
				aux2 += milSep;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}
		fld.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
			fld.value += aux2.charAt(i);
		fld.value += decSep + aux.substr(len - decQtd, len);
	}
	return false;
}

function auxCurrencyFormatNeg (fld, milSep, decSep, e) {
	var whichCode = (window.Event) ? e.which : e.keyCode;
	var fldVal = fld.value;
	var len=0;
	
	//NÃO ACEITA O '-' SE NÃO FOR A PRIMEIRA TECLA
	if ((whichCode == 45) && (fldVal!="")) {
		return false;
	}

	//PRIMEIRA TECLA É O "-"	
	if ((whichCode == 45) && (fldVal=="")) {
		whichCode = 48; //como se tivesse digitado 0
		fld.value = "-"+currencyFormatNeg(fldVal,milSep,decSep,whichCode);
		return false;
	}

	//PROXIMAS TECLAS COM VALOR NEGATIVO
	if ((fldVal.charAt(0) == '-') && (fldVal!="")) {
		len = fldVal.length;
		fldVal = fldVal.substring(1, len);
		fld.value = "-"+currencyFormatNeg(fldVal,milSep,decSep,whichCode);
		return false;
	}

	//PROXIMAS TECLAS COM VALOR POSITIVO
	if (fldVal.charAt(0) != '-') {
		//alert ("aqui");
		fld.value = currencyFormatNeg(fldVal,milSep,decSep,whichCode);
		return false;
	}
	return false;
}

function currencyFormatNeg(fldVal, milSep, decSep, whichCode) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	if (whichCode == 13) return true;  // Enter
	if (whichCode == 8) return true;  // Delete (Bug fixed)
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fldVal.length;
	for(i = 0; i < len; i++)
		if ((fldVal.charAt(i) != '0') && (fldVal.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(fldVal.charAt(i))!=-1) aux += fldVal.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) fldVal = '';
	if (len == 1) fldVal = '0'+ decSep + '0' + aux;
	if (len == 2) fldVal = '0'+ decSep + aux;
	if (len > 2) {
		aux2 = '';
		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += milSep;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}
		fldVal = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
			fldVal += aux2.charAt(i);
		fldVal += decSep + aux.substr(len - 2, len);
	}
	return fldVal;
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
		num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
		cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + '' + num + ',' + cents);
}

function formatToCalc(v) {
	v = v.replace(/\./g,"");
	v = v.replace(",",".");
	v = parseFloat(v);
	return v;
}

function isNumberKey(evt) {
	var charCode = (window.event) ? evt.keyCode : evt.which;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	  return false;
	return true;
}

function isAlfaNumeric(evt) {
	var charCode = (window.event) ? evt.keyCode : evt.which;
	if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
	  return false;
	return true;
}