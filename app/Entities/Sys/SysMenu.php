<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysMenu extends Model{
 
 protected $fields = [
            'nome'=>[],
            'icone'=>[],
            'ordem'=>[],
            'status'=>[],
             ];

    public function __construct(array $attrs =[]){
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
