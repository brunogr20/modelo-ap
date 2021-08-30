<?php
  namespace App\Http\Controllers\ZP\Sys;
  
  use Illuminate\Http\Request;
  use App\Http\Controllers\ZP\Sys\SysController;
  use App\Services\Sys\SysCoreService;
  use App\Services\Sys\SysAnotacaoService;
  use App\ZP\Message;
  
  class SysAnotacaoController extends SysController {
    protected $service;
        
    function __construct(SysCoreService $core_service, SysAnotacaoService $service){
      parent::__construct($core_service);
      $this->service = $service;
    }
    
    public function list(Request $request){
      $r=$this->service->list($request);
      if($r['status']){
        return ['status'=>true, "list"=>$r['list']];
      }else{
        return ['status'=>false, "list"=>[]];
      }
    }
    
    public function update(Request $request){
      $data = $request->all();
      if(!$data['id']){
        return Message::get("ERROR","NOT_AVAILABLE");
      }
      $r = $this->service->update($request);
      if($r['status']){
        return Message::get("SUCCESS","NOTE_UPDATE");
      }else{
        return Message::get("ERROR","NOTE_UPDATE");
      }
    }
    
    public function create(Request $request){
      $r = $this->service->create($request);
      if($r['status']){
        return Message::get("SUCCESS","NOTE_INSERT");
      }else{
        return Message::get("ERROR","NOTE_INSERT");
      }
    }
    
    public function delete(Request $request){
      $data =  $request->all();
       $r = $this->service->delete($data);
      if($r['status']){
        return Message::get("SUCCESS","NOTE_DELETE");
      }else{
        return Message::get("ERROR","NOTE_DELETE");
      }
    }
    
  }