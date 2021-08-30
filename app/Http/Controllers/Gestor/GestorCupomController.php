<?php
    namespace App\Http\Controllers\Gestor;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiInfoMenuService;
    use App\Services\ApiPlanoService;
    use App\Services\ApiTransacaoService;
    use App\Services\ApiCupomService;
	
    class GestorCupomController extends GestorController {

      private $infoMenu;
      private $plano_service;
      private $transacaoService;
      private $cupomService;

      public function __construct(ApiInfoMenuService $infoMenu,ApiPlanoService $plano_service,
      ApiTransacaoService $transacaoService,ApiCupomService $cupomService) {
        $this->infoMenu =$infoMenu;
        $this->plano_service =$plano_service;
        $this->transacaoService =$transacaoService;
        $this->cupomService =$cupomService;
      }
      
      public function index(){
       $user = $this->getUserLogged();
       if($user['nivel']!='ADM_MASTER'){
          return ['status'=>false,'Sem permissão'];
       }
       $planos = $this->plano_service->getPlanos();
       $data =['data'=>[
        'grupoLink'=>'PERFIL',
        'planos'=>$planos,
        // 'meusPlanos'=>$this->plano_service->getMeusPlanos($user),
        'infoMenu'=>$this->infoMenu->getInfoMenu($user),
        'cupons'=> $this->cupomService->getCupons(),
        'userLogged'=> $this->getUserLogged(),
         ]];
        // $data = array_merge_recursive($data,$this->data);

        return view("gestor.cupons", $data);
      }
      // getCupom

      public function getCupom(Request $request){
        $user = $this->getUserLogged();
        if($user['nivel']!='ADM_MASTER'){
           return ['status'=>false,'Sem permissão'];
        }
        $return = $this->cupomService->getCupom($request->id);
        if(count($return)){
          return ['status'=>true,'data'=>$return];
        }else{
          return ['status'=>false,'msg'=>'Não foi possível encontrar o cupom.'];
        }
        return $return;
      }

      public function salvarCupom(Request $request) {
        $data = $request->all();
        $user = $this->getUserLogged();
        if($user['nivel']!='ADM_MASTER'){
           return ['status'=>false,'Sem permissão'];
        }
        if(empty($data['id'])) {
          if($this->cupomService->checkCodCupom($data['codigo'])) {
            return ['status' => false, 'msg' => 'Já existe um cupom com esse código.'];
          } 
        } 
     

        $retorno = $this->cupomService->salvarCupom($data);
        if ($retorno['status']) {
         return ['status' => true, 'msg' => 'Cupom salvo com sucesso.'];
        } else {
         return ['status' => false, 'msg' => 'Não foi possível salvar o cupom.'];
        }
      }

      public function deleteCupom(Request $request) {
        $retorno = $this->cupomService->deleteCupom($request->id);
        if ($retorno['status']) {
          return ['status' => true, 'msg' => 'Cupom deletado com sucesso.'];
         } else {
          return ['status' => false, 'msg' => 'Não foi possível deletar o cupom, tente novamente mais tarde!'];
         }
      }
     
    }
    
    ?>