
    <div id='main-grid' class="col-md-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          @if(count($page['section_active']))
          <h2>Você está em
           <small>
           <i>{{$page['section_active']['menu']}}</i> > 
           @if(!empty($page['section_active']['submenu']))
           <i>{{$page['section_active']['submenu']}}</i> > 
           @endif
           <b><i><a href='{{$page['section_active']['url']}}'>{{$page['section_active']['secao']}}</a></i></b>
           </small>
          </h2>
          <div class="clearfix"></div>
          @endif
        </div>
        <div class="x_content">
        {{-- table-striped table-hover table-bordered display compact --}}
          <table id="tb-grade-principal" class="table  table-hover " cellspacing="0" width="100%">
            
          </table>
        </div>
      </div>
    </div>

<div id="myinfo" aria-show='false' class='myinfo'>
  <div class='myinfo-content'>
    <a onclick="$.infoLoad('hide')" class='fa fa-times info-close hide'></a>
    <div style="margin:10px auto; display:block;" id="myinfo-body" class="myinfo-body"
        data-src-done="{{ asset('public/assets_zp/img/check.svg') }}" 
        data-src-error="{{ asset('public/assets_zp/img/error.png') }}" 
        data-src-before="{{ asset('public/assets_zp/img/load.svg') }}">
      <p>Processando...</p>
    </div>
  </div>
</div>

<script>

$("[data-toggle='info-modal']").on("click",function(){
  let t = $(this).attr("data-target");
  let show = ($(t).attr("aria-show") == "false")? false:true;
  $(t).attr("aria-show",!show);
});

</script>


