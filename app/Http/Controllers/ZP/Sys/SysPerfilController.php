<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysPerfilService;

class SysPerfilController extends SysController {

  protected $service;
    
   function __construct(SysCoreService $core_service, SysPerfilService $service){
    parent::__construct($core_service);
    $this->service = $service;
   }

  public function index(){
   $page = $this->dataPage();
   $page['imports'] = ['dataTable','crud'];
   $page['data'] = [];
   return view('zp.sections.sys.perfil',['page'=>$page]);
  }

}

?>