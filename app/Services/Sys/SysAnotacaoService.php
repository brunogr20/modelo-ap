<?php
  
  namespace App\Services\Sys;
  use Illuminate\Support\Facades\DB;
  use App\Services\Sys\SysCoreService;
  use App\Entities\Sys\SysAnotacao;
  use App\ZP\Config;
  
  class SysAnotacaoService extends SysService{
    
    private $core_service;
    private $repo;
    private $config;
    
    public function __construct( SysCoreService $core_service,SysAnotacao $repo,Config $config){
      $this->core_service = $core_service;
      $this->repo = $repo;
      parent::setRepo($this->repo);
      $this->config = $config;
    }
    
    public function list($request){
      $data =$request->all();
      $user=$this->core_service->getUserLogged();
      $idSection=$this->getIdSection($request->headers->get('referer'));
      if($idSection&&$data['action']=='filter'){
        $query = DB::select("SELECT * FROM  sys_anotacoes 
        WHERE id_usuario='{$user['id']}' AND id_secao={$idSection} AND tipo_exibicao='SECAO' AND status='SIM' ORDER BY created_at DESC, nivel DESC");
      }else{
        $sql =($idSection)?" AND (id_secao={$idSection} OR tipo_exibicao='TODOS')":" AND tipo_exibicao='TODOS' ";
        $query = DB::select("SELECT * FROM  sys_anotacoes 
        WHERE id_usuario='{$user['id']}' {$sql}  AND status='SIM' ORDER BY created_at DESC, nivel DESC");
      }
      if(count($query)){
        $list=[];
        foreach ($query as $val) {
          $list[]=[
            'id'=>$val->id,
            'titulo'=>$val->titulo,
            'texto'=>$val->texto,
            'nivel'=>$val->nivel,
            'tipo_exibicao'=>$val->tipo_exibicao,
            'data'=>retrieveDatetime($val->created_at),
          ];
        }
        return ['status'=>true,'list'=>$list];
      }
      return ['status'=>false,'list'=>[]];
    }
    
    public function create($request){
      $data =$request->all();
      unset($data["id"]);
      $user=$this->core_service->getUserLogged();
      $data['id_secao'] = $this->getIdSection($request->headers->get('referer'));
      $data['id_usuario']= $user['id'];
      $data['status']= "SIM";
      $r = $this->repo->create($data);
      return ['status'=>true,'data'=>$r];
    }
    
    public function update($request){
      $data =$request->all();
      $id =$data['id'];
      unset($data['id']);
      $r  = $this->repo->where('id',$id)->update($data);
      return ['status'=>true,'data'=>$r];
    }
    
    private function getIdSection($url){
      $arUrl =  explode($this->config->getCMS().'/',$url);
      if(!empty($arUrl[1])){
        $section=$this->core_service->getSectionActive($arUrl[1]);
        if(!empty($section['id'])){
         return  $section['id'];
        }
      }
      return '';
    }
    
  }
  ?>