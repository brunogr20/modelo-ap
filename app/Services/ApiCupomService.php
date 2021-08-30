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
    use App\Entities\ApiPlano;
    use App\Entities\ApiCupom;
    use App\Entities\ApiPesquisaPlano;
    
    class ApiCupomService extends SysService{
    
      private $repo;
      private $repoPlano;
      private $repoPesquisaPlano;
    
      public function __construct(ApiCupom $repo,ApiPlano $repoPlano,ApiPesquisaPlano $repoPesquisaPlano){
        $this->repo = $repo;
        $this->repoPlano = $repoPlano;
        $this->repoPesquisaPlano = $repoPesquisaPlano;
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


      public function getCupons(){
        $return=[];
        $planos=[];
        $qPlanos = $this->repoPlano->where('tipo','<>','GRATUITO')->where('status','<>','EXC')->get();
        foreach($qPlanos as $k =>  $val){
          $planos[$val['id']]=$val['titulo'];
        }
        $cupons = $this->repo->where('status','SIM')->orderBy('codigo','ASC')->get();
        foreach($cupons as $k =>  $val){
          $return[$k]=$val;
          $return[$k]['plano']= (!empty($planos[$val['id_plano']]))?$planos[$val['id_plano']]:'';
          $return[$k]['utilizados']= $this->repoPesquisaPlano->where('id_cupom', $val['id'])->count();
        }
        return $cupons;
      }
      public function getCupom($id){
        $res=[];
        $cupom= $this->repo->where('id',$id)->where('status','SIM')->get();
        if(count($cupom)){
          $cupom=$cupom[0];
          $res=[
            'id'=>$cupom['id'],
            'codigo'=>$cupom['codigo'],
            'id_plano'=>$cupom['id_plano']?$cupom['id_plano']:'',
            'valor'=>number_format($cupom['valor'], 2, ',', '.'),
            'quantidade'=>$cupom['quantidade'],
            'data_validade'=>retrieveDatetime($cupom['data_validade']),
            'status'=>$cupom['status'],
          ];
        }
        return $res;
      }


      public function checkCupom($codigo,$plano){
        $dataAtual=date('Y-m-d');
        $cupom = $this->repo->where('codigo',$codigo)->where('data_validade','>=',$dataAtual)->where('status','SIM')->get();
      
        if(count($cupom)){
        $cupom=$cupom[0];

        if(!empty($cupom['id_plano'])&&$cupom['id_plano']!=$plano){
          return ['status'=>false];
        }
        $totalUsado = $this->repoPesquisaPlano->where('id_cupom', $cupom['id'])->count();

        if($totalUsado < $cupom['quantidade']){
          return ['status'=>true,'id'=>$cupom['id'],'valor'=>$cupom['valor']];
        }

      }
        
        
        return ['status'=>false];
      }

      public function salvarCupom($data) {
        
        $limpar_valor = str_replace('.','',$data['valor']);
        //$limpar_valor = str_replace(',','',$limpar_valor);

        $salvar =[
          'id_plano'=>$data['id_plano'],
          'valor'=>$limpar_valor,
          'quantidade'=>$data['quantidade'],
          'data_validade'=>$data['data_validade'],
          'status'=>$data['status'],
        ];

        $salvar['data_validade']=substr($salvar['data_validade'],6,4).'-'.substr($salvar['data_validade'],3,2).'-'.substr($salvar['data_validade'],0,2);

        if(!empty($data['id'])){
          $user = $this->repo->where('id', $data['id'])->where('status', 'SIM');
          if ($user->update($salvar)) {
            return['status' => true];
          }
          }else{
          $salvar['codigo']=$data['codigo'];
          $this->repo->create($salvar);
          return['status' => true];

        }

        return['status' => false];
      }

      public function checkCodCupom($codigo){
        $cupom = $this->repo->where('codigo',$codigo)->get();
        return count($cupom)?true:false;
      }


      public function deleteCupom($id){
        $this->repo->where('id',$id)->update(['status'=>'EXC']);
        return ['status'=>true];
      }

    
    }
    ?>