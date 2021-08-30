<?php 
use Illuminate\Support\Facades\Route;
/**
 * Retorna o diretorio padrão de arquivo com base na url da sessão
 */
function files_path($context=""){
  if($context == ""){
    $context = Route::currentRouteName();
    $context = str_replace("Controller","",$context);
    $context = str_replace('ZP\ZP',"",$context);
    $context = str_replace("ZP\Sys","",$context);
    $context = str_replace('\Sys','Sys',$context);
    // $context = explode('.',preg_replace('/(Controller)$/',"",$context));
    $context = explode('.',$context);
  }
  return "arqConteudo/arq".$context[0]."/";
}

function ckeckEstrutString($s){
   $s = str_replace('	',' ',$s);
  return $s;

}

function scapeJsonString($s){
  // htmlspecialchars
  $s = str_replace('"','&quot;',$s);
 return $s;

}


function retrieveDatetime($d){
  return substr($d,8,2).'/'.substr($d,5,2).'/'.substr($d,0,4);
  // substr($d,0,4).substr($d,5,2).substr($d,8,2).substr($d,11,2).substr($d,14,2).substr($d,17,2);
}
function retrieveDate($date){
 if(!$date){return "";}
 $ano = substr($date,0,4);
 $mes = substr($date,4,2);
 $dia = substr($date,6,2);
 return $dia."/".$mes."/".$ano;
 }
function retrieveTypeDate($date){
 if(!$date){return "";}
 $ano = substr($date,0,4);
 $mes = substr($date,5,2);
 $dia = substr($date,8,2);
 return $dia."/".$mes."/".$ano;
 }
 function retrieveHour($hour){
 if(!$hour){ return "";}
  if(strlen($hour) == 14){
    $hou = substr($hour,8,2);
    $min = substr($hour,10,2);
    $seg = substr($hour,10,2);
    $r = $hou.":".$min.":".$seg;
  }elseif(strlen($hour) == 6){
   $hou = substr($hour,0,2);
   $min = substr($hour,2,2);
   $seg = substr($hour,4,5);
   $r = $hou.":".$min.":".$seg;
  }else{
   $hou = substr($hour,0,2);
   $min = substr($hour,2,2);
   $r = $hou.":".$min;
  }
 return $r;
 }

 function customDate($d){
  $meses = array('01' => 'Janeiro','02' =>'Fevereiro','03' =>'Março','04' =>'Abril','05' =>'Maio','06' =>'Junho',
  '07' =>'Julho','08' =>'Agosto','09' =>'Setembro','10' =>'Outubro','11' =>'Novembro','12'=>'Dezembro');
  return substr($d,8,2).', '.$meses[substr($d,5,2)].', '.substr($d,0,4);
 }

 function formatText($t){
  return str_replace('<br/>', '&nbsp;',nl2br($t));
 }

 function countTime($time){
  $period='';
  $diaH = date('d');
  $mesH = date('m');
  $anoH = date('Y');
  $horaH = date('H');
  $minH = date('i');
  $segH = date('s');
  
  $ano = substr($time,0,4);
  $mes = substr($time,4,2);
  $dia = substr($time,6,2);
  $hora = substr($time,8,2);
  $min = substr($time,10,2);
  $seg = substr($time,12,2);
  
  if ($anoH > $ano) {
      $period = 'há '.($anoH-$ano) . ' anos';
  } elseif ($mesH > $mes) {
    $period = 'há '.($mesH-$mes).' meses';
  } elseif ($diaH > $dia) {
    $period = 'há '.($diaH-$dia).' dias';
  } elseif ($horaH > $hora) {
    $period = 'há '.($horaH-$hora).' horas';
  } elseif ($minH > $min) {
    $period = 'há '.($minH-$min).' minutos';
  } elseif ($segH > $seg) {
   $period = 'há '.($segH-$seg).' segundos';
  } else {
  $period = 'postado agora';
  }
return $period;
 }

 function strUrl($str){
  $str = preg_replace("/[^a-zA-Z0-9-.]/", "-", strtr(utf8_decode(trim($str)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"), "aaaaeeiooouuncAAAAEEIOOOUUNC-"));
  $str = strtolower($str);
  $i = strlen($str);
  while ($i > 0){
   $str = str_replace('--', '-', $str, $i);
  }
  return (substr($str, -1) == '-')?substr($str, 0, -1):$str;
}

function formatToReal($val){
  return number_format($val, 2, ',', '.');
}

function percentage($v, $t) {
  if (!$t) {return '0.0';  }
  $per=number_format(( $v * 100 ) / $t, 1, '.', '');
  return ($per=='100.0')?'100':$per;  
 }
 /**
  * @internal Função recebe uma string estilo camelCase e retorna uma separada por underline;
  * @param String string de entrada
  */
function camelCaseToUnderline($str,$glue="_"){
  preg_match_all('/[A-Z0-9]/',$str,$matches);
  $pieces = preg_split('/[A-Z0-9]/',$str);
  $r="";
  $c=0;
  foreach($pieces as $p){
      if($c!=0){
        if($r==""){
          $r.= strtolower($matches[0][$c-1].$p);
        }else{
          $r.= "{$glue}".strtolower($matches[0][$c-1].$p);
        }
      }
      else{
        $r.= $p;
      }
    $c++;
  }
  return strtolower($r);
 }

function resum($text,$l){
  if(strlen($text)>$l){
  return substr($text,0,$l).'...';
  }
  return $text;
}

 function ufData(){
   return [
     'AC'=>'AC',
     'AL'=>'AL',
     'AM'=>'AM',
     'AP'=>'AP',
     'BA'=>'BA',
     'CE'=>'CE',
     'DF'=>'DF',
     'ES'=>'ES',
     'GO'=>'GO',
     'MA'=>'MA',
     'MG'=>'MG',
     'MT'=>'MT',
     'PA'=>'PA',
     'PB'=>'PB',
     'PE'=>'PE',
     'PI'=>'PI',
     'PR'=>'PR',
     'PI'=>'PI',
     'RJ'=>'RJ',
     'RN'=>'RN',
     'RO'=>'RO',
     'RR'=>'RR',
     'RS'=>'RS',
     'SC'=>'SC',
     'SE'=>'SE',
     'SP'=>'SP',
     'TO'=>'TO'
   ];
}

 function isCpf($cpf) {
  $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
  $invalidos = array('00000000000',
      '11111111111',
      '22222222222',
      '33333333333',
      '44444444444',
      '55555555555',
      '66666666666',
      '77777777777',
      '88888888888',
      '99999999999');
  if (in_array($cpf, $invalidos))
   return false;
// Valida tamanho
  if (strlen($cpf) != 11)
   return false;
// Calcula e confere primeiro dígito verificador
  for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
   $soma += $cpf{$i} * $j;
  $resto = $soma % 11;
  if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
   return false;
// Calcula e confere segundo dígito verificador
  for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
   $soma += $cpf{$i} * $j;
  $resto = $soma % 11;
  return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
 }

  function isCnpj($cnpj) {
  $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
// Valida tamanho
  if (strlen($cnpj) != 14)
   return false;
// Valida primeiro dígito verificador
  for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
   $soma += $cnpj{$i} * $j;
   $j = ($j == 2) ? 9 : $j - 1;
  }
  $resto = $soma % 11;
  if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
   return false;
// Valida segundo dígito verificador
  for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
   $soma += $cnpj{$i} * $j;
   $j = ($j == 2) ? 9 : $j - 1;
  }
  $resto = $soma % 11;
  return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
 }





?>
