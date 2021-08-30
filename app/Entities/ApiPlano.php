<?php

namespace App\Entities;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ApiPlano extends Model{
   protected $table = "api_planos";
   protected $fields = [
            'tipo'=>[],
            'titulo'=>[],
            'texto'=>[],
            'descricao'=>[],
            'valor'=>[],
            'parcela'=>[],
  ];

   public function __construct(array $attrs =[]){
      $this->fillable = array_keys($this->fields);
      parent::__construct($attrs);
   }

}
