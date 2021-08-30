<?php
    namespace App\Http\Controllers\Site;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiHomeService;


    class SiteHomeController{
      private $service;


      function __construct(ApiHomeService $service){
        $this->service =$service;

      }
      
      public function index(){
        $planos = $this->service->getPlanos();

        $data['data'] = [
        'planos'=>$planos,
        ];

        // dd($data);
        return view("site.home",$data);
      }
     
    }

    
    ?>