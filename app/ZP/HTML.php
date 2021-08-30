<?php 
// namespace App\Core;
use App\Services\Sys\SysCoreService;
class HTML {
  
  static function initForm($at = []){
    $aux_at = $at;
    $at['name']=(!empty($at['name']))?$at['name']:"formData";
    $at['tabs']=(!empty($at['tabs']))?$at['tabs']:["geral"=>["name"=>"Geral"]];

    $at['class']=(!empty($at['class']))?$at['class']:'col-md-6 col-xs-12';


    $tbs = "";
    if(is_array($at['tabs'])){
      $at = (Object)$at;
      $tbs = (!empty($at->tabs))?"<div class='' role='tabpanel' data-example-id='togglable-tabs'><ul id='myTab' class='nav nav-tabs bar_tabs' role='tablist'>" : "";
      $c=0;
      foreach($at->tabs as $key => $value){
       $name =(!empty($value['name']))?$value['name']:'Geral';
       $class= ($c == 0)? "active":"";
       $expanded = ($c == 0)? true:false;
       $tbs .= "<li role='presentation'  ".SELF::createStringAttrs($value,['name'])." class='$class'><a href='#tb-$key' id='tab-$key' role='tab' data-toggle='tab' aria-expanded='$expanded'>$name</a></li>";
       $c++;
      }
    }
    $at = (Object)$at;
    $tbs .= (!empty($at->tabs))?"</ul>" : "";
    $tbs=(!empty($at->tabs) && !is_array($at->tabs))?"":$tbs; // Controle para apresentar o formúlário sem abas
     $t =  (!is_array($at->tabs)) ? $at->tabs:"Inserção";

    $v= "<div id='container-form' class='".$at->class."'>
          <div class='x_panel'>
              <div class='x_title'>
                  <h2 data-action-title='form-action'>$t <small></small></h2>
                  <div class='clearfix'></div>
          </div>
         <div class='x_content'>
          $tbs        
        <form id='mainForm' class='tab-content' ".SELF::createStringAttrs($aux_at,['tabs','name'])." name='$at->name' enctype='multipart/form-data' >";
    return $v;
  }

  static function initFormRel($at = []){
    $at['name']=(!empty($at['name']))?$at['name']:"formDataRel";
    $v= "<form  method='post' id='form-relatorio' ".SELF::createStringAttrs($at)." class='tab-content' >";
    return $v;
  }

  static function endFormRel($at = []){
    $v= "
    </form>";
    return $v;
  }

  static function endForm(){
    return "
    </form>
                    </div>
            </div>
        </div>
    </div>
   </div>";
  }
  
  static function initTab($id_tab,$active=false){
    $active = ($active)?"active":"";
    return " <div role='tabpanel' class='tab-pane fade $active in' id='tb-$id_tab' aria-labelledby='tab-$id_tab'>
                <div class='form-horizontal form-label-left'>";
  }
  
  static function endTab(){
    return ""
    . "</div></div>";
  }
  
  static function inputHidden($at = []){
    $at['name']=(!empty($at['name']))?$at['name']:"nome_hidden";
    $at['type']="hidden";
    $at = (Object)$at;
    $v = "<input ".SELF::createStringAttrs($at)." >";
    return $v;
}

  static function status(){
   return  "<div  style='height: 0px;padding: 0px;' class='form-group'>
              <label class='control-label col-md-3 col-sm-3 col-xs-12'></label>
              <div class='col-md-9 col-sm-9 col-xs-12'>
                <div class='btn-status-form-df'>
                  <label data-toggle='tooltip' data-placement='top' title='Registro Ativo'>
                    <input name='status' id='swStatus' type='checkbox' value='SIM' class='js-switch' checked />
                  </label>
                </div>
              </div>
            </div>
            <script>
              var elem = document.querySelector('#swStatus');
              let swStatus = new Switchery(elem,{ size: 'small',color:'var(--cinza-escuro)' });
            </script>
            ";
  }
  
