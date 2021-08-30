<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\DB;
use App\Entities\Sys\SysSecoesAcesso;
use Closure;
use function GuzzleHttp\json_encode;

class AuthUsuario{

    private $repo_secoes_acesso;

    public function __construct(AuthFactory $auth,SysSecoesAcesso $repo_secoes_acesso)  {
        $this->auth = $auth;
        $this->repo_secoes_acesso = $repo_secoes_acesso;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */ 
    public function handle($request, Closure $next,$guard = null){

      if(!in_array($guard,['usuario'])){
        return redirect()->route('gestor.login');  
      }

      $user = $this->auth->guard($guard)->user();
      if(empty($user)){
        return redirect()->route('gestor.login');
      }else{
        return $next($request); 
      }
      
      //   $segments = [];
      //   $typeDownload=false;
      //   $method = $request->method();
      //    $permissionUri = ['home','anotation','user-custom-tema','meu-perfil']; //sempre terá permissão
      //    $i=1;
      //    while(true){
      //       if(empty($request->segments()[$i])){break;}
      //       if($request->segments()[$i]=='download-f'||$request->segments()[$i]=='download-r'){
      //           $typeDownload=true;
      //           break;
      //       }
      //       $segments[]=$request->segments()[$i++];
      //   }
      //  $user = $this->auth->guard('zp')->user();
      //  if(!empty($user)){
      //  $perfil = DB::select("SELECT * FROM sys_perfis WHERE id={$user['id_perfil']} AND status='SIM' ");
      //    if(count($perfil)==1){
      //       $perfil=$perfil[0];
      //           $uri = '';
      //           $uri2 = '';
      //           $uri3 = '';
      //           if($method=='GET'){
      //            $uri = implode('/',$segments);
      //             if(in_array($uri,$permissionUri)){
      //               return $next($request);
      //             }
      //           }
      //           if($method=='POST'){
      //               $uri = $segments[0];
      //               $uri2 = (!empty($segments[1]))?$uri.'/'.$segments[1]:'';
      //               $uri3 = (!empty($segments[2]))?$uri.'/'.$segments[1].'/'.$segments[2]:'';
      //               if(in_array($uri,$permissionUri)||in_array($uri2,$permissionUri)||in_array($uri3,$permissionUri)){
      //                 return $next($request);
      //               }     
      //           }
      //            //  verificar seção com 2 atts ()
      //           $section = ($uri3)? $this->getSection($perfil,$uri3):[];
      //           $section = ($uri2&&!count($section))?$this->getSection($perfil,$uri2):$section ;
      //           $section = (!count($section))?$this->getSection($perfil,$uri):$section ;
      //           // se encontrou uma seção
      //           if(count($section)){
      //             if($method=='GET'&&!$typeDownload){
      //               $this->newAccess($section['id']);
      //               return $next($request); 
      //             }else{
      //               $permission = DB::select("SELECT * FROM sys_permissoes WHERE id_perfil={$user['id_perfil']} AND id_secao={$section['id']} ");
      //               if(count($permission)){
      //                 if($typeDownload&&in_array($permission[0]->nivel,['C','B','A'])){
      //                   return $next($request); 
      //                 }else{
      //                   preg_match_all('/\//',$section['uri'], $matches);
      //                   $pMtd =count($matches[0])+1;
      //                   if(!empty($segments[$pMtd])){
      //                     $lavelList =['C'=>['load_grid','load_form','ajax','filter']];
      //                     $lavelList['B']=array_merge(['create','update','update_status','reorder_grid'],$lavelList['C']);
      //                     $lavelList['A']=array_merge(['delete','delete_file'],$lavelList['B']);
      //                     if(in_array($segments[$pMtd],$lavelList[$permission[0]->nivel])){
      //                       return $next($request); 
      //                     }
      //                   }
      //                 }
      //               }
      //             }
      //           }
      //    }
      //   }
      //   if($request->expectsJson()) {
      //      return response(['status'=>false,'msg'=>'Ação não autorizada.']);
      //   }else{
      //       if(empty($user)){
      //           return redirect()->route('zp.login');
      //       }
      //       abort(403,"Acesso não autorizado");
      //    }
    }

  //  private function getSection($perfil,$uri){
  //      $sql = ($perfil->tipo!='adm_ADMIN')?" AND t2.nivel IN('A','B','C') ":'';
  //      $r = DB::select("SELECT t1.* FROM sys_secoes AS t1 JOIN sys_permissoes AS t2 "
  //      ." ON t1.id=t2.id_secao AND t2.id_perfil={$perfil->id}"
  //      ." WHERE t1.status='SIM' AND t1.uri='{$uri}' $sql");
  //      return (count($r))?(array)$r[0]:[];
  //   }

  //  private function newAccess($idSection){
  //   $user = $this->auth->guard('zp')->user();
  //   $query = $this->repo_secoes_acesso->where('id_usuario',$user['id'])->where('id_secao',$idSection);
  //   $accesses= $query->get();
  //    if(count($accesses)){
  //       $query->update([
  //           'acessos'=>1+(int)$accesses[0]['acessos'],
  //           ]);      
  //    }else{
  //        $this->repo_secoes_acesso->create([
  //           'id_usuario'=>$user['id'],
  //           'id_secao'=>$idSection,
  //           'acessos'=>1,
  //        ]);
  //    }
  //   }
  }
