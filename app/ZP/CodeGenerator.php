<?php
namespace App\ZP;
use Illuminate\Support\Facades\DB;
use App\Entities\Sys\SysSecao;
use App\Services\Sys\SysGeneratorService;

class CodeGenerator {

  private $secao_repo;
  private $gs;

  public function __construct(SysSecao $secao_repo){//
   $this->secao_repo =$secao_repo;
  }
  function findEntity($id,$log){
    $q = DB::select("SELECT * FROM `sys_generators` WHERE id_secao = :id",["id"=>$id]);
    return $q;
  }
  /**
   * @internal Gera as rotas para a nova seção;
   */
  public function gRouters(){

    $query =DB::select("SELECT t1.*,t2.nome AS menu, t3.nome AS submenu 
    FROM sys_secoes AS t1 JOIN sys_menus AS t2  ON t1.id_menu=t2.id"
    ." LEFT JOIN sys_submenus AS t3 ON t1.id_submenu=t3.id AND t3.status='SIM' "
    ." WHERE t1.status='SIM' AND t2.status='SIM' ORDER BY t2.nome ASC,t3.nome ASC,t1.nome ");
    $sections=[];
    $routers="";
    foreach($query as $val) {
      if($val->nome&&$val->controller){
        $uri = strUrl($val->menu);
        if($val->submenu){
          $uri.='/'.strUrl($val->submenu);
        }
        $uri.='/'.strUrl($val->nome);
        $controller='ZP\\'.$val->controller;
        $fn = ($val->tipo=='REL')?'createRelRoutes':'createDefaultRoutes';
        $routers.="Route::prefix('/{$uri}')->group(function () {
          {$fn}('{$controller}');
        });
        ";
        $this->secao_repo->where('id',$val->id)->update(['uri'=>$uri]);
      }
    }
    
    $dir ='./routes/zp.php';
    $dirBkp ='./routes/zp_bkp.php';
    $content = file_get_contents($dir);
    if($content&&$routers){
      $replacement = "#<rt:inst>
        {$routers}
      #</rt:inst>";
      $fBkp = fopen($dirBkp, 'w' );
      ///
      $pattern = "/((\#<rt:inst>)[\s\S]*\#<\/rt:inst>)/";
      $str = preg_replace($pattern, $replacement, $content);
      $file = fopen($dir, 'w' );
      fwrite($file,$str);
      fclose($file);
      shell_exec("chmod 777 $dir -R");
      shell_exec("chmod 777 $dirBkp -R");
     
    } 
  }
  /**
   * @internal Gera a(s) entidade(s) para a nova seção;
   */
  public  function gEntities($name,$fields,$tableName){
    
    $dir ='./app/Entities/';
    $name="Api".ucfirst($name);
    $file=$name.'.php';
    if(file_exists($dir.$file)){
      return ['status'=>false,'msg'=>'Já existe uma classe no dir Entities com o nome '.$file];
    }
    $now = date("d/m/Y");
    $enti='<?php
      /**
       * @internal Classe responsável por fazer a comunicação com a tabela no banco de dados
       *           É o model genérico da aplicação, pode ser utilizada tanto no adm cp quanto no site
       *           ou em alguma api que o sistema disponibilizar
       * @see App\Services\Api'.$name.'Service
       * Arquivo gerado em '.$now.'
      */
      namespace App\Entities;
      
      use Prettus\Repository\Eloquent\BaseRepository;
      use Illuminate\Database\Eloquent\Model;
      
      class '.$name.' extends Model{
      protected $table = "'.$tableName.'";
      protected $fields = [';
              foreach ($fields as $field) {
                
                $enti.='
                  "'.$field.'"=>[],';    
              }
            $enti.='
            "status"=>[]
          ];
      
          public function __construct(array $attrs =[]){
              $this->fillable = array_keys($this->fields);
              parent::__construct($attrs);
          }
      
      }';
      $this->newFile($dir,$file,$enti);
  }
  /**
   * @internal Gera o(s) service(s) para a nova seção;
   */
  public  function gService($data){
    $dir ='./app/Services/';
    $name="Api".ucfirst($data['idGroup'].'Service');
    $entity="Api".$data['idGroup'];
    $file=$name.'.php';
    if(file_exists($dir.$file)){
      return ['status'=>false,'msg'=>'Já existe uma classe no dir Service com o nome '.$file];
    }
    $now = date("d/m/Y");

    $baseSql="";
    $select = '"SELECT"=>["t1"=>["id","status",';
    $arrSql = '$baseSql = [';
    $where ="";
    $counter=2;
    $tables=[];
    foreach($data['fields'] as $field){
      $html=$field['html'];
      //Verifica se está na Grid e se não faz parte de uma entidade.
      if(isset($html['isOnGrid']) && empty($field['entity']['id'])){
        $select .=  '"'.$field['name'].'",';
      }
    }
    $select .='],';
    //Montando select das relacoes
    $counter=2;
    foreach($data['fields'] as $field){
      if(!empty($field['entity']['id'])){
        //Buscar os dados da entidade no banco de dados.
        $ent = $this->findEntity($field['entity']['id'],"Service"); $ent=$ent[0];
        $table = $ent->table_name." AS t".($counter);
        $fValue = $field['entity']['fValue'];
        $fLabel = $field['entity']['fLabel'];
       
        $select .= '"t'.$counter.'"=>["'.$fLabel.'"=>"'.$field['name'].'"],'; // Primeiro campo depois alias
        #Montar o array de query
        $baseSql .= 'LEFT JOIN '.$table.' ON(t'.($counter).'.'.$fValue.' = t1.'.$field['name'].') '."\n".$this->fixTab(2);
        $counter++;
      }
    }

    $select .='],';
    // Verifica se BaseSQL Está vazia e insere o FROM BODY
    if($baseSql != ""){
      $baseSql = '"FROM_BODY" =>$this->repo->getTable()." AS t1 '.$baseSql.'",';
      $where = '"WHERE" => " t1.status <> \'EXC\'",';
    }else{
      $select= "";
      $baseSql = '"FROM_BODY" => $this->repo->getTable(),';
      $where = '"WHERE" => " status <> \'EXC\'",';
    }  
   
    $arrSql.= ' 
     '.$select.'
     '.$baseSql.'
     '.$where.'
     ];';

    
     $serv = '<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\\'.$name.'
       * @see App\Services\Sys\SysService
       * Arquivo gerado em '.$now.'
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\\'.$entity.';
    
    class '.$name.' extends SysService{
    
      private $repo;
    
      public function __construct('.$entity.' $repo){
        $this->repo = $repo;
        parent::setRepo($repo); // Setando o repositorio padrao para o Service.
      }
    
      /**Método é Implementado por seção, 
       * Não pode ser reaproveitado com Herança 
       * */
      public function loadGrid($data){
        '.$arrSql.'
        return $dados = (array) GridSelectBuilder::buildTable($data, $baseSql, true);
      }

    
    }
    ?>';
    $this->newFile($dir,$file,$serv);
  }
  /**
   * @internal Gera a view para a nova seção;
   */
  public function gView($data){
    $dir ='./resources/views/zp/sections/';
    $file=camelCaseToUnderline($data['idGroup'],"-").'.blade.php';
    if(file_exists($dir.$file)){
    return ['status'=>false,'msg'=>'Já existe um arquivo no dir views/zp com o nome '.$file];
    }
    $colsValid='';
    $columns='';

    foreach ($data['fields'] as $val) {

      $html=$val['html'];
      
      if(isset($html['isOnGrid'])){
        if($html['isOnGrid'] == "true"){
          $columns.= "{title:'{$html['label']}',data:'{$val['name']}'},\n".$this->fixTab(7);
        }
      }
      
      if($html['validator']){
        $colsValid .="{$val['name']}:['{$html['validator']}'],\n".$this->fixTab(6);
      }
    }
    $components = $this->componentsView($data['fields']);
  

    $view = '@extends("zp.components.main")'."\n".'@section("sectionContent")';

    if($data['typeSection']){
     
      $view .= '
        <script>
          objPage.grid.init={
            columns:[

              '.$columns.'
            ],
            buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:false,multStatus:true,multDelete:true,}
          }; 
            
          //Validar formulário
          objPage.form.save.validate = {
            
            '.$colsValid.'
          }
          
        </script>
        @include("zp.components.grid")
      
          {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}
            {!! HTML::initTab("geral",true); !!}
              
              {!! HTML::inputHidden(["name"=>"id"]); !!}
              {!! HTML::status(); !!}
              '.$components.'
            {!! HTML::endTab(); !!}
            {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]); !!}
          {!!  HTML::endForm(); !!}';
      }
    $view .= "\n".'@endsection';
    $this->newFile($dir,$file,$view);
  }
  /**
   * @internal Gera o controller para a nova seção
   */
  public  function gController($data){
    $dir ='./app/Http/Controllers/ZP/';
    $name='ZP'.ucfirst($data['idGroup'].'Controller');
    $service="Api".ucfirst($data['idGroup']).'Service';
    $view=camelCaseToUnderline($data['idGroup'],"-");
    $file=$name.'.php';
    if(file_exists($dir.$file)){
      return ['status'=>false,'msg'=>'Já existe uma classe no dir Controller com o nome '.$file];
    }

    $onConstructor="";
    $constVariables="";
    $variables="";
    $uses="";
    $viewData = '$page["data"] = ['."\n";
    foreach($data['fields'] as $field){
     
      // var_dump($field)."\n";
      if(!empty($field['entity']['id'])){
        //Buscar os dados da entidade no banco de dados.
        $entity = $this->findEntity($field['entity']['id'],"controller"); $entity=$entity[0];
        $variableService = camelCaseToUnderline($entity->id_group_file)."_service";
        $viewData .= $this->fixTab(4).' "'.$entity->id_group_file.'Data"=> $this->'.$variableService.'->getSelectData("'.$field["entity"]["fValue"].'","'.$field["entity"]["fLabel"].'"), '."\n";
        $variables .= "protected $$variableService;"."\n";
        $constVariables .= '$this->'.$variableService.'=$'.$variableService.';'."\n";
        $uses .= 'use App\Services\\Api'.$entity->id_group_file.'Service;'."\n".$this->fixTab(1);
        $onConstructor .= ',Api'.$entity->id_group_file.'Service $'.$variableService.'';
      }
    }
    $viewData .=$this->fixTab(2).'];';
    

    $cont = '<?php
    namespace App\Http\Controllers\ZP;
    
    use Illuminate\Http\Request;
    use App\Http\Controllers\ZP\Sys\SysController;
    use App\Services\Sys\SysCoreService;
    use App\Services\\'.$service.';
    '.$uses.'
    class '.$name.' extends SysController {
    
      protected $service;
      '.$variables.'

      function __construct(SysCoreService $core_service, '.$service.' $service '.$onConstructor.'){
        parent::__construct($core_service);
        $this->service=$service;
        '.$constVariables.'
      }
    
      public function index(){
        $page = $this->dataPage();
        $page["imports"] = ["dataTable","crud"];
        '.$viewData.'
        return view("zp.sections.'.$view.'",["page"=>$page]);
      }
    
    }
    
    ?>';
    $this->newFile($dir,$file,$cont);
    return $name;
  }

  private function componentsView($fields){
    $comp='';
    foreach ($fields as $field) {
      $html=$field['html'];
      $attrs='';
      if($html['attrs']){
        $arAt= explode(',',$html['attrs']);
        foreach ($arAt as $at) {
          $a= explode('=',$at);
          if(count($a)){
            $aVal="''";
            if($a[0]!='mask'){
              $aVal=((!empty($a[1]))?"['{$a[1]}']":"['']");
            }else{
              $aVal=((!empty($a[1]))?"['{$a[1]}']":"['']");
            }
            $attrs.=",'{$a[0]}'=>".$aVal;
          }
        }
      }
      if($field['fieldType']=='input'){
        $comp .= "{!! HTML::input(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}]); !!}\n";
      }else if($field['fieldType']=='textarea'){
        $comp .= $this->fixTab(7)."{!! HTML::textarea(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}]); !!}\n";
      }else if($field['fieldType']=='date'){
        $comp .= $this->fixTab(7)."{!! HTML::datetime(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}],['format'=>'DD/MM/YYYY']); !!}\n";
      }else if($field['fieldType']=='time'){
        $comp .= $this->fixTab(7)."{!! HTML::datetime(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}],['format'=>'HH:mm']); !!}\n";
      }else if($field['fieldType']=='select'){
        $options=""; 
        if(!empty($field['entity']['id'])){
          //Carregar dados entidade;
          //Buscar os dados da entidade no banco de dados.
          $entity = $this->findEntity($field['entity']['id'],"view"); $entity=$entity[0];
          $dataKey = $entity->id_group_file.'Data';
          $options = '$page["data"]["'.$dataKey.'"]';
        }else{
          //Carregar dados campos estáticos
          foreach($field['items'] as $item){
            $item=(object)$item; 
            $options .= "'$item->value'"."=>"."'$item->label',";
          }
          $options = "[{$options}]";
        }
        $comp .= $this->fixTab(7)."{!! HTML::select(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}],$options); !!}\n";

      }else if($field['fieldType']=='radio'){
         $o=""; foreach($field['items'] as $item){
           $item=(object)$item; $o .= "['label'=>'$item->label','value'=>'$item->value'],";
         }
        $comp .= $this->fixTab(7)."{!! HTML::radio(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}],[{$o}]); !!}\n";
      }else if($field['fieldType']=='checkbox'){
        $o=""; foreach($field['items'] as $item){$item=(object)$item; $o .= "['label'=>'$item->label','name'=>'$item->name','value'=>'$item->value'],";}
        $comp .= $this->fixTab(7)."{!! HTML::checkbox(['label'=>'{$html['label']}','name'=>'{$field['name']}'{$attrs}],[{$o}]); !!}\n";
      }else if($field['fieldType']=='image'){
        $comp .=$this->fixTab(7)."{!!HTML::image(['label'=>'{$html['label']}','name'=>'{$field['name']}','size'=>3000,'dimensions'=>[300,300],'accept'=>['png','jpeg','jpg']])!!}\n";
      }else if($field['fieldType']=='file'){
        $comp .=$this->fixTab(7)."{!!HTML::file(['label'=>'{$html['label']}','name'=>'{$field['name']}','size'=>2000,'accept'=>['pdf','txt','docx']])!!}\n";
      }
    }
    return $comp;
  }

  private function newFile($dir,$file,$body){
    shell_exec("chmod 777 -R $dir");
    $f = fopen($dir.$file, 'w');
    fwrite($f, $body);
    fclose($f);
    //chmod($dir.$file,"0777");
    shell_exec("chmod 777 $dir.$file -R");
    shell_exec("chmod 777 $dir -R");
  }

  private function fixTab($times){
    $r="";  
    for($v=1; $v<=$times; $v++)
        $r .="\t";
    return $r;
  }
  
}



?>