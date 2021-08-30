<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysSecao extends Model{

 protected $table="sys_secoes";
 
 protected $fields = [
            'id_menu'=>[],
            'id_submenu'=>[],
            'tipo'=>[],
            'nome'=>[],
            'controller'=>[],
            'ordem'=>[],
            'status'=>[],
             ];

   public function __construct(array $attrs =[]){
      $this->fillable = array_keys($this->fields);
      parent::__construct($attrs);
   }

}
