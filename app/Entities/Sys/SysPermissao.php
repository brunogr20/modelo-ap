<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysPermissao extends Model{

 protected $table = 'sys_permissoes';   

 protected $fields = [
            'id_perfil'=>[],
            'id_secao'=>[],
            'nivel'=>[],    
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}