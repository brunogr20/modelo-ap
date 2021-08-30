<?php

namespace App\Services\Sys;

use App\ZP\GridSelectBuilder;
use App\Entities\Sys\SysSubmenu;
use App\Entities\Sys\SysSecao;
use App\ZP\CodeGenerator;

class SysSubmenuService extends SysService{

  private $repo;
  private $secao_repo;

  public function __construct(SysSubmenu $repo,SysSecao $secao_repo,CodeGenerator $generator){
    $this->repo = $repo;
    $this->secao_repo = $secao_repo;
    $this->generator = $generator;
    parent::setRepo($repo); // Setando o repositorio padrao para o Service.
  }

  /**Método é Implementado por sessão, 
   * Não pode ser reaproveitado com Herança 
   * */
  public function loadGrid($data){
    $functions = [
      //'field'=>['function'],
    ];
    $baseSql = [//Obs: o att SELECT  deve trazer o id da tabela principal
        "SELECT"=>["t1"=>['id','nome',"status",'ordem'],"t2"=>["nome"=>"menu"]],
        "FROM_BODY" =>$this->repo->getTable()." AS t1 INNER JOIN sys_menus AS t2 ON t1.id_menu = t2.id",
        "WHERE" => " t1.status <> 'EXC' AND t2.status <> 'EXC'",
        "ORDER BY"=>" t1.ordem ASC",
    ];
    return (array)GridSelectBuilder::buildTable($data,$baseSql,$functions,true);
  }


  public function loadForm($data){
    $query =  $this->repo->where('id',$data['id'])->where('status','<>','EXC')->get();
    if(count($query)){
      $item =$query[0];
      $dSection=[];
      unset($item['updated_at']);
      unset($item['created_at']);
      
      $qSection =  $this->secao_repo->where('id_menu',$item['id_menu'])->where('id_submenu',$item['id'])
                        ->where('status','<>','EXC')->orderBy('ordem','ASC')->get();
      if(count($qSection)){
        foreach($qSection as $val){
          $dSection[]=['id'=>$val['id'],'titulo'=>$val['nome']];
        }
      }
      $item['secoes']=$dSection;
      return $item;
    } 
    return [];
  } 
    
  public function getMenuSections($memu){
    $return =[];
    $query = $this->secao_repo->where('id_menu',$memu)->where('id_submenu','')->where('status','SIM')
                              ->orderBy('nome','ASC')->get();
    foreach ($query as $secao) {
      $return[]=['id'=>$secao['id'],'nome'=>'depois de '.$secao['nome']];
    }
    return $return;
  }

  public function update($request){
    $data =$request->all();
    $id = $data['id'];
    $data['status']= ($data['status'] == "SIM") ? "SIM":"NÃO";
    $this->checkNull($data);
    $this->do_upload($request, $data);
    $id =$data['id'];
    unset($data['id']);
    foreach ($data as $key => $val) {
      if(substr($key,0,11)=='order_item-'){
        $ex= explode('-',$key);
        $this->secao_repo->where('id',$ex[1])->update(['ordem'=>$val]);
        unset($data[$key]);
      }
    }
    $r  = $this->repo->where('id',$id)->update($data);
    $this->generator->gRouters();
    $this->registerAction(['acao'=>'UPDATE','id_registro'=>$id]);
    return ['status'=>true,'data'=>$r];
  }

  public function findSubmenu($menu){
    $return =[];
    $query = $this->repo->where('id_menu',$menu)->where('status','SIM')->orderBy('nome','ASC')->get();
    foreach ($query as $sub) {
      $return[$sub['id']]=$sub['nome'];
    }
    return $return;
  }

}
?>