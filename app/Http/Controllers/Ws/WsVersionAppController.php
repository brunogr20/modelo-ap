<?php
namespace App\Http\Controllers\Ws;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\ApiTireSuasDuvidasService;
use App\ZP\Message;
class WsVersionAppController {

    protected $service;

    function __construct(/*ApiTireSuasDuvidasService $service*/){
      //  $this->service = $service;
    }

    // public function index(){
    //     $page = $this->dataPage();
    //     $page['imports'] = ['dataTable','crud'];
    //     $page['data'] = ['perfis'=>$this->perfil_service->getPerfis()];
    //     return view('zp.sections.sys.usuario',['page'=>$page]);
    // }
 
  

    public function getVersion(Request $request){
      return ['status'=>true,'version'=>'1.0.1'];
    }
 
    
}

?>