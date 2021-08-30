<?php

namespace App\Http\Controllers\ZP\Sys;


use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysHomeService;
use App\Services\Home\HomeBoxService;

class SysHomeController extends SysController{

  protected $service;
  protected $box_service;
     
   function __construct(SysCoreService $core_service,SysHomeService $service,HomeBoxService $box_service){
    $this->service= $service;
    $this->box_service = $box_service;
    parent::__construct($core_service);
   }

  public function index(){
   $page = $this->dataPage();
   $page['imports'] = ['charts'];
   $page['data'] = [
     'chart_accesses'=>$this->service->accesses(),
     'chart_actions'=>$this->service->charActions(),
     'accesses'=>$this->service->getMyAccesses(),
     'last_actions'=>$this->service->getLastActions(),
     'home_box'=>$this->box_service->getHomeBox(),
    ];
   return view('zp.sections.sys.home',['page'=>$page]);
  }

}

?>