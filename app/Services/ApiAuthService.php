<?php  

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Entities\ApiUsuario;
use App\Entities\ApiLogAcesso;
use Carbon\Carbon;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use App\ZP\Mailer; 
use Illuminate\Support\Facades\DB;


class ApiAuthService extends Mailer{

  private $usuario;
  private $log_acesso_repo;
  protected $mail_from_email = 'admcp@adm.com.br';
  protected $mail_from_nome = 'adm';

  function __construct(ApiLogAcesso $log_acesso_repo,ApiUsuario $usuario){
      $this->usuario = $usuario;
      $this->log_acesso_repo = $log_acesso_repo;
  }

  public function auth($data){
     Auth::guard('usuario')->logout();
    $auth = Auth::guard('usuario')->attempt(['cpf'=>$data['login'],'password'=>$data['password']],false);
    if($auth){
      $this->createLogAccess();
      return ['status'=>true];
    }
    return ['status'=>false];
  }

  public function authConta($data){
    $user = $this->usuario->where('id',$data['id'])->where('password',bcrypt($data['password']))->get();
    // dd(bcrypt($data['password']));
    // dd($data);
    if(!count($user)){
      return ['status'=>false];
    }
    Auth::guard('usuario')->logout();
    $auth = Auth::guard('usuario')->attempt(['id'=>$data['id'],'password'=>$data['password']],false);
    if($auth){
      $this->createLogAccess();
      return ['status'=>true];
    }
    return ['status'=>false];
  }

  public function authCliente($user,$data){
    if($user['nivel']=='ADM_MASTER'){
      Auth::guard('usuario')->logout();
      $auth = Auth::guard('usuario')->loginUsingId($data['id'],false);
      if($auth){
        Session::push('ADM_MASTER_id',$user['id']);
        Session::push('ADM_MASTER_nome',$user['nome']);
        return ['status'=>true];
      }
    }
    return ['status'=>false];
  }

  public function logofSessonCliente(){
    if(Session::has('ADM_MASTER_id')){
      Auth::guard('usuario')->logout();
      $auth = Auth::guard('usuario')->loginUsingId(Session::get('ADM_MASTER_id'),false);
      if($auth){
        Session::forget('ADM_MASTER_id');
        Session::forget('ADM_MASTER_nome');
        return ['status'=>true];
      }
    }
    return ['status'=>false];
  }



 /* public function authApp($request,$d){
  
     $credentials = ['celular'=>$d['login'],'password'=>$d['password']];
      if (!Auth::guard('paroquiano')->attempt($credentials,false)) {
          return ['status'=>false,'message'=>'Unauthorized'];
      }
      $user = Auth::guard('paroquiano')->user();
      $tokenResult = $user->createToken($user['id']);
      $token = $tokenResult->token;
      // if ($request->remember_me) {
      //     $token->expires_at = Carbon::now()->addWeeks(1);
      // }
      $token->save();
      $queryParoquia  = $this->paroquia_repo->where('id',$user['id_paroquia'])->get();

      return [
          'status'=>true,
          'id'=>$user['id'],
          'codigo'=>$user['codigo_paroquiano'],
          'nome'=>$user['nome'],
          'email'=>$user['email'],
          'id_paroquia'=>$user['id_paroquia'],
          'access_token'=>$tokenResult->accessToken,
          'token_type'=>'Bearer',
          'expires_at'=>Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
          'paroquia_latitude'=>$queryParoquia[0]['latitude'],
          'paroquia_longitude'=>$queryParoquia[0]['longitude'],
      ];
  }*/

  public function createLogAccess(){
    $dataLog =[
      'local'=>'WEB',
      'tipo'=>'',
      'id_acesso'=>Auth::guard('usuario')->id(),
      'inicio'=>date("Y-m-d H:i:s"),
      'ip'=>$_SERVER['REMOTE_ADDR'],
      'browser'=>$_SERVER['HTTP_USER_AGENT'],
    ];

    $log = $this->log_acesso_repo->create($dataLog);
    Session::push('id_log_acesso',$log['id']);
        
  }

  public function forgotPass($request){
    $email =  $request->email;
    $sigla='';
   
    $user = $this->usuario->where('email',$email);
    
    if(count($user->get())){
      $token='';
      $date =  date('Y-m-d H:i:s', strtotime('+1 days'));
      while(true){
       $token= $sigla.hash("whirlpool",uniqid(rand(),true));
       $checkToken = $this->usuario->where('token_password',$token)->get();
       if(!count($checkToken)){
        $user->update(['token_password'=>$token,'token_data'=>$date]);
         break;
       }
      }
   
      if($token){
        $user=$user->get();
        $dUserNome= $user[0]->nome;
        $dUserEmail= $user[0]->email; 
        $dUserid= $user[0]->id; 

        $dadosEmail['images'] = [];
        $dadosEmail['email'] = $email;
        $dadosEmail['assunto'] = 'Suporte op Aqui';
        $dadosEmail['mensagem'] = "<h2>Prezado(a) {$dUserNome} </h2></br> 
        <p>Você solicitou a definição de uma nova senha para o gestor op Aqui.</p>
        <a href='".env("APP_URL")."rec-auth/{$token}/".md5($date)."' >Clique aqui para mudar sua senha</a>
        <p> Agradecemos a sua participação em nosso programa!</p>";
        $this->enviarEmail($dadosEmail);



        // $this->emailRecSenha(
        //   // ['email'=>$dUserEmail,
        //   ['email'=>"vagnernegreiros@adm.com.br",
        //   'assunto'=>"Suporte op Aqui",
        //   'mensagem'=>"<h2>Prezado(a) {$dUserNome} </h2></br> 
        //   <p>Você solicitou a definição de uma nova senha para o gestor op Aqui.</p>
        //   <a href='".env("APP_URL")."/rec-auth/{$token}/".md5($date)."' >Clique aqui para mudar sua senha</a>
        //   <p> Agradecemos a sua participação em nosso programa!</p>",
        //   'link'=>[
        //     // 'label'=>'Clique aqui para criar uma nova senha',
        //     // 'url'=> $url.'/'.$token.'/'.md5(date('Y-m-d H:i:s')),
        //   ],
        //   ]);
          // $user->update(['token_password'=>$token,'date_password'=>$date]);
          // $user = DB::select("update $checkUser set password='$token' WHERE id = $dUserid");
   
          // <p>Sua nova senha é: {$novaSenha}</p>
      }
      return ['status'=>true];

    }
    return ['status'=>false];
  }


