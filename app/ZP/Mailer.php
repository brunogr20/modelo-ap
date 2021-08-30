<?php

namespace App\ZP;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends Config{

 protected $mail_from_email ='';
 protected $mail_from_nome ='';
 
 public function email($data) {
  $email = $data['email'];
  $subject = $data['subject'];
  $body = $data['body'];
  $body = $this->template1(['body'=>$data['body'],'link'=>$data['link']]);

  $mail = new PHPMailer(true);
  $configMail = $this->getMAIL();
  $mail->isSMTP();
  $mail->CharSet = 'utf-8';
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';

  if(count($configMail)){
   $mail->Host = $configMail['host']; //gmail has host > smtp.gmail.com
   $mail->Port = $configMail['port']; //gmail has port > 587 . without double quotes
   $mail->Username = $configMail['username'];
   $mail->Password = $configMail['password'];
  }
  $mail->SMTPOptions =[
   'ssl'=>[
    'verify_peer'=>false,
    'verify_peer_name'=>false,
    'allow_self_signed'=>true
   ],
  ];

   if (!empty($data['images'])) {
    foreach ($data['images'] as $kImg => $vImg) {
     $mail->AddStringEmbeddedImage($vImg, $kImg, "Filename{$kImg}.png", "base64", "image/png");
    }
   }
  $mail->ClearAllRecipients(); //Limpar todos os que destinatiarios: TO, CC, BCC
  // $mail->setFrom($this->mail_from_email,$this->mail_from_nome);
  $mail->setFrom('contato@teste.com.br', 'adm cp');
  $mail->Subject = $subject;
  $mail->MsgHTML($body);
  $mail->addAddress($email, "");
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

 private function template1($d){
 $msg ='
    <!DOCTYPE html>
    <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <style>
            @import url("https://fonts.googleapis.com/css?family=Work+Sans&display=swap");
            *{margin: 0;padding: 0;font-family:"Work sans",sans serif; }
            .flex{background-color:#121331;text-align: center;color: #fff;align-items: center;display: flex;flex-direction: row;flex-wrap: wrap;justify-content: center;height: 100%;position: absolute;width: 100%;}
            .body p{padding: 20px;max-width: 500px;}
            .btn-call-to-action {background-color: #2af275;color: #121331;text-decoration: none; padding: 15px;display: inline-block; border-radius: 4px;border:2px solid #20b558;font-weight: bold;}
            .btn-call-to-action:hover {color:#fff;box-shadow: 0px 0px 5px 1px #5e62d2;}
            .footer {max-width: 500px;margin: 0 auto;padding: 20px;}
            small{color:#5e62d2;}
            .st0{fill:#2af275;}
            h2{color:#2af275;}
            svg{width:150px;height: 150px;}
            </style>
            <title>Mail</title>
        </head>
        <body>
            <div class="flex">
                <div class="container">
                    <div class="body">
                    <img  src="'.asset('public/zp/img/logo.png').'" />
                        <h2>adm cp</h2>
                        <p>'; 
                    $msg .=($d['body'])?$d['body']:'';                                  
                    $msg .='
                    </p>';    
                    if(!empty($d['link'])){$msg .='<a class="btn-call-to-action" href="'.$d['link']['url'].'">'.$d['link']['label'].'</a>';}
                    $msg .= '
                    </div>
                    <div class="footer">
                        <small>E-mail automático enviado pelo sistema adm cp.</small>
                        <small>Desconsidere esse e-mail caso não o tenha solicitado. </small>
                    </div>
                </div>
            </div>
        </body>   
    </html>';
return $msg;
 } 

}
?>