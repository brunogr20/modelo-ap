<?php

namespace App\Services\Sys;

use App\ZP\GridSelectBuilder;
use App\ZP\CodeGenerator;
use App\Entities\Sys\SysMenu;
use App\Entities\Sys\SysSecao;

class SysMenuService extends SysService{

  private $repo;
  private $generator;
  private $secao_repo;

  public function __construct(SysMenu $repo,CodeGenerator $generator,SysSecao $secao_repo){
    $this->repo = $repo;
    $this->generator = $generator;
    $this->secao_repo = $secao_repo;
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
      "FROM_BODY"=>$this->repo->getTable(),
      "WHERE" => " status <> 'EXC'",
    ];
    return (array)GridSelectBuilder::buildTable($data, $baseSql,$functions, true);
  }

  public function loadForm($data){
    $query =  $this->repo->where('id',$data['id'])->where('status','<>','EXC')->get();
    if(count($query)){
      $item =$query[0];
      $dSection=[];
      unset($item['updated_at']);
      unset($item['created_at']);
      
      $qSection =  $this->secao_repo->where('id_menu',$item['id'])->where('id_submenu','')
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

  public function getMenu(){
    $return =[];
    $query = $this->repo->where('status','SIM')->orderBy('nome','ASC')->get();
    foreach ($query as $menu) {
      $return[$menu['id']]=$menu['nome'];
    }
    return $return;
  }

}
?>