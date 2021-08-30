<?php
namespace App\Http\Controllers\ZP;

use Illuminate\Http\Request;
use App\Services\Sys\SysCoreService;
use App\Reports\ZPParoquiaCupomReport;
use App\ZP\Message;
use App\Http\Controllers\ZP\Sys\ZPRelController;
use App\Services\ApiParoquiaService;
use App\Services\ApiCupomTransacaoService;
use App\Services\ApiEstabelecimentoService;

class ZPRelCuponsController extends ZPRelController {  
   
  protected $paroquia_service;
  protected $cupom_trans_repo;
  protected $estabelecimentoService;
    
  function __construct(SysCoreService $core_service, 
                        ZPParoquiaCupomReport $report,
                      ApiParoquiaService $paroquia_service,
                      ApiCupomTransacaoService $cupom_trans_repo,
                      ApiEstabelecimentoService $estabelecimentoService
                      )
  {
    parent::__construct($core_service,$report);
    $this->paroquia_service = $paroquia_service;
    $this->cupom_trans_repo = $cupom_trans_repo;
    $this->estabelecimentoService = $estabelecimentoService;
  }

  public function index(){
    $page = $this->dataPage();
    $page['imports'] = ['dataTable','rel'];
    $page['data'] = [
      'paroquiasData'=>$this->paroquia_service->getSelectData("id",["nome"]),
      'estabelecimentoData'=>$this->estabelecimentoService->getSelectData("id",["nome"]),
    ];
    return view('zp.sections.rel-cupons',['page'=>$page]);
  }

  public function ajax(Request $request){

    if($request->action=='load-cupom'){
      $r =  $this->paroquia_service->loadComissao($request->cupom);
      if(count($r)){
        return ['status'=>true,'data'=>$r];
      }else{
        return ['status'=>false];
      }
    }elseif($request->action=='detalehes'){
      $r =  $this->cupom_trans_repo->detalhesRelatorio($request->cupom);
      
      if(count($r)){
        return ['status'=>true,'data'=>$r];
      }else{
        return ['status'=>false];
      }
    }elseif($request->action=='submit-comissao'){
      $r =  $this->paroquia_service->salvarComissao([],$request->all());
        
      if($r['status']){
        return ['status'=>true,'msg'=>"Os dados foram salvos com sucesso!"];
      }else{
        return ['status'=>false,'msg'=>'Não foi possível salvar os dados.'];
      }
    }
  }

}