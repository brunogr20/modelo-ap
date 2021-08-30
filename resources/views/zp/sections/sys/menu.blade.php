@extends('zp.components.main')
@section('sectionContent')
    <script>
      objPage.grid.init={
           columns:[
           {title: "Ordem", class:'ordem-col',data: "ordem",name:"",width:"2%"},
           {title: "Nome",data: "nome",name:""},
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:true,multStatus:true,multDelete:true,}
      }; 
        
        //Validar forlulário
        objPage.form.save.validate = {
        nome: ['required',],
        icone: ['required'],
         }
        objPage.form.update.callback=()=>{
          setTimeout(()=>{
            location.reload();
          },3000);
        } 
        objPage.form.loaded.callback = (r) =>{
          if(r.status){
           if(r.data.secoes.length){
            let html='',secoes=r.data.secoes;
            for(let i in secoes){
              html+= `<li ><input type="hidden" name="order_item-${secoes[i].id}" value="${parseInt(i)+1}"><i class="fa fa-sort"></i><b>${parseInt(i)+1}</b> - ${secoes[i].titulo}</li>`;
            } 
            $(".box-secoes ul").html(html);
            $("#tab-tab2").parent().removeClass("hide");
           }
          }
        }
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
          {!! HTML::input(["placeholder"=>"Informe um nome", "name"=>"nome", "label"=>"Nome"]); !!}      
          {!!
            HTML::radio(
              ["label"=>"Icone","name"=>"icone","class"=>"flex flex-wrap"],
              [
                ["label"=>"<i class=\"fas fa-desktop\"></i> Desktop","value"=>"fas fa-desktop"],
                ["label"=>"<i class=\"fab fa-chrome\"></i> Chrome","value"=>"fab fa-chrome"],
                ["label"=>"<i class=\"fas fa-cogs\"></i> Cogs","value"=>"fas fa-cogs"],
                ["label"=>"<i class=\"fas fa-clipboard-list\"></i> Clipboard","value"=>"fas fa-clipboard-list"],
                ["label"=>"<i class=\"fas fa-chart-pie\"></i> Chart Pie","value"=>"fas fa-chart-pie"],
                ["label"=>"<i class=\"fas fa-user-friends\"></i> Users","value"=>"fas fa-user-friends"],
                ["label"=>"<i class=\"fas fa-file-word\"></i> File","value"=>"fas fa-file-word"],
                ["label"=>"<i class=\"fas fa-wrench\"></i> Wrench","value"=>"fas fa-wrench"],
                ["label"=>"<i class=\"fas fa-globe\"></i> Globe","value"=>"fas fa-globe"],
                ["label"=>"<i class=\"fas fa-key\"></i> Key","value"=>"fas fa-key"]
              ]); 
          !!}
        {!! HTML::endTab(); !!}
          
        {!! HTML::initTab('tab2'); !!}
        <div class="tip gray">
          <p>Clique no simbolo <i class="fa fa-sort"></i> e arraste para ordenar as seções.</p>
        </div>
        <div class="box-secoes">
          <ul></ul>
        </div>
        {!! HTML::endTab(); !!}
        
        {!! HTML::buttons(["SAVE"=>["label"=>"Salvar"],"RESET"=>["label"=>"Limpar"],]);!!}

    {!!  HTML::endForm(); !!}

@endsection
