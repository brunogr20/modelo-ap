<?php
    namespace App\Http\Controllers\ZP;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\ZP\Sys\SysController;
    use App\Services\Sys\SysCoreService;
    use App\Services\ApiBannerService;
    use App\Services\ApiNoticiaService;
    
    class ZPBannerController extends SysController {
    
      protected $service;
      protected $noticia_service;
      

      function __construct(SysCoreService $core_service, ApiBannerService $service,ApiNoticiaService $noticia_service ){
        parent::__construct($core_service);
        $this->service=$service;
        $this->noticia_service =$noticia_service ;
        
      }
    
      public function index(){
        $page = $this->dataPage();
        $page["imports"] = ["dataTable","crud"];
        $page['data'] = ['noticias'=>$this->noticia_service->getSelectData('id',['titulo'],[],'DESC')];
        return view("zp.sections.banner",["page"=>$page]);
      }
    
    }
    
    ?>