<?php 

namespace App\Services\Sys;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use App\Entities\Sys\SysLogAcao;
use Illuminate\Support\Facades\DB;
use Auth;
use Request;

class SysService {
  
  private $repo;
  const EXEC_DEFAULT = 0;
  const EXEC_BEFORE = 1;
  const EXEC_AFTER = 2;
  
  protected function setRepo($repo){
    $this->repo=$repo;
  }

  public function getRepo(){
    return $this->repo;
  }
  /**
   * @internal Retorna Campos de uma determinada entidade eliminando os campos não necessários ao formulário;
   * @param $repo, refere-se ao repository presente em cada Service
   * @param $data, refere-se aos dados vindo da request; Nesse caso vem apenas o id da entidade.
   */
  public function loadForm($data){
    $query =  $this->repo->where('id',$data['id'])->where('status','<>','EXC')->get();
    if(count($query)){
      $item =$query[0];
      unset($item['updated_at']);
      unset($item['created_at']);
      return $item;
    } 
    return [];
  } 

  /**
   * @internal 
   */
  public function createRel($param){
    // [""]
    return $param;
  }

  public function create($request){
    $data =$request->all();
    $data = $this->createRel($data);
    $order = in_array('ordem',$this->repo->getFillable());
    unset($data["id"]);
    $data['status']= ($data['status'] == "SIM") ? "SIM":"NÃO";
    $this->checkNull($data);
    $this->do_upload($request, $data);
    if($order){$data['ordem']=1;}
    $r = $this->repo->create($data);
    $this->registerAction(['acao'=>'CREATE','id_registro'=>$r['id']]);
    if($order){$this->new_order();}
    return ['status'=>true,'data'=>$r];
  }

  public function update($request){
    $data=$request->all();
    return $this->do_update($request,$data);
  }

  public function updateStatus($ids,$status){
    if(count($ids)){
      $status = ($status=="SIM")?"SIM":'NÃO';
      foreach ($ids as $id) {
        $this->registerAction(['acao'=>'UPDATE','id_registro'=>$id]);
        $this->repo->where("id",$id)->update(['status'=>$status]);
      }
      return ['status'=>true];
    }
   return ['status'=>false];
  }

  /**
   * @internal "Caso $fValue e $fLabel sejam apenas String
   *           Retorna estrutura em array pronta pra um select simples
   *           Caso $fLabel for um array, então ele retornará o resultado puro da query"
   * @param String $fValue            
   * @param String|Array $fLabel            
   * @return Array
   */
  public function getSelectData($fValue, $fLabel,$con=[[]],$modOrder='ASC'){
    $fields=(is_array($fLabel))?$fLabel:[$fLabel];
    $fields[]=$fValue;
    $query = $this->repo->where("status","=",'SIM')->orderBy($fields[0],$modOrder);
    foreach ($con as $c) {
      if(count($c)==2){
        $query->where($c[0],$c[1]);
      }elseif(count($c)==3){
        $query->where($c[0],$c[1],$c[2]);
      }
    }
    $query = $query->get($fields); 
    $res=[];
    if(count($fLabel)==1){
     foreach($query as $v){
        $res[$v[$fValue]] = $v[$fLabel[0]];
     }
    }else{
      $res=$query;
    }
    return $res;    
  }

  public function new_order(){
   $query = $this->repo->where("status",'<>','EXC')->orderBy('ordem', 'ASC')->orderBy('id', 'DESC');
   $ordem=1;
   foreach($query->get() as $item){
    $this->repo->where('id',$item['id'])->update(['ordem'=>$ordem++]);
   }
  }

  public function reorderGrid($data){
    $displayStart = (int)$data['displayStart'];
    if(is_int($displayStart) && $displayStart > 0){  
      $ordem = $displayStart;
      foreach ($data['orderList'] as $id) {
        $this->repo->where("id",$id)->update(['ordem'=>$ordem++]);
      }
      return ['status'=>true];
    }else{
      return ['status'=>false];
    }
  }

