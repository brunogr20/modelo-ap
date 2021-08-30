<?php 
namespace App\Http\Controllers\ZP\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\SysCoreService;

class SysCoreController extends Controller {

  private $core_service;
  private $acess_level;
  function __construct(SysCoreService $core_service){
    $this->middleware('authZP');
    $this->core_service = $core_service;
  }

  public function dataPage(){
     $page =  [
       'route_data'=> $this->core_service->getRouteData(),
       'user_logged'=>$this->core_service->getUserLogged(),
       'last_acess'=>$this->core_service->getLastAcess(),
       'perfil'=>$this->core_service->getDataPerfil(),
       'section_active'=>$this->core_service->getSectionActive(),
       'menus'=>$this->core_service->getMenu(),
      ];
     return $page;
  }

  

}


