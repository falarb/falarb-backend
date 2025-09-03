<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Validação de E-mail</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

        .container {
            background-color: #F8F9FA;
            padding: 32px;
            text-align: center;
        }

        header {
            margin-bottom: 32px;
            text-align: center;
        }

        .main {
            background-color: #FFFFFF;
            padding: 64px 0;
            border-radius: 6px;
        }

        h1 {
            color: #495057;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 0.4px;
            margin-bottom: 32px;
            text-align: center;
        }

        p {
            text-align: center;
        }

        .subtitle {
            color: #495057;
            font-size: 16px;
            font-weight: 400;
            line-height: 22px;
            margin-bottom: 32px;
        }

        .token-box {
            background: #E7F5FF;
            width: fit-content;
            padding: 18px 32px 18px 42px;
            margin: 0 auto 32px auto;
            border-radius: 6px;
        }

        .token-box p {
            color: #495057;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 20px;
        }

        .info-text {
            color: #495057;
            font-size: 16px;
            font-weight: 400;
            line-height: 22px;
        }

        a {
            display: block;
            align-content: center;
            margin: 32px auto 0 auto;
            width: 278px;
            height: 48px;
            padding: 8px 24px;
            text-align: center;
            border-radius: 8px;
            border: none;
            background: #3051FF;

            color: #FFF !important;
            font-size: 16px;
            font-weight: 700;
            line-height: 22px;
            letter-spacing: 0.3px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="https://raw.githubusercontent.com/falarb/falarb-backend/main/public/img/logo.png"
                alt="Logo Solicita Aí">
        </header>
        <div class="main">
            <h1>Sua solicitação foi criada com sucesso!</h1>
            <p class="subtitle">Olá, {{$userName}}!</p>
            <p class="subtitle">
                A sua demanda já foi registrada e será avaliada em breve. <br />
                Aqui está seu token para verificar o andamento da sua solicitação
            </p>
            <div class="token-box">
                <p>{{ $token }}</p>
            </div>
            <p class="info-text">Ou se quiser, pode clicar no botão abaixo para acessar a tela de acompanhamento:</p>
            <a href="{{ env('WEB_URL') }}/visualizar-solicitacao/{{ $token }}">
                <p style="margin-top: 5px">Ver solicitação</p>
            </a>
        </div>
    </div>
</body>

</html>