  public function delete($data){
    if(count($data['ids'])){
      foreach ($data['ids'] as $id) {
        $this->repo->where('id',$id)->update(['status'=>'EXC']);
      }
      if(in_array('ordem',$this->repo->getFillable())){
       $this->new_order();
      }
      return ['status'=>true];
    }
    return ['status'=>false];
  }

  public function deleteFile($data){
    $this->registerAction(['acao'=>'DELETE','id_registro'=>$data['id']]);
   return $this->dFile($data);
  }
  
  public function dFile($data,$options=['dir'=>'']){
    $files_path = (!empty($options['dir']))?'arqConteudo/'.$options['dir']."/":files_path();
    $ob = $this->repo->where('status','SIM');
 
    foreach($data as $k => $v){
      if($k!='name'){
        $ob->where($k,$v);
      }
    }
    $item = $ob->get([$data['name']]);
    if(count($item)){
      $file = $item[0][$data['name']];
      if($file){
      //  Storage::delete("public/".$files_path.$file);
      //  Storage::delete("public/".$files_path.'thumb_'.$file);
       Storage::delete($files_path.$file);
       Storage::delete($files_path.'thumb_'.$file);
       $ob->update([$data['name']=>'']);
      }
       return ['status'=>true];
    }
  }

  public function newPassword($id,$pass){
    $user = $this->repo->where('id',$id);
    if(count($user->get())==1){
      if($user->update(['password'=>bCrypt($pass)])){
        return true;
      }
    }  
    return false;
  }

