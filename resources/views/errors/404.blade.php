<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Página não encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @font-face{src:url('{{asset("public/zp/fonts/Nunito-Regular.ttf")}}');font-family: erro;}
        article{color:#fff;font-family:erro;margin:1.5rem 0 0 7rem;}
        article h1{font-size:6.5rem;margin-bottom:0;}
        article h1::after{content: '';width: 50px;max-width:90px;height: 2px;background:#08cf53;display: block;left: 0;transition: all .5s linear .1s;position: relative;}
        article h1:hover::after{transform:scaleX(2) translateX(15%);}
        article p{font-size:1.5rem;max-width:40%;}
        .btn-success{color:#fff;background-color:#5cb85c;border-color:#4cae4c;}
        .btn {display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center; white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;  user-select: none;background-image: none; border: 1px solid transparent; border-radius: 4px;}
        .bg-azul{
            background: url('{{asset("public/zp/img/listra.png")}}')repeat-y;
            background-color:#01032a;
            background-position-x: right;
        }
        @media only screen and (max-width:500px){
            .bg-azul > article{margin-left: .5rem;}
            article p {max-width: 60%;}
        }
    </style>
</head>
<body class="bg-azul">
<article>
    <h1 >404</h1>
    <p>Desculpe, a página que você está procurando não foi encontrada.</p>
    <a herf="" class="btn btn-success"><i class="fa fa-home"></i> Ir para Home</a>
</article>
</body>
</html>