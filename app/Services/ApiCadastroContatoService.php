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
    use App\Entities\ApiCadastroContato;
    use PHPMailer\PHPMailer\PHPMailer;
    // use App\ZP\Mailer; 
    
    class ApiCadastroContatoService extends SysService{
    
      private $repo;
    
      public function __construct(ApiCadastroContato $repo){
        $this->repo = $repo;
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

     public function cadastrarMensagem($data,$tipo='SITE'){
      if(!empty($data['email'])){
        $query =  $this->repo->where("email",$data['email'])->where('status','SIM');
        if(count($query->get())){
          $query->update([
            'email'=>$data['email'],
            'nome'=>$data['nome'],
            'telefone'=>$data['telefone'],
            ]);
          }else{
            $query->create([
              'status'=>'SIM',
              'email'=>$data['email'],
              'nome'=>$data['nome'],
              'telefone'=>$data['telefone'],
          ]);
        }
      }
        $msg ="";
        $assunto = "am - Contato pelo site";
        if($tipo=='APP'){
          $assunto = "am - Contato pelo app";
        }
        if(!empty($data['nome'])){
          $msg .= "<p><strong>Nome: </strong>{$data['nome']}</p>";
        }
        if(!empty($data['email'])){
          $msg .= "<p><strong>E-mail: </strong>{$data['email']}</p>";
        }else{
          $data['email'] ="";
        }
        if(!empty($data['telefone'])){
           $msg .= "<p><strong>Telefone: </strong>{$data['telefone']}</p></br>";
        }
        $msg .= "<p>{$data['mensagem']}</p>";
        $this->emailMensagem(
          ['email'=>$data['email'],
          'nome'=>$data['nome'],
          'subject'=>$assunto,
          'body'=>$msg,
          'link'=>[
           // 'label'=>'Clique aqui para criar uma nova senha',
           // 'url'=> $url.'/'.$token.'/'.md5(date('Y-m-d H:i:s')),
          ],
          ]);
          return  ['status'=>true];
      }


      public function emailMensagem($data) {
      //  $email = $data['email'];
        $subject = $data['subject'];
        $body = $data['body'];
       // $body = $this->template1(['body'=>$data['body'],'link'=>$data['link']]);
      
        $mail = new PHPMailer(true);
        // $configMail = $this->getMAIL();
        $mail->isSMTP();
        $mail->CharSet = 'utf-8';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
      
       
         $mail->Host = 'smtp.amdaigreja.com.br'; //gmail has host > smtp.gmail.com
         $mail->Port = 587; //gmail has port > 587 . without double quotes
         $mail->Username = 'contato@amdaigreja.com.br';
         $mail->Password = '@migosD19';
        //  $mail->SMTPAuth = false;
        
        $mail->SMTPOptions =[
         'ssl'=>[
          'verify_peer'=>false,
          'verify_peer_name'=>false,
          'allow_self_signed'=>true
         ],
        ];
      
        //  if (!empty($data['images'])) {
        //   foreach ($data['images'] as $kImg => $vImg) {
        //    $mail->AddStringEmbeddedImage($vImg, $kImg, "Filename{$kImg}.png", "base64", "image/png");
        //   }
        //  }
        $mail->ClearAllRecipients(); //Limpar todos os que destinatiarios: TO, CC, BCC
        // $mail->setFrom($this->mail_from_email,$this->mail_from_nome);
        // $mail->setFrom($email, $data['nome']);
        $mail->setFrom('contato@amdaigreja.com.br',$data['nome']);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        // $mail->addAddress('brunogeraldo@adm.com.br', "");
        $mail->addAddress('contato@amdaigreja.com.br', "");
        $mail->AddReplyTo($data['email'], '');
        if (!empty($data['copy'])) {
         foreach($data['copy'] as $val){
          if(is_array($val)){
           $mail->AddBCC($val['email'], $val['nome']);
          }else{
           $mail->AddBCC($val,"");
          }
         }
        }
      
        if($mail->send()){
         return true;
        }
        return false;
       }
    
    }
    ?>