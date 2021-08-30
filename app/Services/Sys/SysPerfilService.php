<?php

namespace App\Services\Sys;

use App\ZP\GridSelectBuilder;
use App\Entities\Sys\SysPerfil;
use App\Services\Sys\SysCoreService;

class SysPerfilService extends SysService{

  private $repo;
  private $core_service;

  public function __construct(SysPerfil $repo,SysCoreService $core_service){
    $this->repo = $repo;
    $this->core_service = $core_service;
    parent::setRepo($repo); // Setando o repositorio padrao para o Service.
  }

  /**Método é Implementado por sessão, 
   * Não pode ser reaproveitado com Herança 
   * */
  public function loadGrid($data){
    $functions = [
      //'field'=>['function'],
    ];
    $baseSql = [
      "FROM_BODY" => $this->repo->getTable(),
      "WHERE" => " tipo<>'adm_ADMIN' AND status <> 'EXC'",
    ];
    return (array)GridSelectBuilder::buildTable($data,$baseSql,$functions,true);
  }

  public function getPerfis(){
    $return=[];
    $perfil =$this->core_service->getDataPerfil();
    if($perfil['tipo']!='adm_ADMIN'){
      $perfis = $this->repo->where('tipo','<>','adm_ADMIN')->where('status','SIM')->orderBy('nome','ASC')->get();
    }else{
      $perfis = $this->repo->where('status','SIM')->orderBy('nome','ASC')->get();
    }
    foreach ($perfis as $perfil) {
      $return[$perfil['id']] =$perfil['nome'];
    }
    return $return;
  }

}
?>