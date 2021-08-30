<?php 
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\ZP\Message;
use App\Http\Controllers\ZP\Sys\SysCoreController;
use App\Services\Sys\SysCoreService;

class SysController extends SysCoreController {

  private $core_service;
  function __construct(SysCoreService $core_service){
    parent::__construct($core_service);
    $this->core_service=$core_service;
  }

  /**retorna dados para a grid (Ajax) */
  public function loadGrid(Request $request){
    $data = $request->all();
    return $this->service->loadGrid($data);
  }
  /**retorna dados para o formulário (Ajax) */
  public function loadForm(Request $request){
    $result = [];
    $data = $request->all();
    $result =  $this->service->loadForm($data);
    if(($result)){
      return ['status'=>true,'data'=>$result];
    }else{
      return ['status'=>true,'msg'=>''];
    }
  }
  /**Cria novo registro */
  public function create(Request $request){
    $r = $this->service->create($request);
    if($r['status']){
      return Message::get("SUCCESS","INSERT");
    }else{
      return Message::get("ERROR","INSERT");
    }
  }

  public function update(Request $request){
    $data = $request->all();
    if(!$data['id']){
      return Message::get("ERROR","NOT_AVAILABLE");
    }
    $r = $this->service->update($request);
    if($r['status']){
      return Message::get("SUCCESS","UPDATE");
    }else{
      return Message::get("ERROR","UPDATE");
    }
  }
 
  public function updateStatus(Request $request){
    $data =  $request->all();
    $r = $this->service->updateStatus($data['ids'],$data['status']);
    if($r['status']){
      if(count($data['ids'])>1){
         return ($data['status']=='SIM')? Message::get("SUCCESS","STATUS_MANY_ON") : Message::get("SUCCESS","STATUS_MANY_OFF");
        }else{
         return ($data['status']=='SIM')? Message::get("SUCCESS","STATUS_ON") : Message::get("SUCCESS","STATUS_OFF");
      }
    }else{
      return (count($data['ids'])>1)? Message::get("ERROR","UPDATE_MANY"):Message::get("ERROR","UPDATE");
    }
  }

  public function reorderGrid(Request $request){
    $data =  $request->all();
    $r = $this->service->reorderGrid($data);
    if($r['status']){
      // return ['status'=>true, "msg"=>"A nova ordem foi salva!"];
      return Message::get("SUCCESS","REORDER");
    }else{
      //return ['status'=>false, "msg"=>"Não foi possível salvar a ordenação!"];
      return Message::get("ERROR","REORDER");
    }
  }
  
  public function delete(Request $request){
    $data =  $request->only(['ids']);
    $r = $this->service->delete($data);
    if($r['status']){
      return (count($data['ids'])>1)? Message::get("SUCCESS","DELETE_MANY") : Message::get("SUCCESS","DELETE");
    }else{
      return (count($data['ids'])>1)? Message::get("ERROR","DELETE_MANY") : Message::get("ERROR","DELETE");
    }
  }

  public function deleteFile(Request $request){
    $data =  $request->only(['id','name','type']);
    $r = $this->service->deleteFile($data);
    if($r['status']){
      return ($data['type']=='image')? Message::get("SUCCESS","DELETE_IMAGE") : Message::get("SUCCESS","DELETE_FILE");
    }else{
      return ($data['type']=='image')? Message::get("ERROR","DELETE_IMAGE") : Message::get("ERROR","DELETE_FILE");
    }
  }

  public function getDownload(Request $request,$file){
    return $this->service->getDownload($file,$this->core_service->getUserLogged());
  }

}


