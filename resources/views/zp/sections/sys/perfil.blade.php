@extends('zp.components.main')
@section('sectionContent')
    <script>
      objPage.grid.init={
           columns:[
           {title: "Tipo",data: "tipo",name:""},
           {title: "Nome",data: "nome",name:""},
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:false,multStatus:true,multDelete:true,}
      }; 
        
        //Validar forlulário
        objPage.form.save.validate = {
          tipo: ['required',],
          nome: ['required',],
         }
    </script>
    @include('zp.components.grid')
   
      {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}

          {!! HTML::initTab('geral',true); !!}
          {!! HTML::inputHidden(['name'=>'id']); !!}
          {!! HTML::status(); !!}
          {!! HTML::select(
                  ["name"=>"tipo", "label"=>"Tipo"],
                  [
                  "MASTER"=>"MASTER",
                  "ADMINISTRADOR"=>"ADMINISTRADOR",
                  ]
                ); !!}
          {!! HTML::input(["placeholder"=>"Informe um nome", "name"=>"nome", "label"=>"Nome"]); !!}
        {!! HTML::endTab(); !!}
        
        {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]); !!}

    {!!  HTML::endForm(); !!}

@endsection
