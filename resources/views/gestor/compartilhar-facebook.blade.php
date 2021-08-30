<html>
<head>
  <title>{{$data['pesquisa']['titulo']}}</title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
  <meta property="og:url"           content="{{$data['linkPesquisa']}}" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="{{$data['pesquisa']['titulo']}}" />
  <meta property="og:description"   content="{{$data['pesquisa']['descricao']}}" />
  @if($data['pesquisa']['imagem_logo'])
    <meta property="og:image" content="{{$data['pesquisa']['imagem_logo']}}" />
  @endif
</head>
<body>
  <!-- Load Facebook SDK for JavaScript -->
  {{-- <div id="fb-root"></div> --}}
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <!-- Your share button code -->
  <button type="button" class="btn btn-primary btn-lg fb-share-button"></div>

</body>
</html>