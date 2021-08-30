<?php

namespace App\Services\Sys;

use App\ZP\GridSelectBuilder;
use App\Entities\Sys\SysUsuario;
use App\Services\Sys\SysCoreService;
use Illuminate\Support\Facades\DB;

class SysUsuarioService extends SysService{

  private $core_service;
  private $repo;

  public function __construct(SysCoreService $core_service, SysUsuario $repo){
    $this->repo = $repo;
    $this->core_service = $core_service;
    parent::setRepo($repo); // Setando o repositorio padrao para o Service.
  }

  /**Método é Implementado por sessão, 
  *  Não pode ser reaproveitado com Herança 
  **/
  public function loadGrid($data){
    $functions = [
      //'field'=>['function'],
    ];
    $perfil =$this->core_service->getDataPerfil();
    $sql = ($perfil['tipo']!='adm_ADMIN')?" t2.tipo<>'adm_ADMIN'  AND ":'';
   $baseSql = [//Obs: o att SELECT  deve trazer o id da tabela principal
       "SELECT"=>["t1"=>['id','nome','email',"status"],"t2"=>["nome"=>"perfil"]],
       "FROM_BODY"=>$this->repo->getTable()." AS t1 INNER JOIN sys_perfis AS t2 ON t1.id_perfil = t2.id",
      "WHERE" => " $sql t1.status <> 'EXC' AND t2.status <> 'EXC'",
      "ORDER BY"=>"t1.nome ASC",
   ];
    return (array)GridSelectBuilder::buildTable($data,$baseSql,$functions,true);
  }
  
  public function userUpdate($request){
    $data =$request->all();
    $id = $request->id;
    $data['status']= ($data['status'] == "SIM") ? "SIM":"NÃO";
    $this->checkNull($data);
    $this->do_upload($request, $data);
    unset($data['id']);
    $r  = $this->repo->where('id',$id)->update(['imagem'=>$data['imagem']]);
    $this->registerAction(['acao'=>'UPDATE','id_registro'=>$id]);
    return ['status'=>true,'data'=>$r];
  }

  public function customTema($user,$data){
     $query = $this->repo->where('id',$user['id']);
     if(count($query->get())){
       $query->update(['tema'=>$data['tema']]);
       return ['status'=>true];
     }
     return ['status'=>false];
  }
  public function isUniqueEmail($email,$notId=''){
    $query = $this->repo->where('email',$email)->where('status','<>','EXC');
      if($notId){
        $query->where('id','<>',$notId);
     }
     if(count($query->get())){
      return false;
     }
     return true;
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

  public function getUsers(){
    $perfil =$this->core_service->getDataPerfil();
    $sql = ($perfil['tipo']!='adm_ADMIN')?" t2.tipo<>'adm_ADMIN'  AND ":'';
    $ret=[];
    $query =DB::select("SELECT t1.* FROM sys_usuarios AS t1 JOIN sys_perfis t2 ON t1.id_perfil=t2.id
                      WHERE {$sql} t1.status<>'EXC' AND t2.status<>'EXC' ORDER BY t1.nome");
    foreach($query AS $v){
      $ret[$v->id]=$v->nome;
    }
   return $ret;
  }
}
?>