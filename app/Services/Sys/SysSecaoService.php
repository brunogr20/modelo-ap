<?php

namespace App\Services\Sys;

use App\Entities\Sys\SysSecao;
use Illuminate\Support\Facades\DB;

class SysSecaoService extends SysService{

 private $repo;
 private $core_service;

 public function __construct(SysCoreService $core_service,SysSecao $repo){
  $this->repo = $repo;
  $this->core_service = $core_service;
  parent::setRepo($repo); // Setando o repositorio padrao para o Service.
 }


 public function getSections(){
  $perfil =$this->core_service->getDataPerfil();
  $sql = ($perfil['tipo']!='adm_ADMIN')?" t2.id<>1 AND t2.id<>2  AND ":'';
  $ret=[];
  $query =DB::select("SELECT t1.* FROM sys_secoes AS t1 JOIN sys_menus t2 ON t1.id_menu=t2.id
                    WHERE {$sql} t1.status<>'EXC' AND t2.status<>'EXC' ORDER BY t2.ordem ASC,t1.ordem ASC");
  foreach($query AS $v){
    $ret[$v->id]=$v->nome;
  }
 return $ret;
 }

}
?>