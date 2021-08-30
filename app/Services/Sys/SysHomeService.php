<?php

namespace App\Services\Sys;
use Illuminate\Support\Facades\DB;
use App\Services\Sys\SysCoreService;
use App\Entities\Sys\SysLogAcesso;
use App\Entities\Sys\SysSecao;
use App\Entities\Sys\SysLogAcao;

class SysHomeService {

 private $core_service;
 private $log_acao_repo;

 public function __construct( SysCoreService $core_service,SysLogAcesso $log_acesso_repo,SysLogAcao $log_acao_repo,SysSecao $secao_repo){
  $this->core_service = $core_service;
  $this->log_acesso_repo = $log_acesso_repo;
  $this->log_acao_repo = $log_acao_repo;
  $this->secao_repo = $secao_repo;
}

  public function homeBox(){
    $boxs=[
      
    ];

    return $boxs;
  }
  public function accesses(){
  $user=$this->core_service->getUserLogged();
  $query = $this->log_acesso_repo->where('id_usuario',$user['id'])->orderBy('created_at','ASC')->get();
  if(count($query)){
      $days = [];
       foreach ($query as $value) {
        $date = substr($value->inicio,0,4).substr($value->inicio,5,2).substr($value->inicio,8,2);
        if(!empty($days[$date])){
         $days[$date]['accesses']+=1;
        }elseif($date){
         $days[$date] = ['year'=>substr($value->inicio,0,4),'month'=>substr($value->inicio,5,2),'day'=>substr($value->inicio,8,2),'accesses'=>1];
        }
       }
       return $days;
     }
     return [];
  }

  public function charActions(){
  $user=$this->core_service->getUserLogged();
  $tC = $this->log_acao_repo->where('id_usuario',$user['id'])->where('acao','CREATE')->count();
  $tU = $this->log_acao_repo->where('id_usuario',$user['id'])->where('acao','UPDATE')->count();
  $tD = $this->log_acao_repo->where('id_usuario',$user['id'])->where('acao','DELETE')->count();
  $t = $tC+$tU+$tD;
  return [
    ['title'=>'Criados','per'=>percentage($tC,$t),'count'=>$tC],
    ['title'=>'Alterados','per'=>percentage($tU,$t),'count'=>$tU],
    ['title'=>'Excluídos','per'=>percentage($tD,$t),'count'=>$tD],
   ];
  }

  public  function  getMyAccesses(){
   $perfil=$this->core_service->getDataPerfil();
   $return =['more_accesses'=>[],'recents'=>[]];
   $urlSys =$this->core_service->getRouteData()['url_sys'].'/';
   function queryAccess($t){
    global $perfil;
      $permJoin='';
      $permOn='';
      $sql='';
      if($perfil['tipo']!='adm_ADMIN'){
       $permJoin=" JOIN sys_permissoes AS t5 ";
       $permOn="AND t1.id=t5.id_secao AND t5.nivel IN('A','B','C') ";
      }
      if($t=='more'){
       $sql=' ORDER BY t4.acessos DESC LIMIT 3 ';
      }elseif($t=='recents'){
       $sql=' ORDER BY t4.updated_at DESC, t4.id DESC LIMIT 2 ';
      }
      return  DB::select("SELECT t1.uri,t1.nome,t2.nome AS menu, t3.nome AS submenu, t4.updated_at AS utAcesso
        FROM sys_secoes AS t1 JOIN sys_menus AS t2 JOIN sys_secoes_acessos AS t4 {$permJoin} 
        ON t1.id_menu=t2.id AND t4.id_secao=t1.id {$permOn}
        LEFT JOIN sys_submenus AS t3 ON t1.id_submenu=t3.id AND t3.status='SIM' 
        WHERE t1.status='SIM' AND t2.status='SIM' 
        GROUP BY t1.uri,t1.nome,t2.nome, t3.nome, t4.updated_at {$sql}");
    }
   ///////
   $MoreAccesses = queryAccess('more');
   if(count($MoreAccesses)){
     foreach($MoreAccesses as $val){
      $return['more_accesses'][] =['menu'=>$val->menu,'submenu'=>$val->submenu,'secao'=>$val->nome,'url'=> $urlSys.$val->uri,];
    }
   }
   $MoreAccesses = queryAccess('recents');
   if(count($MoreAccesses)){
     foreach($MoreAccesses as $val){
      $datePer= countTime(substr($val->utAcesso,0,4).substr($val->utAcesso,5,2).substr($val->utAcesso,8,2).substr($val->utAcesso,11,2).substr($val->utAcesso,14,2).substr($val->utAcesso,17,2));
      $return['recents'][] =['menu'=>$val->menu,'submenu'=>$val->submenu,'secao'=>$val->nome,'url'=> $urlSys.$val->uri,'time'=>$datePer];
    }
   }
    return $return;
   }

   public  function  getLastActions(){
    $return=[];
    $mondth =['01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Abr', '05'=>'Mai', '06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Set','10'=>'Out',  '11'=>'Nov','12'=>'Dez',];
    $user=$this->core_service->getUserLogged();
     $query= $this->log_acao_repo->where('id_usuario',$user['id'])->orderBy('created_at','DESC')->limit(15)->get();
     foreach ($query as $action) {
      $section= $this->secao_repo->where('id',$action['id_secao'])->get();
      if(count($section)){
        $section=$section[0];
        $return[] =[
          'secao'=>$section['nome'],
          'url'=>$this->core_service->getRouteData()['url_sys'].'/'.$section['uri'],
          'mes'=> $mondth[substr($action['created_at'],5,2)],
          'dia'=>substr($action['created_at'],8,2),
          'hora'=> substr($action['created_at'],11,2).':'.substr($action['created_at'],14,2).':'.substr($action['created_at'],17,2),
          'acao'=>$action['acao'],
        ];
      }
    }
     return $return;
   }

}
?>