  static function input($at = []){
      $at['name']=(!empty($at['name']))?$at['name']:"nome_padrao";
      $at['label']=(!empty($at['label']))?$at['label']:"Label Padrão";
      $at['type']=(!empty($at['type']))?$at['type']:"text";
      $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
      $v = "<div class='form-group {$hidden}' data-form-group='{$at['name']}'>
          <label for='{$at['name']}' class='control-label col-md-3 col-sm-3 col-xs-12'>{$at['label']}</label>
          <div class='col-md-9 col-sm-9 col-xs-12'>
            <input ".SELF::createStringAttrs((Object)$at,['mask','id'])." id='{$at['name']}' class='form-control' >";
      
        if(!empty($at['mask'])){
          $mask=$at['mask'][0];
          $aMask=(!empty($at['mask'][1]))?json_encode($at['mask'][1]):$aMask='0';      
          $v .= "
          <script>
           var aMask = (Object.keys({$aMask}).length)?{$aMask}:null;
           $('input[name={$at['name']}]').mask('{$mask}',aMask);
          </script>";
         }
        return   $v .= "</div>
                     </div>";
       
  }

  static function radio($at=[], $radios=[]){
    $at['label']=(!empty($at['label']))?$at['label']:"Label do Grupo";
    $at['name']=(!empty($at['name']))?$at['name']:"name_grupo";
    $at['class']=(!empty($at['class']))?$at['class']:"";
    $radios=(!empty($radios))?$radios:["label"=>"Radio1","value"=>"Radio1"];
    $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
    $r="";
    $at =(Object)$at;
    foreach($radios as $radio){
      $radio = (Object)$radio;
      $r .= "<div class='radio'>
        <label >
            <div class='iradio_flat-green' style='position: relative;'>
                <input type='radio' class='flat' name='{$at->name}' ".SELF::createStringAttrs($radio,["name"])." style='position: absolute; opacity: 0;'>
                <ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;'></ins>
            </div> {$radio->label}
        </label>
      </div>";
    }
    return "<div class='form-group {$hidden}' data-form-group='{$at->name}'>
              <label for='$at->name'  class='col-md-3 col-sm-3 col-xs-12 control-label'>{$at->label}</label>
              <div class=' $at->class col-md-9 col-sm-9 col-xs-12'>
                {$r}
              </div>
            </div>";
   
  }
  
  static function checkbox($at=[], $checks=[]){
    $at['label']=(!empty($at['label']))?$at['label']:"Label padrão checkboxes";
    $at['name']=(!empty($at['name']))?$at['name']:"nome_aqui";
    $checks=(!empty($checks))?$checks:["label"=>"chekbox1"];
    $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
    $at = (Object)$at;
    $c="";
    foreach($checks as $check){
      $check = (Object)$check;
      $c .= "<!-- checkbox item 1 -->
          <div class='checkbox'>
            <label>
              <div class='icheckbox_flat-green' style='position: relative;'>
                <input type='checkbox' data-checkbox='{$at->name}' class='flat' ".SELF::createStringAttrs($check,['type'])."  style='position: absolute; opacity: 0;'/>
                <ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;'>
                </ins>
              </div> {$check->label}
            </label>
          </div>";
    }
     $v= "<div class='form-group {$hidden}' data-form-group='{$at->name}'>
        <label for='{$at->name}' class='col-md-3 col-sm-3 col-xs-12 control-label'>{$at->label}</label>
        <div type='checkbox' name='{$at->name}'></div>
        <div class='col-md-9 col-sm-9 col-xs-12'>
            {$c}
        </div>
     </div>";
     return $v;
  }
  
  static function select($at=[],$op=[],$opDef=[""=>"Escolha uma opção"]){
    $at['name']=(!empty($at['name']))?$at['name']:"nome_padrao";
    $at['label']=(!empty($at['label']))?$at['label']:"Label Padrão";
      $at['value']=(!empty($at['value']))?$at['value']:"";
      $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
      $at = (Object)$at;
    $vo="";

    foreach ($opDef as $key => $value)
      $vo.="<option ".(($key == $at->value) ? 'selected':'')." value='{$key}'>{$value}</option>";
    foreach ($op as $key => $value)
      $vo.="<option ".(($key == $at->value) ? 'selected':'')." value='{$key}'>{$value}</option>";

    return "<div  class='form-group {$hidden}' data-form-group='{$at->name}'>
          <label for='{$at->name}' class='control-label col-md-3 col-sm-3 col-xs-12'>{$at->label}</label>
          <div class='col-md-9 col-sm-9 col-xs-12'>
            <select ".SELF::createStringAttrs($at)." class='form-control'>
              {$vo}
            </select>
          </div>
        </div>";
  }

