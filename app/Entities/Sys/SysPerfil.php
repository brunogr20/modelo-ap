<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysPerfil extends Model{
 
 protected $table = 'sys_perfis';

 protected $fields = [
             'nivel'=>[],
             'nome'=>[],
             'status'=>[],
             ];

    public function __construct(array $attrs =[]){
    $this->fillable = array_keys($this->fields);
    parent::__construct($attrs);
    }

}
