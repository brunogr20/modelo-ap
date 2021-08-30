<?php
    namespace App\Http\Controllers\Site;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiSitePlanoEscolhidoService;


    class SitePlanoEscolhidoController{
      
      private $service;

      function __construct(ApiSitePlanoEscolhidoService $service){
      $this->service = $service;

      }
      
      public function index($idPlano){
        // dd($idPlano);
        // $data['data'] = [
        //   'dia'=>$this->service->dia($idPlano),
        //   'valor'=>$this->service->valor($idPlano),
        //   'plano'=>$idPlano,
        // ];
        $planos = $this->service->getPlano($idPlano);

        $data['data'] = [
        'planos'=>$planos,
        ];
        
        return view("site.plano-escolhido",$data);
      }
     
    }

    
    ?>