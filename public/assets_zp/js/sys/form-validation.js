$(function () {
  $.fn.extend({
    formValidation: function (arValid) {
      if (Object.keys(arValid).length) {  
          let sForm = 'form[name=' + $(this).attr('name') + '] ', data=[];
          for(let key in arValid){
            let s = $(sForm + `[name=${key}]`);
            if(!s.length){console.log(`Validation error: ${key} not find`);continue;}
            if(s.attr('type')=='radio'){
              let vCheck=$(sForm+` [name=${key}]:checked`).val();
              data[key]=(vCheck!==undefined)?vCheck:'';
            }else if(s.attr('type')=='file'){
              data[key]=s.attr('data-required-name');
            }else if(s.attr('type')=='checkbox'){
             let vCheck=$(sForm+`[data-checkbox=${key}]:checked`);
             data[key]={};
             if(vCheck.length){
              vCheck.each(function(_,e){
                data[key][$(e).attr('name')] =$(e).val();
              });
            }
            }else{data[key] = s.val();}
          }
          for(let key in arValid){
            let s=$(sForm+'[name='+key+']'),val='',
            label=$(`label[for=${key}]`).text().replace('*','');
            if(!s.length){continue;}
            for(let valid of arValid[key]){
              if(typeof valid === 'string'){
                 let aSplit = valid.split('|');
                 let aV = aSplit[0].split(':'),m=(aSplit[1])?aSplit[1]:'';
                 let v= aV[0],val=data[key];
                 if(v.substr(0,1)=='?'){
                   v=v.substr(1,v.length).trim();
                   if(!Object.keys(val).length&&v!='required'){continue;}
                   console.log(Object.keys(val).length,v!='required')
                 }
                 if(v=="required"&&!Object.keys(val).length){
                   if(!m){m = $.vMessage('required',{attr:label});}
                   $.alertMessage('warning',m);
                   return false;
                 }else if(v=='email'){
                   if(!(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(val))){
                     if(!m){m = $.vMessage('email',{attr:label});}
                     $.alertMessage('warning',m);
                     return false;
                   }
                 }else if(v=='max'){
                   if(aV.length<2||!aV[1]){console.log('Erro: Max espera um valor');return false;}
                   let max=parseInt(aV[1]);
                   if(s.attr('type')=='checkbox'){
                     if(Object.keys(val).length>max){
                      if(!m){m = $.vMessage('maxCheckbox',{attr:label,max});}
                      $.alertMessage('warning',m);
                      return false;
                     }
                   }else if(!isNaN(parseFloat(val)) && isFinite(val)){
                     if(val>max){            
                      if(!m){m = $.vMessage('maxNum',{attr:label,max});}
                      $.alertMessage('warning',m);
                      return false;
                     }
                  }else if(typeof val =='string'){
                     if(val.length>max){            
                       if(!m){m = $.vMessage('maxStr',{attr:label,max});}
                       $.alertMessage('warning',m);
                       return false;
                     }                  
                  }
                 }else if(v=='min'){
                   if(aV.length<2||!aV[1]){console.log('Erro: Min espera um valor');return false;}
                   let min=parseInt(aV[1]);
                   if(s.attr('type')=='checkbox'){
                    if(Object.keys(val).length<min){
                    if(!m){m = $.vMessage('minCheckbox',{attr:label,min});}
                    $.alertMessage('warning',m);
                    return false;
                    }
                   }else if(!isNaN(parseFloat(val)) && isFinite(val)){
                    if(val<min){            
                    if(!m){m = $.vMessage('minNum',{attr:label,min});}
                    $.alertMessage('warning',m);
                    return false;
                    }
                  }else if(typeof val =='string'){
                    if(val.length<min){            
                      if(!m){m = $.vMessage('minStr',{attr:label,min});}
                      $.alertMessage('warning',m);
                      return false;
                    }                  
                  }
                 }else if(v=='cpf'){
                  if(!$.isCpf(val)){
                    if(!m){m = $.vMessage('cpf',{attr:label});}
                    $.alertMessage('warning',m);
                    return false;
                  }
                 }else if(v=='cnpj'){
                  if(!$.isCnpj(val)){
                    if(!m){m = $.vMessage('cnpj',{attr:label});}
                    $.alertMessage('warning',m);
                    return false;
                  }
                }else if (v=='data') {
                  var isData=true;
                  for(var n of val.split('')){
                   if(!['0','1','2','3','4','5','6','7','8','9','/'].includes(n)){
                     isData=false;
                     break;
                   }
                  }
                  if(isData){
                    var dia = parseInt(val.substr(0,2)),mes = parseInt(val.substr(3,2));
                    isData=(val.length!=10)?false:true;
                    isData=(dia<1||dia>31)?false:true;
                    isData=(mes<1||mes>12)?false:true;
                  }

                  if(!isData){
                    if(!m){m = $.vMessage('data',{attr:label});}
                    $.alertMessage('warning',m);
                    return false;
                  }
                 }else if(v == 'numerico') {
                    var isNumerico=true;
                    for(var n of val.split('')){
                      if(!['0','1','2','3','4','5','6','7','8','9'].includes(n)){
                       isNumerico=false;
                       break;
                      }
                    } 
                    if(!isNumerico){
                      if(!m){m = $.vMessage('numerico',{attr:label});}
                      $.alertMessage('warning',m);
                      return false;
                    }
                  }else if (ob.e == 'monetario') {
                      var tMoney='',isMoney=true;
                      for(var n of val.split('')){
                        if(!['0','1','2','3','4','5','6','7','8','9','.',','].includes(n)){
                         isMoney=false;
                         break;
                        }
                        tMoney+=n.replace('.','');
                      } 
                      tMoney=tMoney.replace(/,/g, ".");
                       if(!isMoney){
                        if(!m){m = $.vMessage('monetario',{attr:label});}
                        $.alertMessage('warning',m);
                        return false;
                       }
                 }else if(v=='cpfCnpj'){
                  let n = val.replace(/\D/g, ""),tMsg=null;
                  if(n.length==11){
                    if(!$.isCpf(val)){tMsg='cpf2';}
                  }else if(n.length==14){
                    if(!$.isCnpj(val)){tMsg='cnpj2';}
                  }else{
                    tMsg='cpfCnpj';
                  }
                  if(tMsg){
                    if(!m){m = $.vMessage(tMsg,{attr:label});}
                    $.alertMessage('warning',m);
                    return false;
                  }
                 }
              }else if(typeof valid === 'function'){
                 if(valid(data,data[key])===false){
                   return false;
                 }
              }
            }
          }
      }
      return true;
    },
  });
  const DIGITOS_CPF  = 11;
  const DIGITOS_CNPJ = 14;
  const DIG_CNPJ_BASE = 8;

  String.prototype.lpad = function(pSize, pCharPad){
    let str = this;
    let dif = pSize - str.length;
    let ch = String(pCharPad).charAt(0);
    for (; dif>0; dif--) str = ch + str;
    return (str);
  } 
  String.prototype.trim = function(){
    return this.replace(/^\s*/,"").replace(/\s*$/,"");
  }
  $.extend({
    formatCpfCnpj:function(pCpfCnpj, pUseSepar, pIsCnpj){
      if (pIsCnpj==null) pIsCnpj = false;
      if (pUseSepar==null) pUseSepar = true;
      let maxDigitos = (pIsCnpj)? DIGITOS_CNPJ: DIGITOS_CPF;
      let n = String(pCpfCnpj).replace(/\D/g,"").replace(/^0+/,"");
      n = n.lpad(maxDigitos,'0');
      if (!pUseSepar) return n;    
      if (pIsCnpj){
        return n.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
      }else{
        return n.replace(/(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
      }
    },
    dvCpfCnpj: function(pEfetivo, pIsCnpj){//Calcula os 2 dígitos verificadores 
      if (pIsCnpj==null) pIsCnpj = false;
      let i,j,k,soma,dv;
      let cicloPeso = pIsCnpj? DIG_CNPJ_BASE: DIGITOS_CPF;
      let maxDigitos = pIsCnpj? DIGITOS_CNPJ: DIGITOS_CPF;
      let calculado = $.formatCpfCnpj(pEfetivo, false, pIsCnpj);
      calculado = calculado.substring(2, maxDigitos);
      let result = ""; 
      for (j = 1; j <= 2; j++){
        k = 2;
        soma = 0;
        for (i = calculado.length-1; i >= 0; i--){
          soma += (calculado.charAt(i)-'0') * k;
          k = (k-1) % cicloPeso + 2;
        }
        dv = 11 - soma % 11;
        if (dv > 9) dv = 0;
        calculado += dv;
        result += dv;
      }
      return result;
    }, 
    isCpf:function(pCpf){
      let n = $.formatCpfCnpj(pCpf, false, false);
      let base = n.substring(0, n.length - 2);
      let digitos = $.dvCpfCnpj(base, false);
      let algUnico, i;
      if (n != base + digitos) return false;
      algUnico = true;
      for (i=1; algUnico && i<DIGITOS_CPF; i++){
        algUnico = (n.charAt(i-1) == n.charAt(i));
      }
      return (!algUnico);
    },
    isCnpj:function(pCnpj){
      let n = $.formatCpfCnpj(pCnpj, false, true);
      let base = n.substring(0, DIG_CNPJ_BASE);
      let ordem = n.substring(DIG_CNPJ_BASE, 12);
      let digitos = $.dvCpfCnpj(base + ordem, true);
      let algUnico;
      if (n != base + ordem + digitos) return false;
      algUnico = n.charAt(0) != '0';
      for (i=1; algUnico && i<DIG_CNPJ_BASE; i++){
        algUnico = (n.charAt(i-1) == n.charAt(i));
      }
      if (algUnico) return false;
      if (ordem == "0000") return false;
      return (base == "00000000"||parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
    },
    vMessage:function(t,vet){
      let mDefault ={
         'required':'O campo :attr é obrigatório.',
         'maxNum':'O campo :attr não pode ser superior a :max.',
         'maxStr':'O campo :attr não pode ser superior a :max caracteres.',
         'maxCheckbox':'O campo :attr não pode ter mais de :max elemento(s) selecionado(s).',
         'minNum':'O campo :attr deve ser pelo menos :min.',
         'minStr':'O campo :attr deve ter pelo menos :min caracteres.',
         'minCheckbox':'O campo :attr não pode ter menos de :min elemento(s) selecionado(s).',
         'email':'O campo :attr deve ser um endereço de e-mail válido.',
         'cpf':'O campo :attr deve ser um CPF válido.',
         'cpf2':'O campo :attr não tem um CPF válido.',
         'cnpj':'O campo :attr deve ser um CNPJ válido.',
         'cnpj2':'O campo :attr não tem um CNPJ válido.',
         'cpfCnpj':'O campo :attr deve ser um CPF ou CNPJ válido.',
         'data':'O campo :attr deve ser uma data válida.',
         'numerico':'O campo :attr deve conter apenas números.',
         'monetario':'O campo :attr deve ser um valor em reais válido.',
       };
      let msg =mDefault[t];
       for(let k in vet){
         msg =  msg.replace(`:${k}`,`${vet[k]}`);
       }
      return msg;
     },
     markRequiredInputs:function(){
      for(field in objPage.form.save.validate){
          if(objPage.form.save.validate[field].includes('required')){
          $(`label[for=${field}`).html($(`label[for=${field}`).html()+"<b style='font-size:larger'>*</b>");
        }
      }
    }
  });
  $.markRequiredInputs();
});
