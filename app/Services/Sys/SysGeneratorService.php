<?php 
namespace App\Services\Sys;

use App\ZP\GridSelectBuilder;
use App\Entities\Sys\SysGenerator;
use App\ZP\CodeGenerator;
use App\Entities\Sys\SysSecao;
use App\Entities\Sys\SysPermissao;
use Illuminate\Support\Facades\DB;


class SysGeneratorService{
  
  protected $repo;
  protected $g;
  protected $secao_repo;
  protected $perm_repo;
 
  function __construct(SysGenerator $repo, CodeGenerator $g,SysSecao $secao_repo,SysPermissao $perm_repo){ 
   $this->repo = $repo;
   $this->g = $g;
   $this->secao_repo = $secao_repo;
   $this->perm_repo = $perm_repo;
  }

  public function loadGrid($data){
    $baseSql = [
      "FROM" => ["t1"=>$this->repo->getTable()],
      "WHERE" => " t1.status <> 'EXC'",
    ];
    return $dados = (array) GridSelectBuilder::buildTable($data, $baseSql, $functions=[], true);
  }

  public function get($conditions=[]){
    $query = $this->repo->where("status",'SIM');
    foreach($conditions as $c){
      if(count($c)==3){$query->where([$c[0],$c[1],$c[2]]);}
      elseif(count($c)==2){$query->where([$c[0],$c[1]]);}
    }
    return $query->get();
  }
  
  public function getFields($idEntity){
    $rs = DB::select("SELECT table_name FROM sys_generators WHERE id_secao = {$idEntity}");
    $table= $rs[0]->table_name;
    $where = "WHERE Field NOT IN ('created_at', 'updated_at','status','ordem')";
    return $return  = DB::select("SHOW COLUMNS FROM `$table` $where");
  }

  #1
  private function createTable($data){
    // $tableName="api_".camelCaseToUnderline($data['idGroup']);
   
    $tableName=$data['tableName'];
    $c=[];
    $db=env('DB_DATABASE', '');
    $sql = "CREATE TABLE IF NOT EXISTS `{$db}`.`{$tableName}` 
           ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
    foreach ($data['fields'] as  $f) {
     $col = $f['col'];
     $el='';
     
      $el .=" {$col['type']}".((($col['type']=='VARCHAR' || $col['type']=='DECIMAL')&&$col['length']!="")?"({$col['length']})":' ');
     
      $el .= ($col['notNull'] == "true")? ' NOT NULL':' DEFAULT NULL';
      // $t= " VARCHAR ", length: 255, notNull:false,comment:""
      if($f['fieldType']!='checkbox'){
       $com = ($col['comment'])? " COMMENT '{$col['comment']}'":'';
       $c[] = " `{$f['name']}` {$el} {$com}";
      }else{
       foreach ($f['items'] as $it) {
         $c[] = " `{$it['name']}` {$el}";
       }
     }
    }
    $sql .= (count($c))?join(' , ',$c):'';
    
    if($data['hasOrder'] == "true"){
      $sql .= ",`ordem` INT NOT NULL";
    }
    #Criando campos obrigatórios do laravel
    $sql .= ",`created_at` datetime NOT NULL,`updated_at` datetime NOT NULL";
    
    if($data['hasStatus'] == "true"){
      $sql .= ",`status` VARCHAR(5) NOT NULL";
    }
    $sql .= ")  ENGINE = InnoDB;";
    DB::select($sql);
    return $tableName;
  }

  public function create($data){
   $idGroup=$data['idGroup'];
   $data['isEntity'] = true; // Forçando para gerar Entity
   $data['isSection'] = true; // Forçando para gerar Section
   $data['typeSection'] = "GRID"; // Forçando para gerar tipo GRID
   $check = $this->repo->where('id_group_file',$idGroup)->where('status','SIM')->get();
   if(count($check)){
     return ['status'=>false,'msg'=>'Já existe um registo com o nome '.$idGroup];
   }
   /////
   if($data['isEntity']){
    $fs=[];
    foreach($data['fields'] as $val){
      if($val['fieldType'] == "checkbox"){
        foreach($val['items'] as $f){
          $fs[] = $f['name'];
        }
      }else{
        $fs[]=$val['name'];
      }
     
    }
    $tableName = $this->createTable($data);

    $nameEntity = $this->g->gEntities($idGroup,$fs,$tableName);
    if(is_array($nameEntity)){// Se for array é porque deu erro!
      return $nameEntity;
    }
   }
   ///
    if($data['isSection']){
      $nameService = $this->g->gService($data);
    if(is_array($nameService)){// Se for array é porque deu erro!
      return $nameService;
    }
    $nameView=$this->g->gView($data);
    if(is_array($nameView)){ // Se for array é porque deu erro!
      return $nameView;
    }
    $nameController = $this->g->gController($data);
    if(is_array($nameController)){// Se for array é porque deu erro!
      return $nameController;
    } 
    
   }
   if($nameController){
      $section = [
        'id_menu'=>$data['idMenu'],
        'id_submenu'=>($data['idSubmenu'] == null)?"":$data['idSubmenu'],
        'tipo'=>$data['typeSection'],
        'nome'=>$data['sectionName'],
        'controller'=>$nameController,
        'ordem'=>9,
        'status'=>'SIM',
      ];
      $rSec=$this->secao_repo->create($section);
      $lastId = $rSec['id'];
      $json = json_encode($data);
      $this->repo->create(["id_group_file"=>$data['idGroup'], "table_name"=>$data['tableName'], "id_secao"=>$lastId,"texto"=>$json,"status"=>"SIM"]);
      $this->g->gRouters();
      $this->perm_repo->create(["id_perfil"=>1,"id_secao"=>$lastId,"nivel"=>"A"]);
      return ["status"=>true,"msg"=>"Seção ".$data['sectionName']." criada com sucesso!"];
   }
   
  }
  
}