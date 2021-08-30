<?php 

namespace App\Services\Sys;
use Illuminate\Support\Facades\DB;
use Auth;
use Request;
use App\Entities\Sys\SysMenu;
use App\Entities\Sys\SysSubmenu;
use App\Entities\Sys\SysPermissao;
use App\Entities\Sys\SysSecao;
use App\Entities\Sys\SysLogAcesso;
use App\Entities\Sys\SysLogAcao;
use App\Entities\Sys\SysPerfil;

class SysCoreService {

 private $menu_repo;
 private $submenu_repo;
 private $permissao_repo;
 private $log_acesso_repo;
 private $log_acao_repo;
 private $secao_repo;
 private $perfil_repo;

  public function __construct(SysMenu $menu_repo,SysSubmenu $submenu_repo,SysPermissao $permissao_repo,SysLogAcesso $log_acesso_repo,SysLogAcao $log_acao_repo,SysSecao $secao_repo,SysPerfil $perfil_repo){
   $this->menu_repo = $menu_repo;
   $this->submenu_repo = $submenu_repo;
   $this->permissao_repo = $permissao_repo;
   $this->log_acesso_repo = $log_acesso_repo;
   $this->log_acao_repo = $log_acao_repo;
   $this->secao_repo = $secao_repo;
   $this->perfil_repo = $perfil_repo;
  }

  public function getUserLogged(){
    $data =  Auth::guard('zp')->user();
    $user = [
      'id'=>$data['id'],
      'id_perfil'=>$data['id_perfil'],
      'nome'=>$data['nome'],
      'email'=>$data['email'],
      'imagem'=>$data['imagem'],
    ];
    return $user;
  }

  public function getLastAcess(){
    $user= $this->getUserLogged();
    $acess = DB::select("SELECT * FROM sys_log_acessos WHERE id_usuario={$user['id']} ORDER BY id DESC LIMIT 2");
    if(count($acess)>1){
      $d = $acess[1]->created_at;
      $d = substr($d,0,4).substr($d,5,2).substr($d,8,2).substr($d,11,2).substr($d,14,2).substr($d,17,2);
      return ['data'=>retrieveDate($d),'hora'=>retrieveHour($d)];
    }
    return [];
  }
  
  public function getDataPerfil(){
    $user= $this->getUserLogged();
    $perfil = $this->perfil_repo->where('id', $user['id_perfil'])->where('status','SIM')->get();
    $perfil =$perfil[0];
   return ['id'=>$perfil['id'],'tipo'=>$perfil['tipo'],'nome'=>$perfil['nome']];
  }

  public  function  getSectionActive($uri=''){
   $segments=[];
   $section_static=[
     'meu-perfil'=>['id'=>'','menu'=>'','submenu'=>'','level'=>'','url'=>$this->getRouteData()['url_sys'].'/meu-perfil','secao'=>'Meu perfil']
    ];
   $i=1;
   while(true){
    if(empty(Request::segments()[$i])){break;}
    $segments[]=Request::segments()[$i++];
   }
   $uri = ($uri)?$uri:implode('/',$segments);
   if(!empty($section_static[$uri])){
   return $section_static[$uri];
   }else{
   $query =DB::select("SELECT t1.*,t2.nome AS menu, t3.nome AS submenu 
     FROM sys_secoes AS t1 JOIN sys_menus AS t2  ON t1.id_menu=t2.id"
   ." LEFT JOIN sys_submenus AS t3 ON t1.id_submenu=t3.id AND t3.status='SIM' "
   ." WHERE t1.uri='{$uri}' AND t1.status='SIM' AND t2.status='SIM'");
   
   if(count($query)){
     $query = $query[0];
     $level='';
     $perfil = $this->getDataPerfil();
     $permision=$this->permissao_repo->where('id_perfil',$perfil['id'])->where('id_secao',$query->id)->get();
     if(count($permision)){
      $level=$permision[0]['nivel'];
     }
     $r =[
       'id'=>$query->id,
       'menu'=>$query->menu,
       'submenu'=>$query->submenu,
       'secao'=>$query->nome,
       'url'=>$this->getRouteData()['url_sys'].'/'.$uri,
       'level'=>$level,
     ];
     return $r;
    }
   }
    return [];
  }

  
   public static function getRouteData(){
    $data = array("url_page"=>url()->current(),"type"=>"MENU");
    $data['section_url'] =  str_replace(url('/').'/zp/','',$data['url_page']);
    $data['url_context'] = url('/');
    $data['url_sys'] =url('').'/zp';
    $parts = explode("/",$data['section_url']);
    switch(count($parts)){   
        case 1:
            //ORIGEM
            $data['target']=$parts[0];
        break;
        case 2:
            //ORIGEM + ACAO
            $data['target']=$parts[0]; 
            $data['action']=$parts[1];
        break;
        case 3:
            //target + sub_target + action 
            $data['type'] = "SUB_MENU";
            $data['target']=$parts[0]; 
            $data['sub_target']=$parts[1]; 
            $data['action']=$parts[2];
        break;
    }
    return $data;
  }

