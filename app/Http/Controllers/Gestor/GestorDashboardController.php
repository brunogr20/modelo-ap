<?php
    namespace App\Http\Controllers\Gestor;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Storage;
    use App\Services\ApiInfoMenuService;
    use App\Services\ApiPesquisaService;
    use App\Services\ApiPerguntaService;
    use App\Services\ApiDashboardService;
    use App\Reports\PesquisaReport;
	
    class GestorDashboardController extends GestorController {

      private $infoMenu;
      private $pesquisa_servive;
      private $pergunta_service;
      private $dashboard_service;
      private $report;

      public function __construct(ApiInfoMenuService $infoMenu, ApiPesquisaService $pesquisa_servive,
      PesquisaReport $report,ApiPerguntaService $pergunta_service, ApiDashboardService $dashboard_service
       ) {
        $this->infoMenu =$infoMenu;
        $this->pesquisa_servive =$pesquisa_servive;
        $this->report =$report;
        $this->pergunta_service =$pergunta_service;
        $this->dashboard_service =$dashboard_service;
  
      }
      
      public function index($idPesquisa='',$aba='indicadores',$max=''){
       $user = $this->getUserLogged();
       $pesquisa =[];
       $pesquisas = $this->pesquisa_servive->getPesquisasHome($user);
       if(!$idPesquisa && count($pesquisas)){
        $idPesquisa =$pesquisas[0]['id'];
       }
       if(!$idPesquisa){
        return redirect('pesquisa/lista');
       }
      //  dd($idPesquisa);
       $perguntas = $this->pergunta_service->getPerguntasDashboard($idPesquisa);
       
       // if(count($pesquisas)){
        $pesquisa = $this->pesquisa_servive->getPesquisa($user,$idPesquisa);
       // }
     
       if(count($pesquisa)){
       $dataFiltros = $this->dashboard_service->getFitros($user,['pesquisa'=>$pesquisa['id']]);
       // dd($dataFiltros);
       $dashboard = $this->dashboard_service->getDataDashboard(
         ['filtros'=>$dataFiltros['filtros'],'pesquisa'=>$idPesquisa,'mensuracao'=>$dataFiltros['mensuracao']]
        );
      }else{
        $dataFiltros=[];
        $dashboard=[];
      }
      //  $totalEntrevistas = $this->dashboard_service->getTotalEntrevistas($idPesquisa);

       $selectP =  $this->pesquisa_servive->buscarPerquisas($user,[]);
        // dd($this->dashboard_service->getTotalEntrevistas($pesquisa['id']));
       $data =['data'=>[
        'userLogged'=> $this->getUserLogged(),
        'infoMenu'=>$this->infoMenu->getInfoMenu($user),
        'grupoLink'=>'dashboard',
        'pesquisas'=>$pesquisas,
        'selectPesquisas'=>$selectP['data'],
        'pesquisa'=>$pesquisa,
        'dashPerguntas'=>$perguntas,
        'dashboard'=>$dashboard,
        'totalEntrevistas'=>(!empty($dashboard['totalEntrevistados']))?$dashboard['totalEntrevistados']:0,
        'dataFiltros'=>$dataFiltros,
        'aba'=>$aba,
        'zoom'=>($max=='zoom')?'SIM':'',
       ]];
      //  dd( $data);
        // $data = array_merge_recursive($data,$this->data);
        
        return view("gestor.home-dashboard", $data);
      }

      public function buscarPerquisas(Request $request){
        $data = $request->all();
        $user = $this->getUserLogged();

        $r = $this->pesquisa_servive->buscarPerquisas($user,$data);

        if($r['status']){
          return ['status'=>true,'msg'=>'','data'=>$r['data']];
          // return Storage::download($r['nameFile']);
        }
        return ['status'=>false,'msg'=>'Não foi possível gerar o excel'];
      }
      public function alterarIndicadores(Request $request){
         $user = $this->getUserLogged();
         $data = $request->all();
         $return = $this->dashboard_service->alterarIndicadores($user,$data);

         if($return['status']){
           return ['status'=>true];
         }
         return ['status'=>false];
      }

      public function filtros(Request $request){
       $data = $request->all();
       $user = $this->getUserLogged();
       $this->dashboard_service->alterarFitros($user, $data);
       $return = $this->dashboard_service->getDataDashboard($data,false);
       
       return  ['status'=>true,'dashboard'=>$return];
      }


      public function gerarPesquisaExcel(Request $request) {
        $data=$request->all();
        $r = $this->report->filter($data);
        
        if($r['status']){
          return ['status'=>true,'msg'=>'O excel foi gerado','nome'=>$r['nameFile']];
          // return Storage::download($r['nameFile']);
        }
        return ['status'=>false,'msg'=>'Não foi possível gerar o excel'];
      }

      public function baixarPesquisaExcel($nomeFile) {
        // 'arqConteudo/tmpRel/'
        // $r = $this->report->filter(['pesquisa'=>4,'tipoExcel'=>'','localizacao'=>'false']);
        // dd($nomeFile);
        // return Storage::download('arqConteudo/tmpRel/teste-dip-06-06-2020_20200626_095423.xlsx');
        if($nomeFile){
          // return Storage::download('arqConteudo/arqListaModelo/modelo_importar_contatos.csv');
          return Storage::download('arqConteudo/tmpRel/'.$nomeFile);
        }else{
          return ['O arquivo não foi encontrado.'];
        }
      }
    
    }
    
    ?>