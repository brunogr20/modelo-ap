<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysAnotacao extends Model{
 
 protected $table = 'sys_anotacoes';

 protected $fields = [
             'id_usuario'=>[],
             'id_secao'=>[],
             'tipo_exibicao'=>[],
             'titulo'=>[],
             'texto'=>[],
             'nivel'=>[],
             'status'=>[],
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
