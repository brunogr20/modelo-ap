@extends("zp.components.main")
@section("sectionContent")
        <script>
          objPage.grid.init={
            columns:[

              {title:'Nome',data:'nome'},
							{title:'E-mail',data:'email'},
							{title:'Telefone',data:'telefone'},
							
            ],
            buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:false,multStatus:true,multDelete:true,}
          }; 
            
          //Validar formulário
          objPage.form.save.validate = {
            
            nome:['required'],
						email:['required,email'],
						telefone:['required'],
						
          }
          
        </script>
        @include("zp.components.grid")
      
          {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}
            {!! HTML::initTab("geral",true); !!}
              
              {!! HTML::inputHidden(["name"=>"id"]); !!}
              {!! HTML::status(); !!}
              {!! HTML::input(['label'=>'Nome','name'=>'nome']); !!}
{!! HTML::input(['label'=>'E-mail','name'=>'email']); !!}
{!! HTML::input(['label'=>'Telefone','name'=>'telefone']); !!}

            {!! HTML::endTab(); !!}
            {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]); !!}
          {!!  HTML::endForm(); !!}
@endsection