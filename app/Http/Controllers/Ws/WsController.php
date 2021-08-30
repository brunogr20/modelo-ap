<?php
    namespace App\Http\Controllers\Ws;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiPesquisaService;
    use Auth;
	
    class WsController extends Controller {
      
      private $pesquisaService;
      protected $data =[];  
      public function __construct(ApiPesquisaService $pesquisaService) {
         $this->pesquisaService = $pesquisaService;
        // $this->data['data'] = ['duvidas'=>$this->duvidas_service->getSelectData('id',['ordem','titulo','texto'])];
      }


      public function getUserLogged(){   

        $auth =  Auth::guard('api')->user();

        $user =[];
        if(!empty($auth)){
          $user['id']=$auth['id'];
          $user['id_empresa']=$auth['id_empresa'];
          $user['nivel']=$auth['nivel'];
          $user['nome']=$auth['nome'];
          $user['email']=$auth['email'];
          $user['cpf']=$auth['cpf'];
        }
        return $user;
      }

    }
    
    ?>