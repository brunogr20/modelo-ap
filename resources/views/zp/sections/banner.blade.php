@extends("zp.components.main")
@section("sectionContent")
        <script>
          objPage.grid.init={
            columns:[
              {title:'ordem',data:'ordem',width:'20'},
              {title:'Título',data:'titulo'},
							{title:'Texto',data:'texto'},
							
            ],
            buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:true,multStatus:true,multDelete:true,}
          }; 
            
          //Validar formulário
          objPage.form.save.validate = {
            
            titulo:['required'],
            tipo:['required'],
						
          }
          
          objPage.form.loaded.callback=(r)=>{ 
            onEventLink(r.data.link);
          }

          function onEventLink(v){
                $('[data-form-group=externo_link]').addClass('hide');
                $('[data-form-group=legenda_link]').addClass('hide');
                $('[data-form-group=noticia_link]').addClass('hide');
              if(v=='externo'){
                $('[data-form-group=externo_link]').removeClass('hide');
                $('[name=noticia_link]').val('');
              }else if(v=='noticia'){
                $('[name=externo_link]').val('');
                $('[data-form-group=noticia_link]').removeClass('hide');
              }
              if(v){
                $('[data-form-group=legenda_link]').removeClass('hide');
              }
          }

        </script>
        @include("zp.components.grid")
      
          {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],]]); !!}
            {!! HTML::initTab("geral",true); !!}
              
              {!! HTML::inputHidden(["name"=>"id"]); !!}
              {!! HTML::status(); !!}
              {!! HTML::input(['label'=>'Título','name'=>'titulo']); !!}
							{!! HTML::textarea(['label'=>'Texto','name'=>'texto']); !!}
              {!! HTML::select(['label'=>'link','name'=>'link','onclick'=>'onEventLink(this.value)'],[
                'sobre'=>'O Programa',
                'paroquias'=>'Paróquias',
                'estabelecimentos'=>'Estabelecimentos',
                'noticia'=>'Notícia',
                'externo'=>'Externo',
                ]); !!} 
              {!! HTML::select(['label'=>'Notícia','name'=>'noticia_link','hidden'=>true],$page['data']['noticias']); !!}
              {!! HTML::input(['label'=>'Link Externo','value'=>'http://www.','name'=>'externo_link','hidden'=>true]); !!}
              {!! HTML::input(['label'=>'Legenda Link','name'=>'legenda_link','hidden'=>true]); !!}
							{{-- {!!HTML::image(['label'=>'Imagem','name'=>'imagem','size'=>3000,'dimensions'=>[300,300],'accept'=>['png','jpeg','jpg']])!!} --}}
							{{-- {!!HTML::file(['label'=>'Arquivo','name'=>'arquivo','size'=>3000,'accept'=>['pdf','txt','docx']])!!} --}}

            {!! HTML::endTab(); !!}
            {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]); !!}
          {!!  HTML::endForm(); !!}
@endsection