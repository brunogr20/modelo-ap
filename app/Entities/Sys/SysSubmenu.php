<?php

namespace App\Entities\Sys;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SysSubmenu extends Model{
 
 protected $fields = [
            'id_menu'=>[],
            'nome'=>[],
            'ordem'=>[],
            'status'=>[],
             ];

    public function __construct(array $attrs=[]) {
        $this->fillable = array_keys($this->fields);
        parent::__construct($attrs);
    }

}
