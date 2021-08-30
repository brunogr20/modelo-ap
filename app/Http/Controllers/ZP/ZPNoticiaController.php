<?php
    namespace App\Http\Controllers\ZP;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\ZP\Sys\SysController;
    use App\Services\Sys\SysCoreService;
    use App\Services\ApiNoticiaService;
    
    class ZPNoticiaController extends SysController {
    
      protected $service;
      

      function __construct(SysCoreService $core_service, ApiNoticiaService $service ){
        parent::__construct($core_service);
        $this->service=$service;
        
      }
    
      public function index(){
       
        $page = $this->dataPage();
        $page["imports"] = ["dataTable","crud"];
        $page["data"] = ['noticias'=>$this->service->getSelectData('id',['titulo'],[],'DESC')];
        return view("zp.sections.noticia",["page"=>$page]);
      }
    
    }
    
    ?>