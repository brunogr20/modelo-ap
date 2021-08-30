    {{-- Scripts Padrão para todas sessões --}}
    <!-- Bootstrap -->
    <script src="{{asset('public/vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- FastClick (Para Mobile) -->
    <script src="{{asset('public/vendor/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{asset('public/vendor/nprogress/nprogress.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('public/vendor/iCheck/icheck.min.js')}}"></script>
    
    <script src="{{asset('public/assets_zp/js/sys/custom.min.js')}}" ></script>
   
   @if(in_array('dataTable',$page['imports']))
    <!-- Datatables -->
    <!-- <script src="public/vendor/datatables.net/js/jquery.dataTables.min.js"></script> -->
    <script src="{{asset('public/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    {{-- <script src="{{asset('public/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script> --}}
    <script src="{{asset('public/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{asset('public/vendor/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{asset('public/vendor/datatables-rowReorder/dataTablesRowReorder.js')}}"></script>
    <script src="{{asset('public/vendor/jszip/dist/jszip.min.js')}}"></script>
    {{-- <script src="{{asset('public/vendor/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('public/vendor/pdfmake/build/vfs_fonts.js')}}"></script> --}}
    @endif
    @if(in_array('crud',$page['imports']))
    <script src="{{asset('public/assets_zp/js/sys/crud.js')}}" ></script>
    <script src="{{asset('public/assets_zp/js/sys/grid.js')}}" ></script>

    <script src="{{asset('public/vendor/LightGallery/dist/js/plugins/picturefill.min.js')}}"></script>
    <script src="{{asset('public/vendor/LightGallery/dist/js/plugins/jquery.mousewheel.min.js')}}"></script>
    <script src="{{asset('public/vendor/LightGallery/dist/js/lightgallery.min.js')}}"></script>
    <script src="{{asset('public/vendor/LightGallery/dist/js/lightgallery-all.min.js')}}"></script>
    @endif

   
   
      