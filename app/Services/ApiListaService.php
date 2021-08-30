<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\ApiBannerService
       * @see App\Services\Sys\SysService
       * Arquivo gerado em 18/06/2019
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\ApiLista;
    use App\Entities\ApiListaContato;
    use App\Entities\ApiListaPesquisaEnviada;
    use App\Entities\ApiListaSegmentacao;
    use App\Entities\ApiListaSegmentacaoOpcao;
    use App\Entities\ApiListaRelacaoContatoSegmentacao;
    use App\Entities\ApiPesquisa;
    use App\Entities\ApiPesquisasEmail;

    use Illuminate\Support\Facades\DB;
    use QrCode;
    use PHPMailer\PHPMailer\PHPMailer;
    
    class ApiListaService extends SysService{
    
      private $repo;
      private $repoListaContato;
      private $repoListaPesquisaEnviada;
      private $repoListaSegmentacao;
      private $repoListaSegmentacaoOpcao;
      private $repoListaRelacaoContatoSegmentacao;
      private $repoPesquisa;
      private $repoPesquisaEmail;
      private $url;
    
      public function __construct(ApiLista $repo, ApiListaContato $repoListaContato,ApiListaPesquisaEnviada $repoListaPesquisaEnviada,ApiListaSegmentacao $repoListaSegmentacao, ApiListaSegmentacaoOpcao $repoListaSegmentacaoOpcao,ApiListaRelacaoContatoSegmentacao $repoListaRelacaoContatoSegmentacao,ApiPesquisa $repoPesquisa,ApiPesquisasEmail $repoPesquisaEmail){
        $this->repo = $repo;
        $this->repoListaContato = $repoListaContato;
        $this->repoListaPesquisaEnviada = $repoListaPesquisaEnviada;
        $this->repoListaSegmentacao = $repoListaSegmentacao;
        $this->repoListaSegmentacaoOpcao = $repoListaSegmentacaoOpcao;
        $this->repoListaRelacaoContatoSegmentacao = $repoListaRelacaoContatoSegmentacao;
        $this->repoPesquisa = $repoPesquisa;
        $this->repoPesquisaEmail = $repoPesquisaEmail;
        $this->url = url('public').'/storage/'.'arqConteudo/arqPesquisa/';
        parent::setRepo($repo); // Setando o repositorio padrao para o Service.
      }
    
      /**Método é Implementado por seção, 
       * Não pode ser reaproveitado com Herança 
       * */
      public function loadGrid($data){
        $baseSql = [ 
        "FROM_BODY" => $this->repo->getTable(),
        "WHERE" => " status <> 'EXC'",
        ];
        return $dados = (array) GridSelectBuilder::buildTable($data, $baseSql, true);
      }

       public function getLinkPesquisa($tPesquisa,$tContato=""){
        $uri =($tContato)?$tContato.'/'.date('md'):date('md');
        return env("APP_URL").'pesquisa/q/'.$tPesquisa .'/'.$uri;
       }
       public function getQRCodePesquisa($user,$idPesquisa){
        $pesquisa = $this->repoPesquisa->where('id_empresa',$user['id_empresa'])->where('id',$idPesquisa)->where('status', 'SIM')->get();
        if(count($pesquisa)){
          $pesquisa=$pesquisa[0];
          $nome = strUrl($pesquisa['titulo']).'.png';
          QrCode::format('png')->size(350)->generate($this->getLinkPesquisa($pesquisa['id_token']), 
                                         'public/storage/arqConteudo/arqPesquisaQRCode/'.$nome);
          return ['status'=>true,'qrcode'=>'arqConteudo/arqPesquisaQRCode/'.$nome];
        }
        return ['status'=>false];
       }

      public function getListas($data) {
        $return = [];
      
        $listas = $this->repo->where('id_empresa', $data['id_empresa'])->where('status', 'SIM')
                ->orderBy('created_at', 'DESC')->get();

        foreach ($listas as $key => $value) {
         $contatos = $this->repoListaContato->where('id_lista', $value['id'])->where('status', 'SIM')->get();
         $dataInicio = $value['created_at'];
         $return[$key] = [
             'id' => $value['id'],
             'titulo' => $value['titulo'],
             'dataCadastro' => substr($dataInicio, 8, 2) . '/' . substr($dataInicio, 5, 2) . '/' . substr($dataInicio, 0, 4),
             'contatos' => count($contatos),
         ];
        }
      
        return $return;
      }
      
       public function getLista($user,$idLista) {
        $return = [];
      
        $return = $this->repo->select('id', 'titulo')
                ->where('id', $idLista)->where('id_empresa',$user['id_empresa'])->where('status', 'SIM')->orderBy('created_at', 'DESC')->get();
        if(count($return)){
           return $return[0];
        }
          return $return;
       }
      
       public function salvarLista($user,$data) {
        $verificar=null;
         if(!empty($data['id'])){
             $verificar = $this->repo->where('id_empresa',$user['id_empresa'])->where('id', $data['id'])->where('status', 'SIM');
         }

        if (!empty($data['id'])&&count($verificar->get())) {
            $return = $verificar->update(['titulo'=>$data['titulo']]);
        }else{
            $return = $this->repo->create(['id_empresa'=>$user['id_empresa'],'titulo'=>$data['titulo'],'status'=>'SIM']);
        }
        return ['status'=>true];
       }

       public function deleteLista($user, $data) {
        $return = [];
        $lista = $this->repo->where('id_empresa',$user['id_empresa'])->where('id', $data['id'])->where('status', 'SIM');
      
        $getLista = $lista->get();
        if (count($getLista)) {
         $contatos = $this->repoListaContato->where('id_lista', $getLista[0]->id)->get();
         foreach ($contatos as $val) {
          $this->repoListaContato->where('id', $val->id)->update(['status'=>'EXC']);
         }
         $lista->update(['status'=>'EXC']);
         return ['status' => true];
        }
        return ['status' => false];
       }    
      
       /// ********************************************************* CONTATO ****************************************************
           
        function montSegmentacao($idContato,$data){
            $idBlock=[];
            foreach($data as $nome => $val){
                $dSeg=[];
                $aux = explode('-',$nome);
                if($aux[0]=='segmento'&&$val){
                    $dSeg['id_seg_opcao']=$val;
                    $dSeg['id_segmentacao']=$aux[1];
                    $dSeg['id_contato']=$idContato;
                    $cad=$this->repoListaRelacaoContatoSegmentacao->create($dSeg);
                    $idBlock[]=$cad['id'];
                }
            }
            $cad=$this->repoListaRelacaoContatoSegmentacao->where('id_contato',$idContato)->whereNotIn('id',$idBlock)->delete();
        }

       public function salvarContato($user,$data) {   
        $return = 0;
        $ids = [];  
      
        $dadosCont = ['nome'=>$data['nome'],'email'=>$data['email'],'telefone'=>$data['telefone']];

        if(empty($data['id'])){
            $verificar = $this->repoListaContato->where('id_lista', $data['lista'])
                              ->where('email',$data['email'])->where('status','SIM');

            if (!count($verificar->get())) {
                $dadosCont['id_lista']=$data['lista'];
                $dadosCont['id_token'] = $this->tokenContato();
                $cad = $this->repoListaContato->create($dadosCont);
                $this->montSegmentacao($cad['id'],$data);
                return ['status'=>true];
            }
            return ['status'=>false,'msg'=>'Existe um contato com esse e-mail.'];
        }else{  
            $verifEmail = $this->repoListaContato->where('id_lista', $data['lista'])
                              ->where('id','<>', $data['id'])->where('email', $data['email'])->where('status', 'SIM')->get();
                              if(count($verifEmail)){
                                return ['status'=>false,'msg'=>'Existe um contato com esse e-mail.'];
                              }
          
            $verificar = $this->repoListaContato->where('id_lista', $data['lista'])->where('id', $data['id'])->where('status', 'SIM');

            if (count( $verificar->get())){
                $return = $verificar->update($dadosCont);
                $this->montSegmentacao($data['id'],$data);
                return ['status'=>true];
            }
        }
      
        return ['status'=>false,'Não foi possivel cadastrar esse contato.'];
       }
      
       public function deletarContato($request) {
        $return = [];
        $dados = $request->all();
      
        $contato = $this->repositorie_internauta->where('codigo', $dados['codigo_contato'])->where('status', 'SIM');
      
        $getContato = $contato->get();
        if (count($getContato)) {
         $this->repositorie_relacao_enternautas_segmentacao->where('codigo_internauta', $getContato[0]->codigo);
         $contato->delete();
        }
        return ['status' => true];
       }
      
       public function getContatosLista($idLista) {
        $return = [];
        $segmentacao = [];
        $segOpcoes = [];
        // $seg = $this->repoListaContato->where('id_lista',$idLista)->where('status', 'SIM')->get();
        // foreach ($seg as $s) {
        //  $segmentacao[$s['id']] = '';
        //  $opcoes = $this->repoListaSegmentacaoOpcao->where('id_segmentacao', $s['id'])->where('status', 'SIM')->get();
        //  foreach ($opcoes as $o) {
        //   $segOpcoes[$o['id_segmentacao']] = $s['id'];
        //  }
        // }
      
        $contatos = $this->repoListaContato->where('id_lista',$idLista)->where('status','SIM')->orderBy('created_at','DESC')->get();

        foreach ($contatos as $key => $value) {
         $return[$key] = [
             'id' => $value['id'],
             'nome' => $value['nome'],
             'email' => $value['email'],
             //  'data_cadastro'=>substr($dataInicio,8,2).'/'.substr($dataInicio,5,2).'/'.substr($dataInicio,0,4),
             'telefone' => $value['telefone'],
             'segmento' => [],//$segmentacao,
             'visivel' => 'contato',
         ];
         $relSegmento = $this->repoListaRelacaoContatoSegmentacao->where('id_contato', $value['id'])->get();
         if (count($relSegmento)) {
          foreach ($relSegmento as $v) {
            //if(!empty($segOpcoes[$v['id_seg_opcao']])){
               $return[$key]['segmento'][$v['id_segmentacao']] = $v['id_seg_opcao'];
            //}
          }
         }
        }
        return $return;
       }
      
       public function tokenContato() {
        $idToken = '';
        while (true) {
         $idToken = 'c' . hash('sha512', uniqid(rand(), true));
         $verificarToken = $this->repoListaContato->where('id_token', $idToken)->get();
         if (!count($verificarToken)) {
          break;
         }
        }
        return $idToken;
       }
      
       public function importarSegmentacao($data) {
            $return = [];
            $dSegmentacao = explode(',',$data['segmentacao']);
            foreach($dSegmentacao as $s){
                if($s){
                    $seg = $this->repoListaSegmentacao->where('id_lista',$data['lista'])->where('titulo',$s)->where('status', 'SIM');
                
                    $getSeg = $seg->get();
                    if (!count($getSeg)) {
                        $op = $this->repoListaSegmentacao->create(['id_lista'=>$data['lista'],'titulo'=>$s,'status'=>'SIM']); 
                        $return[$op['titulo']]=$op['id'];
                    }else{
                        $return[$getSeg[0]['titulo']]=$getSeg[0]['id'];
                    }
                }
             }
            return $return;
       }

       public function importarContato($data) {
        $return = [];
        $tipo = '';
        
        $id_lista = $data['lista'];
        $contato = $data['dados']['contatos'];
        $segmentecao = $data['dados']['segmentacaoOp'];
      
        $verificar = $this->repoListaContato->where('email',$contato['email'])->where('id_lista',$id_lista );
        $getContato = $verificar->get();
        if (!count($getContato)) {
         $contato['id_token'] = $this->tokenContato();
         $contato['id_lista'] = $id_lista;
         $return = $this->repoListaContato->create($contato);
         $tipo = 'cadastro';
        } else {
         $update = ['nome' => $contato['nome'], 'telefone' => $contato['telefone']];
         $verificar->update($update);
         $return=['id'=>$getContato[0]['id']];
         $tipo = 'alteracao';
        }
        if (count($segmentecao)) {
            $idBlockSegRel=[];
            foreach ($segmentecao as $keyS => $val) {
                if ($val['op_titulo']) {
                    /// verificar se existe uma opção de segmentação no banco
                    $codOpcaoSegmentacao = '';
                    $opcao = $this->repoListaSegmentacaoOpcao->where('id_segmentacao',$val['id_seg'])->where('titulo',$val['op_titulo'])->get();
                                 
                    if (count($opcao)) {
                        $codOpcaoSegmentacao = $opcao[0];
                    } else { /// cadastrar nova segmentação
                        $novaSegmentacao = $this->repoListaSegmentacaoOpcao->create(['id_segmentacao' => $val['id_seg'],'titulo'=>$val['op_titulo'],'status'=>'SIM']);
                        $codOpcaoSegmentacao = $novaSegmentacao;
                    }
                    if ($tipo == 'alteracao'){
                        $relacao = $this->repoListaRelacaoContatoSegmentacao->where('id_seg_opcao',$codOpcaoSegmentacao)->where('id_contato',$return['id'])->get();
                        if (!count($relacao)) {
                         $cad = $this->repoListaRelacaoContatoSegmentacao->create(['id_seg_opcao' => $codOpcaoSegmentacao['id'],'id_segmentacao'=>$codOpcaoSegmentacao['id_segmentacao'],'id_contato' => $return['id']]);
                         $idBlockSegRel[]=$cad['id'];
                        }
                    } else {
                        $cad = $this->repoListaRelacaoContatoSegmentacao->create(['id_seg_opcao' => $codOpcaoSegmentacao['id'],'id_segmentacao'=>$codOpcaoSegmentacao['id_segmentacao'], 'id_contato' => $return['id']]);
                        $idBlockSegRel[]=$cad['id'];
                    }
                }
         }
         ///
         $this->repoListaRelacaoContatoSegmentacao->where('id_contato',$return['id'])->whereNotIn('id',$idBlockSegRel)->delete();
        }
        return ['status'=>true,'tipo'=>$tipo];
       }
      
       ////////////////////////////////////// SEGMENTACOES ///////////////////////////////////////////////////////
         /// lista de segmentação de cada lista de contatos
       public function getSegmentacoes($idLista,$idSeg='') {
        $return = [];
      
        $dSegment = $this->repoListaSegmentacao->where('id_lista', $idLista)->where('status','SIM');
        if($idSeg){
            $dSegment->where('id', $idSeg);
        }
        $dSegment =  $dSegment->orderBy('titulo','ASC')->get();
        if (count($dSegment)) {
            $indSeg=0;
         foreach($dSegment as $seg) {
             $segOp = $this->repoListaSegmentacaoOpcao->where('id_segmentacao', $seg['id'])->where('status','SIM')->orderBy('titulo','ASC')->get();
            //  if(count($segOp)){
                 $return[$indSeg] = ['id'=>$seg['id'],'titulo'=>$seg['titulo'],'opcoes'=>[]];
                foreach ($segOp as $op) {
                  $return[$indSeg]['opcoes'][] = ['id'=>$op['id'],'titulo'=>$op['titulo']];
                }
                $indSeg+=1;
            // }
         }
        }
        return $return;
       }

       public function salvarSegmentacao($data) {
            $return = [];
            $verificar=null;
            if(!empty($data['cod'])){
                $verificar = $this->repoListaSegmentacao->where('id_lista',$data['lista'])->where('id', $data['cod'])->where('status', 'SIM');
            }

        if (!empty($data['cod'])&&count($verificar->get())) {
            $return = $verificar->update(['titulo'=>$data['titulo']]);
        }else{
            $return = $this->repoListaSegmentacao->create(['id_lista'=>$data['lista'],'titulo'=>$data['titulo'],'status'=>'SIM']);
        }
        return ['status'=>true];
       }

       public function deleteSegmentacao($data) {
        $return = [];
        $seg = $this->repoListaSegmentacao->where('id_lista',$data['lista'])->where('id', $data['id'])->where('status', 'SIM');
      
        $getSeg = $seg->get();
        if (count($getSeg)) {
         $op = $this->repoListaSegmentacaoOpcao->where('id_segmentacao', $getSeg[0]->id)->get();
         foreach ($op as $val) {
          $this->repoListaSegmentacaoOpcao->where('id', $val->id)->delete();
         }
         $seg->delete();
         return ['status' => true];
        }
        return ['status' => false];
       }

       public function salvarSegmentacaoOpcao($data) {
        $return = [];
        $verificar=null;

        if(!empty($data['id'])){
            $verificar = $this->repoListaSegmentacaoOpcao->where('id_segmentacao',$data['segmentacao'])->where('id',$data['id'])->where('status','SIM');
        }

        if (!empty($data['id'])&&count($verificar->get())){
            $return = $verificar->update(['titulo'=>$data['titulo']]);
        }else{
            $return = $this->repoListaSegmentacaoOpcao->create(['id_segmentacao'=>$data['segmentacao'],'titulo'=>$data['titulo'],'status'=>'SIM']);
        }
        return ['status'=>true];
       }

       public function deleteSegmentacaoOpcao($data) {
        $return = [];
        $seg = $this->repoListaSegmentacaoOpcao->where('id_segmentacao',$data['seg'])->where('id', $data['cod'])->where('status', 'SIM');
      
        $getSeg = $seg->get();
        if (count($getSeg)) {
         $seg->delete();
         return ['status' => true];
        }
        return ['status' => false];
       }

       public function getSegmentar($data){
         $return = [];
         $arSeg = [];
         $sql = '';
        // foreach($data as $key => $val){
        //     if(substr($key,0,4)=='seg_'&& $val){
        //         $arSeg[]=$val;
        //     }
        // }

        $sqlWhere = array();
        $sqlFrom = array();
        $n = 2;
        foreach ($_POST as $key => $val) {
         if (!in_array($key, array('acao', 'lista')) && $val) {
          $sqlWhere[] = " t1.id=t{$n}.id_contato AND t{$n}.id_seg_opcao={$val} ";
          $sqlFrom[] = ' api_listas_segmentacoes_contatos_relacao AS t' . $n;
          $n+=1;
         }
        }

        $sqlWhere = (count($sqlWhere)) ? " AND " . join(' AND ', $sqlWhere) : '';
        $sqlFrom = (count($sqlFrom)) ? " , " . join(' , ', $sqlFrom) : '';

        $query = DB::select("SELECT t1.id FROM api_listas_contatos AS t1 {$sqlFrom} "
                   . " WHERE  id_lista={$data['lista']} {$sqlWhere} "
                   . "GROUP BY t1.id");

        foreach($query as $val){
            $return[]=$val->id;
            // [
            //   'id'=>,
            // //   'nome'=>$val->nome,
            // //   'email'=>$val->email,
            // ];
        }

        return ['status'=>true,'dados'=>$return];
       }


       ////////////////////////////////////// emails ///////////////////////////////////////////////////////

       public function getListasContatos($user) {
        $return = [];

        $listas = $this->repo->where('id_empresa',$user['id_empresa'])->where('status','SIM')->orderBy('titulo', 'ASC')->get();
        $numLista=0;
        if (count($listas)) {
            foreach ($listas as $keyLis => $lista) {
                $contatos = $this->repoListaContato->where('id_lista', $lista['id'])->where('status', 'SIM')->orderBy('nome', 'ASC')->get();
                if(count($contatos)){
                    $return[$numLista] =[
                        'id'=>$lista['id'],
                        'titulo'=>$lista['titulo'],
                        'contatos'=>[],
                        'segmentacoes'=>[],
                    ];
                    foreach ($contatos as $contato) {
                    $return[$numLista]['contatos'][] = ['id'=>$contato['id'],'nome'=>$contato['nome'],'email'=>$contato['email']];
                    }
                    $seg =$this->getSegmentacoes($lista['id']);
                    if(count($seg)){
                        $return[$numLista]['segmentacoes']=$seg;
                    }
                    $numLista+=1;
             }
            }
          }
          return $return;
       }

       public function getListaPesquisaEnviado($user, $pesquisa) {
        $res = [];
        $return = [];
        $dInter = [];
        $dLista = [];
        $listas = $this->repo->where('id_empresa',$user['id_empresa'])->where('status','SIM')->orderBy('titulo', 'ASC')->get();
        ////
        if (count($listas)) {
         foreach ($listas as $lista) {
          $dLista[$lista['id']] = $lista['titulo'];
          $internautas = $this->repoListaContato->where('id_lista', $lista['id'])->where('status', 'SIM')->get();
          foreach ($internautas as $inter) {
           $dInter[$inter['id']]=['nome'=>$inter['nome'],'email'=>$inter['email']];
          }
         }
        }
      
        $lEnviado = $this->repoListaPesquisaEnviada->where('id_pesquisa', $pesquisa['id'])->where('status', 'SIM')->orderBy('updated_at', 'DESC')->get();
        $indLista = 0;

        foreach ($lEnviado as $key => $value) {
         $status_resposta = ($value['status_resposta'] == 'CONCLUIDO') ? true : false;
         if (empty($res[$value['id_lista']])) {
          $res[$value['id_lista']] = [
              'titulo' => $dLista[$value['id_lista']],
              'id' => $value['id_lista'],
              'pendentes' => 0,
              'concluidos' => 0,
            ];
        }
        if (!$status_resposta) {
            $res[$value['id_lista']]['pendentes'] +=1;
        } elseif ($status_resposta) {
            $res[$value['id_lista']]['concluidos'] +=1;
        }
        
        $res[$value['id_lista']]['contatos'][] = [
            'id' => $value['id'],
            'id_contato' => $value['id_contato'],
            'email' => $dInter[$value['id_contato']]['email'],
            'nome' => $dInter[$value['id_contato']]['nome'],
            'tipo_envio' => $value['tipo_envio'],
             'status_resposta' => $status_resposta,
             'receber_email' => !$status_resposta,
         ];
        }
      
        $num = 0;
        $dGeral = ['pendentes'=> 0,'concluidos'=>0,'totalLista' =>0];
        foreach ($res as $key => $value) {
            $dGeral['concluidos'] +=$value['concluidos'];
            $dGeral['pendentes'] += $value['pendentes'];
            $return[$num++] = $value;
        }
        $dGeral['totalLista']=$dGeral['concluidos']+$dGeral['pendentes'];
        return ['dGeral' => $dGeral, 'dListas' => $return];
       }
      
       public function listaDispEmail($codUsuario) {
        $return = [];
      
        $listas = $this->repositorie
                ->where('codigo_usuario', $codUsuario)
                ->where('status', 'SIM')
                ->orderBy('created_at', 'DESC')
                ->get();
        $indLista = 0;
        foreach ($listas as $key => $value) {
         $contatos = $this->repositorie_internauta->where('codigo_lista', $value['codigo'])->where('status', 'SIM')->get();
         if (count($contatos)) {
          //   $dataInicio = $value['created_at'];
          $returnContatos = [];
          $returnSegmentacao = [];
          foreach ($contatos as $keyCont => $valCont) {
           //segmento
           $segOpcoes = [];
           $contSedment = $this->repositorie_relacao_enternautas_segmentacao->where('codigo_internauta', $valCont['codigo'])
                   ->get();
           foreach ($contSedment as $cSeg) {
            $segOpcoes[] = $cSeg['codigo_seg_opcao'];
           }
      
           $returnContatos[$keyCont] = [
               'codigo' => $valCont['codigo'],
               'nome' => $valCont['nome'],
               'segOpcoes' => $segOpcoes,
               'mostarNaLista' => true,
               'receber_email' => true,
           ];
          }
          //// segmentacao
          $indSeg = 0;
          $dSeg = $this->repositorie_segmentacao->where('codigo_lista', $value['codigo'])->where('status', 'SIM')->get();
          if (count($dSeg)) {
           foreach ($dSeg as $seg) {
            $dSegOpcoes = $this->repositorie_segmentacao_opcao->where('codigo_segmentacao', $seg['codigo'])->where('status', 'SIM')->get();
            if (count($dSegOpcoes)) {
             $returnSegmentacao[$indSeg] = ['titulo' => $seg['titulo'], 'codigo' => $seg['codigo'], 'opcoes' => [], 'resposta' => ''];
             foreach ($dSegOpcoes as $segOp) {
              $returnSegmentacao[$indSeg]['opcoes'][] = ['titulo' => $segOp['titulo'], 'codigo' => $segOp['codigo']];
             }
             $indSeg+=1;
            }
           }
          }
      
          $return[$indLista++] = [
              'codigo' => $value['codigo'],
              'titulo' => $value['titulo'],
              'segmentacao' => $returnSegmentacao,
              'contatos' => $returnContatos,
          ];
         }
        }
      
        return $return;
       }
      
        public function enviarEmails($user,$data) {
           
          //  $return;
            $dados = $data;
            $idPesquisa =$dados['pesquisa'];
            $pesquisa = $this->repoPesquisa->where('id',$idPesquisa)->where('id_empresa',$user['id_empresa'])
                                           ->where('status', 'SIM')->get();
            if (count($pesquisa)) {
                $pesquisa = $pesquisa[0];
                //
                $imagem='';
                $assunto = 'PESQUISA - ' . $pesquisa['titulo'];
                $texto = 'Perticipe da nossa pesquisa.';
                $assinatura ='';
                $emailPerson =  $this->repoPesquisaEmail->where('id_pesquisa',$idPesquisa)->where('tipo','PRINCIPAL');
                if(count($emailPerson->get())){
                    $em = $emailPerson->get();
                    $em =$em[0];
                    $assunto = (!empty($em['titulo_email']))?$em['titulo_email']:$assunto;
                    if(!empty($em['imagem_email'])){
                        $imagem = $this->url.$em['imagem_email'];
                    }
                    $assinatura = (!empty($em['assinatura_email']))?$em['assinatura_email']:$assinatura;
                    $texto = (!empty($em['texto_email']))?$em['texto_email']:$texto;
                }
        
                $lista_contatos = $this->repoListaContato->whereIn('id', $dados['contatos'])->where('status','SIM')->get();
            
                $dadosEmail = ['email'=> "",'assunto'=>$assunto,'mensagem' =>'','images'=>[]];
            
            
                foreach ($lista_contatos as $value) {
                    // $linkPesquisa = "{$this->bUrl}site/pesquisa/{$pesquisa['id_token']}/{$value['id_token']}/" . date('my');
                    $linkPesquisa = $this->getLinkPesquisa($pesquisa['id_token'],$value['id_token']);
                    $tratamento='';
                    if($value['nome']){
                      $nomeTratamento = explode(' ',$value['nome']);
                      $tratamento='Prezado(a) '.$nomeTratamento[0].', <br/>';
                    }
            
                    $bodyEmail='<table width="80%">';
                    // $bodyEmail .= $assunto. "<br/>{$imagem}<br/>".$texto;
                    $bodyEmail .= "
                        <tr><td>
                        <p style='text-align:center;font-size:16px;'>{$tratamento}{$texto}</p>
                        </td></tr>";

                    $bodyEmail .= "
                    <tr><td>
                        <p style='text-align:center;font-size:16px;'>
                            <a href='{$linkPesquisa}' ><button style='background:#204d74;color:#fff;font-size:16px;padding:10px'>Clique aqui para responder a pesquisa</button></a>
                        </p>
                    </td></tr>";
                    
                    if($imagem){
                        $bodyEmail .= "<tr><td><img style='display:table;margin:auto;width: 400px;' src='".$imagem."'></td></tr>";
                    }
                    if ($assinatura){
                        $bodyEmail .= "
                        <tr><td>
                          <p style='text-align:center;font-size:16px;'>".$assinatura."</p>
                        </td></tr>";
                    }
                    $bodyEmail .= '
                    </table>';
                    $dadosEmail['mensagem'] =$bodyEmail;
                    $dadosEmail['email'] = $value['email'];
                    $this->enviarEmail($dadosEmail);
                
                    $cadastrado = $this->repoListaPesquisaEnviada->where('id_pesquisa', $pesquisa['id'])
                    ->where('id_lista', $value['id_lista'])->where('status', 'SIM')->where('id_contato', $value['id']);

                    $retornCad = $cadastrado->get();
                
                    if (!count($retornCad)) {
                       // dd($value['id']);
                        $cadastrado->create([
                            'id_pesquisa' => $pesquisa['id'],
                            'id_lista' => $value['id_lista'],
                            'id_contato' => $value['id'],
                            'status_resposta' => 'PENDENTE',
                            // 'tipo_envio' => $dados['tipo_envio'],
                            'total_enviado' => 1,
                            'status' => 'SIM',
                        ]);
                    } else {
                        $cadastrado->update(['total_enviado' => (int) $retornCad[0]['total_enviado']+=1]);
                    }
                }
            }
        
            return ['status' => true];
        } 
      
       public function enviarNovosEmails($request) {
        $dados = $request->all();
        $pesquisa = $this->repoPesquisa->where('id', $dados['pesquisa'])->where('status', 'SIM')->get();
      
        if (count($pesquisa)) {
             $pesquisa = $pesquisa[0];
         //
         $contatos=[];
         foreach (json_decode($dados['contatos']) as $key => $val) {
            $contatos[] = $val;
         }
         
          $lista_contatos = $this->repoListaContato->whereIn('id', $contatos)->where('status','SIM')->get();
      
         $dadosEmail = ['email' =>"",'assunto'=>$dados['assunto'],'mensagem' =>'','images'=>[]];
         $imagem ='';
         $d=['imagem'=>''];
         $this->do_upload($request, $d,['dir'=>'arqPesquisa']);
         if(!empty($d['imagem'])){
            $imagem = $this->url.$d['imagem'];
         }
      
         foreach ($lista_contatos as $value) {
          $linkPesquisa =  $this->getLinkPesquisa($pesquisa['id_token'],$value['id_token']);
          $tratamento='';
          if($value['nome']){
            $nomeTratamento = explode(' ',$value['nome']);
            $tratamento='Prezado(a) '.$nomeTratamento[0].', <br/>';
          }
          $dadosEmail['mensagem'] = '<table width="80%">';
          $dadosEmail['mensagem'] .= "
                        <tr><td>
                           <p style='text-align:center;font-size:16px;'>{$dados['mensagem']}</p>
                        </td></tr>";

          $dadosEmail['mensagem'] .= "<tr><td>
                          <p style='text-align:center;font-size:16px;'>
                            <a href='{$linkPesquisa}' >
                                <button style='background:#204d74;color:#fff;font-size:16px;padding:10px'>
                                    Clique aqui para responder a pesquisa
                                </button>   
                            </a>
                          </p> 
                    </td></tr>";
                    if($imagem){
                        $dadosEmail['mensagem']  .= "<tr><td><img style='display:table;margin:auto;width:400px' src='".$imagem."'></td></tr>";
                    }
                    if ($dados['assinatura']){
                      $dadosEmail['mensagem']  .= "
                        <tr><td>
                          <p style='text-align:center;font-size:16px;'>".$dados['assinatura']."</p>
                        </td></tr>";
                    }
          $dadosEmail['mensagem'] .=  '</table>';


          $dadosEmail['email'] = $value['email'];
          $this->enviarEmail($dadosEmail);

          $cadastrado = $this->repoListaPesquisaEnviada
                  ->where('id_pesquisa', $pesquisa['id'])
                  ->where('id_lista', $value['id_lista'])
                  ->where('status', 'SIM')
                  ->where('id_contato', $value['id']);
         $retornCad = $cadastrado->get();
          $cadastrado->update(['total_enviado' => (int) $retornCad[0]['total_enviado']+=1]);
         }
         return ['status' =>true];
        }
      
        return ['status' => false];
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
        return $mail->send();
       }

    }
    ?>