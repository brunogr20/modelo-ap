<?php
namespace App\ZP;

class Config {

 private $client_name = "op Aqui"; 
 private $client_image = "cliente.png";

 private $CMS = "zp";
 private $version = "2.1.0";

 private $host = 'smtp.appop.com'; 
 private $port = 587; 
 private $username = 'contato@appop.com'; 
 private $password = 'xxxxxxxxx#Z#zo'; 
 
   public function getClientName(){
    return $this->client_name;
   }
   public function getClientImage(){
    return url("public/assets_zp/client/".$this->client_image);
   }
   public function getCMS(){
    return $this->CMS;
   }
   public function getVersion(){
    return $this->version;
   }

   public function getMAIL(){
      if($this->host&&$this->port&&$this->username&&$this->password){
        return ['host'=>$this->host,'port'=>$this->port,'username'=>$this->username,'password'=>$this->password];
      }
      return [];
   }

}
?>
