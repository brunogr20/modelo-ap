<?php
    namespace App\Http\Controllers\Site;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiSiteCadastroService;
    use App\Services\ApiAuthService;
    use App\Services\ApiSitePlanoEscolhidoService;
    use App\Services\ApiTransacaoService;
    use App\Services\ApiEmpresaService;
    use Auth;
    use Session;


    class SiteCadastroController {
      
        private $service;
        private $service_auth;
        private $service_plano_escolhido;
        private $transacaoService;
        private $service_empresa;

      function __construct(ApiSiteCadastroService $service ,ApiEmpresaService $service_empresa ,ApiAuthService $service_auth, ApiSitePlanoEscolhidoService $service_plano_escolhido, ApiTransacaoService $transacaoService){
        $this->service =$service;
        $this->service_auth =$service_auth;
        $this->service_plano_escolhido =$service_plano_escolhido;
        $this->transacaoService =$transacaoService;
        $this->service_empresa =$service_empresa;
      }
      
      public function index($idPlano){
        $planos = $this->service_plano_escolhido->getPlano($idPlano);

        $data['data'] = [
        'planos'=>$planos,
        ];
        return view("site.cadastro",$data);
      }

      public function cadastro(Request $request){
        $request->validate([
            'nome_empresa' => 'required||min:3|max:100',
            'telefone_empresa' => 'required||min:3|max:20',
            'cpf_cnpj_empresa' => 'required||min:3|max:20',
            'nome' => 'required||min:3|max:100',
            'telefone' => 'required||min:3|max:20',
            'cpf_cnpj' => 'required|max:100',
            'email' => 'required',
            'confirma_email' => 'required',
          ]);

          $data = $request->all();

        $verificacaoCPF = $this->service->verificacaoCPF($data);
        if ($verificacaoCPF['status']== false) {
        return ['status' => false, 'msg' => 'Este CPF/CNPJ Já foi cadastrado no sistema.'];
        } 

        $r_empresa = $this->service->createEmpresa($data);
        $data['id_empresa'] = $r_empresa['id'];

        // $verificacaoEmail = $this->service->verificacaoEmailNovoUsuario($data);
        // if ($verificacaoEmail['status']== false) {
        //   return ['status' => false, 'msg' => 'Este E-mail Já foi cadastrado no sistema.'];
        // } 
        
        $r = $this->service->createUsuario($data);
        $login['login'] = $data['cpf_cnpj'];
        $login['password'] = $data['password'];

        $auth = $this->service_auth->auth($login);
        if(!$auth['status']){
          return ['status'=>false,'msg'=>'Login ou senha inválido'];
        }
        if($data['id_plano'] == 5){
          $user=$this->service->getUserLogged();
          $this->transacaoService->comprarPlano($user,['plano'=>$data['id_plano'],'valor'=>0,'tipo'=>'GRATUITO','mentoria'=>[]]);
          $url = env('APP_URL').'pesquisa/lista';
        } else{
          $url = env('APP_URL').'planos/'.$data['id_plano'];
        }
         //$url = env('APP_URL').'k-c1/aws/contas';
        //  $url = env('APP_URL').'pesquisa/lista';

        return ['status'=>true,'url'=>$url];

        // dd($data);
        // return view("site.confirma-dados", $data);
      }

    }    
    ?>