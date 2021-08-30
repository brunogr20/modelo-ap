<?php  

namespace App\Services\Sys;
use Illuminate\Support\Facades\Auth;
use App\Entities\Sys\SysUsuario;
use App\Entities\Sys\SysLogAcesso;
use Session;
use App\ZP\Mailer; 

class SysAuthService extends Mailer{

  private $usuario_repo;
  private $log_acesso_repo;
  protected $mail_from_email = 'admcp@adm.com.br';
  protected $mail_from_nome = 'adm';


  function __construct(SysUsuario $usuario_repo,SysLogAcesso $log_acesso_repo){
      $this->usuario_repo = $usuario_repo;
      $this->log_acesso_repo = $log_acesso_repo;
  }

  public function auth($data){

    $auth = Auth::guard('zp')->attempt(['email'=>$data['login'],'password'=>$data['password'],'status'=>'SIM'], $data['remember']);
    if($auth){
      $this->createLogAccess();
      return ['status'=>true];
    }
    return ['status'=>false];
  }

  public function createLogAccess(){
    $dataLog =[
      'id_usuario'=>Auth::guard('zp')->id(),
      'inicio'=>date("Y-m-d H:i:s"),
      'ip'=>$_SERVER['REMOTE_ADDR'],
      'browser'=>$_SERVER['HTTP_USER_AGENT'],
    ];
    $log = $this->log_acesso_repo->create($dataLog);
    Session::push('message_welcome',true);
    Session::push('zp_id_log_acesso',$log['id']);
  }

  public function forgotPass($request){
    $email =  $request->email;
    $user = $this->usuario_repo->where('email',$email)->where('status','SIM');
    if(count($user->get())==1){
      $user=$user->get();
      $user=$user[0];
      $token='';
      $date =  date('Y-m-d H:i:s', strtotime('+1 days'));
      while(true){
       $token=hash("whirlpool",uniqid(rand(),true));
       $checkToken = $this->usuario_repo->where('pass_token',$token)->get();
       if(!count($checkToken)){
         break;
       }
      }
      if($token){
        $url =$request->url();
        $url = str_replace('forgot-pass','user-valid', $url);
        $this->email(
          ['email'=>$user['email'],
          'subject'=>"Suporte adm cp",
          'body'=>"Sr(a) {$user['nome']} </br> você solicitou a definição de uma nova senha para o sistema adm cp.</br>
                   Disponibilizamos um link para a mudançã da sua senha.",
          'link'=>[
            'label'=>'Clique aqui para criar uma nova senha',
            'url'=> $url.'/'.$token.'/'.md5(date('Y-m-d H:i:s')),
          ],
          ]);
       $user->update(['pass_token'=>$token,'pass_token_created'=>$date]);
       return ['status'=>true];
      }
    }
    return ['status'=>false];
  }

  public function checkToken($token){
    if(strlen($token)!=128){
      return false;
    }
    $date = date('Y-m-d H:m:s');
    $user = $this->usuario_repo->where('pass_token',$token)->where('pass_token_created','>=',$date)
                 ->where('status','SIM')->get();
    if(count($user)==1){
      return true;
    }
    return false;
  }

  public function newPassword($data){
    if($this->checkToken($data['token'])){
     $password= bcrypt($data['password']);
     $user=$this->usuario_repo->where('pass_token',$data['token']);
     $user->update(['password'=>$password]);
    //  $user->update(['password'=>$password,'pass_token'=>'','pass_token_created'=>'']);
     $user = $user->get();
     $user = $user[0];
     $this->auth(['login'=>$user['email'],'password'=>$data['password'],'remember'=>false]);
     return ['status'=>true];
    }
    return ['status'=>false];
  }

  public function logout(){
     $user =  Auth::guard('zp')->user();
     $this->log_acesso_repo->where('id', Session::get('zp_id_log_acesso'))->where('id_usuario',$user['id'])
     ->update(['encerramento'=>date("Y-m-d H:i:s")]);
     Auth::guard('zp')->logout();
     Session::forget('zp_id_log_acesso');
    return ['status'=>true];
  }

}
?>