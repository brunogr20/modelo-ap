<?php
namespace App\Http\Controllers\ZP\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\ZP\Sys\SysController;
use App\Services\Sys\SysCoreService;
use App\Services\Sys\SysUsuarioService;
use App\Services\Sys\SysPerfilService;
use App\ZP\Message;
class SysUsuarioController extends SysController {

    protected $service;
    protected $perfil_service;
    protected $core_service;

    function __construct(SysCoreService $core_service, SysUsuarioService $service,SysPerfilService $perfil_service){
        parent::__construct($core_service);
        $this->core_service = $core_service;
        $this->service = $service;
        $this->perfil_service = $perfil_service;
    }

    public function index(){
        $page = $this->dataPage();
        $page['imports'] = ['dataTable','crud'];
        $page['data'] = ['perfis'=>$this->perfil_service->getPerfis()];
        return view('zp.sections.sys.usuario',['page'=>$page]);
    }
 
    public function create(Request $request){
        if(!$this->service->isUniqueEmail($request->email)){
            return Message::get("INFO","USER_ALREADY_EXISTS");
           }
        $r = $this->service->create($request);
        if($r['status']){
            return Message::get("SUCCESS",["data"=>$r['data'],"msg"=>"Registro inserido com sucesso."]);
        }else{
            return Message::get("ERROR","INSERT");
        }
    }

    public function update(Request $request){
        if(empty($request->id)){
            return Message::get("INFO","NOT_AVAILABLE");
        }
        if(!$this->service->isUniqueEmail($request->email,$request->id)){
            return Message::get("INFO","USER_ALREADY_EXISTS");
        }
        $r = $this->service->update($request);
        if($r['status']){
            return Message::get("SUCCESS",["data"=>$r['data'], "msg"=>"O registro foi alterado com sucesso."]);
        }else{
            return Message::get("ERROR","UPDATE");
        }
    }

    public function customTema(Request $request){
        $user = $this->core_service->getUserLogged();
        $data =  $request->all();
        $r=$this->service->customTema($user,$data);
        if($r['status']){
          return Message::get("SUCCESS", "TEMA"); 
        }
        return Message::get("ERROR", "TEMA"); 
    }
    public function meuPerfil(){
        $page = $this->dataPage();
        $page['imports'] = ['crud'];
        $page['data'] = [];
        return view('zp.sections.sys.meu-perfil', ['page' => $page]);
    }

    public function ajax(Request $request){
        if ($request->action = 'update_password') {
            if ($request->id && $request->pass) {
                if ($this->service->newPassword($request->id, $request->pass))
                return Message::get("SUCCESS","USER_UPDATE_PASS");
            }
            return Message::get("ERROR","USER_UPDATE_PASS");
        }
    }

    public function userDeleteImage(Request $request){
       $user = $this->core_service->getUserLogged();
       $user['name']=$request->name;
       $r=$this->service->dFile($user);
       if($r['status']){
         return Message::get("SUCCESS", "DELETE_IMAGE"); 
       }
       return Message::get("ERROR", "UPDATE"); 
    }
    public function userUpdate(Request $request){
        $data = $request->all();
        if (!empty($data['pass'])) {
            $user = $this->core_service->getUserLogged();
            if ($this->service->newPassword($user['id'], $request->pass))
                return Message::get("SUCCESS", "UPDATE");
        }
        if (!empty($data['imagem'])) {
            $user = $this->core_service->getUserLogged();
            $request->id = $user['id'];
            if($this->service->userUpdate($request)){
                return Message::get("SUCCESS", "UPDATE");
            } 
        }
        return Message::get("ERROR", "UPDATE");
    }    
}

?>