  static function textarea($at=[]){
      $at['name']=(!empty($at['name']))?$at['name']:"nome_padrao";
      $at['label']=(!empty($at['label']))?$at['label']:"Label Padrão";
      $at['type']=(!empty($at['type']))?$at['type']:"text";
      $at['rows']=(!empty($at['rows']))?$at['rows']:"3";
      $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
      $at = (Object)$at;
      return "<div class='form-group $hidden}' data-form-group='{$at->name}'>
            <label for='{$at->name}' class='control-label col-md-3 col-sm-3 col-xs-12'>{$at->label}</label>
            <div class='col-md-9 col-sm-9 col-xs-12'>
              <textarea class='form-control' ".SELF::createStringAttrs($at,['id'])." id='{$at->name}' ></textarea>
            </div>
          </div>";
  }

  static function datetime($at=[],$params=["format"=>"DD/MM/YYYY"]){
      $at['name']=(!empty($at['name']))?$at['name']:"datetime";
      $at['label']=(!empty($at['label']))?$at['label']:"Label Padrão";
      $at['placeholder']=(!empty($at['placeholder']))?$at['placeholder']:$params['format'];
      $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
      $at = (Object)$at;
      $format = (!empty($params['format']))?$params['format']:"DD/MM/YYYY";
      $icon = (in_array($format,['HH','HH:mm','HH:mm:ss']))?"glyphicon-time":"glyphicon-calendar";
   
      $v= "
     <div class='form-group {$hidden}' data-form-group='{$at->name}'>
         <label for='{$at->name}' class='control-label col-md-3 col-sm-3 col-xs-12'>{$at->label}</label>
         <div class='col-md-4 col-sm-9 col-xs-12'>
           <div class='input-group date' data-picker='picker_{$at->name}'>     
               <input data-picker='picker_{$at->name}' ".SELF::createStringAttrs($at,['type','id'])." data-type='datetime' data-format='{$format}' type='text' class='form-control'/>
               <span class=\"input-group-addon\">
                    <span class=\"glyphicon $icon\"></span>
               </span>
           </div>
         </div>
        <script>
          $('[data-picker=picker_{$at->name}]').datetimepicker({
            ";
              foreach ($params as $k => $p){
                  $v.= $k.":"."'".$p."',";
              }
            $v.="locale:'pt-br'
                });
           ";
       $v.="
        </script>
     </div>
    ";
      return $v;
  }

  /**
   * @version 0.1
   * @access public static
   * @internal Cria componente de períodos na tela.
   * @param $at: array com atributos do componente geral (Label,Name indentificador)
   * @param $p1: array com 2 posições| 1ª(input) dados do primeiro input como (name,id,placeholder) | 2ª(js) dados do objeto javascript
   * @param $p2: array com 2 posições| 1ª(input) dados do segundo input como (name,id,placeholder) | 2ª(js) dados do objeto javascript
   * @author José Henrique Gregório <henriquegreg45@gmail.com>
   * @return String Html do componente de range (data/hora)
   * */
  static function datetimerange($at=[],$p1=["name"=>"data_ini"],$p2=[["name"=>"data_fim"]]){
      $at['id']=(!empty($at['id']))?$at['id']:"periodo_exemplo";
      $at['label']=(!empty($at['label']))?$at['label']:"Label Padrão";
      $at['name']=(!empty($at['name']))?$at['name']:"date_time_name";
      $at['date']=(!empty($at['date']))?$at['date']:["format"=>"DD/MM/YYYY"];
      $hidden=(isset($at['hidden']) && $at['hidden'])?"hide":"";
      $date=$at['date'];
      $at = (Object)$at;
      $p1 = (Object)$p1;
      $p2 = (Object)$p2;
      $format =$date['format'];
      $icon = (in_array($format,['HH','HH:mm','HH:mm:ss']))?"glyphicon-time":"glyphicon-calendar";
      $date['locale']='pt-br';
      $json = json_encode($date);

       $v= "
     <div class='form-group {$hidden}' data-form-group='{$at->name}'>
         <label for='{$at->id}' class='control-label col-md-3 col-sm-3 col-xs-12'>{$at->label}</label>
         <div class='col-md-8 col-xs-12'>
           <div class='flex'>
               <div  class='input-group col-md-5 date' data-picker='picker_{$p1->name}'>     
                   <input data-picker='picker_{$p1->name}' ".SELF::createStringAttrs($p1,['type','id'])." data-type='datetime' data-format='{$format}' type='text' class='form-control '/>
                   <span class=\"input-group-addon\">
                        <span class=\"glyphicon $icon\"></span>
                   </span>
               </div>
               <span style='margin:7px 10px 0 10px;'><strong>Até</strong></span>
               <div class='input-group col-md-5 date' data-picker='picker_{$p2->name}'> 
                   <input data-picker='picker_{$p2->name}' ".SELF::createStringAttrs($p2,['type','id'])." data-type='datetime' data-format='{$format}'  type='text' class='form-control '/>
                   <span class=\"input-group-addon\">
                        <span class=\"glyphicon $icon\"></span>
                   </span>
               </div>
           </div>
         </div>
       
          <script>
                $('[data-picker=picker_{$p1->name}]').datetimepicker({$json});             
                $('[data-picker=picker_{$p2->name}]').datetimepicker({$json});            
        </script>
     </div>
        ";

      return $v;
  }

