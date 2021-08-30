@extends('zp.components.main')
@section('sectionContent')
    <script>
      objPage.grid.init={
           columns:[
           {title: "Ordem", class:'ordem-col',data: "ordem",name:"",width:"2%"},
           {title: "Menu",data: "menu",name:""},
            {title: "Nome",data: "nome",name:""},
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:true,multStatus:true,multDelete:true,}
      }; 
        
        //Validar forlulário
        objPage.form.save.validate = {
            id_menu: ['required',],
            nome: ['required',],
         }
        objPage.form.update.callback=()=>{
          setTimeout(()=>{
            location.reload();
          },3000);
        } 
        function loadSelectPos(sections){
          let s=$('select[name=id_posicao]'),html='';
          html+=`<option value=''>Primeira posição</option>`;
          if (sections.length) {
            for (let s of sections) {
            html+=`<option value='${s['id']}'>${s['nome']}</option>`;
            }
          }
          $('select[name=id_posicao]').html(html);
        }
        $(function () {
          $('select[name=id_menu]').change(function () {
            let id = $(this).val();
            if (id) {
              $.ajax({
                url:objPage.url.url_page + '/ajax',
                type:'POST',
                dataType:'JSON',
                data:{id,action:'load_select_pos'},
                success:function(r){
                  if (r.status) {
                    loadSelectPos(r.sections);
                  }
                  if(r.log){
                    console.log(r.log)
                  }
                },
              });
            }else{
              loadSelectPos([]);
            }
          });
        });

        objPage.form.loaded.callback = (r) => {
            if (r.status) {
              loadSelectPos(r.sections)
              if(r.data.secoes.length){
                let html='',secoes=r.data.secoes;
                for(let i in secoes){
                  html+= `<li ><input type="hidden" name="order_item-${secoes[i].id}" value="${parseInt(i)+1}"><i class="fa fa-sort"></i><b>${parseInt(i)+1}</b> - ${secoes[i].titulo}</li>`;
                } 
                $(".box-secoes ul").html(html);
                $("#tab-tab2").parent().removeClass("hide");
              }     
            }
        };
        objPage.form.new.init = () =>{
          $("#tab-tab2").parent().addClass("hide");
        }
        objPage.form.clear.init = () =>{
          $("#tab-tab2").parent().addClass("hide");
        }
        $(function(){
          $(".box-secoes ul").sortable({
                out:function(e,ui){
                  $(this).find("li").each(function(index){
                    $(this).find("b").html(index+1);
                    $(this).find("input[type='hidden']").val(index+1);
                  });
                }
          });
        });
    </script>
    @include('zp.components.grid')
   
      {!! HTML::initForm(["name"=>"formData","tabs"=>["geral"=>["name"=>"Geral"],"tab2"=>["name"=>"Seções","class"=>"hide"],]]); !!}

          {!! HTML::initTab('geral',true); !!}
          {!! HTML::inputHidden(['name'=>'id']); !!}
          {!! HTML::status(); !!}
          {!! HTML::select(["name"=>"id_menu", "label"=>"Menu"],$page['data']['menu']); !!}
          {!! HTML::select(
           ["name"=>"id_posicao", "label"=>"Posição de exibição"],
           [''=>'Primeira posição']  
          ); !!}
          {!! HTML::input(["placeholder"=>"Informe um nome", "name"=>"nome", "label"=>"Nome"]); !!}
          {!! HTML::endTab(); !!}
          
          {!! HTML::initTab('tab2'); !!}
          <div class="tip gray">
            <p>Clique no simbolo <i class="fa fa-sort"></i> e arraste para ordenar as seções.</p>
          </div>
          <div class="box-secoes">
            <ul></ul>
          </div>
          {!! HTML::endTab(); !!}
        
         {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]); !!}

    {!!  HTML::endForm(); !!} 
@endsection