  public function nameFile($name){
     $name = preg_replace("/[^a-zA-Z0-9-.]/", "-", strtr(utf8_decode(trim($name)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"), "aaaaeeiooouuncAAAAEEIOOOUUNC-"));
     $name = strtolower($name);
     $i = strlen($name);
     while ($i > 0){
      $name = str_replace('--', '-', $name, $i);
     }
     if (substr($name, -1) == '-'){
     $name = substr($name, 0, -1);
     }
     return $name;
  }

  protected function do_upload($request, &$data,$options=['dir'=>'']){
    $uploads =[];
    $files_path = (!empty($options['dir']))?'arqConteudo/'.$options['dir']."/":files_path();
    
    //$dir=''; //'public/'.$files_path;
    
    // shell_exec("chmod 777 -R storage/app/public");
   // if(Storage::directories('storage/app/'.$dir)){
    //  Storage::makeDirectory('storage/app/'.$dir);
    //  }
    foreach($_FILES as $field => $f){
      if($f['tmp_name'] != ""){     
        if ($request->hasFile($field) && $request->file($field)->isValid() &&array_key_exists($field, $data)) {
          if(!empty($data['id'])){
              $this->dFile(['id'=>$data['id'],'name'=>$field],$options);
          }
          // Faz o upload: storage/app/public/arqConteudo/arqXXXXX
          $fileName =  $f['name'];
          $exp=explode('.',$fileName);
          $ext= '.'.strtolower($exp[count($exp)-1]);
          $fileName =$this->nameFile($fileName);
          $newName = str_replace($ext,'',$fileName);
          $fileName = $newName.$ext;

          $a=1;
           while(true){
            if(!file_exists(public_path('storage/'.$files_path.$fileName))){
              break;
            }
            $fileName = ($a++).'-'.$newName.$ext;
           }
           $uploads[$field] = $fileName;
        }  
      }
    }
    
    if(count($uploads)){
    // shell_exec("chmod 777 -R storage/app/public");
    foreach($uploads as $field=>$name){
      if($request->file($field)->storeAs($files_path,$name)){
        //Criando o thumb caso seja imagem
        if(preg_match("/^(image)/",$request->file($field)->getClientMimeType())){
            // open an image file
          $imgThumb = Image::make($request->file($field)->getRealPath());
          $imgThumb->resize(160, 160);
          // $imgThumb->insert('public/watermark.png');
          // $imgThumb->save('storage/app/'.$dir."thumb_".$name);
          $imgThumb->save('public/storage/'.$files_path."thumb_".$name);
        }
        $data[$field]= $name;
      }
    }
    // shell_exec("chmod 777 -R storage/app/public");
   }
    return $data;
  }

  protected function checkNull(&$data){
   function isNull(&$d){
     foreach ($d as &$val) {
        if($val==="null"){$val='';}
     }
   }
   isNull($data);
  }


  public function getDownload($file,$user) {
    $dir =files_path();
    // if(Storage::exists('public/storage/'.$dir.$file)){
    if(Storage::exists($dir.$file)){
      // return Storage::download('public/storage/'.$dir.$file);
      return Storage::download($dir.$file);
      $status ="[SUCESSO] Download realizado";
    }else{
      $status ="[ERRO] Arquivo não encontrado";
    }
    Log::stack(['zp'])->info("TABLE: ".$this->repo->getTable()." FILE: {$file}  USER: {$user['nome']}  STATUS: {$status}");
  }

  public function registerAction($data){
      $user = Auth::guard('zp')->user();
      $method = Request::method();
      function getSection($uri){
        $r = DB::select("SELECT * FROM sys_secoes WHERE status='SIM' AND uri='{$uri}' ");
        return (count($r))?(array)$r[0]:[];
      }
      $register =[
        'id_usuario'=>(!empty($data['id_usuario']))?$data['id_usuario']:$user['id'],
        'tipo'=>(!empty($data['tipo']))?$data['tipo']:'SECAO',
        'id_registro'=>$data['id_registro'],
        'acao'=>$data['acao'],
        'texto'=>(!empty($data['texto']))?$data['texto']:'',
      ];
      if(empty($data['id_secao'])){
        $i=1;
        while(true){
            if(empty(Request::segments()[$i])){break;}
            $segments[]=Request::segments()[$i++];
        }
      $section=[];
      if($method=='GET'){
        $section = getSection(implode('/',$segments));
      }elseif($method=='POST'){
          $section = (!empty($segments[2]))? getSection($segments[0].'/'.$segments[1].'/'.$segments[2]):[];
          $section = (!empty($segments[1])&&!count($section))?getSection($segments[0].'/'.$segments[1]):$section ;
          $section = (!count($section))?getSection($segments[0]):$section ; 
      }
      if(count($section)){
        $register['id_secao']=$section['id'];
      }
      }
      SysLogAcao::create($register);
  }

  protected function do_update($request,$data){
   
    $id = $request->id;
    $data['status']= ($data['status'] == "SIM") ? "SIM":"NÃO";
    $this->checkNull($data);
    $this->do_upload($request, $data);
    unset($data['id']);
    $r  = $this->repo->where('id',$id)->update($data);
    $this->registerAction(['acao'=>'UPDATE','id_registro'=>$id]);
    return ['status'=>true,'data'=>$r];
  }

  public function createOneToMany($request,$param){
    
  }

  public function updateOneToOne($request,$repositories=[],$exec){
    $data=$request->all();
    if($exec == self::EXEC_BEFORE){
      // Percorre todos os possíveis relacionamentos
      foreach($repositories as $prefix => $repo){
        $repoData = [];
        //Percorrendo todos os campos e filtrando pelo prefixo
        foreach($data as $key => $value){
          if(substr($key,0,strlen($prefix)) == $prefix){
            $field= str_replace($prefix,"",$key); // Nomes das colunas da entidade de relacionamento.
            $repoData[$field] = $value;
            unset($data[$key]);//Removendo do array principal.
          }
        }
        //Salvando os dados na tabela de relacionamento
        $repoData['id_rel'] = $data['id'];
        $repo->update($repoData);
        $this->do_update($request,$data);
      }
      exit;
      //return $this->do_update($request);
    }else if($exec == self::EXEC_AFTER){
      //return $this->do_update($request);
    }else if($exec == self::EXEC_DEFAULT){
      //return $this->do_update($request);
    }
  }

}