  static function file($at=[]){
   return SELF::gFile('file',$at);
  }

  static function image($at=[]){
   return  SELF::gFile('image',$at);
  }

  static function buttons($bts=[]){
    $v=[];
      foreach($bts as $keyBt => $bt){
        $hidden=(isset($bt['hidden']) && $bt['hidden'])?"hide":"";
        $bt = (Object)$bt;
        if($keyBt=="SAVE"){
          $v[$keyBt] = " <button type='submit' data-bt='save' ".SELF::createStringAttrs($bt,['label','type'])."  class='btn btn-success {$hidden}'>".(($bt->label)?$bt->label:'Salvar')."</button>";
        }elseif($keyBt=="RESET"){
          $v[$keyBt] = " <button type='button' role='clearForm' ".SELF::createStringAttrs($bt,['label','type'])."  class='btn btn-primary {$hidden}'>".(($bt->label)?$bt->label:'Resetar')."</button>";
        }else{
          $v[] = " <button type='button' ".SELF::createStringAttrs($bt,['label','type'])."  class='btn btn-danger {$hidden}'>".(($bt->label)?$bt->label:'Padrão')."</button>";
        }
    }
    $v = join("",$v);
    return " <div class='ln_solid'></div>
             <div class='form-group'>
             <div class=' btn-form-group col-md-9 col-sm-9 col-xs-12 col-md-offset-3'>
             {$v}
             </div>
           </div>
           ";
  }

  static function modal($id, $at=["size"=>"normal"]){
      $at['size']=(!empty($at['size']))?$at['size']:"normal";
      $at['title']=(!empty($at['title']))?$at['title']:"";
      $at= (Object)$at;

      switch ($at->size){
          case "normal":
              $size = "modal-md";
              break;
          case "small":
              $size = "modal-sm";
              break;
          case "large":
              $size = "modal-lg";
              break;
          case "extra_large":
              $size = "modal-xlg";
              break;
      }
      $v= "
      
        
        <!-- Modal -->
        <div class=\"modal fade\" id=\"{$id}\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"{$id}Title\" aria-hidden=\"true\">
          <div class=\"modal-dialog {$size} modal-dialog-centered\" role=\"document\">
            <div class=\"modal-content\">
              <div class=\"modal-header\">
                <h5 class=\"modal-title\" id=\"{$id}Title\">{$at->title}</h5>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                  <span aria-hidden=\"true\">&times;</span>
                </button>
              </div>
              <div class=\"modal-body\">";
      return $v;
  }

