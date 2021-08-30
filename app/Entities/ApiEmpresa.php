<?php
      /**
       * @internal Classe responsável por fazer a comunicação com a tabela no banco de dados
       *           É o model genérico da aplicação, pode ser utilizada tanto no adm cp quanto no site
       *           ou em alguma api que o sistema disponibilizar
       * @see App\Services\ApiApiTireSuasDuvidaService
       * Arquivo gerado em 18/06/2019
      */
      namespace App\Entities;
      
      use Prettus\Repository\Eloquent\BaseRepository;
      use Illuminate\Database\Eloquent\Model;
      
      class ApiEmpresa extends Model{
      protected $table = "api_empresas";
      protected $fields = [
                  "pessoa"=>[],
                  "nome"=>[],
                  "cpf_cnpj"=>[],
                  "telefone"=>[],
                  "responsavel"=>[],
                  "imagem_logo_empresa"=>[],
                  "uf"=>[],
                  "cidade"=>[],
                  "status"=>[]
          ];
      
          public function __construct(array $attrs =[]){
              $this->fillable = array_keys($this->fields);
              parent::__construct($attrs);
          }
      
      }