<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\ApiEstabelecimentoFuncionarioService
       * @see App\Services\Sys\SysService
       * Arquivo gerado em 15/05/2019
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\ApiPlano;

    use DB;
    
    class ApiHomeService extends SysService{
    
      private $repo;

    
      public function __construct(ApiPlano $repo){
        $this->repo = $repo;

        // parent::setRepo($repo); // Setando o repositorio padrao para o Service.
      }
    
      /**Método é Implementado por seção, 
       * Não pode ser reaproveitado com Herança 
       * */
      public function loadGrid($data){
        $functions = [
          //'field'=>['function'],
        ];
        $baseSql = [ 
          "SELECT"=>["t1"=>["id","status","nome",'tipo',"email","cpf","senha",],"t2"=>["nome"=>"id_estabelecimento"],],
          "FROM_BODY" =>$this->repo->getTable()." AS t1 LEFT JOIN api_estabelecimentos AS t2 ON(t2.id = t1.id_estabelecimento) 
          ",
          "WHERE" => " t1.status <> 'EXC'",
        ];
        return (array)GridSelectBuilder::buildTable($data,$baseSql,$functions,true);
      }



      public function getPlanos(){
        $data = $this->repo->where('status','SIM')->orderBy('ordem','ASC')->get();

        return $data;
      }





 
    }
    ?>