  static function endModal($btns=[["label"=>"Cancelar","class"=>"btn btn-default"],["label"=>"Salvar","class"=>"btn btn-primary"]]){
      $v ="";
      $v.="
      </div>
      <div class='modal-footer'>
      ";
              if(!empty($btns)){
              foreach ($btns as $btn){
                  $btn = (object)$btn;
                  if(!empty($btn->label)){
                      $v.="<button type='button' ".SELF::createStringAttrs($btn,['label']).(($btn->label=='Cancelar')?" data-dismiss='modal'":'')." >{$btn->label}</button>";
                  }else {
                        $v.="<pre>
                                  Informe o botão no formato: [ 
                                                              '<b class='key'>title</b>'=><i class='value'>'Seu título'</i>,
                                                              '<b class='key'>attrs</b>'=>['<b class='key'>class</b>'=><i class='value'>'btn btn-primary'</i>, ...] 
                                                              ]</pre>";
                  }

              }

            }
              $v.="
              </div>
            </div>
          </div>
        </div>";

      return $v;
  }

  static function openModal($id,$at=[]){
      $at['label']=(!empty($at['label']))?$at['label']:"Abrir Modal";
      $at= (Object)$at;
    $v= "
      <!-- Button trigger modal -->
        <button type='button' class='btn btn - primary' data-toggle='modal' data-target='#{$id}'>
          {$at->label}
      </button >";
    return $v;
  }

  private static function createStringAttrs($array=[],$block=[]){
    $block['label']="";
    $atttrs = "";
      foreach ($array as $key => $value) {
        if(!in_array($key,$block))
          $atttrs.= " {$key}='{$value}' ";
      }
    return $atttrs;
  }

  private static function gFile($type,$at=[]){
    $at = (Object)$at;
    $accept = '.'.join(', .',$at->accept);
   $v=" <div class='form-group'>
   <label  class='control-label col-md-3 col-sm-3 col-xs-12'>
    <label for='{$at->name}'>{$at->label} </label>";
      if($type=='image'){
      $v .="<br/><small> ".$at->dimensions[0]."px X ".$at->dimensions[1]."px</small>";
        $asset = asset('public/assets_zp/img/no-image.png');
        $clsFile = "";
      }else if($type=='file'){
        $v .="<small>Extensões permitidas</small>";
        $asset = asset('public/assets_zp/img/no-doc.png');
        $clsFile = "file";
      }
      $v .=" <br>
      <small>({$accept})</small>
      </label>
    
    <div class='thumbnail $clsFile'>
      <div  class='image view view-first'>
       <div data-lightbox='{$at->name}' >
        <a href='".$asset."'>
            <img data-file-tag='{$at->name}' style='width: 100%; display: block;' src='".$asset."' alt='{$at->label}'/>
        </a>
        </div>
        <input type='file' data-type='{$type}' name='{$at->name}' id='file-{$at->name}' data-size='{$at->size}' accept='{$accept}' data-required-name='' style='display:none' />
        <div class='mask'>
          <p   file-title='{$at->name}'>".(($type=='image')?'Sem Imagem':'Sem arquivo')."</p>
          <div class='tools tools-bottom'>";
            if($type=='image'){
             $v .= "<a  style='display:none' class='ef-link' data-file-view='{$at->name}' ><i data-toggle='tooltip' data-placement='bottom' title='Visualizar' class='fas fa-eye'></i></a>
                    <a class='ef-link' data-file-edit='{$at->name}' ><i data-toggle='tooltip' data-placement='bottom' title='Editar' class='fas fa-pencil-alt'></i></a>";
            }else if($type=='file'){
            $v .=  "<a class='ef-link' style='display:none' data-file-download='{$at->name}'><i data-toggle='tooltip' data-placement='bottom' title='Baixar' class='fas fa-download'></i></a>
                    <a class='ef-link'><i data-toggle='tooltip' data-file-edit='{$at->name}' data-placement='bottom' title='Selecionar' class='fas fa-upload'></i></a>";
            }
            $v .= "<a class='ef-link' style='display:none;' data-file-delete='{$at->name}' >
                   <i data-toggle='tooltip' data-placement='bottom' title='Excluir' role='excluir' style='display:none;' class='fas fa-times'></i>
                   <i data-toggle='tooltip' data-placement='bottom' title='Desfazer' role='desfazer' class='fas fa-undo'></i>
                  </a>
          </div>
        </div>
        </div>
        ";
        if($type=='file'){
          $v.= "<div data-file-infoname='{$at->name}' class='file-name-small'>Sem arquivo</div>";
        }
       $v.= "
       
    </div>
  </div>";

  return $v;
  }

  }