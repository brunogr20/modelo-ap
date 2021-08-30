<?php
    namespace App\Http\Controllers\Site\Intra;
    
    use Illuminate\Http\Request;
    use App\Services\ApiParoquianoService;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Site\SiteController;
    use App\Reports\ParoquiaCupomReport;
    use App\Services\ApiEstabelecimentoService;
    use App\Services\ApiParoquiaService;
    
	
    class CuponsController extends SiteController {

     private $paroquianoService;
     private $report;
     private $estabelecimentoService;
     private $paroquiaService;

     function __construct(ApiParoquianoService $paroquianoService,ParoquiaCupomReport $report,ApiEstabelecimentoService $estabelecimentoService,ApiParoquiaService $paroquiaService){
      $this->paroquianoService = $paroquianoService;
      $this->estabelecimentoService = $estabelecimentoService;
      $this->report = $report;
      $this->paroquiaService = $paroquiaService;
     }
      
      public function index(){
        $user = $this->getUserLogged();
        $data=['data'=>['userLogged'=>$user,'estabelecimentos'=>$this->estabelecimentoService->getEstabelecimentosParoquia($user['id']),'areaRestrita'=>true]];
        return view("site.intra.cupons",$data);
      }


      public function consultarParoquiano(Request $request){
        $codigo_paroquiano = $request->input("codParoquiano");
        return (array)$this->paroquianoService->getParoquiano($request);
        
      }

      public function filterCupons(Request $request){
        $data=$request->all();
        
        $r = $this->report->filter($this->getUserLogged(),$data);
        if($data['rel_type']=='VIEW'){
          return $r;
        }else if($data['rel_type']=='EXCEL'){
          if($r['status']){
        return Message::get("SUCCESS","FILE_GENERATE",['nameFile'=>$r['nameFile']]);
      }else{
        Message::get("ERROR","FILE_GENERATE");
      }
    }
  }

    public function salvarComissao(Request $request){

      $r =  $this->paroquiaService->salvarComissao($this->getUserLogged(),$request->all());
        
      if($r['status']){
        return ['status'=>true,'msg'=>"Os dados foram salvos com sucesso!"];
      }else{
        return ['status'=>false,'msg'=>'Não foi possível salvar os dados.'];
      }
    }

    public function loadComissao(Request $request){
      
      $r =  $this->paroquiaService->loadComissao($request->cupom);
        
      if(count($r)){
        return ['status'=>true,'data'=>$r];
      }else{
        return ['status'=>false];
      }
    }


    }
   
    
    ?>