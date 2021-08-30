<?php
    namespace App\Http\Controllers\Ws;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Services\ApiAuthService;
    
    // use App\User;
    // use Carbon\Carbon;
    // use Illuminate\Support\Facades\Auth;
 	
    class WsAuthController extends Controller {

      private $service;

      public function __construct(ApiAuthService $service) {
        $this->service = $service;
      }
      
     public function auth(Request $request){
       return $request->all();
      $request->validate([
        'login' => 'required',
        'password' => 'required'
        ]);
      
        $data = $request->all();
      //  $data['tipo'] ='paroquiano';

       $auth = $this->service->authApp($request,['login'=>$request->login,'password'=>$request->password]);
       // $auth = $this->login($request);

        if(count($auth)>3){
         return $auth;
        }
      return ['status'=>false];
     }

     public function recSenha(Request $request){
      $request->validate(['email' => 'required|email']);
      $f = $this->service->forgotPass($request);
      
      if($f['status']){
        return ['status'=>true,'msg'=>'Acesse o link que enviamos para o seu e-mail e mude sua senha.'];
      }
      return ['status'=>false,'msg'=>'Esse e-mail não foi encontrado, tente novamente ou entre em contato conosco.'];          
     }

    //  public function login($request)
    // {
    
    //    $credentials = ['email'=>$request->login,'password'=>$request->password];
    //     if (!Auth::guard('paroquiano')->attempt($credentials)) {
    //         return ['status'=>false,'message'=>'Unauthorized'];
    //     }
    //     $user = Auth::guard('paroquiano')->user();
    //     $tokenResult = $user->createToken($user['id']);
    //     $token = $tokenResult->token;
    //     if ($request->remember_me) {
    //         $token->expires_at = Carbon::now()->addWeeks(1);
    //     }
    //     $token->save();
    //     return [
    //         'status'=>true,
    //         'nome'=>$user['nome'],
    //         'email'=>$user['email'],
    //         'access_token'=>$tokenResult->accessToken,
    //         'token_type'=>'Bearer',
    //         'expires_at'=>Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
    //     ];
    // }

    
    }
    
    ?>