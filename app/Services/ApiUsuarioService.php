<?php
    /**
       * @internal Classe responsável por fazer operações de negócio (Model) da aplicação.
       *           Trabalha em conjunto com uma classe entidade(Entity). A maioria das funções
       *           dessa classe são herdadas de App\Services\Sys\SysService como (criar,atualizar,deletar)
       *           Assim como a classe de Entidade essa pode ser utilizada por toda aplicação incluindo 
       *           a implementação do site e/ou api que o sistema forneça.
       * @see App\Entities\ApiCadastroContatoService
       * @see App\Services\Sys\SysService
       * Arquivo gerado em 23/05/2019
    */
    namespace App\Services;
    
    use App\ZP\GridSelectBuilder;
    use App\Services\Sys\SysService;
    use App\Entities\ApiUsuario;
    use App\Entities\ApiEmpresa;
    use App\Entities\ApiPesquisa;
    use App\Entities\ApiPesquisaEntrevistadorRelacao;
    use App\Entities\ApiRespostaInternauta;
    use PHPMailer\PHPMailer\PHPMailer;
    // use App\ZP\Mailer; 
    
    class ApiUsuarioService extends SysService{
    
      private $repo;
      private $repo_empresa;
      private $repoPesquisaEntrevistadorRelacao;
      private $repoRespostaInternauta;
      private $repoPesquisa;
      private $pathEmpresa;
    
      public function __construct(ApiEmpresa $repo_empresa,ApiUsuario $repo,ApiPesquisaEntrevistadorRelacao $repoPesquisaEntrevistadorRelacao,ApiRespostaInternauta $repoRespostaInternauta,ApiPesquisa $repoPesquisa){
        $this->repo = $repo;
        $this->repo_empresa = $repo_empresa;
        $this->repoPesquisaEntrevistadorRelacao = $repoPesquisaEntrevistadorRelacao;
        $this->repoRespostaInternauta = $repoRespostaInternauta;
        $this->repoPesquisa = $repoPesquisa;
        $this->pathEmpresa='arqConteudo/arqEmpresa/';
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

      public function listaUsuarios($user){
        $return =[];
        $uduarios = $this->repo->where('status','SIM')->where('id_empresa',$user['id_empresa'])->orderBy('created_at','DESC')->get();
        foreach($uduarios as $key => $val){
          $return[$key]=$val;
          // if($val['nivel']=='ENTREVISTADOR'){
            $return[$key]['totalPesquisasVinculadas']=$this->repoPesquisaEntrevistadorRelacao->where('id_entrevistador',$val['id'])->count();
            $return[$key]['totalEntrevistasRealizadas']=$this->repoRespostaInternauta->where('id_entrevistador',$val['id'])->count();
          // }
        }
        return  $return;
      }

      public function getContasUsuarios($user){
        $return =[];
        $usuarios = $this->repo->where('status','SIM')->where('cpf',$user['cpf'])->get();

        foreach ($usuarios as $key => $value) {
          $empresa = $this->repo_empresa->where('id',$value['id_empresa'])->where('status','SIM')->get();
          $em=[];
          if(count($empresa)){
            $empresa = $empresa[0];
            $imgBase64='';
            if ($empresa['imagem_logo_empresa']) {
              $pathLogo =public_path('/').'storage/'.$this->pathEmpresa. $empresa['imagem_logo_empresa'];
              if (file_exists($pathLogo)){
                $arAux = explode('.', $empresa[0]['imagem_logo_empresa']);
                $imgBase64 = 'data:image/' . $arAux[count($arAux) - 1] . ';base64,' . base64_encode(file_get_contents($pathLogo));
              }
            }
    
            $em = [
               'id'=>$empresa['id'],
               'nome'=>$empresa['nome'],
               'imagem_logo_empresa'=>$imgBase64,
            ];
          }
          $return[$key] = [
           'id'=>$value['id'],
           'empresa'=>$em,
           'nivel'=>$value['nivel'],
           'nome'=>$value['nome'],
           'email'=>$value['email'],
           'cpf'=>$value['cpf'],
          ];
        }
     
        return $return;
      }

      public function getUsuario($user){
       
        $usuario = $this->repo->where('status','SIM')->where('id_empresa',$user['id_empresa'])->where('id',$user['id'])->get();
        if(count($usuario)){
          $usuario = $usuario[0];
          return [
            'id'=>$usuario['id'],
            'nivel'=>$usuario['nivel'],
            'nome'=>$usuario['nome'],
            'email'=>$usuario['email'],
            'cpf'=>$usuario['cpf'],
            'telefone'=>$usuario['telefone'],
            'cargo'=>$usuario['cargo'],
            'status'=>$usuario['status'],
          ];
        } 
        return [];
      }

      public function listaEntrevistadores($user,$data) {
        $codsEntrevistadores = [];
        $retorno = [];
        $entRelacionados = $this->repoPesquisaEntrevistadorRelacao->where('id_pesquisa', $data['id'])->get();
      
        foreach ($entRelacionados as $value) {
         $codsEntrevistadores[] = $value['id_entrevistador'];
        }
        $entrevistadores = $this->repo->where('id_empresa', $user['id_empresa'])->where('status', 'SIM')->orderBy('nome', 'ASC')->get();
        foreach ($entrevistadores as $v) {
         $checked = (in_array($v['id'], $codsEntrevistadores)) ? true : false;
         $retorno[] = [
             'label' => $v['nome'],
             'value' => $v['id'],
             'checked' => $checked,
         ];
        }
        return $retorno;
       }

       public function getUsuariosLista($user){
         $empresas = $this->repo_empresa->where('id','<>',$user['id_empresa'])->where('status','SIM')->get(); 
         $data=[];

         foreach ($empresas as $key => $value) {
           $data[$key] = $value;

           $usuarios = $this->repo->where('id_empresa',$value['id'])->where('nivel','MASTER')->get();
           $data[$key]['usuario']=count($usuarios)?$usuarios[0]:[];
           $data[$key]['totalPesquisas']= $this->repoPesquisa->where('id_empresa',$value['id'])->count();
         }
    
        return $data;
       }
      
       public function editarRelacaoEntrevistadoresPesquisa($dados) {
        $arRelacao = $dados['entrevistadores'];
        $idPesquisa = $dados['id'];
        
         $arNotIn = [];
         foreach ($arRelacao as $e) {
          $rel = $this->repoPesquisaEntrevistadorRelacao->where('id_pesquisa',$idPesquisa)->where('id_entrevistador',$e)->get();
          if (!count($rel)) {
           $novoE = $this->repoPesquisaEntrevistadorRelacao->create(['id_pesquisa'=>$idPesquisa,'id_entrevistador'=>$e]);
           $arNotIn[] = $novoE['id'];
          } else {
           $arNotIn[] = $rel[0]['id'];
          }
         }
         // return $arNotIn;
         $this->repoPesquisaEntrevistadorRelacao->where('id_pesquisa', $idPesquisa)->whereNotIn('id', $arNotIn)->delete();
        
        return ['status' => true];
      }

      public function salvarColaborador($user,$data) {

        $salvar =[
          'nivel'=>$data['nivel'],
          'nome'=>$data['nome'],
          'email'=>$data['email'],
          'telefone'=>$data['telefone'],
          'cpf'=>$data['cpf'],
          'cargo'=>$data['cargo'],
        ];

        if(!empty($data['id'])){
          $user = $this->repo->where('id_empresa', $user['id_empresa'])->where('id', $data['id'])->where('status', 'SIM');
          if ($user->update($salvar)) {
            if($salvar['nivel']!='ENTREVISTADOR'){
              $this->repoPesquisaEntrevistadorRelacao->where('id_entrevistador',$data['id'])->delete();
            }
            return['status' => true];
          }
          }else{
          $salvar['password']=bcrypt($data['senha']);
          $salvar['id_empresa']=$user['id_empresa'];
          $salvar['status']='SIM';
          $this->repo->create($salvar);
          return['status' => true];

        }
        unset($data['senha']);
        unset($data['conf_senha']);
        
        return['status' => false];
      }

      public function updateUsuario($user, $data) {
      
       $user = $this->repo->where('id', $user['id'])->where('status', 'SIM');
      
        if ($user->update($data)) {
         return['status' => true];
        }
        return['status' => false];
      }

      public function createUsuario($user,$data,$request){

        $empresa = $data;
        $empresa['nome'] = $data['nome_empresa']; 
        $empresa['telefone'] = $data['telefone_empresa']; 

        if(!empty($data['id'])&&!empty($data['imagem_logo_empresa'])){
          if($data['imagem_logo_empresa']=='del'){
            $this->dFile(['id'=>$data['id'],'name'=>'imagem_logo_empresa'],['dir'=>'arqEmpresa']);
          }
        }

         $d=['imagem_logo_empresa'=>''];
         $this->do_upload($request, $d,['dir'=>'arqEmpresa']);
         $empresa['imagem_logo_empresa']=$d['imagem_logo_empresa'];

        $this->repo_empresa->create($empresa);

        $idEmpresa = $this->repo_empresa->where('cpf_cnpj',$empresa['cpf_cnpj'])->where('telefone',$empresa['telefone'])->where('status', 'SIM')->get();

        $data['id_empresa'] = $idEmpresa[0]->id;
        $data['password']=bcrypt($data['senha']);
        $data['nivel']='MASTER';
        $this->repo->create($data);

         return ['status' => true];
      }


      public function alterarSenha($user, $data) {

        $user = $this->repo->where('id_empresa', $user['id_empresa'])->where('id', $data['id'])->where('status', 'SIM');
        $input=['password'=>bcrypt($data['senha'])];
        if ($user->update($input)) {
         return ['status' => true];
        }
        return ['status' => false];
      }
      
      public function recuperaSenha($request) {
    
        $request->validate([
            'email' => 'required||min:4|max:100',
        ]);
        $dados = $request->only('email', 'email');
      
        $user = $this->repositorie->where('email', $dados['email'])->where('status', 'SIM')->get();
        if (count($user)) {
          $user = $this->repositorie->where('email', $dados['email'])->where('status', 'SIM');
          $novaSenha = $this->geraSenha(4);
          $input['senha'] = hash("whirlpool", md5(base64_encode($novaSenha)));
      
        // return ['status'=>false,'msg'=>$this->enviarEamil('brunogeraldo@adm.com.br','xxxxxx','nova senha - '.$novaSenha)];
          if ($user->update($input)) {
          return ['status' => true, 'email' => $dados['email'], 'novaSenha' => $novaSenha];
          } else {
          return ['status' => false, 'msg' => 'Não foi possível gerar uma nova senha, tente novamente mais tarde.'];
          }
        } else {
          return ['status' => false, 'msg' => 'O e-mail não foi encontrado, verifique se foi digitado corretamente.'];
        }
      }
      //verificar campo com dado repetido
        public function verificacaoCPF($request){
          $dados = $request;  
          $userCadastrado = $this->repo->where('id', $dados['id'])->where('status', 'SIM')->get();
          
          if($dados['cpf']){
            if($userCadastrado[0]->cpf == $dados['cpf']){
              return ['status'=> true];
            }

            $user = $this->repo->where('cpf', $dados['cpf'])->where('status', 'SIM')->get();
            $userDados = $user->all();
              if(!empty($userDados) == true ){ 
                return  ['status' => false];
            }
          }
            return ['status' => true];
        }

        public function verificacaoEmail($request){
          $dados = $request;          
          $userCadastrado = $this->repo->where('id', $dados['id'])->where('status', 'SIM')->get();
          if($dados['email']){

            if($userCadastrado[0]->email == $dados['email']){
              return ['status'=> true];
            }
  
            $user = $this->repo->where('email', $dados['email'])->where('status', 'SIM')->get();
            $userDados = $user->all();
              if(!empty($userDados) == true ){ 
                return  ['status' => false];
            }
          }
            return ['status' => true];
        }

        public function verificacaoCPFNovoUsuario($request){
          $dados = $request->all();          
          if($dados['cpf']){
            $user = $this->repo->where('cpf', $dados['cpf'])->where('status', 'SIM')->get();
            $userDados = $user->all();
              if(!empty($userDados) == true ){ 
                return  ['status' => false];
            }
          }
            return ['status' => true];
        }

        public function verificacaoEmailNovoUsuario($request){
          $dados = $request->all();          
          if($dados['email']){

            $user = $this->repo->where('email', $dados['email'])->where('status', 'SIM')->get();
            $userDados = $user->all();
              if(!empty($userDados) == true ){ 
                return  ['status' => false];
            }
          }
            return ['status' => true];
        }

        
       /// verifica se já existe um valor igual cadastrado
       public function occurrences($request) {
        $dados = $request->all();
        $chave = key($dados);
        $valor = $dados[$chave];
        if ($chave == 'cpf_cnpj') {
         if (strlen($valor) == 18) {
          if (!$this->isCnpj($valor)) {
           return ['status' => true, 'tipo' => 'validacao'];
          }
         } elseif (strlen($valor) == 14) {
          if (!$this->isCpf($valor)) {
           return ['status' => true, 'tipo' => 'validacao'];
          }
         } else {
          return ['status' => true, 'tipo' => 'validacao'];
         }
        }
      
        if (count($this->repositorie->select('codigo')->where($chave, $valor)->where('status', 'SIM')->get())) {
         return ['status' => true];
        }
        return ['status' => false];
       }
    
    }


    ?>