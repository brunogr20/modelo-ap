<?php

namespace App\Services\Sys;

use Illuminate\Support\Facades\DB;
use App\ZP\GridSelectBuilder;
use App\Entities\Sys\SysPermissao;

class SysPermissaoService extends SysService{

  private $repo;

  public function __construct(SysPermissao $repo){
    $this->repo = $repo;
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
      "FROM_BODY" =>"sys_perfis",
      "WHERE" => " status <> 'EXC'",
    ];
    return $dados = (array) GridSelectBuilder::buildTable($data, $baseSql,$functions, true);
  }

  public function loadForm($data){
    $query =DB::select("SELECT t2.*, t1.nivel FROM sys_permissoes AS t1 JOIN sys_secoes AS t2  ON t1.id_secao=t2.id"
  ." WHERE t1.id_perfil={$data['id']}  AND t2.status='SIM'");
  $item=['id'=>$data['id']];
  if(count($query)){
      foreach($query as $val){
        $name = 'perm-'.$val->id_menu;
        if($val->id_submenu){$name .= '-'.$val->id_submenu;};
        $name .= '-'.$val->id;
        $item[$name]=($val->nivel)?$val->nivel:'0';
      }
      return $item;
    } 
    return $item;
  } 

  public function update($request){
    $data =$request->all();
    $id = $data['id'];
    unset($data['id']);
    $idsNotDel=[];
    foreach($data as $k => $v){
     $ar = explode('-',$k);
     $idSection='';
     if(count($ar)==3){
      $idSection=$ar[2];
     }elseif(count($ar)==4){
      $idSection=$ar[3];
     }
     if($idSection&&$ar[0]=='perm'){
      $query=$this->repo->where('id_perfil',$id)->where('id_secao',$idSection);
      $reg=$query->get();
      if(count($reg)){
        $reg= $reg[0];
        $idsNotDel[]=$reg['id'];
        if($reg['nivel']!=$v){
          $query->update(['nivel'=>$v]);
        }
      }else{
        $res= $this->repo->create([
          'id_perfil'=>$id,
          'id_secao'=>$idSection,
          'nivel'=>$v,
          ]);
          $idsNotDel[]=$res['id'];
        }
      }
    }
   
    $query=$this->repo->where('id_perfil',$id)->whereNotIn('id',$idsNotDel)->delete();
    $this->registerAction(['acao'=>'UPDATE','id_registro'=>$id]);
    return ['status'=>true,'data'=>[]];
  }

}
?>