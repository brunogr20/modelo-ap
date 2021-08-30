@extends('zp.components.main')
@section('sectionContent')
    {{-- <script type="module" src="{{asset('public/assets_zp/js/sys/gen.module.js')}}"></script>  --}}
    <script type="module" src="{{asset('public/assets_zp/js/sys/generator.js')}}"></script>  
    <script>
      objPage.grid.init={
           columns:[
           {title: "Id Grupo",data: "id_group_file",name:""},
           {title: "Descrição",data: "texto",name:""},
           ],
           order:[[1, "ASC"]],//faz a ordenação da grid 
           buttons:{create:true,refresh:true,order:true,search:true,delete:true,status:true,multChecked:false,multStatus:true,multDelete:true,}
      };
        //Validar forlulário
        objPage.form.save.validate = {
          tipo: ['required',]
         }
    </script>

      @include('zp.components.grid')
      {!! HTML::initForm(["name"=>"formCreation", "onsubmit"=>"$.sendGenerate();return false", "class"=>"col-md-6","tabs"=>["geral"=>["name"=>"Geral"]]]); !!}

            {!! HTML::initTab('geral',true); !!}
                {!! HTML::inputHidden(['name'=>'id']); !!}
                {!! HTML::checkbox(
                    ["label"=>"Tipo","name"=>"group_type"],
                    [
                     ["label"=>"Entidade","name"=>"entidade","value"=>"entity"],
                     ["label"=>"Seção","name"=>"secao","value"=>"section"]
                    ]);!!}
                {!! HTML::select(["name"=>"tipo_secao","hidden"=>true, "label"=>"Tipo seção"],["GRID"=>"GRID","REL"=>"RELATÓRIO",]);!!}
                {!! HTML::select(["name"=>"menu","hidden"=>true, "label"=>"Menu"],[],[''=>'Escolha uma opção']);!!}
                {!! HTML::select(["name"=>"submenu","hidden"=>true, "label"=>"Sub Menu"],[],[''=>'Escolha uma opção']);!!}
                {!! HTML::input([ "autocomplete"=>"off", "label"=>"Id Grupo  <a class=\"ef-link\" data-toggle=\"tooltip\" title=\"Identificador do grupo de arquivos, deve representar uma entidade ou seção\"><i class=\"fa fa-info\"></i></a>", "placeholder"=>"Grupo Arquivos/Nome Entidade", "name"=>"id_grupo"]); !!}
                    <div class='col-md-offset-3'>
                        <table class='view-info hide'>
                            <tr>
                                <td>Model</td> 
                                <td >app\Entities\<span class='info-text'><b data-info-text >Example</b>.php</span></td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td>app\Services\<span class='info-text'>Api<b data-info-text >Example</b>Service.php</span></td>
                            </tr>
                            <tr>
                                <td>View</td> 
                                <td>resources\views\zp\sections\<span class='info-text lower'><b data-info-text >example</b>.blade.php</span></td>
                            </tr>
                            <tr>
                                <td>Controller</td>
                                <td>app\Http\Controllers\ZP\<span class='info-text'>ZP<b data-info-text >Example</b>Controller.php</span></td>
                            </tr>
                        </table>
                    </div>
                {!! HTML::input(["label"=>"Nome da tabela  <a class=\"ef-link\" data-toggle=\"tooltip\" title=\"Esse é o nome da tabela que vai ser gerado, você é livre para alterar, mas por favor seja coerente ;-)\"><i class=\"fa fa-info\"></i></a>", "placeholder"=>"nome_da_tabela", "name"=>"table_name"]); !!}
                {!! HTML::input(["label"=>"Nome Seção","hidden"=>true,"placeholder"=>"Nome da Seção", "name"=>"nome_secao"]); !!}

                {!! HTML::openModal("modal_creator",['label'=>"Add Campos"]) !!}
                {!! HTML::modal("modal_creator",["size"=>"extra_large","title"=>"Adicione os campos"]) !!}
                    <div class="flex-extremos">
                        <div class="col-md-3">
                            <label for="">Crie a partir de um template</label>
                            <select onchange="$.loadTemplate(this)" class="form-control form-control-sm" name="" id="">
                                <option value="">Selecione um template</option>
                                <option value="noticia">Notícia</option>
                                <option value="institucional">Institucional</option>
                                <option value="galeria">Galeria</option>
                            </select>
                        </div>
                        <div class="col-md-6 center">
                            <label for="">Ou personalize</label>
                            <div>
                                <img style="width: 30px;" src="{{asset("public/assets_zp/img/seta-baixo.png")}}" alt="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-6">
                                <label>Coluna Status
                                    <input checked="checked" onclick="$.setHasStatus(this)"  type="checkbox">
                                </label>
{{--                                <select class="form-control form-control-sm" name="" id="">--}}
{{--                                    <option value="TEM">TEM</option>--}}
{{--                                    <option value="NÃO TEM">NÃO TEM</option>--}}
{{--                                </select>--}}
                            </div>
                            <div class="col-md-6">
                                <label>Coluna Ordem
                                    <input  onclick="$.setHasOrder(this)" type="checkbox">
                                </label>
{{--                                <select class="form-control form-control-sm" name="" id="">--}}
{{--                                    <option value="TEM">TEM</option>--}}
{{--                                    <option value="NÃO TEM">NÃO TEM</option>--}}
{{--                                </select>--}}
                            </div>
                        </div>

                    </div>
                    <div class="btns-group-row">
{{--                        <p > Clique para adicionar -></p>--}}
                        <div>
                            <button data-add="attribute" data-values="{}" class="btn btn-warning">Attribute</button>
                            <button data-add="input" data-values="{name:'titulo',type:'VARCHAR',length:255,label:'Título',validator:'required'}" class="btn btn-default">Input</button>
                            <button data-add="select" class="btn btn-default">Select</button>
                            <button data-add="checkbox" class="btn btn-default">CheckBox</button>
                            <button data-add="radio" class="btn btn-default">Radios</button>
                            <button data-add="textarea" data-values="{name:'titulo',type:'VARCHAR',length:'255',label:'Título',validator:'required'}" class="btn btn-default">Text area</button>
                            <button data-add="date" class="btn btn-default">Data</button>
                            <button data-add="time" class="btn btn-default">Hora</button>
                            <button data-add="image" class="btn btn-default">Imagem</button>
                            <button data-add="file" class="btn btn-default">Arquivo</button>
                            <button  class="btn btn-default">Galeria</button>
                        </div>
                    </div>
                    <div  class="modal-scroll">
                         <g id="fields"></g>


                    </div>

                {!! HTML::endModal() !!}

        {!! HTML::endTab(); !!}
        {!! HTML::buttons(["SAVE"=>["label"=>"Gerar"],"RESET"=>["label"=>"Limpar"],]); !!}
    {!!  HTML::endForm(); !!}

    
    <script src="{{asset('public/vendor/jquery-ui/jquery-ui.js')}}"></script>
    <script>
    $(function(){
     $("#fields").sortable();
    });
    </script>
@endsection
