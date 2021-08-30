<?php
namespace App\ZP;

class Message {
  const messages = [
    "SUCCESS"=>[
      "UPDATE"=>"Registro atualizado com sucesso!",
      "UPDATE_MANY"=>"Registros atualizados com sucesso!",
      "STATUS_ON"=>"Registro ativado com sucesso!",
      "STATUS_OFF"=>"Registro desativado com sucesso!",
      "STATUS_MANY_ON"=>"Registros ativados com sucesso!",
      "STATUS_MANY_ON"=>"Registros desativados com sucesso!",
      "INSERT"=>"Registro inserido com sucesso!",
      "DELETE"=>"Registro apagado com sucesso!",
      "DELETE_MANY"=>"Registros apagados com sucesso!",
      "DELETE_IMAGE"=>"Imagem apagada com sucesso!",
      "DELETE_FILE"=>"Arquivo apagado com sucesso!",
      "REORDER"=>"A nova ordem foi salva!",
      "NOTE_UPDATE"=>"Nota alterada com sucesso!",
      "NOTE_INSERT"=>"Nota criada com sucesso!",
      "NOTE_DELETE"=>"Nota apagada com sucesso!",
      "FILE_GENERATE"=>"",
      "MAIL_LINK_SEND"=>"Enviamos um link para o seu e-mail!",
      "USER_UPDATE_PASS"=>"Sua nova senha foi cadastrada com sucesso!",
      "TEMA"=>"O novo tema foi definido com sucesso!",
    ],
    "ERROR"=>[
      "UPDATE"=>"Não foi possível atualizar o registro.",
      "UPDATE_MANY"=>"Não foi possível atualizar os registros.",
      "STATUS_ON"=>"Não foi possível ativar o registro.",
      "STATUS_OFF"=>"Não foi possível desativar o registro.",
      "STATUS_MANY_ON"=>"Não foi possível ativar os registros.",
      "STATUS_MANY_OFF"=>"Não foi possível desativar os registros.",
      "INSERT"=>"Não foi possível inserir o registro.",
      "DELETE"=>"Não foi possível apagar o registro.",
      "DELETE_MANY"=>"Não foi possível apagar o registros.",
      "DELETE_IMAGE"=>"Não foi possível apagar a imagem.",
      "DELETE_FILE"=>"Não foi possível apagar o arquivo.",
      "REORDER"=>"Não foi possível salvar a ordenação.",
      "NOTE_UPDATE"=>"Não foi possível alterar a nota.",
      "NOTE_INSERT"=>"Não foi possível criar a nota.",
      "NOTE_DELETE"=>"Não foi possível apagar a nota.",
      "FILE_GENERATE"=>"Não foi possível gerar o arquivo.",
      "LOGOUT"=>"Não foi possível sair do sistema.",
      "TEMA"=>"Não foi possível definir o tema.",
    ],
    "INFO"=>[
      "USER_ALREADY_EXISTS"=>"Já existe um usuário cadastrado com esse e-mail.",
      "NOT_AVAILABLE"=>"Falha no processamento, tente novamente mais tarde.",
      "INCORRET_LOGIN_DATA"=>"Login ou Senha incorretos.",
      "MAIL_NOT_FOUND"=>"E-mail não encontrado.",
      "USER_UPDATE_PASS"=>"Não foi possível definir sua senha."
    ]
  ];

  public static function get($type, $key=[], $whatever = []){
    $ret = (!empty($whatever)) ? $whatever : [];
    $ret['status'] = (strtoupper($type) == "SUCCESS") ? true : false;
    $ret['type']=$type; 
    if(is_array($key)){
      $ret =array_merge($ret,$key);
    }else{
      $ret['msg']=self::messages[strtoupper($type)][strtoupper($key)];
    }
    return $ret;
  }

}
