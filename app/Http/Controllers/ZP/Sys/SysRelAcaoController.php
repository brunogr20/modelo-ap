<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Reports\Sys\SysAcaoReport;
use App\ZP\Message;
use App\Services\Sys\SysUsuarioService;
use App\Services\Sys\SysSecaoService;


class SysRelAcaoController extends SysController {  
   
  protected $user_service;
  protected $secao_service;
    
   function __construct(SysCoreService $core_service, SysAcaoReport $report,SysUsuarioService $user_service,SysSecaoService $secao_service){
    parent::__construct($core_service);
    $this->report = $report;
    $this->user_service = $user_service;
    $this->secao_service = $secao_service;
   }

  public function index(){
    $page = $this->dataPage();
    $page['imports'] = ['dataTable','rel'];
    $page['data'] = ['users'=>$this->user_service->getUsers(),'secoes'=>$this->secao_service->getSections()];
    return view('zp.sections.sys.rel-acao',['page'=>$page]);
  }
  
  public function filter(Request $request){
    $data=$request->all();
        $r = $this->report->filter($data);
    if($data['rel_type']=='VIEW'){
     return $r;
    }else if($data['rel_type']=='EXCEL'){
     if($r['status']){
      return Message::get("SUCCESS","FILE_GENERATE",['nameFile'=>$r['nameFile']]);
     }else{
      Message::get("ERROR","FILE_GENERATE");
     }
    }
  }

  public function getDownloadReport(Request $request,$file){  
  return $this->report->getDownloadReport($file);
  }

}