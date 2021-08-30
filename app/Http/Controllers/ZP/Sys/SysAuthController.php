<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Services\Sys\SysAuthService;
use Auth;
use App\Http\Controllers\Controller;
use App\ZP\Message;
class SysAuthController extends Controller {

  protected $service;
  
  public function __construct(SysAuthService $service){
     $this->service = $service;
  }

  public function index(Request $request){
     if(!empty(Auth::guard('zp')->user())){
      $this->service->createLogAccess();
       return redirect()->route('zp.home');
     }
    return view('zp.sections.sys.login',['page'=>['imports'=>[],'type'=>'login','url'=>$request->url()]]);
  }

  public function auth(Request $request){
    $request->validate([
      'login' => 'required',
      'password' => 'required'
      ]);
      $credentials = [ 'login'=> $request->login,'password' => $request->password, 'remember' => $request->remember ];
      if(!preg_match('/@/',$credentials ['login'])){$credentials['login'].='@adm.com.br';};
      $auth = $this->service->auth($credentials);
      if($auth['status']){
        return ['status'=>true,'url'=>substr($request->url(),0,-4).'home'];
      }
    return Message::get("INFO","INCORRET_LOGIN_DATA");
  }

  public function forgotPass(Request $request){
    $request->validate(['email' => 'required|email']);
    $f = $this->service->forgotPass($request);
    if($f['status']){
      return Message::get("SUCCESS","MAIL_LINK_SEND");
    }
    return Message::get("INFO","MAIL_NOT_FOUND");
  }

  public function newPassword(Request $request){
    $request->validate([
      'password' => 'required|min:6'
      ]);
      $data = ['password'=>$request->password,'token'=>$request->token];
      if(strlen($data['token'])!=128){
       return ['status'=>false,'Falha.'];
      }
      if($this->service->checkToken($request->token)){
        $auth = $this->service->newPassword($data);
        if($auth['status']){
          return ['status'=>true,'url'=>substr($request->url(),0,-8).'home'];
        }
      }else{
        return Message::get("INFO","USER_UPDATE_PASS");
      }
  }

  public function checkToken(Request $request,$token,$key){
     $valid = $this->service->checkToken($token);
    if(!$valid){
     abort(404);
    }
    $url = $request->url();
    $url = str_replace('/'.$token,'',$url); 
    $url = str_replace('/'.$key,'',$url); 
    $url = str_replace('/user-valid','',$url); 
    return view('zp.sections.sys.login',['page'=>['imports'=>[],'token'=>$token,'type'=>'new-password','url'=>$url]]);
  }

  public function logout(Request $request){
    $l = $this->service->logout();
    if($l['status']){
      return redirect()->route('zp.login');
     }
     return Message::get("ERROR","LOGOUT");
  }

}

?>