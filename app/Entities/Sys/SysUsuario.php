<?php

namespace App\Entities\Sys;

// use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SysUsuario extends Authenticatable{

    protected $guard = 'zp';

    protected $fields = [
        'id_perfil'=>[],
        'nome'=>[],
        'email'=>[],
        'imagem'=>[],
        'tema'=>[],
        'status'=>[],
        'pass_token'=>[],
        'pass_token_created'=>[],
        'password'=>[],
        // 'remember_token'=>[],
    ];
     protected $hidden = ['password', 'remember_token',];

    public function __construct(array $attrs =[]){
      $this->fillable = array_keys($this->fields);
      parent::__construct($attrs);
    }

}
