<!DOCTYPE html>
<html lang="pt-br">
<head>
        @include('site.templates.template-chamada') 
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/assets_gestor/img/favicon.png') }}">
</head>
<body>
    <section class="comprar" id="confirmarDados"> 
        <div class="boneco esquerda">
            <img class="marca esquerda" src="{{asset('public/assets_site/imgs/logoBranca.png')}}" alt="">
            <div class="imagem">
                <img class="esquerda" src="{{asset('public/assets_site/imgs/Ativo2.png')}}" alt="">
            </div>
        </div>
        <div class="passo direita">
            <div class="formulario">
                <h2 class="subtituloCompra">confirme seus dados</h2>
                <p>Empresa: <span>{{$data['infoEmpresa']['nome_empresa']}}</span></p>
                <p>CPF/CNPJ: <span>{{$data['infoEmpresa']['cpf_cnpj']}}</span></p>
                <p>Telefone: <span>{{$data['infoEmpresa']['telefone_empresa']}}</span></p>
                <p>Nome do contato: <span>{{$data['infoEmpresa']['responsavel']}}</span></p>
                <p>E-mail: <span>{{$data['infoUsuario']['email']}}</span></p>
                <p>Telefone: <span>{{$data['infoUsuario']['telefone']}}</span></p>
                <p>UF: <span>{{$data['infoEmpresa']['uf']}}</span></p>
                <h1 class="tituloCompra">Plano 30 dias</h1>
                <div class="botoes">
                    <button style="width: 100%;margin: 0;">Realizar pagamento</button>
                </div>
                <div class="botoes" style="justify-content: flex-start;">
                    <button class="mt-5" onclick="goBack()">voltar</button>
                </div>
            </div>
        </div>
    </section>
</body>


<script>
    function goBack() {
    window.history.back()
}
    setTimeout(function () {
       
         $.ajax({    
         url: objPage.url.url_page+"/get/confirma-dados",
         type: 'POST',
         dataType: 'JSON',
         data: data,
         success: function (response) {
           if (response.data) {
            console.log(data);
           } else {
             $.alertMessage('error', response.msg);
               }
             if(response.log){console.log(response.log)}
             },
         });    }, 0);

</script>