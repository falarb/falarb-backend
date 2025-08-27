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

        body {
            background-color: #F8F9FA;
            padding: 32px;
            text-align: center;
        }

        header {
            margin-bottom: 32px;
        }

        main {
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
            color: #ADB5BD;
            font-size: 12px;
            font-weight: 400;
            line-height: 18px;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ asset('img/logo.png') }}" alt="Logo Solicita Aí">
    </header>

    <main>
        <h1>Olá, {{$userName}}!</h1>

        <p class="subtitle">Aqui está o token para você confirmar seu e-mail e seguir com o processo para criar uma
            demanda: </p>

        <div class="token-box">
            <p>{{ $token }}</p>
        </div>

        <p class="info-text">Atenção: Não compartilhe esse código com ninguém</p>
    </main>
</body>

</html>