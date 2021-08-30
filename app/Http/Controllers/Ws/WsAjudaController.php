<?php
namespace App\Http\Controllers\Ws;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\ApiTireSuasDuvidasService;
use App\ZP\Message;
class WsAjudaController {

    protected $service;

    function __construct(ApiTireSuasDuvidasService $service){
        $this->service = $service;
    }

  

    public function getAjuda(Request $request){
 
      $r=[];
    $res = $this->service->getSelectData('id',['ordem','titulo','texto']);
     foreach ($res as $key => $value) {
       $r[$key]=['title'=>$value['titulo'],'content'=>$value['texto']];
     }
      return ['status'=>true,'ajuda'=>$r];
    }
 
    
}

?>