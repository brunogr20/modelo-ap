<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\ApiTireSuasDuvidaService
       * @see App\Services\Sys\SysService
       * Arquivo gerado em 18/06/2019
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\ApiPesquisa;
    use App\Entities\ApiLista;
    use App\Entities\ApiUsuario;
    
    class ApiInfoMenuService extends SysService{
    
      private $repoPesquisa;
      private $repoLista;
      private $repoUsuario;
    
      public function __construct(ApiPesquisa $repoPesquisa, ApiLista $repoLista,ApiUsuario $repoUsuario){
        $this->repoPesquisa = $repoPesquisa;
        $this->repoLista = $repoLista;
        $this->repoUsuario = $repoUsuario;
      }
    
      public function getInfoMenu($data) {
        return [
          'totalPesquisas'=>$this->repoPesquisa->where('id_empresa',$data['id_empresa'])->where('status','SIM')->count(),
          'totalListas'=>$this->repoLista->where('id_empresa',$data['id_empresa'])->where('status','SIM')->count(),
          'totalColaboradores'=>$this->repoUsuario->where('id_empresa',$data['id_empresa'])->where('status','SIM')->count(),
          'totalTutoriais'=>$this->repoPesquisa->where('id_empresa',$data['id_empresa'])->where('status','SIM')->count(),
        ];
      } 
       
    
    }
    ?>