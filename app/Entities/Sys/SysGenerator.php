<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysGenerator extends Model{
 
 protected $fields = [
            'id_group_file'=>[],
            'table_name'=>[],
            'id_secao'=>[],
            'texto'=>[],
            'status'=>[],
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
