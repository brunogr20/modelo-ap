<?php 
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Services\Sys\SysGeneratorService;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysMenuService;
use App\Services\Sys\SysSubmenuService;
use App\Http\Controllers\ZP\Sys\SysController;

use App\ZP\Generator;

class SysGeneratorController extends SysController {

    protected $service;
    protected $menu_service;
    protected $submenu_service;

    function __construct(SysCoreService $core_service, SysGeneratorService $service,SysMenuService $menu_service,SysSubmenuService $submenu_service){
        parent::__construct($core_service);
        $this->service = $service;
        $this->menu_service = $menu_service;
        $this->submenu_service = $submenu_service;
    }

    public function index(){
        $page = $this->dataPage();
        $page['imports'] = ['crud','grid','dataTable'];
        $page['data'] = [];
        return view('zp.sections.sys.generator',['page'=>$page]);
       }

       public function create(Request $request){
           return $this->service->create($request->all());
       }

       public function ajax(Request $request){
            if($request->action=='load_menu'){
                return ['status'=>true,'data'=>$this->menu_service->getMenu()];
            }elseif($request->action=='load_submenu'){
                return ['status'=>true,'data'=>$this->submenu_service->findSubmenu($request->id)];
            }elseif($request->action=="load_entities"){
                return ['status'=>true,'data'=>$this->service->get()];
            }elseif($request->action=="load_entities_fields"){
                return ['status'=>true,'data'=>$this->service->getFields($request->idEntity)];
            }

       }



}
?>