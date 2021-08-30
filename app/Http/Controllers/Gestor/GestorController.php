<?php
    namespace App\Http\Controllers\Gestor;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiPesquisaService;
    use Auth;
    use Session;
	
    class GestorController extends Controller {
      
      private $pesquisaService;
      protected $data =[];  
      public function __construct(ApiPesquisaService $pesquisaService) {
         $this->pesquisaService = $pesquisaService;
        // $this->data['data'] = ['duvidas'=>$this->duvidas_service->getSelectData('id',['ordem','titulo','texto'])];
      }


      public function getUserLogged(){

        $auth =  Auth::guard('usuario')->user();

        $user =[];
        if(!empty($auth)){
          $user['id']=$auth['id'];
          $user['id_empresa']=$auth['id_empresa'];
          $user['nivel']=$auth['nivel'];
          $user['nome']=$auth['nome'];
          $user['cpf']=$auth['cpf'];
          $user['sessionMaster']=[];
          if(Session::has('ADM_MASTER_id')){
            $user['sessionMaster']=[
              'id'=>Session::get('ADM_MASTER_id')[0],
              'nome'=>Session::get('ADM_MASTER_nome')[0],
            ];
          }
        }
        return $user;
      }

    }
    
    ?>