<?php
namespace App\Http\Controllers\ZP;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\ApiInstitucionalService;

class ZPInstitucionalController extends SysController {

  protected $service;
    
  function __construct(SysCoreService $core_service, ApiInstitucionalService $service){
    parent::__construct($core_service);
    $this->service = $service;
  }

  public function index(){
    $page = $this->dataPage();
    $page['imports'] = ['dataTable','crud'];
    $page['data'] = [];
    return view('zp.sections.institucional',['page'=>$page]);
  }

}
?>