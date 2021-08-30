<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysLogAcao extends Model{
 
 protected $table = "sys_log_acoes";

 protected $fields = [
            'id_usuario'=>[],
            'tipo'=>[],
            'id_secao'=>[],
            'id_registro'=>[],
            'acao'=>[],
            'texto'=>[],
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
