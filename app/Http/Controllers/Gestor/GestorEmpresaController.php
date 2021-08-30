<?php
    namespace App\Http\Controllers\Gestor;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiEmpresaService;
    use App\Services\ApiInfoMenuService;

    class GestorEmpresaController extends GestorController {
      
      private $service;
      private $infoMenu;

      function __construct(ApiInfoMenuService $infoMenu,ApiEmpresaService $service){
        $this->infoMenu =$infoMenu;
        $this->service =$service;
      }
      
      public function index(){
        $user = $this->getUserLogged();
        $empresa = $this->service->getEmpresa($user);
        $data['data'] = [
          'grupoLink'=>'PERFIL',
          'infoMenu'=>$this->infoMenu->getInfoMenu($user),
          'empresa'=>$empresa,
          'cidades'=>$this->service->cidades($empresa['uf']),
          'userLogged'=> $this->getUserLogged()
        ];
        return view("gestor.empresa",$data);
      }
     
    
      public function salvarEmpresa(Request $request) {
        $request->validate([
          'nome' => 'required||min:3|max:100',
          'cpf_cnpj' => 'required|max:100',
        ]);
        $user = $this->getUserLogged();
        $data = $request->all();

        $request['id'] = $user['id_empresa'];

        $verificacaoCPF = $this->service->verificacaoCPF($request);
        if ($verificacaoCPF['status']== false) {
          return ['status' => false, 'msg' => 'Este CPF/CNPJ Já foi cadastrado no sistema.'];
         } 

        
        $retorno = $this->service->updateEmpresa($user,$data,$request);
        if ($retorno['status']) {
         return ['status' => true, 'msg' => 'Os dados foram salvos com sucesso.'];
        } else {
         return ['status' => false, 'msg' => 'Não foi possível salvar os dados.'];
        }
       }

       public function cidades(Request $request) {
           
        $retorno = $this->service->cidades($request->uf);
        if ($retorno) {
         return ['status'=>true,'cidades'=>$retorno];
        } else {
         return ['status' => false, 'msg' => 'Não foi carregar as cidades.'];
        }
      }
    
    }
    
    ?>