<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysSubmenuService;
use App\Services\Sys\SysMenuService;

class SysSubmenuController extends SysController {

  protected $service;
  protected $menu_service;
    
   function __construct(SysCoreService $core_service, SysSubmenuService $service,SysMenuService $menu_service){
    parent::__construct($core_service);
    $this->service = $service;
    $this->menu_service = $menu_service;
   }

  public function index(){
   $page = $this->dataPage();
   $page['imports'] = ['dataTable','crud'];
   $page['data'] = ['menu'=>$this->menu_service->getMenu()];
   return view('zp.sections.sys.submenu',['page'=>$page]);
  }

  public function loadForm(Request $request){
    $result = [];
    $data = $request->all();
    $result =  $this->service->loadForm($data);
    $sections =  $this->service->getMenuSections($result['id_menu']);
    if(($result)){
      return ['status'=>true,'data'=>$result,'sections'=>$sections];
    }else{
      return ['status'=>true,'msg'=>''];
    }
  }

  public function ajax(Request $request){
    $data = $request->all();
    if($data['action']=='load_select_pos'){
     $sections = $this->service->getMenuSections($data['id']);
     if(count($sections)){
     return ['status'=>true,'sections'=>$sections];
    }
    return ['status'=>false];
    }
  }

}

?>