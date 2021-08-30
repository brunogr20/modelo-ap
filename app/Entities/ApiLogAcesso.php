<?php

namespace App\Entities;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ApiLogAcesso extends Model{
 
   protected $fields = [
            'local'=>[],
            'tipo'=>[],
            'id_acesso'=>[],
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
