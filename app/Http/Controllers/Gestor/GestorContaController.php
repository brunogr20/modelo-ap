<?php
    namespace App\Http\Controllers\Gestor;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiInfoMenuService;
    use App\Services\ApiUsuarioService;
    use App\Services\ApiEmpresaService;

	
    class GestorContaController extends GestorController {

      private $infoMenu;

      public function __construct(ApiInfoMenuService $infoMenu,ApiUsuarioService $service,ApiEmpresaService $empresa_service) {
        $this->infoMenu =$infoMenu;
        $this->service =$service;
        $this->empresa_service =$empresa_service;
      }
      
      public function index(){
       $user = $this->getUserLogged();
       $contas = $this->service->getContasUsuarios($user);
 
       if(count($contas)==1){
        return redirect('pesquisa/lista');
       }else{
       $data =['data'=>[
        'grupoLink'=>'PERFIL',
        'contas'=>$contas,
        // 'infoMenu'=>$this->infoMenu->getInfoMenu($user),
        // 'cidades'=>$this->empresa_service->cidades($empresa['uf']),
        // 'userLogged'=> $this->getUserLogged(),
         ]];
        // $data = array_merge_recursive($data,$this->data);
        return view("gestor.checar-conta", $data);
       }
      }

      public function salvarUsuario(Request $request){

      }
      // public function salvarUsuario(Request $request){
      //   $request->validate([
      //     'nome' => 'required||min:3|max:100',
      //     'email' => 'required|email|max:100',
      //   ]);
      //   $user = $this->getUserLogged();
      //   $request['id'] = $user['id'];

      //   // $validacao = $this->service->occurrences($request['cpf']);
      //   // $verificacaoCPF = $this->service->verificacaoCPFNovoUsuario($request);
      //   // if ($verificacaoCPF['status']== false) {
      //   //   return ['status' => false, 'msg' => 'Este CPF Já foi cadastrado no sistema.'];
      //   //  } 

      //   // $verificacaoEmail = $this->service->verificacaoEmailNovoUsuario($request);
      //   // if ($verificacaoEmail['status']== false) {
      //   //   return ['status' => false, 'msg' => 'Este E-mail Já foi cadastrado no sistema.'];
      //   // } 
      
      //   $data = $request->all();
        
      //   $retorno = $this->service->createUsuario($user,$data,$request);
      //   if ($retorno['status']) {
      //    return ['status' => true, 'msg' => 'Os dados foram salvos com sucesso.'];
      //   } else {
      //    return ['status' => false, 'msg' => 'Não foi possível salvar os dados.'];
      //   }
      // }
    
    }
    
    ?>