<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysLogAcesso extends Model{
 
   protected $fields = [
            'id_usuario'=>[],
            'inicio'=>[],
            'encerramento'=>[],
            'ip'=>[],
            'browser'=>[],
  ];

   public function __construct(array $attrs =[]){
      $this->fillable = array_keys($this->fields);
      parent::__construct($attrs);
   }

}
