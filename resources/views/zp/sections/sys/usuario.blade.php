@extends('zp.components.main')
@section('sectionContent')
    <script>
      objPage.grid.init={
           columns:[
           {title:"Nome",data:"nome",name:""},
           {title:"E-mail",data:"email",name:""},
           {title:"Perfil",data:"perfil",name:""}
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:true,refresh:true,order:false,search:true,delete:true,status:true,multChecked:true,multStatus:true,multDelete:true,}
      }; 
        
        //Validar forlulário
        objPage.form.save.validate = {
        id_perfil: ['required',],
        nome: ['required',],
        email: ['required','email'],
         }

         objPage.form.loaded.callback=(r)=>{
           $('[data-modal=password]').removeClass("hide");
         } 
         objPage.form.new.init=()=>{
            $('[data-modal=password]').addClass("hide");
         };
         objPage.form.clear.init=()=>{
            $('[data-modal=password]').addClass("hide");
         };
         objPage.form.create.callback=(r)=>{
           if(r.status){
            openModal(r.data.id);
            $('[data-modal=password]').addClass("hide");
           }
         }
         function openModal(id=''){
              if(!id){id=$('input[name=id]').val();}
              $('input[name=id_user]').val(id);
              $('input[name=senha]').val('');
              $('input[name=conf_senha]').val(''); 
              $('#modalPassword').modal('show');
         }
        $(function(){
          $('[data-save=password]').click(function(){
            let pass=$('input[name=senha]').val(),
                pesPass=$('input[name=conf_senha]').val(); 
                id=$('input[name=id_user]').val(); 
                if(!id){
                 $.alertMessage('info','Escolha um registro da GRID.');
                 return false;
                } 
                if(!pass){
                 $.alertMessage('info','O campo senha é obrigatório.');
                 return false;
                } 
                if(pass.length<6){
                 $.alertMessage('info','O campo senha  deve ter no mínino 6 caracteres.');
                 return false;
                } 
                if(pass!=pesPass){
                 $.alertMessage('info','O campo confirmar senha deve ser igual ao da senha.');
                 return false;
                }
                $.ajax({
                  url: objPage.url.url_page+'/ajax',
                  type: 'POST',
                  dataType: 'JSON',
                  data: {action:'update_password',pass,id},
                  success: function (r) {
                    if (r.status) {
                      $('#modalPassword').modal('hide');
                      $.alertMessage('success',r.msg);
                    } else {
                      $.alertMessage('error', r.msg);
                    }
                    if(r.log){console.log(r.log)}
                  },
                  error:function(r){
                    $.ajaxErros(r.responseJSON);
                  },
                }); 
            return false;
        });
      });
    </script>
    @include('zp.components.grid')
   
      {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}
          
        {!! HTML::initTab('geral',true); !!}
        {!! HTML::inputHidden(['name'=>'id']); !!}
        {!! HTML::status(); !!}
        {!! HTML::select(
             ["name"=>"id_perfil", "label"=>"Perfil"],
             $page['data']['perfis'],
             [''=>'Escolha uma opção']
         );!!}
        {!! HTML::input(["placeholder"=>"Informe um nome", "name"=>"nome", "label"=>"Nome"]); !!}      
         {!!HTML::image(["label"=>"Foto","name"=>"imagem","size"=>3000,"dimensions"=>[300,300],"accept"=>["png","jpeg","jpg"]])!!}   
        {!! HTML::input(["placeholder"=>"Informe um e-mail", "name"=>"email", "label"=>"E-mail"]); !!}   
        {!! HTML::endTab(); !!}

        {!! HTML::buttons([
         "SAVE"=>["label"=>"Salvar"],
         'SENHA'=>['label'=>'Senha','hidden'=>true,'data-modal'=>'password',"onclick"=>'openModal();'],
         "RESET"=>["label"=>"Limpar"],
         ]);!!}

      {!!  HTML::endForm(); !!}
          {!! HTML::modal('modalPassword',['title'=>'Alterar Senha'])!!}
         <p>Preencha o formulário abaixo:</p>
         <div class="x_panel">
            <div class="x_content">
             {!! HTML::initTab('senha'); !!}
              {!! HTML::inputHidden(['name'=>'id_user']); !!}
              {!! HTML::input(["placeholder"=>"******",'type'=>'password', "name"=>"senha", "label"=>"Senha"]); !!}  
              {!! HTML::input(["placeholder"=>"******",'type'=>'password', "name"=>"conf_senha", "label"=>"Confirmar senha"]); !!}  
             {!! HTML::endTab(); !!}
            </div>
         </div>
        {!! HTML::endModal([["label"=>"Cancelar","class"=>"btn btn-default"],["label"=>"Salvar","data-save"=>"password","class"=>'btn btn-primary']])!!}
@endsection
