<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysSecoesAcesso extends Model{
 
 protected $fields = [
            'id_usuario'=>[],
            'id_secao'=>[],
            'acessos'=>[],
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
