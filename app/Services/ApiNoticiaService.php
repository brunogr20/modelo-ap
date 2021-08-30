<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\ApiNoticiaService
       * @see App\Services\Sys\SysService
       * Arquivo gerado em 29/05/2019
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\ApiNoticia;
    
    class ApiNoticiaService extends SysService{
    
      private $repo;
    
      public function __construct(ApiNoticia $repo){
        $this->repo = $repo;
        parent::setRepo($repo); // Setando o repositorio padrao para o Service.
      }
    
      /**Método é Implementado por seção, 
       * Não pode ser reaproveitado com Herança 
       * */
      public function loadGrid($data){
        $functions = [
          'data'=>['retrieveDatetime'],
        ];
        $baseSql = [ 
     
     "FROM_BODY" => $this->repo->getTable(),
     "WHERE" => " status <> 'EXC'",
     "ORDER BY" => " data DESC",
     ];
        return $dados = (array) GridSelectBuilder::buildTable($data, $baseSql,$functions, true);
      }

      public function getNoticiasChamadas($l){
        
       return  $this->repo->where('status','SIM')->where('imagem','<>','')->where('data','<=',date('Y-m-d'))
                    ->orderBy('created_at','DESC')->limit(3)->get();
      }

      public function getNoticia($id=''){
        $r = null;
        if($id){
         $query = $this->repo->where('id',$id)->where('status','SIM')->get();
        }else{
         $query = $this->repo->where('status','SIM')->where('data','<=',date('Y-m-d'))->orderBy('data','DESC')->limit(1)->get();
        }
        if(count($query)){
          $r = $query[0];
        }
        return $r;
      }

      public function getPagination($start,$limit,$idBlock=''){
        $query =  $this->repo->where('status','SIM')->where('data','<=',date('Y-m-d'))->orderBy('data','DESC');
        if($idBlock){
         $query->where('id','<>',$idBlock);
        }
        $pos = 0;
        if($start>1){
          $pos = ($start * $limit)-($limit-1);
        }
        $query->take($limit)->skip($pos);

        return $query->get();

      }

      public function getIndexPaginacao($pgCount){
        return $this->repo->where('status','SIM')->paginate($pgCount);
      }

    
    }
    ?>