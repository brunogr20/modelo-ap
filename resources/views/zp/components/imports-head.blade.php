 
    <link href="{{asset('public/vendor/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    {{-- <link href="{{asset('public/vendor/bootstrap-4.3.1/css/bootstrap.min.css')}}" rel="stylesheet"> --}}
    {{-- <link href="{{asset('public/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"> --}}
    <link href="{{asset('public/vendor/fontawesome-free-5.8.2/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('public/vendor/nprogress/nprogress.css')}}" rel="stylesheet">
    <link href="{{asset('public/vendor/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets_zp/css/custom.min.css')}}" rel="stylesheet">
    @if(Auth::guard('zp')->user())
    <script>objPage.isDark=false</script>
      @if(Auth::guard('zp')->user()->tema=='DARK')
       <script>objPage.isDark=true</script>
       <link href="{{asset('public/assets_zp/css/dark.css')}}" data-css='tema' rel="stylesheet">
      @endif
     @endif
    <link href="{{asset('public/vendor/alertify/css/alertify.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/vendor/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    
    <link href="{{asset('public/assets_zp/css/novotema.css')}}" rel="stylesheet">
    <!-- Scripts das dependencias padrão -->
    <script src="{{asset('public/vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('public/vendor/jquery-mousewheel/jquery.mousewheel.min.js')}}"></script>

    <script src="{{asset('public/vendor/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('public/vendor/alertify/alertify.min.js')}}"></script>
    <script src="{{asset('public/assets_zp/js/sys/message.js')}}"></script>
    <script src="{{asset('public/assets_zp/js/sys/core.js')}}"></script>
    <script src="{{asset('public/assets_zp/js/sys/annotation.js')}}"></script>
    
   @if(in_array("charts",$page['imports']))
    <script src="{{asset('public/vendor/amcharts4/core.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/charts.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/dataviz.js')}}"></script>
    <script src="{{asset('public/vendor/amcharts4/themes/animated.js')}}"></script>
   @endif
   @if(in_array("dataTable",$page['imports']))
      {{-- DATATABLES --}}
      <link href="{{asset('public/vendor/datatables-rowReorder/rowreorder.css')}}" rel="stylesheet">
      <script src="{{asset('public/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
      <link rel="stylesheet" type="text/css" href="{{asset('public/vendor/datatables.net-responsive/datatables-responsive.css')}}" />
      <script src="{{asset('public/vendor/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
      <script>objPage['grid']={init:[],lang:{"sEmptyTable": "Nenhum registro encontrado", "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros", "sInfoEmpty": "Mostrando 0 até 0 de 0 registros", "sInfoFiltered": "(Filtrados de _MAX_ registros)", "sInfoPostFix": "", "sInfoThousands": ".", "sLengthMenu": "Mostrar _MENU_", "sLoadingRecords": "Carregando...", "sProcessing": "Processando...", "sZeroRecords": "Nenhum registro encontrado", "sSearch": "<span class='btn btn-default btn-search'><i class='fa fa-search'></i></span>", "oPaginate": {"sNext": "<i class='fa fa-angle-right '></i>", "sPrevious": "<i class='fa fa-angle-left '></i>", "sFirst": "<i class='fa fa-angle-double-left '></i>", "sLast": "<i class='fa fa-angle-double-right '></i>"}, "oAria": {"sSortAscending": ": Ordenar colunas de forma ascendente", "sSortDescending": ": Ordenar colunas de forma descendente"}}}</script>
   @endif
   @if(in_array("crud",$page['imports'])||in_array("rel",$page['imports']))
    {{-- bootstrap daterange picker --}}
    <link href="{{asset('public/vendor/datetimepicker/build/css/datetimepicker.css')}}" rel="stylesheet">
    <script src="{{asset('public/vendor/moment/moment.js')}}"></script>
    <script src="{{asset('public/vendor/moment/locale/pt-br.js')}}"></script>
    <script src="{{asset('public/vendor/datetimepicker/build/js/datetimepicker.min.js')}}"></script>
    <script src="{{asset('public/assets_zp/js/sys/form-validation.js')}}"  ></script>
    <script src="{{ asset('public/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
     objPage['dataForm']=[];
     objPage['form']={new:{init:()=>{}},create:{init:()=>{},callback:()=>{}},update:{init:()=>{},callback:()=>{}},loaded:{init:()=>{},callback:()=>{}},save:{validate:{}},clear:{init:()=>{},callback:()=>{}},deleteFile:{init:()=>{}} };
    </script>
   @endif
   @if(in_array("crud",$page['imports']))
      <script src="{{asset('public/vendor/jquery-mask/src/jquery.mask.js')}}"></script>
      <!-- LightGallery  -->
      <link href="{{asset('public/vendor/LightGallery/dist/css/lightgallery.min.css')}}" rel="stylesheet"/>
      <script>
       if(objPage['grid']===undefined){
        objPage['grid']={init:[]};  
       }
       objPage['grid']= Object.assign(objPage['grid'],{dsGridSelected:[],orderingTable:false,refreshing:false,ajax:{init:()=>{},callback:()=>{}}});
      </script>
    @endif
    @if(in_array("rel",$page['imports']))
        <script src="{{asset('public/vendor/jquery-mask/src/jquery.mask.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('public/vendor/datatables.net-fixedheader/dataTables.fixedHeader.css')}}"/>
        <script src="{{asset('public/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
       <script>
       objPage['rel']={init:[]};
       </script>
    @endif

    
      
