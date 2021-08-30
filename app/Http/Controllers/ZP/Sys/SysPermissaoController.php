<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysPermissaoService;

class SysPermissaoController extends SysController {

  protected $service;
  protected $core_service;
    
   function __construct(SysCoreService $core_service, SysPermissaoService $service){
    $this->core_service = $core_service;
    parent::__construct($core_service);
    $this->service = $service;
   }

  public function index(){
   $page = $this->dataPage();
   $page['imports'] = ['dataTable','crud'];
   $page['data'] = ['menus_permissions'=>$this->core_service->getMenu('permissions')];
   return view('zp.sections.sys.permissao',['page'=>$page,]);
  }

}

?>