<?php

namespace App\Entities;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ApiCupom extends Model{
   protected $table = "api_cupons";
   protected $fields = [
            'codigo'=>[],
            'quantidade'=>[],
            'descricao'=>[],
            'valor'=>[],
            'data_validade'=>[],
  ];

   public function __construct(array $attrs =[]){
      $this->fillable = array_keys($this->fields);
      parent::__construct($attrs);
   }

}
