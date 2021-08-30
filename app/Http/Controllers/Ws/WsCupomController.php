<?php
namespace App\Http\Controllers\Ws;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\ApiCupomTransacaoService;
use App\ZP\Message;
class WsCupomController {

    protected $service;

    function __construct(ApiCupomTransacaoService $service){
        $this->service = $service;
    }

    // public function index(){
    //     $page = $this->dataPage();
    //     $page['imports'] = ['dataTable','crud'];
    //     $page['data'] = ['perfis'=>$this->perfil_service->getPerfis()];
    //     return view('zp.sections.sys.usuario',['page'=>$page]);
    // }
 
  

    public function cupomDetalhes(Request $request){
      $r = $this->service->getCupom([],$request->id);
      if(count($r['transacoes'])){
        return ['status'=>true,'data'=>$r];
      }
      return ['status'=>false];
    }

    public function cupons(Request $request){
      $filter =[];
      if(!$request->id_user){
        return ['status'=>false,'msg'=>'Falha na autenticaição'];
      }
      $e = $this->service->getEstabelecimentosParoquiano($request->all());
      if(!empty($request->ano)){
        $filter['ano']=$request->ano;
      }
      if(!empty($request->mes)){
        $filter['mes']=$request->mes;
      }
      if(!empty($request->estabelecimento)){
        $filter['estabelecimento']=$request->estabelecimento;
      }
      $aFilter =$filter;
      $a=[];
      if($request->oneTabVision||$request->visao=='aberto'){
      $aFilter['status'] ='ABERTO';
      $a = $this->service->getCupons(['id'=>$request->id_user],$aFilter);
      }
      $b=[];
      $acum=['quantidade'=>0,'totalAcumulado'=>0,'totalContribuicao'=>0];
      if($request->oneTabVision||$request->visao=='fechado'){
        $filter['status'] = 'FECHADO';
        $b = $this->service->getCupons(['id'=>$request->id_user],$filter);
        /// soma dos valores
        $acum = $this->service->getCupons(['id'=>$request->id_user],$filter,'ACUMULADO');
      }

      return ['status'=>true,'cuponsAbertos'=>$a,'cuponsFechados'=>$b,'filtros'=>$e,'acumulado'=>$acum];
    }
  
    
}

?>