  function  getMenuSection($gType,$perfil,$idMenu,$idSubmenu=''){
      $sql = ($idSubmenu)? " AND t1.id_submenu={$idSubmenu} ":" AND (t1.id_submenu='' OR t1.id_submenu=NULL) ";
      $sqlLavel =($gType=='menu')?" AND t2.nivel IN('A','B','C') " :'';
      $r =DB::select("SELECT t1.* FROM sys_secoes AS t1 JOIN sys_permissoes AS t2 "
      ." ON t1.id=t2.id_secao AND t2.id_perfil={$perfil['id']}"
      ." WHERE t1.status='SIM' AND t1.id_menu={$idMenu} {$sql} {$sqlLavel} "
      ." ORDER BY t1.ordem ASC");
      return $r;
  }
 public function getMenu($gType="menu"){//menu/permissions
  $return = [];
  $url=$this->getRouteData()['url_sys'].'/';
  $perfil= $this->getDataPerfil();
  $menus=[];
  if($perfil['tipo']!='adm_ADMIN'){
    $menus[$perfil['nome']] = $this->menu_repo->where('status','SIM')->whereIn('id',[2])->orderBy('ordem','ASC')->get();
  }elseif($perfil['tipo']=='adm_ADMIN'){
    $menus[$perfil['nome']] = $this->menu_repo->where('status','SIM')->whereIn('id', [1,2,4])->orderBy('ordem','ASC')->get();
  }
  $menus['GERAL'] = $this->menu_repo->where('status','SIM')->whereNotIn('id',[1,2,4])->orderBy('ordem','ASC')->get();
  foreach($menus as $kMenu => $vMenu){
      $sections = [];
      if(count($vMenu)){
        foreach($vMenu as $keyMenu => $menu) {
          $idMenu = $menu['id'];
          $secoes = $this->getMenuSection($gType,$perfil,$idMenu);
          $sections[$keyMenu]=['id'=>$menu['id'],'nome'=>$menu['nome'],'icone'=>$menu['icone'],'secoes'=>[],];
          if(count($secoes)){
            foreach ($secoes as $secao) {
              $sections[$keyMenu]['secoes'][] = ['id'=>$secao->id,'url'=>$url.$secao->uri,'nome'=>$secao->nome,'secoes'=>[],];
            } 
          }        
          $submenus = $this->submenu_repo->where('id_menu',$idMenu)->where('status','SIM')->orderBy('ordem','ASC')->get();
          if(count($submenus)){      
            $dSubmenu = [];
            foreach ($submenus as $submenu) {
              $secoes = $this->getMenuSection($gType,$perfil,$idMenu,$submenu['id']);
              if(count($secoes)){
                $dSubmenu[$submenu['id']] = ['id'=>$submenu['id'],'nome'=>$submenu['nome'],];
                foreach ($secoes as $secao) {
                  $dSubmenu[$submenu['id']]['secoes'][] =['id'=>$secao->id,'url'=>$url.$secao->uri,'nome'=>$secao->nome,];
                } 
              }
              ////
             if(count($dSubmenu)){
              $aux=[];
              if(!$submenu['id_posicao']){
                $aux[] = $dSubmenu;
                foreach ($sections[$keyMenu]['secoes'] as $secao) {
                  $aux[] = $secao;
                } 
              }else{
                foreach ($sections[$keyMenu]['secoes'] as $secao) {
                  $aux[] = $secao;
                  if(!empty($secao['id'])&&$secao['id']==$submenu['id_posicao']){
                    $aux[] =$dSubmenu;
                  }
                } 
              }
              if(count($aux)){
                $sections[$keyMenu]['secoes']=$aux;
              }
            }
           } 
          } 
      }
    }
    $return[$kMenu]=  $sections;
  }/// fim foreach
  return $return;
 }

}