
@extends('zp.components.main')
@extends('zp.components.rel-grid')
@section('sectionReportFilter')
    <script>
        //Validar forlulário
        objPage.form.save.validate = {
         //
         }
        objPage.rel.init={
          columns:[
            {title:"Data",data:"dataCadastro",name:""},
            {title:"Usuário",data:"nomeUsuario",name:""},
            {title:"Seção",data:"nomeSecao",name:""},
            {title:"Ação",data:"acao",name:""},
          ],
          buttons:[],
        }
    </script>
      <fieldset>
            <legend>Filtros</legend>
          {!! HTML::initFormRel(); !!}
          {!!
            HTML::select(
                ["name"=>"id_usuario", "label"=>"Usuário"],
                 $page['data']['users']
                ); 
                !!}
          {!!
            HTML::select(
              ["name"=>"id_secao", "label"=>"Seção"],
              $page['data']['secoes']
              ); 
          !!}
      {!! HTML::endFormRel(); !!}
      </fieldset>
@endsection



