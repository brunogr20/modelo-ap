<?php
      /**
       * @internal Classe responsável por fazer a comunicação com a tabela no banco de dados
       *           É o model genérico da aplicação, pode ser utilizada tanto no adm cp quanto no site
       *           ou em alguma api que o sistema disponibilizar
       * @see App\Services\ApiApiParoquianoService
       * Arquivo gerado em 14/05/2019
      */
      namespace App\Entities;
      
      use Laravel\Passport\HasApiTokens;
      use Illuminate\Notifications\Notifiable;
      use Illuminate\Foundation\Auth\User as Authenticatable;
      use Prettus\Repository\Eloquent\BaseRepository;
      use Illuminate\Database\Eloquent\Model;
      
      class ApiUsuario extends Authenticatable{
      protected $table = "api_usuarios";

      use HasApiTokens, Notifiable;

      protected $fields = [
                  "id_empresa"=>[],
                  "nivel"=>[],
                  "nome"=>[],
                  "email"=>[],
                  "cpf"=>[],
                  "telefone"=>[],
                  "cargo"=>[],
                  "password"=>[],
                  "remember_token"=>[],
                  'token_password'=>[],
                  'token_data'=>[],
                  "token_acesso_app"=>[],
                  "status"=>[],
                ];
      
          public function __construct(array $attrs =[]){
              $this->fillable = array_keys($this->fields);
              parent::__construct($attrs);
          }
      

          public function findForPassport($identifier) {
            return $this->where('cpf', $identifier)
           // ->orWhere('username', $identifier)
            ->first();
    }
      }