  public function enviarEmail($dados) {
    $email = $dados['email'];
    $assunto = $dados['assunto'];
    $mensagem = $dados['mensagem'];
    $copia = (!empty($dados['copia'])) ? $dados['copia'] : false;
  
    $mail = new PHPMailer(true);
  
    $mail->isSMTP();
    $mail->CharSet = 'utf-8';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
  
    $mail->Host = 'smtp.appop.com'; //gmail has host > smtp.gmail.com
    $mail->Port = 587; //gmail has port > 587 . without double quotes
    $mail->Username = 'contato@appop.com';
    $mail->Password = 'xxxxxxxxx#Z#zo';

    if (count($dados['images'])) {
     foreach ($dados['images'] as $kImg => $vImg) {
      $mail->AddStringEmbeddedImage($vImg, $kImg, "Filename{$kImg}.png", "base64", "image/png");
     }
    }
  
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->ClearAllRecipients(); //Limpar todos os que destinatiarios: TO, CC, BCC
    $mail->setFrom('contato@appop.com', 'Contato op Aqui');
    $mail->Subject = $assunto;
    $mail->MsgHTML($mensagem);
    $mail->addAddress($email, "");
    if ($copia) {
     //$mail->AddBCC("comunicacao@xxxxxx.com.br","");
     $mail->AddBCC("brunogeraldo@adm.com.br", "");
    }
    $mail->send();
   }


  public function checkToken($token){
 
      
      $date = date('Y-m-d H:m:s');
      $query = $this->usuario->where('token_password',$token)->where('token_data','>=',$date)->where('status','SIM')->get();
      
      if(count($query)==1){
        return ['status'=>true,'id'=>$query[0]['id']];
      } if(empty($user)){
        return redirect()->route('gestor.login');
      }
    //}
      return ['status'=>false];
  }

  public function newPassword($data){
    // if($this->checkToken($data['token'])){
     $password= bcrypt($data['password']);

     $user=  $this->usuario->where('id',$data['id']);
     if(count($user->get())){
      $user->update(['password'=>$password/*,'token_password'=>'','token_data'=>''*/]);
      //  $user = $user->get();
      //  $user = $user[0];
      //  $this->auth(['login'=>$user['email'],'password'=>$data['password'],'remember'=>false]);
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

  
 public function emailRecSenha($data) {
  $email = $data['email'];
  $subject = $data['subject'];
  $body = $data['body'];
  // $body = $this->template1(['body'=>$data['body'],'link'=>$data['link']]);

  $mail = new PHPMailer(true);
  $configMail = $this->getMAIL();
  $mail->isSMTP();
  $mail->CharSet = 'utf-8';
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';

  $mail->Host = 'smtp.appop.com'; //gmail has host > smtp.gmail.com
  $mail->Port = 587; //gmail has port > 587 . without double quotes
  $mail->Username = 'contato@appop.com';
  $mail->Password = 'xxxxxxxxx#Z#zo';
  
  $mail->SMTPOptions =[
   'ssl'=>[
    'verify_peer'=>false,
    'verify_peer_name'=>false,
    'allow_self_signed'=>true
   ],
  ];

  //  if (!empty($data['images'])) {
  //   foreach ($data['images'] as $kImg => $vImg) {
  //    $mail->AddStringEmbeddedImage($vImg, $kImg, "Filename{$kImg}.png", "base64", "image/png");
  //   }
  //  }
  $mail->ClearAllRecipients(); //Limpar todos os que destinatiarios: TO, CC, BCC
  // $mail->setFrom($this->mail_from_email,$this->mail_from_nome);
  $mail->setFrom('contato@appop.com', 'Contato op Aqui');
  $mail->Subject = $subject;
  $mail->MsgHTML($body);
  $mail->addAddress($email, "");
  if (!empty($data['copy'])) {
   foreach($data['copy'] as $val){
    if(is_array($val)){
     $mail->AddBCC($val['email'], $val['nome']);
    }else{
     $mail->AddBCC($val,"");
    }
   }
  }
  
  // return true;
  if(!$mail->send()) {
    return false;
  } else {
    return true;
  }

  //   if($mail->send()){
  //    return true;
  //   }
  //   return false;
 }


}
?>