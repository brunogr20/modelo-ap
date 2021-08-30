<?php
namespace App\Http\Controllers\Ws;

use App\Http\Controllers\Ws\WsController;
use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\ApiUsuarioService;
use App\Services\ApiAuthService;
use App\Services\ApiEmpresaService;
// use App\Services\ApiCadastroContatoService;
// use App\Services\ApiNotificacaoService;
use App\ZP\Message;

class WsUsuarioController  extends WsController{

    protected $service;
    protected $repoEmpresa;
    // protected $contatoService;
    // protected $notificacao_service;

    function __construct(ApiUsuarioService $service,ApiAuthService $auth,ApiEmpresaService $repoEmpresa){
        $this->service = $service;
        $this->auth = $auth;
        $this->repoEmpresa = $repoEmpresa;
        // $this->contatoService = $contatoService;
        // $this->notificacao_service = $notificacao_service;
    }

    public function getDataUserLogged(){
       $user =[];
       $auth = $this->getUserLogged();
       if(count($auth)){
            $empresa = $this->repoEmpresa->getEmpresa(['id_empresa'=>$auth['id_empresa']]);
            $user['id']=$auth['id'];
            $user['id_empresa']=$auth['id_empresa'];
            $user['img_empresa']=  !empty($empresa['imagem_logo_empresa'])?$empresa['imagem_logo_empresa']:"";
            $user['nivel']=$auth['nivel'];
            $user['nome']=$auth['nome'];
            $user['email']=$auth['email'];
            $user['contas']=$this->service->getContasUsuarios(['cpf'=>$auth['cpf']]);
            $user['versao']='2.0.0';
       }
       return $user; 
    }

    public function alterarSenha(Request $request) {
        $data = $request->all();
        $user = $this->getUserLogged();
       
        $data=['id'=>$user['id'],'senha'=>$data['senha']];
    
        $retorno = $this->service->alterarSenha($user,$data);
        if ($retorno['status']) {
         return ['status' => true, 'msg' => 'Senha salva com sucesso.'];
        } else {
         return ['status' => false, 'msg' => 'Não foi possível salvar a senha.'];
        }
      }

    // public function index(){
    //     $page = $this->dataPage();
    //     $page['imports'] = ['dataTable','crud'];
    //     $page['data'] = ['perfis'=>$this->perfil_service->getPerfis()];
    //     return view('zp.sections.sys.usuario',['page'=>$page]);
    // }
 
    // public function enviarEmail(Request $request){
    //  $data =  $request->all();
    //  $id_user = $request->id_user;
    //  if(!$id_user){
    //   return ['status'=>false,'msg'=>'Falha na autenticaição'];
    // }
    //  $user =   $this->service->getDataParoquiano(['id'=>$id_user]);
    //  $data['email'] =$user['email'];
    //  $data['telefone'] =$user['celular'];
    //  $data['nome'] = $user['nome']?$user['nome']:'Sem nome';
   
    //   $f = $this->contatoService->cadastrarMensagem($data,'APP');
    //   if($f['status']){
    //     return ['status'=>true,'msg'=>'Mensagem enviada com sacesso.'];
    //   }
    //   return ['status'=>false,'msg'=>'Não foi possível enviar essa mensagem, tente novamente mais tarde.']; 
    // }

    // public function contatoNotificacoes(Request $request){
    //   $id = $request->id_user;
    //   if(!$id){
    //     return ['status'=>false,'msg'=>'Falha na autenticaição'];
    //   }
    //   $msgUser='';
    //   $user = $this->service->getDataParoquiano(['id'=>$id]);
    //   if(!empty($user['msg_notificacao'])){
    //     $msgUser=$user['msg_notificacao'];
    //   }
    //   $d = $this->notificacao_service->contatoNotificacoes($id);
    //  // if(count($d)){
    //    return ['status'=>true,'notificacoes'=>$d,'msgUser'=>$msgUser];
    //   // }
    //    return ['status'=>false];
    // }
    // public function getMinhaConta(Request $request){
    //   if(!$request->id_user){
    //     return ['status'=>false,'msg'=>'Falha na autenticaição'];
    //   }
      
    //   $d = $this->service->getDataParoquiano(['id'=>$request->id_user]);
    //  // if(count($d)){
    //    return ['status'=>true,'user'=>$d];
    //   // }
    //    return ['status'=>false];
    // }


    // public function create(Request $request){
    
    //     // if(!$this->service->isUniqueEmail($request->email)){
    //     //     return ['status'=>false,'msg'=>'Existe outro paroquiano utilizando esse e-mail.'];
    //     // }

    //     // if(!$this->service->isUniqueCpf($request->cpf,$request->id_user)){
    //     //   return ['status'=>false,'msg'=>'Existe outro paroquiano utilizando esse CPF.'];
    //     // }
        
    //     $r = $this->service->create($request,true);
      
    //     if($r['status']){
    //       $this->service->newPassword($r['data']['id'],$request->password);
    //       $auth = $this->auth->authApp($request,['login'=>$request->celular,'password'=>$request->password]);
    //       if(count($auth)>3){
    //         return $auth;
    //       }
    //       return ["status"=>false,"msg"=>"Falha na autenticação!"];
    //     }else{
    //       return ['status'=>false,'msg'=>'Não foi pssível realizar o cadastro!'];
    //     }
    //   }
      
    //   public function checarCampos(Request $request){
        
    //     $data = $request->all();

    //     return $this->service->checarCampos($data);
    // }

    // public function alterarParoquiano(Request $request){
    //   if(!$request->id_user){
    //     return ['status'=>false,'msg'=>'Falha na autenticaição'];
    //   }
    //   $data=[
    //     'nome'=>$request->nome,
    //     'email'=>$request->email,
    //     'celular'=>$request->celular,
    //   ];
    //   if(!empty($request->email)){
    //     if(!$this->service->isUniqueEmail($request->email,$request->id_user)){
    //       return ['status'=>false,'msg'=>'Esse e-mail já existe.'];
    //     }
    //   }
    //   if(!empty($request->celular)){
    //     if(!$this->service->isUniqueCelular($request->celular,$request->id_user)){
    //       return ['status'=>false,'msg'=>'Esse celular já existe.'];
    //     }
    //   }

    //  $r = $this->service->alterar($request->id_user,$data);
    //   if($r['status']){
    //     return ['status'=>true,'msg'=>'Os dados foram alterados com sucesso!'];
    //   }
    //   return ['status'=>false,'msg'=>'Não foi alterar seus dados.'];
    // }

    // public function alterarSenha(Request $request){
    //   if(!$request->id_user){
    //     return ['status'=>false,'msg'=>'Falha na autenticaição'];
    //   }
    //   if ($request->id_user&& $request->password) {
    //     if ($this->service->newPassword($request->id_user, $request->password))
    //     return ['status'=>true,'msg'=>'Senha alterada com sucesso!'];
    // }
    // return ['status'=>false,'msg'=>'Não foi possível alterar a senha.'];
    // }

    // public function getParoquias(Request $request){

    //     return [];
    // }


    // public function update(Request $request){
    //     if(empty($request->id)){
    //         return Message::get("INFO","NOT_AVAILABLE");
    //     }
    //     if(!$this->service->isUniqueEmail($request->email,$request->id)){
    //         return Message::get("INFO","USER_ALREADY_EXISTS");
    //     }
    //     $r = $this->service->update($request);
    //     if($r['status']){
    //         return Message::get("SUCCESS",["data"=>$r['data'], "msg"=>"O registro foi alterado com sucesso."]);
    //     }else{
    //         return Message::get("ERROR","UPDATE");
    //     }
    // }

    
}

?>